<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">View Stocks </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product Name</th>
                                <th>Code</th>
                                <th>Warehouse</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stocks as $stock)
                                <tr>
                                    <td>{{ $stock->product_id ?? 'N/A' }}</td>
                                    <td>{{ $stock->product_name ?? 'N/A' }}</td>
                                    <td>{{ $stock->product_sku ?? 'N/A' }}</td>
                                    <td>{{ $stock->warehouse_name ?? 'N/A' }}</td>
                                    <td>{{ $stock->current_stock ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $stocks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
