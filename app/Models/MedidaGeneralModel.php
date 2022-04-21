<?php

namespace App\Models;

use CodeIgniter\Model;

class MedidaGeneralModel extends Model
{
    protected $table      = 'public."medidas_generales"';
    protected $primaryKey = 'medida_gene_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['medida_gene_id', 'propietario_id', 'nombre', 'objetivo', 'zonas_objetivo', 'responsable',
                                'contacto_responsable', 'fecha_inicio', 'fecha_termino', 'avance', 'updated_at', 'deleted'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}

?>