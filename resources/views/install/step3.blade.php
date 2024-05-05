@extends('install.layout')

@section('title', 'Installation Complete')

@section('content')
    <div class="col-md-8">
        <h2 class="mb-4 text-light text-center">ElitePOS - Final Configuration</h2>
        <div class="progress mb-4">
            <div class="progress-bar" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0"
                aria-valuemax="100">Step 3 of 4</div>
        </div>
        <div class="card">
            <div class="card-header">Create Admin Credentials</div>
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
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <form action="{{ url('install/finalsetup') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="">Username</label>
                                <input type="text" class="form-control" name="username" placeholder="Enter Username"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                            <br><br>

                            <div class="text-right"><button type="submit" id="submitBtn" class="btn btn-primary">Complete
                                    Installation</button></div>
                            <br>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
            document.getElementById('submitBtn').addEventListener('click', function(event) {
                var password = document.getElementById('password').value;
                var confirmPassword = document.getElementById('password_confirmation').value;
                var passwordStrength = checkPasswordStrength(password);

                // Prevent form submission if passwords do not match or if password strength criteria are not met
                if (password !== confirmPassword) {
                    alert('Passwords do not match.');
                    event.preventDefault();
                } else if (!passwordStrength) {
                    alert('Password does not meet the strength criteria.');
                    event.preventDefault();
                }
            });

        function checkPasswordStrength(password) {
            // Example of a simple password strength check (length)
            // You can add more criteria like inclusion of numbers, uppercase, lowercase, special characters, etc.
            return password.length >= 8;
        }

    </script>
@endpush
