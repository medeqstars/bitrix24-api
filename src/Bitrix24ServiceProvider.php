<?php

namespace Medeq\Bitrix24;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class Bitrix24ServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/bitrix24.php', 'bitrix24');
    }

    public function provides()
    {
        return ['bitrix24'];
    }
    
    protected function bootForConsole()
    {
        $this->publishes([
            __DIR__.'/../config/bitrix24.php' => config_path('bitrix24.php'),
        ], 'bitrix24.config');
    }
}
