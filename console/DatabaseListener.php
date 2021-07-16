<?php

namespace console;

use Composer\Script\Event;
use PDO;
use PDOException;
use yii\helpers\{ArrayHelper, Console};

class DatabaseListener
{
    /**
     * It is used to write the user credentials in a PHP file.
     * @see self::dbFile()
     * @param  Event  $event
     */
    public static function config(Event $event)
    {
        $args = ComposerListener::parseArguments($event->getArguments());
        $user = ArrayHelper::getValue($args, 'dbuser');
        $pass = ArrayHelper::getValue($args, 'dbpass');
        $name = ArrayHelper::getValue($args, 'dbname');
        $host = ArrayHelper::getValue($args, 'dbhost', '127.0.0.1');

        if (!isset($user, $pass)) {
            list ($user, $pass) = self::requestCredentials();
        }

        while (null === ($pdo = self::createPDO($user, $pass, $host))) {
            Console::output(Console::ansiFormat(
                'Username/password incorrect.',
                [Console::FG_RED]
            ));
            list ($user, $pass) = self::requestCredentials();
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
\$dbhost = '$host';

PHP;
        file_put_contents(self::dbFile(), $fileContent);
    }

    /**
     * @return string[] MySQL user credentials.
     */
    protected static function requestCredentials()
    {
        $user = Console::prompt('Database username');
        $pass = Console::prompt('Database password');

        return [$user, $pass];
    }

    /**
     * @return string Database name.
     */
    protected static function requestName()
    {
        return Console::prompt('Database name');
    }

    /**
     * @param  string $user MySQL user.
     * @param  string $pass MySQL password.
     * @param  string $pass MySQL host.
     * @return \PDO|null    If the connection fails, it returns null.
     */
    protected static function createPDO($user, $pass, $host)
    {
        try {
            $pdo = new PDO('mysql:host=' . $host, $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * @param  PDO    $pdo      Object to stablish connection.
     * @param  string $dbname   Dabase name.
     * @return bool             Return true if connection was stablished.
     */
    protected static function useDb(PDO $pdo, $dbname)
    {
        try {
            $pdo->query("CREATE DATABASE IF NOT EXISTS $dbname");
            $pdo->query("CREATE DATABASE IF NOT EXISTS {$dbname}_test");
            $pdo->query("USE $dbname");

            return true;
        } catch (PDOException $e) {
            echo "You can't access `$dbname` error {$e->getMessage()}.\n";

            return false;
        }
    }

    /**
     * @return string Location of `db.php` file.
     */
    protected static function dbFile()
    {
        return dirname(__DIR__) . '/common/config/db.php';
    }

    /**
     * Drops and create database.
     */
    public static function truncate()
    {
        include self::dbFile();
        $pdo = self::createPDO($dbuser, $dbpass, $dbhost);
        $pdo->query("DROP DATABASE IF EXISTS $dbname");
        $pdo->query("DROP DATABASE IF EXISTS {$dbname}_test");
        $pdo->query("CREATE DATABASE $dbname");
        $pdo->query("CREATE DATABASE {$dbname}_test");
    }
}
