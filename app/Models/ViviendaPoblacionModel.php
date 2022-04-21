<?php

namespace App\Models;

use CodeIgniter\Model;

class ViviendaPoblacionModel extends Model
{
    protected $table      = 'public."vivienda_poblacion"';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id', 'geom', 'cod_region', 'region', 'cod_provin', 'provincia', 'cod_comuna', 'comuna',
                                'entidad', 'nom_entidad', 'categoria', 'nom_categoria', 'personas', 'hombres', 'mujeres',
                                'edad_0a5', 'edad6a14', 'edad15a64', 'edad_65ymas', 'ind_edad', 'inmigrante', 'pueblo',
                                'viv_part', 'viv_col', 'vpomp', 'total_viv', 'p03a_1', 'p03a_2', 'p03a_3', 'p03a_4',
                                'p03a_5', 'p03a_6', 'p03b_1', 'p03b_2', 'p03b_3', 'p03b_4', 'p03b_5', 'p03b_6', 'p03b_7',
                                'p03c_1', 'p03c_2', 'p03c_3', 'p03c_4', 'p03c_5', 'matacep', 'matrec', 'matirrec',
                                'p05_1', 'p05_2', 'p05_3', 'p05_4', '%p03a1', '%p03a2', '%p03a3', '%p03a4', '%p03a5', '%p03a6',
                                'ind_p03a', '%p03b1', '%p03b2', '%p03b3', '%p03b4', '%p03b5', '%p03b6', '%p03b7', 'ind_p03b',
                                '%p03c1', '%p03c2', '%p03c3', '%p03c4', '%p03c5', 'ind_p03c', '%p05_1', '%p05_2', '%p05_3',
                                '%p05_4', 'ind_p05', 'sup_ha', 'dens_pbl', 'dens_viv', 'origen_data', 'manzent'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}

?>