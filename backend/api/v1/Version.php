<?php

namespace backend\api\v1;

use tecnocen\roa\controllers\ProfileResource;
use tecnocen\roa\IsolatedUrlRule;

class Version extends \tecnocen\roa\modules\ApiVersion
{
    /**
     * @inheritdoc
     */
    public $resources = [
        'profile' => [
            'class' => ProfileResource::class,
            'urlRule' => ['class' => IsolatedUrlRule::class]
        ],
        'user'
    ];

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\\api\\v1\\resources';
}
