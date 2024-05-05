<div>
    
    <!-- Modal -->
    @if ($showPaymentModal)
        <div class="modal fade show" style="display: block; padding-right: 17px;" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Payment Details</h5>
                        <button type="button" class="close" wire:click="$set('showPaymentModal', false)"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @if ($editingPayment)
                        <div class="modal-body">
                            <form wire:submit.prevent="savePayment">
                                <!-- Payment Date -->
                                <div class="form-group">
                                    <label for="paymentDate">Payment Date</label>
                                    <input type="datetime-local" class="form-control"
                                        wire:model.defer="editablePaymentData.paymentDate">
                                    @error('editablePaymentData.paymentDate')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Payment Choice -->
                                <div class="form-group">
                                    <label for="paymentChoice">Payment Method</label>
                                    <select class="form-control" wire:model.defer="editablePaymentData.paymentChoice">
                                        <option value="">Select a payment method</option>
                                        @foreach ($paymentMethods as $method)
                                            <option value="{{ $method->id }}">{{ $method->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('editablePaymentData.paymentChoice')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Payment Amount -->
                                <div class="form-group">
                                    <label for="paymentAmount">Amount</label>
                                    <input type="text" class="form-control"
                                        wire:model.defer="editablePaymentData.amount">
                                    @error('editablePaymentData.amount')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                
                                <div class="form-group">
                                    <label for="paymentNotes">Notes</label>
                                    <textarea class="form-control" wire:model.defer="editablePaymentData.paymentNotes"></textarea>
                                    @error('editablePaymentData.paymentNotes')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                    <button type="button" wire:click="stopEditingPayment"
                                        class="btn btn-secondary">Cancel</button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="modal-body">
                            <!-- Payment details go here -->
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Payment ID</th>
                                        <th>Sale ID</th>
                                        <th>Amount</th>
                                        <th>Payment Method</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->id }}</td>
                                            <td>{{ $payment->sale_id }}</td>
                                            <td>{{ $payment->amount }}</td>
                                            <td>{{ $payment->paymentMethod->title ?? 'N/A' }}</td>
                                            <td>
                                                <button wire:click="startEditingPayment({{ $payment->id }})"
                                                    class="btn btn-sm btn-primary">Edit</button>
                                            </td>
                                            <td>
                                                <button wire:click="deletePayment({{ $payment->id }})"
                                                    class="btn btn-sm btn-danger">Delete</button>
                                                <!-- Add an edit button/link here -->
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary"
                            wire:click="$set('showPaymentModal', false)">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal backdrop -->
        <div class="modal-backdrop fade show"></div>
    @endif

</div>

