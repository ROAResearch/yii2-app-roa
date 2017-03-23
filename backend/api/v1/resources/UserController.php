<?php

namespace backend\api\v1\resources;

use backend\api\models\User;
use tecnocen\roa\controllers\OAuth2ResourceController;

class UserController extends OAuth2ResourceController
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
