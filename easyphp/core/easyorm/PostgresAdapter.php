<?php

namespace easyphp\core\easyorm;

use easyphp\core\easyorm\interfaces\IDatabaseAdapter;

class PostgresAdapter implements IDatabaseAdapter
{

    public function __construct()
    {
        echo "Connected to Postgres";
    }

    public function generateCreateTableSQL($tableName, $columns){

    }

   
}
