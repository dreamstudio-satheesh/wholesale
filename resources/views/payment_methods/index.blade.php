@extends('layouts.app')

@section('content')
<div class="col-lg-12">
    <div class="card card-default">
        <div class="card-header card-header-border-bottom d-flex justify-content-between">
            <h2>Payment Methods List</h2>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createPaymentMethodModal">
                Create
            </button>
        </div>

        <div class="card-body">
            <div>
                <div class="row">
                    <div class="col-md-9"></div> <!-- Empty column for spacing -->
                    <div class="col-md-3 text-right">
                        <input type="text" class="form-control" placeholder="Search payment methods...">
                    </div>
                </div>

                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paymentMethods as $method)
                            <tr>
                                <td>{{ $method->id }}</td>
                                <td>{{ $method->title }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <button type="button" class="btn btn-primary btn-sm mr-2"
                                            onclick="openEditModal({{ $method->id }}, '{{ $method->title }}')">Edit</button>

                                        <form method="POST"
                                            action="{{ route('payment_method.destroy', $method->id) }}"
                                            id="delete-form-{{ $method->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="confirmDelete({{ $method->id }})">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('payment_methods.modal') <!-- Include the create and edit modal here -->
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.openEditModal = function(id, name) {
            // Populate the form fields
            document.getElementById('edit_name').value = name;
            // Set the form action dynamically
            document.getElementById('editPaymentMethodForm').action = '/path/to/update/payment_method/' + id;
            // Show the modal
            var editModal = new bootstrap.Modal(document.getElementById('editPaymentMethodModal'));
            editModal.show();
        }

        window.confirmDelete = function(id) {
            // Implementation of SweetAlert for delete confirmation
        }
    });
</script>
@endpush
