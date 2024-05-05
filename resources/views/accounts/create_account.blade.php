@extends('layouts.app')

@section('content')
    <div class="col-lg-12">
        <div class="card card-default">
            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                <h2>Create Account</h2>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card card-default">
                            <div class="card-body">
                                <form method="POST" action="{{ route('account.store') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label>Account Number</label>
                                        <input class="form-control" type="text" name="account_num" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Account Name</label>
                                        <input class="form-control" type="text" name="account_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Initial Balance</label>
                                        <input class="form-control" type="number" step="0.01" name="initial_balance" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Note</label>
                                        <textarea class="form-control" name="note"></textarea>
                                    </div>
                                    <div class="text-right"> <button class="btn btn-primary btn-default" type="submit">Create Account</button> </div>
                                    
                                </form>
                            </div>
                        </div>
                       

                    </div>
                </div>

                

            </div>
        </div>

    </div>
@endsection
