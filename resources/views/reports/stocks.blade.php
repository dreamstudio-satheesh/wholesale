@extends('layouts.app')




@section('content')
    <div class="content">

        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h2>Stocks Report</h2>

                  
                </div>

                <div class="card-body">
                    @livewire('reports.stock-reports')
                   
                </div>

            </div>


        </div>


    </div>
@endsection

