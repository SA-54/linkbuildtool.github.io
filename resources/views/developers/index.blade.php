@extends('layouts.app')

@section('site_title', formatTitle([__('Developers'), config('settings.title')]))

@section('content')
    <div class="bg-base-1 flex-fill">
        <div class="container h-100 py-6">

            <div class="text-center">
                <h2 class="mb-3 d-inline-block">{{ __('Developers') }}</h2>
                <div class="m-auto">
                    <p class="text-muted font-weight-normal font-size-lg mb-4">{{ __('Explore our API documentation.') }}</p>
                </div>
            </div>

            @php
                $resources = [
                    [
                        'icon' => 'icons.background.link',
                        'title' => 'Links',
                        'description' => 'Manage links',
                        'route' => 'developers.links'
                    ],
                    [
                        'icon' => 'icons.background.space',
                        'title' => 'Spaces',
                        'description' => 'Manage spaces',
                        'route' => 'developers.spaces'
                    ],
                    [
                        'icon' => 'icons.background.domain',
                        'title' => 'Domains',
                        'description' => 'Manage domains',
                        'route' => 'developers.domains'
                    ],
                    [
                        'icon' => 'icons.background.account',
                        'title' => 'Account',
                        'description' => 'Manage account',
                        'route' => 'developers.account'
                    ]
                ];
            @endphp

            <div class="row">
                @foreach($resources as $resource)
                    <div class="col-12 col-sm-6 col-md-4 col-xl-3 mt-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body d-flex flex-column text-center">
                                <div class="d-flex justify-content-center">@include($resource['icon'], ['class' => 'text-primary fill-current icon-card my-3'])</div>

                                <div class="my-2 text-dark font-weight-medium font-size-lg">{{ __($resource['title']) }}</div>

                                <a href="{{ route($resource['route']) }}" class="text-secondary text-decoration-none stretched-link mb-3">{{ __($resource['description']) }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@include('shared.sidebars.user')