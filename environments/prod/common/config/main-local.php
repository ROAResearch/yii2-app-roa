<?php

include __DIR__ . '/db.php';

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => "mysql:host=$dbhost;port=$dbport;dbname=$dbname",
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
