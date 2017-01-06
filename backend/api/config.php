<?php

use yii\helpers\ArrayHelper;

$v1 = ArrayHelper::merge(
    require(__DIR__ . '/v1/config.php'),
    require(__DIR__ . '/v1/config-local.php'),
    [
        'class' => 'yii\base\Module',
        'controllerNamespace' => 'backend\api\v1\controllers'
    ]
);

return [
    'modules' => [
        'v1' => $v1,
        'dev' => $v1,
    ],
];
