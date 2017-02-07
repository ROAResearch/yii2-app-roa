<?php

namespace backend\tests\api\v1;

use Codeception\Example;
use Codeception\Util\HttpCode;
use backend\tests\ApiTester;
use backend\tests\api\UserCest as BaseCest;
use backend\api\models\User;

class UserCest
{
    /**
     * @param ApiTester $I
     */
    public function unauthorized(ApiTester $I)
    {
        $I->sendGET('v1/user');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @depends backend\tests\api\AccessTokenCest:generateToken
     */
    public function index(ApiTester $I)
    {
        $I->amAuthAsUser();
        $I->sendGET('v1/user');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @depends backend\tests\api\AccessTokenCest:generateToken
     * @example {"id": 1, "status": 0}
     * @example {"id": 1, "status": 10}
     */
    public function patch(ApiTester $I, Example $eg)
    {
        $I->amAuthAsUser();
        $I->sendPATCH("v1/user/{$eg['id']}", ['status' => $eg['status']]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => $eg['status']]);
    }

    /**
     * @depends backend\tests\api\AccessTokenCest:generateToken
     */
    public function failPatch(ApiTester $I)
    {
        $I->amAuthAsUser();
        $I->sendPATCH("v1/user/1", ['email' => 'asdf@qwerty.com']);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->dontSeeResponseContainsJson(['email' => 'asdf@qwerty.com']);
    }
}
