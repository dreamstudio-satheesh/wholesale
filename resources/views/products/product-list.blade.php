<div>
    <div class="row">
        <div class="col-md-9">
            <div class="dropdown d-inline-block mb-1">
                <button class="btn btn-outline-success dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                    EXPORT
                </button>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ url('products/export/pdf') }}">PDF</a>
                    <a class="dropdown-item" href="{{ url('products/export/excel') }}">Excel</a>
                    <a class="dropdown-item" href="{{ url('products/export/csv') }}">CSV</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 text-right">
            <input wire:model.change="search" type="text" class="form-control" placeholder="Search products...">
        </div>
    </div>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Image</th>
                <th wire:click="sortBy('name')">
                    Name
                    @if ($sortField === 'name')
                        <span
                            class="mdi {{ $sortDirection === 'asc' ? 'mdi-sort-ascending' : 'mdi-sort-descending' }}"></span>
                    @endif
                </th>
                <th wire:click="sortBy('price')">
                    Price
                    @if ($sortField === 'price')
                        <span
                            class="mdi {{ $sortDirection === 'asc' ? 'mdi-sort-ascending' : 'mdi-sort-descending' }}"></span>
                    @endif
                </th>

                <th wire:click="sortBy('cost')">
                    Cost
                    @if ($sortField === 'cost')
                        <span
                            class="mdi {{ $sortDirection === 'asc' ? 'mdi-sort-ascending' : 'mdi-sort-descending' }}"></span>
                    @endif
                </th>

                <th wire:click="sortBy('sku')">
                    SKU
                    @if ($sortField === 'sku')
                        <span
                            class="mdi {{ $sortDirection === 'asc' ? 'mdi-sort-ascending' : 'mdi-sort-descending' }}"></span>
                    @endif
                </th>

                <th>Category</th>
                <th>Brand</th>
                <th>Actions</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>
                        @if ($product->getMedia('products')->isEmpty())
                            <img class="img-fluid rounded-circle" style="height:40px; width:40px; align-self: center; " src="{{ url('image/no-image.webp') }}" alt="No Image Available">
                        @else
                        <img class="img-fluid rounded-circle" style="height:40px; width:40px; align-self: center; "
                        src="{{ $product->getMedia('products')[0]->getUrl('preview') }}"  alt="{{ $product->name }}">
                        @endif

                       
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>
                        @if ($product->variants->isNotEmpty())
                            <ul>
                                @foreach ($product->variants as $variant)
                                    <li>{{ $variant->name }} - {{ config('settings.currency_symbol') }}
                                        {{ $variant->price }}</li>
                                @endforeach
                            </ul>
                        @else
                            {{ config('settings.currency_symbol') }} {{ $product->price }}
                        @endif
                    </td>
                    <td>
                        @if ($product->variants->isNotEmpty())
                            <ul>
                                @foreach ($product->variants as $variant)
                                    <li>{{ $variant->name }} - {{ config('settings.currency_symbol') }}
                                        {{ $variant->cost }}</li>
                                @endforeach
                            </ul>
                        @else
                            {{ $product->cost ? config('settings.currency_symbol') . $product->cost : 'N/A' }}
                        @endif
                    </td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                    <td>{{ $product->brand->name ?? 'N/A' }}</td>
                    <td>
                        @if (auth()->user()->can('edit_products'))
                            <a href="{{ route('products.edit', $product->id) }}"
                                class="btn btn-primary btn-sm">Edit</a>
                        @endif
                        @if (auth()->user()->can('delete_products'))
                            <button x-data="{ productId: {{ $product->id }} }" @click="confirmDeletion(productId)"
                                class="btn btn-danger btn-sm">Delete</button>
                        @endif
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>

    {{ $products->links() }}
</div>
