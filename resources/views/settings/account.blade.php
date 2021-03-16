@if(isset($admin))
    @section('site_title', formatTitle([__('Edit'), __('User'), config('settings.title')]))
@else
    @section('site_title', formatTitle([__('Account'), config('settings.title')]))
@endif

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => isset($admin) ? route('admin.dashboard') : route('dashboard'), 'title' => isset($admin) ? __('Admin') : __('Home')],
    ['url' => isset($admin) ? route('admin.users') : route('settings'), 'title' => isset($admin) ? __('Users') : __('Settings')],
    ['title' => isset($admin) ? __('Edit') : __('Account')]
]])

<div class="d-flex">
    <h2 class="mb-3 text-break">{{ isset($admin) ? __('Edit') : __('Account') }}</h2>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header">
        <div class="font-weight-medium py-1">
            @if(isset($admin))
                {{ __('User') }}
            @else
                {{ __('Account') }}
            @endif
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        @if($user->getPendingEmail() && isset($admin) == false)
            <div class="alert alert-info d-flex" role="alert">
                <div>
                    <form class="d-inline" method="POST" action="{{ route('settings.account.resend') }}" id="resend-form">
                        @csrf
                        {{ __(':address email address is pending confirmation', ['address' => $user->getPendingEmail()]) }}. {{ __('Didn\'t received the email?') }} <a href="#" class="alert-link font-weight-medium" onclick="event.preventDefault(); document.getElementById('resend-form').submit();">{{ __('Resend') }}</a>
                    </form>
                </div>
                <div class="{{ (__('lang_dir') == 'rtl' ? 'mr-auto' : 'ml-auto') }}">
                    <form class="d-inline" method="POST" action="{{ route('settings.account.cancel') }}" id="cancel-form">
                        @csrf
                        <a href="#" class="alert-link font-weight-medium" onclick="event.preventDefault(); document.getElementById('cancel-form').submit();">{{ __('Cancel') }}</a>
                    </form>
                </div>
            </div>
        @endif

        @if(isset($admin) && $user->trashed())
            <div class="alert alert-danger" role="alert">
                {{ __(':name is disabled.', ['name' => $user->name]) }}
            </div>
        @endif

        <form action="{{ isset($admin) ? route('admin.users.edit', $user->id) : route('settings.account.update') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="i_name">{{ __('Name') }}</label>
                <input type="text" name="name" id="i_name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') ?? $user->name }}">
                @if ($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i_email">{{ __('Email') }}</label>
                <input type="text" name="email" id="i_email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') ?? $user->email }}">
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i_timezone">{{ __('Timezone') }}</label>
                <select name="timezone" id="i_timezone" class="custom-select{{ $errors->has('timezone') ? ' is-invalid' : '' }}">
                    @foreach(timezone_identifiers_list() as $value)
                        <option value="{{ $value }}" @if ($value == $user->timezone) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
                @if ($errors->has('timezone'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('timezone') }}</strong>
                    </span>
                @endif
            </div>

            @if(isset($admin))
                <div class="form-group">
                    <label for="i_email_verified_at">{{ __('Verified') }}</label>
                    <select name="email_verified_at" id="i_email_verified_at" class="custom-select{{ $errors->has('email_verified_at') ? ' is-invalid' : '' }}">
                        <option value="0" @if (empty($user->email_verified_at)) selected @endif>{{ __('No') }}</option>
                        <option value="1" @if ($user->email_verified_at) selected @endif>{{ __('Yes') }}</option>
                    </select>
                    @if ($errors->has('email_verified_at'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email_verified_at') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="i_role">{{ __('Role') }}</label>
                    <select name="role" id="i_role" class="custom-select{{ $errors->has('role') ? ' is-invalid' : '' }}">
                        @foreach([0 => __('User'), 1 => __('Admin')] as $key => $value)
                            <option value="{{ $key }}" @if ($key == $user->role) selected @endif>{{ $value }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('role'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('role') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="i_password">{{ __('Password') }} <span class="text-muted">({{ mb_strtolower(__('Leave empty if you don\'t want to change it')) }})</span></label>
                    <input id="i_password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                </div>
            @endif

            <div class="row mt-3">
                <div class="col">
                    <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
                </div>
                <div class="col-auto">
                    @if(isset($admin))
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           {{ __('More') }}
                        </button>
                        <div class="dropdown-menu {{ (__('lang_dir') == 'rtl' ? 'dropdown-menu' : 'dropdown-menu-right') }} border-0 shadow">
                            @if($user->trashed())
                                <a class="dropdown-item text-success d-flex align-items-center" href="#" data-toggle="modal" data-target="#restoreModal">@include('icons.restore', ['class' => 'fill-current icon-dropdown '.(__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3')]) {{ __('Restore') }}</a>
                                <div class="dropdown-divider"></div>
                            @else
                                <a class="dropdown-item text-danger d-flex align-items-center" href="#" data-toggle="modal" data-target="#disableModal">@include('icons.block', ['class' => 'fill-current icon-dropdown '.(__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3')]) {{ __('Disable') }}</a>
                            @endif
                            <a class="dropdown-item text-danger d-flex align-items-center" href="#" data-toggle="modal" data-target="#deleteModal">@include('icons.delete', ['class' => 'fill-current icon-dropdown '.(__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3')]) {{ __('Delete') }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

@if(isset($admin))
    <div class="row">
        @php
            $menu = [
                ['icon' => 'icons.subscription', 'route' => 'admin.subscriptions', 'title' => __('Subscriptions'), 'stats' => 'subscriptions'],
                ['icon' => 'icons.link', 'route' => 'admin.links', 'title' => __('Links'), 'stats' => 'links'],
                ['icon' => 'icons.space', 'route' => 'admin.spaces', 'title' => __('Spaces'), 'stats' => 'spaces'],
                ['icon' => 'icons.domain', 'route' => 'admin.domains', 'title' => __('Domains'), 'stats' => 'domains']
            ];
        @endphp

        @foreach($menu as $link)
            <div class="col-12 col-md-6 col-lg-3 mt-3">
                <a href="{{ route($link['route'], ['user_id' => $user->id]) }}" class="text-decoration-none text-secondary">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            @include($link['icon'], ['class' => 'fill-current icon-text ' . (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3 ')])
                            <div>{{ $link['title'] }}</div>
                            @include((__('lang_dir') == 'rtl' ? 'icons.chevron_left' : 'icons.chevron_right'), ['class' => 'icon-chevron fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])
                            <div class="{{ (__('lang_dir') == 'rtl' ? 'mr-auto' : 'ml-auto') }} badge badge-primary">{{ number_format($stats[$link['stats']], 0, __('.'), __(',')) }}</div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{ __('Delete') }}</h6>
                    <button type="button" class="close d-flex align-items-center justify-content-center" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="d-flex align-items-center">@include('icons.close')</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ __('Are you sure you want to delete :name?', ['name' => $user->name]) }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <form action="{{ route('admin.users.delete', $user->id) }}" method="post" enctype="multipart/form-data">

                        @csrf

                        <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="disableModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{ __('Disable') }}</h6>
                    <button type="button" class="close d-flex align-items-center justify-content-center" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="d-flex align-items-center">@include('icons.close')</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if(config('settings.stripe'))
                    <div class="mb-3">{{ __('Disabling this account will cancel any active subscription.') }}</div>
                    @endif
                    <div>{{ __('Are you sure you want to disable :name?', ['name' => $user->name]) }}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <form action="{{ route('admin.users.disable', $user->id) }}" method="post" enctype="multipart/form-data">

                        @csrf

                        <button type="submit" class="btn btn-danger">{{ __('Disable') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="restoreModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{ __('Restore') }}</h6>
                    <button type="button" class="close d-flex align-items-center justify-content-center" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="d-flex align-items-center">@include('icons.close')</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if(config('settings.stripe'))
                    <div class="mb-3">{{ __('Restoring this account will resume any previously active subscription.') }}</div>
                    @endif
                    <div>{{ __('Are you sure you want to restore :name?', ['name' => $user->name]) }}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <form action="{{ route('admin.users.restore', $user->id) }}" method="post" enctype="multipart/form-data">

                        @csrf

                        <button type="submit" class="btn btn-success">{{ __('Restore') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif