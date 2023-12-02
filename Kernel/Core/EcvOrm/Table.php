<?php

namespace Kernel\Core\EcvOrm;

use Kernel\Core\EcvOrm\interfaces\IDataAdapter;
use Kernel\Core\EcvOrm\interfaces\ITypeData;

class Table implements ITypeData
{
    protected $columns = [];
    protected $after = null;
    private $database;

    public function __construct(IDataAdapter $database)
    {
        $this->database = $database;
    }
    protected function addColumn($type, $name,  $parameters = [])
    {
        return $this->addColumnDefinition(new ColumnDefinitions(
            array_merge(compact('type', 'name'), $parameters)
        ));
    }

    protected function addColumnDefinition($definition)
    {
        $this->columns[] = $definition;

        if ($this->after) {
            $definition->after($this->after);
            $this->after = $definition->name;
        }
        return $definition;
    }

    public function string($name, $length = 50, $parameters = [])
    {
        $type = sprintf('%s(%s)', static::EASYPHP_STRING, $length,);
        $this->addColumn($type, $name, $parameters);
    }

    public function char($name, $parameters = [])
    {
        if (!isset($parameters['length'])) {
            $parameters['length'] = 1;
        }
        $this->addColumn(static::EASYPHP_CHAR, $name, $parameters);
    }

    public function text($name)
    {
        $this->addColumn(static::EASYPHP_TEXT, $name);
    }

    public function tinyText($name, $parameters = [])
    {
        $parameters['length'] = $parameters['length'] > 255 ? 255 : $parameters['length'];
        $this->addColumn(static::EASYPHP_TENYETEXT, $name, $parameters);
    }

    public function mediumText($name)
    {
        $this->addColumn(static::EASYPHP_MEDIUMTEXT, $name);
    }

    public function longText($name)
    {
        $this->addColumn(static::EASYPHP_LONGTEXT, $name);
    }

    public function blob($name)
    {
        $this->addColumn(static::EASYPHP_BLOB, $name);
    }


    public function tinyBlob($name, $parameters = [])
    {

        $parameters['length'] = $parameters['length'] > 255 ? 255 : $parameters['length'];
        $this->addColumn(static::EASYPHP_TINYBLOB, $name, $parameters);
    }

    public function mediumBlob($name)
    {
        $this->addColumn(static::EASYPHP_MEDIUMBLOB, $name);
    }

    public function longBlob($name)
    {
        $this->addColumn(static::EASYPHP_LONGBLOB, $name);
    }

    public function enum($name, $parameters = [])
    {
        $this->addColumn(static::EASYPHP_ENUM, $name, $parameters);
    }

    public function set($name, $parameters = [])
    {
        $this->addColumn(static::EASYPHP_SET, $name, $parameters);
    }


    public function integer($name, $parameters = [])
    {
        $this->addColumn(static::EASYPHP_INTEGER, $name, $parameters);
    }

    public function tinyInteger($name, $parameters = [])
    {

        $this->addColumn(static::EASYPHP_TINY_INTEGER, $name, $parameters);
    }

    public function smallInteger($name, $parameters = [])
    {
        $this->addColumn(static::EASYPHP_SMALL_INTEGER, $name, $parameters);
    }

    public function mediumInteger($name, $parameters = [])
    {
        $this->addColumn(static::EASYPHP_MEDIUM_INTEGER, $name, $parameters);
    }

    public function id($name = 'id')
    {
        $this->unsignedInteger($name, ['primary_key' => true, 'auto_increment' => true, 'nullable' => true]);
    }
    public function bigInteger($name, $parameters = [])
    {
        $this->unsignedInteger($name, $parameters);
    }

    public function unsignedInteger($name, $parameters = [])
    {
        $parameters['unsigned'] = true;
        $this->addColumn(static::EASYPHP_BIG_INTEGER, $name, $parameters);
    }


    public function float($name, $parameters = [])
    {
        if (!isset($parameters['precision'])) {
            $parameters['precision'] = 10;
        }
        if (!isset($parameters['scale'])) {
            $parameters['scale'] = 2;
        }
        $this->addColumn(static::EASYPHP_FLOAT, $name, $parameters);
    }

    public function decimal($name, $parameters = [])
    {
        if (!isset($parameters['precision'])) {
            $parameters['precision'] = 10;
        }
        if (!isset($parameters['scale'])) {
            $parameters['scale'] = 2;
        }

        $this->addColumn(static::EASYPHP_DECIMAL, $name, $parameters);
    }

    public function numeric($name, $parameters = [])
    {

        if (!isset($parameters['precision'])) {
            $parameters['precision'] = 10;
        }
        if (!isset($parameters['scale'])) {
            $parameters['scale'] = 2;
        }

        $this->addColumn(static::EASYPHP_NUMERIC, $name, $parameters);
    }

    public function double($name, $parameters = [])
    {

        $this->addColumn(static::EASYPHP_DOUBLE, $name, $parameters);
    }

    public function real($name, $parameters = [])
    {
        $this->addColumn(static::EASYPHP_DOUBLE, $name, $parameters);
    }

    public function date($name, $parameters = [])
    {

        $this->addColumn(static::EASYPHP_DATE, $name);
    }

    public function datetime($name, $parameters = [])
    {

        $this->addColumn(static::EASYPHP_DATETIME, $name);
    }


    public function time($name, $parameters = [])
    {

        $this->addColumn(static::EASYPHP_TIME, $name);
    }


    public function timestamp($name, $parameters = [])
    {
        $this->addColumn(static::EASYPHP_TIMESTAMP, $name);
    }


    public function year($name, $parameters = [])
    {
        $this->addColumn(static::EASYPHP_YEAR, $name, $parameters);
    }

    public function timeStamps()
    {
        $this->addColumn(static::EASYPHP_TIMESTAMP, 'created_at', ['nullable' => true, 'default' => 'CURRENT_TIMESTAMP',]);
        $this->addColumn(static::EASYPHP_TIMESTAMP, 'updated_at', ['nullable' => true, 'default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP']);
    }


    public function bool($name, $parameters = [])
    {
        $this->addColumn(static::EASYPHP_BIT, $name, $parameters);
    }

    public function foreign($name, $parameters = [])
    {
         $this->addColumn('foreign', $name,$parameters);
    }

    public function buildTable($tableName)
    {
        return $this->database->generateCreateTableSQL($tableName, $this->columns);
    }

    public function dropTable($tableName)
    {
        return $this->database->generateDropTableSQL($tableName);
    }
}
