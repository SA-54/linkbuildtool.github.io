@section('site_title', formatTitle([__('Settings'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('admin.dashboard'), 'title' => __('Admin')],
    ['title' => __('Settings')],
]])

<h2 class="mb-3 d-inline-block">{{ __('Settings') }}</h2>

<div class="card border-0 shadow-sm">
    <div class="card-header"><div class="font-weight-medium py-1">{{ __('Registration') }}</div></div>
    <div class="card-body">

        @include('shared.message')

        <form action="{{ route('admin.settings.registration') }}" method="post" enctype="multipart/form-data">

            @csrf

            <div class="form-group">
                <label for="i_registration_registration">{{ __('Registration') }}</label>
                <select name="registration_registration" id="i_registration_registration" class="custom-select">
                    @foreach([0 => __('Disabled'), 1 => __('Enabled')] as $key => $value)
                        <option value="{{ $key }}" @if (config('settings.registration_registration') == $key) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="i_registration_verification">{{ __('Email verification') }}</label>
                <select name="registration_verification" id="i_registration_verification" class="custom-select">
                    @foreach([0 => __('Disabled'), 1 => __('Enabled')] as $key => $value)
                        <option value="{{ $key }}" @if (config('settings.registration_verification') == $key) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>

    </div>
</div>