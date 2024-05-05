<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice #1001</title>
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Arial, sans-serif;
            color: #555;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .align-right {
            text-align: right;
        }

        .sub-header {
            background: #f7f7f7;
            padding: 10px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }

        table td,
        table th {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.8em;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <div class="header-section">
            <div>
                <h2>Invoice #1001</h2>
                <pre>
From:
Your Company Name
123, Street Name, Area
Email: contact@yourcompany.com
Phone: 1234567890
                </pre>
            </div>
            <div class="align-right">
                <pre>
To:
Walk-in Customer
Email: 
Phone: 

Date: March 04, 2024
VAT: PL6541215450
                </pre>
            </div>
        </div>
        <div class="sub-header">
            Invoice Summary
        </div>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Unit Cost</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <!-- Repeat this section for each line item -->
                <tr>
                    <td>#6331</td>
                    <td>Raw Honey</td>
                    <td>1</td>
                    <td>₹111.09</td>
                    <td>₹111.09</td>
                </tr>
                <!-- ... other items ... -->
            </tbody>
        </table>
        <div class="footer">
            <p>Thank you for your business!</p>
        </div>
    </div>
</body>

</html>
