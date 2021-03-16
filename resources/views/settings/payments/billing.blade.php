@section('site_title', formatTitle([__('Billing information'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('dashboard'), 'title' => __('Home')],
    ['url' => route('settings'), 'title' => __('Settings')],
    ['title' => __('Billing information')],
]])

<h2 class="mb-3 d-inline-block">{{ __('Billing information') }}</h2>

<div class="card border-0 shadow-sm">
    <div class="card-header">
        <div class="font-weight-medium py-1">{{ __('Billing information') }}</div>
    </div>
    <div class="card-body">
        @include('shared.message')

        <form action="{{ route('settings.payments.billing') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="i_name">{{ __('Name') }}</label>
                <input type="text" name="name" id="i_name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') ?? ($customer->name ?? null) }}">
                @if ($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i_address">{{ __('Address') }}</label>
                <input type="text" name="address" id="i_address" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" value="{{ old('address') ?? ($customer->address->line1 ?? null) }}">
                @if ($errors->has('address'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('address') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="i_city">{{ __('City') }}</label>
                        <input type="text" name="city" id="i_city" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" value="{{ old('city') ?? ($customer->address->city ?? null) }}">
                        @if ($errors->has('city'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('city') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="i_state">{{ __('State') }}</label>
                        <input type="text" name="state" id="i_state" class="form-control" value="{{ old('state') ?? ($customer->address->state ?? null) }}">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="i_postal_code">{{ __('Postal code') }}</label>
                        <input type="text" name="postal_code" id="i_postal_code" class="form-control{{ $errors->has('postal_code') ? ' is-invalid' : '' }}" value="{{ old('postal_code') ?? ($customer->address->postal_code ?? null) }}">
                        @if ($errors->has('postal_code'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('postal_code') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="i_country">{{ __('Country') }}</label>
                <select name="country" id="i_country" class="custom-select{{ $errors->has('country') ? ' is-invalid' : '' }}">
                    <option value="" hidden disabled selected>{{ __('Country') }}</option>
                    @foreach(config('countries') as $key => $value)
                        <option value="{{ $key }}" @if ((old('country') !== null && $key == old('country')) || (isset($customer->address->country) && $key == $customer->address->country)) selected @endif>{{ __($value) }}</option>
                    @endforeach
                </select>
                @if ($errors->has('country'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('country') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i_phone">{{ __('Phone') }}</label>
                <input type="text" name="phone" id="i_phone" class="form-control" value="{{ old('phone') ?? ($customer->phone ?? null) }}">
            </div>

            <div class="row mt-3">
                <div class="col">
                    <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>