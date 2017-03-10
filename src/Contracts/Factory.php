<?php

namespace Superman2014\Sms\Contracts;

interface Factory
{
    /**
     * Get an Sms provider implementation.
     *
     * @param  string  $driver
     * @return \Superman2014\Sms\Contracts\Provider
     */
    public function driver($driver = null);
}

