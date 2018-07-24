<?php

namespace backend\api\models;

use OAuth2\Storage\UserCredentialsInterface;
use tecnocen\oauth2server\models\OauthAccessTokens as AccessToken;

class User extends \common\models\User implements UserCredentialsInterface
{
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()->joinWith('accessTokens', false)
            ->andWhere(['access_token' => $token])
            ->one();
    }

    /**
     * @inheritdoc
     */
    public function checkUserCredentials($username, $password)
    {
        $user = static::findByUsername($username);
        if (empty($user)) {
            return false;
        }

        return $user->validatePassword($password);
    }

    /**
     * @inheritdoc
     */
    public function getUserDetails($username = null)
    {
        $user = $username
            ? static::findByUsername($username)
            : $this;

        return ['user_id' => $user->id];
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getAccessTokens()
    {
        return $this->hasMany(AccessToken::class, ['user_id' => 'id'])
            ->andOnCondition(['client_id' => 'testclient']);
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'id',
            'username',
            'email',
            'status',
            'created_at',
            'updated_at',
        ];
    }
}
