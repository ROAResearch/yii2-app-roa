<?php

namespace frontend\tests\api\v1;

use Codeception\Util\HttpCode;
use frontend\api\models\User;
use frontend\tests\ApiTester;

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
     * @depends frontend\tests\api\AccessTokenCest:generateToken
     */
    public function index(ApiTester $I)
    {
        $I->amAuthAsUser();
        $I->sendGET('v1/user');
        $I->seeResponseCodeIs(HttpCode::OK);
    }
}
