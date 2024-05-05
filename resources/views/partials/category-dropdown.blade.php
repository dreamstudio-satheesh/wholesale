                <!-- Category Dropdown -->
                
                <div class="form-group col-md-4">
                    <label>Category</label>
                    <div class="input-group">
                        <select wire:model="category_id" class="form-control"  wire:key="category-select-{{ now() }}">
                            <option value=''>Choose Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <button type="button" data-toggle="modal" data-target="#createCategoryModal">
                                <span class="input-group-text">
                                    <i class="mdi mdi-plus"></i>
                            </button>
                            </span>
                        </div>

                    </div>

                    @if ($errors->has('category_id'))
                        <span class="text-danger">{{ $errors->first('category_id') }}</span>
                    @endif

                   
                </div>

                <!-- Create Category Modal -->
              
                <div class="modal fade" id="createCategoryModal" tabindex="-1"
                    aria-labelledby="createCategoryModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="createCategoryModalLabel">Add New Category</h5>
                                <button type="button" class="btn-close" data-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Category Creation Form -->
                                <div class="form-group">
                                    <label for="newCategoryName">Category Name</label>
                                    <input type="text" class="form-control" id="newCategoryName"
                                        wire:model="newCategoryName" wire:keydown.enter="createCategory">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" wire:click="createCategory">Add
                                    Category</button>
                            </div>
                        </div>
                    </div>
                </div>
