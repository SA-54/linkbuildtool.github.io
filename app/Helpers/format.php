<?php

/**
 * Format the page titles
 *
 * @param null $value
 * @return string|null
 */
function formatTitle($value = null)
{
    if (is_array($value)) {
        return implode(" - ", $value);
    }

    return $value;
}

/**
 * Format money
 *
 * @param $amount
 * @param $currency
 * @return string
 */
function formatMoney($amount, $currency)
{
    if (in_array(strtoupper($currency), config('currencies.stripe.zero-decimals'))) {
        return number_format($amount, 0, __('.'), __(','));
    } else {
        return number_format($amount / 100, 2, __('.'), __(','));
    }
}

/**
 * Format the stripe status codes
 *
 * @return array
 */
function formatStripeStatus()
{
    $stripeStates = [
        'emulated' => ['status' => 'dark', 'title' => __('Emulated')],

        'trialing' => ['status' => 'success', 'title' => __('Trialing')],
        'active' => ['status' => 'success', 'title' => __('Active')],
        'incomplete' => ['status' => 'warning', 'title' => __('Incomplete')],
        'incomplete_expired' => ['status' => 'danger', 'title' => __('Expired')],
        'past_due' => ['status' => 'warning', 'title' => __('Past due')],
        'canceled' => ['status' => 'danger', 'title' => __('Canceled')],
        'unpaid' => ['status' => 'danger', 'title' => __('Unpaid')]
    ];

    return $stripeStates;
}

/**
 * Format the spaces codes
 *
 * @return array
 */
function formatSpace()
{
    return [
        1 => 'success',
        2 => 'danger',
        3 => 'warning',
        4 => 'info',
        5 => 'dark',
        6 => 'primary'
    ];
}

/**
 * Format the browser icon
 *
 * @param $key
 * @return mixed|string
 */
function formatBrowser($key)
{
    $browser = [
        'Chrome' => 'chrome',
        'Chromium' => 'chromium',
        'Firefox' => 'firefox',
        'Firefox Mobile' => 'firefox',
        'Edge' => 'edge',
        'Internet Explorer' => 'ie',
        'Mobile Internet Explorer' => 'ie',
        'Vivaldi' => 'vivaldi',
        'Brave' => 'brave',
        'Safari' => 'safari',
        'Opera' => 'opera',
        'Opera Mini' => 'opera',
        'Opera Mobile' => 'opera',
        'Opera Touch' => 'operatouch',
        'Yandex Browser' => 'yandex',
        'UC Browser' => 'ucbrowser',
        'Samsung Internet' => 'samsung',
        'QQ Browser' => 'qq',
        'BlackBerry Browser' => 'bbbrowser',
        'Maxthon' => 'maxthon'
    ];

    if (array_key_exists($key, $browser)) {
        return $browser[$key];
    } else {
        return 'unknown';
    }
}

/**
 * Format the platform icon
 *
 * @param $key
 * @return mixed|string
 */
function formatPlatform($key)
{
    $platform = [
        'Windows' => 'windows',
        'Linux' => 'linux',
        'Ubuntu' => 'ubuntu',
        'Windows Phone' => 'windows',
        'iOS' => 'apple',
        'OS X' => 'apple',
        'FreeBSD' => 'freebsd',
        'Android' => 'android',
        'Chrome OS' => 'chromeos',
        'BlackBerry OS' => 'bbos',
        'Tizen' => 'tizen',
        'KaiOS' => 'kaios',
        'BlackBerry Tablet OS' => 'bbos'
    ];

    if (array_key_exists($key, $platform)) {
        return $platform[$key];
    } else {
        return 'unknown';
    }
}

/**
 * Format the devices icon
 *
 * @param $key
 * @return mixed|string
 */
function formatDevice($key)
{
    $device = [
        'desktop' => 'desktop',
        'mobile' => 'mobile',
        'tablet' => 'tablet',
        'television' => 'tv',
        'gaming' => 'gaming',
        'watch' => 'watch'
    ];

    if (array_key_exists($key, $device)) {
        return $device[$key];
    } else {
        return 'unknown';
    }
}

/**
 * Format the country icon
 *
 * @param $key
 * @return string
 */
function formatCountry($key)
{
    if (array_key_exists($key, config('countries'))) {
        return strtolower($key);
    } else {
        return 'unknown';
    }
}

/**
 * Get and format the Gravatar URL.
 *
 * @param $email
 * @param int $size
 * @param string $default
 * @param string $rating
 * @return string
 */
function gravatar($email, $size = 80, $default = 'identicon', $rating = 'g')
{
    $url = 'https://www.gravatar.com/avatar/';
    $url .= md5(mb_strtolower(trim($email)));
    $url .= '?s='.$size.'&d='.$default.'&r='.$rating;
    return $url;
}