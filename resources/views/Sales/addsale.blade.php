<div>

    <div class="col-lg-12">
        <div class="card card-default">
            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                <h2>Add Sale</h2>
            </div>

            <div class="card-body" style="padding-bottom: 2px">

                <form id="addsaleS" wire:submit.prevent="addSale"> <!-- Form submit handling -->
                    <div class="row">

                        <!-- Date -->
                        <div class="form-group col-md-4">
                            <label>Date*</label>
                            <input type="datetime-local" wire:model="inv_datetime" class="form-control">
                            @error('inv_datetime')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        @include('partials.customer-dropdown', [
                            'customers' => $customers,
                            'errors' => $errors,
                        ])

                        @if ($moduleStatuses['warehouses'])
                            <div class="form-group col-md-4">
                                <label>Warehouse</label>
                                <select wire:model="warehouse_id" class="form-control"
                                    @if ($warehouse_id) disabled @endif>
                                    <option value=''>Choose Warehouse</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('warehouse_id'))
                                    <span class="text-danger">{{ $errors->first('warehouse_id') }}</span>
                                @endif
                            </div>

                        @endif

                    </div>

                    <br>

                    <div class="row">

                        <div class="form-group col-md-8">
                            <input type="text" wire:model.live.debounce.250ms="search" wire:keydown.enter.prevent=""
                                placeholder="Scan/Search Product by code or name" class="form-control" id="searchInput">
                            @error('search')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                            <div>
                                @if (!empty($searchResults))
                                    <ul class="list-group" id="searchResultsList">
                                        @foreach ($searchResults as $result)
                                            @if ($result->product_type == 'variable' && $result->variants->isNotEmpty())
                                                @foreach ($result->variants as $variant)
                                                    <li class="list-group-item search-result-item"
                                                        wire:click.stop="addVariantToTable({{ $variant->id }})">
                                                        {{ $result->name }} - {{ $variant->name }}
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="list-group-item search-result-item"
                                                    wire:click="addProductToTable({{ $result->id }})">
                                                    {{ $result->name }}
                                                </li>
                                            @endif
                                        @endforeach

                                    </ul>
                                @endif
                            </div>
                        </div>


                    </div>

                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table my-table table-hover">
                                <thead>
                                    <tr>

                                        <th>Product Name</th>
                                        <th>Net Unit Price</th>
                                        @if ($moduleStatuses['stocks'])
                                        <th>Current Stock</th>
                                        @endif
                                        <th>Quantity</th>
                                        {{-- <th>Tax %</th>
                                        <th>Tax Amount</th> --}}
                                        <th>Subtotal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($selectedProducts)

                                        @foreach ($selectedProducts as $key => $product)
                                            <tr>

                                                <td style="width: 25%">{{ $product['name'] }}</td>
                                                <td>{{ $product['price'] }}</td>
                                                @if ($moduleStatuses['stocks'])
                                                <td>{{ $product['current_stock'] }}</td>
                                                @endif
                                                <td style="width: 25%">
                                                    <div class="input-group  input-group-sm">
                                                        <span class="input-group-btn">
                                                            <button type="button" class=""
                                                                style="color: #8a909d;"
                                                                wire:click="decrementQuantity('{{ $key }}')">
                                                                <span
                                                                    class="mdi mdi-24px mdi-minus-circle-outline"></span>
                                                            </button>
                                                        </span>
                                                        <input type="text" class="form-control col-3"
                                                            wire:change="updateQuantity('{{ $key }}', $event.target.value)"
                                                            wire:model.lazy="selectedProducts.{{ $key }}.quantity">

                                                        <span class="input-group-btn">
                                                            <button type="button" class=""
                                                                style="color: #8a909d;" style=""
                                                                wire:click="incrementQuantity('{{ $key }}')">
                                                                <span
                                                                    class="mdi mdi-24px mdi-plus-circle-outline"></span>
                                                            </button>
                                                        </span>
                                                    </div>
                                                </td>
                                                {{-- 
                                                <td>{{ $product['tax'] }}</td>
                                                <td>{{ $product['tax_amount'] }}</td> --}}
                                                <td style="15%">{{ $product['subtotal'] }}</td>
                                                <td style="width: 20%">
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                        data-toggle="modal"
                                                        data-target="#editProductModal-{{ $key }}">Edit</button>
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        wire:click="removeProduct({{ $key }})">Delete</button>
                                                </td>

                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="9">No data Available</td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table>

                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4 offset-md-8">
                            <table class="table table-striped table-sm">
                                <tbody>

                                    <tr>
                                        <td class="bold">Discount</td>
                                        <td>
                                            <span>
                                                {{ config('settings.currency_symbol') }} {{ $discountAmount }}
                                                ({{ $discountType === 'percent' ? $discountAmount . '%' : 'Fixed' }})
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bold">Tax </td>
                                        <td> {{ $taxAmount }} ({{ $taxRate }}%)</td>
                                    </tr>
                                    <tr>
                                        <td class="bold">Shipping</td>
                                        <td>{{ config('settings.currency_symbol') }} {{ $shippingAmount }}</td>
                                    </tr>
                                    <tr>
                                        <td><span class="font-weight-bold">Grand Total</span></td>
                                        <td class="font-weight-bold">{{ config('settings.currency_symbol') }}
                                            {{ $grandTotal }}</td>
                                    </tr>
                                </tbody>

                            </table>

                        </div>
                    </div>


                    <!-- Discount Section -->
                    <div class="row">

                        <div class="form-group col-md-4">
                            <label>Discount :</label>
                            <div class="discount-input input-group">
                                <div class="input-group-prepend">
                                    <select wire:model.live="discountType" class="form-control">
                                        <option value="fixed"> {{ config('settings.currency_symbol') }} </option>
                                        <option value="percent"> % </option>
                                    </select>
                                </div>
                                <input type="number" wire:model.live.debounce.500ms="discountAmount"
                                    class="form-control" min="0">

                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Tax Rate:</label>
                            <div class="tax-input input-group">
                                <input type="text" wire:model.live.debounce.500ms="taxRate" inputmode="decimal" pattern="[0-9]+(\.[0-9]{1,2})?"
                                    class="form-control">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>


                        <!-- Shipping Amount -->

                        <div class="form-group col-md-4">
                            <label>Shipping Amount</label>
                            <input type="number" wire:model.live.debounce.500ms="shippingAmount" class="form-control"
                                min="0">
                        </div>
                    </div>

                    <!-- Other Details -->
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Please provide any details</label>
                            <textarea wire:model="otherDetails" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group col-md-6 text-center">
                            <br> <br>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>

                    </div>




                </form>

                @foreach ($selectedProducts as $productId => $product)
                    <!-- Modal -->
                    <div class="modal fade" id="editProductModal-{{ $productId }}" tabindex="-1" role="dialog"
                        aria-labelledby="editProductModalLabel-{{ $productId }}" aria-hidden="true">
                        <form id="updateProduct-{{ $productId }}"
                            wire:submit.prevent="updateProduct({{ $productId }})">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editProductModalLabel-{{ $productId }}">
                                            Edit Product</h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">


                                        <div class="form-group">
                                            <label for="productPrice-{{ $productId }}">Product
                                                Price</label>
                                            <input type="number" class="form-control"
                                                id="productPrice-{{ $productId }}"
                                                wire:model="selectedProducts.{{ $productId }}.price">
                                        </div>
                                        {{--  <div class="form-group">
                                            <label for="productTax-{{ $productId }}">Tax Percentage
                                                (%)
                                            </label>
                                            <input type="number" class="form-control"
                                                id="productTax-{{ $productId }}"
                                                wire:model="selectedProducts.{{ $productId }}.tax">
                                        </div>

                                        <div class="form-group">
                                            <label for="taxMethod-{{ $productId }}">Tax Method</label>
                                            <select class="form-control" id="taxMethod-{{ $productId }}"
                                                wire:model="selectedProducts.{{ $productId }}.tax_method">
                                                <option value="exclusive">Exclusive</option>
                                                <option value="inclusive">Inclusive</option>
                                            </select>
                                        </div> --}}

                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                @endforeach

            </div>

        </div>




    </div>
</div>
