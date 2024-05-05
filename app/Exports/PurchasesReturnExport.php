<?php

namespace App\Exports;

use App\Models\PurchaseReturn;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PurchasesReturnExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Eager load the customer and warehouse relationships
        return Purchasereturn::with(['supplier', 'warehouse'])->get();
    }

    /**
     * Map the data for each sale into a format suitable for the export.
     *
     * @param mixed $sale
     * @return array
     */
    public function map($purchase): array
    {
        return [
            $purchase->id,
            $purchase->date,
            $purchase->supplier->name, // Assuming 'name' is the column you want from the customer table
            $purchase->warehouse->name, // Assuming 'name' is the column you want from the warehouse table
            $purchase->status,
            $purchase->grand_total, // Assuming 'grand_total' is the column in your sales table
        ];
    }

    /**
     * Return the headings for the excel file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Id',
            'Date',
            'Supplier',
            'Warehouse',
            'Status',
            'Amount',
        ];
    }
}
