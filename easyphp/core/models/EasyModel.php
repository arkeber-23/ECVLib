<?php

namespace easyphp\core\models;

use easyphp\core\easyorm\EasyOrm;


class EasyModel extends EasyOrm
{

    private $data = [];

    public function __construct($data = null)
    {
        $this->data[$data] = $data;
    }


    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        return $this->data[$name];
    }

}
