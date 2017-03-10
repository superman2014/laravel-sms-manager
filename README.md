# SMS


Step1: 安装, `composer require "superman2014/sms:dev-master"`

Step2: 注册 `Superman2014\Sms\SmsServiceProvider` 到`config/app.php` 配置文件:

```
'providers' => [
    // Other service providers...

    Superman2014\Sms\SmsServiceProvider::class,
],

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

    'aliyunsms' => [
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
];
```

我们可以在.env文件里面配置这三个常量(阿里云access_key,阿里云access_secret,短信签名):

`ALIYUN_SMS_CLIENT_ID,ALIYUN_SMS_CLIENT_SECRET,ALIYUN_SMS_SIGN_NAME`

Step4: 代码中使用

```
<?php

namespace App\TestService;

use Sms;

class Test
{

    public function main()
    {
        $moblie = '18512345678';
        $paramString = [
            'code' => '1234',
        ];
        $templateCode = 'SMS_1234';

        $requestId = Sms::driver('aliyunsms')
            ->prepare($moblie, $paramString, $templateCode)
            ->send();
    }
}
```

**Note**: superman2014/sms还不是很完善

- [ ] add event
- [ ] add default
- [ ] sms frequency limit
- [ ] and so on



