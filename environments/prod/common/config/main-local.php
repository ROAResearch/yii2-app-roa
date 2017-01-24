<?php

include __DIR__ . '/db.php';

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => "mysql:host=127.0.0.1;dbname=$dbname",
            'username' => $dbuser,
            'password' => $dbpass,
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
