<?php

namespace frontend\tests\unit\models;

use Codeception\{Test\Unit, Verify\Expect};
use common\fixtures\UserFixture;
use frontend\{models\ResendVerificationEmailForm, tests\UnitTester};
use Yii;
use yii\mail\MessageInterface;

use function expect;

class ResendVerificationEmailFormTest extends Unit
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

    public function testWrongEmailAddress()
    {
        $model = new ResendVerificationEmailForm();
        $model->attributes = ['email' => 'aaa@bbb.cc'];

        expect($model->validate())->toBeFalse();
        expect($model->hasErrors())->toBeTrue();
        Expect::String($model->getFirstError('email'))
            ->toStartWith('There is no user with this email address.');
    }

    public function testEmptyEmailAddress()
    {
        $model = new ResendVerificationEmailForm();
        $model->attributes = ['email' => ''];

        expect($model->validate())->toBeFalse();
        expect($model->hasErrors())->toBeTrue();
        Expect::String($model->getFirstError('email'))
            ->toStartWith('Email cannot be blank.');
    }

    public function testResendToActiveUser()
    {
        $model = new ResendVerificationEmailForm();
        $model->attributes = ['email' => 'test2@mail.com'];

        expect($model->validate())->toBeFalse();
        expect($model->hasErrors())->toBeTrue();
        Expect::String($model->getFirstError('email'))
            ->toStartWith('There is no user with this email address.');
    }

    public function testSuccessfullyResend()
    {
        $model = new ResendVerificationEmailForm();
        $model->attributes = ['email' => 'test@mail.com'];

        expect($model->validate())->toBeTrue();
        expect($model->hasErrors())->toBeFalse();

        expect($model->sendEmail())->toBeTrue();
        $this->tester->seeEmailIsSent();

        $mail = $this->tester->grabLastSentEmail();

        expect($mail)->toBeInstanceOf(MessageInterface::class);
        Expect::Array($mail->getTo())->toHaveKey('test@mail.com');
        Expect::Array($mail->getFrom())
            ->toHaveKey(Yii::$app->params['supportEmail']);
        Expect::String($mail->getSubject())
            ->toStartWith('Account registration at ' . Yii::$app->name);
        Expect::String($mail->toString())->toContainString(
            '4ch0qbfhvWwkcuWqjN8SWRq72SOw1KYT_1548675330'
        );
    }
}
