@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h2>Users Profile</h2>
                </div>
                <div class="card-body">
                    <form id="UserProfile" method="post" action="{{ route('profile.save') }}">
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>Name *</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter Name">

                            </div>

                            <div class="form-group col-lg-6">
                                <label>User Name *</label>
                                <input type="text" name="username" class="form-control" placeholder="Enter User Name">

                            </div>

                        </div>

                        <div class="row">

                            <div class="form-group col-lg-6">
                                <label>Password *</label>
                                <input type="password" name="password" id="password" placeholder="min : 6 characters"
                                    class="form-control">

                            </div>

                            <div class="form-group col-lg-6">
                                <label>Repeat Password *</label>
                                <input type="password" name="confirm_password" id="password_confirmation"
                                    placeholder="Repeat password" class="form-control">

                            </div>


                        </div>

                        <div class="row">

                            <div class="form-group col-lg-6">
                                <label>Avatar</label>
                                <input type="file" name="avatar" class="form-control-file">
                            </div>

                            <div class="form-group col-lg-6">
                                <br>
                                <button type="submit" class="btn btn-primary btn-default">Submit</button>
                            </div>


                        </div>
                    </form>

                </div>


            </div>
        </div>
    </div>
@endsection



@push('scripts')
    <script>
        $('#UserProfile').on('submit', function(e) {
            e.preventDefault();
            toastr.error("In demo mode user profile cannot be updated.");
        });
    </script>
@endpush
