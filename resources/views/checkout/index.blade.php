@extends('layouts.app')

@section('site_title', formatTitle([__('Checkout'), config('settings.title')]))

@section('content')
<div class="bg-base-1 flex-fill">
    <div class="container py-3 my-3">

        @include('shared.breadcrumbs', ['breadcrumbs' => [
            ['url' => route('dashboard'), 'title' => __('Home')],
            ['url' => route('pricing'), 'title' => __('Pricing')],
            ['title' => __('Checkout')],
        ]])

        <h2 class="mb-3 d-inline-block">{{ __('Checkout') }}</h2>

        @include('shared.message')

        <form action="{{ route('checkout.subscribe', ['id' => $plan->id, 'period' => request()->period]) }}" method="post" enctype="multipart/form-data" id="payment-form">
            @csrf
            <div class="row">
                <div class="col-12 col-lg-8">
                    <!-- Payment method -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <div class="font-weight-medium py-1">{{ __('Payment method') }}</div>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('settings.payments.methods') }}" class="btn btn-outline-primary btn-sm">{{ __('Change') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-0">
                                <label for="i_card_number">{{ __('Card number') }}</label>
                                <div class="input-group flex-nowrap">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="addon-wrapping"><div class="d-flex align-items-center payment-icon">@include('icons.payments.' . (in_array($paymentMethod->card->brand, config('payments')) ? $paymentMethod->card->brand : 'unknown'))</div></span>
                                    </div>
                                    <input id="i_card_number" name="card-number" type="text" class="form-control" value="•••• {{ $paymentMethod->card->last4 }}" disabled>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><span class="card-expiry">{{ date('m / y', strtotime('01-'.$paymentMethod->card->exp_month.'-'.$paymentMethod->card->exp_year)) }}</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Billing information -->
                    <div class="card border-0 mt-3 shadow-sm">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <div class="font-weight-medium py-1">{{ __('Billing information') }}</div>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('settings.payments.billing') }}" class="btn btn-outline-primary btn-sm">{{ __('Change') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="i_name">{{ __('Name') }}</label>
                                <input type="text" name="name" id="i_name" class="form-control" value="{{ $customer->name ?? null}}" disabled>
                            </div>

                            <div class="form-group">
                                <label for="i_address">{{ __('Address') }}</label>
                                <input type="text" name="address" id="i_address" class="form-control" value="{{ $customer->address->line1 ?? null }}" disabled>
                            </div>

                            <div class="form-row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="i_city">{{ __('City') }}</label>
                                        <input type="text" name="city" id="i_city" class="form-control" value="{{ $customer->address->city ?? null }}" disabled>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label for="i_state">{{ __('State') }}</label>
                                        <input type="text" name="state" id="i_state" class="form-control" value="{{ $customer->address->state ?? null }}" disabled>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label for="i_postal_code">{{ __('Postal code') }}</label>
                                        <input type="text" name="postal_code" id="i_postal_code" class="form-control" value="{{ $customer->address->postal_code ?? null }}" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="i_country">{{ __('Country') }}</label>
                                <input type="text" name="country" id="i_country" class="form-control" value="{{ config('countries')[$customer->address->country] ?? null }}" disabled>
                            </div>

                            <div class="form-group mb-0">
                                <label for="i_phone">{{ __('Phone') }}</label>
                                <input type="tel" name="phone" id="i_phone" class="form-control" value="{{ $customer->phone ?? null}}" disabled>
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
                                    <input type="radio" name="interval" data-period-url="{{ route('checkout.index', ['id' => $plan->id, 'period' => 'monthly']) }}" data-form-url="{{ route('checkout.subscribe', ['id' => $plan->id, 'period' => 'monthly']) }}" value="0" @if(request()->period != 'yearly') checked="checked" @endif>{{ __('Monthly') }}
                                </label>
                                <label class="btn btn-outline-dark w-100{{ request()->period == 'yearly' ? ' active' : ''}}" id="plan-yearly">
                                    <input type="radio" name="interval" data-period-url="{{ route('checkout.index', ['id' => $plan->id, 'period' => 'yearly']) }}" data-form-url="{{ route('checkout.subscribe', ['id' => $plan->id, 'period' => 'yearly']) }}" value="1" @if(request()->period == 'yearly') checked="checked" @endif>{{ __('Yearly') }}

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

                                <!-- Discount -->
                                @if($coupon)
                                    <li class="list-group-item text-success">
                                        <div class="row">
                                            <div class="col">
                                                <div>{{ __('Discount') }} ({{ $coupon->percent_off }}%)</div>
                                            </div>
                                            <div class="col-auto">
                                                <span class="d-none checkout-monthly">
                                                    -{{ formatMoney(checkoutDiscount($plan->amount_month, $coupon->percent_off), $plan->currency) }}
                                                </span>
                                                <span class="d-none checkout-yearly">
                                                    -{{ formatMoney(checkoutDiscount($plan->amount_year, $coupon->percent_off), $plan->currency) }}
                                                </span>
                                                <span class="text-muted">{{ strtoupper($plan->currency) }}</span>
                                            </div>
                                        </div>
                                        <input type="hidden" name="coupon_id" value="{{ $coupon->id }}">
                                    </li>
                                @endif

                                <!-- Tax rates -->
                                @foreach($taxRates as $tax)
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col">
                                                <div>{{ $tax->display_name }} {{ $tax->jurisdiction ? ' - ' . $tax->jurisdiction : '' }} ({{ $tax->percentage }}% {{ $tax->inclusive ? __('incl.') : __('excl.') }})</div>
                                            </div>
                                            <div class="col-auto">
                                                @if($tax->inclusive == false)
                                                    <span class="d-none checkout-monthly">
                                                        {{ formatMoney(checkoutExclusiveTax(checkoutPostDiscountLessInclTax(checkoutPostDiscount($plan->amount_month, checkoutDiscount($plan->amount_month, $coupon->percent_off ?? null)), checkoutInclusiveTax(checkoutPostDiscount($plan->amount_month, checkoutDiscount($plan->amount_month, $coupon->percent_off ?? null)), $inclTaxRatesPercentage ?? null)), $tax->percentage ?? null), $plan->currency) }}
                                                    </span>
                                                    <span class="d-none checkout-yearly">
                                                        {{ formatMoney(checkoutExclusiveTax(checkoutPostDiscountLessInclTax(checkoutPostDiscount($plan->amount_year, checkoutDiscount($plan->amount_year, $coupon->percent_off ?? null)), checkoutInclusiveTax(checkoutPostDiscount($plan->amount_year, checkoutDiscount($plan->amount_year, $coupon->percent_off ?? null)), $inclTaxRatesPercentage ?? null)), $tax->percentage ?? null), $plan->currency) }}
                                                    </span>
                                                @else
                                                    <span class="d-none checkout-monthly">
                                                        {{ formatMoney(checkoutPostDiscountLessInclTax(checkoutPostDiscount($plan->amount_month, checkoutDiscount($plan->amount_month, $coupon->percent_off ?? null)), checkoutInclusiveTax(checkoutPostDiscount($plan->amount_month, checkoutDiscount($plan->amount_month, $coupon->percent_off ?? null)), $inclTaxRatesPercentage ?? null)) * ($tax->percentage / 100), $plan->currency) }}
                                                    </span>
                                                    <span class="d-none checkout-yearly">
                                                        {{ formatMoney(checkoutPostDiscountLessInclTax(checkoutPostDiscount($plan->amount_year, checkoutDiscount($plan->amount_year, $coupon->percent_off ?? null)), checkoutInclusiveTax(checkoutPostDiscount($plan->amount_year, checkoutDiscount($plan->amount_year, $coupon->percent_off ?? null)), $inclTaxRatesPercentage ?? null)) * ($tax->percentage / 100), $plan->currency) }}
                                                    </span>
                                                @endif

                                                <span class="text-muted">{{ strtoupper($plan->currency) }}</span>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach

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

                                <!-- Coupons -->
                                @if($plan->coupons && !$coupon)
                                    <li class="list-group-item">
                                        <a href="#" id="coupon" class="{{ $errors->has('coupon') ? 'd-none' : '' }}">{{ __('Have a coupon code?') }}</a>

                                        <div class="form-row {{ $errors->has('coupon') ? 'd-flex' : 'd-none' }}" id="coupon-input">
                                            <div class="col">
                                                <div class="form-group mb-0">
                                                    <input type="text" name="coupon" id="i_coupon" class="form-control form-control-sm{{ $errors->has('coupon') ? ' is-invalid' : '' }}" value="{{ old('coupon') }}" placeholder="{{ __('Coupon code') }}"{{ $errors->has('coupon') ? '' : ' disabled' }}>
                                                    @if ($errors->has('coupon'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('coupon') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-auto">
                                                <button type="submit" class="btn btn-primary btn-sm">{{ __('Apply') }}</button>
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
                                <div class="col-auto">
                                    <span class="d-none checkout-monthly">
                                        {{ formatMoney(checkoutTotal(checkoutPostDiscount($plan->amount_month, checkoutDiscount($plan->amount_month, $coupon->percent_off ?? null)), checkoutExclusiveTax(checkoutPostDiscountLessInclTax(checkoutPostDiscount($plan->amount_month, checkoutDiscount($plan->amount_month, $coupon->percent_off ?? null)), checkoutInclusiveTax(checkoutPostDiscount($plan->amount_month, checkoutDiscount($plan->amount_month, $coupon->percent_off ?? null)), $inclTaxRatesPercentage ?? null)), $exclTaxRatesPercentage ?? null)), $plan->currency) }}
                                    </span>
                                    <span class="d-none checkout-yearly">
                                        {{ formatMoney(checkoutTotal(checkoutPostDiscount($plan->amount_year, checkoutDiscount($plan->amount_year, $coupon->percent_off ?? null)), checkoutExclusiveTax(checkoutPostDiscountLessInclTax(checkoutPostDiscount($plan->amount_year, checkoutDiscount($plan->amount_year, $coupon->percent_off ?? null)), checkoutInclusiveTax(checkoutPostDiscount($plan->amount_year, checkoutDiscount($plan->amount_year, $coupon->percent_off ?? null)), $inclTaxRatesPercentage ?? null)), $exclTaxRatesPercentage ?? null)), $plan->currency) }}
                                    </span>
                                    <span>
                                        {{ $plan->currency }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Agreement -->
                    <div class="mt-3">
                        <span class="small text-muted">{!! __('By continuing, you agree with the :tos and authorize :title to charge your payment method on a recurring basis.', ['tos' => '<a href="'.config('settings.legal_terms_url').'" target="_blank">'. __('Terms of Service') .'</a>', 'title' => '<strong>'.e(config(('settings.title'))).'</strong>']) !!} {{ __('You can cancel your subscription at any time.') }}</span>
                    </div>

                    <!-- Pay button -->
                    <button type="submit" name="submit" class="btn btn-success btn-block my-3">
                        <span class="d-none checkout-monthly">
                            {{ __('Pay :amount :currency', ['amount' => formatMoney(checkoutTotal(checkoutPostDiscount($plan->amount_month, checkoutDiscount($plan->amount_month, $coupon->percent_off ?? null)), checkoutExclusiveTax(checkoutPostDiscountLessInclTax(checkoutPostDiscount($plan->amount_month, checkoutDiscount($plan->amount_month, $coupon->percent_off ?? null)), checkoutInclusiveTax(checkoutPostDiscount($plan->amount_month, checkoutDiscount($plan->amount_month, $coupon->percent_off ?? null)), $inclTaxRatesPercentage ?? null)), $exclTaxRatesPercentage ?? null)), $plan->currency), 'currency' => e($plan->currency)]) }}
                        </span>
                        <span class="d-none checkout-yearly">
                            {{ __('Pay :amount :currency', ['amount' => formatMoney(checkoutTotal(checkoutPostDiscount($plan->amount_year, checkoutDiscount($plan->amount_year, $coupon->percent_off ?? null)), checkoutExclusiveTax(checkoutPostDiscountLessInclTax(checkoutPostDiscount($plan->amount_year, checkoutDiscount($plan->amount_year, $coupon->percent_off ?? null)), checkoutInclusiveTax(checkoutPostDiscount($plan->amount_year, checkoutDiscount($plan->amount_year, $coupon->percent_off ?? null)), $inclTaxRatesPercentage ?? null)), $exclTaxRatesPercentage ?? null)), $plan->currency), 'currency' => e($plan->currency)]) }}
                        </span>
                    </button>

                    <!-- Contact helper -->
                    <div class="text-decoration-none d-block mt-lg-auto">
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
@include('shared.sidebars.user')
@endsection