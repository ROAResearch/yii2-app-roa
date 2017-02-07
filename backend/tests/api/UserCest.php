<?php

namespace backend\tests\api;

use backend\tests\ApiTester;
use common\fixtures\UserFixture;

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
