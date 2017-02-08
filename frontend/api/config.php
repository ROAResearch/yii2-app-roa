<?php

use yii\helpers\ArrayHelper;

$v1 = ArrayHelper::merge(
    require(__DIR__ . '/v1/config.php'),
    require(__DIR__ . '/v1/config-local.php')
);

return [
    'versions' => [
        'v1' => $v1,
        'dev' => $v1,
    ],
];
