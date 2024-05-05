        {{-- Select Customer --}}
        <div class="form-group">
            <label>Customer*</label>
            <div class="input-group">
                <select id="customerDropdown" class="form-control">
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
                <div class="input-group-append">
                    <button type="button" data-toggle="modal" data-target="#createCustomerModal">
                        <span class="input-group-text"><i class="mdi mdi-plus"></i></span>
                    </button>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Date*</label>
            <input type="datetime-local" id="saleDate" value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" class="form-control">
        </div>

        <div id="cartContainer">
            <!-- Cart items will be displayed here -->
        </div>

        {{-- Cart items total amount and checkout button --}}
        <div id="discountSection" class="cart-summary">
            <label for="discountInput">Discount</label>
            <div  class="discount-input input-group">
                <input type="text" inputmode="decimal" pattern="[0-9]+(\.[0-9]{1,2})?"  class="form-control" id="discountInput" placeholder="Enter discount">
                <div class="input-group-append ">
                <select id="discount_type" class="form-control">
                    <option value="fixed">{{config('settings.currency_symbol')}} </option>
                    <option value="percent">%</option>
                </select>
                </div>
            </div>

            <div class="tax-input input-group">
                <input type="text" inputmode="decimal" pattern="[0-9]+(\.[0-9]{1,2})?" class="form-control" id="taxInput" placeholder="Enter tax rate">
                <div class="input-group-append">
                    <span class="input-group-text">%</span>
                </div>
            </div>
            

            <div id="totalAmountContainer" class="mt-3">
                <!-- Total amount will be displayed here -->
            </div>

            <div class="col-md-12 pr-2 pt-4 text-right">
            <button type="button" id="createSaleButton" class="btn btn-primary">Create Sale</button>
            </div>


        </div>
