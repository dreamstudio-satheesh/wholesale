<div>
    <div class="row" id="recent-orders">
        <div class="col-md-3">
            <form wire:submit.prevent="SearchQuery">
                <input wire:model="search" type="text" class="form-control" placeholder="Search products...">
            </form>
           {{--  <input wire:model="search" type="text" class="form-control" placeholder="Search products..."> --}}
        </div>
        <div class="col-md-6"></div>
        <div class="col-md-3">
            <div class=" text-right date-range-report ">
                <span> @if ($startDate)
                    {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{  \Carbon\Carbon::parse($endDate)->format('M d, Y')  }}
                @endif 
                </span>
              </div>
        </div>
       
        
    </div>

    <table class="table mt-3">
        <thead>
            <tr class="text-center">
                <th scope="col" wire:click="sortBy('sku')">
                    Code
                    @if ($sortField === 'sku')
                        <span
                            class="mdi {{ $sortDirection === 'asc' ? 'mdi-sort-ascending' : 'mdi-sort-descending' }}"></span>
                    @endif
                </th>

                <th scope="col" wire:click="sortBy('name')">
                    Name
                    @if ($sortField === 'name')
                        <span
                            class="mdi {{ $sortDirection === 'asc' ? 'mdi-sort-ascending' : 'mdi-sort-descending' }}"></span>
                    @endif
                </th>

                <th scope="col" wire:click="sortBy('product_type')">
                    Product Type
                    @if ($sortField === 'product_type')
                        <span
                            class="mdi {{ $sortDirection === 'asc' ? 'mdi-sort-ascending' : 'mdi-sort-descending' }}"></span>
                    @endif
                </th>

                <th scope="col" wire:click="sortBy('category_id')">
                    Category
                    @if ($sortField === 'category_id')
                        <span
                            class="mdi {{ $sortDirection === 'asc' ? 'mdi-sort-ascending' : 'mdi-sort-descending' }}"></span>
                    @endif
                </th>
                <th>Warehouse</th>
                <th>Qty Sold</th>
                <th>Amount Sold</th>
                <th>Qty Purchased</th>
                <th scope="col"> Amount Purchased</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr class="text-center">
                    <td scope="row">{{ $product['sku'] }}</td>
                    <td scope="row">{{ $product['name'] }}</td>
                    <td scope="row">{{ $product['product_type'] }}</td>
                    <td scope="row">{{ $product['category'] }}</td>
                    <td scope="row">{{ $warehouse }}</td>
                    <td scope="row">{{ $product['qty_sold'] }}</td>
                    <td scope="row">{{ config('settings.currency_symbol') }} {{ number_format($product['amount_sold'], 2) }}</td>
                    <td scope="row">{{ $product['qty_purchased'] }}</td>
                    <td scope="row">{{ config('settings.currency_symbol') }} {{ number_format($product['amount_purchased'], 2) }}</td>
                       
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $products->links() }}

</div>
