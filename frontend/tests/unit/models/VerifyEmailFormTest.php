<?php

namespace frontend\tests\unit\models;

use Codeception\Verify\Expect;
use common\{fixtures\UserFixture, models\User};
use frontend\{models\VerifyEmailForm, tests\UnitTester};
use yii\base\InvalidArgumentException;

use function expect;

class VerifyEmailFormTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected UnitTester $tester;

    public function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php',
            ],
        ]);
    }

    public function testVerifyWrongToken()
    {
        Expect::Callable(
            function () {
                new VerifyEmailForm('');
            }
        )->toThrow(InvalidArgumentException::class);

        Expect::Callable(
            function () {
                new VerifyEmailForm('notexistingtoken_1391882543');
            }
        )->toThrow(InvalidArgumentException::class);
    }

    public function testAlreadyActivatedToken()
    {
        Expect::Callable(
            function () {
                new VerifyEmailForm('already_used_token_1548675330');
            }
        )->toThrow(InvalidArgumentException::class);
    }

    public function testVerifyCorrectToken()
    {
        $model = new VerifyEmailForm(
            '4ch0qbfhvWwkcuWqjN8SWRq72SOw1KYT_1548675330'
        );
        $user = $model->verifyEmail();

        expect($user)->toBeInstanceOf(User::class);
        Expect::String($user->username)->toStartWith('test.test');
        Expect::String($user->email)->toStartWith('test@mail.com');
        expect($user->status)->toEqual(User::STATUS_ACTIVE);
        expect($user->validatePassword('Test1234'))->toBeTrue();
    }
}
