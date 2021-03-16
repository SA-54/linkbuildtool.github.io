<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Language routes
Route::post('/lang', 'LocaleController@index')->name('locale');

// Remote Redirect Routes
if (isset(parse_url(config('app.url'))['host']) && request()->getHost() != parse_url(config('app.url'))['host']) {
    Route::get('/{id}/+', 'RedirectController@index')->name('link.preview');
    Route::get('/{id}', 'RedirectController@index')->name('link.redirect');
    Route::post('/{id}/+', 'RedirectController@validatePassword');
    Route::post('/{id}', 'RedirectController@validatePassword');
}

// Install Routes
Route::prefix('install')->group(function () {
    Route::middleware('install')->group(function () {
        Route::get('/', 'InstallController@index')->name('install');
        Route::get('/requirements', 'InstallController@requirements')->name('install.requirements');
        Route::get('/permissions', 'InstallController@permissions')->name('install.permissions');
        Route::get('/database', 'InstallController@database')->name('install.database');
        Route::get('/account', 'InstallController@account')->name('install.account');

        Route::post('/database', 'InstallController@saveConfig');
        Route::post('/account', 'InstallController@saveDatabase');
    });

    Route::get('/complete', 'InstallController@complete')->name('install.complete');
});

// Update Routes
Route::prefix('update')->group(function () {
    Route::middleware('installed')->group(function () {
        Route::get('/', 'UpdateController@index')->name('update');
        Route::get('/overview', 'UpdateController@overview')->name('update.overview');
        Route::get('/complete', 'UpdateController@complete')->name('update.complete');

        Route::post('/overview', 'UpdateController@updateDatabase');
    });
});

// Auth Routes
Auth::routes(['verify' => true]);

// Home Routes
Route::get('/', 'HomeController@index')->middleware('installed')->name('home');
Route::post('/shorten', 'HomeController@createLink')->middleware('throttle:10,1')->name('guest');

// Contact Routes
Route::get('/contact', 'ContactController@index')->name('contact');
Route::post('/contact', 'ContactController@sendMail')->middleware('throttle:5,10');

// Pages Routes
Route::get('/page/{url}', 'PageController@index')->name('page');

// Developers Routes
Route::prefix('/developers')->group(function () {
    Route::get('/', 'DevelopersController@index')->name('developers');
    Route::get('/links', 'DevelopersController@links')->name('developers.links');
    Route::get('/spaces', 'DevelopersController@spaces')->name('developers.spaces');
    Route::get('/domains', 'DevelopersController@domains')->name('developers.domains');
    Route::get('/account', 'DevelopersController@account')->name('developers.account');
});

// User Routes
Route::get('/dashboard', 'DashboardController@index')->middleware('verified')->name('dashboard');

Route::get('/links', 'LinksController@index')->middleware('verified')->name('links');
Route::get('/links/edit/{id}', 'LinksController@linksEdit')->middleware('verified')->name('links.edit');

Route::get('/spaces', 'SpacesController@index')->middleware('verified')->name('spaces');
Route::get('/spaces/new', 'SpacesController@spacesNew')->middleware('verified')->name('spaces.new');
Route::get('/spaces/edit/{id}', 'SpacesController@spacesEdit')->middleware('verified')->name('spaces.edit');

Route::get('/domains', 'DomainsController@index')->middleware('verified')->name('domains');
Route::get('/domains/new', 'DomainsController@domainsNew')->middleware('verified')->name('domains.new');
Route::get('/domains/edit/{id}', 'DomainsController@domainsEdit')->middleware('verified')->name('domains.edit');

Route::prefix('/stats/{id}')->group(function () {
    Route::get('/general', 'StatsController@index')->name('stats');
    Route::get('/evolution/hours', 'StatsController@hours')->name('stats.evolution.hours');
    Route::get('/evolution/days', 'StatsController@days')->name('stats.evolution.days');
    Route::get('/evolution/months', 'StatsController@months')->name('stats.evolution.months');
    Route::get('/geographic', 'StatsController@geographic')->name('stats.geographic');
    Route::get('/browsers', 'StatsController@browsers')->name('stats.browsers');
    Route::get('/platforms', 'StatsController@platforms')->name('stats.platforms');
    Route::get('/devices', 'StatsController@devices')->name('stats.devices');
    Route::get('/languages', 'StatsController@languages')->name('stats.languages');
    Route::get('/sources', 'StatsController@sources')->name('stats.sources');
    Route::get('/social', 'StatsController@social')->name('stats.social');
});

Route::get('/qr/{id}', 'QRController@index')->name('qr');

Route::post('/links/new', 'LinksController@createLink')->name('links.new');
Route::post('/links/edit/{id}', 'LinksController@updateLink');
Route::post('/links/delete/{id}', 'LinksController@deleteLink')->name('links.delete');

Route::post('/spaces/new', 'SpacesController@createSpace');
Route::post('/spaces/edit/{id}', 'SpacesController@updateSpace');
Route::post('/spaces/delete/{id}', 'SpacesController@deleteSpace')->name('spaces.delete');

Route::post('/domains/new', 'DomainsController@createDomain');
Route::post('/domains/edit/{id}', 'DomainsController@updateDomain');
Route::post('/domains/delete/{id}', 'DomainsController@deleteDomain')->name('domains.delete');

Route::prefix('settings')->middleware('verified')->group(function () {
    Route::get('/', 'SettingsController@index')->name('settings');

    Route::get('/account', 'SettingsController@account')->name('settings.account');
    Route::get('/preferences', 'SettingsController@preferences')->name('settings.preferences');
    Route::get('/security', 'SettingsController@security')->name('settings.security');
    Route::get('/api', 'SettingsController@api')->name('settings.api');
    Route::get('/delete', 'SettingsController@delete')->name('settings.delete');

    Route::get('/payments/methods', 'SettingsController@paymentMethods')->middleware('payment')->name('settings.payments.methods');
    Route::get('/payments/methods/new', 'SettingsController@paymentMethodsNew')->middleware('payment')->name('settings.payments.methods.new');
    Route::get('/payments/methods/edit/{id}', 'SettingsController@paymentMethodsEdit')->middleware('payment')->name('settings.payments.methods.edit');

    Route::get('/payments/subscriptions', 'SettingsController@subscriptions')->middleware('payment')->name('settings.payments.subscriptions');
    Route::get('/payments/subscriptions/edit/{id}', 'SettingsController@subscriptionsEdit')->middleware('payment')->name('settings.payments.subscriptions.edit');

    Route::get('/payments/invoices', 'SettingsController@invoices')->middleware('payment')->name('settings.payments.invoices');
    Route::get('/payments/invoice/{invoice}', 'SettingsController@invoice')->middleware('payment')->name('settings.payments.invoice');

    Route::get('/payments/billing', 'SettingsController@billing')->middleware('payment')->name('settings.payments.billing');

    Route::post('/account', 'SettingsController@updateAccount')->name('settings.account.update');
    Route::post('/account/resend', 'SettingsController@resendAccount')->name('settings.account.resend');
    Route::post('/account/cancel', 'SettingsController@cancelAccount')->name('settings.account.cancel');
    Route::post('/preferences', 'SettingsController@updatePreferences')->name('settings.preferences.update');
    Route::post('/security', 'SettingsController@updateSecurity')->name('settings.security.update');
    Route::post('/delete', 'SettingsController@deleteAccount')->name('settings.account.delete');

    Route::post('/payments/methods/new', 'SettingsController@createPaymentMethod')->middleware('payment');
    Route::post('/payments/methods/edit/{id}', 'SettingsController@updatePaymentMethod')->middleware('payment');
    Route::post('/payments/methods/delete/{id}', 'SettingsController@deletePaymentMethod')->middleware('payment')->name('settings.payments.methods.delete');

    Route::post('/payments/subscriptions/cancel/{subscription}', 'SettingsController@cancelSubscription')->middleware('payment')->name('settings.payments.subscriptions.cancel');
    Route::post('/payments/subscriptions/resume/{subscription}', 'SettingsController@resumeSubscription')->middleware('payment')->name('settings.payments.subscriptions.resume');

    Route::post('/payments/billing', 'SettingsController@updateBilling')->middleware('payment');

    Route::post('/api', 'SettingsController@updateApi')->name('settings.api.update');
});

// Admin Routes
Route::prefix('admin')->middleware('admin', 'license')->group(function () {
    Route::redirect('/', 'admin/dashboard');

    Route::get('/dashboard', 'AdminController@dashboard')->middleware('license')->name('admin.dashboard');

    Route::get('/settings/general', 'AdminController@settingsGeneral')->name('admin.settings.general');
    Route::get('/settings/appearance', 'AdminController@settingsAppearance')->name('admin.settings.appearance');
    Route::get('/settings/email', 'AdminController@settingsEmail')->name('admin.settings.email');
    Route::get('/settings/social', 'AdminController@settingsSocial')->name('admin.settings.social');
    Route::get('/settings/payment', 'AdminController@settingsPayment')->name('admin.settings.payment');
    Route::get('/settings/invoice', 'AdminController@settingsInvoice')->name('admin.settings.invoice');
    Route::get('/settings/registration', 'AdminController@settingsRegistration')->name('admin.settings.registration');
    Route::get('/settings/contact', 'AdminController@settingsContact')->name('admin.settings.contact');
    Route::get('/settings/legal', 'AdminController@settingsLegal')->name('admin.settings.legal');
    Route::get('/settings/captcha', 'AdminController@settingsCaptcha')->name('admin.settings.captcha');
    Route::get('/settings/shortener', 'AdminController@settingsShortener')->name('admin.settings.shortener');

    Route::get('/languages', 'AdminController@languages')->name('admin.languages');
    Route::get('/languages/new', 'AdminController@languagesNew')->name('admin.languages.new');
    Route::get('/languages/edit/{id}', 'AdminController@languagesEdit')->name('admin.languages.edit');

    Route::get('/users', 'AdminController@users')->name('admin.users');
    Route::get('/users/new', 'AdminController@usersNew')->name('admin.users.new');
    Route::get('/users/edit/{id}', 'AdminController@usersEdit')->name('admin.users.edit');

    Route::get('/links', 'AdminController@links')->name('admin.links');
    Route::get('/links/edit/{id}', 'AdminController@linksEdit')->name('admin.links.edit');

    Route::get('/spaces', 'AdminController@spaces')->name('admin.spaces');
    Route::get('/spaces/edit/{id}', 'AdminController@spacesEdit')->name('admin.spaces.edit');

    Route::get('/domains', 'AdminController@domains')->name('admin.domains');
    Route::get('/domains/new', 'AdminController@domainsNew')->name('admin.domains.new');
    Route::get('/domains/edit/{id}', 'AdminController@domainsEdit')->name('admin.domains.edit');

    Route::get('/pages', 'AdminController@pages')->name('admin.pages');
    Route::get('/pages/new', 'AdminController@pagesNew')->name('admin.pages.new');
    Route::get('/pages/edit/{id}', 'AdminController@pagesEdit')->name('admin.pages.edit');

    Route::get('/plans', 'AdminController@plans')->name('admin.plans');
    Route::get('/plans/new', 'AdminController@plansNew')->middleware('payment')->name('admin.plans.new');
    Route::get('/plans/edit/{id}', 'AdminController@plansEdit')->name('admin.plans.edit');

    Route::get('/subscriptions', 'AdminController@subscriptions')->name('admin.subscriptions');
    Route::get('/subscriptions/new', 'AdminController@subscriptionsNew')->middleware('payment')->name('admin.subscriptions.new');
    Route::get('/subscriptions/edit/{id}', 'AdminController@subscriptionsEdit')->name('admin.subscriptions.edit');


    Route::post('/settings/general', 'AdminController@updateSettingsGeneral');
    Route::post('/settings/appearance', 'AdminController@updateSettingsAppearance');
    Route::post('/settings/email', 'AdminController@updateSettingsEmail');
    Route::post('/settings/social', 'AdminController@updateSettingsSocial');
    Route::post('/settings/payment', 'AdminController@updateSettingsPayment');
    Route::post('/settings/invoice', 'AdminController@updateSettingsInvoice');
    Route::post('/settings/registration', 'AdminController@updateSettingsRegistration');
    Route::post('/settings/contact', 'AdminController@updateSettingsContact');
    Route::post('/settings/legal', 'AdminController@updateSettingsLegal');
    Route::post('/settings/captcha', 'AdminController@updateSettingsCaptcha');
    Route::post('/settings/shortener', 'AdminController@updateSettingsShortener');

    Route::post('/languages/new', 'AdminController@createLanguage');
    Route::post('/languages/edit/{id}', 'AdminController@updateLanguage');
    Route::post('/language/delete/{id}', 'AdminController@deleteLanguage')->name('admin.languages.delete');

    Route::post('/users/new', 'AdminController@createUser');
    Route::post('/users/edit/{id}', 'AdminController@updateUser');
    Route::post('/users/delete/{id}', 'AdminController@deleteUser')->name('admin.users.delete');
    Route::post('/users/disable/{id}', 'AdminController@disableUser')->name('admin.users.disable');
    Route::post('/users/restore/{id}', 'AdminController@restoreUser')->name('admin.users.restore');

    Route::post('/links/edit/{id}', 'AdminController@updateLink');
    Route::post('/links/delete/{id}', 'AdminController@deleteLink')->name('admin.links.delete');

    Route::post('/spaces/edit/{id}', 'AdminController@updateSpace');
    Route::post('/spaces/delete/{id}', 'AdminController@deleteSpace')->name('admin.spaces.delete');

    Route::post('/domains/new', 'AdminController@createDomain');
    Route::post('/domains/edit/{id}', 'AdminController@updateDomain');
    Route::post('/domains/delete/{id}', 'AdminController@deleteDomain')->name('admin.domains.delete');

    Route::post('/pages/new', 'AdminController@createPage');
    Route::post('/pages/edit/{id}', 'AdminController@updatePage');
    Route::post('/pages/delete/{id}', 'AdminController@deletePage')->name('admin.pages.delete');

    Route::post('/plans/new', 'AdminController@createPlan')->middleware('payment');
    Route::post('/plans/edit/{id}', 'AdminController@updatePlan');
    Route::post('/plans/disable/{id}', 'AdminController@disablePlan')->middleware('payment')->name('admin.plans.disable');
    Route::post('/plans/restore/{id}', 'AdminController@restorePlan')->middleware('payment')->name('admin.plans.restore');

    Route::post('/subscriptions/new', 'AdminController@createSubscription')->middleware('payment');
    Route::post('/subscriptions/delete/{id}', 'AdminController@deleteSubscription')->name('admin.subscriptions.delete');
});

Route::get('admin/license', 'AdminController@license')->middleware('admin')->name('admin.license');
Route::post('admin/license', 'AdminController@updateLicense')->middleware('admin');

// Pricing Routes
Route::prefix('pricing')->middleware('payment')->group(function () {
    Route::get('/', 'PricingController@index')->name('pricing');
});

// Checkout Routes
Route::prefix('checkout')->middleware('verified', 'payment')->group(function () {
    Route::get('/collect/{period}', 'CheckoutController@collect')->name('checkout.collect');
    Route::post('/collect/{period}', 'CheckoutController@updatePaymentDetails');

    Route::get('/confirm/{id}', 'CheckoutController@show')->name('checkout.confirm');

    Route::get('/cancelled', 'CheckoutController@cancelled')->name('checkout.cancelled');
    Route::get('/complete', 'CheckoutController@complete')->name('checkout.complete');

    Route::get('/{id}/{period}', 'CheckoutController@index')->name('checkout.index');
    Route::post('/subscribe/{id}/{period}', 'CheckoutController@subscribe')->name('checkout.subscribe');
});

// Stripe Webhook Routes
Route::post('stripe/webhook', 'WebhookController@handleWebhook')->name('stripe.webhook');

// Redirect Routes
Route::get('/{id}/+', 'RedirectController@index')->name('link.preview');
Route::get('/{id}', 'RedirectController@index')->name('link.redirect');
Route::post('/{id}/+', 'RedirectController@validatePassword');
Route::post('/{id}', 'RedirectController@validatePassword');