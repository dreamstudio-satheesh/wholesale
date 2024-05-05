            <div class="modal fade" id="createCustomerModal" tabindex="-1" aria-labelledby="createCustomerModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createCustomerModalLabel">Add New Customer</h5>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="customerForm">
                        <div class="modal-body">
                            <!-- Customer Creation Form -->
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


