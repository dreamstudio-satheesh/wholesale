<div>
    <div class="row">
        <div style="padding-left:30px;" class="col-md-8  col-xs-12">
            <div class="row" style="padding-top: 20px; padding-left:20px;">
                <div class="col-md-8">
                    <h2>Brands List</h2>
                </div>
                <div class="col-md-4 text-right">
                    <input wire:model.change="search" id="search-box" data-toggle="tooltip" title="ALT+F" type="text"
                        class="form-control" placeholder="Search Brands...">
                </div>
            </div>

            <div style="padding-top: 10px">

                <table class="table table-bordered mt-5">
                    @if ($brands && $brands->count() > 0)
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($brands as $brand)
                                <tr>
                                    <td>{{ ($brands->currentPage() - 1) * $brands->perPage() + $loop->index + 1 }}
                                    </td>

                                    <td>{{ $brand->name }}</td>
                                    <td>@if ($brand->getMedia('brands')->isEmpty())
                                        <img class="img-fluid rounded-circle" style="height:40px; width:40px; align-self: center; " src="{{ url('image/no-image.webp') }}" alt="No Image Available">
                                    @else
                                    <img class="img-fluid rounded-circle" style="height:40px; width:40px; align-self: center; "
                                    src="{{ $brand->getMedia('brands')[0]->getUrl('preview') }}"  alt="{{ $brand->name }}">
                                    @endif</td>
                                    <td>
                                        <button wire:click="edit({{ $brand->id }})"
                                            class="btn btn-primary btn-sm">Edit</button>
                                        <button  x-data="{ unitId: {{ $brand->id }} }" @click="confirmDeletion(unitId)"
                                            class="btn btn-danger btn-sm">Delete</button>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    @else
                        <h5>brands does not have any record</h5>
                    @endif
                </table>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>{{ $brands->links() }}</div>
                    <div class="text-right">Total : {{ $brands->total() }}</div>
                </div>
            </div>


        </div>

        <div class="col-md-4 col-xs-12">

            <div class="card">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h5 class="modal-title">{{ $brand_id ? 'Edit Brand' : 'Create Brand' }}</h5>
                </div>
                <div class="card-body" style="padding-top: 10px">

                    <form wire:submit.prevent="saveBrand">
                        <div class="form-group">
                            <label for="name">Name*</label>
                            <input type="text" class="form-control" id="BrandName" autofocus placeholder="Enter name" wire:model="name">
                            @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
    
                        <div class="form-group">
                            <label>Image*</label>
                            <input type="file" wire:model="image" class="form-control">
                            @error('image')
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

                    $("#BrandName").focus();

                });
            });



            hotkeys('alt+i', function(event, handler) {
                event.preventDefault();
                let brandName = document.getElementById('BrandName');
                if (document.activeElement !== brandName) {
                    brandName.focus();
                }
            });
        </script>
    @endpush
