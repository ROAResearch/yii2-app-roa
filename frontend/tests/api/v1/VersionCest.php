<?php

namespace frontend\tests\api\v1;

use Codeception\Example;
use Codeception\Util\HttpCode;
use frontend\tests\ApiTester;
            
class VersionCest
{
    /**
     * @depends frontend\tests\api\AccessTokenCest:generateToken
     * @param ApiTester $I
     */
    public function index(ApiTester $I)
    {
        $I->amAuthAsUser();
        $I->sendGET('v1');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
    }
}
