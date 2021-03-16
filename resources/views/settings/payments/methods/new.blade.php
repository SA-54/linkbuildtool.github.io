@section('site_title', formatTitle([__('New'), __('Payment method'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('dashboard'), 'title' => __('Home')],
    ['url' => route('settings'), 'title' => __('Settings')],
    ['url' => route('settings.payments.methods'), 'title' => __('Payment methods')],
    ['title' => __('New')],
]])

<h2 class="mb-3 d-inline-block">{{ __('New') }}</h2>

<div class="card border-0 shadow-sm">
    <div class="card-header">
        <div class="font-weight-medium py-1">{{ __('Payment method') }}</div>
    </div>
    <div class="card-body">
        @include('shared.message')

        <form id="billing-form" action="{{ route('settings.payments.methods.new') }}" method="post">
            @csrf

            <div class="form-group">
                <label for="i_card_number">{{ __('Card number') }}</label>
                <div id="i_card_number" class="form-control" style="height: 2.4em; padding-top: .5em;"></div>
                <span class="invalid-feedback" id="card-error" role="alert">
                    <strong></strong>
                </span>
            </div>

            @if($hasDefaultPaymentMethod)
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="default" id="i_default" value="1" checked>
                    <label class="custom-control-label" for="i_default">{{ __('Default') }}</label>
                </div>
            </div>
            @endif

            <input type="hidden" id="payment_method" name="payment_method">

            <button type="submit" id="i_save" data-secret="{{ $intent->client_secret }}" class="btn btn-primary d-inline-flex align-items-center"><span id="save-text">{{ __('Save') }}</span><span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="save-loader"></span>&#8203;</button>
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
        const form = document.getElementById('billing-form');

        cardElement.mount('#i_card_number');

        const cardHolderCardNumber = document.getElementById('i_card_number');
        const cardButton = document.getElementById('i_save');
        const clientSecret = cardButton.dataset.secret;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            // Disable the save button to avoid the form being submitted twice
            document.querySelector('#i_save').disabled = true;

            // Hide the Save text
            document.querySelector('#save-text').classList.add('d-none');

            // Show the loading animation
            document.querySelector('#save-loader').classList.remove('d-none');

            // Remove previous invalid state if any
            cardHolderCardNumber.classList.remove('is-invalid');

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