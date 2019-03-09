<?php

namespace Compubel\Rating;

use Illuminate\Support\ServiceProvider;

class RatingServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        $timestamp = date('Y_m_d_His');

        $this->publishes([
            __DIR__.'/../database/migrations/2019_03_08_000000_create_rating_table.php' => database_path('/migrations/' . $timestamp .'_create_rating_table.php')
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../config/rating.php' => config_path('rating.php'),
        ], 'config');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/rating.php', 'rating');

        // Register the service the package provides.
//        $this->app->singleton('laravel-rating', function ($app) {
//            return new laravel-rating;
//        });
//	    $this->app->singleton(Rating::class, function () {
//		    return new Rating();
//	    });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
//    public function provides()
//    {
//        return ['laravel-rating'];
//    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/rating.php' => config_path('rating.php'),
        ], 'rating.config');

        // Registering package commands.
        // $this->commands([]);
    }
}
