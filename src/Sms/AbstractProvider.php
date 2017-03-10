<?php

namespace Superman2014\Sms\Sms;

use BadMethodCallException;

abstract class AbstractProvider
{

    public static $scopes = ['prepare'];
    protected $config;

    /**
     * Create a new provider instance.
     *
     * @param array $config
     * @return void
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function __call($method, $params)
    {
        if (in_array($method, static::$scopes)) {
            $this->$method(...$params);
        }

        throw new BadMethodCallException('Not supposed to be called.');
    }

}

