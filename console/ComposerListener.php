<?php

namespace console;

use Composer\Script\Event;
use PDO;
use PDOException;

class ComposerListener
{
    public static function init(Event $event)
    {
        $composer = $event->getComposer();
        if (version_compare($composer::VERSION, '1.3.0', 'le')) {
            $event->stopPropagation();
            echo "Please update your composer version to 1.3 or higher.\n";
            exit(1);
        }
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

    protected static function requestPDOUser()
    {
        echo "Database username:\n";
        $user = trim(fgets(STDIN));
        echo "Database password:\n";
        $pass = trim(fgets(STDIN));
        return [$user, $pass];
    }
    protected static function requestPDODbname()
    {
        echo "Database name:\n";
        return trim(fgets(STDIN));
    }

    protected static function dbFile()
    {
        return dirname(__DIR__) . '/common/config/db.php';
    }

    public static function configDb(Event $event)
    {
        list ($user, $pass) = self::requestPDOUser();
        while (null === ($pdo = self::createPDO($user, $pass))) {
            echo "Username/password incorrect.\n";
            list($user, $pass) = self::requestPDOUser();
        }
        echo "Username/password accepted.\n";
        $dbname = self::requestPDODbname();
        while (!self::useDB($pdo, $dbname)) {
            echo "You don't have permissions to access `$dbname` database.\n";
            $dbname = self::requestPDODbname();
        }

        $fileContent = <<<PHP
<?php

\$user = '$user';
\$pass = '$pass';
\$dbname = '$dbname';
PHP;
	      file_put_contents(self::dbFile(), $fileContent);
    }

    public static function dropDB()
    {
        include self::dbFile();
        $pdo = self::createPDO($user, $pass);
        $pdo->query("drop database if exists $dbname");
        $pdo->query("drop database if exists {$dbname}_test");
    }

}
