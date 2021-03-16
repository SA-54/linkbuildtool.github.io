@section('site_title', formatTitle([__('Settings'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('admin.dashboard'), 'title' => __('Admin')],
    ['title' => __('Settings')],
]])

<h2 class="mb-3 d-inline-block">{{ __('Settings') }}</h2>

<div class="card border-0 shadow-sm">
    <div class="card-header"><div class="font-weight-medium py-1">{{ __('Payment') }}</div></div>
    <div class="card-body">

        @include('shared.message')

        <form action="{{ route('admin.settings.payment') }}" method="post" enctype="multipart/form-data">

            @csrf

            <div class="form-group">
                <label for="i_stripe">{{ __('Enabled') }}</label>
                <select name="stripe" id="i_stripe" class="custom-select{{ $errors->has('stripe') ? ' is-invalid' : '' }}">
                    @foreach([1 => __('Yes'), 0 => __('No')] as $key => $value)
                        <option value="{{ $key }}" @if(config('settings.stripe') == $key) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
                @if ($errors->has('stripe'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('stripe') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i_stripe_key">{{ __('Stripe publishable key') }}</label>
                <input type="text" name="stripe_key" id="i_stripe_key" class="form-control{{ $errors->has('stripe_key') ? ' is-invalid' : '' }}" value="{{ config('settings.stripe_key') }}">
                @if ($errors->has('stripe_key'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('stripe_key') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i_stripe_secret">{{ __('Stripe secret key') }}</label>
                <input type="password" name="stripe_secret" id="i_stripe_secret" class="form-control{{ $errors->has('stripe_secret') ? ' is-invalid' : '' }}" value="{{ config('settings.stripe_secret') }}">
                @if ($errors->has('stripe_secret'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('stripe_secret') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i_stripe_wh_secret">{{ __('Stripe webhook secret key') }}</label>
                <input type="password" name="stripe_wh_secret" id="i_stripe_wh_secret" class="form-control{{ $errors->has('stripe_wh_secret') ? ' is-invalid' : '' }}" value="{{ config('settings.stripe_wh_secret') }}">
                @if ($errors->has('stripe_wh_secret'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('stripe_wh_secret') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i_stripe_wh_url">{{ __('Stripe webhook URL') }}</label>
                <input type="text" dir="ltr" name="stripe_wh_url" id="i_stripe_wh_url" class="form-control" value="{{ route('stripe.webhook') }}" disabled>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>

    </div>
</div>