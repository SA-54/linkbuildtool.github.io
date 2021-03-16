@section('site_title', formatTitle([__('Settings'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('admin.dashboard'), 'title' => __('Admin')],
    ['title' => __('Settings')],
]])

<h2 class="mb-3 d-inline-block">{{ __('Settings') }}</h2>

<div class="card border-0 shadow-sm">
    <div class="card-header"><div class="font-weight-medium py-1">{{ __('Captcha') }}</div></div>
    <div class="card-body">

        @include('shared.message')

        <form action="{{ route('admin.settings.captcha') }}" method="post" enctype="multipart/form-data">

            @csrf

            <div class="form-group">
                <label for="i_captcha_site_key">{{ __('reCAPTCHA site key') }}</label>
                <input id="i_captcha_site_key" type="text" class="form-control{{ $errors->has('captcha_site_key') ? ' is-invalid' : '' }}" name="captcha_site_key" value="{{ config('settings.captcha_site_key') }}">
                @if ($errors->has('captcha_site_key'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('captcha_site_key') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i_captcha_secret_key">{{ __('reCAPTCHA secret key') }}</label>
                <input id="i_captcha_secret_key" type="password" class="form-control{{ $errors->has('captcha_secret_key') ? ' is-invalid' : '' }}" name="captcha_secret_key" value="{{ config('settings.captcha_secret_key') }}">
                @if ($errors->has('captcha_secret_key'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('captcha_secret_key') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i_captcha_shorten">{{ Str::ucfirst(mb_strtolower(__(':name form', ['name' => __('Shorten')]))) }}</label>
                <select name="captcha_shorten" id="i_captcha_shorten" class="custom-select">
                    @foreach([0 => __('Disabled'), 1 => __('Enabled')] as $key => $value)
                        <option value="{{ $key }}" @if (config('settings.captcha_shorten') == $key) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label for="i_captcha_registration">{{ Str::ucfirst(mb_strtolower(__(':name form', ['name' => __('Registration')]))) }}</label>
                <select name="captcha_registration" id="i_captcha_registration" class="custom-select">
                    @foreach([0 => __('Disabled'), 1 => __('Enabled')] as $key => $value)
                        <option value="{{ $key }}" @if (config('settings.captcha_registration') == $key) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="i_captcha_contact">{{ Str::ucfirst(mb_strtolower(__(':name form', ['name' => __('Contact')]))) }}</label>
                <select name="captcha_contact" id="i_captcha_contact" class="custom-select">
                    @foreach([0 => __('Disabled'), 1 => __('Enabled')] as $key => $value)
                        <option value="{{ $key }}" @if (config('settings.captcha_contact') == $key) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>

    </div>
</div>