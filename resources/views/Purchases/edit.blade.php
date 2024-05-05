@extends('layouts.app')



@section('content')
    <div class="content">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h2>Edit Purchase</h2>
                </div>

                <div class="card-body" style="padding-bottom: 2px">
                    <form id="purchaseForm" action="" method="POST">

                        <div class="row">

                            <!-- Date -->
                            <div class="form-group col-md-4">
                                <label>Date*</label>
                                <input type="datetime-local" value="{{ $purchases->date }}" name=""
                                  id="dateInput"  class="form-control">

                            </div>

                            <div class="form-group col-md-4">
                                <label>Customer</label>
                                <div class="input-group">
                                    <select id="supplierDropdown" class="form-control">
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}"
                                                {{ $supplier->id == $purchases->supplier_id ? 'selected' : '' }}>
                                                {{ $supplier->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            @if ($moduleStatuses['warehouses'])
                                <div class="form-group col-md-4">
                                    <label>Warehouse</label>
                                    <select id="warehouseSelect" class="form-control">
                                        <option value=''>Select Warehouse</option>
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}"
                                                {{ $warehouse->id == $purchases->warehouse_id ? 'selected' : '' }}>
                                                {{ $warehouse->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            @endif


                        </div>

                        <br>

                        <div class="row">

                            <div class="form-group  col-md-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="mdi mdi-barcode-scan barcode-text "></span>
                                    </div>
                                    <input type="text" placeholder="Scan/Search Product by code or name"
                                        class="form-control" id="search-box" autocomplete="off" autofocus>
                                </div>

                            </div>

                            <div class="form-group col-md-6">
                                <div id="searchResults"></div>
                            </div>
                        </div>
                        <br><br>



                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table my-table">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Net Unit Price</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="9">No data Available</td>
                                        </tr>
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
                                                <span id="display_discount"> ₹ 0 (Fixed) </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bold">Tax </td>
                                            <td id="display_tax"></td>
                                        </tr>
                                        <tr>
                                            <td class="bold">Shipping</td>
                                            <td id="display_shipping"></td>
                                        </tr>
                                        <tr>
                                            <td><span class="font-weight-bold">Grand Total</span></td>
                                            <td class="font-weight-bold" id="grandTotal"></td>
                                        </tr>
                                    </tbody>

                                </table>

                            </div>
                        </div>

                        <div class="row">

                            <div class="form-group col-md-4">
                                <label>Discount :</label>
                                <div class="discount-input input-group">
                                    <div class="input-group-prepend">
                                        <select id="discountType" class="form-control">
                                            <option value="fixed"> ₹ </option>
                                            <option value="percent"> % </option>
                                        </select>
                                    </div>
                                    <input type="number" id="discountAmount" class="form-control" min="0">

                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Tax Rate:</label>
                                <div class="tax-input input-group">
                                    <input type="text" id="taxRate" inputmode="decimal" pattern="[0-9]+(\.[0-9]{1,2})?"
                                        class="form-control">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>


                            <!-- Shipping Amount -->

                            <div class="form-group col-md-4">
                                <label>Shipping Amount</label>
                                <input type="number" id="shippingAmount" class="form-control" min="0">
                            </div>
                        </div>


                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Please provide any details</label>
                                <textarea id="otherDetails" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="form-group col-md-6 text-center">
                                <br> <br>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        </div>


                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('styles')
    <style>
        #searchResults {
            position: absolute;
            z-index: 1000;
            width: 100%;
        }

        .list-group-item {
            cursor: pointer;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
        }

        .my-table .form-control {
            min-width: 50px;
            max-width: 84px;
        }

        .barcode-text {
            display: flex;
            align-items: center;
            padding: 0.59rem 1rem;
            margin-bottom: 0;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            text-align: center;
            white-space: nowrap;
            background-color: #f7f9fc;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }
    </style>
@endpush


@push('scripts')
    <script>
       var EditPurchaseUrl = "{{ route('purchases.editPurchase', ['id' => $purchases->id]) }}";

        const PurchaseslistPage ="{{ route('purchases.index') }}";
        const warehousesModuleEnabled = @json($moduleStatuses['warehouses']);

        // Initialize global variables for calculations
        let totalWithoutTaxAndShipping = 0; // Initialize with subtotal of products
        
        let tax = 0;
        let grandTotal = 0;
        let cartItems = {!! $cartItems !!};

        let purchaseCart = cartItems;


      

        document.addEventListener("DOMContentLoaded", function() {

            document.getElementById('discountAmount').value = {{ $purchases->discount }};

            document.getElementById('taxRate').value = {{ $purchases->tax_rate }};

            document.getElementById('discountType').value = "{{ $purchases->discount_type }}";

            document.getElementById('shippingAmount').value = "{{ $purchases->shipping_amount }}";

        });
    </script>

    <script src="{{ assets('assets/js/editpurchase.js') }}"></script>
@endpush
