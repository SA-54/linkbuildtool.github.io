@extends('layouts.invoice')

@section('site_title', formatTitle([__('Invoice'), config('settings.title')]))

@section('content')
<div class="bg-base-1 flex-fill">
    <div class="container p-3 mx-auto">

        <div class="invoice-container d-print-none">
            <div class="row no-gutters">
                <div class="col">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">{{ __('Back') }}</a>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary" onclick="window.print();">{{ __('Print') }}</button>
                </div>
            </div>
        </div>

        <div class="bg-base-0 border rounded p-5 my-3 invoice-container">
            <div class="row">
                <div class="col-8">
                    <!-- Organization Details -->
                    <h3>{{ config('settings.title') }}</h3>
                </div>
                <div class="col-4 {{ (__('lang_dir') == 'rtl' ? 'text-left' : 'text-right') }}">
                    <h3 class="text-uppercase text-muted">{{ __('Invoice') }}</h3>
                </div>
                <div class="col-12 py-3">
                    <div><span class="text-muted">{{ __('Date') }}:</span> {{ $invoice->date()->format(__('Y-m-d')) }}</div>
                    <div><span class="text-muted">{{ __('Product') }}:</span> {{ $product }}</div>
                    <div><span class="text-muted">{{ __('Invoice number') }}:</span> {{ $invoice->number }}</div>
                </div>
            </div>

            <div class="row py-3">
                <div class="col-6">
                    <h5>{{ __('Vendor') }}</h5>
                    <div><span class="text-muted">{{ __('Name') }}:</span> {{ config('settings.invoice_vendor') ?? config('settings.title') }}</div>

                    @if (config('settings.invoice_address'))
                        <div><span class="text-muted">{{ __('Address') }}:</span> {{ config('settings.invoice_address') }}</div>
                    @endif

                    @if (config('settings.invoice_city'))
                        <div><span class="text-muted">{{ __('City') }}:</span> {{ config('settings.invoice_city') }}</div>
                    @endif

                    @if (config('settings.invoice_state'))
                        <div><span class="text-muted">{{ __('State') }}:</span> {{ config('settings.invoice_state') }}</div>
                    @endif

                    @if (config('settings.invoice_postal_code'))
                        <div><span class="text-muted">{{ __('Postal code') }}:</span> {{ config('settings.invoice_postal_code') }}</div>
                    @endif

                    @if (config('settings.invoice_country'))
                        <div><span class="text-muted">{{ __('Country') }}:</span> {{ config('settings.invoice_country') }}</div>
                    @endif

                    @if (config('settings.invoice_phone'))
                        <div><span class="text-muted">{{ __('Phone') }}:</span> {{ config('settings.invoice_phone') }}</div>
                    @endif

                    @if (config('settings.invoice_vat_number'))
                        <div><span class="text-muted">{{ __('VAT number') }}:</span> {{ config('settings.invoice_vat_number') }}</div>
                    @endif
                </div>
                <div class="col-6">
                    <h5>{{ __('Client') }}</h5>
                    @if($invoice->customer_name)
                        <div><span class="text-muted">{{ __('Name') }}:</span> {{ $invoice->customer_name }}</div>
                    @endif

                    @if ($invoice->customer_address->line1)
                        <div><span class="text-muted">{{ __('Address') }}:</span> {{ $invoice->customer_address->line1 }}</div>
                    @endif

                    @if ($invoice->customer_address->city)
                        <div><span class="text-muted">{{ __('City') }}:</span> {{ $invoice->customer_address->city }}</div>
                    @endif

                    @if ($invoice->customer_address->state)
                        <div><span class="text-muted">{{ __('State') }}:</span> {{ $invoice->customer_address->state }}</div>
                    @endif

                    @if ($invoice->customer_address->postal_code)
                        <div><span class="text-muted">{{ __('Postal code') }}:</span> {{ $invoice->customer_address->postal_code }}</div>
                    @endif

                    @if ($invoice->customer_address->country)
                        <div><span class="text-muted">{{ __('Country') }}:</span> {{ $invoice->customer_address->country }}</div>
                    @endif

                    @if ($invoice->customer_phone)
                        <div><span class="text-muted">{{ __('Phone') }}:</span> {{ $invoice->customer_phone }}</div>
                    @endif

                    <div class="form-group mt-3">
                        <label for="i_notes" class="d-print-none">{{ __('Notes') }}</label>
                        <textarea name="notes" id="i_notes" class="form-control"></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0">
                            <div class="row font-weight-medium">
                                <div class="col-6">{{ __('Description') }}</div>
                                <div class="col-3">{{ __('Date') }}</div>
                                <div class="col-3">{{ __('Amount') }}</div>
                            </div>
                        </div>

                        <!-- Display The Invoice Items -->
                        @foreach ($invoice->invoiceItems() as $item)
                            <div class="list-group-item px-0">
                                <div class="row">
                                    <div class="col-6">{{ $item->description }}</div>
                                    <div class="col-3"></div>
                                    <div class="col-3">{{ formatMoney($item->amount, $invoice->currency) }} {{ strtoupper($invoice->currency) }}</div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Display The Subscriptions -->
                        @foreach ($invoice->subscriptions() as $subscription)
                            <div class="list-group-item px-0">
                                <div class="row">
                                    <div class="col-6">{{ __('Subscription') }} ({{ $subscription->quantity }})</div>
                                    <div class="col-3">
                                        <div>{{ $subscription->startDateAsCarbon()->tz(Auth::user()->timezone ?? config('app.timezone'))->format(__('Y-m-d')) }}</div>
                                        <div>{{ $subscription->endDateAsCarbon()->tz(Auth::user()->timezone ?? config('app.timezone'))->format(__('Y-m-d')) }}</div>
                                    </div>
                                    <div class="col-3">{{ formatMoney($subscription->amount, $invoice->currency) }} {{ strtoupper($invoice->currency) }}</div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Display The Subtotal -->
                        @if ($invoice->hasDiscount() || $invoice->tax_percent || $invoice->hasStartingBalance())
                            <div class="list-group-item px-0">
                                <div class="row">
                                    <div class="col-9 {{ (__('lang_dir') == 'rtl' ? 'text-left' : 'text-right') }}">{{ __('Subtotal') }}</div>
                                    <div class="col-3">{{ formatMoney($invoice->subtotal, $invoice->currency) }} {{ strtoupper($invoice->currency) }}</div>
                                </div>
                            </div>
                        @endif

                        <!-- Display The Discount -->
                        @if ($invoice->hasDiscount())
                            <div class="list-group-item px-0">
                                <div class="row">
                                    <div class="col-9 {{ (__('lang_dir') == 'rtl' ? 'text-left' : 'text-right') }}">
                                        @if ($invoice->discountIsPercentage())
                                            {{ $invoice->coupon() }} ({{ $invoice->percentOff() }}% {{ __('Discount') }})
                                        @else
                                            {{ $invoice->coupon() }} ({{ formatMoney($invoice->discount->coupon->amount_off, $invoice->currency) }} {{ strtoupper($invoice->currency) }} {{ __('Discount') }})
                                        @endif
                                    </div>
                                    <div class="col-3">-{{ formatMoney((int) round($invoice->subtotal * ($invoice->percentOff() / 100)), $invoice->currency) }} {{ strtoupper($invoice->currency) }}</div>
                                </div>
                            </div>
                        @endif

                        <!-- Display The Taxes -->
                        @unless ($invoice->isNotTaxExempt())
                            <tr>
                                <td colspan="{{ $invoice->hasTax() ? 3 : 2 }}" style="text-align: right;">
                                    @if ($invoice->isTaxExempt())
                                        {{ __('Tax is exempted') }}
                                    @else
                                        {{ __('Tax to be paid on reverse charge basis') }}
                                    @endif
                                </td>
                                <td></td>
                            </tr>
                        @else
                            @foreach ($invoice->taxes() as $tax)
                                <div class="list-group-item px-0">
                                    <div class="row">
                                        <div class="col-9 {{ (__('lang_dir') == 'rtl' ? 'text-left' : 'text-right') }}">{{ $tax->display_name }} {{ $tax->jurisdiction ? ' - ' . $tax->jurisdiction : '' }} ({{ $tax->percentage }}% {{ $tax->inclusive ? __('incl.') : __('excl.') }})</div>

                                        <div class="col-3">{{ formatMoney($tax->rawAmount(), $invoice->currency) }} {{ strtoupper($invoice->currency) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        @endunless

                        <!-- Starting Balance -->
                        @if ($invoice->hasStartingBalance())
                            <div class="list-group-item px-0">
                                <div class="row">
                                    <div class="col-9 {{ (__('lang_dir') == 'rtl' ? 'text-left' : 'text-right') }}">{{ __('Customer balance') }}</div>
                                    <div class="col-3">{{ formatMoney($invoice->rawStartingBalance(), $invoice->currency) }} {{ strtoupper($invoice->currency) }}</div>
                                </div>
                            </div>
                        @endif

                        <!-- Display The Final Total -->
                        <div class="list-group-item px-0">
                            <div class="row">
                                <div class="col-9 {{ (__('lang_dir') == 'rtl' ? 'text-left' : 'text-right') }} font-weight-bold">{{ __('Total') }}</div>
                                <div class="col-3 font-weight-bold">{{ formatMoney($invoice->rawTotal(), $invoice->currency) }} {{ strtoupper($invoice->currency) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection