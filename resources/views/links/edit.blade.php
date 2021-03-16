@section('site_title', formatTitle([__('Edit'), __('Link'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => isset($admin) ? route('admin.dashboard') : route('dashboard'), 'title' => isset($admin) ? __('Admin') : __('Home')],
    ['url' => isset($admin) ? route('admin.links') : route('links'), 'title' => __('Links')],
    ['title' => __('Edit')],
]])

<div class="d-flex">
    <h2 class="mb-0 flex-grow-1 text-break">{{ __('Edit') }}</h2>

    <div class="d-flex align-items-center flex-grow-0">
        @include('shared.buttons.copy_link')
        @include('shared.dropdowns.link', ['options' => ['dropdown' => ['button' => true, 'edit' => true, 'share' => true, 'stats' => true, 'preview' => true, 'open' => true, 'delete' => true]]])
    </div>
</div>

<div class="card border-0 shadow-sm mt-3">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col">
                <div class="font-weight-medium py-1">{{ __('Link') }}</div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        <form action="{{ isset($admin) ? route('admin.links.edit', $link->id) : route('links.edit', $link->id) }}" method="post" enctype="multipart/form-data" autocomplete="off">
            @csrf

            @if(isset($admin))
                <input type="hidden" name="user_id" value="{{ isset($link->user) ? $link->user->id : '0' }}">
            @endif

            <div class="row">
                <div class="col-12">
                    <label for="i_url">{{ __('Link') }}</label>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">@include('icons.link', ['class' => 'icon-label fill-current text-muted'])</div>
                            </div>
                            <input type="text" dir="ltr" name="url" class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}" autocapitalize="none" spellcheck="false" id="i_url" value="{{ old('url') ?? $link->url }}">
                            <div class="input-group-append" data-toggle="tooltip" title="{{ __('UTM Builder') }}">
                                <a href="#" class="btn text-secondary bg-transparent input-group-text d-flex align-items-center" data-toggle="modal" data-target="#utmModal" id="utm_builder">
                                    @include('icons.tag', ['class' => 'fill-current icon-button'])
                                </a>
                            </div>
                        </div>
                        @if ($errors->has('url'))
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('url') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="i_domain">{{ __('Domain') }}</label>
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
                            <select name="domain" id="i_domain" class="custom-select{{ $errors->has('domain') ? ' is-invalid' : '' }}" disabled>
                                <option value="">{{ __('None') }}</option>
                                @foreach($domains as $domain)
                                    <option value="{{ $domain->id }}" @if((old('domain') !== null && old('domain') == $domain->id) || ($link->domain_id == $domain->id && old('domain') == null)) selected @endif>{{ $domain->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('domain'))
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('domain') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label for="i_alias">{{ __('Alias') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">@include('icons.alias', ['class' => 'icon-label fill-current text-muted'])</div>
                            </div>
                            <input type="text" name="alias" class="form-control{{ $errors->has('alias') ? ' is-invalid' : '' }}" autocapitalize="none" spellcheck="false" id="i_alias" value="{{ old('alias') ?? $link->alias }}">
                        </div>
                        @if ($errors->has('alias'))
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('alias') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="i_space">{{ __('Space') }}</label>
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
                            <select name="space" id="i_space" class="custom-select{{ $errors->has('space') ? ' is-invalid' : '' }}" @cannot('spaces', ['App\Link', $userFeatures['option_spaces']]) disabled @endcan>
                                <option value="">{{ __('None') }}</option>
                                @foreach($spaces as $space)
                                    <option value="{{ $space->id }}" @if((old('space') !== null && old('space') == $space->id) || ($link->space_id == $space->id && old('space') == null)) selected @endif>{{ $space->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('space'))
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('space') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="i_password">{{ __('Password') }}</label>
                            </div>
                            <div class="col-auto">
                                @cannot('password', ['App\Link', $userFeatures['option_password']])
                                    @if(config('settings.stripe'))
                                        <a href="{{ route('pricing') }}" data-toggle="tooltip" title="{{ __('Unlock feature') }}">@include('icons.unlock', ['class' => 'fill-current text-primary icon-text'])</a>
                                    @endif
                                @endcannot
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">@include('icons.security', ['class' => 'icon-label fill-current text-muted'])</div>
                            </div>
                            <input type="password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="i_password" value="{{ old('password') ?? $link->password }}" autocomplete="new-password" @cannot('password', ['App\Link', $userFeatures['option_password']]) disabled @endcan>
                        </div>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="i_expiration_date">{{ __('Expiration date') }}</label>
                            </div>
                            <div class="col-auto">
                                @cannot('expiration', ['App\Link', $userFeatures['option_expiration']])
                                    @if(config('settings.stripe'))
                                        <a href="{{ route('pricing') }}" data-toggle="tooltip" title="{{ __('Unlock feature') }}">@include('icons.unlock', ['class' => 'fill-current text-primary icon-text'])</a>
                                    @endif
                                @endcannot
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">@include('icons.calendar', ['class' => 'icon-label fill-current text-muted'])</div>
                            </div>
                            <input type="date" name="expiration_date" class="form-control{{ $errors->has('expiration_date') ? ' is-invalid' : '' }}" id="i_expiration_date" placeholder="YYYY-MM-DD" value="{{ old('expiration_date') ?? ($link->ends_at ? $link->ends_at->tz(Auth::user()->timezone ?? config('app.timezone'))->format('Y-m-d') : '') }}" @cannot('expiration', ['App\Link', $userFeatures['option_expiration']]) disabled @endcan>
                            <input type="time" name="expiration_time" class="form-control{{ $errors->has('expiration_time') ? ' is-invalid' : '' }}" placeholder="HH:MM" value="{{ old('expiration_time') ?? ($link->ends_at ? $link->ends_at->tz(Auth::user()->timezone ?? config('app.timezone'))->format('H:i') : '') }}" @cannot('expiration', ['App\Link', $userFeatures['option_expiration']]) disabled @endcan>
                            <div class="input-group-append">
                                <div class="input-group-text">@include('icons.expire', ['class' => 'icon-label fill-current text-muted'])</div>
                            </div>
                        </div>
                        <div class="row no-gutters">
                            <div class="col">
                                @if ($errors->has('expiration_date'))
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $errors->first('expiration_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col">
                                @if ($errors->has('expiration_time'))
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $errors->first('expiration_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <div class="row no-gutters">
                            <div class="col-8">
                                <div class="row">
                                    <div class="col"><label for="i_expiration_url">{{ __('Expiration link') }}</label></div>
                                    <div class="col-auto">
                                        @cannot('expiration', ['App\Link', $userFeatures['option_expiration']])
                                            @if(config('settings.stripe'))
                                                <a href="{{ route('pricing') }}" data-toggle="tooltip" title="{{ __('Unlock feature') }}">@include('icons.unlock', ['class' => 'fill-current text-primary icon-text'])</a>
                                            @endif
                                        @endcannot
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 {{ (__('lang_dir') == 'rtl' ? 'text-left' : 'text-right') }}">
                                <label for="i_expiration_clicks">{{ __('Clicks') }}</label>
                                @cannot('expiration', ['App\Link', $userFeatures['option_expiration']])
                                    @if(config('settings.stripe'))
                                        <a href="{{ route('pricing') }}" data-toggle="tooltip" title="{{ __('Unlock feature') }}">@include('icons.unlock', ['class' => 'fill-current text-primary icon-text'])</a>
                                    @endif
                                @endcannot
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">@include('icons.link', ['class' => 'icon-label fill-current text-muted'])</div>
                            </div>
                            <input type="text" dir="ltr" name="expiration_url" id="i_expiration_url" class="form-control{{ $errors->has('expiration_url') ? ' is-invalid' : '' }}" autocapitalize="none" spellcheck="false" value="{{ old('expiration_url') ?? $link->expiration_url }}" @cannot('expiration', ['App\Link', $userFeatures['option_expiration']]) disabled @endcan>
                            <input type="number" name="expiration_clicks" id="i_expiration_clicks" class="form-control w-25 flex-none {{ $errors->has('expiration_clicks') ? ' is-invalid' : '' }}" autocapitalize="none" spellcheck="false" value="{{ old('expiration_clicks') ?? $link->expiration_clicks }}" @cannot('expiration', ['App\Link', $userFeatures['option_expiration']]) disabled @endcan>
                            <div class="input-group-append">
                                <div class="input-group-text">@include('icons.mouse', ['class' => 'icon-label fill-current text-muted'])</div>
                            </div>
                        </div>
                        <div class="row no-gutters">
                            <div class="col-8">
                                @if ($errors->has('expiration_url'))
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $errors->first('expiration_url') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-4">
                                @if ($errors->has('expiration_clicks'))
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $errors->first('expiration_clicks') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="i_public">{{ __('Stats') }}</label>
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
                            <select name="public" id="i_public" class="custom-select{{ $errors->has('public') ? ' is-invalid' : '' }}" @cannot('expiration', ['App\Link', $userFeatures['option_stats']]) disabled @endcan>
                                @foreach([0 => __('Private'), 1 => __('Public')] as $key => $value)
                                    <option value="{{ $key }}" @if ((old('public') !== null && old('public') == $key) || ($link->public == $key && old('public') == null)) selected @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('public'))
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('public') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="i_disabled">{{ __('Disabled') }}</label>
                            </div>
                            <div class="col-auto">
                                @cannot('disabled', ['App\Link', $userFeatures['option_disabled']])
                                    @if(config('settings.stripe'))
                                        <a href="{{ route('pricing') }}" data-toggle="tooltip" title="{{ __('Unlock feature') }}">@include('icons.unlock', ['class' => 'fill-current text-primary icon-text'])</a>
                                    @endif
                                @endcannot
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">@include('icons.block', ['class' => 'icon-label fill-current text-muted'])</div>
                            </div>
                            <select name="disabled" id="i_disabled" class="custom-select{{ $errors->has('disabled') ? ' is-invalid' : '' }}" @cannot('disabled', ['App\Link', $userFeatures['option_disabled']]) disabled @endcan>
                                @foreach([0 => __('No'), 1 => __('Yes')] as $key => $value)
                                    <option value="{{ $key }}" @if ((old('disabled') !== null && old('disabled') == $key) || ($link->disabled == $key && old('disabled') == null)) selected @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('disabled'))
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('disabled') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="hr-text"><span class="font-weight-medium text-muted">{{ __('Targeting') }}</span></div>

            <div class="mb-3">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    @foreach([  ['title' => __('None'), 'value' => 0, 'name' => '', 'input' => 'empty'],
                                ['title' => __('Geographic'), 'value' => 1, 'name' => 'geographic', 'input' => 'geo'],
                                ['title' => __('Platforms'), 'value' => 2, 'name' => 'platforms', 'input' => 'platform'],
                                ['title' => __('Rotation'), 'value' => 3, 'name' => 'rotation', 'input' => 'rotation']
                                ] as $targetButton)
                        <label class="btn btn-outline-{{ ($errors->has($targetButton['input'].'.*.key') || $errors->has($targetButton['input'].'.*.value') ? 'danger' : 'secondary') }} d-flex align-items-center{{ old('target_type') !== null ? (old('target_type') == $targetButton['value'] ? ' active' : '') : ($link->target_type == $targetButton['value'] ? ' show active' : '') }}">
                            <input type="radio" name="target_type" value="{{ $targetButton['value'] }}" data-target="#{{ $targetButton['input'] }}-container"{{ old('target_type') !== null ? (old('target_type') == $targetButton['value'] ? ' checked' : '') : ($link->target_type == $targetButton['value'] ? ' checked' : '') }}>
                            @if($targetButton['name'])
                                @include('icons.'.$targetButton['name'], ['class' => 'icon-button fill-current'])
                            @endif
                            <span class="d-md-inline-block {{ ($targetButton['value'] ? (__('lang_dir') == 'rtl' ? 'd-none mr-2' : 'd-none ml-2') : '') }}">&#8203;{{ $targetButton['title'] }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="tab-content">
                <div id="empty-container" class="tab-pane fade{{ old('target_type') !== null ? (old('target_type') == 0 ? ' show active' : '') : ($link->target_type == 0 ? ' show active' : '') }}"></div>

                <div id="geo-container" class="tab-pane fade{{ old('target_type') !== null ? (old('target_type') == 1 ? ' show active' : '') : ($link->target_type == 1 ? ' show active' : '') }}">
                    <input name="geo[empty][key]" type="hidden" disabled>
                    <input name="geo[empty][value]" type="hidden" disabled>

                    <div class="input-content">
                        <div class="row d-none input-template">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">@include('icons.geographic', ['class' => 'icon-label fill-current text-muted'])</div>
                                        </div>
                                        <select name="geo_key[]" data-input="key" class="custom-select" disabled>
                                            <option value="" selected>{{ __('Country') }}</option>
                                            @foreach(config('countries') as $key => $value)
                                                <option value="{{ $key }}">{{ __($value) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-row">
                                    <div class="col">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">@include('icons.link', ['class' => 'icon-label fill-current text-muted'])</div>
                                                </div>
                                                <input type="text" data-input="value" class="form-control" autocapitalize="none" spellcheck="false" value="" disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-auto form-group d-flex align-items-start">
                                        <button type="button" class="btn btn-outline-danger d-flex align-items-center input-delete">@include('icons.delete', ['class' => 'icon-button fill-current'])&#8203;</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php
                            if (old('geo')) {
                                $geoList = old('geo');
                            } elseif($link->geo_target) {
                                $geoList = json_decode(json_encode($link->geo_target), true);
                            } else {
                                $geoList = [];
                            }
                        @endphp

                        @foreach($geoList as $id => $geo)
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">@include('icons.geographic', ['class' => 'icon-label fill-current text-muted'])</div>
                                            </div>
                                            <select name="geo[{{ $id }}][key]" data-input="key" class="custom-select{{ $errors->has('geo.'.$id.'.key') ? ' is-invalid' : '' }}">
                                                <option value="">{{ __('Country') }}</option>
                                                @foreach(config('countries') as $key => $value)
                                                    <option value="{{ $key }}" @if($geo['key'] == $key) selected @endif>{{ __($value) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('geo.'.$id.'.key'))
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $errors->first('geo.'.$id.'.key') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-row">
                                        <div class="col">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">@include('icons.link', ['class' => 'icon-label fill-current text-muted'])</div>
                                                    </div>
                                                    <input type="text" dir="ltr" name="geo[{{ $id }}][value]" data-input="value" class="form-control{{ $errors->has('geo.'.$id.'.value') ? ' is-invalid' : '' }}" autocapitalize="none" spellcheck="false" value="{{ $geo['value'] }}">
                                                </div>
                                                @if ($errors->has('geo.'.$id.'.value'))
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $errors->first('geo.'.$id.'.value') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-auto form-group d-flex align-items-start">
                                            <button type="button" class="btn btn-outline-danger d-flex align-items-center input-delete">@include('icons.delete', ['class' => 'icon-button fill-current'])&#8203;</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @can('geo', ['App\Link', $userFeatures['option_geo']])
                        <button type="button" class="btn btn-outline-secondary input-add d-inline-flex align-items-center">@include('icons.add', ['class' => 'icon-button fill-current'])&#8203;</button>
                    @else
                        @if(config('settings.stripe'))
                            <a href="{{ route('pricing') }}" class="btn btn-outline-primary d-inline-flex align-items-center" data-toggle="tooltip" title="{{ __('Unlock feature') }}">@include('icons.unlock', ['class' => 'icon-button fill-current'])&#8203;</a>
                        @endif
                    @endcan
                </div>

                <div id="platform-container" class="tab-pane fade{{ old('target_type') !== null ? (old('target_type') == 2 ? ' show active' : '') : ($link->target_type == 2 ? ' show active' : '') }}">
                    <input name="platform[empty][key]" type="hidden" disabled>
                    <input name="platform[empty][value]" type="hidden" disabled>

                    <div class="input-content">
                        <div class="row d-none input-template">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">@include('icons.platforms', ['class' => 'icon-label fill-current text-muted'])</div>
                                        </div>
                                        <select name="platform_key[]" data-input="key" class="custom-select" disabled>
                                            <option value="" selected>{{ __('Platform') }}</option>
                                            @foreach(config('platforms') as $platform)
                                                <option value="{{ $platform }}">{{ $platform }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-row">
                                    <div class="col">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">@include('icons.link', ['class' => 'icon-label fill-current text-muted'])</div>
                                                </div>
                                                <input type="text" data-input="value" class="form-control" autocapitalize="none" spellcheck="false" value="" disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-auto form-group d-flex align-items-start">
                                        <button type="button" class="btn btn-outline-danger d-flex align-items-center input-delete">@include('icons.delete', ['class' => 'icon-button fill-current'])&#8203;</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php
                            if (old('platform')) {
                                $platformList = old('platform');
                            } elseif($link->platform_target) {
                                $platformList = json_decode(json_encode($link->platform_target), true);
                            } else {
                                $platformList = [];
                            }
                        @endphp

                        @foreach($platformList as $id => $platform)
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">@include('icons.platforms', ['class' => 'icon-label fill-current text-muted'])</div>
                                            </div>
                                            <select name="platform[{{ $id }}][key]" data-input="key" class="custom-select{{ $errors->has('platform.'.$id.'.key') ? ' is-invalid' : '' }}">
                                                <option value="">{{ __('Platform') }}</option>
                                                @foreach(config('platforms') as $value)
                                                    <option value="{{ $value }}" @if($platform['key'] == $value) selected @endif>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('platform.'.$id.'.key'))
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $errors->first('platform.'.$id.'.key') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-row">
                                        <div class="col">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">@include('icons.link', ['class' => 'icon-label fill-current text-muted'])</div>
                                                    </div>
                                                    <input type="text" dir="ltr" name="platform[{{ $id }}][value]" data-input="value" class="form-control{{ $errors->has('platform.'.$id.'.value') ? ' is-invalid' : '' }}" autocapitalize="none" spellcheck="false" value="{{ $platform['value'] }}">
                                                </div>
                                                @if ($errors->has('platform.'.$id.'.value'))
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $errors->first('platform.'.$id.'.value') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-auto form-group d-flex align-items-start">
                                            <button type="button" class="btn btn-outline-danger d-flex align-items-center input-delete">@include('icons.delete', ['class' => 'icon-button fill-current'])&#8203;</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @can('platform', ['App\Link', $userFeatures['option_platform']])
                        <button type="button" class="btn btn-outline-secondary input-add d-inline-flex align-items-center">@include('icons.add', ['class' => 'icon-button fill-current'])&#8203;</button>
                    @else
                        @if(config('settings.stripe'))
                            <a href="{{ route('pricing') }}" class="btn btn-outline-primary d-inline-flex align-items-center" data-toggle="tooltip" title="{{ __('Unlock feature') }}">@include('icons.unlock', ['class' => 'icon-button fill-current'])&#8203;</a>
                        @endif
                    @endcan
                </div>

                <div id="rotation-container" class="tab-pane fade{{ old('target_type') !== null ? (old('target_type') == 3 ? ' show active' : '') : ($link->target_type == 3 ? ' show active' : '') }}">
                    <input name="rotation[empty][value]" type="hidden" disabled>
                    <div class="input-content">
                        <div class="row d-none input-template">
                            <div class="col-12">
                                <div class="form-row">
                                    <div class="col">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">@include('icons.link', ['class' => 'icon-label fill-current text-muted'])</div>
                                                </div>
                                                <input type="text" data-input="value" class="form-control" autocapitalize="none" spellcheck="false" value="" disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-auto form-group d-flex align-items-start">
                                        <button type="button" class="btn btn-outline-danger d-flex align-items-center input-delete">@include('icons.delete', ['class' => 'icon-button fill-current'])&#8203;</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php
                            if (old('rotation')) {
                                $rotationList = old('rotation');
                            } elseif($link->rotation_target) {
                                $rotationList = json_decode(json_encode($link->rotation_target), true);
                            } else {
                                $rotationList = [];
                            }
                        @endphp

                        @foreach($rotationList as $id => $rotation)
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-row">
                                        <div class="col">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">@include('icons.link', ['class' => 'icon-label fill-current text-muted'])</div>
                                                    </div>
                                                    <input type="text" dir="ltr" name="rotation[{{ $id }}][value]" data-input="value" class="form-control{{ $errors->has('rotation.'.$id.'.value') ? ' is-invalid' : '' }}" autocapitalize="none" spellcheck="false" value="{{ $rotation['value'] }}">
                                                </div>
                                                @if ($errors->has('rotation.'.$id.'.value'))
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $errors->first('rotation.'.$id.'.value') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-auto form-group d-flex align-items-start">
                                            <button type="button" class="btn btn-outline-danger d-flex align-items-center input-delete">@include('icons.delete', ['class' => 'icon-button fill-current'])&#8203;</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @can('linkRotation', ['App\Link', $userFeatures['option_link_rotation']])
                        <button type="button" class="btn btn-outline-secondary input-add d-inline-flex align-items-center">@include('icons.add', ['class' => 'icon-button fill-current'])&#8203;</button>
                    @else
                        @if(config('settings.stripe'))
                            <a href="{{ route('pricing') }}" class="btn btn-outline-primary d-inline-flex align-items-center" data-toggle="tooltip" title="{{ __('Unlock feature') }}">@include('icons.unlock', ['class' => 'icon-button fill-current'])&#8203;</a>
                        @endif
                    @endcan
                </div>
            </div>

            <div class="row mt-3">
                <div class="col">
                    <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#deleteLinkModal" data-action="{{ isset($admin) ? route('admin.links.delete', $link->id) : route('links.delete', $link->id) }}" data-text="{{ __('Are you sure you want to delete :name?', ['name' => (str_replace(['http://', 'https://'], '', (($link->domain->name ?? config('app.url')) . '/' . $link->alias)))]) }}">{{ __('Delete') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if(isset($admin))
    @if(isset($link->user))
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header">
                <div class="row"><div class="col"><div class="font-weight-medium py-1">{{ __('User') }}</div></div><div class="col-auto"><a href="{{ route('admin.users.edit', $link->user->id) }}" class="btn btn-outline-primary btn-sm">{{ __('Edit') }}</a></div></div>
            </div>
            <div class="card-body mb-n3">
                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <div class="text-muted">{{ __('Name') }}</div>
                        <div>{{ $link->user->name }}</div>
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <div class="text-muted">{{ __('Email') }}</div>
                        <div>{{ $link->user->email }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif

@include('shared.modals.utm')
@include('shared.modals.share_link', ['admin' => isset($admin) ? true : false])
@include('shared.modals.delete_link', ['admin' => isset($admin) ? true : false])