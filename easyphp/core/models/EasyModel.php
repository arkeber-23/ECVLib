<?php

namespace easyphp\core\models;

use easyphp\core\easyorm\EasyOrm;

class EasyModel extends EasyOrm
{
    /**
     * @var array $data
     * Almacena los datos del modelo, que pueden ser accedidos y gestionados de manera dinámica.
     */
    private $data = [];

    /**
     * Constructor de la clase EasyModel.
     *
     * @param array|null $data
     * Permite inicializar la instancia del modelo con datos iniciales (opcional).
     */
    public function __construct($data = null)
    {
        if ($data !== null) {
            $this->data = $data;
        }
    }

    /**
     * Método mágico para establecer propiedades dinámicas del modelo.
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Método mágico para obtener propiedades dinámicas del modelo.
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->data[$name] ?? null;
    }
}
