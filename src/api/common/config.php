<?php
return array(
    'MD5_SALT' => 'madfan',
    'JWT_KEY' => 'madfan',
    'NOTIFICATION' => array(
        'MAIL' => array(
            'SMTP' => array(
                'HOST' => 'smtp.feishu.cn',
                'USERNAME' => 'post@os120.com',
                'PASSWORD' => 'VqHrqCKMtvGykE7E',
                'PORT' => 465,
            ),
            'FROM' => array(
                'MAIL' => 'post@os120.com',
                'NAME' => 'POST',
            ),
            'TPL' => array(
                'AUTO_CHARGE' => array(
                    'SUBJECT' => 'Hi {user.first_name} {user.last_name}, automatic fee deduction reminder',
                    'BODY' => 'Hi {user.first_name} {user.last_name}, System will automatically deduct {bill.amount} {bill.currency} on {date.now}',
                ),
            ),
        ),
        'SMS' => array(
            'ALIYUN' => array(
                'ACCESS_KEY_ID' => '',
                'ACCESS_SECRET' => '',
                'REGION_ID' => '',
                'SIGN_NAME' => '',
                'TPL' => array(
                    'AUTO_CHARGE' => array(
                        'TEMPLATE_CODE' => '',
                    ),
                ),
            ),
        ),
    ),
);
