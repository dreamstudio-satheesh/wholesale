@extends('layouts.app')



@section('content')
<div class="content">





    <div class="invoice-wrapper rounded border bg-white py-5 px-3 px-md-4 px-lg-5">
        <div class="d-flex justify-content-between">
            <h2 class="text-dark font-weight-medium">Invoice #{{ $purchase->id }}</h2>
    
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
                    {{ $purchase->supplier->name }}
                    <br> {{ $purchase->supplier->address }}
                    <br> Email: {{ $purchase->supplier->email }}
                    <br> Phone: {{ $purchase->supplier->phone }}
                </address>
            </div>
    
            <div class="col-xl-4 col-lg-4">
                <p class="text-dark mb-2">Details</p>
    
                <address>
                    Invoice ID:
                    <span class="text-dark">#{{ $purchase->id }}</span>
                    <br> {{ \Carbon\Carbon::parse($purchase->date)->format('F d, Y') }}
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
                @foreach ($purchase->items as $item)
                <tr>
                    <td>#{{ $item->product->sku }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ config('settings.currency_symbol') }} {{ $item->price }}</td>
                    <td>{{ config('settings.currency_symbol') }} {{ $item->subtotal }}</td>
                </tr>
                    
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
                                <span id="display_discount">{{ config('settings.currency_symbol') }} {{$purchase->discount_amount}} ({{ ucfirst($purchase->discount_type) }})
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="bold">Tax </td>
                            <td id="display_tax">{{ config('settings.currency_symbol') }} {{$purchase->tax_amount}} ({{$purchase->tax_rate}}%)</td>
                        </tr>
                        <tr>
                            <td class="bold">Shipping</td>
                            <td id="display_shipping">{{ config('settings.currency_symbol') }} {{$purchase->shipping_amount}}</td>
                        </tr>
                        <tr>
                            <td><span class="font-weight-bold">Grand Total</span></td>
                            <td class="font-weight-bold" id="grandTotal">{{ config('settings.currency_symbol') }} {{$purchase->grand_total}}</td>
                        </tr>
                    </tbody>

                </table>
    
               
            </div>
        </div>
    </div>
    
    
    
    
    
          </div> <!-- End Content -->
@endsection
