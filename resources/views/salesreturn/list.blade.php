<div>
    <div class="row">
        <div class="col-md-9">
            <button class="btn btn-outline-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                EXPORT
            </button>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{ url('salereturn/export/pdf') }}">PDF</a>
                <a class="dropdown-item" href="{{ url('salereturn/export/excel') }}">Excel</a>
                <a class="dropdown-item" href="{{ url('salereturn/export/csv') }}">CSV</a>
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
            @foreach ($salesreturn as $item)
                <tr>
                    <td scope="row">{{ $item->return_invoice_number }}</td>
                    <td scope="row">{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y H:i') }}</td>
                    <td scope="row">{{ $item->customer->name }}</td>
                    <td scope="row">{{ $item->warehouse->name }}</td>

                    <td scope="row">
                        @switch($item->payment_status)
                            @case('Unpaid')
                                <span class="btn btn-sm  btn-outline-warning">{{ $item->payment_status }}</span>
                            @break

                            @case('Partial')
                                <span class="btn btn-sm btn-outline-info">{{ $item->payment_status }}</span>
                            @break

                            @case('Paid')
                                <span class="btn btn-sm  btn-outline-primary">{{ $item->payment_status }}</span>
                            @break

                            @default
                                <span class="btn btn-sm  btn-outline-default">{{ $item->payment_status }}</span>
                        @endswitch
                    </td>

                    <td scope="row">{{ config('settings.currency_symbol') }} {{ number_format($item->grand_total, 2) }}</td>
                    <td scope="row">
                        <div class="input-group">
                            <button class="btn btn-sm btn-outline-success dropdown-toggle" type="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"
                                data-display="static">Action</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('salesreturn.show', $item->id) }}"><span class="mdi mdi-eye-outline"> Sales Return
                                        Detail</span></a>
                                <a class="dropdown-item" href="{{ route('salesreturn.edit', $item->id) }}"><span
                                        class="mdi mdi-square-edit-outline">Edit Sales Return</span></a>
                                
                                <a class="dropdown-item" href="#" x-data="{ saleID: {{ $item->id }} }" @click="confirmDeletion(saleID)"> <span class="mdi mdi-backspace-outline"> Delete Sales Return</span></a>


                                <div role="separator" class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item" wire:click="showPayment({{ $item->id }})"><span class="mdi mdi-cash-multiple"> Show
                                        Payment</span></a>
                                @if ( $item->payment_status !== 'Paid')
                                <a href="#" class="dropdown-item" wire:click="createPayment({{ $item->id }})"><span class="mdi mdi-file-upload-outline"> Create Payment</span></a>
                                @endif   

                                <div role="separator" class="dropdown-divider"></div>
                                
                                <a class="dropdown-item" href="{{ url('salesreturn/download',$item->id) }}"><span class="mdi mdi-file-download-outline">
                                        Download Pdf</span></a>

                              
                               
                            </div>

                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $salesreturn->links() }}

    @livewire('sales-return.create-payment-modal')
    @livewire('sales-return.show-payment-modal')
</div>
