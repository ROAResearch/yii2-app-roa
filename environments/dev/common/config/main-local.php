<?php

return [
    'components' => [
        'db' => array_merge(include __DIR__ . '/db.local.php', [
            'class' => 'yii\db\Connection',
            'charset' => 'utf8',
        ]),
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
