<?php

namespace Core\Databases;

use PDO;
use PDOException;

class Database
{
    private static $pdo;

    public static function connect()
    {
        if (!self::$pdo) {
            try {
                $dsn = 'mysql:host=' . Config::getHost() . ';dbname=' . Config::getDatabase();
                self::$pdo = new PDO($dsn, Config::getUsername(), Config::getPassword());
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
