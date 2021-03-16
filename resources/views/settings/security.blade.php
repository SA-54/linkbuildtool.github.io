@section('site_title', formatTitle([__('Security'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('dashboard'), 'title' => __('Home')],
    ['url' => route('settings'), 'title' => __('Settings')],
    ['title' => __('Security')]
]])

<h2 class="mb-3 d-inline-block">{{ __('Security') }}</h2>

<div class="card border-0 shadow-sm">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col"><div class="font-weight-medium py-1">{{ __('Security') }}</div></div>
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        <form action="{{ route('settings.security.update') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="i_current_password">{{ __('Current password') }}</label>
                <input type="password" name="current_password" id="i_current_password" class="form-control{{ $errors->has('current_password') ? ' is-invalid' : '' }}">
                @if ($errors->has('current_password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('current_password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i_password">{{ __('New password') }}</label>
                <input type="password" name="password" id="i_password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}">
                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i_password_confirmation">{{ __('Confirm new password') }}</label>
                <input type="password" name="password_confirmation" id="i_password_confirmation" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}">
                @if ($errors->has('password_confirmation'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif
            </div>

            <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>
    </div>
</div>