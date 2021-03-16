@section('site_title', formatTitle([__('Settings'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('admin.dashboard'), 'title' => __('Admin')],
    ['title' => __('Settings')],
]])

<h2 class="mb-3 d-inline-block">{{ __('Settings') }}</h2>

<div class="card border-0 shadow-sm">
    <div class="card-header"><div class="font-weight-medium py-1">{{ __('Email') }}</div></div>
    <div class="card-body">

        @include('shared.message')

        <form action="{{ route('admin.settings.email') }}" method="post" enctype="multipart/form-data">

            @csrf

            <div class="form-group">
                <label for="i_email_driver">{{ __('Driver') }}</label>
                <select name="email_driver" id="i_email_driver" class="custom-select">
                    @foreach(['smtp', 'log'] as $value)
                        <option value="{{ $value }}" @if (config('settings.email_driver') == $value) selected @endif>{{ ucfirst($value) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="i_email_host">{{ __('Host') }}</label>
                <input type="text" name="email_host" id="i_email_host" class="form-control" value="{{ config('settings.email_host') }}">
            </div>

            <div class="form-group">
                <label for="i_email_port">{{ __('Port') }}</label>
                <input type="number" name="email_port" id="i_email_port" class="form-control" value="{{ config('settings.email_port') }}">
            </div>

            <div class="form-group">
                <label for="i_email_encryption">{{ __('Encryption') }}</label>
                <select name="email_encryption" id="i_email_encryption" class="custom-select">
                    @foreach(['tls', 'ssl'] as $value)
                        <option value="{{ $value }}" @if (config('settings.email_encryption') == $value) selected @endif>{{ strtoupper($value) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="i_email_address">{{ __('Email address') }}</label>
                <input type="text" dir="ltr" name="email_address" id="i_email_address" class="form-control" value="{{ config('settings.email_address') }}">
            </div>

            <div class="form-group">
                <label for="i_email_username">{{ __('Username') }}</label>
                <input type="text" name="email_username" id="i_email_username" class="form-control" value="{{ config('settings.email_username') }}">
            </div>

            <div class="form-group">
                <label for="i_email_password">{{ __('Password') }}</label>
                <input type="password" name="email_password" id="i_email_password" class="form-control" value="{{ config('settings.email_password') }}">
            </div>

            <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>

    </div>
</div>