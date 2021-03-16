@section('site_title', formatTitle([$link->alias, __('Social'), __('Stats'), config('settings.title')]))

<div class="card border-0 shadow-sm">
    <div class="card-header">
        <div class="font-weight-medium py-1">{{ __('Social') }}</div>
    </div>
    <div class="card-body">
        @if($user->can('stats', ['App\Link', $remoteUserFeatures['option_stats']]))
            @if(count($socials) == 0)
                {{ __('No data') }}.
            @else
                <div class="list-group list-group-flush my-n3">
                    @foreach($socials as $social)
                        <div class="list-group-item px-0 border-0">
                            <div class="d-flex flex-column">
                                <div class="d-flex justify-content-between mb-2">
                                    <div class="d-flex text-truncate align-items-center">
                                        <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"><img src="https://www.google.com/s2/favicons?domain={{ $social->referrer }}" rel="noreferrer" class="icon-label"></div>
                                        <div class="text-truncate">
                                            {{ $domains[$social->referrer] }}
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-baseline {{ (__('lang_dir') == 'rtl' ? 'mr-3 text-left' : 'ml-3 text-right') }}">
                                        <span class="text-muted small">{{ number_format($social->count, 0, __('.'), __(',')) }}</span>

                                        <div class="chart-value {{ (__('lang_dir') == 'rtl' ? 'mr-3' : 'ml-3') }}">
                                            {{ number_format((($social->count / $totalCount) * 100), 1, __('.'), __(',')) }}%
                                        </div>
                                    </div>
                                </div>
                                <div class="progress chart-progress w-100">
                                    <div class="progress-bar rounded" role="progressbar" style="width: {{ (($social->count / $totalCount) * 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-3 align-items-center">
                        <div class="row">
                            <div class="col">
                                <div class="mt-2 mb-3">{{ __('Showing :from-:to of :total', ['from' => $socials->firstItem(), 'to' => $socials->lastItem(), 'total' => $socials->total()]) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                {{ $socials->onEachSide(1)->links() }}
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