<div>
    <div class="row">
        <div class="col-md-9">
            <button class="btn btn-outline-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                EXPORT
            </button>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{ url('purchasereturn/export/pdf') }}">PDF</a>
                <a class="dropdown-item" href="{{ url('purchasereturn/export/excel') }}">Excel</a>
                <a class="dropdown-item" href="{{ url('purchasereturn/export/csv') }}">CSV</a>
            </div>
        </div>
        <div class="col-md-3 text-right">
            <input wire:model="search" type="text" class="form-control" placeholder="Search sales...">
        </div>
    </div>

    <table class="table mt-3">
        <thead>
            <tr>
                <th scope="col" wire:click="sortBy('return_invoice_number')">
                    Invoice Number
                    @if ($sortField === 'return_invoice_number')
                        <span
                            class="mdi {{ $sortDirection === 'asc' ? 'mdi-sort-ascending' : 'mdi-sort-descending' }}"></span>
                    @endif
                </th>

                <th scope="col" wire:click="sortBy('date')">
                    Date
                    @if ($sortField === 'date')
                        <span
                            class="mdi {{ $sortDirection === 'asc' ? 'mdi-sort-ascending' : 'mdi-sort-descending' }}"></span>
                    @endif
                </th>

                <th scope="col" wire:click="sortBy('supplier_id')">
                    Supplier
                    @if ($sortField === 'customer_id')
                        <span
                            class="mdi {{ $sortDirection === 'asc' ? 'mdi-sort-ascending' : 'mdi-sort-descending' }}"></span>
                    @endif
                </th>

                <th scope="col">Warehouse</th>

                <th scope="col">Status</th>

                <th scope="col" wire:click="sortBy('grand_total')">
                    Amount
                    @if ($sortField === 'grand_total')
                        <span
                            class="mdi {{ $sortDirection === 'asc' ? 'mdi-sort-ascending' : 'mdi-sort-descending' }}"></span>
                    @endif
                </th>


                <!-- Add other headers as needed -->
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchasesreturn as $purchase)
                <tr>
                    <td scope="row">{{ $purchase->return_invoice_number }}</td>
                    <td scope="row">{{ \Carbon\Carbon::parse($purchase->date)->format('d-m-Y H:i') }}</td>
                    <td scope="row">{{ $purchase->supplier->name }}</td>
                    <td scope="row">{{ $purchase->warehouse->name }}</td>

                    <td scope="row">
                        @switch($purchase->payment_status)
                            @case('Unpaid')
                                <span class="btn btn-sm  btn-outline-warning">{{ $purchase->payment_status }}</span>
                            @break

                            @case('Partial')
                                <span class="btn btn-sm btn-outline-info">{{ $purchase->payment_status }}</span>
                            @break

                            @case('Paid')
                                <span class="btn btn-sm  btn-outline-primary">{{ $purchase->payment_status }}</span>
                            @break

                            @default
                                <span class="btn btn-sm  btn-outline-default">{{ $purchase->payment_status }}</span>
                        @endswitch
                    </td>

                    <td scope="row">{{ config('settings.currency_symbol') }} {{ number_format($purchase->grand_total, 2) }}</td>
                    <td scope="row">
                        <div class="input-group">
                            <button class="btn btn-sm btn-outline-success dropdown-toggle" type="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"
                                data-display="static">Action</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('purchasesreturn.show', $purchase->id) }}"><span class="mdi mdi-eye-outline"> Purchase
                                        Detail</span></a>
                                <a class="dropdown-item" href="{{ route('purchasesreturn.editreturn', $purchase->id) }}"><span
                                        class="mdi mdi-square-edit-outline">Edit Return</span></a>

                                        <a class="dropdown-item" href="#" x-data="{ purchaseID: {{ $purchase->id }} }"
                                            @click="confirmDeletion(purchaseID)"> <span class="mdi mdi-backspace-outline">
                                    Delete  Return</span></a>


                                <div role="separator" class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item" wire:click="showPayment({{ $purchase->id }})"><span class="mdi mdi-cash-multiple"> Show
                                        Payment</span></a>
                                @if ( $purchase->payment_status !== 'Paid')
                                <a href="#" class="dropdown-item" wire:click="createPayment({{ $purchase->id }})"><span class="mdi mdi-file-upload-outline"> Create Payment</span></a>
                                @endif   

                                <div role="separator" class="dropdown-divider"></div>
                                
                                <a class="dropdown-item" href="{{ url('purchasereturn/download',$purchase->id) }}"><span class="mdi mdi-file-download-outline">
                                        Download Pdf</span></a>

                              
                               
                            </div>

                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $purchasesreturn->links() }}

    @livewire('purchase-return.create-payment-modal')
    @livewire('purchase-return.show-payment-modal')
</div>
