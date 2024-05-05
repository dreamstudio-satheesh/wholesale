<div class="col-lg-12">
    <div class="card card-default">
        <div class="card-header card-header-border-bottom d-flex justify-content-between">
            <h2>Add Purchase</h2>
        </div>

        <div class="card-body">
           

            @if ($errors->has('purchaseError'))
                <div class="alert alert-danger">{{ $errors->first('purchaseError') }}</div>
            @endif

            <form wire:submit.prevent="addPurchase">
                <div class="row">

                    <!-- Purchase Date -->
                    <div class="form-group col-md-4">
                        <label>Purchase Date:</label>
                        <input type="date" wire:model="purchase_date" class="form-control">
                    </div>

                    <!-- Supplier Dropdown -->
                    @include('partials.supplier-dropdown', [
                        'suppliers' => $suppliers,
                        'errors' => $errors,
                    ])

                    <!-- Warehouse Dropdown -->
                    @if ($moduleStatuses['warehouses'])
                        <div class="form-group col-md-4">
                            <label>Warehouse</label>
                            <select wire:model="warehouse_id" class="form-control" @if($warehouse_id) disabled @endif>
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

                <!-- Product Search -->
                <br>

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
                                        <li class="list-group-item search-result-item"
                                            wire:click="addProductToTable({{ $result->id }})">
                                            {{ $result->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>


                </div>

                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-md">
                            <thead>
                                <tr>

                                    <th scope="col">Product Name</th>
                                    <th scope="col">Net Unit Price</th>
                                    <th scope="col">Current Stock</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Tax %</th>
                                    <th scope="col">Tax Amount</th>
                                    <th scope="col">Subtotal</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($selectedProducts)
                                    @foreach ($selectedProducts as $productId => $product)
                                        <tr>

                                            <td>{{ $product['name'] }}</td>
                                            <td>{{ $product['price'] }}</td>
                                            <td>{{ $product['current_stock'] }}</td>
                                            <td>
                                                <div class="input-group  input-group-sm">
                                                    <span class="input-group-btn">
                                                        <button type="button" class="" style="color: #8a909d;"
                                                            wire:click="decrementQuantity({{ $productId }})">
                                                            <span class="mdi mdi-24px mdi-minus-circle-outline"></span>
                                                        </button>
                                                    </span>
                                                    <input type="text" class="form-control col-3"
                                                        wire:change="updateQuantity({{ $productId }}, $event.target.value)"
                                                        wire:model.lazy="selectedProducts.{{ $productId }}.quantity">

                                                    <span class="input-group-btn">
                                                        <button type="button" class="" style="color: #8a909d;"
                                                            style=""
                                                            wire:click="incrementQuantity({{ $productId }})">
                                                            <span class="mdi mdi-24px mdi-plus-circle-outline"></span>
                                                        </button>
                                                    </span>
                                                </div>
                                            </td>

                                            <td>{{ $product['tax'] }}</td>
                                            <td>{{ $product['tax_amount'] }}</td>
                                            <td>{{ $product['subtotal'] }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary"
                                                    data-toggle="modal"
                                                    data-target="#editProductModal-{{ $productId }}">Edit</button>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    wire:click="removeProduct({{ $productId }})">Delete</button>
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
                                            ${{ $discountAmount }}
                                            ({{ $discountType === 'percent' ? $discountAmount . '%' : 'Fixed' }})
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bold">Shipping</td>
                                    <td>${{ $shippingAmount }}</td>
                                </tr>
                                <tr>
                                    <td><span class="font-weight-bold">Grand Total</span></td>
                                    <td class="font-weight-bold">${{ $grandTotal }}</td>
                                </tr>
                            </tbody>

                        </table>

                    </div>
                </div>


                <!-- Discount Section -->
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Discount Type</label>
                        <select wire:model="discountType" class="form-control">
                            <option value="fixed">Fixed Amount</option>
                            <option value="percent">Percentage (%)</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Discount Amount</label>
                        <input type="number" wire:model.live.debounce.500ms="discountAmount" class="form-control"
                            min="0">
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
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                                    <div class="form-group">
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
                                    </div>

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
