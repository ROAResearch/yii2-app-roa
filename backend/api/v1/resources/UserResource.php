<?php

namespace backend\api\v1\resources;

use backend\api\models\User;
use roaresearch\yii2\roa\controllers\Resource;

class UserResource extends Resource
{
    /**
     * @inheritdoc
     */
    public $modelClass = User::class;

    /**
     * @inheritdoc
     */
    public function verbs(): array
    {
        $verbs = parent::verbs();
        unset($verbs['delete'], $verbs['create']);

        return $verbs;
    }
}
