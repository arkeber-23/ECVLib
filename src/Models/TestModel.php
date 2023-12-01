<?php

namespace App\Models;

use Kernel\models\EcvLibModel;

class TestModel extends EcvLibModel
{
    protected $table = 'tests';

    public function __construct()
    {
        parent::__construct();
    }
}