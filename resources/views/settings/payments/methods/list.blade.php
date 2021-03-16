@section('site_title', formatTitle([__('Payment methods'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('dashboard'), 'title' => __('Home')],
    ['url' => route('settings'), 'title' => __('Settings')],
    ['url' => route('settings.payments.methods'), 'title' => __('Payment methods')],
]])

<div class="d-flex">
    <div class="flex-grow-1">
        <h2 class="mb-3 d-inline-block">{{ __('Payment methods') }}</h2>
    </div>
    <div>
        <a href="{{ route('settings.payments.methods.new') }}" class="btn btn-primary mb-3">{{ __('New') }}</a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col"><div class="font-weight-medium py-1">{{ __('Payment methods') }}</div></div>
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        @if($paymentMethods == null || count($paymentMethods) == 0)
            {{ __('No results found.') }}
        @else
            <div class="list-group list-group-flush my-n3">
                <div class="list-group-item px-0 text-muted">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="row">
                                <div class="col-12 col-lg-6">{{ __('Card number') }}</div>
                                <div class="col-12 col-lg-6">{{ __('Expiration date') }}</div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="btn btn-outline-primary btn-sm invisible">{{ __('Edit') }}</div>
                        </div>
                    </div>
                </div>

                @foreach($paymentMethods as $payment)
                    <div class="list-group-item px-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="row">
                                    <div class="col-12 col-lg-6"><div class="payment-icon">@include('icons.payments.' . (in_array($payment->card->brand, config('payments')) ? $payment->card->brand : 'unknown'))</div> •••• {{ $payment->card->last4 }} @if($defaultPaymentMethod && $defaultPaymentMethod->id == $payment->id) <span class="badge badge-secondary">{{ __('Default') }}</span>@endif</div>
                                    <div class="col-12 col-lg-6"><span class="card-expiry{{ (date('m') > $payment->card->exp_month && date('Y') >= $payment->card->exp_year) ? ' text-danger' : '' }}">{{ date('m / y', strtotime('01-'.$payment->card->exp_month.'-'.$payment->card->exp_year)) }}</span></div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('settings.payments.methods.edit', $payment->id) }}" class="btn btn-outline-primary btn-sm">{{ __('Edit') }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
