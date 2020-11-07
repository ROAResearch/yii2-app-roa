<?php

namespace console;

use Composer\Script\Event;
use console\models\Host;
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
        $host = ArrayHelper::getValue($args, 'dbhost');
        $user = ArrayHelper::getValue($args, 'dbuser');
        $pass = ArrayHelper::getValue($args, 'dbpass');
        $name = ArrayHelper::getValue($args, 'dbname');
        if (!isset($host, $user, $pass)) {
            list ($host, $user, $pass) = self::requestCredentials($host, $user, $pass);
        }
        while (null === ($pdo = self::createPDO($host, $user, $pass))) {
            self::errorMsg('Username/password incorrect.');
            list ($host, $user, $pass) = self::requestCredentials();
        }

        if (empty($name)) {
            $name = self::requestName();
        }
        while (!self::useDB($pdo, $name)) {
            self::errorMsg("You don't have permissions to access `$name` database.");
            $name = self::requestName();
        }

        $fileContent = <<<PHP
<?php

\$dbhost = '$host';
\$dbuser = '$user';
\$dbpass = '$pass';
\$dbname = '$name';

PHP;
        file_put_contents(self::dbFile(), $fileContent);
    }

    /**
     * Drops and create database.
     */
    public static function truncate()
    {
        include self::dbFile();
        $pdo = self::createPDO($host, $dbuser, $dbpass);
        $pdo->query("DROP DATABASE IF EXISTS $dbname");
        $pdo->query("DROP DATABASE IF EXISTS {$dbname}_test");
        $pdo->query("CREATE DATABASE $dbname");
        $pdo->query("CREATE DATABASE {$dbname}_test");
    }

    /**
     * @return string[] MySQL user credentials.
     * @param null|string $host MySQL host (prompt|argument from composer).
     * @param null|string $user MySQL user (prompt|argument from composer).
     * @param null|string $pass MySQL password (prompt|argument from composer).
     */
    protected static function requestCredentials($host = null, $user = null, $pass = null)
    {
        $host = (null === $host) ? Console::prompt('Database host', [
            'default' => '127.0.0.1',
            'validator' => function ($input, &$error) {
                $valid = false;
                $model = new Host();
                $model->ipHost = $input;

                if ($model->validate()) {
                    $valid = true;
                } else {
                    $error = self::errorMsg('Invalid IP address.');
                }

                return $valid;
            }
        ]) : $host;
        $user = (null === $user) ? Console::prompt('Database username', [
            'default' => 'root'
        ]) : $user;
        $pass = (null === $pass) ? Console::prompt('Database password') : $pass;

        return [$host, $user, $pass];
    }

    /**
     * @return string Database name.
     */
    protected static function requestName()
    {
        return Console::prompt('Database name', [
            'default' => 'yii2_app_roa'
        ]);
    }

    /**
     * @param string $user MySQL user.
     * @param string $pass MySQL password.
     * @param string $host MySQL host.
     * @return PDO|null    If the connection fails, it returns null.
     */
    protected static function createPDO($host, $user, $pass)
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
            self::errorMsg("You can't access `$dbname` error {$e->getMessage()}.");

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

    protected static function errorMsg($message)
    {
        return Console::output(Console::ansiFormat(
            $message,
            [Console::ITALIC, Console::FG_RED]
        ));
    }
}
