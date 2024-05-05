@extends('layouts.pos')

@section('content')
    <div class="content">
        <div class="card card-default">
            <!-- Warehouse and Category Selection -->
            <div class="row">
                <div class="col-lg-8 col-sm-12 pl-4 pt-2">
                    @include('pos.product_selection')
                </div>

                <!-- Cart and Customer Selection -->
                <div class="col-lg-4 col-sm-12 pt-2 pr-4">
                    @include('pos.cart_items')
                </div>
            </div>

            <!-- Create Customer Modal -->
            @include('pos.create_customer_modal')
        </div>
    </div>
@endsection

@push('scripts')
    @include('pos.scripts')
@endpush

@push('styles')
    @include('pos.styles')
@endpush
