@extends('layouts.app')

@section('content')
    <div class="col-lg-12">
        <div class="card card-default">
            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                <h2>Account List</h2>
                @if (auth()->user()->can('create_account'))
                <a href="{{ url('accounting/account/create') }}" class="btn btn-primary">Create</a>
                @endif
            </div>

            <div class="card-body">

                <div>
                    <div class="row">
                        <div class="col-md-9"></div> <!-- Empty column for spacing -->
                        <div class="col-md-3 text-right">
                            <input type="text" class="form-control" placeholder="Search products...">
                        </div>
                    </div>

                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Account Number</th>
                                <th>Account Name</th>
                                <th>Initial Balance</th>
                                <th>Actions</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($accounts as $account)
                                <tr>
                                    <td>{{ $account->id }}</td>
                                    <td>{{ $account->account_num }}</td>
                                    <td>{{ $account->account_name }}</td>
                                    <td>{{ $account->initial_balance }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if (auth()->user()->can('edit_account'))
                                            <a href="{{ route('account.edit', $account->id) }}"
                                                class="btn btn-primary btn-sm mr-2">Edit</a>
                                            @endif 
                                            @if (auth()->user()->can('delete_account'))   
                                            <form method="POST" action="{{ route('account.destroy', $account->id) }}"
                                                id="delete-form-{{ $account->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete({{ $account->id }})">Delete</button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                    <div>
                    </div>

                </div>
            </div>

        </div>


    </div>
@endsection


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
