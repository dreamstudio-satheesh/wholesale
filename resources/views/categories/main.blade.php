<div>
    <div class="row">
        <div style="padding-left:30px;" class="col-md-8  col-xs-12">
            <div class="row" style="padding-top: 20px; padding-left:20px;">
                <div class="col-md-8">
                    <h2>Categories List</h2>
                </div>
                <div class="col-md-4 text-right">
                    <input wire:model.change="search" id="search-box" data-toggle="tooltip" title="ALT+F" type="text"
                        class="form-control" placeholder="Search Categories...">
                </div>
            </div>

            <div style="padding-top: 10px">

                <table class="table table-bordered mt-5">
                    @if ($categories && $categories->count() > 0)
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Parent</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->index + 1 }}
                                    </td>

                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->parent ? $category->parent->name : 'No Parent' }}</td>
                                    <td>
                                        <button wire:click="edit({{ $category->id }})"
                                            class="btn btn-primary btn-sm">Edit</button>
                                        <button x-data="{ unitId: {{ $category->id }} }" @click="confirmDeletion(unitId)"
                                            class="btn btn-danger btn-sm">Delete</button>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    @else
                        <h5>categories does not have any record</h5>
                    @endif
                </table>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>{{ $categories->links() }}</div>
                    <div class="text-right">Total : {{ $categories->total() }}</div>
                </div>
            </div>


        </div>

        <div class="col-md-4 col-xs-12">

            <div class="card">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h5 class="modal-title">{{ $category_id ? 'Edit Category' : 'Create Category' }}</h5>
                </div>
                <div class="card-body" style="padding-top: 10px">

                    <form wire:submit="store">
                        <div class="form-group">
                            <label for="name">Name*</label>
                            <input type="text" class="form-control" data-toggle="tooltip" title="ALT+I"
                                id="CategoryName" autofocus placeholder="Enter name" wire:model="name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="parent_id">Parent Category</label>
                            <select class="form-control" wire:model="parent_id">
                                <option value="">None</option>
                                @foreach ($parentCategories as $parentCategory)
                                    <option value="{{ $parentCategory->id }}">{{ $parentCategory->name }}</option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" wire:click="create" class="btn btn-secondary">Cancel</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
             function confirmDeletion(unitId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('delete', unitId);
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                }
            })
        }
        
            document.addEventListener('DOMContentLoaded', function() {
                window.addEventListener('show-toastr', event => {

                    toastr.options = {
                        closeButton: true,
                        positionClass: "toast-top-right",
                    };
                    toastr.success(event.detail[0].message);

                    $("#CategoryName").focus();

                });
            });



            hotkeys('alt+i', function(event, handler) {
                event.preventDefault();
                let categoryName = document.getElementById('CategoryName');
                if (document.activeElement !== categoryName) {
                    categoryName.focus();
                }
            });
        </script>
    @endpush
