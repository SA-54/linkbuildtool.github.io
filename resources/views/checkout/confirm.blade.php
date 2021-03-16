@extends('layouts.app')

@section('site_title', formatTitle([__('Confirm payment'), config('settings.title')]))

@section('content')
<div class="bg-base-1 flex-fill">
    <div class="container py-3 my-3">
        @include('shared.breadcrumbs', ['breadcrumbs' => [
            ['url' => route('dashboard'), 'title' => __('Home')],
            ['url' => route('pricing'), 'title' => __('Pricing')],
            ['title' => __('Confirm payment')],
        ]])

        <h2 class="mb-0 d-inline-block">{{ __('Confirm payment') }}</h2>

        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-body">
                        @include('shared.message')

                        <form id="billing-form" action="{{ route('checkout.complete') }}">
                            @csrf

                            <div class="alert alert-warning" role="alert">
                                {{ __('Extra confirmation is needed to process your payment. Please confirm your payment by filling out your payment details below.') }}
                            </div>

                            <div class="form-group">
                                <label for="i_name">{{ __('Name') }}</label>
                                <input type="text" name="name" id="i_name" class="form-control">

                                <span class="invalid-feedback" role="alert" id="name-error">
                                    <strong></strong>
                                </span>
                            </div>

                            <div class="form-group">
                                <label for="i_card_number">{{ __('Card number') }}</label>
                                <div id="i_card_number" class="form-control" style="height: 2.4em; padding-top: .5em;"></div>

                                <span class="invalid-feedback" role="alert" id="card-error">
                                    <strong></strong>
                                </span>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <button type="submit" id="i_pay" data-secret="{{ $payment->clientSecret() }}" class="btn btn-success d-inline-flex align-items-center"><span id="save-text">{{ __('Pay :amount :currency', ['amount' => formatMoney($payment->amount, $payment->currency), 'currency' => strtoupper($payment->currency)]) }}</span><span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="save-loader"></span>&#8203;</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4 d-flex flex-column justify-content-start">
                <!-- Contact helper -->
                <div class="text-decoration-none d-block mt-3">
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
        const form = document.getElementById('billing-form');

        cardElement.mount('#i_card_number');

        const cardHolderName = document.getElementById('i_name');
        const cardHolderCardNumber = document.getElementById('i_card_number');
        const cardButton = document.getElementById('i_pay');
        const clientSecret = cardButton.dataset.secret;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            // Disable the save button to avoid the form being submitted twice
            document.querySelector('#i_pay').disabled = true;

            // Hide the Save text
            document.querySelector('#save-text').classList.add('d-none');

            // Show the loading animation
            document.querySelector('#save-loader').classList.remove('d-none');

            // Remove previous invalid state if any
            cardHolderCardNumber.classList.remove('is-invalid');
            cardHolderName.classList.remove('is-invalid');

            const {paymentIntent, error} = await stripe.handleCardPayment(
                clientSecret, cardElement, {
                    payment_method_data: {
                        billing_details: {
                            name: cardHolderName.value
                        }
                    }
                }
            );

            if (error) {
                // Re-enable the save button if there's an error
                document.getElementById("i_pay").disabled = false;

                if (error.code === 'parameter_invalid_empty' &&
                    error.param === 'payment_method_data[billing_details][name]') {
                    document.querySelector('#name-error strong').textContent = '{{ __('validation.required', ['attribute' => 'name']) }}';
                    cardHolderName.classList.add('is-invalid');
                } else {
                    // Show the error message
                    cardHolderCardNumber.classList.add('is-invalid');
                    document.querySelector('#card-error strong').textContent = error.message;
                }

                // Show the save text
                document.querySelector('#save-text').classList.remove('d-none');

                // Hide the loading animation
                document.querySelector('#save-loader').classList.add('d-none');
            } else {
                window.location.replace('{{ route('checkout.complete') }}');
            }
        });
    })();
</script>
@include('shared.sidebars.user')
@endsection