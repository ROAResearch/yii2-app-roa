<?php

namespace frontend\tests\unit\models;

use Codeception\Verify\Expect;
use common\{fixtures\UserFixture, models\User};
use frontend\{models\SignupForm, tests\UnitTester};
use Yii;
use yii\mail\MessageInterface;

use function expect;

class SignupFormTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected UnitTester $tester;

    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php',
            ],
        ];
    }

    public function testCorrectSignup()
    {
        $model = new SignupForm([
            'username' => 'some_username',
            'email' => 'some_email@example.com',
            'password' => 'some_password',
        ]);

        expect($model->signup())->toBeTrue();

        /** @var User $user */
        $user = $this->tester->grabRecord(User::class, [
            'username' => 'some_username',
            'email' => 'some_email@example.com',
            'status' => User::STATUS_INACTIVE,
        ]);

        $this->tester->seeEmailIsSent();

        $mail = $this->tester->grabLastSentEmail();

        expect($mail)->toBeInstanceOf(MessageInterface::class);
        Expect::Array($mail->getTo())->toHaveKey('some_email@example.com');
        Expect::Array($mail->getFrom())
            ->toHaveKey(Yii::$app->params['supportEmail']);
        Expect::String($mail->getSubject())
            ->toStartWith('Account registration at ' . Yii::$app->name);
        Expect::String($mail->toString())
            ->toContainString($user->verification_token);
    }

    public function testNotCorrectSignup()
    {
        $model = new SignupForm([
            'username' => 'troy.becker',
            'email' => 'nicolas.dianna@hotmail.com',
            'password' => 'some_password',
        ]);

        expect($model->signup())->toBeFalse();
        Expect::Array($model->getErrors('username'))->toHaveCount(1);
        Expect::Array($model->getErrors('email'))->toHaveCount(1);

        Expect::String($model->getFirstError('username'))
            ->toStartWith('This username has already been taken.');
        Expect::String($model->getFirstError('email'))
            ->toStartWith('This email address has already been taken.');
    }
}
