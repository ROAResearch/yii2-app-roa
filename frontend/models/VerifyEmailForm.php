<?php

namespace frontend\models;

use common\models\User;
use yii\base\{InvalidArgumentException, Model};

class VerifyEmailForm extends Model
{
    /**
     * @var string
     */
    public string $token;

    /**
     * @var User
     */
    private User $user;

    /**
     * Creates a form model with given token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the
     * object properties
     * @throws InvalidArgumentException if token is empty or not valid
     */
    public function __construct(string $token, array $config = [])
    {
        $token ?: throw new InvalidArgumentException(
            'Verify email token cannot be blank.'
        );

        $this->user = User::findByVerificationToken($token)
            ?: throw new InvalidArgumentException('Wrong verify email token.');

        parent::__construct($config);
    }

    /**
     * Verify email
     *
     * @return ?User the saved model or null if saving fails
     */
    public function verifyEmail(): ?User
    {
        $user = $this->user;
        $user->status = User::STATUS_ACTIVE;

        return $user->save(false) ? $user : null;
    }
}
