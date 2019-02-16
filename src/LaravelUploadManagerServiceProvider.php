<?php

namespace abdulsametsahin\UploadManager;

use Illuminate\Support\ServiceProvider;

class UploadManagerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('abdulsametsahin-uploadmanager', function(){ return new UploadManager(); });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        require __DIR__ . "/routes/routes.php";

        $this->loadViewsFrom(__DIR__.'/views', 'UploadManager');
        /*
        $this->publishes([
            __DIR__.'/public' => public_path('vendor/abdulsametsahin/UploadManager'),
        ], 'public');
        */
    }
}
