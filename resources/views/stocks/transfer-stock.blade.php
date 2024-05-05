<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"> Stocks Transfers </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>From Warehouse</th>
                                <th>To Warehouse</th>
                                <th>Items</th>
                                <th>Grand Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stocks as $stock)
                                <tr>
                                    <td>{{ $stock->id ?? 'N/A' }}</td>
                                    <td>{{ $stock->from_warehouse->name ?? 'N/A' }}</td>
                                    <td>{{ $stock->to_warehouse->name ?? 'N/A' }}</td>
                                    <td>
                                        @foreach ($stock->items as $item)
                                            <p>{{ $item->product->name }} - {{ $item->quantity }}</p>
                                            
                                        @endforeach
                                    </td>
                                    <td>{{ $stock->grand_total ?? 'N/A' }}</td>
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
