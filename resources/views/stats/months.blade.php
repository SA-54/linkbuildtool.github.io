@section('site_title', formatTitle([$link->alias, __('Months'), __('Stats'), config('settings.title')]))

<div class="card border-0 shadow-sm">
    <div class="card-header">
        <div class="row">
            <div class="col">
                <div class="font-weight-medium py-1">{{ __('Evolution') }}</div>
            </div>
            <div class="col-auto">
                <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                    <a href="{{ route('stats.evolution.hours', ['id' => $link->id]) }}" class="btn btn-outline-primary">{{ __('Hours') }}</a>
                    <a href="{{ route('stats.evolution.days', ['id' => $link->id]) }}" class="btn btn-outline-primary">{{ __('Days') }}</a>
                    <a href="{{ route('stats.evolution.months', ['id' => $link->id]) }}" class="btn btn-primary">{{ __('Months') }}</a>
                </div>
            </div>
        </div>

    </div>
    <div class="card-body">
        @if($user->can('stats', ['App\Link', $remoteUserFeatures['option_stats']]))
            @if(count($values) == 0)
                {{ __('No data') }}.
            @else
                @foreach($values as $value)
                    <div class="list-group-item px-0 border-0">
                        <div class="d-flex flex-column">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="d-flex text-truncate align-items-center">
                                    <div class="text-truncate">
                                        {{ __(\Carbon\Carbon::parse($value['date_result'])->tz(Auth::user()->timezone ?? config('app.timezone'))->format('F')) }} <span class="text-muted">{{ \Carbon\Carbon::parse($value['date_result'])->tz(Auth::user()->timezone ?? config('app.timezone'))->format('Y') }}</span>
                                    </div>
                                </div>

                                <div class="d-flex align-items-baseline {{ (__('lang_dir') == 'rtl' ? 'mr-3 text-left' : 'ml-3 text-right') }}">
                                    <span class="text-muted small">{{ number_format($value['aggregate'], 0, __('.'), __(',')) }}</span>

                                    <div class="chart-value d-flex align-items-center justify-content-end {{ (__('lang_dir') == 'rtl' ? 'mr-3' : 'ml-3') }}">
                                        @if(calcGrowth($value['aggregate'], ($values[$loop->index + 1]['aggregate'] ?? 0)) > 0)
                                            @include('icons.increase', ['class' => 'fill-current text-success icon-performance '.(__('lang_dir') == 'rtl' ? 'ml-1' : 'mr-1')]){{ str_replace(__('.') . '0', '', number_format(calcGrowth($value['aggregate'], ($values[$loop->index + 1]['aggregate'] ?? 0)), 1, __('.'), __(','))) }}%
                                        @elseif(calcGrowth($value['aggregate'], ($values[$loop->index + 1]['aggregate'] ?? 0)) < 0)
                                            @include('icons.decrease', ['class' => 'fill-current text-danger icon-performance '.(__('lang_dir') == 'rtl' ? 'ml-1' : 'mr-1')]){{ str_replace(['-', __('.') . '0'], '', number_format(calcGrowth($value['aggregate'], ($values[$loop->index + 1]['aggregate'] ?? 0)), 1, __('.'), __(','))) }}%
                                        @else
                                            @include('icons.neutral', ['class' => 'fill-current text-secondary icon-performance '.(__('lang_dir') == 'rtl' ? 'ml-1' : 'mr-1')])0%
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="progress chart-progress w-100">
                                <div class="progress-bar rounded" role="progressbar" style="width: {{ ($value['aggregate'] ? (($value['aggregate'] / $max) * 100) : 0) }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        @else
            @include('shared.feature_unlock')
        @endif
    </div>
</div>