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
                <h2>Return Invoice #{{ $sale->return_invoice_number }}</h2>
                <pre>
From:
{{ config('settings.company_name') }}
{{ config('settings.company_address') }}
Email: {{ config('settings.company_email') }}
Phone: {{ config('settings.company_phone') }}
                </pre>
            </div>
            <div class="align-right">
                <pre>
            {{ $sale->customer->name }}
            {{ $sale->customer->address }}
            Email: {{ $sale->customer->email }}
            Phone: {{ $sale->customer->phone }}

            {{ \Carbon\Carbon::parse($sale->date)->format('F d, Y') }}
            VAT:{{ config('settings.company_vat') }}
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
                @foreach ($sale->items as $item)
                <tr>
                    <td>#{{ $item->product->sku }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ config('settings.currency_symbol') }} {{ $item->price }}</td>
                    <td>{{ config('settings.currency_symbol') }} {{ $item->subtotal }}</td>
                </tr>
                    
                @endforeach
               
                <!-- ... other items ... -->
            </tbody>
        </table>
        <div class="footer">
            <p>Thank you for your business!</p>
        </div>
    </div>
</body>

</html>
