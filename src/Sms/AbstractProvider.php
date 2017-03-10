<?php

namespace Superman2014\Sms\Sms;

use BadMethodCallException;

abstract class AbstractProvider
{

    public static $scopes = ['prepare'];
    protected $credentials;

    /**
     * Create a new provider instance.
     *
     * @param  string  $clientId
     * @param  string  $clientSecret
     * @param  string  $signName
     * @return void
     */
    public function __construct($clientId, $clientSecret, $signName)
    {
        $this->credentials['client_id'] = $clientId;
        $this->credentials['client_secret'] = $clientSecret;
        $this->credentials['sign_name'] = $signName;
    }

    public function __call($method, $params)
    {
        if (in_array($method, static::$scopes)) {
            $this->$method(...$params);
        }

        throw new BadMethodCallException('Not supposed to be called.');
    }

}

