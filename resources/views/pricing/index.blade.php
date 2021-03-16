@extends('layouts.app')

@section('site_title', formatTitle([__('Pricing'), config('settings.title')]))

@section('content')
<div class="flex-fill">
    <div class="bg-base-1">
        <div class="container py-6">
            @include('shared.message')

            <div class="text-center">
                <h2 class="mb-3 d-inline-block">{{ __('Pricing') }}</h2>
                <div class="m-auto">
                    <p class="text-muted font-weight-normal font-size-lg">{{ __('Simple pricing plans for everyone and every budget.') }}</p>
                </div>

                <div class="mb-3 mt-5">
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-outline-dark active" id="plan-monthly">
                            <input type="radio" name="options" autocomplete="off" checked>{{ __('Monthly') }}
                        </label>
                        <label class="btn btn-outline-dark" id="plan-yearly">
                            <input type="radio" name="options" autocomplete="off">{{ __('Yearly') }}
                        </label>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                @foreach($plans as $plan)
                    <div class="col-12 col-md-4 pt-4 @if($plan->plan_month && $plan->plan_year) order-{{ $loop->remaining }} order-md-{{ $loop->iteration }} @else order-{{ $loop->remaining }} order-md-{{ $loop->iteration }} @endif">
                        <div class="card border-0 shadow-sm rounded h-100 overflow-hidden plan">
                            <div class="card-body p-4 d-flex flex-column">
                                <h5 class="mt-1 mb-3 text-muted text-uppercase d-inline-block">{{ $plan->name }}</h5>
                                <div class="plan-title-underline" style="background-color: {{ $plan->color }};"></div>
                                <div class="my-4">
                                    @if($plan->plan_month && $plan->plan_year)
                                        <div class="plan-preload plan-monthly d-none d-block">
                                            <div class="h1 mb-1">
                                            <span class="font-weight-bold">
                                                {{ formatMoney($plan->amount_month, $plan->currency) }}
                                            </span>
                                                <span class="pricing-plan-price text-muted">
                                                {{ $plan->currency }}
                                            </span>
                                            </div>
                                            <span class="text-muted text-lowercase">{{ __('Month') }}</span>
                                        </div>

                                        <div class="plan-yearly d-none">
                                            <div class="h1 mb-1">
                                            <span class="font-weight-bold">
                                                {{ formatMoney($plan->amount_year, $plan->currency) }}
                                            </span>
                                                <span class="pricing-plan-price text-muted">
                                                {{ $plan->currency }}
                                            </span>
                                            </div>

                                            <span class="text-muted text-lowercase">{{ __('Year') }}</span>

                                            @if(($plan->amount_month*12) > $plan->amount_year)
                                                <span class="badge badge-success">
                                                {{ __(':value% off', ['value' => number_format(((($plan->amount_month*12) - $plan->amount_year)/($plan->amount_month*12) * 100), 0)]) }}
                                            </span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="plan-preload plan-monthly d-none d-block">
                                            <h1 class="mb-1">
                                                <span class="font-weight-bold text-uppercase">
                                                    {{ __('Free') }}
                                                </span>
                                            </h1>
                                        </div>

                                        <div class="plan-yearly d-none">
                                            <h1 class="mb-1">
                                                <span class="font-weight-bold text-uppercase">
                                                    {{ __('Free') }}
                                                </span>
                                            </h1>
                                        </div>

                                        <div class="plan-monthly d-none d-block">
                                            <span class="text-muted text-lowercase">{{ __('Month') }}</span>
                                        </div>

                                        <div class="plan-yearly d-none">
                                            <span class="text-muted text-lowercase">{{ __('Year') }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="plan-features mb-4">
                                    <div class="row py-2">
                                        <div class="col{{ ($plan->option_links == 0 ? ' text-black-50' : '') }}">
                                            {{ __('Links') }}
                                        </div>
                                        <div class="col-auto d-flex align-items-center font-weight-medium">
                                            @if($plan->option_links < 0)
                                                {{ __('Unlimited') }}
                                            @elseif($plan->option_links > 0)
                                                {{ number_format($plan->option_links, 0, __('.'), __(',')) }}
                                            @else
                                                @include('icons/close', ['class' => 'text-black-50 pt-1'])
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row py-2">
                                        <div class="col{{ ($plan->option_spaces == 0 ? ' text-black-50' : '') }}">
                                            {{ __('Spaces') }}
                                        </div>
                                        <div class="col-auto d-flex align-items-center font-weight-medium">
                                            @if($plan->option_spaces < 0)
                                                {{ __('Unlimited') }}
                                            @elseif($plan->option_spaces > 0)
                                                {{ number_format($plan->option_spaces, 0, __('.'), __(',')) }}
                                            @else
                                                @include('icons/close', ['class' => 'text-black-50 pt-1'])
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row py-2">
                                        <div class="col d-flex align-items-center{{ ($plan->option_domains == 0 ? ' text-black-50' : '') }}">
                                            {{ __('Domains') }}

                                            <div class="d-flex align-content-center {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}" data-toggle="tooltip" title="{{ __('Connect your own custom domains to our service.') }}">@include('icons.info', ['class' => 'text-muted icon-text fill-current'])</div>
                                        </div>
                                        <div class="col-auto d-flex align-items-center font-weight-medium">
                                            @if($plan->option_domains < 0)
                                                {{ __('Unlimited') }}
                                            @elseif($plan->option_domains > 0)
                                                {{ number_format($plan->option_domains, 0, __('.'), __(',')) }}
                                            @else
                                                @include('icons/close', ['class' => 'text-black-50 pt-1'])
                                            @endif
                                        </div>
                                    </div>

                                    @if(count($domains) > 0)
                                        <div class="row py-2">
                                            <div class="col d-flex align-items-center{{ (!$plan->option_global_domains ? ' text-black-50' : '') }}">
                                                {{ __('Additional domains') }}

                                                <div class="d-flex align-content-center {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}" data-toggle="tooltip" title="{{ __('Get free access to additional domains: :domains.', ['domains' => implode(', ', $domains)]) }}">@include('icons.info', ['class' => 'text-muted icon-text fill-current'])</div>
                                            </div>

                                            <div class="col-auto d-flex align-items-center">
                                                @if($plan->option_global_domains)
                                                    @include('icons/checkmark', ['class' => 'text-success'])
                                                @else
                                                    @include('icons/close', ['class' => 'text-black-50 pt-1'])
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row py-2">
                                        <div class="col{{ (!$plan->option_stats ? ' text-black-50' : '') }}">
                                            {{ __('Advanced stats') }}
                                        </div>

                                        <div class="col-auto d-flex align-items-center">
                                            @if($plan->option_stats)
                                                @include('icons/checkmark', ['class' => 'text-success'])
                                            @else
                                                @include('icons/close', ['class' => 'text-black-50 pt-1'])
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row py-2">
                                        <div class="col{{ (!$plan->option_geo ? ' text-black-50' : '') }}">
                                            {{ __('Geotargeting') }}
                                        </div>

                                        <div class="col-auto d-flex align-items-center">
                                            @if($plan->option_geo)
                                                @include('icons/checkmark', ['class' => 'text-success'])
                                            @else
                                                @include('icons/close', ['class' => 'text-black-50 pt-1'])
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row py-2">
                                        <div class="col{{ (!$plan->option_platform ? ' text-black-50' : '') }}">
                                            {{ __('Platform targeting') }}
                                        </div>

                                        <div class="col-auto d-flex align-items-center">
                                            @if($plan->option_platform)
                                                @include('icons/checkmark', ['class' => 'text-success'])
                                            @else
                                                @include('icons/close', ['class' => 'text-black-50 pt-1'])
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row py-2">
                                        <div class="col d-flex align-items-center{{ ($plan->option_deep_links == 0 ? ' text-black-50' : '') }}">
                                            {{ __('Deep links') }}

                                            <div class="d-flex align-content-center {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}" data-toggle="tooltip" title="{{ __('Redirect links to pages in your app, and track their performance.') }}">@include('icons.info', ['class' => 'text-muted icon-text fill-current'])</div>
                                        </div>

                                        <div class="col-auto d-flex align-items-center">
                                            @if($plan->option_deep_links)
                                                @include('icons/checkmark', ['class' => 'text-success'])
                                            @else
                                                @include('icons/close', ['class' => 'text-black-50 pt-1'])
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row py-2">
                                        <div class="col d-flex align-items-center{{ (!$plan->option_link_rotation ? ' text-black-50' : '') }}">
                                            {{ __('Link rotation') }}

                                            <div class="d-flex align-content-center {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}" data-toggle="tooltip" title="{{ __('Evenly distribute traffic among links, useful for A/B testing.') }}">@include('icons.info', ['class' => 'text-muted icon-text fill-current'])</div>
                                        </div>

                                        <div class="col-auto d-flex align-items-center">
                                            @if($plan->option_link_rotation)
                                                @include('icons/checkmark', ['class' => 'text-success'])
                                            @else
                                                @include('icons/close', ['class' => 'text-black-50 pt-1'])
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row py-2">
                                        <div class="col{{ (!$plan->option_password ? ' text-black-50' : '') }}">
                                            {{ __('Link password') }}
                                        </div>

                                        <div class="col-auto d-flex align-items-center">
                                            @if($plan->option_password)
                                                @include('icons/checkmark', ['class' => 'text-success'])
                                            @else
                                                @include('icons/close', ['class' => 'text-black-50 pt-1'])
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row py-2">
                                        <div class="col d-flex align-items-center{{ ($plan->option_domains == 0 ? ' text-black-50' : '') }}">
                                            {{ __('Link expiration') }}

                                            <div class="d-flex align-content-center {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}" data-toggle="tooltip" title="{{ __('Set your links to expire after a number of clicks, or at a specific date and time.') }}">@include('icons.info', ['class' => 'text-muted icon-text fill-current'])</div>
                                        </div>

                                        <div class="col-auto d-flex align-items-center">
                                            @if($plan->option_expiration)
                                                @include('icons/checkmark', ['class' => 'text-success'])
                                            @else
                                                @include('icons/close', ['class' => 'text-black-50 pt-1'])
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row py-2">
                                        <div class="col{{ (!$plan->option_disabled ? ' text-black-50' : '') }}">
                                            {{ __('Link deactivation') }}
                                        </div>

                                        <div class="col-auto d-flex align-items-center">
                                            @if($plan->option_disabled)
                                                @include('icons/checkmark', ['class' => 'text-success'])
                                            @else
                                                @include('icons/close', ['class' => 'text-black-50 pt-1'])
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row py-2">
                                        <div class="col{{ (!$plan->option_utm ? ' text-black-50' : '') }}">
                                            {{ __('UTM Builder') }}
                                        </div>

                                        <div class="col-auto d-flex align-items-center">
                                            @if($plan->option_utm)
                                                @include('icons/checkmark', ['class' => 'text-success'])
                                            @else
                                                @include('icons/close', ['class' => 'text-black-50 pt-1'])
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row py-2">
                                        <div class="col{{ (!$plan->option_api ? ' text-black-50' : '') }}">
                                            {{ __('API access') }}
                                        </div>

                                        <div class="col-auto d-flex align-items-center">
                                            @if($plan->option_api)
                                                @include('icons/checkmark', ['class' => 'text-success'])
                                            @else
                                                @include('icons/close', ['class' => 'text-black-50 pt-1'])
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="plan-footer d-flex align-items-end mt-auto">
                                    @foreach([0 => 1, 1 => 0.65, 2 => 0.3] as $p => $o)
                                        <svg style="bottom: {{ $p }}rem; left: 0; opacity: {{ $o }}; color: {{ $plan->color }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 422.7" preserveAspectRatio="none" class="plan-footer w-100 position-absolute z-0"><path fill="currentColor" d="M0,19.55,79.26,37.21C158.51,54.22,317,68,475.54,67.49c158.51.5,317-35.83,475.54-52.84C1109.6-3,1268.11-3,1426.62,5.74c158.52,9.41,317,25.92,475.54,44.08s317,34.68,396.29,44.09l79.25,8.75v317H0Z"/></svg>
                                    @endforeach

                                    <div class="z-1 w-100">
                                        @auth
                                            @if($plan->plan_month && $plan->plan_year)
                                                @if($user->subscribed($plan->name))
                                                    <div class="btn btn-light btn-block text-uppercase py-2 disabled">{{ __('Active') }}</div>
                                                @else
                                                    <div class="plan-no-animation plan-monthly d-none d-block">
                                                        <a href="{{ route('checkout.index', ['id' => $plan->id, 'period' => 'monthly']) }}" class="btn btn-light btn-block text-uppercase py-2">
                                                            @if($plan->trial_days > 0)
                                                                {{ __('Free trial') }}
                                                            @else
                                                                @if($user->hasIncompletePayment($plan->name))
                                                                    {{ __('Confirm payment') }}
                                                                @else
                                                                    {{ __('Subscribe') }}
                                                                @endif
                                                            @endif
                                                        </a>
                                                    </div>
                                                    <div class="plan-no-animation plan-yearly d-none">
                                                        <a href="{{ route('checkout.index', ['id' => $plan->id, 'period' => 'yearly']) }}" class="btn btn-light btn-block text-uppercase py-2">
                                                            @if($plan->trial_days > 0)
                                                                {{ __('Free trial') }}
                                                            @else
                                                                @if($user->hasIncompletePayment($plan->name))
                                                                    {{ __('Confirm payment') }}
                                                                @else
                                                                    {{ __('Subscribe') }}
                                                                @endif
                                                            @endif
                                                        </a>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="btn btn-light btn-block text-uppercase py-2 disabled">{{ __('Free') }}</div>
                                            @endif
                                        @else
                                            @if(config('settings.registration_registration'))
                                                <div class="plan-no-animation plan-monthly d-none d-block">
                                                    <a href="{{ route('register', ['plan' => $plan->id, 'period' => 'monthly']) }}" class="btn btn-light btn-block text-uppercase py-2 stretched-link">{{ __('Register') }}</a>
                                                </div>
                                                <div class="plan-no-animation plan-yearly d-none">
                                                    <a href="{{ route('register', ['plan' => $plan->id, 'period' => 'yearly']) }}" class="btn btn-light btn-block text-uppercase py-2 stretched-link">{{ __('Register') }}</a>
                                                </div>
                                            @else
                                                <div class="plan-no-animation plan-monthly d-none d-block">
                                                    <a href="{{ route('login', ['plan' => $plan->id, 'period' => 'monthly']) }}" class="btn btn-light btn-block text-uppercase py-2 stretched-link">{{ __('Login') }}</a>
                                                </div>
                                                <div class="plan-no-animation plan-yearly d-none">
                                                    <a href="{{ route('login', ['plan' => $plan->id, 'period' => 'yearly']) }}" class="btn btn-light btn-block text-uppercase py-2 stretched-link">{{ __('Login') }}</a>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="bg-base-0">
        <div class="container py-6">
            <div class="text-center">
                <h2 class="d-inline-block">{{ __('Frequently asked questions') }}</h2>
            </div>

            <div class="row">
                <div class="col-12 col-md-6 mt-5 h-100">
                    <div class="h5 font-weight-medium">{{ __('What forms of payment do you accept?') }}</div>
                    <div class="text-muted">{{ __('We support all the major credit cards such as Visa, Mastercard, Maestro, American Express, etc.') }}</div>
                </div>

                <div class="col-12 col-md-6 mt-5 h-100">
                    <div class="h5 font-weight-medium">{{ __('Can I change plans?') }}</div>
                    <div class="text-muted">{{ __('Yes, you can change your plan at any time.') }} {{ __('Upon switching plans, your current subscription will be cancelled immediately.') }}</div>
                </div>

                <div class="col-12 col-md-6 mt-5 h-100">
                    <div class="h5 font-weight-medium">{{ __('Can I cancel my subscription?') }}</div>
                    <div class="text-muted">{{ __('Yes, you can cancel your subscription at any time.') }} {{ __('You\'ll continue to have access to the features you\'ve paid for until the end of your billing cycle.') }}</div>
                </div>

                <div class="col-12 col-md-6 mt-5 h-100">
                    <div class="h5 font-weight-medium">{{ __('What happens when my subscription expires?') }}</div>
                    <div class="text-muted">{{ __('Once your subscription expires, you\'ll lose access to all the subscription features.') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-base-1">
        <div class="container py-6 text-center">
            <div><h2 class="mb-5 d-inline-block">{{ __('Still have questions?') }}</h2></div>

            <a href="{{ route('contact') }}" class="btn btn-primary">{{ __('Contact us') }}</a>
        </div>
    </div>
</div>
@include('shared.sidebars.user')
@endsection
