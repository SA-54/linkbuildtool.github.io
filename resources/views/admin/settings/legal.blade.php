@section('site_title', formatTitle([__('Settings'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('admin.dashboard'), 'title' => __('Admin')],
    ['title' => __('Settings')],
]])

<h2 class="mb-3 d-inline-block">{{ __('Settings') }}</h2>

<div class="card border-0 shadow-sm">
    <div class="card-header"><div class="font-weight-medium py-1">{{ __('Legal') }}</div></div>
    <div class="card-body">

        @include('shared.message')

        <form action="{{ route('admin.settings.legal') }}" method="post" enctype="multipart/form-data">

            @csrf

            <div class="form-group">
                <label for="i_legal_terms_url">{{ __(':name URL', ['name' => __('Terms of Service')]) }}</label>
                <input type="text" dir="ltr" name="legal_terms_url" id="i_legal_terms_url" class="form-control" value="{{ config('settings.legal_terms_url') }}">
            </div>

            <div class="form-group">
                <label for="i_privacy_url">{{ __(':name URL', ['name' => __('Privacy Policy')]) }}</label>
                <input type="text" dir="ltr" name="legal_privacy_url" id="i_privacy_url" class="form-control" value="{{ config('settings.legal_privacy_url') }}">
            </div>

            <div class="form-group">
                <label for="i_cookie_url">{{ __(':name URL', ['name' => __('Cookie Policy')]) }}</label>
                <input type="text" dir="ltr" name="legal_cookie_url" id="i_cookie_url" class="form-control" value="{{ config('settings.legal_cookie_url') }}">
            </div>

            <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>

    </div>
</div>