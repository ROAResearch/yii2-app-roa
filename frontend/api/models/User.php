<?php

namespace frontend\api\models;

use OAuth2\Storage\UserCredentialsInterface;
use roaresearch\yii2\oauth2server\models\OauthAccessTokens as AccessToken;
use roaresearch\yii2\roa\hal\{Contract, ContractTrait};
use yii\db\ActiveQuery;

class User extends \common\models\User implements
    UserCredentialsInterface,
    Contract
{
    use ContractTrait;

    /**
     * @inheritdoc
     */
    protected function slugBehaviorConfig(): array
    {
        return ['resourceName' => 'user'];
    }

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
     * @return ActiveQuery
     */
    public function getAccessTokens(): ActiveQuery
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
