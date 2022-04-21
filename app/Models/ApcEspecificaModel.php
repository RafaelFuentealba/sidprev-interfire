<?php

namespace App\Models;

use CodeIgniter\Model;

class ApcEspecificaModel extends Model
{
    protected $table      = 'public."apc_especifica"';
    protected $primaryKey = 'apc_espe_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['apc_espe_id', 'interfaz', 'nombre_interfaz', 'propietario_id',
                                'causa_especifica', 'medidas_espe', 'cantidad_causa_especifica', 'grupo_especifica',
                                'causa_general', 'grupo_causa', 'updated_at', 'deleted'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}

?>