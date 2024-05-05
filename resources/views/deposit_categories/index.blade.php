@extends('layouts.app')

@section('content')
    <div class="col-lg-12">
        <div class="card card-default">
            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                <h2>Deposit Categories List</h2>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createDepositCategoryModal">
                    Create
                </button>
            </div>

            <div class="card-body">
                <div>
                    <div class="row">
                        <div class="col-md-9"></div> <!-- Empty column for spacing -->
                        <div class="col-md-3 text-right">
                            <input type="text" class="form-control" placeholder="Search categories...">
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
                            @foreach ($depositCategories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td>{{ $category->title }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <button type="button" class="btn btn-primary btn-sm mr-2"
                                                onclick="openEditModal({{ $category->id }}, '{{ $category->title }}')">Edit</button>

                                            <form method="POST"
                                                action="{{ route('deposit_category.destroy', $category->id) }}"
                                                id="delete-form-{{ $category->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete({{ $category->id }})">Delete</button>
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

    @include('deposit_categories.modal') <!-- Include the create and edit  modal here -->
@endsection



@push('scripts')
 
    @if (session('success'))
        <script>
            toastr.options = {
                closeButton: true,
                positionClass: "toast-top-right",
                timeOut: "2000", // You can adjust the time out as needed
            };
            toastr.success("{{ session('success') }}");
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {


            window.openEditModal = function(id, title) {
                // Populate the form fields
                document.getElementById('edit_title').value = title;

                // Set the form action dynamically
                document.getElementById('editDepositCategoryForm').action = '/accounting/deposit_category/' +
                id;

                // Show the modal
                var editModal = new bootstrap.Modal(document.getElementById('editDepositCategoryModal'));
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
