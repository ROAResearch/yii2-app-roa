<?php

namespace backend\api\v1;

class ApiVersion extends \tecnocen\roa\modules\ApiVersion
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
    public $controllerNamespace = 'backend\\api\\v1\\Controllers';
}
