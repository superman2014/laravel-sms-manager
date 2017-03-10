<?php

namespace Superman2014\Sms;

use InvalidArgumentException;
use Illuminate\Support\Manager;

class SmsManager extends Manager implements Contracts\Factory
{
    /**
     * Get a driver instance.
     *
     * @param  string|null $driver
     * @return mixed
     */
    public function driver($driver = null)
    {
        return $this->sms($driver);
    }

    /**
     * Get a sms provider instance.
     *
     * @param  string|null $driver
     * @return mixed
     */
    public function sms($name = null)
	{
		$name = $name ?: $this->getDefaultDriver();

		return $this->sms[$name] = $this->get($name);
	}

    /**
     * Create an instance of the specified driver.
     *
     * @param array $config
     * @return \Superman2014\Sms\Sms\AbstractProvider
     */
    protected function createAliyunsmsDriver($config)
    {
        $provider = 'Superman2014\Sms\Sms\AliyunSmsProvider';

		return new $provider($config);
    }

    /**
     * Attempt to get the sms provider instance.
     *
     * @param  string  $name
     * @return \Superman2014\Sms\Sms\AbstractProvider
     */
    protected function get($name)
    {
        return isset($this->sms[$name]) ? $this->sms[$name] : $this->resolve($name);
    }

    /**
     * Resolve the given sms.
     *
     * @param  string  $name
     *
     * @return \Superman2014\Sms\Sms\AbstractProvider

     * @throws \InvalidArgumentException
     */
    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Sms provider [{$name}] is not defined.");
        }

        if (isset($this->customCreators[$config['driver']])) {
            return $this->callCustomCreator($config);
        } else {
            $driverMethod = 'create'.ucfirst($config['driver']).'Driver';

            if (method_exists($this, $driverMethod)) {
                return $this->{$driverMethod}($config);
            } else {
                throw new InvalidArgumentException("Driver [{$config['driver']}] is not supported.");
            }
        }
    }

    /**
     * Get the sms configuration.
     *
     * @param  string  $name
     * @return array
     */
    protected function getConfig($name)
    {
        return $this->app['config']["sms.sms.{$name}"];
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['sms.default'];
    }

    public function setDefaultDriver($name)
    {
        $this->app['config']['sms.default'] = $name;
    }
}

