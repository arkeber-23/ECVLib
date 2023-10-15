<?php
namespace easyphp\core\db;

use PDO;
use PDOException;

class Database
{
    private static $conn = null;

    private function __construct()
    {
    }

    public static function getConnection()
    {
        $db_driver = getenv('DB_DRIVER');
        $host = getenv('HOST');
        $db_name = getenv('DB_NAME');
        $db_user = getenv('DB_USER');
        $db_password = getenv('DB_PASSWORD');
        $db_charset = getenv('DB_CHARSET');

        try {
            $opciones = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $url = "$db_driver:host=$host;dbname=$db_name;charset=$db_charset";
            self::$conn = new PDO($url, $db_user, $db_password, $opciones);
        } catch (PDOException $e) {
            return "Error en la conexión a la base de datos: " . $e->getMessage();
        }

        if (is_null(self::$conn)) {
            self::$conn = new Database();
        }
        return self::$conn;
    }

    private function __clone()
    {
    }
}
