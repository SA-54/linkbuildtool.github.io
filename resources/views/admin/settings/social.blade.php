@section('site_title', formatTitle([__('Settings'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('admin.dashboard'), 'title' => __('Admin')],
    ['title' => __('Settings')],
]])

<h2 class="mb-3 d-inline-block">{{ __('Settings') }}</h2>

<div class="card border-0 shadow-sm">
    <div class="card-header"><div class="font-weight-medium py-1">{{ __('Social') }}</div></div>
    <div class="card-body">

        @include('shared.message')

        <form action="{{ route('admin.settings.social') }}" method="post" enctype="multipart/form-data">

            @csrf

            <div class="form-group">
                <label for="i_social_facebook">{{ __('Facebook') }}</label>
                <input type="text" dir="ltr" name="social_facebook" id="i_social_facebook" class="form-control" value="{{ config('settings.social_facebook') }}">
            </div>

            <div class="form-group">
                <label for="i_social_twitter">{{ __('Twitter') }}</label>
                <input type="text" dir="ltr" name="social_twitter" id="i_social_twitter" class="form-control" value="{{ config('settings.social_twitter') }}">
            </div>

            <div class="form-group">
                <label for="i_social_instagram">{{ __('Instagram') }}</label>
                <input type="text" dir="ltr" name="social_instagram" id="i_social_instagram" class="form-control" value="{{ config('settings.social_instagram') }}">
            </div>

            <div class="form-group">
                <label for="i_social_youtube">{{ __('YouTube') }}</label>
                <input type="text" dir="ltr" name="social_youtube" id="i_social_youtube" class="form-control" value="{{ config('settings.social_youtube') }}">
            </div>

            <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>

    </div>
</div>