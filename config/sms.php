<?php

return [

    'default' => 'aliyunsms',

    'sms' => [
        'aliyunsms' => [
            'driver' => 'aliyunsms',
            'client_id' => env('ALIYUN_SMS_CLIENT_ID', null),
            'client_secret' => env('ALIYUN_SMS_CLIENT_SECRET', null),
            'sign_name' => env('ALIYUN_SMS_SIGN_NAME', null),
            'end_point' => env('ALIYUN_SMS_END_POINT', null),
            'template_codes' => [
                'SMS_1234' => [
                    'name' => '测试验证码',
                    'scopes' => ['captcha'],
                ],
            ],
        ],
    ],
];
