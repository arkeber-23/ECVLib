<?php

namespace Kernel\Core\EcvOrm;

use Kernel\Core\EcvOrm\interfaces\IDataAdapter;

class PostGresGenerateSql implements IDataAdapter
{
    public function generateCreateTableSQL($tableName, $columns)
    {
    }

    public function generateDropTableSQL($tableName)
    {
    }
}
