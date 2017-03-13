<?php

namespace backend\tests\api;

use Codeception\Util\HttpCode;
use backend\tests\ApiTester;

class ApiCest
{
    /**
     * @param ApiTester $I
     */
    public function index(ApiTester $I)
    {
        $I->sendGET('');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            // edit upon releases
            'v1' =>  ['stability' => 'development'],
            'dev' =>  ['stability' => 'development'],
        ]);
    }
}
