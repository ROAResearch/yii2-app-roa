<?php
namespace backend\tests;

use backend\api\models\User;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;

    protected static $tokens = [];

    protected static $authUsers = [];

    protected $user;

    public function storeToken($name, $token, User $user)
    {
        static::$tokens[$name] = $token;
        static::$authUsers[$name] = $user;
    }

    /**
     * Define custom actions here
     */

    public function amLoggedInAsUser()
    {
        $this->user = 'erau';
        $this->logUser();
    }

    protected function logUser()
    {
        $this->amLoggedInAs(static::$authUsers[$this->user]);
    }

    public function amAuthAsUser()
    {
        $this->user = 'erau';
        $this->authUser();
    }

    protected function authUser()
    {
        $this->amBearerAuthenticated(static::$tokens[$this->user]);
    }

    public function grabAuthUser()
    {
        return static::$authUsers[$this->user];
    }
}
