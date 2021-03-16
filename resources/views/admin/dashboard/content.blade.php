@extends('layouts.app')

@section('site_title', formatTitle([__('Dashboard'), config('settings.title')]))

@section('content')
<div class="bg-base-1 flex-fill">
    @include('admin.dashboard.header')
    <div class="bg-base-1">
        <div class="container py-3 my-3">
            <h4 class="mb-0">{{ __('Overview') }}</h4>

            <div class="row mb-5">
                @php
                    $cards = [
                        'users' =>
                        [
                            'title' => 'Users',
                            'value' => $stats['users'],
                            'description' => 'Manage users',
                            'route' => 'admin.users',
                            'icon' => 'icons.background.users'
                        ],
                        [
                            'title' => 'Subscriptions',
                            'value' => $stats['subscriptions'],
                            'description' => 'Manage subscriptions',
                            'route' => 'admin.subscriptions',
                            'icon' => 'icons.background.subscription'
                        ],
                        [
                            'title' => 'Plans',
                            'value' => $stats['plans'],
                            'description' => 'Manage plans',
                            'route' => 'admin.plans',
                            'icon' => 'icons.background.package'
                        ],
                        [
                            'title' => 'Links',
                            'value' => $stats['links'],
                            'description' => 'Manage links',
                            'route' => 'admin.links',
                            'icon' => 'icons.background.link'
                        ],
                        [
                            'title' => 'Spaces',
                            'value' => $stats['spaces'],
                            'description' => 'Manage spaces',
                            'route' => 'admin.spaces',
                            'icon' => 'icons.background.space'
                        ],
                        [
                            'title' => 'Domains',
                            'value' => $stats['domains'],
                            'description' => 'Manage domains',
                            'route' => 'admin.domains',
                            'icon' => 'icons.background.domain'
                        ]
                    ];
                @endphp

                @foreach($cards as $card)
                    <div class="col-12 col-md-6 col-lg-4 mt-3">
                        <div class="card border-0 shadow-sm h-100 overflow-hidden">
                            <div class="card-body d-flex">
                                <div class="flex-grow-1 d-block text-truncate">
                                    <div class="text-muted font-weight-medium mb-2 text-truncate">{{ __($card['title']) }}</div>
                                    <div class="h1 mb-0 font-weight-normal text-truncate">{{ number_format($card['value'], 0, __('.'), __(',')) }}</div>
                                </div>

                                <div class="text-primary d-flex align-items-top">
                                    @include($card['icon'], ['class' => 'fill-current icon-card-stats'])
                                </div>
                            </div>
                            <div class="card-footer bg-base-2 border-0">
                                <a href="{{ route($card['route']) }}" class="text-muted font-weight-medium d-inline-flex align-items-baseline">{{ __($card['description']) }}@include((__('lang_dir') == 'rtl' ? 'icons.chevron_left' : 'icons.chevron_right'), ['class' => 'icon-chevron fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <h4 class="mb-0">{{ __('Recent activity') }}</h4>
            <div class="row">
                <div class="col-12 col-xl-6 mt-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header align-items-center">
                            <div class="row">
                                <div class="col"><div class="font-weight-medium py-1">{{ __('Latest users') }}</div></div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($users) == 0)
                                {{ __('No data') }}.
                            @else
                                <div class="list-group list-group-flush my-n3">
                                    @foreach($users as $user)
                                        <div class="list-group-item px-0">
                                            <div class="row align-items-center">
                                                <div class="col text-truncate">
                                                    <div class="row align-items-center">
                                                        <div class="col-12 d-flex">
                                                            <div class="{{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}"><img src="{{ gravatar($user->email, 48) }}" class="rounded-circle icon-label"></div>
                                                            <div class="text-truncate">
                                                                <div class="d-flex">
                                                                    <div class="text-truncate">
                                                                        <a href="{{ route('admin.users.edit', $user->id) }}"@if($user->trashed()) class="text-danger" @endif>{{ $user->name }}</a>
                                                                    </div>
                                                                </div>

                                                                <div class="text-muted text-truncate small">
                                                                    {{ $user->email }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-primary btn-sm">{{ __('Edit') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-6 mt-3">
                    @if(config('settings.stripe'))
                        <div class="card border-0 shadow-sm">
                            <div class="card-header align-items-center">
                                <div class="row">
                                    <div class="col"><div class="font-weight-medium py-1">{{ __('Latest subscriptions') }}</div></div>
                                </div>
                            </div>
                            <div class="card-body">
                                @if(count($subscriptions) == 0)
                                    {{ __('No data') }}.
                                @else
                                    <div class="list-group list-group-flush my-n3">
                                        @foreach($subscriptions as $subscription)
                                            <div class="list-group-item px-0">
                                                <div class="row align-items-center">
                                                    <div class="col text-truncate">
                                                        <div class="row align-items-center">
                                                            <div class="col-12 d-flex">
                                                                <div class="{{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}"><img src="{{ gravatar($subscription->user->email, 48) }}" class="rounded-circle icon-label"></div>
                                                                <div class="text-truncate">
                                                                    <div class="d-flex">
                                                                        <div class="text-truncate">
                                                                            <a href="{{ route('admin.users.edit', $subscription->user->id) }}">{{ $subscription->user->name }}</a>
                                                                        </div>
                                                                        <div>
                                                                            <div class="badge badge-{{ formatStripeStatus()[$subscription->stripe_status]['status'] }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}">{{ formatStripeStatus()[$subscription->stripe_status]['title'] }}</div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="text-dark text-truncate small">
                                                                        <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}" class="text-secondary">{{ $subscription->name }}</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}" class="btn btn-outline-primary btn-sm">{{ __('Edit') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="card border-0 shadow-sm">
                            <div class="card-header align-items-center">
                                <div class="row">
                                    <div class="col"><div class="font-weight-medium py-1">{{ __('Latest links') }}</div></div>
                                </div>
                            </div>

                            <div class="card-body">
                                @if(count($links) == 0)
                                    {{ __('No data') }}.
                                @else
                                    <div class="list-group list-group-flush my-n3">
                                        @foreach($links as $link)
                                            <div class="list-group-item px-0">
                                                <div class="row align-items-center">
                                                    <div class="col d-flex text-truncate">
                                                        <div class="{{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}"><img src="https://icons.duckduckgo.com/ip3/{{ parse_url($link->url)['host'] }}.ico" rel="noreferrer" class="icon-label"></div>

                                                        <div class="text-truncate">
                                                            <a href="{{ route('stats', $link->id) }}" class="{{ ($link->disabled || $link->expiration_clicks && $link->clicks >= $link->expiration_clicks || \Carbon\Carbon::now()->greaterThan($link->ends_at) && $link->ends_at ? 'text-danger' : 'text-primary') }}" dir="ltr">{{ str_replace(['http://', 'https://'], '', (($link->domain->name ?? config('app.url')) .'/'.$link->alias)) }}</a>

                                                            <div class="text-dark text-truncate small">
                                                                <span class="text-secondary cursor-help" data-toggle="tooltip-url" title="{{ $link->url }}">@if($link->title){{ $link->title }}@else<span dir="ltr">{{ str_replace(['http://', 'https://'], '', $link->url) }}</span>@endif</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto d-flex">
                                                        @include('shared.buttons.copy_link')
                                                        @include('shared.dropdowns.link', ['admin' => true, 'options' => ['dropdown' => ['button' => true, 'edit' => true, 'share' => true, 'stats' => true, 'preview' => true, 'open' => true, 'delete' => true]]])
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@include('shared.modals.share_link')
@include('shared.modals.delete_link')

@include('admin.sidebar')
@endsection