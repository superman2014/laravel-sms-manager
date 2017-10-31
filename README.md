# SMS

Support Laravel and Lumen.

Step1: 安装, `composer require "superman2014/laravel-sms-manager:1.0.x@dev"`

Step2: 注册 `Superman2014\Sms\SmsServiceProvider` 到`config/app.php` 配置文件:

```
'providers' => [
    // Other service providers...

    Superman2014\Sms\SmsServiceProvider::class,
],

```

or  bootstrap/app.php

```
$app->register(Superman2014\Sms\SmsServiceProvider::class);

```

也可以, 添加 `Sms` 门面 到配置文件的 `aliases` 数组里:

```
    'Sms' => Superman2014\Sms\Facades\Sms::class,
```

Step3: 生成`config/sms.php`

```
    php artisan vendor:publish
```

配置文件内容如下:

```
<?php

return [

    'default' => 'aliyunsms',

    'sms' => [
        'aliyunsms' => [
            'driver' => 'aliyunsms',
            'client_id' => env('ALIYUN_SMS_CLIENT_ID', null),
            'client_secret' => env('ALIYUN_SMS_CLIENT_SECRET', null),
            'sign_name' => env('ALIYUN_SMS_SIGN_NAME', null),
            'template_codes' => [
                'SMS_1234' => [
                    'name' => '测试验证码',
                    'scopes' => ['captcha'],
                ],
            ],
        ],
    ],
];
```

我们可以在.env文件里面配置这三个常量(阿里云access_key,阿里云access_secret,短信签名):

`ALIYUN_SMS_CLIENT_ID,ALIYUN_SMS_CLIENT_SECRET,ALIYUN_SMS_SIGN_NAME`

Step4: 代码中使用

```
php artisan tinker
Psy Shell v0.7.2 (PHP 7.0.15 — cli) by Justin Hileman

>>> use Sms;
=> null
>>> $paramString = ['captcha' => 1234];
=> [
     "captcha" => 1234,
   ]
>>> $a = Sms::driver()->prepare('18512345678', $paramString, 'SMS_1234')->send();
>>> $a = Sms::driver('aliyunsms')->prepare('18512345678', $paramString, 'SMS_1234')->send();


支持营销短信变量为空
>>> $a = Sms::driver()->prepareV20170606('18512345678', [], 'SMS_1234')->sendV20170606();

```

**Note**: superman2014/sms还不是很完善

- [ ] add event
- [ ] support queue
- [x] add default
- [x] sms frequency limit [captcha-repository](https://github.com/superman2014/captcha-repository)
- [ ] and so on



