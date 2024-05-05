<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class SalesExportPDF implements FromView
{
    /**
     * Return the view for the PDF export.
     *
     * @return View
     */
    public function view(): View
    {
        // Eager load related customer and warehouse data
        $sales = Sale::with(['customer', 'warehouse'])->get();

        // Pass the sales data to the view
        return view('exports.sales', compact('sales'));
    }
}

