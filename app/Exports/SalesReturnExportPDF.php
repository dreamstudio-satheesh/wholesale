<?php

namespace App\Exports;

use App\Models\SalesReturn;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class SalesReturnExportPDF implements FromView
{
    /**
     * Return the view for the PDF export.
     *
     * @return View
     */
    public function view(): View
    {
        // Eager load related customer and warehouse data
        $salesreturn = SalesReturn::with(['customer', 'warehouse'])->get();

        // Pass the sales data to the view
        return view('exports.salesreturn', compact('salesreturn'));
    }
}

