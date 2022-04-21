<?php

namespace App\Models;

use CodeIgniter\Model;

class ApcEspecificaComunalModel extends Model
{
    protected $table      = 'public."apc_especifica_comunal"';
    protected $primaryKey = 'apc_espe_comunal_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['apc_espe_comunal_id', 'nombre_interfaz', 'propietario_id',
                                'causa_especifica', 'cantidad_causa_especifica', 'grupo_especifica',
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