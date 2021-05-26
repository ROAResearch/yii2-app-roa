<?php

namespace frontend\tests\unit\models;

use Codeception\Verify\Expect;
use frontend\models\ContactForm;
use yii\mail\MessageInterface;

use function expect;

class ContactFormTest extends \Codeception\Test\Unit
{
    public function testSendEmail()
    {
        $model = new ContactForm();

        $model->attributes = [
            'name' => 'Tester',
            'email' => 'tester@example.com',
            'subject' => 'very important letter subject',
            'body' => 'body of current message',
        ];

        expect($model->sendEmail('admin@example.com'))->toBeTrue();

        // using Yii2 module actions to check email was sent
        $this->tester->seeEmailIsSent();

        /** @var MessageInterface  $emailMessage */
        $emailMessage = $this->tester->grabLastSentEmail();
        expect($emailMessage)->toBeInstanceOf(MessageInterface::class);
        Expect::Array($emailMessage->getTo())->toHaveKey('admin@example.com');
        Expect::Array($emailMessage->getFrom())
            ->toHaveKey('noreply@example.com');
        Expect::Array($emailMessage->getReplyTo())
            ->toHaveKey('tester@example.com');
        expect($emailMessage->getSubject())
            ->toEqual('very important letter subject');
        Expect::String($emailMessage->toString())
            ->toContainString('body of current message');
    }
}
