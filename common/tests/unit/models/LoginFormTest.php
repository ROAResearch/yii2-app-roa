<?php

namespace common\tests\unit\models;

use Codeception\Verify\Expect;
use common\{fixtures\UserFixture, models\LoginForm, tests\UnitTester};
use Yii;

use function expect;

/**
 * Login form test
 */
class LoginFormTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return array
     */
    public function _fixtures(): array
    {
        return [
            'user' => [
                'class' => UserFixture::class,
            ],
        ];
    }

    /**
     * Trying to log with an unexistant username.
     */
    public function testLoginNoUser(): void
    {
        $model = new LoginForm([
            'username' => 'not_existing_username',
            'password' => 'not_existing_password',
        ]);

        expect($model->login())->toBeFalse();
        expect(Yii::$app->user->isGuest)->toBeTrue();
    }

    /**
     * Trying to log with an existant user but using wrong password
     */
    public function testLoginWrongPassword(): void
    {
        $model = new LoginForm([
            'username' => 'erau',
            'password' => 'wrong_password',
        ]);

        expect($model->login())->toBeFalse();
        Expect::Array($model->errors)->toHaveKey('password');
        expect(Yii::$app->user->isGuest)->toBeTrue();
    }

    /**
     * Successful login
     */
    public function testLoginCorrect()
    {
        $model = new LoginForm([
            'username' => 'erau',
            'password' => 'password_0',
        ]);

        expect($model->login())->toBeTrue();
        Expect::Array($model->errors)->notToHaveKey('password');
        expect(Yii::$app->user->isGuest)->toBeFalse();
    }
}
