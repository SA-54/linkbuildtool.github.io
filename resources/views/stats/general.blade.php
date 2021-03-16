@section('site_title', formatTitle([$link->alias, __('General'), __('Stats'), config('settings.title')]))

<h4 class="mb-0">{{ __('Clicks') }}</h4>

<div class="row mb-5">
    @foreach($stats as $key => $value)
        <div class="col-12 col-lg-4 mt-3">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body">
                    <div class="text-muted font-weight-medium mb-2">{{ __($key) }}</div>
                    <div class="h1 mb-0 font-weight-normal">{{ number_format($value['current'], 0, __('.'), __(',')) }}</div>
                </div>
                <div class="card-footer bg-base-2 border-0">
                    <div class="d-flex align-items-center text-muted font-weight-medium">
                        @if(isset($value['previous']))
                            @if(calcGrowth($value['current'], $value['previous']) > 0)
                                @include('icons.increase', ['class' => 'fill-current text-success icon-performance '.(__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2')]) {{ str_replace(__('.') . '0', '', number_format(calcGrowth($value['current'], $value['previous']), 1, __('.'), __(','))) }}% {{ __('Increase') }}
                            @elseif(calcGrowth($value['current'], $value['previous']) < 0)
                                @include('icons.decrease', ['class' => 'fill-current text-danger icon-performance '.(__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2')]) {{ str_replace(['-', __('.') . '0'], '', number_format(calcGrowth($value['current'], $value['previous']), 1, __('.'), __(','))) }}% {{ __('Decrease') }}
                            @else
                                @if($value['current'] == $value['previous'] && $value['current'] > 0)
                                    @include('icons.neutral', ['class' => 'fill-current text-danger icon-performance '.(__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2')]) {{ str_replace(['-', __('.') . '0'], '', number_format(calcGrowth($value['current'], $value['previous']), 1, __('.'), __(','))) }}% {{ __('Constant') }}
                                @elseif(!$value['previous'])
                                    {{ __('No prior data') }}
                                @else
                                    {{ __('No current data') }}
                                @endif
                            @endif
                        @else
                            {{ __('Total') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<h4 class="mb-0">{{ __('Recent activity') }}</h4>

<div class="card border-0 shadow-sm mt-3">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col"><div class="font-weight-medium py-1">{{ __('Latest clicks') }}</div></div>
        </div>
    </div>

    <div class="card-body">
        @if($user->can('stats', ['App\Link', $remoteUserFeatures['option_stats']]))
            @if(count($clicks) == 0)
                {{ __('No data') }}.
            @else
                <div class="list-group list-group-flush my-n3">
                    @foreach($clicks as $click)
                        <div class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col text-truncate">
                                    <div class="row align-items-center">
                                        <div class="col-6 col-lg-3 d-flex">
                                            <div class="{{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}"><img src="{{ asset('/images/icons/countries/'.formatCountry($click->country)) }}.svg" class="icon-label"></div>
                                            <div class="text-truncate">
                                                <div class="d-flex">
                                                    <div class="text-truncate">
                                                        @if(array_key_exists($click->country, config('countries')))
                                                            {{ __(config('countries')[$click->country]) }}
                                                        @else
                                                            {{ __('Unknown') }}
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="text-muted text-truncate small">
                                                    @if($click->referrer)
                                                        {{ $click->referrer }}
                                                    @else
                                                        {{ __('Direct, Email, SMS') }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3 d-flex">
                                            <div class="{{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}"><img src="{{ asset('/images/icons/browsers/'.formatBrowser($click->browser)) }}.svg" class="icon-label"></div>
                                            <div class="text-truncate">
                                                <div class="d-flex">
                                                    <div class="text-truncate">
                                                        @if($click->browser)
                                                            {{ $click->browser }}
                                                        @else
                                                            {{ __('Unknown') }}
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="text-muted text-truncate small">
                                                    @if($click->platform)
                                                        {{ $click->platform }}
                                                    @else
                                                        {{ __('Unknown') }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3 d-flex">
                                            <div class="{{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}"><img src="{{ asset('/images/icons/devices/'.formatDevice($click->device)) }}.svg" class="icon-label"></div>
                                            <div class="text-truncate">
                                                <div class="d-flex">
                                                    <div class="text-truncate">
                                                        @if($click->device)
                                                            <span class="text-capitalize">{{ __(Str::ucfirst($click->device)) }}</span>
                                                        @else
                                                            {{ __('Unknown') }}
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="text-muted text-truncate small">
                                                    @if(array_key_exists($click->language, config('languages')))
                                                        {{ __(config('languages')[$click->language]) }}
                                                    @else
                                                        {{ __('Unknown') }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3 d-flex">
                                            <div class="{{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}"><img src="{{ asset('/images/icons/time.svg') }}" class="icon-label"></div>
                                            <div class="text-truncate">
                                                <div class="d-flex">
                                                    <div class="text-truncate">
                                                        {{ $click->created_at->tz(Auth::user()->timezone ?? config('app.timezone'))->toTimeString() }}
                                                    </div>
                                                </div>

                                                <div class="text-muted text-truncate small">
                                                    {{ $click->created_at->tz(Auth::user()->timezone ?? config('app.timezone'))->format(__('Y-m-d')) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-3 align-items-center">
                        <div class="row">
                            <div class="col">
                                <div class="mt-2 mb-3">{{ __('Showing :from-:to of :total', ['from' => $clicks->firstItem(), 'to' => $clicks->lastItem(), 'total' => $clicks->total()]) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                {{ $clicks->onEachSide(1)->links() }}
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