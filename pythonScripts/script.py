import openpyxl
import html

def parse_and_preview_excel(file_path, html_output):
    try:
        # Load the workbook and select the active sheet
        wb = openpyxl.load_workbook(file_path, data_only=True)
        sheet = wb.active
        
        # Capture headers from the first row
        headers = [str(cell.value or '').strip() for cell in sheet[1]]
        
        # Build terminal preview header
        print("=" * 80)
        print(f"READING SPREADSHEET HEADERS:\n{headers}")
        print("=" * 80)
        
        # Start constructing HTML table structure
        html_rows = ""
        header_row = "".join(f"<th>{html.escape(h)}</th>" for h in headers)
        
        row_counter = 0
        
        # Iterate line-by-line starting from row 2 (skipping header row)
        for row in sheet.iter_rows(min_row=2, values_only=True):
            # Check if row is completely empty to prevent useless DB writes
            if not any(row):
                continue
                
            row_counter += 1
            
            # Map values to string for clean terminal tracking
            row_values = [str(val or '').strip() for val in row]
            
            # Log exact line-by-line progress to terminal console
            print(f"Line {row_counter} Read -> {row_values}")
            
            # Construct row for HTML validation page
            td_elements = "".join(f"<td>{html.escape(val)}</td>" for val in row_values)
            html_rows += f"<tr><td>{row_counter}</td>{td_elements}</tr>"
            
        print("=" * 80)
        print(f"Total lines parsed successfully: {row_counter}. Generating HTML preview...")
        print("=" * 80)

        # Assemble full HTML payload
        html_document = f"""<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database Import Staging Preview</title>
    <style>
        body {{ font-family: sans-serif; margin: 30px; background-color: #f8f9fa; }}
        h2 {{ color: #2c3e50; }}
        p {{ color: #7f8c8d; font-size: 14px; }}
        .table-container {{ overflow-x: auto; background: white; padding: 15px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }}
        table {{ border-collapse: collapse; width: 100%; font-size: 12px; }}
        th, td {{ padding: 8px 10px; border: 1px solid #ddd; text-align: left; }}
        th {{ background-color: #34495e; color: white; }}
        tr:nth-child(even) {{ background-color: #f2f2f2; }}
        .row-num {{ font-weight: bold; color: #e74c3c; background-color: #fdf2e9; text-align: center; }}
    </style>
</head>
<body>
    <h2>Database Staging Preview (Line-by-Line Breakdown)</h2>
    <p>Verify this table data format looks perfect before pushing it into your MySQL execution script.</p>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>DB Row #</th>
                    {header_row}
                </tr>
            </thead>
            <tbody>
                {html_rows}
            </tbody>
        </table>
    </div>
</body>
</html>"""

        with open(html_output, 'w', encoding='utf-8') as f:
            f.write(html_document)
            
    except Exception as e:
        print(f"Parsing error triggered: {e}")

# Execute script
parse_and_preview_excel('2026_Partnership_Coordination_Projects_Register_MASTER_FINAL.xlsx', 'db_preview.html')
