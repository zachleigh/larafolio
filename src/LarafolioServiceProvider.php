<?php

namespace Larafolio;

use View;
use Illuminate\Support\ServiceProvider as BaseProvider;

class LarafolioServiceProvider extends BaseProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register any other events for your application.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('larafolio::*', 'Larafolio\Http\ViewComposers\GlobalComposer');

        if (!$this->app->routesAreCached()) {
            require __DIR__.'/routes/web.php';
        }

        $this->loadViewsFrom(__DIR__.'/resources/views', 'larafolio');

        $this->publishes([
            __DIR__.'/database/migrations/' => database_path('migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../dist' => public_path('vendor/larafolio'),
        ], 'public');

        $this->publishes([
            __DIR__.'/config/imagecache.php' => config_path('imagecache.php'),
        ]);
    }
}
