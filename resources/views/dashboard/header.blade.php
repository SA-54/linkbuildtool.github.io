<div class="bg-base-0">
    <div class="container py-5">
        <div class="d-flex">
            <div class="row no-gutters w-100">
                <div class="d-flex col-12 col-md">
                    <div class="flex-shrink-1">
                        <a href="{{ route('settings') }}" class="d-block"><img src="{{ gravatar($user->email, 128) }}" class="rounded-circle dashboard-user-image"></a>
                    </div>
                    <div class="flex-grow-1 d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-3' : 'ml-3') }}">
                        <div>
                            <h4 class="font-weight-medium mb-0">{{ $user->name }}</h4>

                            <div class="text-muted mt-2">
                                @if(config('settings.stripe'))
                                    @forelse($subscriptions as $subscription)
                                        <div class="d-inline-block {{ (__('lang_dir') == 'rtl' ? 'ml-4' : 'mr-4') }}">
                                            <div class="d-flex">
                                                <div class="d-inline-flex align-items-center">
                                                    @include('icons.package', ['class' => 'fill-current icon-dashboard'])
                                                </div>

                                                <div class="d-inline-block {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}">
                                                    <a href="{{ route('settings.payments.subscriptions.edit', $subscription->id) }}" class="text-dark text-decoration-none">{{ $subscription->name }} <span class="badge badge-{{ formatStripeStatus()[$subscription->stripe_status]['status'] }}">{{ formatStripeStatus()[$subscription->stripe_status]['title'] }}</span></a>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="d-inline-block {{ (__('lang_dir') == 'rtl' ? 'ml-4' : 'mr-4') }}">
                                            <div class="d-flex">
                                                <div class="d-inline-flex align-items-center">
                                                    @include('icons.package', ['class' => 'fill-current icon-dashboard'])
                                                </div>

                                                <div class="d-inline-block {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}">
                                                    <a href="{{ route('pricing') }}" class="text-dark text-decoration-none">{{ $plan->name }} <span class="badge badge-primary">{{ __('Free') }}</span></a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforelse
                                @else
                                    <div class="d-inline-block {{ (__('lang_dir') == 'rtl' ? 'ml-4' : 'mr-4') }}">
                                        <div class="d-flex">
                                            <div class="d-inline-flex align-items-center">
                                                @include('icons.email', ['class' => 'fill-current icon-dashboard'])
                                            </div>

                                            <div class="d-inline-block {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}">
                                                {{ $user->email }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if(empty($subscriptions) && config('settings.stripe'))
                    <div class="col-12 col-md-auto d-flex flex-row-reverse align-items-center">
                        <a href="{{ route('pricing') }}" class="btn btn-outline-primary btn-block d-flex justify-content-center align-items-center mt-4 mt-md-0 {{ (__('lang_dir') == 'rtl' ? 'ml-md-3' : 'mr-md-3') }}">@include('icons.package_up', ['class' => 'icon-button fill-current '.(__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2')]){{ __('Upgrade') }}</a>
                    </div>
                @endif

                <div class="col-12 col-md-auto d-flex flex-row-reverse align-items-center">
                    <a href="{{ route('links') }}" class="btn btn-primary btn-block d-flex justify-content-center align-items-center mt-4 mt-md-0">@include('icons.add', ['class' => 'icon-button fill-current '.(__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2')]){{ __('New link') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>