<?php

namespace App\Models;

use CodeIgniter\Model;

class IncendiosModel extends Model
{
    protected $table      = 'public."incendios_quinquenio"';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id', 'geom', 'region', 'provincia', 'comuna', 'escenario', 'causa_gene', 'causa_espe',
                                'sub_t_plan', 'arbolado', 'matorral', 'pastizal', 'sub_t_vege', 'agricola', 'desechos',
                                'sup_total', 'categoria', 'mes_ocurre', 'inicio', 'hora_inici', 'primer_ata', 'hora_ataqu',
                                'tiempo_resp', 'extincion', 'hora_extin', 'tiempo_incendio', 'temperatura', 'humedad_%',
                                'exposicion', 'direccion', 'viento_kh', 'topografia', 'fuente', 'ult_actual', 'temporada',
                                'x', 'y', 'id_especifico', 'id_general'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}

?>