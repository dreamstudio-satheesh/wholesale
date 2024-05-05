<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">View Stock History</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Warehouse</th>
                                <th>Quantity</th>
                                <th>Movement</th>
                                <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stocks as $stock)
                                <tr>
                                    <td>{{ $stock->product->name ?? 'N/A' }}</td>
                                    <td>{{ $stock->warehouse->name ?? 'N/A' }}</td>
                                    <td>{{ $stock->quantity }}</td>
                                    <td>{{ $stock->movement_reason }}</td>
                                    <td>{{ $stock->updated_at->toFormattedDateString() }}</td>
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
