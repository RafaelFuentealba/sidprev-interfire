<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'public."usuarios"';
    protected $primaryKey = 'usuario_id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['usuario_id', 'nombre', 'apellido', 'email', 'clave', 'img_perfil', 'telefono_contacto',
                                'comuna', 'provincia', 'region', 'nombre_organizacion', 'nombre_cargo_laboral',
                                'tipo_acceso', 'updated_at', 'deleted'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}

?>