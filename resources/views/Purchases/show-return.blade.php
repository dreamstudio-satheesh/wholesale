@extends('layouts.app')



@section('content')
    <div class="content">





        <div class="invoice-wrapper rounded border bg-white py-5 px-3 px-md-4 px-lg-5">
            <div class="d-flex justify-content-between">
                <h2 class="text-dark font-weight-medium">Invoice #{{ $purchasesreturn->return_invoice_number }}</h2>

                <div class="btn-group">

                    <button class="btn btn-sm btn-secondary">
                        <i class="mdi mdi-printer"></i> Print
                    </button>
                </div>
            </div>

            <div class="row pt-5">
                <div class="col-xl-4 col-lg-4">
                    <p class="text-dark mb-2">From</p>

                    <address>
                        {{ config('settings.company_name') }}
                        <br> {{ config('settings.company_address') }}
                        <br> Email: {{ config('settings.company_email') }}
                        <br> Phone: {{ config('settings.company_phone') }}
                    </address>
                </div>

                <div class="col-xl-4 col-lg-4">
                    <p class="text-dark mb-2">To</p>

                    <address>
                        {{ $purchasesreturn->supplier->name }}
                        <br> {{ $purchasesreturn->supplier->address }}
                        <br> Email: {{ $purchasesreturn->supplier->email }}
                        <br> Phone: {{ $purchasesreturn->supplier->phone }}
                    </address>
                </div>

                <div class="col-xl-4 col-lg-4">
                    <p class="text-dark mb-2">Details</p>

                    <address>
                        Invoice ID:
                        <span class="text-dark">#{{ $purchasesreturn->id }}</span>
                        <br> {{ \Carbon\Carbon::parse($purchasesreturn->date)->format('F d, Y') }}
                        <br> VAT: {{ config('settings.company_vat') }}
                    </address>
                </div>
            </div>

            <table class="table mt-3 py-5 table-striped table-responsive table-responsive-large" style="width:100%">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Unit Cost</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($purchasesreturn->items as $item)
                        @if ($item->quantity > 0)
                            <tr>
                                <td>#{{ $item->product->sku }}</td>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ config('settings.currency_symbol') }} {{ $item->price }}</td>
                                <td>{{ config('settings.currency_symbol') }} {{ $item->subtotal }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

            <div class="row justify-content-end">
                <div class="col-lg-5 col-xl-4 col-xl-3 ml-sm-auto">
                    <table class="table table-bordered table-sm">
                        <tbody>

                            <tr>
                                <td class="bold">Discount</td>
                                <td>
                                    <span id="display_discount">{{ config('settings.currency_symbol') }}
                                        {{ $purchasesreturn->discount_amount }}
                                        ({{ ucfirst($purchasesreturn->discount_type) }})
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="bold">Tax </td>
                                <td id="display_tax">{{ config('settings.currency_symbol') }}
                                    {{ $purchasesreturn->tax_amount }} ({{ $purchasesreturn->tax_rate }}%)</td>
                            </tr>
                            <tr>
                                <td class="bold">Shipping</td>
                                <td id="display_shipping">{{ config('settings.currency_symbol') }}
                                    {{ $purchasesreturn->shipping_amount }}</td>
                            </tr>
                            <tr>
                                <td><span class="font-weight-bold">Grand Total</span></td>
                                <td class="font-weight-bold" id="grandTotal">{{ config('settings.currency_symbol') }}
                                    {{ $purchasesreturn->grand_total }}</td>
                            </tr>
                        </tbody>

                    </table>


                </div>
            </div>
        </div>





    </div> <!-- End Content -->
@endsection
