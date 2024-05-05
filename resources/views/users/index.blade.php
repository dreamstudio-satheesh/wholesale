@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h2>Users List</h2>
                    @if (auth()->user()->can('create_users'))
                        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#createUserModal">Add
                            User</a>
                    @endif
                </div>
                <div class="card-body">
                    <table class="table card-table table-responsive table-responsive-large" style="width:100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>User Name</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td> {{ $user->name }}</td>
                                    <td>
                                        <a class="text-dark" href="#"> {{ $user->username }} </a>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">Active</span>
                                    </td>
                                    <td>
                                        @if ($user->id != 1)
                                            @if (auth()->user()->can('edit_users'))
                                                <button href="#" class="btn btn-primary btn-sm edit-user"
                                                    onclick="loadUserData({{ $user->id }})">Edit</button>
                                            @endif

                                            @if (auth()->user()->can('delete_users'))
                                                <a href="#" class="btn btn-danger btn-sm"
                                                    onclick="event.preventDefault(); document.getElementById('delete-user-form-{{ $user->id }}').submit();">
                                                    Delete
                                                </a>
                                            @endif
                                        @else
                                            <span>Admin User Cannot Modify</span>
                                        @endif



                                        <form id="delete-user-form-{{ $user->id }}"
                                            action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>


                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>


            </div>
        </div>
    </div>



    <!-- Create User Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title" id="createUserModalLabel">Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>Roles</label>
                            <select name="role_id" class="form-control">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <!-- Add any additional fields here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" id="userId" name="edit_id">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name">
                        </div>
                        <div class="form-group">
                            <label>Roles</label>
                            <select name="role_id" class="form-control">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="edit_username" name="username">
                        </div>
                        <!-- Password Field -->
                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input type="password" class="form-control" id="edit_password" name="password"
                                placeholder="Enter new password">
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="form-group">
                            <label for="password_confirmation">Confirm New Password</label>
                            <input type="password" class="form-control" id="edit_password_confirmation"
                                name="password_confirmation" placeholder="Confirm new password">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection



@push('scripts')
    <script>
        // Function to call the edit method and populate the form
        function loadUserData(userId) {
            console.log('edit user');
            $.ajax({
                url: '/users/' + userId + '/edit',
                type: 'GET',
                success: function(data) {
                    $('#edit_id').val(data.id);
                    $('#edit_name').val(data.name);
                    $('#edit_username').val(data.username);

                    // Clear the password fields
                    $('#edit_password').val('');
                    $('#edit_password_confirmation').val('');

                    // Set the form's action attribute to the update route
                    $('#editUserForm').attr('action', '/users/' + data.id);

                    // Show the modal after populating it
                    $('#editUserModal').modal('show');
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    </script>
@endpush
