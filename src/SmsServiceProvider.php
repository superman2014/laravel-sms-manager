<?php

namespace Superman2014\Sms;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    public function boot()
    {
        $config = __DIR__.'/../config/sms.php';

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([
                $config => config_path('sms.php'),
            ], 'config');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('sms');
        }

        $this->mergeConfigFrom($config, 'sms');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton('Superman2014\Sms\Contracts\Factory', function ($app) {
            return new SmsManager($app);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Superman2014\Sms\Contracts\Factory'];
    }
}
