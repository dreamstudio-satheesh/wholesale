<div>

    <div class="row">
        <div style="padding-left:30px;" class="col-md-8  col-xs-12">
            <div style="padding-top: 20px; padding-left:20px;" class="row">
                <div class="col-md-8">
                    <h2>Units List</h2>
                </div>
                <div class="col-md-4 text-right">
                    <input wire:model.change="search" type="text" id="search-box" data-toggle="tooltip" title="ALT+F"
                        class="form-control" placeholder="Search Units...">
                </div>
            </div>

            <div style="padding-top: 10px">


                <table class="table table-bordered mt-5">
                    @if ($units && $units->count() > 0)
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($units as $unit)
                                <tr>
                                    <td>{{ ($units->currentPage() - 1) * $units->perPage() + $loop->index + 1 }}
                                    </td>

                                    <td>{{ $unit->name }}</td>

                                    <td>
                                        <button wire:click="edit({{ $unit->id }})"
                                            class="btn btn-primary btn-sm">Edit</button>
                                        <button x-data="{ unitId: {{ $unit->id }} }" @click="confirmDeletion(unitId)"
                                            class="btn btn-danger btn-sm">Delete</button>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    @else
                        <h5>Units does not have any record</h5>
                    @endif
                </table>

                {{ $units->links() }}

            </div>
        </div>
        <div class="col-md-4 col-xs-12">
            <div class="card">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h5 class="modal-title">{{ $unit_id ? 'Edit Unit' : 'Create Unit' }}</h5>
                </div>
                <div class="card-body" style="padding-top: 10px">
                    <form wire:submit="store">
                        <div class="form-group">
                            <label for="name">Name*</label>
                            <input type="text" class="form-control" data-toggle="tooltip" title="ALT+I"
                                id="UnitName" autofocus placeholder="Enter name" wire:model="name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="name">Short Name*</label>
                            <input type="text" class="form-control" id="ShotrtName" autofocus
                                placeholder="Enter Short name" wire:model="shortName">
                            @error('shortName')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="parent_id">Base Unit</label>
                            <select class="form-control" wire:model.live="baseUnit">
                                <option value="">Nill</option>
                                @foreach ($baseUnits as $base_unit)
                                    <option value="{{ $base_unit->id }}">{{ $base_unit->name }}</option>
                                @endforeach
                            </select>

                            @error('baseUnit')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @if ($baseUnit)
                            <div class="form-group">
                                <label for="operator">Operator</label>
                                <select class="form-control" wire:model="operator">
                                    <!-- Add your operator options here -->
                                    <option selected value="*">Multiply(*)</option>
                                    <option value="/">Divide(/)</option>
                                    <!-- Add other operators as needed -->
                                </select>
                                @error('operator')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="operatorValue">Operation Value</label>
                                <input type="text" class="form-control" id="operatorValue"
                                    placeholder="Enter Operation Value" wire:model="operatorValue">
                                @error('operatorValue')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

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

                $("#UnitName").focus();

            });
        });
    </script>
@endpush
