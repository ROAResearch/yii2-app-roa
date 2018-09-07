<?php

namespace backend\api\v1\resources;

use backend\api\models\User;
use tecnocen\roa\controllers\Resource;

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
        unset($verbs['delete'], $verbs['create']);

        return $verbs;
    }
}
