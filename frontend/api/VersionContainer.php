<?php

namespace frontend\api;

class VersionContainer extends \roaresearch\yii2\roa\modules\ApiContainer
{
    /**
     * @inheritdoc
     */
    public string $identityClass = models\User::class;

    /**
     * @inheritdoc
     */
    public array $versions = [
        'v1' => ['class' => v1\Version::class],
        'dev' => ['class' => v1\Version::class],
    ];
}
