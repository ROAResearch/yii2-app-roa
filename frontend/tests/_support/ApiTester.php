<?php

namespace frontend\tests;

use frontend\api\models\User;

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

    /**
     * @var string[] pairs of user_name => oauth2_token for oauth2 auth.
     */
    protected static $tokens = [];

    /**
     * @var User[] pairs of user_name => User to grab User models.
     */
    protected static $authUsers = [];

    /**
     * @var string identificator for the auth/logged user.
     */
    protected $loggedUsername;

    /**
     * Saves a token and user by an unique name.
     *
     * @param string $username unique name to index the tokens and models
     * @param string $token oauth2 authorization token
     * @param User $user
     */
    public function storeToken($username, $token, User $user)
    {
        static::$tokens[$username] = $token;
        static::$authUsers[$username] = $user;
    }

    /**
     * Log as a regular user.
     */
    public function amLoggedInAsUser()
    {
        $this->logUser('erau');
    }

    /**
     * Login a user stored in `$authUsers`
     *
     * @param string $username
     */
    protected function logUser($username)
    {
        $this->username = $username;
        $this->amLoggedInAs(static::$authUsers[$username]);
    }

    /**
     * Sends an oauth2 token authorization for a regular user.
     */
    public function amAuthAsUser()
    {
        $this->authUser('erau');
    }

    /**
     * Authorizes a user stored in `$tokens`
     *
     * @param string $username
     */
    protected function authUser($username)
    {
        $this->username = $username;
        $this->amBearerAuthenticated(static::$tokens[$username]);
    }

    /**
     * Gets the instance of the user currently logged in.
     *
     * @return User
     */
    public function grabAuthUser()
    {
        return static::$authUsers[$this->username];
    }
}
