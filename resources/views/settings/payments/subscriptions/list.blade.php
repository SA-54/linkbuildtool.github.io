@section('site_title', formatTitle([__('Subscriptions'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('dashboard'), 'title' => __('Home')],
    ['url' => route('settings'), 'title' => __('Settings')],
    ['url' => route('settings.payments.subscriptions'), 'title' => __('Subscriptions')],
]])

<h2 class="mb-3 d-inline-block">{{ __('Subscriptions') }}</h2>

<div class="card border-0 shadow-sm">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col"><div class="font-weight-medium py-1">{{ __('Subscriptions') }}</div></div>
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        @if(count($subscriptions) == 0)
            {{ __('No results found.') }}
        @else
            <div class="list-group list-group-flush my-n3">
                <div class="list-group-item px-0 text-muted">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="row">
                                <div class="col-12 col-lg-6">{{ __('Plan') }}</div>
                                <div class="col-12 col-lg-3">{{ __('Status') }}</div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="btn btn-outline-primary btn-sm invisible">{{ __('Edit') }}</div>
                        </div>
                    </div>
                </div>

                @foreach($subscriptions as $subscription)
                    <div class="list-group-item px-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="row">
                                    <div class="col-12 col-lg-6">{{ $subscription->name }}</div>
                                    <div class="col-12 col-lg-6"><span class="badge badge-{{ formatStripeStatus()[$subscription->stripe_status]['status'] }}">{{ formatStripeStatus()[$subscription->stripe_status]['title'] }}</span></div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('settings.payments.subscriptions.edit', $subscription->id) }}" class="btn btn-outline-primary btn-sm">{{ __('Edit') }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
