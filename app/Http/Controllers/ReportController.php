<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\SalesItem;
use Illuminate\View\View;
use App\Models\PaymentSale;
use App\Models\SalesReturn;
use Illuminate\Http\Request;
use App\Models\PurchaseReturn;
use App\Models\PaymentPurchase;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:generate_reports');
    }
    //

    public function profitloss(Request $request): View
    {
        // Get start and end dates from the request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Set default dates if not provided in the request
        if (empty($startDate) || empty($endDate)) {
            $startDate = Carbon::today()->subDays(29)->toDateString();
            $endDate = Carbon::today()->toDateString();
        }

        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        // Calculate total sales count and  sales amount
        $totalSalesCount = Sale::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalSalesAmount = Sale::whereBetween('created_at', [$startDate, $endDate])->sum('grand_total');

        $SalesReturnCount = SalesReturn::whereBetween('created_at', [$startDate, $endDate])->count();
        $SalesReturnAmount = SalesReturn::whereBetween('created_at', [$startDate, $endDate])->sum('grand_total');

        // Calculate total purchase count
        $totalPurchasesCount = Purchase::whereBetween('created_at', [$startDate, $endDate])->count();

        // Calculate total purchase amount
        $totalPurchasesAmount = Purchase::whereBetween('created_at', [$startDate, $endDate])->sum('grand_total');

        // Calculate total purchase return count
        $PurchaseReturnCount = PurchaseReturn::whereBetween('created_at', [$startDate, $endDate])->count();

        // Calculate total purchase return amount
        $PurchaseReturnAmount = PurchaseReturn::whereBetween('created_at', [$startDate, $endDate])->sum('grand_total');

        // Calculate expense amount
        $ExpenseAmount = Expense::whereBetween('created_at', [$startDate, $endDate])->sum('amount');

        // Calculate Payment Sales amount
        $PaymentSaleAmount = PaymentSale::whereBetween('created_at', [$startDate, $endDate])->sum('amount');

        $PaymentPurchaseAmount = PaymentPurchase::whereBetween('created_at', [$startDate, $endDate])->sum('amount');

        $totalProductCost = SalesItem::join('products', 'sales_items.product_id', '=', 'products.id')->sum(\DB::raw('sales_items.quantity * products.cost'));

        $RevenueAmount = $totalSalesAmount - $SalesReturnAmount;

        $GrossProfit = $totalSalesAmount - $totalProductCost;

        $PaymentsRecived = $PaymentSaleAmount + $PurchaseReturnAmount;

        $PaymentSent = $PaymentPurchaseAmount + $SalesReturnAmount + $ExpenseAmount;

        $reportData = [
            'SalesCount' => $totalSalesCount,
            'SalesAmount' => $totalSalesAmount,
            'SalesReturnCount' => $SalesReturnCount,
            'SalesReturnAmount' => $SalesReturnAmount,
            'PurchasesCount' => $totalPurchasesCount,
            'PurchasesAmount' => $totalPurchasesAmount,
            'PurchaseReturnCount' => $PurchaseReturnCount,
            'PurchaseReturnAmount' => $PurchaseReturnAmount,
            'ExpenseAmount' => $ExpenseAmount,
            'RevenueAmount' => $RevenueAmount,
            'totalProductCost' => $totalProductCost,
            'GrossProfit' => $GrossProfit,
            'PaymentSaleAmount' => $PaymentSaleAmount,
            'PaymentsRecived' => $PaymentsRecived,
            'PaymentPurchaseAmount' => $PaymentPurchaseAmount,
            'PaymentSent' => $PaymentSent,
            // Include other relevant data
        ];

        return view('reports.profitloss', compact('reportData'));
    }

    public function stocks(): View
    {
        return view('reports.stocks');
    }

    public function products()
    {
        return view('reports.products');
    }

    public function sales(): View
    {
        return view('reports.sales');
    }

    public function purchases(): View
    {
        return view('reports.purchases');
    }

    public function suppliers(): View
    {
        return view('reports.suppliers');
    }

    public function customers(): View
    {
        return view('reports.customers');
    }
}
