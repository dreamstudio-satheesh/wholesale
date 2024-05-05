@extends('layouts.app')

@section('content')
<div class="col-lg-12">
    <div class="card card-default">
        <div class="card-header card-header-border-bottom d-flex justify-content-between">
            <h2>Deposit List</h2>
            @if (auth()->user()->can('create_deposit'))
            <a href="{{ route('deposit.create') }}" class="btn btn-primary">Create</a>
            @endif
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-9"></div> <!-- Empty column for spacing -->
                <div class="col-md-3 text-right">
                    <input type="text" class="form-control" placeholder="Search deposits...">
                </div>
            </div>
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Account</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($deposits as $deposit)
                    <tr>
                        <td>{{ $deposit->id }}</td>
                        <td>{{ $deposit->account->account_name }}</td> 
                        <td>{{ $deposit->category->title }}</td>
                        <td>{{ $deposit->amount }}</td>
                        <td>{{ $deposit->date->format('d-m-Y') }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if (auth()->user()->can('edit_deposit'))
                                <a href="{{ route('deposit.edit', $deposit->id) }}"
                                    class="btn btn-primary btn-sm mr-2">Edit</a>
                                @endif    
                                @if (auth()->user()->can('delete_deposit'))
                                <form method="POST" action="{{ route('deposit.destroy', $deposit->id) }}"
                                    id="delete-form-{{ $deposit->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $deposit->id }})">Delete</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- SweetAlert2 CSS -->
<link href="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
@endpush

@push('scripts')
@if (session('success'))
<script>
    toastr.options = {
        closeButton: true,
        positionClass: "toast-top-right",
        timeOut: "5000", // You can adjust the time out as needed
    };
    toastr.success("{{ session('success') }}");
</script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
