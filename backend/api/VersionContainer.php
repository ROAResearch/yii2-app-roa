<?php

namespace backend\api;

class VersionContainer extends \roaresearch\yii2\roa\modules\ApiContainer
{
    /**
     * @inheritdoc
     */
    public $identityClass = models\User::class;

    /**
     * @inheritdoc
     */
    public $versions = [
        'v1' => ['class' => v1\Version::class],
        'dev' => ['class' => v1\Version::class],
    ];
}
