<?php

namespace App\Models;

use CodeIgniter\Model;

class ApcGeneralModel extends Model
{
    protected $table      = 'public."apc_general"';
    protected $primaryKey = 'apc_gene_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['apc_gene_id', 'interfaz', 'nombre_interfaz', 'propietario_id',
                                'medidas_gene', 'updated_at', 'deleted'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}

?>