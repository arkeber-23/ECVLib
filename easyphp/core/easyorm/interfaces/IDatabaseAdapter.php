<?php
namespace easyphp\core\easyorm\interfaces;

interface IDatabaseAdapter {
    public function generateCreateTableSQL($tableName, $columns);

    public function generateDropTableSQL($tableName);
}