<?php

namespace backend\api\v1\resources;

use backend\api\models\User;
use tecnocen\roa\controllers\OAuth2Resource;

class UserResource extends OAuth2Resource
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
