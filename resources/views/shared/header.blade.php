@guest
<div id="header" class="header sticky-top shadow bg-base-0 z-1025">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light px-0 py-3">
            <a href="{{ route('home') }}" aria-label="{{ config('settings.title') }}" class="navbar-brand p-0">
                <div class="logo">
                    <img src="{{ url('/') }}/uploads/brand/{{ config('settings.logo') }}">
                </div>
            </a>
            <button class="navbar-toggler border-0 p-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav pt-2 p-lg-0 {{ (__('lang_dir') == 'rtl' ? 'mr-auto' : 'ml-auto') }}">
                    @if(config('settings.stripe'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pricing') }}" role="button">{{ __('Pricing') }}</a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}" role="button">{{ __('Login') }}</a>
                    </li>

                    @if(config('settings.registration_registration'))
                        <li class="nav-item d-flex align-items-center">
                            <a class="btn btn-outline-primary" href="{{ route('register') }}" role="button">{{ __('Register') }}</a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
    </div>
</div>
@else
<div id="header" class="header sticky-top shadow bg-base-0 z-1025 d-lg-none">
    <div class="container-fluid">
        <nav class="navbar navbar-light px-0 py-3">
            <a href="{{ route('dashboard') }}" aria-label="{{ config('settings.title') }}" class="navbar-brand p-0">
                <div class="logo">
                    <img src="{{ url('/') }}/uploads/brand/{{ config('settings.logo') }}">
                </div>
            </a>
            <button class="slide-menu-toggle navbar-toggler border-0 p-0" type="button">
                <span class="navbar-toggler-icon"></span>
            </button>
        </nav>
    </div>
</div>

<nav class="slide-menu shadow bg-base-0 ct navbar navbar-light p-0 d-flex flex-column z-1025" id="slide-menu">
    <div class="sidebar-section flex-grow-1 d-flex flex-column w-100">
        <div>
            <div class="{{ (__('lang_dir') == 'rtl' ? 'pr-4' : 'pl-4') }} py-3 d-flex align-items-center">
                <a href="{{ route('dashboard') }}" aria-label="{{ config('settings.title') }}" class="navbar-brand p-0">
                    <div class="logo">
                        <img src="{{ url('/') }}/uploads/brand/{{ config('settings.logo') }}">
                    </div>
                </a>
                <div class="close slide-menu-toggle cursor-pointer d-lg-none d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-auto' : 'ml-auto') }} px-4 py-2">
                    @include('icons.close', ['class' => 'fill-current icon-close'])
                </div>
            </div>
        </div>

        <div class="sidebar-section flex-grow-1 overflow-auto sidebar">
            <div class="d-flex align-items-center">
                <div class="py-3 {{ (__('lang_dir') == 'rtl' ? 'pr-4 pl-0' : 'pl-4 pr-0') }} font-weight-medium text-muted text-uppercase flex-grow-1">{{ __('Menu') }}</div>

                @if(Auth::user()->role == 1)
                    @if (request()->segment(1) == 'admin')
                        <a class="px-4 py-2 text-decoration-none text-secondary" href="{{ route('dashboard') }}" data-toggle="tooltip" title="{{ __('User') }}" role="button"><span class="d-flex align-items-center">@include('icons.user', ['class' => 'icon-text fill-current'])</span></a>
                    @else
                        <a class="px-4 py-2 text-decoration-none text-secondary" href="{{ route('admin.dashboard') }}" data-toggle="tooltip" title="{{ __('Admin') }}" role="button"><span class="d-flex align-items-center">@include('icons.admin', ['class' => 'icon-text fill-current'])</span></a>
                    @endif
                @endif
            </div>

            @yield('menu')
        </div>

        <div class="py-3 px-4">
            <div class="row no-gutters">
                <div class="col">
                    <div class="small text-muted">
                         {{ __(':number of :total links created.', ['number' => number_format($stats['links'], 0, __('.'), __(',')), 'total' => ($userFeatures['option_links'] < 0 ? '∞' : number_format($userFeatures['option_links'], 0, __('.'), __(',')))]) }}
                    </div>
                </div>
                <div class="col-auto d-flex align-items-end small {{ (__('lang_dir') == 'rtl' ? 'pr-2' : 'pl-2') }}">
                    <a href="{{ route('pricing') }}" class="text-secondary d-flex align-items-center" data-toggle="tooltip" data-html="true" title="<div class='mx-2 font-size-base {{ (__('lang_dir') == 'rtl' ? 'text-right' : 'text-left') }}'><div class='row my-2'><div class='col'>{{ __('Links') }}</div><div class='col-auto'>{{ __(':number of :total', ['number' => number_format($stats['links'], 0, __('.'), __(',')), 'total' => ($userFeatures['option_links'] < 0 ? '∞' : number_format($userFeatures['option_links'], 0, __('.'), __(',')))]) }}</div></div><div class='row my-2'><div class='col'>{{ __('Spaces') }}</div><div class='col-auto'>{{ __(':number of :total', ['number' => number_format($stats['spaces'], 0, __('.'), __(',')), 'total' => ($userFeatures['option_spaces'] < 0 ? '∞' : number_format($userFeatures['option_spaces'], 0, __('.'), __(',')))]) }}</div></div><div class='row my-2'><div class='col'>{{ __('Domains') }}</div><div class='col-auto'>{{ __(':number of :total', ['number' => number_format($stats['domains'], 0, __('.'), __(',')), 'total' => ($userFeatures['option_domains'] < 0 ? '∞' : number_format($userFeatures['option_domains'], 0, __('.'), __(',')))]) }}</div></div></div>">@include('icons.info', ['class' => 'icon-text fill-current'])&#8203;</a>
                </div>
            </div>

            <div class="progress w-100 my-2 chart-progress">
                <div class="progress-bar rounded" role="progressbar" style="width: {{ ($userFeatures['option_links'] == 0 ? 100 : (($stats['links'] / $userFeatures['option_links']) * 100)) }}%"></div>
            </div>
        </div>

        <div class="sidebar sidebar-footer">
            <div class="py-3 {{ (__('lang_dir') == 'rtl' ? 'pr-4 pl-0' : 'pl-4 pr-0') }} d-flex align-items-center" aria-expanded="true">
                <a href="{{ route('settings') }}" class="d-flex align-items-center overflow-hidden text-secondary text-decoration-none flex-grow-1">
                    <img src="{{ gravatar(Auth::user()->email, 72) }}" class="flex-shrink-0 rounded-circle {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">

                    <div class="d-flex flex-column text-truncate">
                        <div class="font-weight-medium text-dark text-truncate">
                            {{ Auth::user()->name }}
                        </div>

                        <div class="small font-weight-medium">
                            {{ __('Settings') }}
                        </div>
                    </div>
                </a>

                <a class="py-2 px-4 d-flex flex-shrink-0 align-items-center text-secondary" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" data-toggle="tooltip" title="{{ __('Logout') }}">@include('icons.logout', ['class' => 'fill-current'])</a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</nav>
@endguest