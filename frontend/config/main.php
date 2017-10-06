<?php

use common\models\User;
use filsh\yii2\oauth2server\Module as OAuth2Module;
use frontend\api\models\User as ApiUser;
use frontend\api\VersionContainer;
use frontend\controllers;
use OAuth2\GrantType\RefreshToken;
use OAuth2\GrantType\UserCredentials;
use yii\log\FileTarget;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'api'],
    'controllerNamespace' => controllers::class,
    'modules' => [
        'api' => [
            'class' => VersionContainer::class,
        ],
        'oauth2' => [
            'class' => OAuth2Module::class,
            'tokenParamName' => 'accessToken',
            'tokenAccessLifetime' => 3600 * 24,
            'storageMap' => [
                'user_credentials' => ApiUser::class,
            ],
            'grantTypes' => [
                'user_credentials' => [
                    'class' => UserCredentials::class,
                ],
                'refresh_token' => [
                    'class' => RefreshToken::class,
                    'always_issue_new_refresh_token' => true,
                ],
            ],
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => User::class,
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_identity-frontend',
                'httpOnly' => true,
            ],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'POST api/oauth2/<action:\w+>' => 'oauth2/rest/<action>',
            ],
        ],
    ],
    'params' => $params,
];
