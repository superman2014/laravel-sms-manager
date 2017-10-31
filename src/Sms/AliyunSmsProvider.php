<?php

namespace Superman2014\Sms\Sms;

use Superman2014\Sms\Contracts\Provider as ProviderContract;
use Superman2014\Aliyun\Sms\SmsSender;
use AliyunMNS\SmsSender as SmsSenderV20170606;
use InvalidArgumentException;

class AliyunSmsProvider extends AbstractProvider implements ProviderContract
{
    public static $templateVariableMaxLength = 15;

    /*
     * 发送短信预处理.
     *
     * @throws InvalidArgumentException
     *
     * @return Superman2014\AliyunSmsProvider
     */
    public function prepare($mobile, array $paramString, $templateCode)
    {
        $this->mobile = (array) $mobile;

        if (!isset($this->config['template_codes'][$templateCode])) {
            throw new InvalidArgumentException(sprintf('The %s is invalid.', $templateCode));
        }

        $this->templateCode = $templateCode;

        $templateScope = $this->config['template_codes'][$templateCode];

        $originScope = $templateScope['scopes'];
        sort($templateScope['scopes'], SORT_STRING);

        $paramStringKeys = array_keys($paramString);
        sort($paramStringKeys, SORT_STRING);

        if ($templateScope['scopes'] !== $paramStringKeys) {
            throw new InvalidArgumentException(sprintf('The %s include params:%s.', $templateCode, implode(',', $originScope)));
        }

        array_walk($paramString, function (&$item, $key) {
            if (mb_strlen($item) > static::$templateVariableMaxLength) {
                throw new InvalidArgumentException(
                    sprintf(
                        'The %s may not be greater than %s characters',
                        $key,
                        static::$templateVariableMaxLength
                    )
                );
            }

            if (is_numeric($item)) {
                $item = strval($item);
            }
        });

        $this->paramString = $paramString;

        return $this;
    }

    /*
     * 发送短信预处理.
     *
     * @throws InvalidArgumentException
     *
     * @return Superman2014\AliyunSmsProvider
     */
    public function prepareV20170606($mobile, array $paramString, $templateCode)
    {
        $this->mobile = $mobile;

        if (!isset($this->config['template_codes'][$templateCode])) {
            throw new InvalidArgumentException(sprintf('The %s is invalid.', $templateCode));
        }

        $this->templateCode = $templateCode;

        $templateScope = $this->config['template_codes'][$templateCode];

        $originScope = $templateScope['scopes'];
        sort($templateScope['scopes'], SORT_STRING);

        $paramStringKeys = array_keys($paramString);
        sort($paramStringKeys, SORT_STRING);

        if ($templateScope['scopes'] !== $paramStringKeys) {
            throw new InvalidArgumentException(sprintf('The %s include params:%s.', $templateCode, implode(',', $originScope)));
        }

        array_walk($paramString, function (&$item, $key) {
            if (mb_strlen($item) > static::$templateVariableMaxLength) {
                throw new InvalidArgumentException(
                    sprintf(
                        'The %s may not be greater than %s characters',
                        $key,
                        static::$templateVariableMaxLength
                    )
                );
            }

            if (is_numeric($item)) {
                $item = strval($item);
            }
        });

        $this->paramString = $paramString;

        return $this;
    }

    /**
     * 发送短信.
     *
     * @return string|bool
     */
    public function send()
    {
        $smsSender = new SmsSender();

        return $smsSender->send(
            implode(',', $this->mobile),
            json_encode($this->paramString),
            $this->config['client_id'],
            $this->config['client_secret'],
            $this->config['sign_name'],
            $this->templateCode
        );
    }

    public function sendV20170606()
    {
        $smsSender = new SmsSenderV20170606();

        return $smsSender->send(
            $this->mobile,
            $this->paramString,
            $this->config['client_id'],
            $this->config['client_secret'],
            $this->config['sign_name'],
            $this->templateCode,
            $this->config['end_point']
        );
    }
}
