<?php

namespace frontend\api\v1\controllers;

use frontend\api\models\User;
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
        unset($verbs['delete'], $verbs['create'], $verbs['update']);
        return $verbs;
    }
}
