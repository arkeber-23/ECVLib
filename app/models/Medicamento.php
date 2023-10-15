<?php
namespace app\models;

use easyphp\core\models\EasyModel;

class Medicamento extends EasyModel 
{
    /**
     * @var string $table
     * Nombre de la tabla en la base de datos asociada a este modelo.
     */
    protected $table= "medicamentos";

    public function __construct() {
        parent::__construct();
    }
    
}
