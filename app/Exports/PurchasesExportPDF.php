<?php

namespace App\Exports;

use App\Models\Purchase;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PurchasesExportPDF implements FromView
{
    /**
     * Return the view for the PDF export.
     *
     * @return View
     */
    public function view(): View
    {
        // Eager load related customer and warehouse data
        $purchases = Purchase::with(['supplier', 'warehouse'])->get();

        // Pass the purchases data to the view
        return view('exports.purchases', compact('purchases'));
    }
}

