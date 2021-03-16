@section('site_title', formatTitle([__('Invoices'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('dashboard'), 'title' => __('Home')],
    ['url' => route('settings'), 'title' => __('Settings')],
    ['url' => route('settings.payments.invoices'), 'title' => __('Invoices')],
]])

<h2 class="mb-3 d-inline-block">{{ __('Invoices') }}</h2>

<div class="card border-0 shadow-sm">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col"><div class="font-weight-medium py-1">{{ __('Invoices') }}</div></div>
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        @if($invoices == null || count($invoices) == 0)
            {{ __('No results found.') }}
        @else
            <div class="list-group list-group-flush my-n3">
                <div class="list-group-item px-0 text-muted">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="row">
                                <div class="col-12 col-lg-6">{{ __('Date') }}</div>
                                <div class="col-12 col-lg-6">{{ __('Total') }}</div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="btn btn-outline-primary btn-sm invisible">{{ __('View') }}</div>
                        </div>
                    </div>
                </div>

                @foreach($invoices as $invoice)
                    <div class="list-group-item px-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="row">
                                    <div class="col-12 col-lg-6">
                                        {{ $invoice->date()->format(__('Y-m-d')) }}
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        {{ formatMoney($invoice->rawTotal(), $invoice->currency) }} {{ strtoupper($invoice->currency) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('settings.payments.invoice', $invoice->id) }}" class="btn btn-outline-primary btn-sm">{{ __('View') }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>