<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Eager load the customer and warehouse relationships
        return Sale::with(['customer', 'warehouse'])->get();
    }

    /**
     * Map the data for each sale into a format suitable for the export.
     *
     * @param mixed $sale
     * @return array
     */
    public function map($sale): array
    {
        return [
            $sale->invoice_number,
            $sale->date,
            $sale->customer->name, // Assuming 'name' is the column you want from the customer table
            $sale->warehouse->name, // Assuming 'name' is the column you want from the warehouse table
            $sale->status,
            $sale->grand_total, // Assuming 'grand_total' is the column in your sales table
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
            'Invoice Number',
            'Date',
            'Customer',
            'Warehouse',
            'Status',
            'Amount',
        ];
    }
}
