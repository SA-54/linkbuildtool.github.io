<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('shared.footer', 'App\Http\View\Composers\FooterPagesComposer');

        View::composer([
            'dashboard.content',
            'shared.header'
        ], 'App\Http\View\Composers\UserStatsComposer');

        View::composer([
            'links.edit',
            'stats.browsers',
            'stats.devices',
            'stats.general',
            'stats.geographic',
            'stats.languages',
            'stats.platforms',
            'stats.social',
            'stats.sources',
            'shared.header'
        ], 'App\Http\View\Composers\UserFeaturesComposer');
    }
}
