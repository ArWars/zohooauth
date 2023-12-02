<?php

namespace Arwars\LaravelZohoOauth;

use Illuminate\Support\ServiceProvider;
use Arwars\LaravelZohoOauth\Console\ZohoOauthInitCommand;
use Arwars\LaravelZohoOauth\Console\ZohoOauthPruneCommand;
use Arwars\LaravelZohoOauth\Console\ZohoOauthRefreshCommand;

class ZohoOauthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->registerPublishables();

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    protected function registerPublishables(): void
    {
        if ($this->app->runningInConsole()) {
            if (! class_exists('CreateZohoOauthTable')) {
                $this->publishes([
                    __DIR__.'/../database/migrations/create_zoho_oauth_tables.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_zoho_oauth_tables.php'),
                ], 'migrations');
            }
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->loadTranslationsFrom(
            __DIR__.'/../resources/lang/', 'zoauth'
        );

        $this->registerCommands();
    }

    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ZohoOauthInitCommand::class,
                ZohoOauthRefreshCommand::class,
                ZohoOauthPruneCommand::class,
            ]);
        }
    }
}
