<div class="modal fade {{ $isModalOpen ? 'show' : '' }}"  tabindex="-1" aria-hidden="true" style="{{ $isModalOpen ? 'display: block;' : 'display: none;' }}" aria-modal="{{ $isModalOpen ? 'true' : 'false' }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $warehouse_id ? 'Edit Warehouse' : 'Create Warehouse' }}</h5>
                <button type="button" class="close" wire:click="closeModal">×</button>
            </div>
            <div class="modal-body">
                <form wire:submit="store">
                    <div class="form-group">
                        <label for="name">Name*</label>
                        <input type="text" class="form-control" id="name" autofocus placeholder="Enter name" wire:model="name">
                        @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="name">Address</label>
                        <input type="text" class="form-control" id="address" autofocus placeholder="Enter address" wire:model="address">
                        @error('address') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="name">Pincode</label>
                        <input type="text" class="form-control" id="pincode" autofocus placeholder="Enter pincode" wire:model="pincode">
                        @error('pincode') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="name">Phone</label>
                        <input type="text" class="form-control" id="phone" autofocus placeholder="Enter phone" wire:model="phone">
                        @error('phone') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>  
                    <div class="form-group">
                        <label for="name">City</label>
                        <input type="text" class="form-control" id="city" autofocus placeholder="Enter city" wire:model="city">
                        @error('city') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <button type="button" wire:click.prevent="store()" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="{{ $isModalOpen ? 'modal-backdrop fade show' : '' }}"></div>
