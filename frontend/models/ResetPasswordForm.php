<?php

namespace frontend\models;

use Yii;
use common\models\User;
use yii\base\{InvalidArgumentException, Model};

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;

    /**
     * @var User
     */
    private $user;

    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the
     * object properties
     * @throws InvalidArgumentException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException(
                'Password reset token cannot be blank.'
            );
        }

        $this->user = User::findByPasswordResetToken($token)
            ?: throw new InvalidArgumentException(
                'Wrong password reset token.'
            );

        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $user = $this->user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();
        $user->generateAuthKey();

        return $user->save(false);
    }
}
