@extends('layouts.app')

@section('content')
    <div class="content">

        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h2>Products - Bulk price update  </h2>
                </div>

                <div class="card-body">

                    @livewire('bulk-price-update')
                </div>

            </div>


        </div>
    </div>
@endsection

