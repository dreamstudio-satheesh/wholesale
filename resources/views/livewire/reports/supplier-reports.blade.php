<div>
    <div class="row" id="recent-orders">
        <div class="col-md-3">
            <form wire:submit.prevent="SearchQuery">
                <input wire:model="search" type="text" class="form-control" placeholder="Search suppliers...">
            </form>
           {{--  <input wire:model="search" type="text" class="form-control" placeholder="Search suppliers..."> --}}
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

                <th scope="col" wire:click="sortBy('name')">
                    Name
                    @if ($sortField === 'name')
                        <span
                            class="mdi {{ $sortDirection === 'asc' ? 'mdi-sort-ascending' : 'mdi-sort-descending' }}"></span>
                    @endif
                </th>

                <th scope="col" wire:click="sortBy('phone')">
                    Phone
                    @if ($sortField === 'phone')
                        <span
                            class="mdi {{ $sortDirection === 'asc' ? 'mdi-sort-ascending' : 'mdi-sort-descending' }}"></span>
                    @endif
                </th>

              

                <th scope="col">Total Purchases</th>
              
                <th>Total Amount</th>
                <th>Total Paid</th>
                <th scope="col">Total Purchase Due</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($suppliers as $supplier)
                <tr class="text-center">
                    <td scope="row"> #{{ $supplier->id }}</td>
                    <td scope="row"> {{ $supplier->name }}</td>
                    <td scope="row">{{ $supplier->phone }}</td>
                    <td scope="row">{{ $supplier->total_purchases }}</td>
                    <td scope="row">{{ config('settings.currency_symbol') }} {{ number_format($supplier->total_amount, 2) }}</td>
                    <td scope="row">{{ config('settings.currency_symbol') }} {{ number_format($supplier->total_paid, 2) }}</td>
                    <td scope="row">{{ config('settings.currency_symbol') }} {{ number_format($supplier->total_purchase_due, 2) }}</td>
                    
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $suppliers->links() }}

</div>
