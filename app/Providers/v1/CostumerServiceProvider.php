<?php

namespace App\Providers\v1;

use CostumerService;
use Illuminate\Support\ServiceProvider;

class CostumerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CostumerService::class, function($app){
            return new CostumerService();
        });
    }
}
