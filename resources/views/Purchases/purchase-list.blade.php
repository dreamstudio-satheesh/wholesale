<div>
    <div class="row">
        <div class="col-md-9">
            <button class="btn btn-outline-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                EXPORT
            </button>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{ url('purchases/export/pdf') }}">PDF</a>
                <a class="dropdown-item" href="{{ url('purchases/export/excel') }}">Excel</a>
                <a class="dropdown-item" href="{{ url('purchases/export/csv') }}">CSV</a>
            </div>
        </div>
        <div class="col-md-3 text-right">
            <input wire:model="search" type="text" class="form-control" placeholder="Search purchases...">
        </div>
    </div>

    <table class="table mt-3">
        <thead>
            <tr>
                <th scope="col" wire:click="sortBy('id')">
                    Id
                    @if ($sortField === 'id')
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
                    @if ($sortField === 'supplier_id')
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
            @foreach ($purchases as $purchase)
                <tr>
                    <td scope="row">{{ $purchase->id }}</td>
                    <td scope="row">{{ \Carbon\Carbon::parse($purchase->date)->format('d-m-Y H:i') }}</td>
                    <td scope="row">{{ $purchase->supplier->name }}</td>
                    <td scope="row">{{ $purchase->warehouse->name }}</td>

                    <td scope="row">
                        @switch($purchase->payment_status)
                            @case('Unpaid')
                                <span class="mb-1 btn btn-sm  btn-outline-warning">{{ $purchase->payment_status }}</span>
                            @break

                            @case('Partial')
                                <span class="mb-1 btn btn-sm btn-outline-info">{{ $purchase->payment_status }}</span>
                            @break

                            @case('Paid')
                                <span class="mb-1 btn btn-sm  btn-outline-primary">{{ $purchase->payment_status }}</span>
                            @break

                            @default
                                <span class="mb-1 btn btn-sm  btn-outline-default">{{ $purchase->payment_status }}</span>
                        @endswitch
                    </td>

                    <td scope="row">{{ config('settings.currency_symbol') }}
                        {{ number_format($purchase->grand_total, 2) }}</td>
                    <td scope="row">
                        <div class="input-group">
                            <button class="btn btn-sm btn-outline-success dropdown-toggle" type="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"
                                data-display="static">Action</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('purchases.show', $purchase->id) }}"><span
                                        class="mdi mdi-eye-outline"> Purchase Detail</span></a>
                                @if (auth()->user()->can('edit_purchases'))
                                    <a class="dropdown-item" href="{{ route('purchases.edit', $purchase->id) }}"><span
                                            class="mdi mdi-square-edit-outline">Edit Purchase</span></a>
                                @endif
                                @if ($purchase->has_return == 0)
                                    @if (auth()->user()->can('delete_purchases'))
                                    <a class="dropdown-item" href="#" x-data="{ purchaseID: {{ $purchase->id }} }"
                                        @click="confirmDeletion(purchaseID)"> <span class="mdi mdi-backspace-outline">
                                                Delete Purchase</span></a>
                                    @endif
                                @endif

                                <div role="separator" class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item" wire:click="showPayment({{ $purchase->id }})"><span
                                        class="mdi mdi-cash-multiple"> Show Payment</span></a>
                                @if ($purchase->payment_status !== 'Paid')
                                    <a href="#" class="dropdown-item" wire:click="createPayment({{ $purchase->id }})"><span
                                            class="mdi mdi-file-upload-outline"> Create Payment</span></a>
                                @endif

                                <div role="separator" class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{ url('purchases/download',$purchase->id) }}"><span class="mdi mdi-file-download-outline">
                                        Download Pdf</span></a>
                                @if ($purchase->has_return == 0)
                                    <div role="separator" class="dropdown-divider"></div>
                                    <a class="dropdown-item"
                                        href="{{ route('purchasesreturn.create', $purchase->id) }}"><span
                                            class="mdi mdi-file-replace-outline"> Purchases Return</span></a>
                                @endif
                            </div>

                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $purchases->links() }}


    @livewire('purchase.create-payment-modal')
    @livewire('purchase.show-payment-modal')
</div>
