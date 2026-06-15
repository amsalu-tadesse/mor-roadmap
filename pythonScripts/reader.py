import mysql.connector
import html

def export_database_to_html(html_output_file):
    # Database connection parameters
    db_config = {
        'host': 'localhost',
        'user': 'root',
        'password': 'amsaleka',
        'database': 'mor',
        'charset': 'utf8mb4'
    }

    try:
        conn = mysql.connector.connect(**db_config)
        cursor = conn.cursor(dictionary=True) # Fetch results as dictionary for clean reading
        
        # 1. Execute relational JOIN query to fetch and link everything back together
        query = """
            SELECT 
                t.name AS theme,
                o.name AS objective,
                i.name AS initiative,
                a.id AS activity_id,
                a.activities,
                a.start_date,
                a.end_date,
                a.budget,
                a.expenditure,
                a.completion,
                s.name AS status
            FROM activities a
            LEFT JOIN initiatives i ON a.initiative_id = i.id
            LEFT JOIN objectives o ON i.objective_id = o.id
            LEFT JOIN themes t ON o.theme_id = t.id
            LEFT JOIN activity_statuses s ON a.activity_status_id = s.id
            ORDER BY t.id, o.id, i.id, a.id
        """
        cursor.execute(query)
        rows = cursor.fetchall()

        html_rows = ""
        row_counter = 0

        # Variables to track and group repeating parent text values
        prev_theme = None
        prev_objective = None
        prev_initiative = None

        for row in rows:
            row_counter += 1
            act_id = row['activity_id']

            # 2. Fetch and reconstruct split partners for this specific activity
            partner_query = """
                SELECT p.name 
                FROM activity_interested_partner aip
                JOIN partners p ON aip.partner_id = p.id
                WHERE aip.activity_id = %s
            """
            cursor.execute(partner_query, (act_id,))
            partner_results = cursor.fetchall()
            # Combine split partners back into a clean comma-separated display string
            partners_list = [p['name'] for p in partner_results]
            partners_display = ", ".join(partners_list)

            # 3. Clean up null values or dates for the UI layout
            start_dt = str(row['start_date']) if row['start_date'] else ""
            end_dt = str(row['end_date']) if row['end_date'] else ""
            budget = row['budget'] if row['budget'] else ""
            expenditure = row['expenditure'] if row['expenditure'] else ""
            
            # Format completion number cleanly as a percentage string
            completion_val = row['completion'] if row['completion'] is not None else 0.0
            completion_display = f"{completion_val:g}%" if completion_val > 0 else "0%"

            # 4. Clear out repeating parent texts to recreate the spreadsheet look
            theme_display = row['theme'] if row['theme'] != prev_theme else ""
            objective_display = row['objective'] if row['objective'] != prev_objective else ""
            initiative_display = row['initiative'] if row['initiative'] != prev_initiative else ""

            # Update tracking states
            if row['theme']: prev_theme = row['theme']
            if row['objective']: prev_objective = row['objective']
            if row['initiative']: prev_initiative = row['initiative']

            # 5. Build HTML table row structure
            html_rows += f"""
            <tr>
                <td class="cell-center text-muted">{row_counter}</td>
                <td class="bold-text">{html.escape(theme_display)}</td>
                <td>{html.escape(objective_display)}</td>
                <td>{html.escape(initiative_display)}</td>
                <td>{html.escape(row['activities'] or '')}</td>
                <td class="cell-center">{html.escape(start_dt)}</td>
                <td class="cell-center">{html.escape(end_dt)}</td>
                <td class="text-right">{html.escape(budget)}</td>
                <td class="text-right">{html.escape(expenditure)}</td>
                <td class="cell-center bold-text">{html.escape(completion_display)}</td>
                <td class="cell-center"><span class="badge">{html.escape(row['status'] or '')}</span></td>
                <td class="bold-text" style="color: #2c3e50;">{html.escape(partners_display)}</td>
            </tr>
            """

        # 6. Wrap rows in a responsive dashboard layout template
        full_html = f"""<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Partnership Coordination Projects Register</title>
    <style>
        body {{
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 25px;
            background-color: #f8f9fa;
            color: #333;
        }}
        h2 {{
            color: #1e3d59;
            margin-bottom: 5px;
        }}
        p {{
            color: #777;
            font-size: 14px;
            margin-top: 0;
            margin-bottom: 20px;
        }}
        .table-container {{
            overflow-x: auto;
            background: #ffffff;
            padding: 12px;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }}
        table {{
            border-collapse: collapse;
            width: 100%;
            font-size: 12px;
        }}
        th {{
            background-color: #1e3d59;
            color: white;
            padding: 12px 10px;
            font-weight: 600;
            text-align: left;
            border: 1px solid #173046;
            white-space: nowrap;
        }}
        td {{
            padding: 10px;
            border: 1px solid #e0e0e0;
            vertical-align: top;
        }}
        tr:nth-child(even) {{
            background-color: #fdfdfd;
        }}
        tr:hover {{
            background-color: #f1f5f9;
        }}
        .bold-text {{
            font-weight: 600;
        }}
        .cell-center {{
            text-align: center;
        }}
        .text-right {{
            text-align: right;
        }}
        .text-muted {{
            color: #999;
        }}
        .badge {{
            background-color: #e1f5fe;
            color: #0288d1;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 11px;
            display: inline-block;
        }}
    </style>
</head>
<body>

    <h2>2026 Partnership Coordination Projects Register</h2>
    <p>Live database view generated directly from relational MySQL source tables.</p>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Strategic Theme</th>
                    <th>Strategic Objective</th>
                    <th>Initiative</th>
                    <th>Activities Supported / Planned</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Budget</th>
                    <th>Expenditure</th>
                    <th>Completion %</th>
                    <th>Status</th>
                    <th>Interested Partners</th>
                </tr>
            </thead>
            <tbody>
                {html_rows}
            </tbody>
        </table>
    </div>

</body>
</html>"""

        with open(html_output_file, 'w', encoding='utf-8') as f:
            f.write(full_html)
            
        print(f"Success! Reconstructed HTML spreadsheet generated at: '{html_output_file}'")

    except Exception as e:
        print(f"An error occurred while reading database: {e}")
    finally:
        if 'cursor' in locals(): cursor.close()
        if 'conn' in locals(): conn.close()

if __name__ == "__main__":
    export_database_to_html('reconstructed_spreadsheet.html')
