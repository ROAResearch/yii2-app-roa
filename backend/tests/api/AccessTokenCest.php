<?php

namespace backend\tests\api;

use backend\api\models\User;
use backend\tests\ApiTester;
use Codeception\Util\HttpCode;

/**
 * Prueba funcional del api para la ruta `/oauth2/token`
 *
 * @author Angel (Faryshta) Guevara <aguevara@tecnocen.com>
 */
class AccessTokenCest
{
    /**
     * @param ApiTester $I
     *
     * @depends backend\tests\api\UserCest:fixtures
     */
    public function generateToken(ApiTester $I)
    {
        $I->wantTo('Generar token de acceso.');
        $I->amHttpAuthenticated('testclient', 'testpass');
        $I->sendPOST('oauth2/token', [
            'grant_type' => 'password',
            'username' => 'erau',
            'password' => 'password_0',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'access_token' => 'string:regex(/[0-9a-f]{40}/)',
            'refresh_token' => 'string:regex(/[0-9a-f]{40}/)',
        ]);
        $I->storeToken(
            'erau',
            $I->grabDataFromResponseByJsonPath('access_token')[0],
            $I->grabRecord(User::class, ['username' => 'erau'])
        );
    }
}
