<?php

namespace App\Models;

use CodeIgniter\Model;

class InterfazModel extends Model
{
    protected $table      = 'public."interfaces"';
    protected $primaryKey = 'interfaz_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['interfaz_id', 'nombre', 'interfaz_forma', 'interfaz_geom', 'indice_riesgo', 'amenaza', 'vulnerabilidad', 'riesgo_historico', 'amenaza_historico', 'vulnerabilidad_historico',
                                'prevalencia', 'pendiente', 'vegetacion_combustible', 'vivienda_poblacion',
                                'limpieza_techo', 'residuos_agricolas', 'residuos_forestales', 'residuos_domesticos', 'residuos_industriales',
                                'propietario_id', 'updated_at', 'deleted'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}

?>