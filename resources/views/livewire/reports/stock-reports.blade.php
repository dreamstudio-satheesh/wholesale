<div>
    <div class="row" id="recent-orders">
        <div class="col-md-4" >
            @if ($moduleStatuses['warehouses'])
            <div class="form-group">
                <select id="warehouse" class="form-control" wire:model.live="warehouseId">
                    <option value="">Select Warehouse</option>
                    @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>
                @error('warehouseId') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            @endif
        </div>
        <div class="col-md-4" ></div>
        <div class="col-md-4">
            <form wire:submit.prevent="SearchQuery">
                <input wire:model="search" type="text" class="form-control" placeholder="Search stocks...">
            </form>

        </div>
        

    </div>




<table class="table mt-3">
    <thead>
        <tr class="">
            <th wire:click="sortBy('sku')">
                Code
                @if ($sortField === 'sku')
                    <span
                        class="mdi {{ $sortDirection === 'asc' ? 'mdi-sort-ascending' : 'mdi-sort-descending' }}"></span>
                @endif
            </th>


            <th wire:click="sortBy('name')">
                Name
                @if ($sortField === 'name')
                    <span
                        class="mdi {{ $sortDirection === 'asc' ? 'mdi-sort-ascending' : 'mdi-sort-descending' }}"></span>
                @endif
            </th>

            <th>Warehouse</th>
            <th>Current Stock</th>
            <th>Total Amount Stock</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr class="">
                <td> #{{ $product->sku }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $this->warehouseName }}</td>
                <td>{{ $product->stocks->first()->current_stock ?? '0' }}</td>
                <td>
                    @if(optional($product->stocks->first())->total_amount_stock !== null)
                        {{ config('settings.currency_symbol') }}
                        {{ optional($product->stocks->first())->total_amount_stock }}
                    @else
                    0.00    
                    @endif
                </td>
        @endforeach
    </tbody>
</table>

{{ $products->links() }}

</div>
