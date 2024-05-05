@extends('layouts.app')

@section('content')
    <div class="content">

        @if (auth()->user()->can('view_dashboard'))
            <!-- Top Statistics -->
            <div class="row">
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="card widget-block p-4 rounded bg-primary border">
                        <div class="card-block">
                            <i class="mdi mdi-cart-outline mdi-36px mr-4 text-white"></i>
                            <h4 class="text-white my-2">{{ config('settings.currency_symbol') }}
                                {{ $reportData['totalSalesAmount'] }}</h4>
                            <p>Total Sales</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="card widget-block p-4 rounded bg-warning border">
                        <div class="card-block">
                            <i class="mdi mdi-arrow-left mdi-36px mr-4 text-white"></i>
                            <h4 class="text-white my-2">{{ config('settings.currency_symbol') }}
                                {{ $reportData['SalesReturnAmount'] }}</h4>
                            <p>Total Sales Return</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="card widget-block p-4 rounded bg-danger border">
                        <div class="card-block">
                            <i class="mdi mdi-cart-plus mdi-36px mr-4 text-white"></i>
                            <h4 class="text-white my-2">{{ config('settings.currency_symbol') }}
                                {{ $reportData['totalPurchasesAmount'] }}</h4>
                            <p>Total Purchase</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="card widget-block p-4 rounded bg-success border">
                        <div class="card-block">
                            <i class="mdi mdi-arrow-right mdi-36px mr-4 text-white"></i>
                            <h4 class="text-white my-2">{{ config('settings.currency_symbol') }}
                                {{ $reportData['PurchasesReturnAmount'] }}</h4>
                            <p>Total Purchase Return</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-8 col-md-12">

                    <!-- Sales Graph -->
                    <div class="card card-default">
                        <div class="card-header">
                            <h2>Sales Of The Week</h2>
                        </div>
                        <div class="card-body">
                            <canvas id="myChart" class="chartjs"></canvas>
                        </div>
                        <div class="card-footer d-flex flex-wrap bg-white p-0">
                            <div class="col-6 px-0">
                                <div class="text-center p-4">
                                    <h4>{{ config('settings.currency_symbol') }} {{ $todaySalesAmount }}</h4>
                                    <p class="mt-2">Today's Total Sales</p>
                                </div>
                            </div>
                            <div class="col-6 px-0">
                                <div class="text-center p-4 border-left">
                                    <h4>{{ config('settings.currency_symbol') }} {{ $todayPurchaseAmount }}</h4>
                                    <p class="mt-2">Today's Total Purchase</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-xl-4 col-md-12">

                    <!-- Doughnut Chart -->
                    <div class="card card-default">
                        <div class="card-header justify-content-center">
                            <h2>Top Selling Products</h2>
                        </div>
                        <div style="min-height: 550px" class="card-body">
                            <canvas id="myDoughnutChart"></canvas>
                        </div>


                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-xl-4 col-lg-6 col-12">

                    <!-- Polar and Radar Chart -->
                    <div class="card card-default">
                        <div class="card-header justify-content-center">
                            <h2>Top Customers ({{ now()->format('M, y') }})</h2>
                        </div>
                        <div style="min-height: 470px" class="card-body pt-0">
                            <canvas id="myPieChart"></canvas>
                        </div>
                    </div>

                </div>



                <div class="col-xl-8 col-12">

                    <!-- Recent Order Table -->
                    <div class="card card-table-border-none recent-orders" id="recent-orders">
                        <div class="card-header justify-content-between">
                            <h2>Recent Orders</h2>
                            <div class="date-range-report">
                                <span>Jan 28, 2024 - Feb 26, 2024</span>
                            </div>
                        </div>
                        <div class="card-body pt-0 pb-5">
                            <table class="table card-table table-responsive table-responsive-large" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th> Name</th>
                                        <th class="d-none d-lg-table-cell"> Grand Total</th>
                                        <th class="d-none d-lg-table-cell"> Paid</th>
                                        <th>Due</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recent_sales as $sale)
                                        <tr>
                                            <td>#{{ $sale['invoice_number'] }}</td>
                                            <td>
                                                <a class="text-dark" href="">{{ $sale['customer_name'] }}</a>
                                            </td>
                                            <td class="d-none d-lg-table-cell">{{ config('settings.currency_symbol') }} {{ $sale['grand_total'] }}</td>
                                            <td class="d-none d-lg-table-cell">{{ config('settings.currency_symbol') }} {{ $sale['paid'] }}</td>
                                            <td>
                                              {{ config('settings.currency_symbol') }} {{ $sale['due'] }}
                                            </td>

                                        </tr>
                                    @endforeach


                                </tbody>
                            </table>
                        </div>


                    </div>

                </div>
            </div>
        @else
            <h1>Hello {{ auth()->user()->name }}, Welcome to Dashboard</h1>
        @endif
    </div> <!-- End Content -->
@endsection



@push('scripts')
    <script>
        // Assuming you have the labels and data for the current week
        var labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        var salesData = {!! json_encode($salesData) !!};
        var purchaseData = {!! json_encode($purchaseData) !!};

        // Create a new Chart.js chart
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sales',
                    data: salesData,
                    backgroundColor: '#4c84ff',
                }, {
                    label: 'Purchases',
                    data: purchaseData,
                    backgroundColor: '#29cc97',
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });


        // Create a new Chart.js chart
        var ctx = document.getElementById('myDoughnutChart').getContext('2d');
        var myDoughnutChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($topSellinglabels) !!},
                datasets: [{
                    data: {!! json_encode($topSellingdata) !!},
                    backgroundColor: ["#4c84ff", "#29cc97", "#8061ef", "#fec402", "#ff9800"],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        fontColor: '#333',
                        fontSize: 14,
                        boxWidth: 40,
                        padding: 5
                    }
                },
                cutoutPercentage: 75,
                tooltips: {
                    callbacks: {
                        title: function(tooltipItem, data) {
                            return "Order : " + data["labels"][tooltipItem[0]["index"]];
                        },
                        label: function(tooltipItem, data) {
                            return data["datasets"][0]["data"][tooltipItem["index"]];
                        }
                    },
                    titleFontColor: "#888",
                    bodyFontColor: "#555",
                    titleFontSize: 12,
                    bodyFontSize: 14,
                    backgroundColor: "rgba(256,256,256,0.95)",
                    displayColors: true,
                    borderColor: "rgba(220, 220, 220, 0.9)",
                    borderWidth: 2
                }
            }
        });




        /*======== PIE CHART ========*/

        var ctx = document.getElementById('myPieChart').getContext('2d');
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($topCustomerslabels) !!},
                datasets: [{
                    data: {!! json_encode($topCustomersdata) !!},
                    backgroundColor: ["#4c84ff", "#29cc97", "#8061ef", "#fec402", "#ff9800"],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        fontColor: '#333',
                        fontSize: 14,
                        boxWidth: 10,
                        padding: 20
                    }
                }
            }
        });
    </script>
@endpush
