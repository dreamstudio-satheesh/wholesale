<div>
    <div class="row">
        <div class="col-md-9"></div> <!-- Empty column for spacing -->
        <div class="col-md-3 text-right">
            <input wire:model.change="search" type="text" class="form-control" placeholder="Search products...">
        </div>
    </div>

    <table class="table mt-3">
        <thead>
            <tr>
                <th wire:click="sortBy('sku')">
                    SKU
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

                <th>Price</th>

                <th>Cost</th>

                <th>Actions</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->name }}</td>
                    <td>
                        @if ($product->variants->isNotEmpty())
                            <ul>
                                @foreach ($product->variants as $variant)
                                    <li>
                                        <div class="input-group mb-2 mr-sm-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"> {{ $variant->name }}</div>
                                            </div>

                                            <input wire:model="updatedCosts.variant.{{ $variant->id }}" class="form-control" type="text">
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <input wire:model="updatedPrices.{{ $product->id }}" class="form-control" type="text">
                        @endif
                    </td>
                    <td>
                        @if ($product->variants->isNotEmpty())
                            <ul>
                                @foreach ($product->variants as $variant)
                                    <li><div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"> {{ $variant->name }}</div>
                                        </div>

                                        <input class="form-control" type="text" wire:model="updatedCosts.variant.{{ $variant->id }}">
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        @else
                            
                            @if ($product->product_type == 'services')
                                N/A
                            @else
                                 <input  class="form-control" type="text" wire:model="updatedCosts.{{ $product->id }}">
                            @endif
                           
                        @endif
                    </td>


                    <td>
                        <button type="submit" wire:click="updatePrice({{ $product->id }})" class="btn btn-primary btn-sm">Update Price</button>

                </tr>
            @endforeach

        </tbody>
    </table>

    {{ $products->links() }}
</div>


@push('scripts')
    <script>
      
        document.addEventListener('DOMContentLoaded', function() {
            window.addEventListener('show-toastr', event => {

                toastr.options = {
                    closeButton: true,
                    positionClass: "toast-top-right",
                };
                toastr.success(event.detail[0].message);


            });
        });
    </script>
@endpush