@section('site_title', formatTitle([__('Edit'), __('Subscription'), config('settings.title')]))

@if(isset($admin))
    @include('shared.breadcrumbs', ['breadcrumbs' => [
        ['url' => route('admin.dashboard'), 'title' => __('Admin')],
        ['url' => route('admin.subscriptions'), 'title' => __('Subscriptions')],
        ['title' => __('Edit')],
    ]])
@else
    @include('shared.breadcrumbs', ['breadcrumbs' => [
        ['url' => route('dashboard'), 'title' => __('Home')],
        ['url' => route('settings'), 'title' => __('Settings')],
        ['url' => route('settings.payments.subscriptions'), 'title' => __('Subscriptions')],
        ['title' => __('Edit')]
    ]])
@endif

<h2 class="mb-0 d-inline-block">{{ __('Edit') }}</h2>

<div class="card border-0 shadow-sm mt-3">
    <div class="card-header"><div class="font-weight-medium py-1">{{ __('Subscription') }}</div></div>
    <div class="card-body mb-n3">
        @include('shared.message')

        @if($subscription->stripe_status == 'active')
            @if($user->subscription($subscription->name)->cancelled() && !$user->subscription($subscription->name)->onGracePeriod())
                <div class="alert alert-danger" role="alert">
                    {{ __(':name is cancelled.', ['name' => $subscription->name]) }}
                </div>
            @endif
        @endif

        <div class="row">
            <div class="col-12 col-lg-6 mb-3">
                <div class="text-muted">{{ __('Plan') }}</div>
                <div>{{ $subscription->name }} <span class="badge badge-secondary">{{ $plan->plan_month == $subscription->stripe_plan ? __('Monthly') : __('Yearly') }}</span></div>
            </div>

            <div class="col-12 col-lg-6 mb-3">
                <div class="text-muted">{{ __('Status') }}</div>
                <div><span class="badge badge-{{ formatStripeStatus()[$subscription->stripe_status]['status'] }}">{{ formatStripeStatus()[$subscription->stripe_status]['title'] }}</span></div>
            </div>

            @if(isset($admin))
                <div class="col-12 col-lg-6 mb-3">
                    <div class="text-muted">{{ __('Plan ID') }}</div>
                    <div>{{ $subscription->stripe_plan }}</div>
                </div>

                <div class="col-12 col-lg-6 mb-3">
                    <div class="text-muted">{{ __('Subscription ID') }}</div>
                    <div>{{ $subscription->stripe_id }}</div>
                </div>
            @endif

            <div class="col-12 col-lg-6 mb-3">
                <div class="text-muted">{{ __('Created at') }}</div>
                <div>{{ $subscription->created_at->format(__('Y-m-d')) }}</div>
            </div>


            @if($subscription->updated_at)
                <div class="col-12 col-lg-6 mb-3">
                    <div class="text-muted">{{ __('Updated at') }}</div>
                    <div>{{ $subscription->updated_at->format(__('Y-m-d')) }}</div>
                </div>
            @endif

            @if($subscription->trial_ends_at)
                <div class="col-12 col-lg-6 mb-3">
                    <div class="text-muted">{{ __('Trial ends at') }}</div>
                    <div>{{ $subscription->trial_ends_at->format(__('Y-m-d')) }}</div>
                </div>
            @endif

            @if($subscription->ends_at)
                <div class="col-12 col-lg-6 mb-3">
                    <div class="text-muted">{{ __('Ends at') }}</div>
                    <div>{{ $subscription->ends_at->format(__('Y-m-d')) }}</div>
                </div>
            @else
                <div class="col-12 col-lg-6 mb-3">
                    <div class="text-muted">{{ __('Renews on') }}</div>
                    <div>
                        @if($subscription->updated_at)
                            @if($plan->plan_month == $subscription->stripe_plan)
                                {{ $subscription->updated_at->addMonth(1)->format(__('Y-m-d')) }}
                            @else
                                {{ $subscription->updated_at->addYears(1)->format(__('Y-m-d')) }}
                            @endif
                        @else
                            @if($plan->plan_month == $subscription->stripe_plan)
                                {{ $subscription->created_at->addMonth(1)->format(__('Y-m-d')) }}
                            @else
                                {{ $subscription->created_at->addYears(1)->format(__('Y-m-d')) }}
                            @endif
                        @endif
                    </div>
                </div>
            @endif
        </div>

        @if(isset($admin) == false)
            <div class="row">
                <div class="col">
                    @if($subscription->onGracePeriod() && $subscription->stripe_id)
                        <button type="button" class="btn btn-outline-success mb-3" data-toggle="modal" data-target="#resumeModal">{{ __('Resume') }}</button>
                    @endif

                    @if($subscription->hasIncompletePayment())
                        <a href="{{ route('checkout.confirm', $subscription->latestPayment()->id) }}" class="btn btn-outline-primary mb-3">{{ __('Confirm payment') }}</a>
                    @endif
                </div>

                <div class="col-auto">
                    @if($subscription->recurring() || ($subscription->onTrial() && !$subscription->onGracePeriod()))
                        <button type="button" class="btn btn-outline-danger mb-3" data-toggle="modal" data-target="#cancelModal">{{ __('Cancel') }}</button>
                    @endif
                </div>
            </div>
        @else
            @if(!$subscription->stripe_id)
                <div class="row">
                    <div class="col-auto">
                        <button type="button" class="btn btn-outline-danger mb-3" data-toggle="modal" data-target="#deleteModal">{{ __('Delete') }}</button>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>

@if(isset($admin))
    <div class="card border-0 shadow-sm mt-3">
        <div class="card-header">
            <div class="row"><div class="col"><div class="font-weight-medium py-1">{{ __('User') }}</div></div><div class="col-auto"><a href="{{ route('admin.users.edit', $subscription->user->id) }}" class="btn btn-outline-primary btn-sm">{{ __('Edit') }}</a></div></div>
        </div>
        <div class="card-body mb-n3">
            <div class="row">
                <div class="col-12 col-lg-6 mb-3">
                    <div class="text-muted">{{ __('Name') }}</div>
                    <div>{{ $subscription->user->name }}</div>
                </div>

                <div class="col-12 col-lg-6 mb-3">
                    <div class="text-muted">{{ __('Email') }}</div>
                    <div>{{ $subscription->user->email }}</div>
                </div>
            </div>
        </div>
    </div>

    @if(!$subscription->stripe_id)
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">{{ __('Delete') }}</h6>
                        <button type="button" class="close d-flex align-items-center justify-content-center" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="d-flex align-items-center">@include('icons.close')</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ __('Are you sure you want to delete :name?', ['name' => $subscription->name]) }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                        <form action="{{ route('admin.subscriptions.delete', $subscription->id) }}" method="post" enctype="multipart/form-data">

                            @csrf

                            <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@else
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{ __('Cancel') }}</h6>
                    <button type="button" class="close d-flex align-items-center justify-content-center" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="d-flex align-items-center">@include('icons.close')</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">{{ __('You\'ll continue to have access to the features you\'ve paid for until the end of your billing cycle.') }}</div>
                    <div>{{ __('Are you sure you want to cancel your subscription?') }}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <form action="{{ route('settings.payments.subscriptions.cancel', ['subscription' => $subscription->name]) }}" method="post" enctype="multipart/form-data">

                        @csrf

                        <button type="submit" class="btn btn-danger">{{ __('Cancel') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="resumeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{ __('Resume') }}</h6>
                    <button type="button" class="close d-flex align-items-center justify-content-center" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="d-flex align-items-center">@include('icons.close')</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>{{ __('Are you sure you want to resume your subscription?') }}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <form action="{{ route('settings.payments.subscriptions.resume', ['subscription' => $subscription->name]) }}" method="post" enctype="multipart/form-data">

                        @csrf

                        <button type="submit" class="btn btn-success">{{ __('Resume') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif