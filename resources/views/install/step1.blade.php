@extends('install.layout')

@section('title', 'Setup')

@section('content')
    <div class="col-md-8">
        <h2 class="mb-4 text-light text-center">ElitePOS - Setup</h2>
        <div class="progress mb-4">
            <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0"
                aria-valuemax="100">Step 1 of 4</div>
        </div>
        <div class="card">
            <div class="card-header">
                Setup
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
                <form action="{{ url('install/setup') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="dbHost">ElitePOS - Setup</label>
                        <input type="text" class="form-control" name="app_name" value="Elite POS" required>
                    </div>
                    <div class="form-group">
                        <label for="">Select Environment </label>
                        <select class="form-control" id="app_env" name="app_env">
                            <option value="local">Local</option>
                            <option value="testing">Testing</option>
                            <option value="production">Production</option>

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">App Debug Mode </label>
                        <select class="form-control" id="app_debug" name="app_debug">
                            <option value="true">True</option>
                            <option value="false">False</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">App Key </label>
                        <input type="text" class="form-control"id="app_key" name="app_key" value="{{ $data['APP_KEY'] }}"
                            readonly>

                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-outline-warning mt-3" id="generate_key"
                            title="Generate Key">Generate New Key</button>

                    </div>

                    <div class="text-right"><button type="submit" class="btn btn-success">Next Step</button></div>
                    <br>

                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#generate_key').click(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'GET',
                    url: '/install/getAppKey',
                    success: function(data) {
                        $('#app_key').val(data);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error: " + status + " " + error);
                    }
                });
            });
        });
    </script>
@endpush
