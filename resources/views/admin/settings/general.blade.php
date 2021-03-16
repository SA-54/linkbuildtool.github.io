@section('site_title', formatTitle([__('Settings'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('admin.dashboard'), 'title' => __('Admin')],
    ['title' => __('Settings')],
]])

<h2 class="mb-3 d-inline-block">{{ __('Settings') }}</h2>

<div class="card border-0 shadow-sm">
    <div class="card-header"><div class="font-weight-medium py-1">{{ __('General') }}</div></div>
    <div class="card-body">

        @include('shared.message')

        <form action="{{ route('admin.settings.general') }}" method="post" enctype="multipart/form-data">

            @csrf

            <div class="form-group">
                <label for="i_title">{{ __('Title') }}</label>
                <input type="text" name="title" id="i_title" class="form-control" value="{{ config('settings.title') }}">
            </div>

            <div class="form-group">
                <label for="i_tagline">{{ __('Tagline') }}</label>
                <input type="text" name="tagline" id="i_tagline" class="form-control" value="{{ config('settings.tagline') }}">
            </div>

            <div class="form-group">
                <label for="i_index">{{ __('Custom index') }}</label>
                <input type="text" dir="ltr" name="index" id="i_index" class="form-control{{ $errors->has('index') ? ' is-invalid' : '' }}" value="{{ config('settings.index') }}">
                @if ($errors->has('index'))
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $errors->first('index') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i_paginate">{{ __('Pagination results') }}</label>
                <select name="paginate" id="i_paginate" class="custom-select{{ $errors->has('paginate') ? ' is-invalid' : '' }}">
                    @foreach([10, 20, 25, 50, 100] as $value)
                        <option value="{{ $value }}" @if (config('settings.paginate') == $value) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
                @if ($errors->has('paginate'))
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $errors->first('paginate') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i_timezone">{{ __('Timezone') }}</label>
                <select name="timezone" id="i_timezone" class="custom-select">
                    @foreach(timezone_identifiers_list() as $value)
                        <option value="{{ $value }}" @if (config('settings.timezone') == $value) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="i_tracking_code">{{ __('Tracking code') }}</label>
                <textarea name="tracking_code" id="i_tracking_code" class="form-control">{{ config('settings.tracking_code') }}</textarea>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>

    </div>
</div>