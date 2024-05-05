             <!-- Supplier Dropdown -->
             <div class="form-group col-md-4">
                 <label>Supplier</label>
                 <div class="input-group">
                     <select wire:model="supplier_id" class="form-control" wire:key="category-select-{{ now() }}">
                         <option value=''>Choose Supplier</option>
                         @foreach ($suppliers as $supplier)
                             <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                         @endforeach
                     </select>
                     <div class="input-group-append">
                         <button type="button" data-toggle="modal" data-target="#createSupplierModal">
                             <span class="input-group-text">
                                 <i class="mdi mdi-plus"></i>
                         </button>
                     </div>
                 </div>
                 @if ($errors->has('supplier_id'))
                     <span class="text-danger">{{ $errors->first('supplier_id') }}</span>
                 @endif
             </div>


             <!-- Create Supplier Modal -->
             <div class="modal fade" id="createSupplierModal" tabindex="-1" aria-labelledby="createSupplierModalLabel"
                 aria-hidden="true">
                 <div class="modal-dialog">
                     <div class="modal-content">
                         <div class="modal-header">
                             <h5 class="modal-title" id="createSupplierModalLabel">Add New Supplier</h5>
                             <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                         </div>
                         <div class="modal-body">
                             <!-- Supplier Creation Form -->
                             <div class="form-group">
                                 <label for="newSupplierName">Supplier Name</label>
                                 <input type="text" class="form-control" id="newSupplierName" wire:model="newSupplierName"
                                     wire:keydown.enter="createSupplier">
                             </div>
                         </div>
                         <div class="modal-footer">
                             <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                             <button type="button" class="btn btn-primary" wire:click="createSupplier">Add
                                 Supplier</button>
                         </div>
                     </div>
                 </div>
             </div>
