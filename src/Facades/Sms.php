<?php

namespace Superman2014\Sms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Superman2014\Sms\SmsManager
 */
class Sms extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Superman2014\Sms\Contracts\Factory';
    }
}
