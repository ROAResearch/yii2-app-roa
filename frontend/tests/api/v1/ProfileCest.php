<?php

namespace frontend\tests\api\v1;

use Codeception\Util\HttpCode;
use frontend\tests\ApiTester;

/**
 * @author Angel (Faryshta) Guevara <aguevara@alquimiadigitial.mx>
 */
class ProfileCest
{
    /**
     * @param ApiTester $I
     */
    public function unauthorized(ApiTester $I)
    {
        $I->sendGET('v1/profile');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @depends frontend\tests\api\AccessTokenCest:generateToken
     */
    public function profile(ApiTester $I)
    {
        $I->amAuthAsUser();
        $I->sendGET('v1/profile');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['email' => $I->grabAuthUser()->email]);
    }

    /**
     * @depends frontend\tests\api\AccessTokenCest:generateToken
     * @depends profile
     */
    public function patch(ApiTester $I)
    {
        $I->amAuthAsUser();
        $I->sendPATCH('v1/profile', ['email' => 'asdf@qwerty.com']);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->dontSeeResponseContainsJson(['email' => 'asdf@qwerty.com']);
    }
}
