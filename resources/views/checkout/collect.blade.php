@extends('layouts.app')

@section('site_title', formatTitle([__('Payment information'), config('settings.title')]))

@section('content')
<div class="bg-base-1 flex-fill">
    <div class="container py-3 my-3">

        @include('shared.breadcrumbs', ['breadcrumbs' => [
            ['url' => route('dashboard'), 'title' => __('Home')],
            ['url' => route('pricing'), 'title' => __('Pricing')],
            ['title' => __('Payment information')],
        ]])

        <h2 class="mb-3 d-inline-block">{{ __('Payment information') }}</h2>

        <form id="payment-form" action="{{ route('checkout.collect', ['period' => request()->period]) }}" method="post">
            @csrf
            <div class="row">
                <div class="col-12 col-lg-8">
                    <!-- Payment method -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <div class="font-weight-medium py-1">{{ __('Payment method') }}</div>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-0">
                                <label for="i_card_number">{{ __('Card number') }}</label>
                                <div id="i_card_number" class="form-control" style="height: 2.4em; padding-top: .5em;"></div>
                                <span class="invalid-feedback" id="card-error" role="alert">
                                    <strong></strong>
                                </span>
                            </div>

                            <input type="hidden" id="payment_method" name="payment_method">
                        </div>
                    </div>

                    <!-- Billing information -->
                    <div class="card border-0 shadow-sm mt-3">
                        <div class="card-header">
                            <div class="font-weight-medium py-1">{{ __('Billing information') }}</div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="i_name">{{ __('Name') }}</label>
                                <input type="text" name="name" id="i_name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') ?? ($customer->name ?? null) }}">
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="i_address">{{ __('Address') }}</label>
                                <input type="text" name="address" id="i_address" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" value="{{ old('address') ?? ($customer->address->line1 ?? null) }}">
                                @if ($errors->has('address'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="i_city">{{ __('City') }}</label>
                                        <input type="text" name="city" id="i_city" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" value="{{ old('city') ?? ($customer->address->city ?? null) }}">
                                        @if ($errors->has('city'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('city') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label for="i_state">{{ __('State') }}</label>
                                        <input type="text" name="state" id="i_state" class="form-control" value="{{ old('state') ?? ($customer->address->state ?? null) }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label for="i_postal_code">{{ __('Postal code') }}</label>
                                        <input type="text" name="postal_code" id="i_postal_code" class="form-control{{ $errors->has('postal_code') ? ' is-invalid' : '' }}" value="{{ old('postal_code') ?? ($customer->address->postal_code ?? null) }}">
                                        @if ($errors->has('postal_code'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('postal_code') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="i_country">{{ __('Country') }}</label>
                                <select name="country" id="i_country" class="custom-select{{ $errors->has('country') ? ' is-invalid' : '' }}">
                                    <option value="" hidden disabled selected>{{ __('Country') }}</option>
                                    @foreach(config('countries') as $key => $value)
                                        <option value="{{ $key }}" @if(old('country') == $key) selected @elseif(isset($customer->address->country) && old('country') == null && $key == $customer->address->country) selected @endif>{{ __($value) }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('country'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('country') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group mb-0">
                                <label for="i_phone">{{ __('Phone') }}</label>
                                <input type="tel" name="phone" id="i_phone" class="form-control" value="{{ old('phone') ?? ($customer->phone ?? null) }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 d-flex flex-column justify-content-start">
                    <!-- Order summary -->
                    <div class="card border-0 mt-3 mt-lg-0 shadow-sm">
                        <div class="card-header"><div class="font-weight-medium py-1">{{ __('Order summary') }}</div></div>
                        <div class="card-body">
                            <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                                <label class="btn btn-outline-dark w-100{{ request()->period != 'yearly' ? ' active' : ''}}" id="plan-monthly">
                                    <input type="radio" name="interval" data-period-url="{{ route('checkout.collect', ['period' => 'monthly']) }}" data-form-url="{{ route('checkout.collect', ['period' => 'monthly']) }}" value="0" @if(request()->period != 'yearly') checked="checked" @endif>{{ __('Monthly') }}
                                </label>
                                <label class="btn btn-outline-dark w-100{{ request()->period == 'yearly' ? ' active' : ''}}" id="plan-yearly">
                                    <input type="radio" name="interval" data-plan-price="{{ formatMoney($plan->amount_year, $plan->currency) }}" data-period-url="{{ route('checkout.collect', ['period' => 'yearly']) }}" data-form-url="{{ route('checkout.collect', ['period' => 'yearly']) }}" value="1" @if(request()->period == 'yearly') checked="checked" @endif>{{ __('Yearly') }}

                                    @if(($plan->amount_month*12) > $plan->amount_year)
                                        <span class="badge bg-success text-white">-{{ number_format(((($plan->amount_month*12) - $plan->amount_year)/($plan->amount_month*12) * 100), 0) }}%</span>
                                    @endif
                                </label>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <!-- Plan description -->
                                <li class="list-group-item pt-0">
                                    <div class="row">
                                        <div class="col">
                                            <div>{{ __(':name plan', ['name' => $plan->name]) }}</div>

                                            <div class="d-none checkout-monthly">
                                                <div class="small text-muted">{!! ($plan->trial_days && count($user->subscriptions) == 0) ? __('Billed :period, after trial ends.', ['period' => mb_strtolower(__('Monthly'))]) :__('Billed :period.', ['period' => mb_strtolower(__('Monthly'))]) !!}</div>
                                            </div>
                                            <div class="d-none checkout-yearly">
                                                <div class="small text-muted">{!! ($plan->trial_days && count($user->subscriptions) == 0) ? __('Billed :period, after trial ends.', ['period' => mb_strtolower(__('Yearly'))]) :__('Billed :period.', ['period' => mb_strtolower(__('Yearly'))]) !!}</div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="d-none checkout-monthly">
                                                {{ formatMoney($plan->amount_month, $plan->currency) }}</span> <span class="text-muted">{{ strtoupper($plan->currency) }}</span>
                                            </div>
                                            <div class="d-none checkout-yearly">
                                                {{ formatMoney($plan->amount_year, $plan->currency) }} <span class="text-muted">{{ strtoupper($plan->currency) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <!-- If the plan is on trial, and the user is not currently subscribed -->
                                @if($plan->trial_days && count($user->subscriptions) == 0)
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col">
                                                <div>{{ __('Trial days') }}</div>
                                            </div>
                                            <div class="col-auto">
                                                {{ $plan->trial_days }}
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="card-footer font-weight-bold">
                            <div class="row">
                                <div class="col">
                                    <span>{{ __('Total') }}</span>
                                </div>
                                <div class="col-auto text-muted font-weight-normal">
                                    {{ __('Calculated at checkout') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Agreement -->
                    <div class="mt-3">
                        <span class="small text-muted">{!! __('By continuing, you agree to save the payment method and billing information in your :title account.', ['title' => '<strong>'.e(config(('settings.title'))).'</strong>']) !!}</span>
                    </div>

                    <!-- Next button -->
                    <button type="submit" id="i_save" data-secret="{{ $intent->client_secret }}" class="btn btn-primary btn-block my-3"><span id="save-text" class="d-inline-flex align-items-center">{{ __('Next') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron_left' : 'icons.chevron_right'), ['class' => 'icon-chevron fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</span><span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="save-loader"></span></button>

                    <!-- Contact helper -->
                    <div class="d-block mt-lg-auto">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body d-flex">
                                <div class="d-flex justify-content-center">@include('icons.background.contact', ['class' => 'text-primary fill-current icon-card '.(__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3')])</div>
                                <div>
                                    <div class="text-dark font-weight-medium">{{ __('Need help?') }}</div>

                                    <a href="{{ route('contact') }}" class="text-secondary text-decoration-none stretched-link">{{ __('Get in touch with us.') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    (function () {
        'use strict';

        const stripe = Stripe('{{ config('cashier.key') }}');

        const elements = stripe.elements({
            locale: 'auto'
        });

        const cardElement = elements.create('card', {
            hidePostalCode: true,
            iconStyle: 'default',
            style: {
                base: {
                    fontSize: '16px',
                    fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif'
                }
            }
        });

        // The billing form
        const form = document.getElementById('payment-form');

        cardElement.mount('#i_card_number');

        const cardHolderName = document.getElementById('i_name');
        const cardHolderCardNumber = document.getElementById('i_card_number');
        const cardButton = document.getElementById('i_save');
        const clientSecret = cardButton.dataset.secret;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            // Disable the save button to avoid the form being submitted twice
            document.querySelector('#i_save').disabled = true;

            // Hide the Save text
            document.querySelector('#save-text').classList.add('d-none');
            document.querySelector('#save-text').classList.remove('d-inline-flex');

            // Show the loading animation
            document.querySelector('#save-loader').classList.remove('d-none');

            // Remove previous invalid state if any
            cardHolderCardNumber.classList.remove('is-invalid');
            cardHolderName.classList.remove('is-invalid');

            const {setupIntent, error} = await stripe.handleCardSetup(
                clientSecret, cardElement, {
                    payment_method_data: {
                    }
                }
            );

            if (error) {
                // Re-enable the save button if there's an error
                document.getElementById("i_save").disabled = false;

                // Show the error message
                cardHolderCardNumber.classList.add('is-invalid');
                document.querySelector('#card-error strong').textContent = error.message;

                // Show the save text
                document.querySelector('#save-text').classList.remove('d-none');
                document.querySelector('#save-text').classList.add('d-inline-flex');

                // Hide the loading animation
                document.querySelector('#save-loader').classList.add('d-none');
            } else {
                // Set the payment method input value to the payment method id
                document.getElementById('payment_method').value = setupIntent.payment_method;

                form.submit();
            }
        });

        /**
         * Checks if the country input has a value
         * @returns {boolean}
         */
        function isCountry() {
            if (document.getElementById("i_country").value) {
                return true;
            }
        }
    })();
</script>
@include('shared.sidebars.user')
@endsection