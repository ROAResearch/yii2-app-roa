<?php

namespace console;

use Composer\Script\Event;
use PDO;
use PDOException;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

class DatabaseListener
{
    public static function config(Event $event)
    {
        $args = ComposerListener::parseArguments($event->getArguments());
        $user = ArrayHelper::getValue($args, 'dbuser');
        $pass = ArrayHelper::getValue($args, 'dbpass');
        $name = ArrayHelper::getValue($args, 'dbname');
        if (!isset($user, $pass)) {
            list ($user, $pass) = self::requestCredentials();
        }

        while (null === ($pdo = self::createPDO($user, $pass))) {
            Console::output(Console::ansiFormat(
                'Username/password incorrect.',
                [Console::FG_RED]
            ));
            list($user, $pass) = self::requestCredentials();
        }

        if (empty($name)) {
            $name = self::requestName();
        }
        while (!self::useDB($pdo, $name)) {
            echo "You don't have permissions to access `$name` database.\n";
            $name = self::requestName();
        }

        $fileContent = <<<PHP
<?php

\$dbuser = '$user';
\$dbpass = '$pass';
\$dbname = '$name';
PHP;
	      file_put_contents(self::dbFile(), $fileContent);
    }

    protected static function requestCredentials()
    {
        $user = Console::prompt('Database username');
        $pass = Console::prompt('Database password');
        return [$user, $pass];
    }

    protected static function requestName()
    {
        return Console::prompt('Database name');
    }

    protected static function createPDO($user, $pass)
    {
        try {
            $pdo = new PDO('mysql:host=127.0.0.1', $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            return null;
        }
    }
    protected static function useDb(PDO $pdo, $dbname)
    {
        try {
            $pdo->query("create database if not exists $dbname");
            $pdo->query("create database if not exists {$dbname}_test");
            $pdo->query("use $dbname");
            return true;
        } catch (PDOException $e) {
            echo "You can't access `$dbname` error {$e->getMessage()}.\n";
            return false;
        }
    }

    protected static function dbFile()
    {
        return dirname(__DIR__) . '/common/config/db.php';
    }

    public static function truncate()
    {
        include self::dbFile();
        $pdo = self::createPDO($dbuser, $dbpass);
        $pdo->query("drop database if exists $dbname");
        $pdo->query("drop database if exists {$dbname}_test");
        $pdo->query("create database $dbname");
        $pdo->query("create database {$dbname}_test");
    }

}
