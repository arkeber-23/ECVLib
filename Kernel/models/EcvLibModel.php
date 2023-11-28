<?php

namespace Kernel\models;

use Kernel\Core\EcvOrm\TitanOrm;

class EcvLibModel extends TitanOrm
{
    /**
     * @var array $data
     * Almacena los datos del modelo, que pueden ser accedidos y gestionados de manera dinámica.
     */
    protected $data = [];

    /**
     * Constructor de la clase EcvLibModel.
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
