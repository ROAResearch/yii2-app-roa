<?php

namespace frontend\api\v1\resources;

use frontend\api\models\User;
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
    public function verbs()
    {
        $verbs = parent::verbs();
        unset($verbs['delete'], $verbs['create'], $verbs['update']);

        return $verbs;
    }
}
