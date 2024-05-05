             <!-- Brand Dropdown -->
             <div class="form-group col-md-4">
                 <label>Brand</label>
                 <div class="input-group">
                     <select wire:model="brand_id" class="form-control" wire:key="brand-select-{{ now() }}">
                         <option value=''>Choose Brand</option>
                         @foreach ($brands as $brand)
                             <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                         @endforeach
                     </select>
                     <div class="input-group-append">
                         <button type="button" data-toggle="modal" data-target="#createBrandModal">
                             <span class="input-group-text">
                                 <i class="mdi mdi-plus"></i>
                         </button>
                     </div>
                 </div>
                 @if ($errors->has('brand_id'))
                     <span class="text-danger">{{ $errors->first('brand_id') }}</span>
                 @endif
             </div>


             <!-- Create Brand Modal -->
             <div class="modal fade" id="createBrandModal" tabindex="-1" aria-labelledby="createBrandModalLabel"
                 aria-hidden="true">
                 <div class="modal-dialog">
                     <div class="modal-content">
                         <div class="modal-header">
                             <h5 class="modal-title" id="createBrandModalLabel">Add New Brand</h5>
                             <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                         </div>
                         <div class="modal-body">
                             <!-- Brand Creation Form -->
                             <div class="form-group">
                                 <label for="newBrandName">Brand Name</label>
                                 <input type="text" class="form-control" id="newBrandName" wire:model="newBrandName"
                                     wire:keydown.enter="createBrand">
                             </div>
                         </div>
                         <div class="modal-footer">
                             <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                             <button type="button" class="btn btn-primary" wire:click="createBrand">Add
                                 Brand</button>
                         </div>
                     </div>
                 </div>
             </div>
