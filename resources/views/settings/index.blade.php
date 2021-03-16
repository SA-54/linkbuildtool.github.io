@section('site_title', formatTitle([__('Settings'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('dashboard'), 'title' => __('Home')],
    ['title' => __('Settings')]
]])

<h2 class="mb-0 d-inline-block">{{ __('Settings') }}</h2>

@php
    $settings[] = [
        'icon' => 'icons.background.account',
        'title' => 'Account',
        'description' => 'Update your account information',
            'route' => 'settings.account'
    ];

    $settings[] = [
        'icon' => 'icons.background.security',
        'title' => 'Security',
        'description' => 'Change your security information',
        'route' => 'settings.security'
    ];

    // If the payment system is enabled & the user has previously added his billing information
    if(config('settings.stripe') && $user->card_brand) {
        $settings[] = [
            'icon' => 'icons.background.card',
            'title' => 'Payment methods',
            'description' => 'Add, change, or remove payment methods',
            'route' => 'settings.payments.methods'
        ];

        $settings[] = [
            'icon' => 'icons.background.billing',
            'title' => 'Billing information',
            'description' => 'Change your billing information',
            'route' => 'settings.payments.billing'
        ];

        $settings[] = [
            'icon' => 'icons.background.subscription',
            'title' => 'Subscriptions',
            'description' => 'View your subscriptions details',
            'route' => 'settings.payments.subscriptions'
        ];

        $settings[] = [
            'icon' => 'icons.background.invoice',
            'title' => 'Invoices',
            'description' => 'Print and view your invoice history',
            'route' => 'settings.payments.invoices'
        ];
    }

    $settings[] = [
        'icon' => 'icons.background.api',
        'title' => 'API',
        'description' => 'View and change your developer key',
        'route' => 'settings.api'
    ];

    $settings[] = [
        'icon' => 'icons.background.delete',
        'title' => 'Delete account',
        'description' => 'Delete your account and associated data',
        'route' => 'settings.delete'
    ];
@endphp

<div class="row">
    @foreach($settings as $setting)
        <div class="col-12 col-sm-6 col-md-4 col-xl-3 mt-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column text-center">
                    <div class="d-flex justify-content-center">@include($setting['icon'], ['class' => 'text-primary fill-current icon-card my-3'])</div>

                    <div class="my-2 text-dark font-weight-medium font-size-lg">{{ __($setting['title']) }}</div>

                    <a href="{{ (Route::has($setting['route']) ? route($setting['route']) : $setting['route']) }}" class="text-secondary text-decoration-none stretched-link mb-3">{{ __($setting['description']) }}</a>
                </div>
            </div>
        </div>
    @endforeach
</div>
