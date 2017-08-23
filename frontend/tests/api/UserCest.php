<?php

namespace frontend\tests\api;

use common\fixtures\UserFixture;
use frontend\tests\ApiTester;

/**
 * Prueba funcional del api para el usuario
 *
 * @author Angel (Faryshta) Guevara <aguevara@solmipro.com>
 */
class UserCest
{
    /**
     * @param ApiTester $I actor de pruebas.
     */
    public function fixtures(ApiTester $I)
    {
        $I->haveFixtures(['user' => ['class' => UserFixture::class]]);
    }
}
