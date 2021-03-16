@section('site_title', formatTitle([__('Settings'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('admin.dashboard'), 'title' => __('Admin')],
    ['title' => __('Settings')],
]])

<h2 class="mb-3 d-inline-block">{{ __('Settings') }}</h2>

<div class="card border-0 shadow-sm">
    <div class="card-header"><div class="font-weight-medium py-1">{{ __('Contact') }}</div></div>
    <div class="card-body">

        @include('shared.message')

        <form action="{{ route('admin.settings.contact') }}" method="post" enctype="multipart/form-data">

            @csrf

            <div class="form-group">
                <label for="i_contact_email">{{ __('Email address') }}</label>
                <input id="i_contact_email" type="text" dir="ltr" class="form-control{{ $errors->has('contact_email') ? ' is-invalid' : '' }}" name="contact_email" value="{{ config('settings.contact_email') }}">
                @if ($errors->has('contact_email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('contact_email') }}</strong>
                    </span>
                @endif
            </div>

            <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>

    </div>
</div>