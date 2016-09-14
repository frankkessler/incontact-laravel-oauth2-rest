<?php

namespace Frankkessler\Incontact\Providers;

use Illuminate\Support\ServiceProvider;
use Frankkessler\Incontact\IncontactConfig;

class IncontactLaravelServiceProvider extends ServiceProvider{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish your migrations
        $this->publishes([
            __DIR__ . '/../../migrations/incontact.php' => base_path('/database/migrations/2015_11_11_052116_create_incontact_tokens_table.php')
        ], 'migrations');

        //publish config
        $this->publishes([
            __DIR__ . '/../../config/incontact.php' => config_path('incontact.php'),
        ], 'config');

        //merge default config if values were removed or never published
        $this->mergeConfigFrom(__DIR__.'/../../config/incontact.php', 'incontact');

        //set custom package views folder
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'incontact');

        //set custom routes for admin pages
        if(IncontactConfig::get('incontact.enable_oauth_routes')) {
            if (!$this->app->routesAreCached()) {
                require __DIR__ . '/../../http/routes.php';
            }
        }
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['incontact'] = $this->app->share(function($app)
        {
            return $app->make('Frankkessler\Incontact\Incontact', [
                'config' => [
                    'incontact.logger' => $app['log'],
                ],
            ]);
        });
    }

}