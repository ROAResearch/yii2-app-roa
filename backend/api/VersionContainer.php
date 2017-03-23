<?php

namespace backend\api;

class VersionContainer extends \tecnocen\roa\modules\ApiContainer
{
    /**
     * @inheritdoc
     */
    public $identityClass = 'backend\\api\\models\\User';

    /**
     * @inheritdoc
     */
    public $baseNamespace = 'backend\\api';

    /**
     * @inheritdoc
     */
    public $versions = [
        'v1' => ['class' => v1\Version::class],
        'dev' => ['class' => v1\Version::class],
    ];
}
