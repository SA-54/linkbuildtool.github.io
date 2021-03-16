@section('site_title', formatTitle([__('Preferences'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('dashboard'), 'title' => __('Home')],
    ['url' => route('settings'), 'title' => __('Settings')],
    ['title' => __('Preferences')]
]])

<div class="d-flex">
    <h2 class="mb-3 text-break">{{ __('Preferences') }}</h2>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header">
        <div class="font-weight-medium py-1">
            {{ __('Preferences') }}
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        @if(isset($admin) && $user->trashed())
            <div class="alert alert-danger" role="alert">
                {{ __(':name is disabled.', ['name' => $user->name]) }}
            </div>
        @endif

        <form action="{{ route('settings.preferences.update') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <div class="row">
                    <div class="col">
                        <label for="i_default_domain">{{ __('Domain') }} ({{ mb_strtolower(__('Default')) }})</label>
                    </div>
                    <div class="col-auto">
                        @cannot('domains', ['App\Link', $userFeatures['option_domains']])
                            @if(config('settings.stripe'))
                                <a href="{{ route('pricing') }}" data-toggle="tooltip" title="{{ __('Unlock feature') }}">@include('icons.unlock', ['class' => 'fill-current text-primary icon-text'])</a>
                            @endif
                        @endcannot
                    </div>
                </div>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">@include('icons.domain', ['class' => 'icon-label fill-current text-muted'])</div>
                    </div>
                    <select name="default_domain" id="i_default_domain" class="custom-select{{ $errors->has('default_domain') ? ' is-invalid' : '' }}">
                        <option value="">{{ __('None') }}</option>
                        @foreach($domains as $domain)
                            <option value="{{ $domain->id }}" @if(($user->default_domain == $domain->id && old('default_domain') == null) || ($domain->id == old('default_domain'))) selected @endif>{{ str_replace(['http://', 'https://'], '', $domain->name) }}</option>
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('default_domain'))
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $errors->first('default_domain') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col">
                        <label for="i_default_space">{{ __('Space') }} ({{ mb_strtolower(__('Default')) }})</label>
                    </div>
                    <div class="col-auto">
                        @cannot('spaces', ['App\Link', $userFeatures['option_spaces']])
                            @if(config('settings.stripe'))
                                <a href="{{ route('pricing') }}" data-toggle="tooltip" title="{{ __('Unlock feature') }}">@include('icons.unlock', ['class' => 'fill-current text-primary icon-text'])</a>
                            @endif
                        @endcannot
                    </div>
                </div>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">@include('icons.space', ['class' => 'icon-label fill-current text-muted'])</div>
                    </div>
                    <select name="default_space" id="i_default_space" class="custom-select{{ $errors->has('default_space') ? ' is-invalid' : '' }}" @cannot('spaces', ['App\Link', $userFeatures['option_spaces']]) disabled @endcan>
                        <option value="">{{ __('None') }}</option>
                        @foreach($spaces as $space)
                            <option value="{{ $space->id }}" @if(($user->default_space == $space->id && old('default_space') == null) || ($space->id == old('default_space'))) selected @endif>{{ $space->name }}</option>
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('default_space'))
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $errors->first('default_space') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col">
                        <label for="i_default_stats">{{ __('Stats') }} ({{ mb_strtolower(__('Default')) }})</label>
                    </div>
                    <div class="col-auto">
                        @cannot('stats', ['App\Link', $userFeatures['option_stats']])
                            @if(config('settings.stripe'))
                                <a href="{{ route('pricing') }}" data-toggle="tooltip" title="{{ __('Unlock feature') }}">@include('icons.unlock', ['class' => 'fill-current text-primary icon-text'])</a>
                            @endif
                        @endcannot
                    </div>
                </div>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">@include('icons.stats', ['class' => 'icon-label fill-current text-muted'])</div>
                    </div>
                    <select name="default_stats" id="i_default_stats" class="custom-select{{ $errors->has('default_stats') ? ' is-invalid' : '' }}" @cannot('expiration', ['App\Link', $userFeatures['option_stats']]) disabled @endcan>
                        @foreach([0 => __('Private'), 1 => __('Public')] as $key => $value)
                            <option value="{{ $key }}" @if(($user->default_stats == $key && old('default_stats') == null) || (old('default_stats') !== null && old('default_stats') == $key)) selected @endif>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('default_stats'))
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $errors->first('default_stats') }}</strong>
                    </span>
                @endif
            </div>

            <div class="row mt-3">
                <div class="col">
                    <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
                </div>
                <div class="col-auto">
                </div>
            </div>
        </form>
    </div>
</div>