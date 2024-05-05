<div>
    <div class="row">
        <div style="padding-left:30px;" class="col-md-8  col-xs-12">
            <div class="row" style="padding-top: 20px; padding-left:20px;">
                <div class="col-md-8">
                    <h2>Warehouses List</h2>
                </div>
                <div class="col-md-4 text-right">
                    <input wire:model.change="search" id="search-box" data-toggle="tooltip" title="ALT+F" type="text"
                        class="form-control" placeholder="Search Warehouses...">
                </div>
            </div>

            <div style="padding-top: 10px">

                <table class="table table-bordered mt-5">
                    @if ($warehouses && $warehouses->count() > 0)
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($warehouses as $warehouse)
                                <tr>
                                    <td>{{ ($warehouses->currentPage() - 1) * $warehouses->perPage() + $loop->index + 1 }}
                                    </td>

                                    <td>{{ $warehouse->name }}</td>
                                    <td>
                                        <button wire:click="edit({{ $warehouse->id }})"
                                            class="btn btn-primary btn-sm">Edit</button>
                                        @if ($warehouse->id != 1)
                                            <button wire:click="delete({{ $warehouse->id }})"
                                                class="btn btn-danger btn-sm">Delete</button>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    @else
                        <h5>warehouses does not have any record</h5>
                    @endif
                </table>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>{{ $warehouses->links() }}</div>
                    <div class="text-right">Total : {{ $warehouses->total() }}</div>
                </div>
            </div>


        </div>

        <div class="col-md-4 col-xs-12">

            <div class="card">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h5 class="modal-title">{{ $warehouse_id ? 'Edit Warehouse' : 'Create Warehouse' }}</h5>
                </div>
                <div class="card-body" style="padding-top: 10px">

                    <form wire:submit="store">
                        <div class="form-group">
                            <label for="name">Name*</label>
                            <input type="text" class="form-control" id="WarehouseName" autofocus
                                placeholder="Enter name" wire:model="name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                        </div>
                        <div class="form-group">
                            <label for="name">Address</label>
                            <input type="text" class="form-control" id="address" autofocus
                                placeholder="Enter address" wire:model="address">
                            @error('address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Pincode</label>
                            <input type="text" class="form-control" id="pincode" autofocus
                                placeholder="Enter pincode" wire:model="pincode">
                            @error('pincode')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Phone</label>
                            <input type="text" class="form-control" id="phone" autofocus
                                placeholder="Enter phone" wire:model="phone">
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">City</label>
                            <input type="text" class="form-control" id="city" autofocus placeholder="Enter city"
                                wire:model="city">
                            @error('city')
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
            document.addEventListener('DOMContentLoaded', function() {
                window.addEventListener('show-toastr', event => {

                    toastr.options = {
                        closeButton: true,
                        positionClass: "toast-top-right",
                    };
                    toastr.success(event.detail[0].message);

                    $("#WarehouseName").focus();

                });
            });



            hotkeys('alt+i', function(event, handler) {
                event.preventDefault();
                let warehouseName = document.getElementById('WarehouseName');
                if (document.activeElement !== warehouseName) {
                    warehouseName.focus();
                }
            });
        </script>
    @endpush
