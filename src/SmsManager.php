<?php

namespace Superman2014\Sms;

use InvalidArgumentException;
use Illuminate\Support\Manager;

class SmsManager extends Manager implements Contracts\Factory
{
    /**
     * Get a driver instance.
     *
     * @param  string  $driver
     * @return mixed
     */
    public function with($driver)
    {
        return $this->driver($driver);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \Superman2014\Sms\Sms\AbstractProvider
     */
    protected function createAliyunsmsDriver()
    {
        $config = $this->app['config']['sms.aliyunsms'];

        return $this->buildProvider(
            'Superman2014\Sms\Sms\AliyunSmsProvider', $config
        );
    }

    /**
     * Build an Sms provider instance.
     *
     * @param  string  $provider
     * @param  array  $config
     * @return \Superman2014\Sms\Sms\AbstractProvider
     */
    public function buildProvider($provider, $config)
    {
        return new $provider(
            $config['client_id'],
            $config['client_secret'],
            $config['sign_name']
        );
    }

    /**
     * Get the default driver name.
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        throw new InvalidArgumentException('No Sms driver was specified.');
    }
}

