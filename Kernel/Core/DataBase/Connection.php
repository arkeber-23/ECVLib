<?php

namespace Kernel\Core\DataBase;

use Exception;
use PDO;
use PDOException;

class Connection
{
    private static $conn = null;

    private function __construct()
    {
    }

    public static function getConnection()
    {

        if (!is_null(self::$conn)) {
            self::$conn = new Connection();
        }

        $db_driver = getenv('DB_DRIVER');
        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT');
        $db_name = getenv('DB_NAME');
        $db_user = getenv('DB_USER');
        $db_password = getenv('DB_PASSWORD');
        $db_charset = getenv('DB_CHARSET');

        if ($db_driver === 'mysql') {
            $url = "$db_driver:host=$host;port=$port;dbname=$db_name;charset=$db_charset";
        } elseif ($db_driver === 'pgsql') {
            $url = "$db_driver:host=$host;port=$port;dbname=$db_name";
        } else {
            $dir = __DIR__ . '/../../../src/Database';
            if (!dir($dir)) {
                mkdir($dir, 0777, true);
            }
            $db_name = !empty($db_name) ? $db_name :'database';
            $url = "sqlite:$dir/$db_name" . ".sqlite";
        }

        try {
            $opciones = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            self::$conn = new PDO($url, $db_user, $db_password, $opciones);
        } catch (PDOException $e) {
            throw new Exception("Error in the connection: " . $e->getMessage());
        }
        return self::$conn;
    }

    private function __clone()
    {
    }
}
