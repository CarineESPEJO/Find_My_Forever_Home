<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $config = require __DIR__ . '/../../config/config.php';

            try {
                $dbConfig = $config['db'];
                $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['name']};charset=utf8";
                self::$connection = new PDO($dsn, $dbConfig['user'], $dbConfig['pass']);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}
