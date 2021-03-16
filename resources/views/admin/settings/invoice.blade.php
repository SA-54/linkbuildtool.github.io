@section('site_title', formatTitle([__('Settings'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('admin.dashboard'), 'title' => __('Admin')],
    ['title' => __('Settings')],
]])

<h2 class="mb-3 d-inline-block">{{ __('Settings') }}</h2>

<div class="card border-0 shadow-sm">
    <div class="card-header"><div class="font-weight-medium py-1">{{ __('Invoice') }}</div></div>
    <div class="card-body">

        @include('shared.message')

        <form action="{{ route('admin.settings.invoice') }}" method="post" enctype="multipart/form-data">

            @csrf

            <div class="form-group">
                <label for="i_invoice_vendor">{{ __('Vendor') }}</label>
                <input type="text" name="invoice_vendor" id="i_invoice_vendor" class="form-control" value="{{ config('settings.invoice_vendor') }}">
            </div>

            <div class="form-group">
                <label for="i_invoice_address">{{ __('Address') }}</label>
                <input type="text" name="invoice_address" id="i_invoice_address" class="form-control" value="{{ config('settings.invoice_address') }}">
            </div>

            <div class="form-row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="i_invoice_city">{{ __('City') }}</label>
                        <input type="text" name="invoice_city" id="i_invoice_city" class="form-control" value="{{ config('settings.invoice_city') }}">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="i_invoice_state">{{ __('State') }}</label>
                        <input type="text" name="invoice_state" id="i_invoice_state" class="form-control" value="{{ config('settings.invoice_state') }}">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="i_invoice_postal_code">{{ __('Postal code') }}</label>
                        <input type="text" name="invoice_postal_code" id="i_invoice_postal_code" class="form-control" value="{{ config('settings.invoice_postal_code') }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="i_invoice_country">{{ __('Country') }}</label>
                <select name="invoice_country" id="i_invoice_country" class="custom-select">
                    <option value="" hidden disabled selected>{{ __('Country') }}</option>
                    @foreach(config('countries') as $key => $value)
                        <option value="{{ $key }}" @if ($key == config('settings.invoice_country')) selected @endif>{{ __($value) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="i_invoice_phone">{{ __('Phone') }}</label>
                <input type="tel" name="invoice_phone" id="i_invoice_phone" class="form-control" value="{{ config('settings.invoice_phone') }}">
            </div>

            <div class="form-group">
                <label for="i_invoice_vat_number">{{ __('VAT number') }}</label>
                <input type="text" name="invoice_vat_number" id="i_invoice_vat_number" class="form-control" value="{{ config('settings.invoice_vat_number') }}">
            </div>

            <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>

    </div>
</div>