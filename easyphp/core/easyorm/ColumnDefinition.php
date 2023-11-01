<?php

namespace easyphp\core\easyorm;

class ColumnDefinition
{
    protected $name;
    protected $type;
    protected $parameters = [];
    private $references;
    private $referencedTable;
    
    public function __construct($attributes)
    {
        $this->name = $attributes['name'];
        $this->type = $attributes['type'];
        $this->parameters = $attributes;
        unset($this->parameters['name']);
        unset($this->parameters['type']);
    }

    public function after($columnName)
    {
        $this->parameters['after'] = $columnName;
    }


    public function getType()
    {
        return $this->type;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getParameters()
    {
        return $this->parameters;
    }
    public function addReference($name)
    {
        $this->references = $name;
    }

    public function setReferencedTable($name)
    {
        $this->referencedTable = $name;
    }
}
