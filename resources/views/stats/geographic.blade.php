@section('site_title', formatTitle([$link->alias, __('Geographic'), __('Stats'), config('settings.title')]))

<div class="card border-0 shadow-sm">
    <div class="card-header">
        <div class="row">
            <div class="col">
                <div class="font-weight-medium py-1">{{ __('Map') }}</div>
            </div>
            <div class="col-auto">

            </div>
        </div>
    </div>

    <div class="card-body">
        @if($user->can('stats', ['App\Link', $remoteUserFeatures['option_stats']]))
            <div id="clicksMap"></div>
            <script src="{{ asset('js/map.js') }}" defer></script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    'use strict';

                    new svgMap({
                        targetElementID: 'clicksMap',
                        data: {
                            data: {
                                country: {
                                    name: '',
                                    format: '{0}'
                                },
                                clicks: {
                                    name: '',
                                    format: '{0} <span class="text-lowercase">{{ __('Clicks') }}</span>',
                                    thousandSeparator: '{{ __(',') }}'
                                }
                            },
                            applyData: 'clicks',
                            values: {
                                @foreach($countriesChart as $country)
                                '{{ $country->country }}': {clicks: {{ $country->count }}, country: '{{ array_key_exists($country->country, config('countries')) ? __(config('countries')[$country->country]) : '' }}'},
                                @endforeach
                            }
                        },
                        colorMin: '#b8b8ff',
                        colorMax: '#313164',
                        hideFlag: true,
                        noDataText: '{{ __('No data') }}'
                    });
                });
            </script>
        @else
            @include('shared.feature_unlock')
        @endif
    </div>
</div>

<div class="card border-0 shadow-sm mt-3">
    <div class="card-header">
        <div class="font-weight-medium py-1">{{ __('Countries') }}</div>
    </div>
    <div class="card-body">
        @if($user->can('stats', ['App\Link', $remoteUserFeatures['option_stats']]))
            @if(count($countries) == 0)
                {{ __('No data') }}.
            @else
                <div class="list-group list-group-flush my-n3">
                    @foreach($countries as $country)
                        <div class="list-group-item px-0 border-0">
                            <div class="d-flex flex-column">
                                <div class="d-flex justify-content-between mb-2">
                                    <div class="d-flex text-truncate align-items-center">
                                        <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"><img src="{{ asset('/images/icons/countries/'.formatCountry($country->country)) }}.svg" class="icon-label"></div>
                                        <div class="text-truncate">
                                            @if(array_key_exists($country->country, config('countries')))
                                                {{ __(config('countries')[$country->country]) }}
                                            @else
                                                {{ __('Unknown') }}
                                            @endif
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-baseline {{ (__('lang_dir') == 'rtl' ? 'mr-3 text-left' : 'ml-3 text-right') }}">
                                        <span class="text-muted small">{{ number_format($country->count, 0, __('.'), __(',')) }}</span>

                                        <div class="chart-value {{ (__('lang_dir') == 'rtl' ? 'mr-3' : 'ml-3') }}">
                                            {{ number_format((($country->count / $link->clicks) * 100), 1, __('.'), __(',')) }}%
                                        </div>
                                    </div>
                                </div>
                                <div class="progress chart-progress w-100">
                                    <div class="progress-bar rounded" role="progressbar" style="width: {{ (($country->count / $link->clicks) * 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-3 align-items-center">
                        <div class="row">
                            <div class="col">
                                <div class="mt-2 mb-3">{{ __('Showing :from-:to of :total', ['from' => $countries->firstItem(), 'to' => $countries->lastItem(), 'total' => $countries->total()]) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                {{ $countries->onEachSide(1)->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @else
            @include('shared.feature_unlock')
        @endif
    </div>
</div>