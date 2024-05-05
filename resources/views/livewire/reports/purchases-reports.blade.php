<div>
    <div class="row" id="recent-orders">
        <div class="col-md-3">
            <form wire:submit.prevent="SearchQuery">
                <input wire:model="search" type="text" class="form-control" placeholder="Search purchases...">
            </form>
           {{--  <input wire:model="search" type="text" class="form-control" placeholder="Search purchases..."> --}}
        </div>
        <div class="col-md-6"></div>
        <div class="col-md-3">
            <div class=" text-right date-range-report ">
                <span> @if ($startDate)
                    {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{  \Carbon\Carbon::parse($endDate)->format('M d, Y')  }}
                @endif 
                </span>
              </div>
        </div>
       
        
    </div>

    <table class="table mt-3">
        <thead>
            <tr class="text-center">
                <th scope="col" wire:click="sortBy('id')">
                    ID
                    @if ($sortField === 'id')
                        <span
                            class="mdi {{ $sortDirection === 'asc' ? 'mdi-sort-ascending' : 'mdi-sort-descending' }}"></span>
                    @endif
                </th>

                <th scope="col" wire:click="sortBy('date')">
                    Date and Time
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
                <th scope="col" wire:click="sortBy('grand_total')">
                    Amount
                    @if ($sortField === 'grand_total')
                        <span
                            class="mdi {{ $sortDirection === 'asc' ? 'mdi-sort-ascending' : 'mdi-sort-descending' }}"></span>
                    @endif
                </th>
                <th>Due</th>
                <th>Created By</th>
                <th scope="col">Payment Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchases as $purchase)
                <tr class="text-center">
                    <td scope="row"> #{{ $purchase->id }}</td>
                    <td scope="row">{{ \Carbon\Carbon::parse($purchase->date)->format('d-m-Y H:i') }}</td>
                    <td scope="row">{{ $purchase->supplier->name }}</td>
                    <td scope="row">{{ $purchase->warehouse->name }}</td>
                    <td scope="row">{{ config('settings.currency_symbol') }} {{ number_format($purchase->grand_total, 2) }}</td>
                    <td scope="row">{{ config('settings.currency_symbol') }} {{ number_format($purchase->grand_total-$purchase->paid_amount, 2) }}</td>
                    <td scope="row">{{ $purchase->user->name }}</td>
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
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $purchases->links() }}

</div>
