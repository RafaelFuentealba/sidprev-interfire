<?php

namespace App\Models;

use CodeIgniter\Model;

class ComunasModel extends Model
{
    protected $table      = 'public."comunas_chile"';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id', 'geom', 'region', 'nom_region', 'provincia', 'nom_provin', 'comuna', 'nom_comuna', 'km2'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}

?>