<div>
    <div class="col-lg-12">

        <div class="card card-default">
            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                <h2>Add product</h2>
            </div>

            <div class="card-body">

                <div class="row">

                   

                    <form wire:submit.prevent="saveProduct"> <!-- Form submit handling -->
                        <div class="row">
                            <!-- Product Name -->
                            <div class="form-group col-md-4">
                                <label>Product Name*</label>
                                <input type="text" wire:model="productName" class="form-control"
                                    placeholder="Enter Name" autofocus>
                                @error('productName')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Product Code -->
                            <div class="form-group col-md-4">
                                <label>Product Code*</label>
                                <input type="text" wire:model="productCode" class="form-control"
                                    placeholder="Enter Code">
                                @error('productCode')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @if ($moduleStatuses['categories'])
                                <!-- Category Dropdown with Modal -->
                                @include('partials.category-dropdown', [
                                    'categories' => $categories,
                                    'errors' => $errors,
                                ])
                            @endif

                            @if ($moduleStatuses['brands'])
                                <!-- Brand Dropdown with Modal -->
                                @include('partials.brand-dropdown', [
                                    'brands' => $brands,
                                    'errors' => $errors,
                                ])
                            @endif

                          {{--   <div class="form-group col-md-4">
                                <label>Order Tax*</label>
                                <div class="input-group">
                                    <input type="text" wire:model="orderTax" class="form-control" placeholder="0">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                @error('orderTax')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label>Tax Method</label>
                                <select wire:model="taxMethod" class="form-control">
                                    <option value='exclusive'>Exclusive</option>
                                    <option value='inclusive'>Inclusive</option>
                                </select>
                                @error('taxMethod')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div> --}}

                            <!-- Image Upload -->
                            <div class="form-group col-md-4">
                                <label>Image*</label>
                                <input type="file" wire:model="image" class="form-control">
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Details -->
                            <div class="form-group col-md-4">
                                <label>Please provide any details </label>
                                <textarea wire:model="details" class="form-control" rows="2" placeholder="Please provide any details"></textarea>
                                @error('details')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>



                        </div>


                        <div class="row">
                            <!-- Dynamic Dropdown for Product Type -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Product Type *</label>
                                    <select wire:model.change="productType" class="form-control">
                                        <option value="">Select Product Type</option>
                                        <option value="standard">Standard Product</option>
                                        <option value="variable">Variable Product</option>
                                        <option value="services">Services Product</option>
                                    </select>
                                    @error('productType')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>



                            @if ($productType == 'standard')
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Product Cost</label>
                                        <input type="number" wire:model="productCost" class="form-control"
                                            placeholder="Product Cost">
                                        @error('productCost')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            @if ($productType == 'standard' || $productType == 'services')
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Product Price</label>
                                        <input type="number" wire:model="productPrice" class="form-control"
                                            placeholder="Product Price">
                                        @error('productPrice')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            @if ($productType == 'standard' || $productType == 'variable')

                                <!-- Fields for Standard Product -->
                                @if ($moduleStatuses['units'])
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Unit Product</label>
                                            <select wire:model.live="unit_id" class="form-control">
                                                <option value="">Select Unit for Product</option>
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Unit Sale</label>
                                            <select wire:model="unit_sale_id" class="form-control">
                                                <option value="">Select Unit for Sale</option>
                                                @foreach ($saleUnits as $unit)
                                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Unit Purchase</label>
                                            <select wire:model="unit_purchase_id" class="form-control">
                                                <option value="">Select Unit for Purchase</option>
                                                @foreach ($purchaseUnits as $unit)
                                                    <option value="{{ $unit->id }}">{{ $unit->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Minimum Sale Quantity</label>
                                        <input type="number" wire:model="minimumSaleQuantity" class="form-control"
                                            placeholder="Minimum Sale Quantity">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Stock Alert</label>
                                        <input type="number" wire:model="stockAlert" class="form-control"
                                            placeholder="Stock Alert">
                                    </div>
                                </div>
                        </div>
                </div>
                @endif

                @if ($productType == 'variable')
                    <br>

                    <div class="form-inline">
                        @foreach ($variants as $index => $variant)
                            <div class="input-group mb-2 mr-sm-2">
                                <input wire:model="variants.{{ $index }}.name" type="text"
                                    class="form-control" placeholder="Variant Name">
                                <input wire:model="variants.{{ $index }}.code" type="text"
                                    class="form-control" placeholder="Variant Code">
                                <input wire:model="variants.{{ $index }}.cost" type="text"
                                    class="form-control" placeholder="Product Cost">
                                <input wire:model="variants.{{ $index }}.price" type="text"
                                    class="form-control" placeholder="Product Price">
                                <button type="button" class="btn btn-danger mb-2"
                                    wire:click="removeVariant({{ $index }})">Remove</button>
                            </div>
                        @endforeach

                        <button type="button" class="btn btn-secondary mb-2" wire:click="addVariant">Add
                            Variant</button>
                    </div>


                @endif
            </div>


            <div class="form-footer pt-5 border-top">
                <button wire:click="saveProduct" class="btn btn-primary btn-default">Save</button>
                <button type="button" class="btn btn-secondary btn-default">Cancel</button>
            </div>
            </form>
        </div>

    </div>


</div>

</div>



@push('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.addEventListener('showErrorToast', event => {
                toastr.options = {
                    closeButton: true,
                    positionClass: "toast-top-right",
                };

                let messages = event.detail;
                if (messages.length && Array.isArray(messages[0])) {
                    messages = messages.flat();
                }

                messages.forEach(message => {
                    toastr.error(message);
                });
            });


            window.addEventListener('show-toastr', event => {
                toastr.options = {
                    closeButton: true,
                    positionClass: "toast-top-right",
                };
                console.log(event);
                toastr.success(event.detail[0].message);

            });
        });
    </script>

    @if ($moduleStatuses['categories'])
        @script
            <script>
                $('#createCategoryModal').on('shown.bs.modal', function() {
                    $('#newCategoryName').focus()
                });
                document.getElementById('newCategoryName').addEventListener('keypress', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        Livewire.dispatch('createCategory');
                    }
                });

                $wire.on('categoryModalClose', () => {
                    $('#createCategoryModal').modal('hide');
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open').css('padding-right', '');
                });
            </script>
        @endscript
    @endif

    @if ($moduleStatuses['brands'])
        @script
            <script>
                $('#createBrandModal').on('shown.bs.modal', function() {
                    $('#newBrandName').focus()
                });

                document.getElementById('newBrandName').addEventListener('keypress', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        Livewire.dispatch('createBrand');
                    }
                });

                $wire.on('brandModalClose', () => {
                    $('#createBrandModal').modal('hide');
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open').css('padding-right', '');
                });
            </script>
        @endscript
    @endif
@endpush
