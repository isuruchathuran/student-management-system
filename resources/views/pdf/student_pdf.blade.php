<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Student Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000000;
        }

        h2 {
            text-align: center;
            margin-bottom: 4px;
            font-size: 15px;
        }

        p.meta {
            text-align: center;
            font-size: 10px;
            color: #555555;
            margin-bottom: 20px;
        }

        p.filter-note {
            text-align: center;
            font-size: 10px;
            color: #1a56a0;
            margin-bottom: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #333333;
            color: #ffffff;
            padding: 7px 8px;
            text-align: left;
            font-size: 10px;
        }

        td {
            padding: 6px 8px;
            border-bottom: 1px solid #dddddd;
            font-size: 10px;
        }

        tr:nth-child(even) td {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>

    <h2>Student Management System — Student Report</h2>
    

    

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Reg No</th>
                <th>Student Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Birthday</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($students as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student->reg_No }}</td>
                    <td>{{ $student->Name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->phone }}</td>
                    <td>{{ $student->date_of_birth }}</td>
                    <td>{{ $student->address }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 16px; color: #777777;">
                        No student records found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
