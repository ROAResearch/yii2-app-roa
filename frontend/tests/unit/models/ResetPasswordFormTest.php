<?php

namespace frontend\tests\unit\models;

use common\fixtures\UserFixture;
use frontend\models\ResetPasswordForm;
use yii\base\InvalidArgumentException;

class ResetPasswordFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \frontend\tests\UnitTester
     */
    protected $tester;

    public function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php',
            ],
        ]);
    }

    public function testResetWrongToken()
    {
        $this->tester->expectException(
            InvalidArgumentException::class,
            function () {
                new ResetPasswordForm('');
            }
        );

        $this->tester->expectException(
            InvalidArgumentException::class,
            function () {
                new ResetPasswordForm('notexistingtoken_1391882543');
            }
        );
    }

    public function testResetCorrectToken()
    {
        $user = $this->tester->grabFixture('user', 0);
        $form = new ResetPasswordForm($user['password_reset_token']);
        $form->password = 'password_0';
        expect_that($form->resetPassword());
    }
}
