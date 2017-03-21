<?php

namespace Superman2014\Sms\Contracts;

interface Provider
{
    /**
     * Send Sms.
     *
     * @throw \Exception
     *
     * @return bool
     */
    public function send();
}
