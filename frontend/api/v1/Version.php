<?php

namespace frontend\api\v1;

use tecnocen\roa\controllers\ProfileResource;

class Version extends \tecnocen\roa\modules\ApiVersion
{
    /**
     * @inheritdoc
     */
    public $resources = [
        'profile' => ['class' => ProfileResource::class],
        'user'
    ];

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\\api\\v1\\resources';
}
