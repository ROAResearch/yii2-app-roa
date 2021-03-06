<?php

namespace backend\tests\api;

use backend\tests\ApiTester;
use Codeception\Util\HttpCode;

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
            'v1' => ['stability' => 'development'],
            'dev' => ['stability' => 'development'],
        ]);
    }
}
