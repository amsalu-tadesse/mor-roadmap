import openpyxl
import re
from datetime import datetime
import mysql.connector

def parse_date(date_val):
    """Safely normalizes varied date objects/strings to YYYY-MM-DD format."""
    if not date_val:
        return None
    if isinstance(date_val, datetime):
        return date_val.date().strftime('%Y-%m-%d')
    for fmt in ('%d-%b-%Y', '%d-%b-%y', '%Y-%m-%d', '%m/%d/%Y'):
        try:
            return datetime.strptime(str(date_val).strip(), fmt).date().strftime('%Y-%m-%d')
        except ValueError:
            continue
    return None

def clean_completion(comp_val):
    """Converts percentages or strings safely to a numeric float value."""
    if comp_val is None or str(comp_val).strip() == "":
        return 0.0
    try:
        val_str = str(comp_val).replace('%', '').strip()
        return float(val_str)
    except ValueError:
        return 0.0

def parse_split_partners(partners_string):
    """Splits partner strings by commas or forward slashes, stripping out extra spacing."""
    if not partners_string or str(partners_string).strip() == "":
        return []
    raw_list = re.split(r'[,/]', str(partners_string))
    return [" ".join(p.strip().split()) for p in raw_list if p.strip()]

def parse_split_directorates(directorate_string):
    """Splits directorate strings strictly by forward slash (/), stripping out extra spacing."""
    if not directorate_string or str(directorate_string).strip() == "":
        return []
    raw_list = re.split(r'[/]', str(directorate_string))
    return [" ".join(d.strip().split()) for d in raw_list if d.strip()]

def get_or_create(cursor, table_name, name_value, user_id, now_str, extra_fields=None):
    """Checks if a record exists by name. If not, inserts it dynamically and returns its ID."""
    if not name_value or str(name_value).strip() == "":
        return None

    cleaned_name = str(name_value).strip()

    query_select = f"SELECT id FROM `{table_name}` WHERE `name` = %s LIMIT 1"
    cursor.execute(query_select, (cleaned_name,))
    result = cursor.fetchone()
    if result:
        return result[0] # Returns the clean primary ID integer directly

    columns = ["name", "created_at", "updated_at", "created_by"]
    values = [cleaned_name, now_str, now_str, user_id]

    if extra_fields:
        for field_col, field_val in extra_fields.items():
            columns.append(field_col)
            values.append(field_val)

    col_str = ", ".join([f"`{c}`" for c in columns])
    placeholders = ", ".join(["%s"] * len(values))

    query_insert = f"INSERT INTO `{table_name}` ({col_str}) VALUES ({placeholders})"
    cursor.execute(query_insert, tuple(values))
    return cursor.lastrowid

def get_or_create_pivot(cursor, activity_id, partner_id, now_str):
    """Ensures duplicate pivot records are not generated in activity_interested_partner."""
    query_select = """
        SELECT id FROM activity_interested_partner
        WHERE activity_id = %s AND partner_id = %s LIMIT 1
    """
    cursor.execute(query_select, (activity_id, partner_id))
    result = cursor.fetchone()
    if result:
        return result[0]

    query_insert = """
        INSERT INTO activity_interested_partner (activity_id, partner_id, created_at, updated_at)
        VALUES (%s, %s, %s, %s)
    """
    cursor.execute(query_insert, (activity_id, partner_id, now_str, now_str))
    return cursor.lastrowid

def get_or_create_directorate_initiative(cursor, initiative_id, directorate_id, now_str):
    """Ensures duplicate pivot records are not generated in directorate_initiative."""
    query_select = """
        SELECT id FROM directorate_initiative
        WHERE initiative_id = %s AND directorate_id = %s LIMIT 1
    """
    cursor.execute(query_select, (initiative_id, directorate_id))
    result = cursor.fetchone()
    if result:
        return result[0]

    query_insert = """
        INSERT INTO directorate_initiative (initiative_id, directorate_id, created_at, updated_at)
        VALUES (%s, %s, %s, %s)
    """
    cursor.execute(query_insert, (initiative_id, directorate_id, now_str, now_str))
    return cursor.lastrowid

def get_or_create_activity_directorate(cursor, activity_id, directorate_id, now_str):
    """Ensures duplicate pivot records are not generated in activity_directorate."""
    query_select = """
        SELECT id FROM activity_directorate
        WHERE activity_id = %s AND directorate_id = %s LIMIT 1
    """
    cursor.execute(query_select, (activity_id, directorate_id))
    result = cursor.fetchone()
    if result:
        return result[0]

    query_insert = """
        INSERT INTO activity_directorate (activity_id, directorate_id, created_at, updated_at)
        VALUES (%s, %s, %s, %s)
    """
    cursor.execute(query_insert, (activity_id, directorate_id, now_str, now_str))
    return cursor.lastrowid
def migrate_wps_to_mysql():
    db_config = {
        'host': 'localhost',
        'user': 'root',
        'password': 'amsaleka',
        'database': 'mor',
        'charset': 'utf8mb4'
    }

    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor()

    current_theme_id = None
    current_objective_id = None
    current_initiative_id = None

    now_str = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    user_id = 1

    try:
        # ----------------------------------------------------------------
        # 1. TRUNCATE ENGINE (Safely clear old structural elements)
        # ----------------------------------------------------------------
        print("Initializing cleanup engine... Flushing managed tables.")
        cursor.execute("SET FOREIGN_KEY_CHECKS = 0;")

        tables_to_clean = [
            'activity_interested_partner',
            'activity_directorate',
            'directorate_initiative',
            'activities',
            'initiatives',
            'objectives',
            'themes',
            'partners',
            'activity_statuses',
            'directorates',
         ]
        for table in tables_to_clean:
            cursor.execute(f"TRUNCATE TABLE `{table}`;")
            print(f" -> Cleared table and reset IDs for: {table}")

        cursor.execute("SET FOREIGN_KEY_CHECKS = 1;")
        print("Database cleanup complete. Fresh schema slate ready.")
        print("=" * 60)

        # ----------------------------------------------------------------
        # 2. ROW EXTRACTION CYCLE
        # ----------------------------------------------------------------
        wb = openpyxl.load_workbook('2026_Partnership_Coordination_Projects_Register_MASTER_FINAL.xlsx', data_only=True)
        sheet = wb.active

        row_count = 0

        for row in sheet.iter_rows(min_row=2):
            vals = [cell.value for cell in row]
            if not any(v is not None and str(v).strip() != "" for v in vals):
                continue

            row_count += 1

            # Map values based on your clean array column layouts
            theme_raw       = vals[0] if len(vals) > 0 else None
            objective_raw   = vals[1] if len(vals) > 1 else None
            initiative_raw  = vals[2] if len(vals) > 2 else None
            activity_raw    = vals[3] if len(vals) > 3 else None
            start_date_raw  = vals[4] if len(vals) > 4 else None
            end_date_raw    = vals[5] if len(vals) > 5 else None
            budget_raw      = vals[6] if len(vals) > 6 else None
            expenditure_raw = vals[7] if len(vals) > 7 else None
            directorate_raw = vals[8] if len(vals) > 8 else None
            completion_raw  = vals[9] if len(vals) > 9 else None
            partners_raw    = vals[10] if len(vals) > 10 else None
            status_raw      = vals[11] if len(vals) > 11 else None
            request_type_raw= vals[12] if len(vals) > 12 else None

            # ----------------------------------------------------------------
            # 3. UPSERT RELATION HIERARCHY
            # ----------------------------------------------------------------
            if theme_raw and str(theme_raw).strip() != "":
                current_theme_id = get_or_create(cursor, "themes", theme_raw, user_id, now_str)

            if objective_raw and str(objective_raw).strip() != "":
                extra_obj = {"theme_id": current_theme_id} if current_theme_id else {}
                current_objective_id = get_or_create(cursor, "objectives", objective_raw, user_id, now_str, extra_fields=extra_obj)

            if initiative_raw and str(initiative_raw).strip() != "":
                extra_init = {"objective_id": current_objective_id, "theme_id": current_theme_id, 'implementation_status_id': 3}
                current_initiative_id = get_or_create(cursor, "initiatives", initiative_raw, user_id, now_str, extra_fields=extra_init)

            # ----------------------------------------------------------------
            # 4. RESOLVE FORWARD SLASH (/) SPLITTING FOR DIRECTORATES
            # ----------------------------------------------------------------
            current_row_directorate_ids = []

            if directorate_raw and str(directorate_raw).strip() != "":
                directorate_names = parse_split_directorates(directorate_raw)

                for dir_name in directorate_names:
                    dir_id = get_or_create(cursor, "directorates", dir_name, user_id, now_str)
                    if dir_id:
                        current_row_directorate_ids.append(dir_id)

                        # Populate relationship entry inside directorate_initiative
                        if current_initiative_id:
                            get_or_create_directorate_initiative(cursor, current_initiative_id, dir_id, now_str)

            # ----------------------------------------------------------------
            # 5. STANDALONE TABLE UPSERTS (STATUS)
            # ----------------------------------------------------------------
            activity_status_id = None
            if status_raw and str(status_raw).strip() != "":
                activity_status_id = get_or_create(cursor, "activity_statuses", status_raw, user_id, now_str)

            # ----------------------------------------------------------------
            # 6. INSERT BASE ACTIVITY RECORD
            # ----------------------------------------------------------------
            if activity_raw and str(activity_raw).strip() != "":
                if not current_initiative_id:
                    print(f"Skipping row {row_count + 1}: activity cannot bind without a parent initiative.")
                    continue

                start_dt = parse_date(start_date_raw)
                end_dt = parse_date(end_date_raw)
                budget = str(budget_raw).strip() if budget_raw is not None else None
                expenditure = str(expenditure_raw).strip() if expenditure_raw is not None else None
                completion = clean_completion(completion_raw)
                request_type = str(request_type_raw).strip() if request_type_raw is not None else None

                query_insert_activity = """
                    INSERT INTO activities (
                        initiative_id, activities, priority,
                        start_date, end_date, budget, expenditure,
                        completion, activity_status_id, request_type,
                        created_by, updated_by, created_at, updated_at
                    ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
                """

                cursor.execute(query_insert_activity, (
                    current_initiative_id,
                    str(activity_raw).strip(),
                    'M',
                    start_dt,
                    end_dt,
                    budget,
                    expenditure,
                    completion,
                    activity_status_id,
                    request_type,
                    user_id,
                    user_id,
                    now_str,
                    now_str
                ))

                activity_id = cursor.lastrowid

                # ----------------------------------------------------------------
                # 7. RESOLVE SPLIT AND MULTI-PARTNER ASSIGNMENTS (PIVOT ENGINE)
                # ----------------------------------------------------------------
                if partners_raw and str(partners_raw).strip() != "":
                    partner_names = parse_split_partners(partners_raw)
                    for partner_name in partner_names:
                        p_id = get_or_create(cursor, "partners", partner_name, user_id, now_str)
                        if p_id:
                            get_or_create_pivot(cursor, activity_id, p_id, now_str)

                # ----------------------------------------------------------------
                # 8. MAP SPLIT DIRECTORATES TO ACTIVITY PIVOT MATRIX
                # ----------------------------------------------------------------
                for d_id in current_row_directorate_ids:
                    get_or_create_activity_directorate(cursor, activity_id, d_id, now_str)

            # Keep interface visually active during long loop executions
            if row_count == 1 or row_count % 10 == 0:
                print(f" -> Processed data entries for row index #{row_count + 1}...")

        # ----------------------------------------------------------------
        # 9. CLEAN LEADING NUMBERS AND DOTS FROM INITIATIVES TABLE
        # ----------------------------------------------------------------
        print("\nCleaning up leading numbers and dots from initiatives...")
        query_clean_initiatives = """
            UPDATE initiatives
            SET name = REGEXP_REPLACE(name, '^[[:space:]]*[0-9]+(\\.[0-9]+)*\\.[[:space:]]*', '')
            WHERE name REGEXP '^[[:space:]]*[0-9]+(\\.[0-9]+)*\\.';
        """
        cursor.execute(query_clean_initiatives)
        print(f" -> Cleaned initiative rows: {cursor.rowcount}")

        # ----------------------------------------------------------------
        # 10. BACKFILL FIRST/LATEST PARTNER ID INTO THE ACTIVITIES TABLE
        # ----------------------------------------------------------------
        print("Backfilling partner reference links into the activities table...")
        query_backfill_partners = """
            UPDATE activities a
            JOIN (
                SELECT activity_id, partner_id,
                       ROW_NUMBER() OVER (PARTITION BY activity_id ORDER BY id ASC) as rn
                FROM activity_interested_partner
            ) ranked_partners ON a.id = ranked_partners.activity_id
            SET a.partner_id = ranked_partners.partner_id
            WHERE ranked_partners.rn = 1;
        """
        cursor.execute(query_backfill_partners)
        print(f" -> Backfilled partner associations across: {cursor.rowcount} activity rows")

        # Commit all structured records and changes safely together
        conn.commit()
        print(f"\nMigration Successful! Managed to cleanly process {row_count} database rows with directorates.")

    except Exception as err:
        # Revert changes if an unhandled anomaly breaks execution halfway
        conn.rollback()
        try:
            cursor.execute("SET FOREIGN_KEY_CHECKS = 1;")
        except:
            pass
        print(f"\nDatabase transaction canceled and rolled back due to failure: {err}")
    finally:
        # Gracefully shut down active statement contexts and connection links
        cursor.close()
        conn.close()

if __name__ == "__main__":
    migrate_wps_to_mysql()
