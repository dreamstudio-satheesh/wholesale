@extends('layouts.app')

@section('content')
    <div class="col-lg-12">
        <div class="card card-default">
            <div class="card-header card-header-border-bottom">
                <h2>System Settings</h2>
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

                <form action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- Default Currency Dropdown -->
                        <div class="form-group col-md-4">
                            <label for="default_currency">Default Currency *</label>
                            <select class="form-control" id="default_currency" name="default_currency" required>
                                @foreach ($currencies as $currency)
                                    <option value="{{ $currency->code }}"
                                        {{ isset($settings['default_currency']) && $settings['default_currency'] == $currency->code ? 'selected' : '' }}>
                                        {{ $currency->name }} ({{ $currency->symbol }})
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('default_currency'))
                                <span class="text-danger">{{ $errors->first('default_currency') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="company_name">Company Name *</label>
                            <input type="text" class="form-control" id="company_name" name="company_name" required
                                value="{{ $settings['company_name'] ?? '' }}">
                            @if ($errors->has('company_name'))
                                <span class="text-danger">{{ $errors->first('company_name') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="developed_by">Developed By *</label>
                            <input type="text" class="form-control" id="developed_by" name="developed_by" required
                                value="{{ $settings['developed_by'] ?? '' }}">
                            @if ($errors->has('developed_by'))
                                <span class="text-danger">{{ $errors->first('developed_by') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="company_email">Company Email</label>
                            <input type="text" class="form-control" id="company_email" name="company_email" 
                                value="{{ $settings['company_email'] ?? '' }}">
                            @if ($errors->has('company_email'))
                                <span class="text-danger">{{ $errors->first('company_email') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="company_phone">Company Phone</label>
                            <input type="text" class="form-control" id="company_phone" name="company_phone" 
                                value="{{ $settings['company_phone'] ?? '' }}">
                            @if ($errors->has('company_phone'))
                                <span class="text-danger">{{ $errors->first('company_phone') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="company_vat">Company VAT</label>
                            <input type="text" class="form-control" id="company_vat" name="company_vat" 
                                value="{{ $settings['company_vat'] ?? '' }}">
                            @if ($errors->has('company_vat'))
                                <span class="text-danger">{{ $errors->first('company_vat') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="app_name">App Name *</label>
                            <input type="text" class="form-control" id="app_name" name="app_name" required
                                value="{{ $settings['app_name'] ?? '' }}">
                            @if ($errors->has('app_name'))
                                <span class="text-danger">{{ $errors->first('app_name') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="app_footer">Footer</label>
                            <input type="text" class="form-control" id="app_footer" name="app_footer" 
                                value="{{ $settings['app_footer'] ?? '' }}">
                            @if ($errors->has('app_footer'))
                                <span class="text-danger">{{ $errors->first('app_footer') }}</span>
                            @endif
                        </div>
                        <!-- Default Customer Dropdown -->
                        <div class="form-group col-md-4">
                            <label for="default_customer">Default Customer</label>
                            <select class="form-control" id="default_customer" name="default_customer" required>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('default_customer'))
                                <span class="text-danger">{{ $errors->first('default_customer') }}</span>
                            @endif
                        </div>

                        <!-- Default Warehouse Dropdown -->
                        <div class="form-group col-md-4">
                            <label for="default_warehouse">Default Warehouse</label>
                            <select class="form-control" id="default_warehouse" name="default_warehouse" required>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('default_warehouse'))
                                <span class="text-danger">{{ $errors->first('default_warehouse') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="company_address">Company Address *</label>
                            <textarea rows="1" class="form-control" name="company_address">{{ $settings['company_address'] ?? '' }}</textarea>
                            @if ($errors->has('company_address'))
                                <span class="text-danger">{{ $errors->first('company_address') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="time_zone">Time Zone </label>
                            <select class="form-control" id="time_zone" name="time_zone" required>
                                @foreach ($zonesArray as $zone)
                                    <option value="{{ $zone['zone'] }}"
                                        {{ $settings['time_zone'] == $zone['zone'] ? 'selected' : '' }}>
                                        {{ $zone['label'] }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('time_zone'))
                                <span class="text-danger">{{ $errors->first('time_zone') }}</span>
                            @endif
                        </div>

                    </div>
                    <div class="row">
                        <!-- Continue with the other fields in a similar three-column layout -->
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
