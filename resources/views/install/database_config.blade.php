{{-- resources/views/install/database_config.blade.php --}}

@extends('install.layout')

@section('title', 'Database Configuration')

@section('content')
    <div class="col-md-8">
        <h2 class="mb-4 text-light text-center">ElitePOS - Database Configuration</h2>
        <div class="progress mb-4">
            <div class="progress-bar" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0"
                aria-valuemax="100">Step 2 of 4</div>
        </div>
        <div class="card">
            <div class="card-header">
                Database Configuration
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ url('install/setup_database') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="dbHost">Database Host</label>
                        <input type="text" class="form-control" id="dbHost" name="db_host" value="127.0.0.1" required>
                    </div>
                    <div class="form-group">
                        <label for="dbName">Database Name</label>
                        <input type="text" class="form-control" id="db_name" name="db_database" required>
                    </div>
                    <div class="form-group">
                        <label for="dbUser">Database User</label>
                        <input type="text" class="form-control" id="db_user" name="db_username" required>
                    </div>
                    <div class="form-group">
                        <label for="dbPassword">Database Password</label>
                        <input type="password" class="form-control" id="dbPassword" name="db_password">
                    </div>
                    <div class="text-right"><button type="submit" class="btn btn-success">Next Step</button></div>
                </form>
            </div>
        </div>
    </div>
@endsection
