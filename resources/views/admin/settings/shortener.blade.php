@section('site_title', formatTitle([__('Settings'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('admin.dashboard'), 'title' => __('Admin')],
    ['title' => __('Settings')],
]])

<h2 class="mb-3 d-inline-block">{{ __('Settings') }}</h2>

<div class="card border-0 shadow-sm">
    <div class="card-header"><div class="font-weight-medium py-1">{{ __('Shortener') }}</div></div>
    <div class="card-body">

        @include('shared.message')

        <form action="{{ route('admin.settings.shortener') }}" method="post" enctype="multipart/form-data">

            @csrf

            <div class="form-group">
                <label for="i_short_guest">{{ __('Guest shortening') }}</label>
                <select name="short_guest" id="i_short_guest" class="custom-select">
                    @foreach([0 => __('Disabled'), 1 => __('Enabled')] as $key => $value)
                        <option value="{{ $key }}" @if (config('settings.short_guest') == $key) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="i_short_protocol" class="d-flex align-items-center"><span>{{ __('Domains protocol') }}</span> <span data-toggle="tooltip" title="{{ __('Use HTTPS only if you are able to generate SSL certificates for the additional domains.') }}" class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}">@include('icons.info', ['class' => 'fill-current text-muted icon-text'])</span></label>
                <select name="short_protocol" id="i_short_protocol" class="custom-select">
                    @foreach(['http' => 'HTTP', 'https' => 'HTTPS'] as $key => $value)
                        <option value="{{ $key }}" @if (config('settings.short_protocol') == $key) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="i_short_bad_words" class="d-flex align-items-center"><span>{{ __('Bad words') }}</span> <span data-toggle="tooltip" title="{{ __('New row acts as a delimiter.') }}" class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}">@include('icons.info', ['class' => 'fill-current text-muted icon-text'])</span></label>
                <textarea name="short_bad_words" id="i_short_bad_words" class="form-control" rows="3">{{ config('settings.short_bad_words') }}</textarea>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>

    </div>
</div>