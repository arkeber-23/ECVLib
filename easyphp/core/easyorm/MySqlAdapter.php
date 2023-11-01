<?php

namespace easyphp\core\easyorm;

use easyphp\core\db\Database;
use easyphp\core\easyorm\interfaces\IDatabaseAdapter;

class MySqlAdapter implements IDatabaseAdapter
{

    protected $connected = null;
    public function __construct()
    {
        $this->connected = Database::getConnection();
    }

    public function generateCreateTableSQL($tableName, $columns)
    {
        $sql = "\nDROP TABLE IF EXISTS $tableName;\n\nCREATE TABLE $tableName (\n";

        foreach ($columns as $column) {
            $sql .= rtrim($this->getColumnDefinitionSQL($column)) . ",\n";
        }

        $sql = rtrim($sql, ",\n");
        $sql .= ");\n";

        $this->executeQuery($sql);
    }

    public function generateDropTableSQL($tableName)
    {
        $sql = "DROP TABLE IF EXISTS $tableName;";
        $this->executeQuery($sql);
    }

    private function getColumnDefinitionSQL($column)
    {
        $name = $column->getName();
        $type = $column->getType();
        $type = str_replace('biginteger', 'bigint', $type);
        $parameters = $column->getParameters();

        if ($type == 'foreign') {
            $sql = "FOREIGN KEY ($name) REFERENCES {$parameters['references']} ({$parameters['column_name']})";
            if (isset($parameters['on_delete'])) {
                $sql .= " ON DELETE {$parameters['on_delete']}";
            }
            if (isset($parameters['on_update'])) {
                $sql .= " ON UPDATE {$parameters['on_update']}";
            }
        } else {
            $sql = "$name $type";
        }

        if (isset($parameters['unsigned']) && $parameters['unsigned']) {
            $sql .= ' UNSIGNED';
        }

        if (isset($parameters['auto_increment']) && $parameters['auto_increment']) {
            $sql .= ' AUTO_INCREMENT';
        }

        if (isset($parameters['not_null']) && $parameters['not_null']) {
            $sql .= ' NOT NULL';
        }

        if (isset($parameters['precision']) && isset($parameters['scale'])) {
            $sql .= "({$parameters['precision']}, {$parameters['scale']})";
        }

        if (isset($parameters['default'])) {
            $default = is_numeric($parameters['default']) ? $parameters['default'] : "'{$parameters['default']}'";
            if ($parameters['default'] == 'CURRENT_TIMESTAMP' || $parameters['default'] == 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP') {
                $default = $parameters['default'];
            }
            $sql .= " DEFAULT $default";
        }

        if (isset($parameters['primary_key']) && $parameters['primary_key']) {
            $sql .= ' PRIMARY KEY';
        }

        if (isset($parameters['unique']) && $parameters['unique']) {
            $sql .= ' UNIQUE';
        }

        return $sql;
    }

    private function executeQuery($sql)
    {
        try {
            $this->connected->exec($sql);
        } catch (\Exception $e) {
            echo "\e[91mError executing query: \n" . $e->getMessage() . "\n";
        }
    }
}
