<?php

namespace App\Exports;

use App\Models\PurchaseReturn;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class PurchasesReturnExportPDF implements FromView
{
    /**
     * Return the view for the PDF export.
     *
     * @return View
     */
    public function view(): View
    {
        // Eager load related customer and warehouse data
        $purchases = PurchaseReturn::with(['supplier', 'warehouse'])->get();

        // Pass the purchases data to the view
        return view('exports.purchases', compact('purchases'));
    }
}

