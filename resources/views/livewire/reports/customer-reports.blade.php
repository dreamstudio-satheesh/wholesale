<div>
    <div class="row" id="recent-orders">
        <div class="col-md-3">
            <form wire:submit.prevent="SearchQuery">
                <input wire:model="search" type="text" class="form-control" placeholder="Search customers...">
            </form>
           {{--  <input wire:model="search" type="text" class="form-control" placeholder="Search customers..."> --}}
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

              

                <th scope="col">Total Sales</th>
              
                <th>Total Amount</th>
                <th>Total Paid</th>
                <th scope="col">Total Sale Due</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
                <tr class="text-center">
                    <td scope="row"> #{{ $customer->id }}</td>
                    <td scope="row"> {{ $customer->name }}</td>
                    <td scope="row">{{ $customer->phone }}</td>
                    <td scope="row">{{ $customer->total_sales }}</td>
                    <td scope="row">{{ config('settings.currency_symbol') }} {{ number_format($customer->total_amount, 2) }}</td>
                    <td scope="row">{{ config('settings.currency_symbol') }} {{ number_format($customer->total_paid, 2) }}</td>
                    <td scope="row">{{ config('settings.currency_symbol') }} {{ number_format($customer->total_sale_due, 2) }}</td>
                    
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $customers->links() }}

</div>
