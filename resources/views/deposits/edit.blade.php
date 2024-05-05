@extends('layouts.app')

@section('content')
    <div class="col-lg-12">
        <div class="card card-default">
            <div class="card-header card-header-border-bottom">
                <h2>Edit Deposit</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('deposit.update', $deposit->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') <!-- Method Spoofing to support PUT request -->
                    <div class="row">
                        <div class="col-md-6"> <!-- Column 1 -->
                            <div class="form-group">
                                <label for="account_id">Account *</label>
                                <select class="form-control" id="account_id" name="account_id" required>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}" {{ $deposit->account_id == $account->id ? 'selected' : '' }}>
                                            {{ $account->account_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="date">Date *</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ $deposit->date->format('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="deposit_category_id">Category *</label>
                                <select class="form-control" id="deposit_category_id" name="deposit_category_id" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $deposit->deposit_category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="amount">Amount *</label>
                                <input type="number" class="form-control" id="amount" name="amount" value="{{ $deposit->amount }}" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6"> <!-- Column 2 -->
                            <div class="form-group">
                                <label for="payment_method_id">Payment Method *</label>
                                <select class="form-control" id="payment_method_id" name="payment_method_id" required>
                                    @foreach ($paymentMethods as $payment)
                                        <option value="{{ $payment->id }}" {{ $deposit->payment_method_id == $payment->id ? 'selected' : '' }}>
                                            {{ $payment->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="deposit_ref">Deposit Reference *</label>
                                <input type="text" class="form-control" id="deposit_ref" name="deposit_ref" value="{{ $deposit->deposit_ref }}" required>
                            </div>
                            <div class="form-group">
                                <label for="attachment">Attachment</label>
                                <input type="file" class="form-control-file" id="attachment" name="attachment">
                                @if($deposit->attachment)
                                    <!-- Optionally show existing attachment here -->
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="description">Please provide any details</label>
                                <textarea class="form-control" id="description" name="description" rows="2">{{ $deposit->description }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="text-right mt-2 pr-2">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
