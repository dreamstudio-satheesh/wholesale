<!DOCTYPE html>
<html>
<head>
    <title>Products Export</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th bgcolor="#007bff" align="center" style="color: white; padding: 10px; width:60px">ID</th>
                <th bgcolor="#007bff" align="center" style="color: white; padding: 10px; width:200px">Name</th>
                <th bgcolor="#007bff" align="center" style="color: white; padding: 10px; width:100px">SKU</th>
                <th bgcolor="#007bff" align="center" style="color: white; padding: 10px; width:100px">Cost</th>
                <th bgcolor="#007bff" align="center" style="color: white; padding: 10px; width:100px">Price</th>
                <th bgcolor="#007bff" align="center" style="color: white; padding: 10px; width:60px">Category ID</th>
                <th bgcolor="#007bff" align="center" style="color: white; padding: 10px; width:60px">Brand ID</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td align="center" style="padding: 8px; border: 1px solid #ccc;">{{ $product->id }}</td>
                    <td align="center" style="padding: 8px; border: 1px solid #ccc;">{{ $product->name }}</td>
                    <td align="center" style="padding: 8px; border: 1px solid #ccc;">{{ $product->sku }}</td>
                    <td align="center" style="padding: 8px; border: 1px solid #ccc;">{{ $product->cost }}</td>
                    <td align="center" style="padding: 8px; border: 1px solid #ccc;">{{ $product->price }}</td>
                    <td align="center" style="padding: 8px; border: 1px solid #ccc;">{{ $product->category_id }}</td>
                    <td align="center" style="padding: 8px; border: 1px solid #ccc;">{{ $product->brand_id }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
