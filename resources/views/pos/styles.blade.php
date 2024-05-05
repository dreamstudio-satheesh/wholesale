@push('styles')
    <style>
        /* Additional reset for input group to ensure no external styles are causing issues */
        table .input-group {
            display: flex;
            align-items: center;
            width: fit-content;
            /* makes sure the input group only takes as much space as needed */
        }

        /* Reset for form controls to ensure consistency */
        table .form-control {
            width: 60px;
            /* or another fixed width as needed */
            margin: 0;
            height: calc(1.5em + 0.75rem + 2px);
            /* Adjust height to match other inputs */
        }

        /* Ensure the remove button has a consistent line height and box model */
        .remove-item-btn {
            margin-left: 10px;
            line-height: 1;
            padding: 0;
            background: none;
            border: none;
        }



        /* Fix for the table layout */
        .table {
            table-layout: fixed;
            /* Ensures the table respects the given column widths */
            width: 100%;
            /* Sets the table to take up 100% of the container width */
        }

        /* Ensure the table body cells do not wrap unnecessarily */
        .table td.product-name {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }





        /* Adjust the column widths */
        .table th.product-name,
        .table td.product-name {
            width: 40%;
            /* Allocate 50% of the width to the product name */
        }

        .table th.price,
        .table td.price {
            width: 20%;
            /* Allocate 25% of the width to the price */
        }

        .table th.quantity,
        .table td.quantity {
            width: 20%;
            /* Allocate 15% of the width to the quantity */
        }

        .table th.remove,
        .table td.remove {
            width: 10%;
            /* Allocate 10% of the width to the remove button column */
        }


        /* Style for the scrollbar and tbody to ensure proper overflow behavior */
        #cartContainer {
            overflow-y: auto;
            max-height: 340px;
            /* Adjust this value to your preference */
        }

        .product-card .quantity {
            position: absolute;
            top: 12px;
            left: 0;
            /* width: 50px; */
            width: auto;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e9e3e3;
            padding: 3px 8px 1px 12px;
            border-radius: 0px 6px 6px 0px;
            font-size: 12px;
            color: #131414;
            white-space: nowrap;
        }
    </style>
@endpush
