<?php

namespace frontend\api\v1;

class Version extends \tecnocen\roa\modules\ApiVersion
{
    /**
     * @inheritdoc
     */
    public $resources = [
        'user'
    ];

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\\api\\v1\\resources';
}
