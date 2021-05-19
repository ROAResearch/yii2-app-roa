<?php

namespace common\models;

use Closure;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public string $username = '';
    public string $password = '';
    public bool $rememberMe = true;

    private ?User $user = null;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', Closure::fromCallable([$this, 'validatePassword'])],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param ?array $params the additional name-value pairs given in the rule
     */
    public function validatePassword(string $attribute, ?array $params)
    {
        if (
            !$this->hasErrors()
            && !$user = $this->getUser()?->validatePassword($this->password)
        ) {
            $this->addError($attribute, 'Incorrect username or password.');
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login(): bool
    {
        return $this->validate()
            && Yii::$app->user->login(
                $this->getUser(),
                $this->rememberMe ? 3600 * 24 * 30 : 0
            );
    }

    /**
     * Finds user by [[username]]
     *
     * @return ?User
     */
    protected function getUser(): ?User
    {
        return $this->user ??= User::findByUsername($this->username);
    }
}
