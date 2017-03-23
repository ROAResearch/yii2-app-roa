<?php

namespace backend\api\v1;

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
    public $controllerNamespace = 'backend\\api\\v1\\resources';
}
