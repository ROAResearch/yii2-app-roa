<?php

namespace frontend\tests\unit\models;

use Codeception\Verify\Expect;
use common\{fixtures\UserFixture, models\User};
use frontend\models\PasswordResetRequestForm;
use Yii;
use yii\mail\MessageInterface;

use function expect;

class PasswordResetRequestFormTest extends \Codeception\Test\Unit
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

    public function testSendMessageWithWrongEmailAddress()
    {
        $model = new PasswordResetRequestForm();
        $model->email = 'not-existing-email@example.com';
        expect($model->sendEmail())->toBeFalse();
    }

    public function testNotSendEmailsToInactiveUser()
    {
        $user = $this->tester->grabFixture('user', 1);
        $model = new PasswordResetRequestForm();
        $model->email = $user['email'];
        expect($model->sendEmail())->toBeFalse();
    }

    public function testSendEmailSuccessfully()
    {
        $userFixture = $this->tester->grabFixture('user', 0);

        $model = new PasswordResetRequestForm();
        $model->email = $userFixture['email'];
        $user = User::findOne(
            ['password_reset_token' => $userFixture['password_reset_token']]
        );

        expect($model->sendEmail())->toBeTrue();
        expect($user->password_reset_token)->toBeString()->notToBeEmpty();

        $emailMessage = $this->tester->grabLastSentEmail();
        expect($emailMessage)->toBeInstanceOf(MessageInterface::class);
        Expect::Array($emailMessage->getTo())->toHaveKey($model->email);
        Expect::Array($emailMessage->getFrom())
            ->toHaveKey(Yii::$app->params['supportEmail']);
    }
}
