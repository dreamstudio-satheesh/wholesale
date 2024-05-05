             <!-- Customer Dropdown -->
             <div class="form-group col-md-4">
                 <label>Customer</label>
                 <div class="input-group">
                     <select wire:model="customer_id" class="form-control" wire:key="category-select-{{ now() }}">
                         @foreach ($customers as $customer)
                             <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                         @endforeach
                     </select>
                     <div class="input-group-append">
                         <button type="button" data-toggle="modal" data-target="#createCustomerModal">
                             <span class="input-group-text">
                                 <i class="mdi mdi-plus"></i>
                         </button>
                     </div>
                 </div>
                 @if ($errors->has('customer_id'))
                     <span class="text-danger">{{ $errors->first('customer_id') }}</span>
                 @endif
             </div>


             <!-- Create Customer Modal -->
             <div class="modal fade" id="createCustomerModal" tabindex="-1" aria-labelledby="createCustomerModalLabel"
                 aria-hidden="true">
                 <div class="modal-dialog">
                     <div class="modal-content">
                         <div class="modal-header">
                             <h5 class="modal-title" id="createCustomerModalLabel">Add New Customer</h5>
                             <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                         </div>
                         <div class="modal-body">
                             <!-- Customer Creation Form -->
                             <div class="form-group">
                                 <label for="newCustomerName">Customer Name</label>
                                 <input type="text" class="form-control" id="newCustomerName" wire:model="newCustomerName"
                                     wire:keydown.enter="createCustomer">
                             </div>
                         </div>
                         <div class="modal-footer">
                             <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                             <button type="button" class="btn btn-primary" wire:click="createCustomer">Add
                                 Customer</button>
                         </div>
                     </div>
                 </div>
             </div>
