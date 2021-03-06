<?php

namespace Larafolio;

use View;
use Larafolio\Commands\PublishSeeds;
use Intervention\Image\ImageServiceProvider;
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
        $this->app->bind(
            'Larafolio\Http\HttpValidator\HttpValidator',
            'Larafolio\Http\HttpValidator\CurlValidator'
        );

        $this->app->register(ImageServiceProvider::class);
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
            __DIR__.'/config/larafolio.php' => config_path('larafolio.php'),
        ]);

        $this->publishes([
            __DIR__.'/config/imagecache.php' => config_path('imagecache.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                PublishSeeds::class,
            ]);
        }
    }
}
