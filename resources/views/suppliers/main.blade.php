<div>

    <div class="row">


        <div style="padding-left:30px;" class="col-md-8  col-xs-12">


            <div style="padding-top: 20px; padding-left:20px;" class="row">
                <div class="col-md-8">
                    <h4>Suppliers List</h4>
                </div>
                <div class="col-md-4 text-right">
                    <input wire:model.change="search" id="search-box" data-toggle="tooltip" title="ALT+F" type="text" class="form-control"
                        placeholder="Search Suppliers...">
                </div>
            </div>
            <div style="padding-top: 10px">

                <table class="table table-bordered mt-3">
                    @if ($suppliers && $suppliers->count() > 0)
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th wire:click="sortBy('name')">
                                    Name
                                    @if ($sortField === 'name')
                                        <span
                                            class="mdi {{ $sortDirection === 'asc' ? 'mdi-sort-ascending' : 'mdi-sort-descending' }}"></span>
                                    @endif
                                </th>
                                <th wire:click="sortBy('phone')">
                                    Phone
                                    @if ($sortField === 'phone')
                                        <span
                                            class="mdi {{ $sortDirection === 'asc' ? 'mdi-sort-ascending' : 'mdi-sort-descending' }}"></span>
                                    @endif
                                </th>
                                <th wire:click="sortBy('email')">
                                    Email
                                    @if ($sortField === 'email')
                                        <span
                                            class="mdi {{ $sortDirection === 'asc' ? 'mdi-sort-ascending' : 'mdi-sort-descending' }}"></span>
                                    @endif
                                </th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suppliers as $supplier)
                                <tr>
                                    <td>{{ ($suppliers->currentPage() - 1) * $suppliers->perPage() + $loop->index + 1 }}
                                    </td>
                                    <td>{{ $supplier->name }}</td>
                                    <td>{{ $supplier->phone }}</td>
                                    <td>{{ $supplier->email }}</td>
                                    <td>
                                        <button wire:click="edit({{ $supplier->id }})"
                                            class="btn btn-primary btn-sm">Edit</button>
                                        <button x-data="{ unitId: {{ $supplier->id }} }" @click="confirmDeletion(unitId)"
                                            class="btn btn-danger btn-sm">Delete</button>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    @else
                        <h5>suppliers does not have any record</h5>
                    @endif
                </table>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>{{ $suppliers->links() }}</div>
                    <div class="text-right">Total : {{ $suppliers->total() }}</div>
                </div>
            </div>


        </div>
        <div class="col-md-4 col-xs-12">

            <div class="card">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h5>{{ $supplier_id ? 'Edit Supplier' : 'Create Supplier' }}</h5>
                </div>
                <div class="card-body" style="padding-top: 10px">
                    <form wire:submit.prevent="store">
                        <div class="form-group">
                            <label for="name">Name*</label>
                            <input type="text" class="form-control" data-toggle="tooltip" title="ALT+I" id="SupplierName" placeholder="Enter name"
                                wire:model="name" autofocus>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" placeholder="Enter phone number"
                                wire:model="phone">
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter email"
                                wire:model="email">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea class="form-control" id="address" rows="3" placeholder="Enter address" wire:model="address"></textarea>
                            @error('address')
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

                $( "#SupplierName" ).focus();

            });
        });
    </script>
@endpush
