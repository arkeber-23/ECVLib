<?php

namespace Kernel\Core\EcvOrm\interfaces;

interface IDataAdapter {
    public function generateCreateTableSQL($tableName, $columns);

    public function generateDropTableSQL($tableName);
}