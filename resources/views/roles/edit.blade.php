@use ("Illuminate\Support\Str")

@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-default">
                    <div class="card-header card-header-border-bottom">
                        <h2>Create Role & Permissions</h2>
                    </div>

                    <div class="card-body">

                        <form method="POST" action="{{ route('roles.update', $role) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="roleName">Role name *</label>
                                        <input type="text" class="form-control" id="roleName" name="role_name"
                                            value="{{ $role->name }}" placeholder="Enter role name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="roleDescription">Description</label>
                                        <textarea class="form-control" id="roleDescription" name="description" placeholder="Enter description">{{ $role->description }}</textarea>
                                    </div>
                                </div>

                            </div>

                            <div class="row mt-4">

                                <div class="col-md-8">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                                @foreach ($permissionsgroup as $group)
                                                    <tr>
                                                        <th>{{ $group->name }}</th>

                                                        <td>
                                                            <div class="pt-2">
                                                                @foreach ($group->permissions as $permission)
                                                                    @if (Str::startsWith($permission->name, ['view_all', 'view_own']))
                                                                        <span> {{ $permission->description }}</span>
                                                                        <label class="control control-radio">
                                                                            {{ $permission->name_label }}
                                                                            <input type="radio"
                                                                                value="{{ $permission->id }}"
                                                                                id="permission_{{ $permission->id }}"
                                                                                name="permissionsradio[{{ $group->id }}]"
                                                                                {{ $currentPermissionIds->contains($permission->id) ? 'checked' : '' }}>

                                                                            <div class="control-indicator"></div>
                                                                        </label>
                                                                    @else
                                                                        <span> {{ $permission->description }}</span>
                                                                        <label class="control control-checkbox"
                                                                            for="permission_{{ $permission->id }}">
                                                                            {{ $permission->name_label }}
                                                                            <input type="checkbox"
                                                                                value="{{ $permission->id }}"
                                                                                id="permission_{{ $permission->id }}"
                                                                                name="permissions[]"
                                                                                {{ $currentPermissionIds->contains($permission->id) ? 'checked' : '' }}>
                                                                            <div class="control-indicator"></div>
                                                                        </label>
                                                                    @endif
                                                                @endforeach
                                                            </div>

                                                        </td>

                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <th></th>
                                                    <td><button type="submit" class="btn btn-primary">Submit</button></td>
                                                </tr>
                                            </tbody>

                                        </table>

                                    </div>

                                </div>
                            </div>



                        </form>


                    </div>

                </div>
            </div>






        </div>
    </div>
@endsection
