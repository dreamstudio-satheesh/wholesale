<div>
    @if($showCreatePaymentModal)
        <div class="modal fade show" style="display: block; padding-right: 17px;" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create Payment</h5>
                        <button type="button" class="close" wire:click="$set('showCreatePaymentModal', false)">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form wire:submit.prevent="createPayment">
                        <div class="modal-body">
                            <!-- Form fields for payment creation -->
                            <input type="hidden" wire:model="saleId">
                            <div class="form-group">
                                <label for="paymentDate">Payment Date</label>
                                <input type="datetime-local" class="form-control" wire:model="paymentDate">
                            </div>
                            <div class="form-group">
                                <label for="paymentChoice">Payment Choice</label>
                                <select class="form-control" wire:model="paymentChoice">
                                    <option value="1">Select a payment method</option>
                                        @foreach ($paymentMethods as $method)
                                            <option value="{{ $method->id }}">{{ $method->title }}</option>
                                        @endforeach
                                </select>
                                @error('paymentChoice')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                            </div>
                            <div class="form-group">
                                <label for="paymentAmount">Payment Amount</label>
                                <input type="text" class="form-control" wire:model="paymentAmount">
                            </div>
                            <div class="form-group">
                                <label for="paymentNotes">Payment Notes</label>
                                <textarea class="form-control" wire:model="paymentNotes"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="$set('showCreatePaymentModal', false)">Close</button>
                            <button type="submit" class="btn btn-primary">Save Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
