<?php

namespace Core\Databases;

use Dotenv\Dotenv;

class Config
{
    private static $host;
    private static $username;
    private static $password;
    private static $database;

    public static function init()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        self::$host = $_ENV['DB_HOST'];
        self::$username = $_ENV['DB_USERNAME'];
        self::$password = $_ENV['DB_PASSWORD'];
        self::$database = $_ENV['DB_DATABASE'];
    }

    public static function getHost()
    {
        return self::$host;
    }

    public static function getUsername()
    {
        return self::$username;
    }

    public static function getPassword()
    {
        return self::$password;
    }

    public static function getDatabase()
    {
        return self::$database;
    }
}
