<?php

namespace backend\api\v1;

use tecnocen\roa\controllers\ProfileResource;
use tecnocen\roa\urlRules\SingleRecord as SingleRecordUrlRule;

class Version extends \tecnocen\roa\modules\ApiVersion
{
    /**
     * @inheritdoc
     */
    public $resources = [
        'profile' => [
            'class' => ProfileResource::class,
            'urlRule' => ['class' => SingleRecordUrlRule::class],
        ],
        'user',
    ];

    /**
     * @inheritdoc
     */
    public $controllerNamespace = resources::class;
}
