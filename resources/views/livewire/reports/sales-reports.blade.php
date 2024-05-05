<div>
    <div class="row" id="recent-orders">
        <div class="col-md-3">
            <form wire:submit.prevent="SearchQuery">
                <input wire:model="search" type="text" class="form-control" placeholder="Search sales...">
            </form>
           {{--  <input wire:model="search" type="text" class="form-control" placeholder="Search sales..."> --}}
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
                <th scope="col" wire:click="sortBy('invoice_number')">
                    Invoice
                    @if ($sortField === 'invoice_number')
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

                <th scope="col" wire:click="sortBy('customer_id')">
                    Customer
                    @if ($sortField === 'customer_id')
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
            @foreach ($sales as $sale)
                <tr class="text-center">
                    <td scope="row"> #{{ $sale->invoice_number }}</td>
                    <td scope="row">{{ \Carbon\Carbon::parse($sale->date)->format('d-m-Y H:i') }}</td>
                    <td scope="row">{{ $sale->customer->name }}</td>
                    <td scope="row">{{ $sale->warehouse->name }}</td>
                    <td scope="row">{{ config('settings.currency_symbol') }} {{ number_format($sale->grand_total, 2) }}</td>
                    <td scope="row">{{ config('settings.currency_symbol') }} {{ number_format($sale->grand_total-$sale->paid_amount, 2) }}</td>
                    <td scope="row">{{ $sale->user->name }}</td>
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
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $sales->links() }}

</div>
