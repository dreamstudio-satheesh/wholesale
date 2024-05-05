<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            color: #333;
        }
        .container {
            margin: 20px;
            padding: 15px;
        }
        h1 {
            text-align: center;
            color: #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            text-align: center;
            padding: 8px;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th bgcolor="#4CAF50" style="color: white; font-weight: bold; text-align: center; width:100px">Invoice Number</th>
                    <th bgcolor="#4CAF50" style="color: white; font-weight: bold; text-align: center; width:100px">Date</th>
                    <th bgcolor="#4CAF50" style="color: white; font-weight: bold; text-align: center; width:100px">Customer</th>
                    <th bgcolor="#4CAF50" style="color: white; font-weight: bold; text-align: center; width:100px">Warehouse</th>
                    <th bgcolor="#4CAF50" style="color: white; font-weight: bold; text-align: center; width:100px">Status</th>
                    <th bgcolor="#4CAF50" style="color: white; font-weight: bold; text-align: center; width:100px">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salesreturn as $sale)
                    <tr>
                        <td align="center" valign="middle" style="border: 1px solid #ddd;">{{ $sale->return_invoice_number }}</td>
                        <td align="center" valign="middle" style="border: 1px solid #ddd;">{{ $sale->date }}</td>
                        <td align="center" valign="middle" style="border: 1px solid #ddd;">{{ $sale->customer->name }}</td>
                        <td align="center" valign="middle" style="border: 1px solid #ddd;">{{ $sale->warehouse->name }}</td>
                        <td align="center" valign="middle" style="border: 1px solid #ddd;">{{ $sale->status }}</td>
                        <td align="center" valign="middle" style="border: 1px solid #ddd;">{{ $sale->grand_total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>
</body>
</html>
