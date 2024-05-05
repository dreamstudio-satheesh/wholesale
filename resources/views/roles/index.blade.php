@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h2>Roles & Permissions </h2>
                    <a href="{{ route('roles.create') }}" class="btn btn-primary">Create</a>
                </div>
                <div class="card-body">
                    <table class="table card-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>
                                        <a class="text-dark" href="#"> {{ $role->name }} </a>
                                    </td>
                                    <td> {{ $role->description }}</td>

                                  
                                    <td>
                                        @if ($role->name == 'admin')

                                         Admin 	Cannot change default permissions
                                        @else
                                            <a href="{{ route('roles.edit', $role->id) }}"
                                                class="btn btn-primary btn-sm edit-permision">Edit</a>
                                            <a href="#" class="btn btn-danger btn-sm"
                                                onclick="event.preventDefault(); document.getElementById('delete-permision-form-{{ $role->id }}').submit();">
                                                Delete
                                            </a>
                                        @endif



                                        <form id="delete-permision-form-{{ $role->id }}"
                                            action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-none">
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
@endsection
