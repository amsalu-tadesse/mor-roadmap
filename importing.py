import pandas as pd
from sqlalchemy import create_engine, text
import sys
import re

# 1. Database Connection
engine = create_engine('mysql+pymysql://root:amsaleka@localhost/mor')

# 2. Load File
file_path = '/home/eca/Documents/DRIMT/2026_Partnership_Coordination_Projects_Register_MASTER_FINAL.xlsx'
try:
    df = pd.read_excel(file_path, sheet_name='Data')
except Exception:
    df = pd.read_excel(file_path)

df.columns = [str(col).strip() for col in df.columns]

# --- COLUMN MAPPING ---
COL_THEME   = 'Strategic Themes'
COL_OBJ     = 'Strategic Objectives'
COL_INIT    = 'Initiatives'
COL_DIR     = 'Directorate'
COL_PARTNER = 'Partner'
COL_STATUS  = 'Status'
COL_START   = 'Start Date'
COL_END     = 'End Date'
COL_BUDGET  = 'Budget'
COL_EXP     = 'Expenditure'
COL_COMP    = '%Completion'
COL_REQ     = 'Request'
COL_ACT     = 'Activities Supported /Planned'

# --- CLEANING & HIERARCHY ---
for col in [COL_THEME, COL_OBJ, COL_INIT, COL_DIR]:
    if col in df.columns:
        df[col] = df[col].ffill().str.strip()

# Regex to remove "1. ", "1.1 ", "2. " etc.
df['Init_Clean'] = df[COL_INIT].str.replace(r'^\d+(\.\d+)*\s*\.?\s*', '', regex=True)

# Date Fix Logic
def fix_excel_dates(value):
    if pd.isna(value) or value == 0 or str(value).strip() in ["", "0"]:
        return None
    if isinstance(value, (int, float)):
        if value > 40000:
            return pd.to_datetime(value, unit='D', origin='1899-12-30', errors='coerce')
        return None
    return pd.to_datetime(value, errors='coerce')

if COL_START in df.columns:
    df[COL_START] = df[COL_START].apply(fix_excel_dates)
if COL_END in df.columns:
    df[COL_END] = df[COL_END].apply(fix_excel_dates)

def sync_lookup(table_name, excel_col):
    if excel_col not in df.columns: return
    new_names = set(df[excel_col].dropna().unique())
    try:
        existing = pd.read_sql(f'SELECT name FROM {table_name}', con=engine)
        existing_names = set(existing['name'].tolist())
    except: existing_names = set()
    to_insert = list(new_names - existing_names)
    if to_insert:
        pd.DataFrame({'name': to_insert}).to_sql(table_name, con=engine, if_exists='append', index=False)

def save_to_db():
    print("Beginning fresh database truncation...")

    # Comprehensive list of all tables targeted by the script
    tables_to_truncate = [
        'support_requests',
        'initiatives',
        'objectives',
        'themes',
        'directorates',
        'partners',
        'request_statuses',
        'initiative_statuses'
    ]

    with engine.connect() as conn:
        # 1. Turn off foreign key constraint checks
        conn.execute(text("SET FOREIGN_KEY_CHECKS = 0;"))

        # 2. Run truncation loop across all database tables
        for table in tables_to_truncate:
            print(f"Truncating table: {table}")
            conn.execute(text(f"TRUNCATE TABLE {table};"))

        # 3. Turn foreign key constraint checks back on
        conn.execute(text("SET FOREIGN_KEY_CHECKS = 1;"))
        conn.commit()

    print("Database cleared successfully.")

    # --- A. Sync Lookup Tables ---
    print("Syncing Lookup Tables...")
    sync_lookup('partners', COL_PARTNER)
    sync_lookup('request_statuses', COL_REQ)
    sync_lookup('initiative_statuses', COL_STATUS)
    sync_lookup('themes', COL_THEME)
    sync_lookup('directorates', COL_DIR)

    db_partners = pd.read_sql('SELECT id, name FROM partners', con=engine)
    db_req_status = pd.read_sql('SELECT id, name FROM request_statuses', con=engine)
    db_init_status = pd.read_sql('SELECT id, name FROM initiative_statuses', con=engine)
    db_themes = pd.read_sql('SELECT id, name FROM themes', con=engine)
    db_dirs = pd.read_sql('SELECT id, name FROM directorates', con=engine)

    # --- B. Sync Objectives ---
    print("Syncing Objectives...")
    obj_raw = df[[COL_THEME, COL_OBJ]].drop_duplicates()
    obj_map = obj_raw.merge(db_themes, left_on=COL_THEME, right_on='name')
    obj_df = obj_map.rename(columns={'id': 'theme_id', COL_OBJ: 'oname'})[['oname', 'theme_id']]
    try:
        ex_obj = pd.read_sql('SELECT name, theme_id FROM objectives', con=engine)
        obj_df = obj_df[~obj_df.set_index(['oname', 'theme_id']).index.isin(ex_obj.set_index(['name', 'theme_id']).index)]
    except: pass
    if not obj_df.empty:
        obj_df.rename(columns={'oname': 'name'}).to_sql('objectives', con=engine, if_exists='append', index=False)
    db_objs = pd.read_sql('SELECT id, name, theme_id FROM objectives', con=engine)

    # --- C. Sync Initiatives ---
    print("Syncing Initiatives...")
    init_map = df.copy()
    init_map = init_map.merge(db_themes, left_on=COL_THEME, right_on='name')
    init_map = init_map.merge(db_objs, left_on=[COL_OBJ, 'id'], right_on=['name', 'theme_id'], suffixes=('', '_objdb'))
    init_map = init_map.merge(db_dirs, left_on=COL_DIR, right_on='name', suffixes=('', '_dbdir'))

    # Merge for Partner and Status IDs safely
    init_map = init_map.merge(db_partners.rename(columns={'id':'pid', 'name':'pname'}), left_on=COL_PARTNER, right_on='pname', how='left')
    init_map = init_map.merge(db_init_status.rename(columns={'id':'sid', 'name':'sname'}), left_on=COL_STATUS, right_on='sname', how='left')

    final_initiatives = pd.DataFrame({
        'name': init_map['Init_Clean'],
        'objective_id': init_map['id_objdb'],
        'directorate_id': init_map['id_dbdir'],
        'partner_id': init_map['pid'],
        'initiative_status_id': init_map['sid'],
        'start_date': init_map[COL_START],
        'end_date': init_map[COL_END],
        'budget': init_map.get(COL_BUDGET, None),
        'expenditure': init_map.get(COL_EXP, None),
        'completion': init_map.get(COL_COMP, None),
        'request': init_map.get(COL_REQ, None)
    }).drop_duplicates()

    try:
        # Pull existing initiative names from the database
        ex_init = pd.read_sql('SELECT name FROM initiatives', con=engine)
        existing_names = set(ex_init['name'].dropna().tolist())

        # Filter final_initiatives to keep only rows whose name is NOT in the database
        final_initiatives = final_initiatives[~final_initiatives['name'].isin(existing_names)]
    except Exception as e:
        print(f"Skipping unique name check due to error: {e}")

    if not final_initiatives.empty:
        final_initiatives.to_sql('initiatives', con=engine, if_exists='append', index=False)
        print(f"Successfully inserted {len(final_initiatives)} new initiatives.")
    else:
        print("All initiatives already exist in the database. Skipping insertion.")

    # --- D. Sync SUPPORT REQUESTS
    print("Syncing Support Requests...")
    db_inits = pd.read_sql('SELECT id, name, objective_id FROM initiatives', con=engine)

    if COL_ACT in df.columns:
        sup_map = df.copy()

        # 1. Join with DB Objectives to get the Parent ID
        sup_map = sup_map.merge(db_objs, left_on=COL_OBJ, right_on='name', suffixes=('', '_o'))

        # 2. Map to Initiatives using BOTH name and Objective ID (The Tie-Breaker)
        sup_map = sup_map.merge(db_inits,
                                left_on=['Init_Clean', 'id'],
                                right_on=['name', 'objective_id'],
                                suffixes=('', '_init'))

        # 3. Map Partners and Statuses
        sup_map = sup_map.merge(db_partners.rename(columns={'id':'part_id'}), left_on=COL_PARTNER, right_on='name', suffixes=('', '_p'))
        sup_map = sup_map.merge(db_req_status.rename(columns={'id':'req_id'}), left_on=COL_REQ, right_on='name', suffixes=('', '_r'))

        # 4. Construct Final Data and Groupby to KILL duplicates
        final_support = pd.DataFrame({
            'initiative_id': sup_map['id_init'],
            'partner_id': sup_map['part_id'],
            'activities': sup_map[COL_ACT],
            'request_status_id': sup_map['req_id'],
            'priority': 'M'
        })

        final_support = final_support.groupby(['activities', 'partner_id'], as_index=False).first()

        final_support.to_sql('support_requests', con=engine, if_exists='append', index=False)
        print(f"Final Success! Added {len(final_support)} clean Support Requests.")

if __name__ == "__main__":
    save_to_db()
