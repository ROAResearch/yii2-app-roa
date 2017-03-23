<?php

namespace backend\api;

class ApiContainer extends \tecnocen\roa\modules\ApiContainer
{
    /**
     * @inheritdoc
     */
    public $baseNamespace = 'backend\\api';

    /**
     * @inheritdoc
     */
    public $versions = [
        'v1' => ['class' => v1\ApiVersion::class],
        'dev' => ['class' => v1\ApiVersion::class],
    ];
}
