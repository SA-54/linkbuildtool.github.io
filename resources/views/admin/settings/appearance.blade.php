@section('site_title', formatTitle([__('Settings'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('admin.dashboard'), 'title' => __('Admin')],
    ['title' => __('Settings')],
]])

<h2 class="mb-3 d-inline-block">{{ __('Settings') }}</h2>

<div class="card border-0 shadow-sm">
    <div class="card-header"><div class="font-weight-medium py-1">{{ __('Appearance') }}</div></div>
    <div class="card-body">

        @include('shared.message')

        <form action="{{ route('admin.settings.appearance') }}" method="post" enctype="multipart/form-data">

            @csrf

            <div class="form-group">
                <label for="i_logo">{{ __('Logo') }}</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text py-1 px-2"><img src="{{ url('/') }}/uploads/brand/{{ config('settings.logo') }}" style="max-height: 1.625rem"></span>
                    </div>
                    <div class="custom-file">
                        <input type="file" name="logo" id="i_logo" class="custom-file-input{{ $errors->has('logo') ? ' is-invalid' : '' }}" accept="jpeg,png,bmp,gif,svg,webp">
                        <label class="custom-file-label" for="i_logo" data-browse="{{ __('Browse') }}">{{ __('Choose file') }}</label>
                    </div>
                </div>
                @if ($errors->has('logo'))
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $errors->first('logo') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i_favicon">{{ __('Favicon') }}</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text py-1 px-2"><img src="{{ url('/') }}/uploads/brand/{{ config('settings.favicon') }}" style="max-height: 1.625rem;"></span>
                    </div>
                    <div class="custom-file">
                        <input type="file" name="favicon" id="i_favicon" class="custom-file-input{{ $errors->has('favicon') ? ' is-invalid' : '' }}" accept="jpeg,png,bmp,gif,svg,webp">
                        <label class="custom-file-label" for="i_favicon" data-browse="{{ __('Browse') }}">{{ __('Choose file') }}</label>
                    </div>
                </div>
                @if ($errors->has('favicon'))
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $errors->first('favicon') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i_theme">{{ __('Theme') }} ({{ mb_strtolower(__('Default')) }})</label>
                <select name="theme" id="i_theme" class="custom-select{{ $errors->has('theme') ? ' is-invalid' : '' }}">
                    @foreach([0 => __('Light'), 1 => __('Dark')] as $key => $value)
                        <option value="{{ $key }}" @if(config('settings.theme') == $key) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
                @if ($errors->has('theme'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('theme') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i_custom_css">{{ __('Custom CSS') }}</label>
                <textarea name="custom_css" id="i_custom_css" class="form-control">{{ config('settings.custom_css') }}</textarea>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>

    </div>
</div>