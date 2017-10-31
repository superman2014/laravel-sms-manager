<?php

return [

    'default' => 'aliyunsms',

    'sms' => [
        'aliyunsms' => [
            'driver' => 'aliyunsms',
            'client_id' => env('ALIYUN_SMS_CLIENT_ID', null),
            'client_secret' => env('ALIYUN_SMS_CLIENT_SECRET', null),
            'sign_name' => env('ALIYUN_SMS_SIGN_NAME', null), //对应下面,而且是默认签名
            'end_point' => env('ALIYUN_SMS_END_POINT', null),
            'template_codes' => [
                'SMS_1234' => [
                    'name' => '测试验证码',
                    'scopes' => ['captcha'],
                    'sign_name_key' => 'sign_name',//对应上面的sign_name
                ],
            ],
        ],
    ],
];
