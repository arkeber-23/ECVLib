<?php

namespace app\db;

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
        try {
            $opciones = [
                PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $url = DB_DRIVER.":host=" . HOST . "; dbname=" . DB_NAME . ";" . DB_CHARSET;
            self::$conn   = new PDO($url, DB_USER, DB_PASSWORD, $opciones);
        } catch (PDOException $e) {
            return "Error Conexion! " . $e->getMessage();
            die();
        }

        if (is_null(self::$conn)) {
            self::$conn = new Database();
        }
        return  self::$conn;
    }
    private function __clone()
    {
    }
}
