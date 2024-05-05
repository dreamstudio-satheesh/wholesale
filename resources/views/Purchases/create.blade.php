@extends('layouts.app')



@section('content')
    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <form id="purchaseForm" action="" method="POST">
                    <div class="row">

                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h5>Add Purchase</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Supplier</label>
                                            <div class="input-group">
                                                <select id="supplierDropdown" class="form-control">
                                                    @foreach ($suppliers as $supplier)
                                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="input-group-append">
                                                    <button type="button" data-toggle="modal"
                                                        data-target="#createSupplierModal">
                                                        <span class="input-group-text"><i class="mdi mdi-plus"></i></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($moduleStatuses['warehouses'])
                                            <div class="form-group col-md-6">
                                                <label>Warehouse</label>
                                                <select id="warehouseSelect" class="form-control">
                                                    <option value=''>Select Warehouse</option>
                                                    @foreach ($warehouses as $warehouse)
                                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        @endif


                                    </div>

                                    <div class="row">
                                        <div class="form-group  col-md-12">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="mdi mdi-barcode-scan barcode-text "></span>
                                                </div>
                                                <input type="text" placeholder="Scan/Search Product by code or name"
                                                    class="form-control" id="search-box" autocomplete="off" autofocus>
                                            </div>

                                        </div>

                                        <div class="form-group col-md-10">
                                            <div id="searchResults"></div>
                                        </div>
                                    </div>

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
                                        <div class="form-group col-md-8">
                                            <label>Please provide any details</label>
                                            <textarea id="otherDetails" class="form-control" rows="2"></textarea>
                                        </div>
                                        <div class="form-group col-md-4 text-center">
                                            <br> <br>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
            
                                    </div>
            

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">

                                <div class="card-body">
                                        <!-- Date -->
                                        <div class="form-group col-md-12">
                                            <label>Date*</label>
                                            <input type="datetime-local"
                                                value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" name=""
                                                id="dateInput" class="form-control">

                                        </div>
                                    

                                        <div class="form-group col-md-12">
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
            
                                        <div class="form-group col-md-12">
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
            
                                        <div class="form-group col-md-12">
                                            <label>Shipping Amount</label>
                                            <input type="number" id="shippingAmount" class="form-control" min="0">
                                        </div>
                                   

                                        <div class="form-group col-md-12">
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
                            </div>
                        </div>
                </form>
            </div>
        </div>



        @include('purchases.supplier_modal')

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
        var createPurchaseUrl = "{{ route('purchases.createPurchase') }}";
        let purchaseCart = []; // Initialize the cart as an empty array
        let addSupplierUrl = "{{ route('suppliers.addsupplier') }}";
        const stocksModuleEnabled = @json($moduleStatuses['stocks']);
        const warehousesModuleEnabled = @json($moduleStatuses['warehouses']);

        // Initialize global variables for calculations
        let totalWithoutTaxAndShipping = 0; // Initialize with subtotal of products
        let tax = 0;
        let grandTotal = 0;
    </script>



    <script src="{{ assets('assets/js/purchase.js') }}"></script>
@endpush
