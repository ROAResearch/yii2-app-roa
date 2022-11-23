<?php

return [
    'components' => [
        'db' => ['class' => 'yii\db\Connection', 'charset' => 'utf8']
            + include __DIR__ . '/db.local.php',
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
        ],
    ],
];
