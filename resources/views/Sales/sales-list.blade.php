<div>
    <div class="row">
        <div class="col-md-9">
            <div class="dropdown d-inline-block mb-1">
                <button class="btn btn-outline-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                    EXPORT
                </button>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ url('sales/export/pdf') }}">PDF</a>
                    <a class="dropdown-item" href="{{ url('sales/export/excel') }}">Excel</a>
                    <a class="dropdown-item" href="{{ url('sales/export/csv') }}">CSV</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 text-right">
            <input wire:model="search" type="text" class="form-control" placeholder="Search sales...">
        </div>
    </div>

    <table class="table mt-3">
        <thead>
            <tr>
                <th scope="col" wire:click="sortBy('invoice_number')">
                    Invoice Number
                    @if ($sortField === 'invoice_number')
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

                <th scope="col" wire:click="sortBy('customer_id')">
                    Customer
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
            @foreach ($sales as $sale)
                <tr>
                    <td scope="row">{{ $sale->invoice_number }}</td>
                    <td scope="row">{{ \Carbon\Carbon::parse($sale->date)->format('d-m-Y H:i') }}</td>
                    <td scope="row">{{ $sale->customer->name }}</td>
                    <td scope="row">{{ $sale->warehouse->name }}</td>

                    <td scope="row">
                        @switch($sale->payment_status)
                            @case('Unpaid')
                                <span class="btn btn-sm  btn-outline-warning">{{ $sale->payment_status }}</span>
                            @break

                            @case('Partial')
                                <span class="btn btn-sm btn-outline-info">{{ $sale->payment_status }}</span>
                            @break

                            @case('Paid')
                                <span class="btn btn-sm  btn-outline-primary">{{ $sale->payment_status }}</span>
                            @break

                            @default
                                <span class="btn btn-sm  btn-outline-default">{{ $sale->payment_status }}</span>
                        @endswitch
                    </td>

                    <td scope="row">{{ config('settings.currency_symbol') }}
                        {{ number_format($sale->grand_total, 2) }}</td>
                    <td scope="row">
                        <div class="input-group">
                            <button class="btn btn-sm btn-outline-success dropdown-toggle" type="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"
                                data-display="static">Action</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('sales.show', $sale->id) }}"><span
                                        class="mdi mdi-eye-outline"> Sale
                                        Detail</span></a>
                                @if (auth()->user()->can('edit_sales'))
                                    <a class="dropdown-item" href="{{ route('sales.edit', $sale->id) }}"> <span
                                            class="mdi mdi-square-edit-outline">Edit Sale</span></a>
                                @endif

                                @if ($sale->has_return == 0)
                                    @if (auth()->user()->can('delete_sales'))
                                        <a class="dropdown-item" href="#" x-data="{ saleID: {{ $sale->id }} }"
                                            @click="confirmDeletion(saleID)"> <span class="mdi mdi-backspace-outline">
                                                Delete Sale</span></a>
                                    @endif
                                @endif

                                <div role="separator" class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#"
                                    wire:click="showPayment({{ $sale->id }})"><span class="mdi mdi-cash-multiple">
                                        Show
                                        Payment</span></a>
                                @if ($sale->payment_status !== 'Paid')
                                    <a class="dropdown-item" href="#"
                                        wire:click="createPayment({{ $sale->id }})"><span
                                            class="mdi mdi-file-upload-outline"> Create Payment</span></a>
                                @endif

                                <div role="separator" class="dropdown-divider"></div>
                                @if (auth()->user()->can('show_pos'))
                                <a class="dropdown-item" href="{{ route('pos.show', $sale->id) }}" target="blank"><span
                                        class="mdi mdi-cloud-print-outline"> Invoice POS</span></a>
                                @endif        
                                <a class="dropdown-item" href="{{ url('sales/download',$sale->id) }}"><span class="mdi mdi-file-download-outline">
                                        Download Pdf</span></a>
                                @if ($sale->has_return == 0)
                                    <div role="separator" class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('salesreturn.create', $sale->id) }}"><span
                                            class="mdi mdi-file-replace-outline">
                                            Sales Return</span></a>
                                @endif

                            </div>

                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $sales->links() }}

    @livewire('sales.create-payment-modal')
    @livewire('sales.show-payment-modal')
</div>
