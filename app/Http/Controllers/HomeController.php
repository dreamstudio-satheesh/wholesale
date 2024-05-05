<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\SalesReturn;
use Illuminate\Http\Request;
use App\Models\PurchaseReturn;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get the current date
        $today = Carbon::now()->format('Y-m-d');

        $totalPurchasesAmount = Purchase::sum('grand_total');
        $totalSalesAmount = Sale::sum('grand_total');

        // Query to get today's sales and purchase amounts
        $todaySalesAmount = Sale::whereDate('created_at', $today)->sum('grand_total');
        $todayPurchaseAmount = Purchase::whereDate('created_at', $today)->sum('grand_total');

        $SalesReturnAmount = SalesReturn::sum('grand_total');
        $PurchasesReturnAmount = PurchaseReturn::sum('grand_total');

        // Get the current date and set it to the start of the week (Monday)
        $startDate = Carbon::now()->startOfWeek();

        // Create an array to hold sales and purchase data for each day of the week
        $salesData = [];
        $purchaseData = [];

        // Loop through each day of the week (Monday to Sunday)
        for ($i = 0; $i < 7; $i++) {
            // Get the date for the current day of the week
            $currentDate = $startDate->copy()->addDays($i);

            // Query sales data for the current day
            $dailySalesTotal = Sale::whereDate('date', $currentDate)->sum('grand_total');

            // Query purchase data for the current day
            $dailyPurchaseTotal = Purchase::whereDate('date', $currentDate)->sum('grand_total');

            // Add the daily sales and purchase totals to the respective arrays
            $salesData[] = $dailySalesTotal;
            $purchaseData[] = $dailyPurchaseTotal;
        }

        // Query to get top five selling products with their counts
        $topSellingProducts = DB::table('sales_items')
            ->join('products', 'sales_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('COUNT(sales_items.product_id) as count'))
            ->groupBy('products.name') // include products.name in the GROUP BY clause
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Convert the query result to an array of label and data objects
        $topSellinglabels = $topSellingProducts->pluck('name')->toArray();
        $topSellingdata = $topSellingProducts->pluck('count')->toArray();

        // Query to get the top five customers with their total sales amounts
        $topFiveCustomers = Customer::select('id', 'name')
            ->withSum('sales', 'grand_total')
            ->whereHas('sales', function ($query) {
                $query->whereMonth('date', Carbon::now()->month);
            })
            ->orderByDesc('sales_sum_grand_total')
            ->take(5)
            ->get();

        // Prepare the data for the polar chart
        $topCustomerslabels = $topFiveCustomers->pluck('name')->toArray();
        $topCustomersdata = $topFiveCustomers->pluck('sales_sum_grand_total')->toArray();

        $reportData = [
            'totalPurchasesAmount' => $totalPurchasesAmount,
            'PurchasesReturnAmount' => $PurchasesReturnAmount,
            'totalSalesAmount' => $totalSalesAmount,
            'SalesReturnAmount' => $SalesReturnAmount,
        ];

        $recent_sales = Sale::with('customer')
            ->orderByDesc('created_at')
            ->take(5)
            ->get(['invoice_number', 'customer_id', 'grand_total', 'paid_amount', 'created_at'])
            ->map(function ($sale) {
                return [
                    'invoice_number' => $sale->invoice_number,
                    'customer_name' => $sale->customer->name, // Accessing customer's name through relationship
                    'grand_total' => $sale->grand_total,
                    'paid' => $sale->paid_amount,
                    'due' => $sale->grand_total - $sale->paid_amount,
                ];
            });

        return view('home', compact('topCustomerslabels', 'topCustomersdata', 'todaySalesAmount', 'todayPurchaseAmount', 'reportData', 'salesData', 'purchaseData', 'topSellinglabels', 'topSellingdata','recent_sales'));
    }
}
