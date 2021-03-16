@if(config('settings.legal_cookie_url'))
    @if(Cookie::get('cookie_law') == 0)
        <div class="fixed-bottom pointer-events-none">
            <div class="d-flex justify-content-center align-items-center">
                <div class="border-0 mt-0 mr-3 mb-3 ml-3 p-2 rounded cookie-banner pointer-events-auto" id="cookie_banner">
                    <div class="row align-items-center p-1">
                        <div class="col">
                            {!! __('By using this website, you agree to our :url.', ['url' => '<a href="'. config('settings.legal_cookie_url') .'">'. __('Cookie Policy') .'</a>']) !!}
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-outline-primary btn-sm" id="cookie_banner_dismiss">{{ __('OK') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            window.addEventListener('DOMContentLoaded', function () {
                'use strict';

                document.querySelector('#cookie_banner_dismiss').addEventListener('click', function(e) {
                    setCookie('cookie_law', 1, new Date().getTime() + (10 * 365 * 24 * 60 * 60 * 1000), '/');
                    document.querySelector('#cookie_banner').classList.add('d-none');
                });
            });
        </script>
    @endif
@endif