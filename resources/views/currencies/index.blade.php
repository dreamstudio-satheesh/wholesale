@extends('layouts.app')

@section('content')
<div class="col-lg-12">
    <div class="card card-default">
        <div class="card-header card-header-border-bottom d-flex justify-content-between">
            <h2>Currency List</h2>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createCurrencyModal">
                Create
            </button>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-9"></div> <!-- Empty column for spacing -->
                <div class="col-md-3 text-right">
                    <input type="text" class="form-control" placeholder="Search currencies...">
                </div>
            </div>

            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Symbol</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($currencies as $currency)
                    <tr>
                        <td>{{ $currency->id }}</td>
                        <td>{{ $currency->name }}</td>
                        <td>{{ $currency->code }}</td>
                        <td>{{ $currency->symbol }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn btn-primary btn-sm mr-2"
                                    onclick="openEditModal({{ $currency->id }}, '{{ $currency->name }}', '{{ $currency->code }}', '{{ $currency->symbol }}')">Edit</button>
                                <form method="POST"
                                    action="{{ route('currency.destroy', $currency->id) }}"
                                    id="delete-form-{{ $currency->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $currency->id }})">Delete</button>
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

@include('currencies.modal') <!-- Include the create and edit modal here -->
@endsection

@push('styles')
<link href="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if (session('success'))
<script>
    toastr.options = {
        closeButton: true,
        positionClass: "toast-top-right",
        timeOut: "2000",
    };
    toastr.success("{{ session('success') }}");
</script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {

        window.openEditModal = function(id, name, code, symbol) {
            // Populate the form fields
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_code').value = code;
            document.getElementById('edit_symbol').value = symbol;

            // Set the form action dynamically
            var formAction = '{{ url('settings/currency') }}' + '/' + id;
            document.getElementById('editCurrencyForm').action = formAction;

            // Show the modal
            var editModal = new bootstrap.Modal(document.getElementById('editCurrencyModal'));
            editModal.show();
        }

        window.confirmDelete = function(id) {
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
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    });
</script>
@endpush
