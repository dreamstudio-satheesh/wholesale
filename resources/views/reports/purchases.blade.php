@extends('layouts.app')

@section('content')
    <div class="content">

        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h2>Purchases Report</h2>
                </div>

                <div class="card-body">
                    @livewire('reports.purchase-reports')

                </div>

            </div>


        </div>


    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            "use strict";

            if ($("#recent-orders").length) {
                var start = moment().subtract(29, "days");
                var end = moment();

                var cb = function(start, end) {
                    $("#recent-orders .date-range-report span").html(
                        start.format("ll") + " - " + end.format("ll")
                    );
                };

                var dateRangePicker = $("#recent-orders .date-range-report").daterangepicker({
                    startDate: start,
                    endDate: end,
                    opens: 'left',
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                            'month').endOf('month')]
                    }
                }, cb);

                // cb(start, end);

                // Only emit Livewire event when a date range is applied after selection
                dateRangePicker.on('apply.daterangepicker', function(ev, picker) {
                    // Now, dispatch the Livewire event here
                    var startDate = picker.startDate.format('YYYY-MM-DD');
                    var endDate = picker.endDate.format('YYYY-MM-DD');
                    Livewire.dispatch('dateRangeSelected', {
                        startDate: startDate,
                        endDate: endDate
                    });
                });

               
                
            }

        });
    </script>
@endpush
