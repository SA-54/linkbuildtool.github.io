@section('site_title', formatTitle([config('settings.title'), e(config('settings.tagline'))]))

@extends('layouts.app')

@section('content')
    <div class="flex-fill">
    <div class="bg-base-0 position-relative">
        <div class="container position-relative py-5 py-sm-6">
            <div class="row">
                <div class="col-12 py-sm-5">
                    <h1 class="display-4 font-weight-medium text-center">{{ __('Simple, powerful & recognizable links') }}</h1>
                    <p class="text-muted font-weight-normal mt-4 font-size-lg text-center">{{ __('Brand, track, and share your short links, engage with your users on a different level.') }}</p>

                    <div class="row">
                        <div class="col-2 d-none d-lg-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 37.4 37.4" style="width: 1.4rem; height: 1.4rem; transform: rotate(-17deg); {{ (__('lang_dir') == 'rtl' ? 'left' : 'right') }}: 2rem; top: 4rem; filter: blur(1px);" class="position-absolute"><path d="M26.5,3.1a7.81,7.81,0,0,1,7.8,7.8V26.5A7.64,7.64,0,0,1,32,32a7.45,7.45,0,0,1-5.5,2.3H10.9a7.81,7.81,0,0,1-7.8-7.8V10.9a7.81,7.81,0,0,1,7.8-7.8H26.5m0-3.1H10.9A10.94,10.94,0,0,0,0,10.9V26.5A10.94,10.94,0,0,0,10.9,37.4H26.5A10.94,10.94,0,0,0,37.4,26.5V10.9A10.94,10.94,0,0,0,26.5,0Z" style="fill:#e53c5f"/><path d="M28.8,10.9a2.3,2.3,0,1,1,2.3-2.3h0A2.2,2.2,0,0,1,29,10.9ZM18.7,12.5a6.2,6.2,0,1,1-6.2,6.2h0a6.17,6.17,0,0,1,6.14-6.2h.06m0-3.1a9.4,9.4,0,1,0,9.4,9.4h0A9.45,9.45,0,0,0,18.7,9.4Z" style="fill:#e53c5f"/></svg>

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 46 37.32" style="width: 1.7rem; height: 1.7rem; transform: rotate(22deg); {{ (__('lang_dir') == 'rtl' ? 'left' : 'right') }}: 6rem; top: 2rem; filter: blur(1px);" class="position-absolute"><path d="M46,4.42a16.91,16.91,0,0,1-5.4,1.5A9.86,9.86,0,0,0,44.8.72a19.29,19.29,0,0,1-6,2.3,9.4,9.4,0,0,0-16.3,6.4,16.35,16.35,0,0,0,.2,2.2A27,27,0,0,1,3.2,1.72a9.41,9.41,0,0,0,3,12.6,8.25,8.25,0,0,1-4.3-1.2v.1a9.51,9.51,0,0,0,7.6,9.3,10.55,10.55,0,0,1-2.5.3,12.09,12.09,0,0,1-1.8-.2,9.35,9.35,0,0,0,8.8,6.5A19.14,19.14,0,0,1,0,33a26.43,26.43,0,0,0,14.44,4.3c17.4,0,26.9-14.4,26.9-26.9V9.22A15.36,15.36,0,0,0,46,4.42Z" style="fill:#55acee"/></svg>

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36.4 36.4" style="width: 1.3rem; height: 1.3rem; transform: rotate(-5deg); {{ (__('lang_dir') == 'rtl' ? 'left' : 'right') }}: 4.5rem; top: 6rem; filter: blur(1px);" class="position-absolute"><path d="M12.6,0H23.8C34,0,36.4,2.4,36.4,12.6V23.8C36.4,34,34,36.4,23.8,36.4H12.6C2.4,36.4,0,34,0,23.8V12.6C0,2.4,2.4,0,12.6,0Z" style="fill:#5181b8;fill-rule:evenodd"/><path d="M29.8,12.5c.2-.6,0-1-.8-1H26.4a1.31,1.31,0,0,0-1.2.8,18.63,18.63,0,0,1-3.3,5.4c-.6.6-.9.8-1.2.8s-.4-.2-.4-.8V12.5c0-.7-.2-1-.8-1H15.3c-.4,0-.7.2-.7.6h0c0,.6,1,.8,1.1,2.6v3.9c0,.9-.2,1-.5,1-.9,0-3.1-3.3-4.4-7.1-.3-.7-.5-1-1.2-1H7c-.8,0-.9.4-.9.8,0,.7.9,4.2,4.2,8.8,2.2,3.2,5.3,4.9,8.1,4.9,1.7,0,1.9-.4,1.9-1V22.6c0-.8.2-.9.7-.9s1.1.2,2.6,1.7c1.8,1.8,2.1,2.6,3.1,2.6h2.7c.8,0,1.1-.4.9-1.1a11.65,11.65,0,0,0-2.2-3.1c-.6-.7-1.5-1.5-1.8-1.9a.91.91,0,0,1,0-1.2C26.24,18.7,29.5,14.1,29.8,12.5Z" style="fill:#fff;fill-rule:evenodd"/></svg>
                        </div>

                        @if(config('settings.short_guest'))
                            <div class="col-12 col-lg-8">
                                <div class="form-group mt-5" id="short-form-container"@if(session('link')) style="display: none;"@endif>
                                    <form action="{{ route('guest') }}" method="post" enctype="multipart/form-data" id="short-form">
                                        @csrf
                                        <div class="form-row">
                                            <div class="col-12 col-sm">
                                                <input type="text" dir="ltr" autocomplete="off" autocapitalize="none" spellcheck="false" name="url" class="form-control form-control-lg font-size-lg{{ $errors->has('url') ? ' is-invalid' : '' }}" placeholder="{{ __('Shorten your link') }}" autofocus>
                                                @if ($errors->has('url'))
                                                    <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $errors->first('url') }}</strong>
                                                </span>
                                                @endif

                                                @if ($errors->has('g-recaptcha-response'))
                                                    <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                            <div class="col-12 col-sm-auto">
                                                @if(config('settings.captcha_shorten'))
                                                    {!! NoCaptcha::displaySubmit('short-form', __('Shorten'), ['data-theme' => (Cookie::get('dark_mode') == 1 ? 'dark' : 'light'), 'data-size' => 'invisible', 'class' => 'btn btn-primary btn-lg btn-block font-size-lg mt-3 mt-sm-0']) !!}

                                                    {!! NoCaptcha::renderJs(__('lang_code')) !!}
                                                @else
                                                    <button class="btn btn-primary btn-lg btn-block font-size-lg mt-3 mt-sm-0" type="submit">{{ __('Shorten') }}</button>
                                                @endif
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                @include('home.link')
                            </div>
                        @else
                            <div class="col-12 col-lg-8 d-flex justify-content-center">
                                <div class="form-group mt-5">
                                    @if(config('settings.registration_registration'))
                                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg font-size-lg{{ (__('lang_dir') == 'rtl' ? ' ml-3' : ' mr-3') }}">{{ __('Get started for free') }}</a>
                                    @endif
                                    <a href="#features" class="btn btn-outline-primary btn-lg font-size-lg" data-scroll-to="72">{{ __('Learn more') }}</a>
                                </div>
                            </div>
                        @endif

                        <div class="col-2 d-none d-lg-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 37.4 37.4" style="width: 1.4rem; height: 1.4rem; transform: rotate(7deg); {{ (__('lang_dir') == 'rtl' ? 'right' : 'left') }}: 2rem; top: 4rem; filter: blur(1px);" class="position-absolute"><path d="M37.4,18.67a18.7,18.7,0,1,0-21.6,18.5V24.07H11v-5.4h4.8v-4.1c0-4.7,2.8-7.3,7.1-7.3a20.41,20.41,0,0,1,4.2.4v4.6H24.74a2.71,2.71,0,0,0-3,2.3v4.1h5.2l-.8,5.4h-4.4v13.1A18.7,18.7,0,0,0,37.4,18.67Z" style="fill:#1977f3"/><path d="M26,24.07l.8-5.4H21.6v-3.5a2.62,2.62,0,0,1,2.32-2.89H27V7.67c-1.4-.2-2.8-.3-4.2-.4-4.3,0-7.1,2.6-7.1,7.3v4.1H10.9v5.4h4.84v13.1a19.45,19.45,0,0,0,5.9,0V24.07Z" style="fill:#fefefe"/></svg>

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 31.23 22" style="width: 1.65rem; height: 1.65rem; transform: rotate(22deg); {{ (__('lang_dir') == 'rtl' ? 'right' : 'left') }}: 6rem; top: 2.5rem; filter: blur(1px);" class="position-absolute"><path d="M30.57,3.44A3.9,3.9,0,0,0,27.81.66C25.38,0,15.61,0,15.61,0S5.85,0,3.41.66A3.92,3.92,0,0,0,.65,3.44,41.27,41.27,0,0,0,0,11a41.27,41.27,0,0,0,.65,7.56,3.92,3.92,0,0,0,2.76,2.78c2.44.66,12.2.66,12.2.66s9.77,0,12.2-.66a3.9,3.9,0,0,0,2.76-2.78A40.66,40.66,0,0,0,31.23,11,40.66,40.66,0,0,0,30.57,3.44Z" style="fill:#e70000"/><polygon points="12.42 15.64 12.42 6.36 20.58 11 12.42 15.64" style="fill:#fefefe"/></svg>

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36" style="width: 1.3rem; height: 1.3rem; transform: rotate(-20deg); {{ (__('lang_dir') == 'rtl' ? 'right' : 'left') }}: 5rem; top: 6rem; filter: blur(1px);" class="position-absolute"><circle cx="18" cy="18" r="18" style="fill:#ff4500"/><path d="M30,18a2.63,2.63,0,0,0-2.72-2.53,2.58,2.58,0,0,0-1.72.73A12.83,12.83,0,0,0,18.63,14l1.16-5.61,3.86.81a1.8,1.8,0,0,0,3.58-.39,1.8,1.8,0,0,0-3.35-.71l-4.41-.89a.56.56,0,0,0-.66.43h0l-1.33,6.24a12.83,12.83,0,0,0-7,2.22,2.63,2.63,0,1,0-3.6,3.83,2.31,2.31,0,0,0,.71.47,5.34,5.34,0,0,0,0,.8c0,4,4.69,7.31,10.49,7.31s10.49-3.28,10.49-7.31a5.34,5.34,0,0,0,0-.8A2.62,2.62,0,0,0,30,18ZM12,19.8a1.81,1.81,0,1,1,1.81,1.81A1.81,1.81,0,0,1,12,19.8Zm10.46,4.95A6.9,6.9,0,0,1,18,26.14a6.89,6.89,0,0,1-4.44-1.39.48.48,0,0,1,.68-.68A5.9,5.9,0,0,0,18,25.2a5.9,5.9,0,0,0,3.76-1.1.5.5,0,0,1,.7.72h0v-.07Zm-.32-3.08a1.27,1.27,0,1,1,.07.07h-.08Z" style="fill:#fff"/></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-base-1" id="features">
        <div class="container py-5 py-md-7">
            <div class="text-center">
                <h2 class="mb-3 d-inline-block">{{ __('Features') }}</h2>
                <div class="m-auto">
                    <p class="text-muted font-weight-normal font-size-lg">{{ __('Measure traffic, know your audience, stay in control of your links.') }}</p>
                </div>
            </div>

            <div class="row mx-lg-n4">
                @php
                    $features = [
                        [
                            'icon' => 'stats',
                            'title' => __('Statistics'),
                            'description' => __('Get to know your audience, analyze the performance of your links.')
                        ],
                        [
                            'icon' => 'geolocation',
                            'title' => __('Geotargeting'),
                            'description' => __('Drive your traffic based on the geographical location of your audience.')
                        ],
                        [
                            'icon' => 'devices',
                            'title' => __('Platform targeting'),
                            'description' => __('Redirect your users based on the devices they\'re using.')
                        ],
                        [
                            'icon' => 'campaign',
                            'title' => __('Campaigns'),
                            'description' => __('Run time-limited marketing campaigns with our expiration date feature.')
                        ],
                        [
                            'icon' => 'privacy',
                            'title' => __('Privacy'),
                            'description' => __('Secure your links from unwanted visitors with our password option.')
                        ],
                        [
                            'icon' => 'account',
                            'title' => __('Personal'),
                            'description' => __('Personalize and brand your links with custom domains and aliases.')
                        ],
                    ];
                @endphp

                @foreach($features as $feature)
                    <div class="col-12 col-sm-6 col-md-4 pt-3 pr-md-3 pl-md-3 pt-lg-4 pr-lg-4 pl-lg-4 mt-4 feature">
                        <div class="d-flex flex-column">
                            <div class="icon-gradient-{{ $loop->index+1 }}">@include('icons.background.'.$feature['icon'], ['class' => 'fill-current mb-3 icon-features'])</div>
                            <div class="d-block w-100"><h5 class="mt-1 mb-3 d-inline-block">{{ $feature['title'] }}</h5></div>
                            <div class="d-block w-100 text-muted">{{ $feature['description'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @php
        $features2 = [
            [
                'icon' => 'domain',
                'title' => __('Domains'),
                'color' => 'primary',
                'description' => __('Brand your links with your own domains and increase your click-through rate with up to 35% more.')
            ],
            [
                'icon' => 'alias',
                'title' => __('Aliases'),
                'color' => 'primary',
                'description' => __('There\'s no need for hard to remember links, personalize your links with easy to remember custom aliases.')
            ]
        ];
    @endphp

    <div class="bg-base-0">
        <div class="container py-5 py-md-7 position-relative z-1">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <h2 class="mb-3">{{ __('Empower your links') }}</h2>
                    <div class="m-auto">
                        <p class="text-muted font-weight-normal font-size-lg">{{ __('Users are aware of the links they\'re clicking, branded links will increase your brand recognition, inspire trust and increase your click-through rate.') }}</p>
                    </div>

                    <div class="row mx-lg-n4">
                        @foreach($features2 as $feature)
                            <div class="col-12 pt-3 pr-md-3 pl-md-3 pt-lg-4 pr-lg-4 pl-lg-4 mt-4 feature">
                                <div class="d-flex flex-row">
                                    <div class="text-{{ $feature['color'] }} {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">@include('icons.background.'.$feature['icon'], ['class' => 'fill-current icon-features'])</div>
                                    <div class="{{ (__('lang_dir') == 'rtl' ? 'mr-1' : 'ml-1') }}">
                                        <div class="d-block w-100"><h5 class="mt-0 mb-1 d-inline-block">{{ $feature['title'] }}</h5></div>
                                        <div class="d-block w-100 text-muted">{{ $feature['description'] }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-6 d-none d-lg-block position-relative">
                    <svg class="position-absolute text-success" style="width: 9rem; height: 6rem; bottom: 0.5rem; {{ (__('lang_dir') == 'rtl' ? 'right: 1.2rem;' : 'left: 1.6rem;') }}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 170 120"><defs><pattern id="dots2" width="20" height="20" patternUnits="userSpaceOnUse" fill="currentColor" viewBox="0 0 20 20"><rect width="20" height="20" style="fill:none"></rect><circle cx="5" cy="5" r="3"></circle></pattern></defs><rect width="170" height="120" style="opacity:0.3;fill:url(#dots2)"></rect></svg>

                    <svg class="w-100 h-100 position-relative" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 976.72 738.48"><path d="M520.86,17.08c-173.69,0-239.71,4-413.59,81.76S93.84,373.1,81.36,543s158.34,191.92,404,191,316.67,49,369.44,0,159.54-214.26,130.75-353.41S954.6,270.61,951.72,155.84,520.86,17.08,520.86,17.08Z" transform="translate(-15.99 -17)" style="fill:#a6a0ff;opacity:0.05"/><path d="M497.71,17c-173.35,10.88-239,19-407.66,107.5s3.77,274.56,2,444.86S262.06,751,507.17,734.65s319.12,29.07,368.73-23.11,145.8-223.84,108.35-360.9-37.77-107.77-47.83-222.13S497.71,17,497.71,17Z" transform="translate(-15.99 -17)" style="fill:#a6a0ff;opacity:0.1"/><g style="opacity:0.8"><path d="M925.9,95.2a10,10,0,0,0-10-10H110.6a10,10,0,0,0-10,10V662.3a10,10,0,0,0,10,10H915.9a10,10,0,0,0,10-10V95.2Z" transform="translate(-15.99 -17)" style="fill:#fff"/></g><circle cx="106.71" cy="80.9" r="6.8" style="fill:#6c63ff"/><circle cx="128.81" cy="80.9" r="6.8" style="fill:#6c63ff"/><circle cx="150.91" cy="80.9" r="6.8" style="fill:#6c63ff"/><g style="opacity:0.75"><path d="M100.6,484.9a11.77,11.77,0,0,1,9.9-11.3L916,367a8.54,8.54,0,0,1,9.9,8.7V662.2a10,10,0,0,1-10,10H110.6a10,10,0,0,1-10-10Z" transform="translate(-15.99 -17)" style="fill:#a6a0ff"/></g><rect x="128.61" y="243.2" width="317.5" height="21" style="fill:#3f3d56"/><rect x="128.61" y="284.2" width="317.5" height="21" style="fill:#3f3d56"/><rect x="128.31" y="325.2" width="158.7" height="21" style="fill:#3f3d56"/><rect x="84.61" y="94.4" width="825.3" height="71.5" style="fill:#3f3d56"/><path d="M146.44,167.06v-3.47a8.77,8.77,0,0,0,1.27.07,2.9,2.9,0,0,0,3.23-2.41c0-.05.27-1,.25-1l-6.63-18.65h4.78l4.4,14.9h.07l4.39-14.9h4.61l-6.73,19.37c-1.6,4.62-3.66,6.19-7.91,6.19C147.92,167.13,146.67,167.11,146.44,167.06Z" transform="translate(-15.99 -17)" style="fill:#fff"/><path d="M163.58,150.88c0-6,3.59-9.7,9-9.7s9,3.67,9,9.7-3.55,9.69-9,9.69S163.58,156.93,163.58,150.88Zm13.59,0c0-3.95-1.79-6.22-4.57-6.22s-4.57,2.27-4.57,6.22,1.79,6.23,4.57,6.23S177.17,154.86,177.17,150.88Z" transform="translate(-15.99 -17)" style="fill:#fff"/><path d="M201,160.2h-4.21V157h-.09a5.74,5.74,0,0,1-5.7,3.56c-4,0-6.61-2.61-6.61-6.92v-12h4.38v11.16c0,2.67,1.3,4.11,3.76,4.11s4.1-1.79,4.1-4.5V141.57H201Z" transform="translate(-15.99 -17)" style="fill:#fff"/><path d="M204.52,141.57h4.2v3.25h.09a4.48,4.48,0,0,1,4.47-3.57,6.24,6.24,0,0,1,1.37.18v3.95a5.63,5.63,0,0,0-1.78-.24c-2.48,0-4,1.6-4,4.21V160.2h-4.38Z" transform="translate(-15.99 -17)" style="fill:#fff"/><path d="M215.56,157.86a2.53,2.53,0,1,1,2.53,2.53A2.51,2.51,0,0,1,215.56,157.86Z" transform="translate(-15.99 -17)" style="fill:#fff"/><path d="M224.84,134.83h4.38V160.2h-4.38Z" transform="translate(-15.99 -17)" style="fill:#fff"/><path d="M232.81,136.75a2.43,2.43,0,1,1,2.42,2.37A2.39,2.39,0,0,1,232.81,136.75Zm.24,4.82h4.38V160.2h-4.38Z" transform="translate(-15.99 -17)" style="fill:#fff"/><path d="M241.1,141.57h4.2v3.2h.09a6,6,0,0,1,5.86-3.55c4.11,0,6.45,2.63,6.45,6.94v12h-4.38V149c0-2.64-1.23-4.12-3.73-4.12s-4.11,1.82-4.11,4.49V160.2H241.1Z" transform="translate(-15.99 -17)" style="fill:#fff"/><path d="M272.76,141.57h5.05l-7.46,8,7.77,10.62h-5.06l-5.89-8-1.48,1.49v6.47h-4.37V134.83h4.37v14.51h.06Z" transform="translate(-15.99 -17)" style="fill:#fff"/><path d="M278.17,165.63l9-30.8h4l-9,30.8Z" transform="translate(-15.99 -17)" style="fill:#fff"/><path d="M292.2,154.87c0-3.3,2.55-5.29,7.09-5.57l4.92-.28v-1.34c0-2-1.32-3.07-3.48-3.07s-3.4,1-3.68,2.55H293c.2-3.48,3.17-6,7.91-6s7.63,2.44,7.63,6.19V160.2h-4.22v-3h-.08a6.6,6.6,0,0,1-5.81,3.27C294.82,160.5,292.2,158.23,292.2,154.87Zm12-1.58v-1.42l-4.31.26c-2.11.14-3.32,1.09-3.32,2.57s1.26,2.49,3.16,2.49C302.22,157.19,304.21,155.54,304.21,153.29Z" transform="translate(-15.99 -17)" style="fill:#fff"/><path d="M312.17,134.83h4.38V160.2h-4.38Z" transform="translate(-15.99 -17)" style="fill:#fff"/><path d="M320.13,136.75a2.44,2.44,0,1,1,2.43,2.37A2.4,2.4,0,0,1,320.13,136.75Zm.25,4.82h4.38V160.2h-4.38Z" transform="translate(-15.99 -17)" style="fill:#fff"/><path d="M327.69,154.87c0-3.3,2.55-5.29,7.09-5.57l4.92-.28v-1.34c0-2-1.32-3.07-3.48-3.07s-3.39,1-3.68,2.55h-4.06c.2-3.48,3.17-6,7.91-6s7.63,2.44,7.63,6.19V160.2H339.8v-3h-.08a6.6,6.6,0,0,1-5.8,3.27C330.31,160.5,327.69,158.23,327.69,154.87Zm12-1.58v-1.42l-4.31.26c-2.11.14-3.32,1.09-3.32,2.57s1.26,2.49,3.16,2.49C337.71,157.19,339.7,155.54,339.7,153.29Z" transform="translate(-15.99 -17)" style="fill:#fff"/><path d="M354.75,141.18c4.37,0,7.34,2.36,7.45,5.8h-4.08c-.14-1.6-1.42-2.58-3.46-2.58s-3.2.91-3.2,2.27c0,1.05.84,1.75,2.67,2.19l3.32.74c3.73.88,5.19,2.32,5.19,5.08,0,3.52-3.25,5.89-8,5.89s-7.64-2.32-7.94-5.85H351c.26,1.7,1.58,2.65,3.79,2.65s3.45-.86,3.45-2.25c0-1.09-.69-1.67-2.5-2.13l-3.39-.79c-3.45-.81-5.19-2.6-5.19-5.29C347.2,143.5,350.21,141.18,354.75,141.18Z" transform="translate(-15.99 -17)" style="fill:#fff"/><path d="M327.5,623.3H155.3a10,10,0,0,1-10-10V520.6a10,10,0,0,1,10-10H327.5a10,10,0,0,1,10,10v92.7A10,10,0,0,1,327.5,623.3Z" transform="translate(-15.99 -17)" style="fill:#3f3d56"/><path d="M597.5,623.3H425.3a10,10,0,0,1-10-10V520.6a10,10,0,0,1,10-10H597.5a10,10,0,0,1,10,10v92.7A10,10,0,0,1,597.5,623.3Z" transform="translate(-15.99 -17)" style="fill:#3f3d56"/><path d="M867.5,623.3H695.3a10,10,0,0,1-10-10V520.6a10,10,0,0,1,10-10H867.5a10,10,0,0,1,10,10v92.7A10,10,0,0,1,867.5,623.3Z" transform="translate(-15.99 -17)" style="fill:#3f3d56"/></svg>
                </div>
            </div>
        </div>
    </div>

    @if(config('settings.stripe'))
    <div class="bg-base-1">
        <div class="container py-5 py-md-7 position-relative z-1">
            <div class="text-center">
                <h2 class="mb-3 d-inline-block">{{ __('Plans') }}</h2>
                <div class="m-auto">
                    <p class="text-muted font-weight-normal font-size-lg">{{ __('Simple pricing plans for everyone and every budget.') }}</p>
                </div>
            </div>

            <div class="row mx-lg-n4 justify-content-center position-relative">

                <svg class="position-absolute d-none d-md-block text-danger" style="width: 5.5rem; height: 5.5rem; top: 1.5rem; {{ (__('lang_dir') == 'rtl' ? 'right: 0;' : 'left: 0;') }}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 110 110"><defs><pattern id="dots3" width="20" height="20" patternUnits="userSpaceOnUse" fill="currentColor" viewBox="0 0 20 20"><rect width="20" height="20" style="fill:none"/><circle cx="5" cy="5" r="3"/></pattern></defs><rect width="110" height="110" style="opacity:0.3;fill:url(#dots3)"/></svg>

                @foreach($plans as $plan)
                    <div class="col-12 col-md-4 pt-md-3 pr-md-3 pl-md-3 pt-lg-4 pr-lg-4 pl-lg-4 mt-4 @if($plan->plan_month && $plan->plan_year) order-{{ $loop->remaining }} order-md-{{ $loop->iteration }} @else order-{{ $loop->remaining }} order-md-{{ $loop->iteration }} @endif">
                        <div class="card border-0 shadow-sm rounded h-100 overflow-hidden plan">
                            <div class="card-body p-4 d-flex flex-column">
                                <h5 class="mt-1 mb-3 text-muted text-uppercase d-inline-block">{{ $plan->name }}</h5>

                                <div class="plan-title-underline" style="background-color: {{ $plan->color }};"></div>

                                <div class="mt-4">
                                    {{ __($plan->description) }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center">
                <a href="{{ route('pricing') }}" class="btn btn-primary py-2 mt-5">{{ __('View pricing') }}</a>
            </div>
        </div>
    </div>

    <div class="bg-base-0">
        <div class="container position-relative text-center py-5 py-md-7 d-flex flex-column z-1">
            <div class="flex-grow-1">
                <div class="badge badge-pill badge-success mb-3 px-3 py-2">{{ __('Join us') }}</div>
                <div class="text-center">
                    <h4 class="mb-3">{{ __('Ready to get started?') }}</h4>
                    <div class="m-auto">
                        <p class="mb-5 font-weight-normal text-muted font-size-lg">{{ __('Reach your customers more efficiently by starting your marketing campaign with us.') }}</p>
                    </div>
                </div>
            </div>

            <div><a href="{{ route('register') }}" class="btn btn-primary py-2">{{ __('Get started') }}</a></div>
        </div>
    </div>
    @endif

    <div class="bg-base-1">
        <div class="container pt-6">
            @php
                $counters = [
                    [
                        'value' => number_format($stats['links'], 0, __('.'), __(',')),
                        'title' => __('Created links'),
                        'id' => 'links_created'
                    ],
                    [
                        'value' => number_format($stats['redirects'], 0, __('.'), __(',')),
                        'title' => __('Redirected links'),
                        'id' => 'links_redirected'
                    ],
                    [
                        'value' => number_format($stats['users'], 0, __('.'), __(',')),
                        'title' => __('Registered users'),
                        'id' => 'users_registered'
                    ],
                ];
            @endphp

            <div class="row">
                @foreach($counters as $count)
                    <div class="col-12 col-md-4 mb-6 text-md-center">
                        <div class="display-4 font-weight-normal mb-1" id="{{ $count['id'] }}">{{ $count['value'] }}</div>
                        <div class="text-muted">{{ $count['title'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection