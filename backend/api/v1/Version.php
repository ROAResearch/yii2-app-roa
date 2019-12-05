<?php

namespace backend\api\v1;

use roaresearch\yii2\roa\{
    controllers\ProfileResource,
    urlRules\SingleRecord as SingleRecordUrlRule
};

class Version extends \roaresearch\yii2\roa\modules\ApiVersion
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
