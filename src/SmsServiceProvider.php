<?php

namespace Superman2014\Sms;

use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__. '/../config/sms.php' => config_path('sms.php'),
            ], 'config');
        }

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

