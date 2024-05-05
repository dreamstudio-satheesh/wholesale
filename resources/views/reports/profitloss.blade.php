@extends('layouts.app')


@section('content')
    <div class="content">

        <div class="col-lg-12">
            <div class="card card-default">
                <div id="recent-orders" class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h2>Profit and Loss Report</h2>

                    <div class=" text-right date-range-report ">
                        <span> </span>

                        <form method="POST" action="{{ route('reports.profit-loss') }}">
                            @csrf
                            <input type="hidden" id="start_date" name="start_date">
                            <input type="hidden" id="end_date" name="end_date">
                        </form>

                    </div>

                </div>

                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-xl-4">
                            <div class="media widget-media p-4 bg-white border">
                                <div class="icon rounded-circle mr-4 bg-danger">
                                    <i class="mdi mdi-cart-outline text-white "></i>
                                </div>

                                <div class="media-body align-self-center">
                                    <h4 class="text-primary mb-2">{{ config('settings.currency_symbol') }}
                                        {{ $reportData['SalesAmount'] }}</h4>
                                    <p>Total Sales ({{ $reportData['SalesCount'] }})</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-6 col-xl-4">
                            <div class="media widget-media p-4 bg-white border">
                                <div class="icon rounded-circle mr-4 bg-primary">
                                    <i class="mdi mdi-shopping text-white "></i>
                                </div>

                                <div class="media-body align-self-center">
                                    <h4 class="text-primary mb-2">{{ config('settings.currency_symbol') }}
                                        {{ $reportData['PurchasesAmount'] }}</h4>
                                    <p>Total Purchases ({{ $reportData['PurchasesCount'] }})</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-6 col-xl-4">
                            <div class="media widget-media p-4 bg-white border">
                                <div class="icon rounded-circle bg-warning mr-4">
                                    <i class="mdi mdi-subdirectory-arrow-left text-white "></i>
                                </div>

                                <div class="media-body align-self-center">
                                    <h4 class="text-primary mb-2">{{ config('settings.currency_symbol') }}
                                        {{ $reportData['SalesReturnAmount'] }}</h4>
                                    <p>Sales Return ({{ $reportData['SalesReturnCount'] }})</p>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-xl-4">
                            <div class="media widget-media p-4 bg-white border">
                                <div class="icon bg-success rounded-circle mr-4">
                                    <i class="mdi mdi-subdirectory-arrow-right text-white "></i>
                                </div>

                                <div class="media-body align-self-center">
                                    <h4 class="text-primary mb-2">{{ config('settings.currency_symbol') }}
                                        {{ $reportData['PurchaseReturnAmount'] }}</h4>
                                    <p>Purchases Return ({{ $reportData['PurchaseReturnCount'] }})</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-6 col-xl-4">
                            <div class="media widget-media p-4 bg-white border">
                                <div class="icon rounded-circle mr-4 bg-danger">
                                    <i class="mdi  mdi-cash-100 text-white "></i>
                                </div>

                                <div class="media-body align-self-center">
                                    <h4 class="text-primary mb-2">{{ config('settings.currency_symbol') }}
                                        {{ $reportData['ExpenseAmount'] }}</h4>
                                    <p>Expense</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-6 col-xl-4">
                            <div class="card custom-card">
                                <div class="card-body d-flex flex-column">

                                    <div class="d-flex flex-row align-items-center justify-content-start mt-auto">
                                        <div
                                            class="icon bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="mdi mdi-cash-multiple mdi-24px text-white "></i>
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-primary mb-2">{{ config('settings.currency_symbol') }}
                                                {{ $reportData['RevenueAmount'] }}</h4>
                                            <p>Revenue</p>
                                        </div>
                                    </div>

                                </div>
                                <div class="mt-4 custom-card-footer">
                                    ({{ config('settings.currency_symbol') }} {{ $reportData['SalesAmount'] }} Sales) -
                                    ({{ config('settings.currency_symbol') }} {{ $reportData['SalesReturnAmount'] }} Sales
                                    Return)
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-lg-4 mb-4">
                            <div class="card custom-card">
                                <div class="card-body d-flex flex-column">

                                    <div class="d-flex flex-row align-items-center justify-content-start mt-auto">
                                        <div
                                            class="icon bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="mdi mdi-currency-usd mdi-24px text-white "></i>
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-primary mb-2">{{ config('settings.currency_symbol') }}
                                                {{ $reportData['GrossProfit'] }}</h4>
                                            <p>Gross Profit</p>
                                        </div>
                                    </div>

                                </div>
                                <div class="mt-4 custom-card-footer">
                                    ({{ config('settings.currency_symbol') }} {{ $reportData['SalesAmount'] }} Sales)
                                    - ({{ config('settings.currency_symbol') }} {{ $reportData['totalProductCost'] }}
                                    Product Cost)
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-4 mb-4">
                            <div class="card custom-card">
                                <div class="card-body d-flex flex-column">

                                    <div class="d-flex flex-row align-items-center justify-content-start mt-auto">
                                        <div
                                            class="icon bg-warning rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="mdi mdi-cash mdi-24px text-white "></i>
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-primary mb-2">{{ config('settings.currency_symbol') }}
                                                {{ $reportData['PaymentsRecived'] }}</h4>
                                            <p>Payments Received</p>
                                        </div>
                                    </div>

                                </div>
                                <div class="mt-4 custom-card-footer">
                                    ({{ config('settings.currency_symbol') }} {{ $reportData['PaymentSaleAmount'] }}
                                    Payment sale) + ({{ config('settings.currency_symbol') }}
                                    {{ $reportData['PurchaseReturnAmount'] }} Purchases Return)
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-4 mb-4">
                            <div class="card custom-card">
                                <div class="card-body d-flex flex-column">

                                    <div class="d-flex flex-row align-items-center justify-content-start mt-auto">
                                        <div
                                            class="icon bg-success rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="mdi mdi-cash-refund mdi-24px text-white "></i>
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-primary mb-2">{{ config('settings.currency_symbol') }}
                                                {{ $reportData['PaymentSent'] }}</h4>
                                            <p>Payments Sent</p>
                                        </div>
                                    </div>

                                </div>
                                <div class="mt-4 custom-card-footer">
                                    ({{ config('settings.currency_symbol') }} {{ $reportData['PaymentPurchaseAmount'] }}
                                    Payment purchase) + ({{ config('settings.currency_symbol') }}
                                    {{ $reportData['SalesReturnAmount'] }} Sales Return) + (
                                    {{ config('settings.currency_symbol') }} {{ $reportData['ExpenseAmount'] }} Expenses)
                                </div>
                            </div>

                        </div>

                    </div>






                </div>

            </div>


        </div>


    </div>
@endsection

@push('styles')
    <style>
        .icon {
            width: 70px;
            height: 70px;
            text-align: center;
            line-height: 70px;
            display: flex;
            /* Added for centering the icon with flexbox */
            justify-content: center;
            /* Added for centering the icon horizontally */
            align-items: center;
            /* Added for centering the icon vertically */

        }

        .custom-card {
            border: 1px solid #e5e9f2;
            border-radius: 4px;
            background-color: #fff;
        }

        .custom-card .card-body {
            display: flex;
            padding: 0.725rem;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .custom-card-footer {
            background-color: #f0f4f7;
            color: #7b8a8b;
            padding: 0.75rem;
            border-radius: 0 0 4px 4px;
            position: relative;
            /* Change to 'relative' if 'absolute' positioning causes issues */
            bottom: 0;
            width: 100%;
        }

        .card-title {
            margin-bottom: 0.5rem;
        }

        .card-text {
            font-weight: bold;
        }

        @media (min-width: 992px) {

            /* Adjust this media query based on your layout's requirements */
            .custom-card {
                display: flex;
                flex-direction: column;
                height: 100%;
            }

            .custom-card .card-body {
                flex: 1;
                /* This allows the card body to expand and push the footer down */
            }
        }
    </style>
@endpush


@push('scripts')
    <script>
        $(function() {
            if ($("#recent-orders").length) {
                var start = moment().subtract(29, "days");
                var end = moment();
                var cb = function(start, end) {
                    $("#recent-orders .date-range-report span").html(
                        start.format("ll") + " - " + end.format("ll")
                    );
                };

                $("#recent-orders .date-range-report").daterangepicker({
                    startDate: start,
                    endDate: end,
                    opens: 'left',
                    ranges: {
                        Today: [moment(), moment()],
                        Yesterday: [
                            moment().subtract(1, "days"),
                            moment().subtract(1, "days")
                        ],
                        "Last 7 Days": [moment().subtract(6, "days"), moment()],
                        "Last 30 Days": [moment().subtract(29, "days"), moment()],
                        "This Month": [moment().startOf("month"), moment().endOf("month")],
                        "Last Month": [
                            moment()
                            .subtract(1, "month")
                            .startOf("month"),
                            moment()
                            .subtract(1, "month")
                            .endOf("month")
                        ]
                    }
                }, cb);

                $("#recent-orders .date-range-report").on('apply.daterangepicker', function(ev, picker) {
                    var startDate = picker.startDate.format('YYYY-MM-DD');
                    var endDate = picker.endDate.format('YYYY-MM-DD');

                    // Update the hidden inputs
                    $("#start_date").val(startDate);
                    $("#end_date").val(endDate);

                    // Submit the form
                    $("form").submit();
                });

                cb(start, end);
            }
        });
    </script>
@endpush
