            <div class="modal fade" id="createCustomerModal" tabindex="-1" aria-labelledby="createCustomerModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createCustomerModalLabel">Add New Customer</h5>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <!-- Customer Creation Form -->
                        <form id="customerForm">
                        <div class="modal-body">
                            
                            <div class="form-group">
                                <label for="newCustomerName">Customer Name</label>
                                <input type="text" class="form-control" id="newCustomerName">
                            </div>
                            <div class="form-group">
                                <label for="newCustomerPhone">Customer Phone</label>
                                <input type="text" class="form-control" id="newCustomerPhone">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="createCustomerBtn">Add Customer</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>



            <!-- Payment Modal -->
            <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="paymentModalLabel">Create Payment</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="paymentForm">
                                <!-- Hidden input field for sales_id -->
                                <input type="hidden" id="salesId" name="salesId" value="">
                                <div class="row">
                                    <!-- Left Column -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="paymentDate">Date*</label>
                                            <input type="datetime-local" class="form-control" id="paymentDate"
                                                name="paymentDate" value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="paymentChoice">Payment Choice*</label>
                                            <select id="paymentChoice" name="paymentChoice" class="form-control">
                                                @foreach ($paymentMethods as $method)
                                                    <option value="{{ $method->id }}">{{ $method->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="paymentNotes">Payment Reference ID</label>
                                            <textarea class="form-control" id="paymentNotes" name="paymentNotes"></textarea>
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="payingAmount">Paying Amount*</label>
                                            <input type="text" class="form-control" id="payingAmount"
                                                name="payingAmount">
                                        </div>
                                        @if ($moduleStatuses['accounting'])
                                        <div class="form-group">
                                            <label for="account">Account</label>
                                            <select class="form-control" id="account_id" name="account_id" >
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endif
                                        <div class="form-group">
                                            <label for="salesNotes">Sales Notes</label>
                                            <textarea class="form-control" id="salesNotes" name="salesNotes"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Payment</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
