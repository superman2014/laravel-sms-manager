<?php

namespace Superman2014\Sms\Sms;

use Superman2014\Sms\Contracts\Provider as ProviderContract;
use InvalidArgumentException;
use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;

class AliyunSmsProvider extends AbstractProvider implements ProviderContract
{
    public static $templateVariableMaxLength = 15;

    public static $acsClient = null;

	public function __construct($config)
	{
        parent::__construct($config);
		Config::load();
	}

	/**
	 * 取得AcsClient
	 *
	 * @return DefaultAcsClient
	 */
	public static function getAcsClient($accessKeyId, $accessKeySecret, $endPointName) {
		//产品名称:云通信流量服务API产品,开发者无需替换
		$product = "Dysmsapi";

		//产品域名,开发者无需替换
		$domain = "dysmsapi.aliyuncs.com";

		// 暂时不支持多Region
		$region = "cn-hangzhou";

		if (static::$acsClient == null) {

			//初始化acsClient,暂不支持region化
			$profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

			// 增加服务结点
			DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

			// 初始化AcsClient用于发起请求
			static::$acsClient = new DefaultAcsClient($profile);
		}

		return static::$acsClient;
	}

    /*
     * 发送短信预处理.
     *
     * @throws InvalidArgumentException
     *
     * @return Superman2014\AliyunSmsProvider
     */
    public function prepare($mobile, array $paramString, $templateCode)
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
        $signName = $this->config['sign_name'];
        if (! empty($this->config['template_codes'][$this->templateCode]['sign_name_key'])) {
            $signNameKey = $this->config['template_codes'][$this->templateCode]['sign_name_key'];
            if (empty($this->config[$signNameKey])) {
                throw new InvalidArgumentException(sprintf('The %s sign name key is not exists'));
            }
            $signName = $this->config[$signNameKey];
        }

        return static::sendSms(
            $this->mobile,
            $this->paramString,
            $this->config['client_id'],
            $this->config['client_secret'],
            $signName,
            $this->templateCode
        );
    }

    public function sendV20170606()
    {
        $signName = $this->config['sign_name'];
        if (! empty($this->config['template_codes'][$this->templateCode]['sign_name_key'])) {
            $signNameKey = $this->config['template_codes'][$this->templateCode]['sign_name_key'];
            if (empty($this->config[$signNameKey])) {
                throw new InvalidArgumentException(sprintf('The %s sign name key is not exists'));
            }
            $signName = $this->config[$signNameKey];
        }

        return static::sendSms(
            $this->mobile,
            $this->paramString,
            $this->config['client_id'],
            $this->config['client_secret'],
            $this->config['sign_name'],
            $this->templateCode
        );
    }

    /**
     * 发送短信
     * @return stdClass
     */
    public static function sendSms($mobile, array $templateParam, $clientId, $clientSecret, $signName, $templateCode, $endPoint = 'cn-hangzhou') {

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        // 必填，设置短信接收号码
        $request->setPhoneNumbers($mobile);

        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName($signName);

        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode($templateCode);

        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
		if ($templateParam) {
			$request->setTemplateParam(json_encode($templateParam, JSON_UNESCAPED_UNICODE));
		}

        // 可选，设置流水号
        //$request->setOutId("yourOutId");

        // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
        //$request->setSmsUpExtendCode("1234567");

        // 发起访问请求
        $acsResponse = static::getAcsClient($clientId, $clientSecret, $endPoint)->getAcsResponse($request);

        return $acsResponse->RequestId;
    }

}
