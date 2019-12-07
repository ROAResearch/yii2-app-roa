<?php

namespace common\tests\unit\models;

use common\{fixtures\UserFixture, models\LoginForm};
use Yii;

/**
 * Login form test
 */
class LoginFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    /**
     * @return array
     */
    public function _fixtures()
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
    public function testLoginNoUser()
    {
        $model = new LoginForm([
            'username' => 'not_existing_username',
            'password' => 'not_existing_password',
        ]);

        expect('model should not login user', $model->login())->false();
        expect('user should not be logged in', Yii::$app->user->isGuest)
            ->true();
    }

    /**
     * Trying to log with an existant user but using wrong password
     */
    public function testLoginWrongPassword()
    {
        $model = new LoginForm([
            'username' => 'erau',
            'password' => 'wrong_password',
        ]);

        expect('model should not login user', $model->login())->false();
        expect('error message should be set', $model->errors)
            ->hasKey('password');
        expect('user should not be logged in', Yii::$app->user->isGuest)
            ->true();
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

        expect('model should login user', $model->login())->true();
        expect('error message should not be set', $model->errors)
            ->hasntKey('password');
        expect('user should be logged in', Yii::$app->user->isGuest)->false();
    }
}
