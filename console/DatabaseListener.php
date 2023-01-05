<?php

namespace console;

use PDO;
use PDOException;

class DatabaseListener
{
    /**
     * Drops and create database.
     */
    public static function truncate()
    {
        extract(include dirname(__DIR__) . '/common/config/db.local.php');
        static::truncatePDO(new PDO($dsn, $username, $password));
        extract(include dirname(__DIR__) . '/common/config/db.test.php');
        static::truncatePDO(new PDO($dsn, $username, $password));
    }

    protected static function truncatePDO(PDO $pdo)
    {
        $dbname = $pdo->query('SELECT database()')->fetchColumn();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $pdo->query("DROP DATABASE IF EXISTS $dbname");
            $pdo->query("CREATE DATABASE $dbname");
        } catch (PDOException $e) {
            echo "You can't access `$dbname` error {$e->getMessage()}.\n";
        }
    }
}
