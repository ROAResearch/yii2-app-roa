<?php

namespace frontend\tests\unit\models;

use Codeception\Verify\Expect;
use common\fixtures\UserFixture;
use frontend\{models\ResetPasswordForm, tests\UnitTester};
use yii\base\InvalidArgumentException;

use function expect;

class ResetPasswordFormTest extends \Codeception\Test\Unit
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

    public function testResetWrongToken()
    {
        Expect::Callable(
            function () {
                new ResetPasswordForm('');
            }
        )->toThrow(InvalidArgumentException::class);

        Expect::Callable(
            function () {
                new ResetPasswordForm('notexistingtoken_1391882543');
            }
        )->toThrow(InvalidArgumentException::class);
    }

    public function testResetCorrectToken()
    {
        $user = $this->tester->grabFixture('user', 0);
        $form = new ResetPasswordForm($user['password_reset_token']);
        $form->password = 'password_0';
        expect($form->resetPassword())->toBeTrue();
    }
}
