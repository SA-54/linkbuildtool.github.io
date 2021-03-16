<div class="d-block d-lg-inline-flex text-secondary text-decoration-none" id="dark-theme" data-toggle="tooltip" title="{{ __('Change theme') }}">
    <div class="d-flex align-items-center">
        <label class="mb-0 cursor-pointer d-flex py-1" for="dark-switch"><span class="d-flex align-items-center">@include('icons.daynight', ['class' => 'fill-current icon-text '.(__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2')])</span><span class="flex-grow-1"><span class="text-muted">{{ config('settings.dark_mode') == 1 ? __('Dark') : __('Light') }}</span></span></label>
    </div>
    <div class="d-none {{ (__('lang_dir') == 'rtl' ? 'mr-auto' : 'ml-auto') }}">
        <div class="custom-control custom-switch d-inline-block {{ (__('lang_dir') == 'rtl' ? 'ml-n2' : 'mr-n2') }}">
            <input type="checkbox" class="custom-control-input" id="dark-switch" {{ (Cookie::get('dark_mode') == 1 ? ' checked' : '') }}>
            <label class="custom-control-label cursor-pointer" for="dark-switch"></label>
        </div>
    </div>
</div>

<script>
    window.addEventListener('DOMContentLoaded', function () {
        'use strict';

        // Change the css file on input checkbox change
        document.querySelector('#dark-switch') && document.querySelector('#dark-switch').addEventListener('change', function(e) {
            const appCss = document.querySelector('#app-css');

            if(e.target.checked) {
                appCss.setAttribute('href', '{{ asset('css/app'. (__('lang_dir') == 'rtl' ? '.rtl' : '') .'.dark.css') }}');
                setCookie('dark_mode', 1, new Date().getTime() + (10 * 365 * 24 * 60 * 60 * 1000), '/');
                document.querySelector('#dark-theme > div > label > span > span').textContent = '{{ __('Dark') }}';
            } else {
                appCss.setAttribute('href', '{{ asset('css/app'. (__('lang_dir') == 'rtl' ? '.rtl' : '') .'.css') }}');
                setCookie('dark_mode', 0, new Date().getTime() + (10 * 365 * 24 * 60 * 60 * 1000), '/');
                document.querySelector('#dark-theme > div > label > span > span').textContent = '{{ __('Light') }}';
            }

            e.stopPropagation();
        });
    });
</script>