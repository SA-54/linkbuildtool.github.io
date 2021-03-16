@section('site_title', formatTitle([__('New'), __('Subscription'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('admin.dashboard'), 'title' => __('Admin')],
    ['url' => route('admin.subscriptions'), 'title' => __('Subscriptions')],
    ['title' => __('New')],
]])

<h2 class="mb-3 d-inline-block">{{ __('New') }}</h2>

<div class="card border-0 shadow-sm">
    <div class="card-header"><div class="font-weight-medium py-1">{{ __('Subscription') }}</div></div>
    <div class="card-body">
        @include('shared.message')

        <form action="{{ route('admin.subscriptions.new') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="i_email">{{ __('Email address') }}</label>
                <input id="i_email" type="text" dir="ltr" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" autofocus>
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i_plan">{{ __('Plan') }}</label>
                <select name="plan" id="i_plan" class="custom-select{{ $errors->has('currency') ? ' is-invalid' : '' }}">
                    @foreach($plans as $plan)
                        <option value="{{ $plan->plan_month }}">{{ $plan->name }} {{ formatMoney($plan->amount_month, $plan->currency) }} {{ $plan->currency }}</option>
                        <option value="{{ $plan->plan_year }}">{{ $plan->name }} {{ formatMoney($plan->amount_year, $plan->currency) }} {{ $plan->currency }}</option>
                    @endforeach
                </select>
                @if ($errors->has('currency'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('currency') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i_trial_days">{{ __('Trial days') }}</label>
                <input type="number" name="trial_days" id="i_trial_days" class="form-control{{ $errors->has('trial_days') ? ' is-invalid' : '' }}" value="{{ old('trial_days') ?? null }}">
                @if ($errors->has('trial_days'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('trial_days') }}</strong>
                    </span>
                @endif
            </div>

            <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>
    </div>
</div>