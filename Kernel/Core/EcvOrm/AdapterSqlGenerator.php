<?php

namespace Kernel\Core\EcvOrm;



class AdapterSqlGenerator
{
  
    private static $table;
    private static $driver;

    public function __construct()
    {

        static::$driver = getenv('DB_DRIVER');
    }

    private static function getDriver($driver)
    {
        switch ($driver) {
            case 'mysql':
                return new MysqlGenerateSql();
            case 'pgsql':
                return new PostGresGenerateSql();
            default:
                return new MysqlGenerateSql();
        }
    }

    public static function create($tableName, $callback)
    {

        static::$table = new Table(self::getDriver(static::$driver));
        if (is_callable($callback)) {
            call_user_func($callback, static::$table);
        }
        return static::$table->buildTable($tableName);
    }


    public static function dropTable($tableName)
    {
        if (is_null(static::$table)) {
            static::$table = new Table(self::getDriver(static::$driver));
        }

        return static::$table->dropTable($tableName);
    }
}