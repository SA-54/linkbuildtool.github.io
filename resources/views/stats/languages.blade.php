@section('site_title', formatTitle([$link->alias, __('Languages'), __('Stats'), config('settings.title')]))

<div class="card border-0 shadow-sm">
    <div class="card-header">
        <div class="font-weight-medium py-1">{{ __('Languages') }}</div>
    </div>
    <div class="card-body">
        @if($user->can('stats', ['App\Link', $remoteUserFeatures['option_stats']]))
            @if(count($languages) == 0)
                {{ __('No data') }}.
            @else
                <div class="list-group list-group-flush my-n3">
                    @foreach($languages as $language)
                        <div class="list-group-item px-0 border-0">
                            <div class="d-flex flex-column">
                                <div class="d-flex justify-content-between mb-2">
                                    <div class="d-flex text-truncate align-items-center">
                                        <div class="text-truncate">
                                            @if(array_key_exists($language->language, config('languages')))
                                                {{ __(config('languages')[$language->language]) }}
                                            @else
                                                {{ __('Unknown') }}
                                            @endif
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-baseline {{ (__('lang_dir') == 'rtl' ? 'mr-3 text-left' : 'ml-3 text-right') }}">
                                        <span class="text-muted small">{{ number_format($language->count, 0, __('.'), __(',')) }}</span>

                                        <div class="chart-value {{ (__('lang_dir') == 'rtl' ? 'mr-3' : 'ml-3') }}">
                                            {{ number_format((($language->count / $link->clicks) * 100), 1, __('.'), __(',')) }}%
                                        </div>
                                    </div>
                                </div>
                                <div class="progress chart-progress w-100">
                                    <div class="progress-bar rounded" role="progressbar" style="width: {{ (($language->count / $link->clicks) * 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-3 align-items-center">
                        <div class="row">
                            <div class="col">
                                <div class="mt-2 mb-3">{{ __('Showing :from-:to of :total', ['from' => $languages->firstItem(), 'to' => $languages->lastItem(), 'total' => $languages->total()]) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                {{ $languages->onEachSide(1)->links() }}
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