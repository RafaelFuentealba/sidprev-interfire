<?php

namespace App\Controllers;
use App\Libraries\Hash;
use App\Models\UserModel;
use App\Models\InterfazModel;
use App\Models\ApcEspecificaModel;
use App\Models\ApcGeneralModel;
use App\Models\MedidaEspecificaModel;
use App\Models\MedidaGeneralModel;
use App\Models\CausaModel;
use App\Models\ApcEspecificaComunalModel;
use App\Models\IncendiosModel;
use App\Models\ComunasModel;
use App\Models\PendienteModel;
use App\Models\ViviendaPoblacionModel;

class Admin extends BaseController
{
    private $db;
    private $cp;

    public function __construct() {
        helper('Form');
        helper(['url', 'form']);
        $this->db = \Config\Database::connect('default');
        $this->cp = \Config\Database::connect('capas');
    }

    /** sets which user is logged into the system */
    function getActiveUser() {
        $userModel = new UserModel($this->db);
        $loggedUser = session()->get('loggedUser');
        $user_info = $userModel->where(['usuario_id' => $loggedUser, 'deleted' => false])->first();
        return $user_info;
    }

    /** return the user profile view */
    public function viewMyProfile() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser) {
            $userData = ['userInfo' => $loggedUser];
            $response = ['userData' => $loggedUser];
            return view('admin/templates/principal-panel', $userData).view('admin/profile/view-profile', $response);
        }
    }

    /** return the view to edit user profile */
    public function editMyProfile() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser) {
            $comunasModel = new ComunasModel($this->cp);

            $getRegiones = $comunasModel->select('nom_region')->groupBy('nom_region')->findAll();
            $regiones = [];
            foreach ($getRegiones as $region) {
                array_push($regiones, $region['nom_region']);
            }
            $userData = ['userInfo' => $loggedUser];
            
            $response = [
                'userData' => $loggedUser,
                'regiones' => $regiones
            ];
            return view('admin/templates/principal-panel', $userData).view('admin/profile/edit-profile', $response);
        }
    }

    /** update basic user information */
    public function saveMyProfile() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser && !empty($_POST)) {
            $userModel = new UserModel($this->db);
            $causaModel = new CausaModel($this->db);
            $apcEspeComunalModel = new ApcEspecificaComunalModel($this->db);
            $schema_table_incendios = 'public."incendios_quinquenio"';

            $nombre = $this->request->getPost('usuario-nombre');
            $apellido = $this->request->getPost('usuario-apellido');
            $contacto = $this->request->getPost('usuario-contacto');
            $organizacion = $this->request->getPost('usuario-organizacion');
            $cargoLaboral = $this->request->getPost('usuario-cargo');
            $region = $this->request->getPost('usuario-region');
            $provincia = $this->request->getPost('usuario-provincia');
            $comuna = $this->request->getPost('usuario-comuna');

            $userToUpdate = $userModel->where('usuario_id', $loggedUser['usuario_id'])->first();
            $userToUpdate['nombre'] = $nombre;
            $userToUpdate['apellido'] = $apellido;
            $userToUpdate['telefono_contacto'] = $contacto;
            $userToUpdate['nombre_organizacion'] = $organizacion;
            $userToUpdate['nombre_cargo_laboral'] = $cargoLaboral;
            $userToUpdate['region'] = $region;
            $userToUpdate['provincia'] = $provincia;
            $userToUpdate['comuna'] = $comuna;
            $updatedUser = $userModel->update($loggedUser['usuario_id'], $userToUpdate);

            if (!empty($comuna)) {
                $aIDCausas = [];
                $aCantidadCausasEspecificas = [];
                $query_contador_especificas = $this->cp->query("SELECT COUNT(*) AS cantidad_especificas, id_especifico FROM ".$schema_table_incendios." WHERE UNACCENT(LOWER(comuna)) = UNACCENT(LOWER('".$comuna."')) AND id_especifico <> 999 GROUP BY id_especifico;");
                $contador_causas_especificas = $query_contador_especificas->getResultArray();
                foreach ($contador_causas_especificas as $value) {
                    array_push($aCantidadCausasEspecificas, intval($value['cantidad_especificas']));
                    array_push($aIDCausas, $value['id_especifico']);
                }
                    
                $query_causas = $causaModel->select('causa_id, nombre_causa_especifica, grupo_causa_especifica, causa_general, grupo')->whereIn('causa_id', $aIDCausas)->orderBy('causa_id', 'ASC')->get();
                $data_causas = $query_causas->getResultArray();
                $aNombreCausasEspecificas = [];
                $aGrupoEspecificas = [];
                $aCausaGeneral = [];
                $aGrupoCausa = [];
                foreach ($data_causas as $causa) {
                    array_push($aNombreCausasEspecificas, $causa['nombre_causa_especifica']);
                    array_push($aGrupoEspecificas, $causa['grupo_causa_especifica']);
                    array_push($aCausaGeneral, $causa['causa_general']);
                    array_push($aGrupoCausa, $causa['grupo']);
                }

                $getAPCcomuna = $apcEspeComunalModel->countAll();
                if ($getAPCcomuna > 0) $getAPCusuario = $apcEspeComunalModel->where('propietario_id', $loggedUser['usuario_id'])->delete();
                
                $countSavedAPC = 0;
                for ($i=0; $i < sizeof($data_causas); $i++) {
                    $apcToSave = array(
                        'nombre_interfaz' => $comuna,
                        'propietario_id' => (int)$loggedUser['usuario_id'],
                        'causa_especifica' => $aNombreCausasEspecificas[$i],
                        'cantidad_causa_especifica' => $aCantidadCausasEspecificas[$i],
                        'grupo_especifica' => $aGrupoEspecificas[$i],
                        'causa_general' => $aCausaGeneral[$i],
                        'grupo_causa' => $aGrupoCausa[$i]
                    );
                    $savedAPC = $apcEspeComunalModel->insert($apcToSave);
                    if ($savedAPC) $countSavedAPC = $countSavedAPC + 1;
                    else {
                        return $this->response->setJSON(0);
                        break;
                    }
                }
    
                if ($countSavedAPC == sizeof($data_causas)) return $this->response->setJSON(1);
                else return $this->response->setJSON(0);
            } else {
                if (!$updatedUser) return $this->response->setJSON(0);
                else return $this->response->setJSON(1);
            }
        }
    }

    /** update the password of user */
    public function changeMyPassword() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser && !empty($_POST)) {
            $userModel = new UserModel($this->db);

            $oldPassword = $this->request->getPost('usuario-password');
            $newPassword = $this->request->getPost('usuario-new-password');
            
            $userToUpdate = $userModel->where('usuario_id', $loggedUser['usuario_id'])->first();
            if ($userToUpdate['deleted'] == 'f') {
                $checkOldPassword = Hash::check($oldPassword, $userToUpdate['clave']);
                if (!$checkOldPassword) {
                    return json_encode('La contraseña actual es incorrecta');
                } else {
                    $userToUpdate['clave'] = Hash::make($newPassword);
                    $updatedUser = $userModel->update($loggedUser['usuario_id'], $userToUpdate);
                    
                    if (!$updatedUser) return $this->response->setJSON(0);
                    else return $this->response->setJSON(1);
                }
            }
        }
    }

    /** return the name of the provinces according to the region */
    public function getProvinciasByRegion() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser && !empty($_GET)) {
            $comunasModel = new ComunasModel($this->cp);
            $region = $this->request->getPostGet('region-name');
            $getProvincias = $comunasModel->select('nom_provin')->where('nom_region', $region)->groupBy('nom_provin')->findAll();
            return $this->response->setJSON($getProvincias);
        }
    }

    /** return the name of the comunas according to the provincia */
    public function getComunasByProvincia() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser && !empty($_GET)) {
            $comunasModel = new ComunasModel($this->cp);
            $provincia = $this->request->getPostGet('provincia-name');
            $getComunas = $comunasModel->select('nom_comuna')->where('nom_provin', $provincia)->groupBy('nom_comuna')->findAll();
            return $this->response->setJSON($getComunas);
        }
    }

    /** return the view for create an interface */
    public function interfaceCreate() {
        $loggedUser = $this->getActiveUser();
        $userData = ['userInfo' => $loggedUser];
        return view('admin/templates/principal-panel', $userData).view('admin/app');
    }

    /** gets data of circumference type interfaz */
    public function getDataOfCircleInterfaces() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser && !empty($_POST)) {
            $lng = $this->request->getPost('longitude-zone');
            $lat = $this->request->getPost('latitude-zone');
            $radius = $this->request->getPost('radius-zone');

            $latlng_to_geom = "ST_SetSRID(ST_GeomFromText('POINT(".$lng." ".$lat.")'), 4326)";
            $latlng_to_buffer = "ST_Buffer(ST_SetSRID(ST_MakePoint(".$lng.",".$lat."), 4326), (".$radius."*100/111.32))";
    
            $schema_table_incendios = 'public."incendios_quinquenio"';
            $query_incendios = $this->cp->query("SELECT
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2015 - Junio 2016' THEN 1 ELSE 0 END), 0) AS inc_2015,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2016 - Junio 2017' THEN 1 ELSE 0 END), 0) AS inc_2016,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2017 - Junio 2018' THEN 1 ELSE 0 END), 0) AS inc_2017,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2018 - Junio 2019' THEN 1 ELSE 0 END), 0) AS inc_2018,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2019 - Junio 2020' THEN 1 ELSE 0 END), 0) AS inc_2019,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2020 - Junio 2021' THEN 1 ELSE 0 END), 0) AS inc_2020
                    FROM ".$schema_table_incendios." WHERE ST_WITHIN(geom, ".$latlng_to_buffer.");");
            
            $query_superficie = $this->cp->query("SELECT
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2015 - Junio 2016' THEN sup_total ELSE 0 END), 0) AS sup_2015,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2016 - Junio 2017' THEN sup_total ELSE 0 END), 0) AS sup_2016,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2017 - Junio 2018' THEN sup_total ELSE 0 END), 0) AS sup_2017,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2018 - Junio 2019' THEN sup_total ELSE 0 END), 0) AS sup_2018,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2019 - Junio 2020' THEN sup_total ELSE 0 END), 0) AS sup_2019,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2020 - Junio 2021' THEN sup_total ELSE 0 END), 0) AS sup_2020
                    FROM ".$schema_table_incendios." WHERE ST_WITHIN(geom, ".$latlng_to_buffer.");");

            $schema_table_pendientes = 'public."pendientes_pais"';
            $query_pendiente = $this->cp->query("SELECT gridcode,
                        (ST_Area(ST_Intersection(geom, ".$latlng_to_buffer.")) / ST_Area(".$latlng_to_buffer."))*100 AS pc_superficie
                    FROM ".$schema_table_pendientes." WHERE ST_DWITHIN(geom, ".$latlng_to_geom.", ".$radius.");");

            $schema_table_combustible = 'public."combustible"';
            $query_combustible = $this->cp->query("SELECT puntaje,
                        (ST_Area(ST_Intersection(geom, ".$latlng_to_buffer.")) / ST_Area(".$latlng_to_buffer."))*100 AS pc_superficie
                    FROM ".$schema_table_combustible." WHERE ST_DWITHIN(geom, ".$latlng_to_geom.", ".$radius.");");

            $schema_table_vivienda_poblacion = 'public."vivienda_poblacion"';
            $query_poblacion = $this->cp->query("SELECT COALESCE(sum(dens_pbl), 0) AS sum_densidad, COUNT(*) AS cantidad
                    FROM ".$schema_table_vivienda_poblacion." WHERE ST_WITHIN(geom, ".$latlng_to_buffer.");");
                
            $query_mat_techo = $this->cp->query("SELECT
                COALESCE(sum(p03b_6), 0) AS techo_tipo_1a, COALESCE(sum(p03b_5), 0) AS techo_tipo_1b,
                COALESCE(sum(p03b_4), 0) AS techo_tipo_2,
                COALESCE(sum(p03b_1), 0) AS techo_tipo_3,
                COALESCE(sum(p03b_3), 0) AS techo_tipo_4
            FROM ".$schema_table_vivienda_poblacion." WHERE ST_WITHIN(geom, ".$latlng_to_buffer.");");

            $response = [
                'incendios' => $query_incendios->getRowArray(),
                'superficie' => $query_superficie->getRowArray(),
                'pendiente' => $query_pendiente->getResultArray(),
                'combustible' =>  $query_combustible->getResultArray(),
                'poblacion' => $query_poblacion->getRowArray(),
                'vivienda_techos' => $query_mat_techo->getRowArray()
            ];
            return $this->response->setJSON($response);
        }
    }

    /** saves the configured interfaz based on the fires that occurred, creates and saves the APC  */
    public function saveInterfaz() {
        $loggedUser = $this->getActiveUser();

        if (!empty($_POST)) {
            $interfazModel = new InterfazModel($this->db);
            $causaModel = new CausaModel($this->db);
            $medidaEspeModel =  new MedidaEspecificaModel($this->db);
            $apcEspecificaModel = new ApcEspecificaModel($this->db);
            $medidaGeneModel =  new MedidaGeneralModel($this->db);
            $apcGeneralModel = new ApcGeneralModel($this->db);
                
            $interfaceName = $this->request->getPost('interfaz-nombre');
            $shape = $this->request->getPost('interfaz-forma');
            $risk = $this->request->getPost('interfaz-riesgo');
            $amenaza = $this->request->getPost('interfaz-amenaza');
            $vulnerabilidad = $this->request->getPost('interfaz-vulnerabilidad');
            $limpiezaTecho = $this->request->getPost('limpieza-techo');
            $resAgricolas = $this->request->getPost('residuos-agricolas');
            $resForestales = $this->request->getPost('residuos-forestales');
            $resDomesticos = $this->request->getPost('residuos-domesticos');
            $resIndustriales = $this->request->getPost('residuos-industriales');
            
            $interfaceShape = json_decode($shape);
            if ($interfaceShape[0] == 'Circle') {
                $lng = $interfaceShape[1][0];
                $lat = $interfaceShape[1][1];
                $radius = $interfaceShape[2];

                $latlng_to_geom = "ST_SetSRID(ST_GeomFromText('POINT(".$lng." ".$lat.")'), 4326)";
                $latlng_to_buffer = "ST_Buffer(ST_SetSRID(ST_MakePoint(".$lng.",".$lat."), 4326), (".$radius."*100/111.32))";
                
                $schema_table_incendios = 'public."incendios_quinquenio"';
                $schema_table_pendientes = 'public."pendientes_pais"';
                $schema_table_combustible = 'public."combustible"';
                $schema_table_vivienda_poblacion = 'public."vivienda_poblacion"';
                $query_prevalencia = $this->cp->query("SELECT id FROM ".$schema_table_incendios." WHERE ST_WITHIN(geom, ".$latlng_to_buffer.") AND id_especifico <> 999;");
                $query_pendiente = $this->cp->query("SELECT id, gridcode, (ST_Area(ST_Intersection(geom, ".$latlng_to_buffer.")) / ST_Area(".$latlng_to_buffer."))*100 AS pc_superficie FROM ".$schema_table_pendientes." WHERE ST_DWITHIN(geom, ".$latlng_to_geom.", ".$radius.");");
                $query_combustible = $this->cp->query("SELECT id, uso_tierra, puntaje, (ST_Area(ST_Intersection(geom, ".$latlng_to_buffer.")) / ST_Area(".$latlng_to_buffer."))*100 AS pc_superficie FROM ".$schema_table_combustible." WHERE ST_DWITHIN(geom, ".$latlng_to_geom.", ".$radius.");");
                $query_vivienda_poblacion = $this->cp->query("SELECT id FROM ".$schema_table_vivienda_poblacion." WHERE ST_WITHIN(geom, ".$latlng_to_buffer.");");
                $query_buffer = $this->cp->query("SELECT ".$latlng_to_buffer." AS buffer FROM ".$schema_table_incendios." LIMIT 1;");
                
                $interfaceData = array(
                    'nombre' => $interfaceName,
                    'interfaz_forma' => $shape,
                    'interfaz_geom' => $query_buffer->getRowArray()['buffer'],
                    'indice_riesgo' => (int)$risk,
                    'amenaza' => (int)$amenaza,
                    'vulnerabilidad' => (int)$vulnerabilidad,
                    'riesgo_historico' => json_encode([date("Y") => (int)$risk]),
                    'amenaza_historico' => json_encode([date("Y") => (int)$amenaza]),
                    'vulnerabilidad_historico' => json_encode([date("Y") => (int)$vulnerabilidad]),
                    'prevalencia' => json_encode($query_prevalencia->getResultArray()),
                    'pendiente' => json_encode($query_pendiente->getResultArray()),
                    'vegetacion_combustible' => json_encode($query_combustible->getResultArray()),
                    'vivienda_poblacion' => json_encode($query_vivienda_poblacion->getResultArray()),
                    'limpieza_techo' => (int)$limpiezaTecho,
                    'residuos_agricolas' => (int)$resAgricolas,
                    'residuos_forestales' => (int)$resForestales,
                    'residuos_domesticos' => (int)$resDomesticos,
                    'residuos_industriales' => (int)$resIndustriales,
                    'propietario_id' => (int)$loggedUser['usuario_id']
                );
                
                $interfaceSave = $interfazModel->insert($interfaceData);
                if (!$interfaceSave) {
                    return $this->response->setJSON(0);
                } else {
                    $savedInterfaz = $interfazModel->where('propietario_id', $loggedUser['usuario_id'])->orderBy('updated_at', 'DESC')->first();
                    
                    $aIDCausas = [];
                    $aCantidadCausasEspecificas = [];
                    $query_contador_especificas = $this->cp->query("SELECT COUNT(*) AS cantidad_especificas, id_especifico FROM ".$schema_table_incendios." WHERE ST_WITHIN(geom, ".$latlng_to_buffer.") AND id_especifico <> 999 GROUP BY id_especifico;");
                    $contador_causas_especificas = $query_contador_especificas->getResultArray();
                    foreach ($contador_causas_especificas as $value) {
                        array_push($aCantidadCausasEspecificas, intval($value['cantidad_especificas']));
                        array_push($aIDCausas, $value['id_especifico']);
                    }
                        
                    $query_causas = $causaModel->select('causa_id, nombre_causa_especifica, medidas, grupo_causa_especifica, causa_general, grupo')->whereIn('causa_id', $aIDCausas)->orderBy('causa_id', 'ASC')->get();
                    $data_causas = $query_causas->getResultArray();
                    $aNombreCausasEspecificas = [];
                    $aGrupoEspecificas = [];
                    $aCausaGeneral = [];
                    $aGrupoCausa = [];
                    foreach ($data_causas as $causa) {
                        array_push($aNombreCausasEspecificas, $causa['nombre_causa_especifica']);
                        array_push($aGrupoEspecificas, $causa['grupo_causa_especifica']);
                        array_push($aCausaGeneral, $causa['causa_general']);
                        array_push($aGrupoCausa, $causa['grupo']);
                    }

                    $query_grupos = $causaModel->select('grupo_causa_especifica')->whereIn('causa_id', $aIDCausas)->groupBy('grupo_causa_especifica')->get();
                    $data_grupos_especificos = $query_grupos->getResultArray();
                    $aSavedMedidasEspe = [];
                    foreach ($data_grupos_especificos as $grupo) {
                        $query_medidas_grupo = $causaModel->select('medidas')->where('grupo_causa_especifica', $grupo['grupo_causa_especifica'])->first();
                        $medidas = json_decode($query_medidas_grupo['medidas']);
                        $aMedidas = [];
                        for ($i = 0; $i < sizeof($medidas); $i++) {
                            $medidaToSave = array(
                                'propietario_id' => (int)$loggedUser['usuario_id'],
                                'nombre' => $medidas[$i]->nombre,
                                'grupo_objetivo' => $medidas[$i]->grupo_objetivo,
                                'zonas_objetivo' => $medidas[$i]->zonas_objetivo,
                                'responsable' => $medidas[$i]->responsable,
                                'contacto_responsable' => $medidas[$i]->contacto_responsable
                            );
                            $savedMedidaEspe = $medidaEspeModel->insert($medidaToSave);
                            if (!$savedMedidaEspe) {
                                return $this->response->setJSON(0);
                                break;
                            }
                            $idMedidaEspe = $medidaEspeModel->where('propietario_id', $loggedUser['usuario_id'])->getInsertID();
                            array_push($aMedidas, $idMedidaEspe);
                        }
                        $aSavedMedidasEspe[$grupo['grupo_causa_especifica']] = $aMedidas;
                    }
                    
                    $countSavedAPCEspecifica = 0;
                    for ($i=0; $i < sizeof($data_causas); $i++) {
                        $grupoEspe = '';
                        foreach ($aSavedMedidasEspe as $grupo => $medidas) {
                            if ($grupo == $aGrupoEspecificas[$i]) $grupoEspe = $medidas;
                        }
                        $apcToSave = array(
                            'interfaz' => (int)$savedInterfaz['interfaz_id'],
                            'nombre_interfaz' => $savedInterfaz['nombre'],
                            'propietario_id' => (int)$loggedUser['usuario_id'],
                            'causa_especifica' => $aNombreCausasEspecificas[$i],
                            'medidas_espe' => json_encode($grupoEspe),
                            'cantidad_causa_especifica' => $aCantidadCausasEspecificas[$i],
                            'grupo_especifica' => $aGrupoEspecificas[$i],
                            'causa_general' => $aCausaGeneral[$i],
                            'grupo_causa' => $aGrupoCausa[$i]
                        );
                        $savedAPC = $apcEspecificaModel->insert($apcToSave);
                        if ($savedAPC) $countSavedAPCEspecifica = $countSavedAPCEspecifica + 1;
                        else {
                            return $this->response->setJSON(0);
                            break;
                        }
                    }
                    
                    $medidasGenerales = [
                        ['Limpieza exterior de viviendas', '', '', 'Vecinos, Municipalidad', ''],
                        ['Limpieza de techos y patios de viviendas', '', '', 'Vecinos', ''],
                        ['Generar ordenanza local', 'para regular actividades y conductas de mayor riesgo', '', 'Municipalidad', ''],
                        ['Construcción de cortafuegos', '', '', 'Forestales, CONAF, Particulares', ''],
                        ['Mantención de cortafuegos', '', '', 'Forestales, CONAF, Particulares', ''],
                        ['Poda', '', '', 'Forestales, CONAF, Particulares', ''],
                        ['Raleo', '', '', 'Forestales, CONAF, Particulares', ''],
                        ['Reducción de combustible vegetal', '', '', 'Particulares, Forestales, Municipalidad', ''],
                        ['Control de microbasurales', '', '', 'Municipalidad, Vecinos', ''],
                        ['Despejar tendido eléctrico de ramas', '', '', 'Eléctricas, Municipalidad', ''],
                        ['Contrucción de señaléticas', '', '', 'Municipalidad, Empresas', '']
                    ];

                    $aIDMedidasGenerales = [];
                    foreach ($medidasGenerales as $medida) {
                        $medidaToSave = array(
                            'propietario_id' => (int)$loggedUser['usuario_id'],
                            'nombre' => $medida[0],
                            'objetivo' => $medida[1],
                            'zonas_objetivo' => $medida[2],
                            'responsable' => $medida[3],
                            'contacto_responsable' => $medida[4]
                        );
                        $savedMedidaGene = $medidaGeneModel->insert($medidaToSave);
                        if (!$savedMedidaGene) {
                            return $this->response->setJSON(0);
                            break;
                        }
                        $idMedidaGene = $medidaGeneModel->where('propietario_id', $loggedUser['usuario_id'])->getInsertID();
                        array_push($aIDMedidasGenerales, $idMedidaGene);
                    }

                    $apcGeneralToSave = array(
                        'interfaz' => (int)$savedInterfaz['interfaz_id'],
                        'nombre_interfaz' => $savedInterfaz['nombre'],
                        'propietario_id' => (int)$loggedUser['usuario_id'],
                        'medidas_gene' => json_encode($aIDMedidasGenerales)
                    );
                    $savedAPCGeneral = $apcGeneralModel->insert($apcGeneralToSave);

                    if ($savedAPCGeneral) return $this->response->setJSON(1);
                    else return $this->response->setJSON(0);
                }
            }
        }
    }

    /** gets the saved interfaz and return its data to the user */
    public function savedInterfazRiesgo($id = null) {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser) {
            $userData = ['userInfo' => $loggedUser];
            $interfazModel = new InterfazModel($this->db);
            $vivPblModel = new ViviendaPoblacionModel($this->cp);
            
            if (!empty($id)) $getInterfaz = $interfazModel->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz_id' => $id])->first();
            else $getInterfaz = $interfazModel->where('propietario_id', $loggedUser['usuario_id'])->orderBy('created_at', 'DESC')->first();
            
            if (!empty($getInterfaz)) {
                if (!empty($id)) $getViviendaPoblacion = $interfazModel->select('vivienda_poblacion')->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz_id' => $id])->first();
                else $getViviendaPoblacion = $interfazModel->select('vivienda_poblacion')->where('propietario_id', $loggedUser['usuario_id'])->orderBy('created_at', 'DESC')->first();
                $idsViviendaPoblacion = [];
                foreach (json_decode($getViviendaPoblacion['vivienda_poblacion']) as $data) {
                    array_push($idsViviendaPoblacion, $data->id);
                }
                
                /** gets data of poblacion */
                if (!empty($idsViviendaPoblacion)) {
                    $getDataPoblacion = $vivPblModel->select('COALESCE(SUM(personas), 0) AS n_habitantes, COALESCE(SUM(dens_pbl), 0) AS dens_habitantes, COALESCE(SUM(total_viv), 0) AS n_viviendas, COALESCE(SUM(dens_viv), 0) AS dens_viviendas')->whereIn('id', $idsViviendaPoblacion)->findAll()[0];
                    $getDataPoblacion['dens_habitantes'] = $getDataPoblacion['dens_habitantes'] / sizeof($idsViviendaPoblacion);
                    $getDataPoblacion['dens_viviendas'] = $getDataPoblacion['dens_viviendas'] / sizeof($idsViviendaPoblacion);
                    
                    /** gets data of Techo Viviendas */
                    $getDataViviendas = $vivPblModel->select('COALESCE(SUM(p03b_6), 0) AS techo_tipo_1a, COALESCE(SUM(p03b_5), 0) AS techo_tipo_1b, COALESCE(SUM(p03b_4), 0) AS techo_tipo_2, COALESCE(SUM(p03b_1), 0) AS techo_tipo_3, COALESCE(SUM(p03b_3), 0) AS techo_tipo_4')->whereIn('id', $idsViviendaPoblacion)->findAll()[0];
                    $totalViviendas = $getDataViviendas['techo_tipo_1a'] + $getDataViviendas['techo_tipo_1b'] + $getDataViviendas['techo_tipo_2'] + $getDataViviendas['techo_tipo_3'] + $getDataViviendas['techo_tipo_4'];
                    $puntuacionTipo1 = (($getDataViviendas['techo_tipo_1a'] + $getDataViviendas['techo_tipo_1b'])/$totalViviendas)*100;
                    $puntuacionTipo2 = ($getDataViviendas['techo_tipo_2']/$totalViviendas)*75;
                    $puntuacionTipo3 = ($getDataViviendas['techo_tipo_3']/$totalViviendas)*50;
                    $puntuacionTipo4 = ($getDataViviendas['techo_tipo_4']/$totalViviendas)*25;
                    $puntuacionTechosViviendas = round($puntuacionTipo1 + $puntuacionTipo2 + $puntuacionTipo3 + $puntuacionTipo4);
                } else {
                    $getDataPoblacion = ['n_habitantes' => 0, 'dens_habitantes' => 0, 'n_viviendas' => 0, 'dens_viviendas' => 0];
                    $getDataViviendas = 0;
                    $totalViviendas = 0;
                    $puntuacionTechosViviendas = 0;
                }
                
                /** gets data of vegetacion combustible */
                if (!empty($id)) $getCombustible = $interfazModel->select('vegetacion_combustible')->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz_id' => $id])->first();
                else $getCombustible = $interfazModel->select('vegetacion_combustible')->where('propietario_id', $loggedUser['usuario_id'])->orderBy('created_at', 'DESC')->first();
                
                $getCombustible = json_decode($getCombustible['vegetacion_combustible'], true);
                $dataTipoCombustible = [];
                foreach ($getCombustible as $combustible) {
                    if (array_key_exists($combustible['uso_tierra'], $dataTipoCombustible)) {
                        $dataTipoCombustible[$combustible['uso_tierra']][0] = $dataTipoCombustible[$combustible['uso_tierra']][0] + $combustible['pc_superficie'];
                    } else {
                        $dataTipoCombustible[$combustible['uso_tierra']][0] = $combustible['pc_superficie'];
                        $dataTipoCombustible[$combustible['uso_tierra']][1] = $combustible['puntaje'];
                    }
                }
                
                $response = [
                    'interfaz' => $getInterfaz,
                    'datosCombustible' => $dataTipoCombustible,
                    'datosPoblacion' => $getDataPoblacion,
                    'datosViviendas' => $getDataViviendas,
                    'totalViviendas' => $totalViviendas,
                    'puntuacionTechos' => $puntuacionTechosViviendas
                ];

                if (!empty($id)) return view('admin/templates/principal-panel', $userData).view('admin/manage-interfaces/riesgo/riesgo-interfaz', $response);
                else return view('admin/templates/principal-panel', $userData).view('admin/analysis/riesgo/view-riesgo', $response);
            } else {
                return view('admin/templates/principal-panel', $userData).view('admin/templates/no-interfaces');
            }
        }
    }

    /** gets the saved interfaz data for calculates riesgo */
    public function savedInterfaz($id = null) {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser) {
            $interfazModel = new InterfazModel($this->db);
            if (!empty($id)) $getInterfaz = $interfazModel->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz_id' => $id])->first();
            else $getInterfaz = $interfazModel->where('propietario_id', $loggedUser['usuario_id'])->orderBy('created_at', 'DESC')->first();
            return $this->response->setJSON($getInterfaz);
        }
    }

    /** gets the prevalencia data of the interfaz */
    public function savedPrevalenciaInterfaz($id = null) {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser) {
            $interfazModel = new InterfazModel($this->db);
            $incendiosModel = new IncendiosModel($this->cp);
            
            if (!empty($id)) $getPrevalencia = $interfazModel->select('prevalencia')->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz_id' => $id])->first();
            else $getPrevalencia = $interfazModel->select('prevalencia')->where('propietario_id', $loggedUser['usuario_id'])->orderBy('created_at', 'DESC')->first();
            
            $resPrevalenciaQuinquenio = [];
            $resPrevalenciaUltima = [];
            foreach (json_decode($getPrevalencia['prevalencia']) as $prevalencia) {
                $getDataIncendio = $incendiosModel->select('sup_total, mes_ocurre, temporada')->where('id', $prevalencia->id)->first();
                if ($getDataIncendio['temporada'] == 'Julio 2020 - Junio 2021') array_push($resPrevalenciaUltima, $getDataIncendio);
                else array_push($resPrevalenciaQuinquenio, $getDataIncendio);
            }

            $response = ['quinquenio' => $resPrevalenciaQuinquenio, 'ultima_temporada' => $resPrevalenciaUltima];
            return $this->response->setJSON($response);
        }
    }

    /** gets the pendiente data of the interfaz */
    public function savedPendienteInterfaz($id = null) {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser) {
            $interfazModel = new InterfazModel($this->db);
            if (!empty($id)) $getInterfaz = $interfazModel->select('pendiente')->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz_id' => $id])->first();
            else $getInterfaz = $interfazModel->select('pendiente')->where('propietario_id', $loggedUser['usuario_id'])->orderBy('created_at', 'DESC')->first();
            return $this->response->setJSON($getInterfaz);
        }
    }

    /** gets the causas data of the interfaz */
    public function getInterfazCausas($id = null) {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser) {
            $userData = ['userInfo' => $loggedUser];
            $interfazModel = new InterfazModel($this->db);
            $incendiosModel = new IncendiosModel($this->cp);
            
            if (!empty($id)) $getInterfaz = $interfazModel->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz_id' => $id])->first();
            else $getInterfaz = $interfazModel->where('propietario_id', $loggedUser['usuario_id'])->orderBy('created_at', 'DESC')->first();
            
            if (!empty($getInterfaz)) {
                if (!empty($id)) $getPrevalencia = $interfazModel->select('prevalencia')->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz_id' => $id])->first();
                else $getPrevalencia = $interfazModel->select('prevalencia')->where('propietario_id', $loggedUser['usuario_id'])->orderBy('created_at', 'DESC')->first();
                
                $causasInterfaz = [];
                foreach (json_decode($getPrevalencia['prevalencia']) as $incendio) {
                    $getCausa = $incendiosModel->select('id_especifico')->where('id', $incendio->id)->first();
                    if (!in_array($getCausa['id_especifico'], $causasInterfaz)) array_push($causasInterfaz, $getCausa['id_especifico']);
                }
                
                $response = [
                    'interfaz' => $getInterfaz,
                    'totalCausasInterfaz' => (int)sizeof($causasInterfaz)
                ];
                
                if (!empty($id)) return view('admin/templates/principal-panel', $userData).view('admin/manage-interfaces/causas/causas-interfaz', $response);
                else return view('admin/templates/principal-panel', $userData).view('admin/analysis/causas/view-causas', $response);
            } else {
                return view('admin/templates/principal-panel', $userData).view('admin/templates/no-interfaces');
            }

        }
    }

    /** gets the causas data of the interfaz by temporadas */
    public function getCausasTemporadas($id = null) {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser) {
            $interfazModel = new InterfazModel($this->db);
            $incendiosModel = new IncendiosModel($this->cp);

            if (!empty($id)) $getPrevalencia = $interfazModel->select('prevalencia')->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz_id' => $id])->first();
            else $getPrevalencia = $interfazModel->select('prevalencia')->where('propietario_id', $loggedUser['usuario_id'])->orderBy('created_at', 'DESC')->first();
            
            $resCausasQuinquenio = [];
            $resCausasUltima = [];
            foreach (json_decode($getPrevalencia['prevalencia']) as $prevalencia) {
                $getDataIncendio = $incendiosModel->select('id_especifico, temporada')->where('id', $prevalencia->id)->first();
                if ($getDataIncendio['temporada'] == 'Julio 2020 - Junio 2021') array_push($resCausasUltima, $getDataIncendio);
                else array_push($resCausasQuinquenio, $getDataIncendio);
            }

            $result = ['quinquenio' => $resCausasQuinquenio, 'ultima_temporada' => $resCausasUltima];
            return $this->response->setJSON($result);
        }
    }

    /** generates a sintesis causas of the interfaz based on the fires that occurred */
    public function showCausasSintesis($id = null) {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser) {
            $userData = ['userInfo' => $loggedUser];
            $interfazModel = new InterfazModel($this->db);
            $apcEspeModel = new ApcEspecificaModel($this->db);
            
            if (!empty($id)) $getInterfaz = $interfazModel->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz_id' => $id])->first();
            else $getInterfaz = $interfazModel->where('propietario_id', $loggedUser['usuario_id'])->orderBy('created_at', 'DESC')->first();
            
            if (!empty($getInterfaz)) {
                $getCausas = $apcEspeModel->select('SUM(cantidad_causa_especifica) AS cantidad, grupo_causa')->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz' => $getInterfaz['interfaz_id'], 'deleted' => false])->groupBy('grupo_causa')->orderBy('cantidad', 'DESC')->findAll();
                $getGrupoEspecificas = $apcEspeModel->select('SUM(cantidad_causa_especifica) AS cantidad, grupo_causa, grupo_especifica')->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz' => $getInterfaz['interfaz_id'], 'deleted' => false])->groupBy('grupo_causa, grupo_especifica')->orderBy('cantidad', 'DESC')->findAll();
                
                $totalCausas = 0;
                foreach ($getCausas as $causa) {
                    $totalCausas = $totalCausas + $causa['cantidad'];
                }

                $response = [
                    'interfaz' => $getInterfaz,
                    'grupoCausas' => $getCausas,
                    'totalCausas' => $totalCausas,
                    'grupoEspecificas' => $getGrupoEspecificas
                ];
                
                if (!empty($id)) return view('admin/templates/principal-panel', $userData).view('admin/manage-interfaces/sintesis/sintesis-interfaz', $response);
                else return view('admin/templates/principal-panel', $userData).view('admin/analysis/sintesis/view-sintesis', $response);
            } else {
                return view('admin/templates/principal-panel', $userData).view('admin/templates/no-interfaces');
            }
        }
    }

    /** return the causas especificas that are within a group of causas especificas */
    public function showAPCSintesisByGroup($id = null) {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser && !empty($_POST)) {
            $interfazModel = new InterfazModel($this->db);
            $apcEspeModel = new ApcEspecificaModel($this->db);

            $grupoCausa = $this->request->getPost('grupo-causa');
            $grupoEspecifica = $this->request->getPost('grupo-especifica');
            
            if (!empty($id)) $getInterfaz = $interfazModel->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz_id' => $id])->first();
            else $getInterfaz = $interfazModel->where('propietario_id', $loggedUser['usuario_id'])->orderBy('created_at', 'DESC')->first();
            
            $getCausasEspecificas = $apcEspeModel->select('SUM(cantidad_causa_especifica) AS cantidad, grupo_causa, grupo_especifica, causa_especifica')->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz' => $getInterfaz['interfaz_id'], 'deleted' => false, 'grupo_causa' => $grupoCausa, 'grupo_especifica' => $grupoEspecifica])->groupBy('grupo_causa, grupo_especifica, causa_especifica')->orderBy('cantidad', 'DESC')->findAll();
            return $this->response->setJSON($getCausasEspecificas);
        }
    }

    /** return the group of causas especificas that are within interfaz and their medidas */
    public function showGrupoCausasEspecificas($id = null) {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser) {
            $userData = ['userInfo' => $loggedUser];

            $interfazModel = new InterfazModel($this->db);
            $apcEspecificaModel = new ApcEspecificaModel($this->db);
            $medidaEspecificaModel = new MedidaEspecificaModel($this->db);

            if (!empty($id)) $getInterfaz = $interfazModel->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz_id' => $id])->first();
            else $getInterfaz = $interfazModel->where('propietario_id', $loggedUser['usuario_id'])->orderBy('created_at', 'DESC')->first();

            if (!empty($getInterfaz)) {
                $getGrupoCausas = $apcEspecificaModel->select('SUM(cantidad_causa_especifica) AS cantidad, grupo_especifica')->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz' => $getInterfaz['interfaz_id']])->orderBy('cantidad', 'DESC')->groupBy('grupo_especifica')->findAll();
            
                $getMedidasGrupo = [];
                $getIDGrupoEspecifica = [];
                foreach ($getGrupoCausas as $grupo) {
                    $medidasGrupo = $apcEspecificaModel->select('medidas_espe')->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz' => $getInterfaz['interfaz_id'], 'grupo_especifica' => $grupo['grupo_especifica']])->first();
                    $idAPC = $apcEspecificaModel->select('apc_espe_id')->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz' => $getInterfaz['interfaz_id'], 'grupo_especifica' => $grupo['grupo_especifica']])->first();
                    $getIDGrupoEspecifica[$grupo['grupo_especifica']] = $idAPC;
                    $dataMedidas = [];
                    foreach (json_decode($medidasGrupo['medidas_espe']) as $idMedida) {
                        $getMedida = $medidaEspecificaModel->where('medida_espe_id', $idMedida)->find();
                        $getMedida[0]['grupo'] = $grupo['grupo_especifica'];
                        array_push($dataMedidas, $getMedida[0]);
                    }
                    $getMedidasGrupo[$grupo['grupo_especifica']] = $dataMedidas;
                }

                $response = [
                    'interfaz' => $getInterfaz,
                    'grupoCausas' => $getGrupoCausas,
                    'medidasGrupos' => $getMedidasGrupo,
                    'identificadoresAPCEspecifica' => $getIDGrupoEspecifica
                ];

                if (!empty($id)) return view('admin/templates/principal-panel', $userData).view('admin/manage-interfaces/especifica/list-apc-interfaz', $response);
                else return view('admin/templates/principal-panel', $userData).view('admin/generate-apc/especifica/list-apc-especifica', $response);
            } else {
                return view('admin/templates/principal-panel', $userData).view('admin/templates/no-interfaces');
            }
        }
    }

    /** return the causas especificas that are within a group */
    public function showGrupoEspecifica($idGrupo = null, $idInterfaz = null) {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser) {
            $userData = ['userInfo' => $loggedUser];
            $apcEspecificaModel = new ApcEspecificaModel($this->db);
            $medidaEspecificaModel = new MedidaEspecificaModel($this->db);

            if (!empty($idInterfaz)) $getAPC = $apcEspecificaModel->where(['propietario_id' => $loggedUser['usuario_id'], 'apc_espe_id' => $idGrupo, 'interfaz' => $idInterfaz])->first();
            else $getAPC = $apcEspecificaModel->where(['propietario_id' => $loggedUser['usuario_id'], 'apc_espe_id' => $idGrupo])->first();
            
            if (!empty($getAPC)) {
                $aMedidasAPC = [];
                foreach (json_decode($getAPC['medidas_espe']) as $medida) {
                    $dataMedida = $medidaEspecificaModel->where('medida_espe_id', $medida)->first();
                    array_push($aMedidasAPC, $dataMedida);
                }
                
                $response = [
                    'apc' => $getAPC,
                    'medidas' => $aMedidasAPC
                ];

                if (!empty($idInterfaz)) return view('admin/templates/principal-panel', $userData).view('admin/manage-interfaces/especifica/view-apc-interfaz', $response);
                else return view('admin/templates/principal-panel', $userData).view('admin/generate-apc/especifica/view-apc', $response);
            } else {
                return view('admin/templates/principal-panel', $userData).view('admin/templates/no-interfaces');
            }
        }
    }

    /** return the view for edit the group of causas especificas */
    public function editGrupoEspecifica($idGrupo = null, $idInterfaz = null) {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser) {
            $userData = ['userInfo' => $loggedUser];
            $apcEspecificaModel = new ApcEspecificaModel($this->db);
            $medidaEspecificaModel = new MedidaEspecificaModel($this->db);
            $causaModel = new CausaModel($this->db);

            if (!empty($idInterfaz)) $getAPC = $apcEspecificaModel->where(['propietario_id' => $loggedUser['usuario_id'], 'apc_espe_id' => $idGrupo, 'interfaz' => $idInterfaz])->first();
            else $getAPC = $apcEspecificaModel->where(['propietario_id' => $loggedUser['usuario_id'], 'apc_espe_id' => $idGrupo])->first();
            
            if (!empty($getAPC)) {
                $aMedidasAPC = [];
                foreach (json_decode($getAPC['medidas_espe']) as $medida) {
                    $dataMedida = $medidaEspecificaModel->where('medida_espe_id', $medida)->first();
                    array_push($aMedidasAPC, $dataMedida);
                }
                
                $getMedidasGrupo = $causaModel->where('grupo_causa_especifica', $getAPC['grupo_especifica'])->first();
                $medidasGrupo = json_decode($getMedidasGrupo['medidas'], true);

                $response = [
                    'apc' => $getAPC,
                    'medidas' => $aMedidasAPC,
                    'medidasGrupo' => $medidasGrupo
                ];
                
                if (!empty($idInterfaz)) return view('admin/templates/principal-panel', $userData).view('admin/manage-interfaces/especifica/edit-apc-interfaz', $response);
                else return view('admin/templates/principal-panel', $userData).view('admin/generate-apc/especifica/edit-apc', $response);
            } else {
                return view('admin/templates/principal-panel', $userData).view('admin/templates/no-interfaces');
            }
        }
    }

    /** update the group of causas especificas */
    public function updateGrupoEspecifica() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser && !empty($_POST)) {
            $medidaEspecificaModel = new MedidaEspecificaModel($this->db);

            $medidaID = $this->request->getPost('medida-id');
            $grupos = $this->request->getPost('medida-grupo-objetivo');
            $zonas = $this->request->getPost('medida-zonas-objetivo');
            $responsable = $this->request->getPost('medida-responsable');
            $contactoResponsable = $this->request->getPost('medida-contacto-responsable');
            $inicio = $this->request->getPost('medida-fecha-inicio');
            $termino = $this->request->getPost('medida-fecha-termino');
            $avance = $this->request->getPost('medida-avance');
            
            $medidaToUpdate = array(
                'grupo_objetivo' => $grupos,
                'zonas_objetivo' => $zonas,
                'responsable' => $responsable,
                'contacto_responsable' => $contactoResponsable,
                'fecha_inicio' => $inicio,
                'fecha_termino' => $termino,
                'avance' => $avance
            );
            $updateMedidaEspecifica = $medidaEspecificaModel->update($medidaID, $medidaToUpdate);

            if (!$updateMedidaEspecifica) return $this->response->setJSON(0);
            else return $this->response->setJSON(1);
        }
    }

    /** adds a medida to group of causas especificas */
    public function addMedidaGrupoEspecifica() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser && !empty($_POST)) {
            $medidaEspeModel = new MedidaEspecificaModel($this->db);
            $apcEspecificaModel = new ApcEspecificaModel($this->db);

            $apcID = $this->request->getPost('apc-id');
            $medida = $this->request->getPost('medida-nombre');
            $grupo = $this->request->getPost('medida-grupo');
            $zonas = $this->request->getPost('medida-zonas');
            $responsable = $this->request->getPost('medida-responsable');
            $contactoResponsable = $this->request->getPost('medida-responsable-contacto');
            $inicio = $this->request->getPost('medida-fecha-inicio');
            $termino = $this->request->getPost('medida-fecha-termino');
            $avance = $this->request->getPost('medida-avance');

            $medidaToSave = array(
                'propietario_id' => (int)$loggedUser['usuario_id'],
                'nombre' => $medida,
                'grupo_objetivo' => $grupo,
                'zonas_objetivo' => $zonas,
                'responsable' => $responsable,
                'contacto_responsable' => $contactoResponsable,
                'fecha_inicio' => date('Y-m-d', strtotime($inicio)),
                'fecha_termino' => date('Y-m-d', strtotime($termino)),
                'avance' => $avance
            );
            
            $savedMedidaEspe = $medidaEspeModel->insert($medidaToSave);
            $idMedidaEspecifica = $medidaEspeModel->where('propietario_id', $loggedUser['usuario_id'])->getInsertID();
            $causaToUpdate = $apcEspecificaModel->where(['propietario_id' => $loggedUser['usuario_id'], 'apc_espe_id' => $apcID])->first();
            $aMedidas = [];
            foreach (json_decode($causaToUpdate['medidas_espe']) as $medida) {
                array_push($aMedidas, $medida);
            }
            array_push($aMedidas, $idMedidaEspecifica);
            
            $apcsToUpdate = $apcEspecificaModel->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz' => $causaToUpdate['interfaz'], 'grupo_especifica' => $causaToUpdate['grupo_especifica']])->findAll();
            $allUpdated = 0;
            foreach ($apcsToUpdate as $apc) {
                $apc['medidas_espe'] = json_encode($aMedidas);
                $causaToSave = $apcEspecificaModel->update($apc['apc_espe_id'], $apc);
                if (!$causaToSave) {
                    $allUpdated = 1;
                    break;
                }
            }

            if ($allUpdated == 0) return $this->response->setJSON(1);
            else return $this->response->setJSON(0);
        }
    }

    /** delete a medida of the group of causas especificas */
    public function deleteMedidaGrupoEspecifica() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser && !empty($_POST)) {
            $apcEspeModel = new ApcEspecificaModel($this->db);
            $medidaEspeModel = new MedidaEspecificaModel($this->db);

            $apcEspeID = $this->request->getPost('apc-id');
            $medidaID = $this->request->getPost('apc-medida-id');

            $causaToUpdate = $apcEspeModel->where(['propietario_id' => $loggedUser['usuario_id'], 'apc_espe_id' => $apcEspeID])->first();
            $aMedidas = [];
            foreach (json_decode($causaToUpdate['medidas_espe']) as $medida) {
                if ($medida != $medidaID) array_push($aMedidas, $medida);
            }

            $apcsToUpdate = $apcEspeModel->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz' => $causaToUpdate['interfaz'], 'grupo_especifica' => $causaToUpdate['grupo_especifica']])->findAll();
            $allUpdated = 0;
            foreach ($apcsToUpdate as $apc) {
                $apc['medidas_espe'] = json_encode($aMedidas);
                $causaToSave = $apcEspeModel->update($apc['apc_espe_id'], $apc);
                if (!$causaToSave) {
                    $allUpdated = 1;
                    break;
                }
            }
            
            if ($allUpdated != 0) return $this->response->setJSON(0);
            else {
                $medidaToDelete = $medidaEspeModel->where(['propietario_id' => $loggedUser['usuario_id'], 'medida_espe_id' => $medidaID])->delete();
                if (!$medidaToDelete) return $this->response->setJSON(0);
                else return $this->response->setJSON(1);
            }
        }
    }

    /** delete a multiple medidas of the group of causas especificas */
    public function deleteMultiplesMedidasEspecificas() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser && !empty($_POST)) {
            $interfazModel = new InterfazModel($this->db);
            $apcEspeModel = new ApcEspecificaModel($this->db);
            $medidaEspeModel = new MedidaEspecificaModel($this->db);

            $medidas = $this->request->getPost('medidas');
            $apcEspeID = $this->request->getPost('apc-id');

            $getAPC = $apcEspeModel->where(['propietario_id' => $loggedUser['usuario_id'], 'apc_espe_id' => $apcEspeID])->first();
            $getAPCs = $apcEspeModel->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz' => $getAPC['interfaz'], 'grupo_especifica' => $getAPC['grupo_especifica']])->findAll();
            
            $deletedMedidas = 0;
            foreach ($medidas as $idMedida) {
                $medidaToDelete = $medidaEspeModel->where(['propietario_id' => $loggedUser['usuario_id'], 'medida_espe_id' => $idMedida])->delete();    
                if ($medidaToDelete) $deletedMedidas = $deletedMedidas + 1;
                else {
                    return $this->response->setJSON(0);
                    break;
                }
            }
            if ($deletedMedidas == sizeof($medidas)) {
                $medidasAPC = json_decode($getAPC['medidas_espe'], true);
                $diffMedidas = array_values(array_diff($medidasAPC, $medidas));
                $savedAPCs = 0;
                foreach ($getAPCs as $apc) {
                    $apc['medidas_espe'] = json_encode($diffMedidas);
                    $apcToUpdate = $apcEspeModel->update($apc['apc_espe_id'], $apc);
                    if ($apcToUpdate) $savedAPCs = $savedAPCs + 1;
                    else {
                        return $this->response->setJSON(0);
                        break;
                    }
                }
                if ($savedAPCs == sizeof($getAPCs)) return $this->response->setJSON(1);
            }
        }
    }

    /** delete a group of causas especificas */
    public function deleteGrupoEspecifica() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser && !empty($_POST)) {
            $apcEspeModel = new ApcEspecificaModel($this->db);
            $medidaEspeModel = new MedidaEspecificaModel($this->db);

            $grupo = $this->request->getPost('nombre-grupo');
            $apcEspeID = $this->request->getPost('id-apc');
            
            $interfaz = $apcEspeModel->where(['propietario_id' => $loggedUser['usuario_id'], 'apc_espe_id' => $apcEspeID])->first()['interfaz'];
            $getAPCEspe = $apcEspeModel->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz' => $interfaz, 'grupo_especifica' => $grupo])->findAll();
            
            $medidas = json_decode($getAPCEspe[0]['medidas_espe']);
            $deletedMedidas = 0;
            foreach ($medidas as $medida) {
                $medidaToDelete = $medidaEspeModel->where('medida_espe_id', $medida)->delete();
                if (!$medidaToDelete) {
                    return $this->response->setJSON(0);
                    break;
                } else {
                    $deletedMedidas = $deletedMedidas + 1;
                }
            }

            if ($deletedMedidas == sizeof($medidas)) {
                $allDeleted = 0;
                foreach ($getAPCEspe as $apc) {
                    $deleteAPCEspe = $apcEspeModel->where('apc_espe_id', $apc['apc_espe_id'])->delete();
                    if (!$deleteAPCEspe) {
                        return $this->response->setJSON(0);
                        break;
                    } else {
                        $allDeleted = $allDeleted + 1;
                    }
                }
                if ($allDeleted == sizeof($getAPCEspe)) return $this->response->setJSON(1);
                else return $this->response->setJSON(0);
            }
        }
    }

    /** return a view with the medidas generales for interfaz */
    public function showMedidasGenerales($id = null) {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser) {
            $userData = ['userInfo' => $loggedUser];
            $interfazModel = new InterfazModel($this->db);
            $apcGeneralModel = new ApcGeneralModel($this->db);
            $medidaGeneralModel = new MedidaGeneralModel($this->db);

            if (!empty($id)) $getInterfaz = $interfazModel->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz_id' => $id])->first();
            else $getInterfaz = $interfazModel->where('propietario_id', $loggedUser['usuario_id'])->orderBy('created_at', 'DESC')->first();
            
            if (!empty($getInterfaz)) {
                $getAPC = $apcGeneralModel->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz' => $getInterfaz['interfaz_id']])->first();
                $medidasGenerales = [];
                foreach (json_decode($getAPC['medidas_gene']) as $medidaID) {
                    $dataMedida = $medidaGeneralModel->where(['propietario_id' => $loggedUser['usuario_id'], 'medida_gene_id' => $medidaID])->first();
                    array_push($medidasGenerales, $dataMedida);
                }

                $setMedidasGenerales = [
                    ['Limpieza exterior de viviendas', '', '', 'Vecinos, Municipalidad', ''],
                    ['Limpieza de techos y patios de viviendas', '', '', 'Vecinos', ''],
                    ['Generar ordenanza local', 'para regular actividades y conductas de mayor riesgo', '', 'Municipalidad', ''],
                    ['Construcción de cortafuegos', '', '', 'Forestales, CONAF, Particulares', ''],
                    ['Mantención de cortafuegos', '', '', 'Forestales, CONAF, Particulares', ''],
                    ['Poda', '', '', 'Forestales, CONAF, Particulares', ''],
                    ['Raleo', '', '', 'Forestales, CONAF, Particulares', ''],
                    ['Reducción de combustible vegetal', '', '', 'Particulares, Forestales, Municipalidad', ''],
                    ['Control de microbasurales', '', '', 'Municipalidad, Vecinos', ''],
                    ['Despejar tendido eléctrico de ramas', '', '', 'Eléctricas, Municipalidad', ''],
                    ['Contrucción de señaléticas', '', '', 'Municipalidad, Empresas', '']
                ];

                $response = [
                    'interfaz' => $getInterfaz,
                    'apcGeneral' => $getAPC,
                    'medidasGenerales' => $medidasGenerales,
                    'medidas' => $setMedidasGenerales
                ];
                if (!empty($id)) return view('admin/templates/principal-panel', $userData).view('admin/manage-interfaces/general/list-apc-interfaz', $response);
                else return view('admin/templates/principal-panel', $userData).view('admin/generate-apc/general/list-apc-general', $response);
            } else {
                return view('admin/templates/principal-panel', $userData).view('admin/templates/no-interfaces');
            }
        }
    }

    /** return the details of a medida general */
    public function showMedidaGeneral($idMedida = null, $idInterfaz = null) {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser) {
            $userData = ['userInfo' => $loggedUser];
            $interfazModel = new InterfazModel($this->db);
            $apcGeneralModel = new ApcGeneralModel($this->db);
            $medidaGeneralModel = new MedidaGeneralModel($this->db);

            if (!empty($idInterfaz)) $getAPC = $apcGeneralModel->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz' => $idInterfaz])->first();
            else $getAPC = $apcGeneralModel->where('propietario_id', $loggedUser['usuario_id'])->orderBy('created_at', 'DESC')->first();
            
            $getMedida = $medidaGeneralModel->where(['propietario_id' => $loggedUser['usuario_id'], 'medida_gene_id' => $idMedida])->first();
            
            $response = [
                'apc' => $getAPC,
                'medida' => $getMedida
            ];

            if (!empty($idInterfaz)) return view('admin/templates/principal-panel', $userData).view('admin/manage-interfaces/general/view-apc-interfaz', $response);
            else return view('admin/templates/principal-panel', $userData).view('admin/generate-apc/general/view-apc', $response);
        }
    }

    /** return a view for edit a medida general */
    public function editMedidaGeneral($idMedida = null, $idInterfaz = null) {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser) {
            $userData = ['userInfo' => $loggedUser];
            $interfazModel = new InterfazModel($this->db);
            $apcGeneralModel = new ApcGeneralModel($this->db);
            $medidaGeneralModel = new MedidaGeneralModel($this->db);

            if (!empty($idInterfaz)) $getAPC = $apcGeneralModel->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz' => $idInterfaz])->first();
            else $getAPC = $apcGeneralModel->where('propietario_id', $loggedUser['usuario_id'])->orderBy('created_at', 'DESC')->first();
            
            $getMedida = $medidaGeneralModel->where(['propietario_id' => $loggedUser['usuario_id'], 'medida_gene_id' => $idMedida])->first();

            $response = [
                'apc' => $getAPC,
                'medida' => $getMedida
            ];

            if (!empty($idInterfaz)) return view('admin/templates/principal-panel', $userData).view('admin/manage-interfaces/general/edit-apc-interfaz', $response);
            else return view('admin/templates/principal-panel', $userData).view('admin/generate-apc/general/edit-apc', $response);
        }
    }

    /** updates information of a medida general */
    public function updateMedidaGeneral() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser && !empty($_POST)) {
            $medidaGeneModel = new MedidaGeneralModel($this->db);

            $idMedida = $this->request->getPost('medida-id');
            $zonas = $this->request->getPost('medida-zonas');
            $responsable = $this->request->getPost('medida-responsable');
            $contactoResponsable = $this->request->getPost('medida-contacto-responsable');
            $inicio = $this->request->getPost('medida-fecha-inicio');
            $termino = $this->request->getPost('medida-fecha-termino');
            $avance = $this->request->getPost('medida-avance');
            
            $medidaToUpdate = $medidaGeneModel->where(['propietario_id' => $loggedUser['usuario_id'], 'medida_gene_id' => $idMedida])->first();
            $medidaToUpdate['zonas_objetivo'] = $zonas;
            $medidaToUpdate['responsable'] = $responsable;
            $medidaToUpdate['contacto_responsable'] = $contactoResponsable;
            $medidaToUpdate['fecha_inicio'] = $inicio;
            $medidaToUpdate['fecha_termino'] = $termino;
            $medidaToUpdate['avance'] = $avance;
            $medidaToSave = $medidaGeneModel->update($medidaToUpdate['medida_gene_id'], $medidaToUpdate);

            if (!$medidaToSave) return $this->response->setJSON(0);
            else return $this->response->setJSON(1);
        }
    }

    /** adds a medida general */
    public function addMedidaGeneral() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser && !empty($_POST)) {
            $medidaGeneModel = new MedidaGeneralModel($this->db);
            $apcGeneModel = new ApcGeneralModel($this->db);

            $apcID = $this->request->getPost('apc-id');
            $medida = $this->request->getPost('medida-nombre');
            $objetivo = $this->request->getPost('medida-objetivo');
            $zonas = $this->request->getPost('medida-zonas');
            $responsable = $this->request->getPost('medida-responsable');
            $contactoResponsable = $this->request->getPost('medida-contacto-responsable');
            $inicio = $this->request->getPost('medida-fecha-inicio');
            $termino = $this->request->getPost('medida-fecha-termino');
            $avance = $this->request->getPost('medida-avance');

            $medidaToSave = array(
                'propietario_id' => (int)$loggedUser['usuario_id'],
                'nombre' => $medida,
                'objetivo' => $objetivo,
                'zonas_objetivo' => $zonas,
                'responsable' => $responsable,
                'contacto_responsable' => $contactoResponsable,
                'fecha_inicio' => date('Y-m-d', strtotime($inicio)),
                'fecha_termino' => date('Y-m-d', strtotime($termino)),
                'avance' => $avance
            );
            $savedMedidaGeneral = $medidaGeneModel->insert($medidaToSave);
            $idMedidaGeneral = $medidaGeneModel->where('propietario_id', $loggedUser['usuario_id'])->getInsertID();
            
            if (!empty($apcID)) {
                $apcToUpdate = $apcGeneModel->where(['propietario_id' => $loggedUser['usuario_id'], 'apc_gene_id' => $apcID])->first();
                $aMedidas = json_decode($apcToUpdate['medidas_gene']);
                array_push($aMedidas, $idMedidaGeneral);
                $apcToUpdate['medidas_gene'] = json_encode($aMedidas);

                $apcToSave = $apcGeneModel->update($apcToUpdate['apc_gene_id'], $apcToUpdate);
                if (!$apcToSave) return $this->response->setJSON(0);
                else return $this->response->setJSON(1);
            } else {
                $medidaGeneModel->where('medida_gene_id', $idMedidaGeneral)->delete();
                return $this->response->setJSON(0);
            }
        }
    }

    /** deletes a medida general */
    public function deleteMedidaGeneral() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser && !empty($_POST)) {
            $userData = ['userInfo' => $loggedUser];
            $apcGeneralModel = new ApcGeneralModel($this->db);
            $medidaGeneralModel = new MedidaGeneralModel($this->db);

            $idMedida = $this->request->getPost('id-medida');
            $idInterfaz = $this->request->getPost('id-interfaz-apc');
            
            if (!empty($idMedida) && !empty($idInterfaz)) {
                $getAPC = $apcGeneralModel->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz' => $idInterfaz])->first();
                $medidaToDelete = $medidaGeneralModel->where(['propietario_id' => $loggedUser['usuario_id'], 'medida_gene_id' => $idMedida])->delete();
                
                if (!$medidaToDelete) return $this->response->setJSON(0);
                else {
                    $medidasGenerales = [];
                    foreach (json_decode($getAPC['medidas_gene'], true) as $medidaID) {
                        if ($medidaID != $idMedida) array_push($medidasGenerales, $medidaID);
                    }
                    $getAPC['medidas_gene'] = json_encode($medidasGenerales);
                    $apcToUpdate = $apcGeneralModel->update($getAPC['apc_gene_id'], $getAPC);

                    if (!$apcToUpdate) return $this->response->setJSON(0);
                    else return $this->response->setJSON(1);
                }
            } else {
                return $this->response->setJSON(0);
            }
        }
    }

    /** delete multiples medidas generales */
    public function deleteMultiplesMedidasGenerales() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser && !empty($_POST)) {
            $apcGeneralModel = new ApcGeneralModel($this->db);
            $medidaGeneralModel = new MedidaGeneralModel($this->db);

            $medidas = $this->request->getPost('medidas');
            $idInterfaz = $this->request->getPost('id-interfaz-apc');

            $getAPC = $apcGeneralModel->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz' => $idInterfaz])->first();
            $deletedMedidas = 0;
            foreach ($medidas as $idMedida) {
                $medidaToDelete = $medidaGeneralModel->where(['propietario_id' => $loggedUser['usuario_id'], 'medida_gene_id' => $idMedida])->delete();    
                if ($medidaToDelete) $deletedMedidas = $deletedMedidas + 1;
                else {
                    return $this->response->setJSON(0);
                    break;
                }
            }
            if ($deletedMedidas == sizeof($medidas)) {
                $medidasAPC = json_decode($getAPC['medidas_gene'], true);
                $getAPC['medidas_gene'] = json_encode(array_values(array_diff($medidasAPC, $medidas)));
                $apcToUpdate = $apcGeneralModel->update($getAPC['apc_gene_id'], $getAPC);

                if (!$apcToUpdate) return $this->response->setJSON(0);
                else return $this->response->setJSON(1);
            }
        }
    }
    
    /** return a view with interfaces created by user */
    public function showAllGestionarInterfaces() {
        $loggedUser = $this->getActiveUser();
        $userData = ['userInfo' => $loggedUser];
        $interfazModel = new InterfazModel($this->db);
        $incendiosModel = new IncendiosModel($this->cp);

        /** gets data of interfaces */
        $getInterfaces = $interfazModel->select('interfaz_id, nombre, indice_riesgo, amenaza, vulnerabilidad, updated_at')->where('propietario_id', $loggedUser['usuario_id'])->orderBy('created_at', 'DESC')->paginate(10);
        
        if (!empty($getInterfaces)) {
            /** gets the number of fires and surfaces affected in the interfaz  */
            $getIncendioDano = $interfazModel->select('interfaz_id, prevalencia')->where('propietario_id', $loggedUser['usuario_id'])->findAll();
            $aCantidadIncendios = [];
            $aSuperficieAfectada = [];
            foreach ($getIncendioDano as $incendios) {
                $interfaz_id = json_decode($incendios['interfaz_id'], true);
                $interfaz_prevalencia = json_decode($incendios['prevalencia'], true);
                $SupAfectada = 0;
                $NumIncendios = 0;
                foreach ($interfaz_prevalencia as $prevalencia) {
                    $query_superf_afectada = $incendiosModel->where('id', $prevalencia['id'])->select('sup_total')->get();
                    $SupAfectada = $SupAfectada + $query_superf_afectada->getRow()->sup_total;
                    $NumIncendios = $NumIncendios + 1;
                }
                $aCantidadIncendios[$interfaz_id] = $NumIncendios;
                $aSuperficieAfectada[$interfaz_id] = $SupAfectada;
            }
            
            $response = [
                'interfaces' => $getInterfaces,
                'cantIncendios' => $aCantidadIncendios,
                'supAfectada' => $aSuperficieAfectada,
                'pager' => $interfazModel->pager
            ];
            return view('admin/templates/principal-panel', $userData).view('admin/manage-interfaces/mi-list-interfaces', $response);
        } else {
            return view('admin/templates/principal-panel', $userData).view('admin/templates/no-interfaces');
        }
    }

    /** return a view with details of the APC */
    public function showAgendaInterfaz($id = null) {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser && !empty($id)) {
            $interfazModel = new InterfazModel($this->db);
            $apcEspecificaModel = new ApcEspecificaModel($this->db);
            $apcGeneralModel = new ApcGeneralModel($this->db);
            $medidaEspecificaModel = new MedidaEspecificaModel($this->db);
            $medidaGeneralModel = new MedidaGeneralModel($this->db);
            $userData = ['userInfo' => $loggedUser];
            
            $getInterfaz = $interfazModel->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz_id' => $id])->first();
            if (!empty($getInterfaz)) {
                /** gets medidas especificas of interfaz */
                $getGrupoCausas = $apcEspecificaModel->select('SUM(cantidad_causa_especifica) AS cantidad, grupo_especifica')->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz' => $getInterfaz['interfaz_id']])->orderBy('cantidad', 'DESC')->groupBy('grupo_especifica')->findAll();
                $getMedidasGrupo = [];
                $getIDGrupoEspecifica = [];
                foreach ($getGrupoCausas as $grupo) {
                    $medidasGrupo = $apcEspecificaModel->select('medidas_espe')->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz' => $getInterfaz['interfaz_id'], 'grupo_especifica' => $grupo['grupo_especifica']])->first();
                    $idAPC = $apcEspecificaModel->select('apc_espe_id')->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz' => $getInterfaz['interfaz_id'], 'grupo_especifica' => $grupo['grupo_especifica']])->first();
                    $getIDGrupoEspecifica[$grupo['grupo_especifica']] = $idAPC;
                    $dataMedidas = [];
                    foreach (json_decode($medidasGrupo['medidas_espe']) as $idMedida) {
                        $getMedida = $medidaEspecificaModel->where('medida_espe_id', $idMedida)->find();
                        $getMedida[0]['grupo'] = $grupo['grupo_especifica'];
                        array_push($dataMedidas, $getMedida[0]);
                    }
                    $getMedidasGrupo[$grupo['grupo_especifica']] = $dataMedidas;
                }

                /** gets medidas generales of interfaz */
                $getAPC = $apcGeneralModel->select('medidas_gene')->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz' => $getInterfaz['interfaz_id']])->first();
                $medidasGenerales = [];
                foreach (json_decode($getAPC['medidas_gene']) as $medidaID) {
                    $dataMedida = $medidaGeneralModel->where(['propietario_id' => $loggedUser['usuario_id'], 'medida_gene_id' => $medidaID])->first();
                    array_push($medidasGenerales, $dataMedida);
                }

                $response = [
                    'interfaz' => $getInterfaz,
                    'grupoCausas' => $getGrupoCausas,
                    'medidasGrupos' => $getMedidasGrupo,
                    'identificadoresAPCEspecifica' => $getIDGrupoEspecifica,
                    'medidasGenerales' => $medidasGenerales
                ];                
                return view('admin/templates/principal-panel', $userData).view('admin/manage-interfaces/agenda/view-agenda', $response);
            } else {
                return view('admin/templates/principal-panel', $userData).view('admin/templates/no-interfaces');
            }
        }
    }

    /** return a view to edit information of the interfaz */
    public function editInterfaz($id = null) {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser && !empty($id)) {
            $interfazModel = new InterfazModel($this->db);
            $userData = ['userInfo' => $loggedUser];
            
            $getInterface = $interfazModel->where('interfaz_id', $id)->first();
            $response = [
                'interfaz' => $getInterface
            ];
            
            return view('admin/templates/principal-panel', $userData).view('admin/manage-interfaces/mi-edit-interfaz', $response);
        }
    }

    /** updates information of the interfaz */
    public function updateInterfaz() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser && !empty($_POST)) {
            $interfazModel = new InterfazModel($this->db);

            $idInterfaz = $this->request->getPost('interfaz-id');
            if (!empty($idInterfaz)) $interfazToUpdate = $interfazModel->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz_id' => $idInterfaz])->first();
            
            $interfazToUpdate['nombre'] = $this->request->getPost('interfaz-nombre');
            $interfazToUpdate['indice_riesgo'] = $this->request->getPost('interfaz-riesgo');
            $interfazToUpdate['vulnerabilidad'] = $this->request->getPost('interfaz-vulnerabilidad');
            $interfazToUpdate['limpieza_techo'] = $this->request->getPost('limpieza-techo');
            $interfazToUpdate['residuos_agricolas'] = $this->request->getPost('residuos-agricolas');
            $interfazToUpdate['residuos_forestales'] = $this->request->getPost('residuos-forestales');
            $interfazToUpdate['residuos_domesticos'] = $this->request->getPost('residuos-domesticos');
            $interfazToUpdate['residuos_industriales'] = $this->request->getPost('residuos-industriales');
            
            $setInterfaz = $interfazModel->update($interfazToUpdate['interfaz_id'], $interfazToUpdate);
            if (!$setInterfaz) return $this->response->setJSON(0);
            else return $this->response->setJSON(1);
        }
    }
    
    /** return a view to confirm deletion of the interfaz */
    public function deleteInterfaz($id = null) {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser && !empty($id)) {
            $interfazModel = new InterfazModel($this->db);
            $userData = ['userInfo' => $loggedUser];

            $getInterface = $interfazModel->where('interfaz_id', $id)->first();
            $response = [
                'interfaz' => $getInterface
            ];

            return view('admin/templates/principal-panel', $userData).view('admin/manage-interfaces/mi-delete-interfaz', $response);
        }
    }

    /** delete the interfaz from database */
    public function confirmDeletionInterfaz() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser && !empty($_POST)) {
            $interfazModel = new InterfazModel($this->db);
            $apcEspeModel = new ApcEspecificaModel($this->db);
            $apcGeneModel = new ApcGeneralModel($this->db);
            $medidaEspeModel = new MedidaEspecificaModel($this->db);
            $medidaGeneModel = new MedidaGeneralModel($this->db);
            $idInterfaz = $this->request->getPost('id-interfaz');

            /** delete medidas especificas and APC especifica */
            $getAPCEspecifica = $apcEspeModel->select('apc_espe_id, medidas_espe')->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz' => $idInterfaz])->findAll();
            $idsAPCEspecifica = [];
            $medidasEspecificas = [];
            foreach ($getAPCEspecifica as $apc) {
                foreach (json_decode($apc['medidas_espe']) as $idMedida) {
                    array_push($medidasEspecificas, $idMedida);
                }
                array_push($idsAPCEspecifica, $apc['apc_espe_id']);
            }
            $medidasEspecificas = array_unique($medidasEspecificas, SORT_REGULAR);
            $deletedMedidasEspe = 0;
            foreach ($medidasEspecificas as $idMedida) {
                $medidaEspeToDelete = $medidaEspeModel->where('medida_espe_id', $idMedida)->delete();
                if ($medidaEspeToDelete) $deletedMedidasEspe = $deletedMedidasEspe + 1;
                else {
                    return $this->response->setJSON(0);
                    break;
                }
            }
            $deletedAPCEspe = 0;
            foreach ($idsAPCEspecifica as $idAPC) {
                $apcEspeToDelete = $apcEspeModel->where('apc_espe_id', $idAPC)->delete();
                if ($apcEspeToDelete) $deletedAPCEspe = $deletedAPCEspe + 1;
                else {
                    return $this->response->setJSON(0);
                    break;
                }
            }

            /** delete medidas generales and APC general */
            $getAPCGeneral = $apcGeneModel->select('apc_gene_id, medidas_gene')->where(['propietario_id' => $loggedUser['usuario_id'], 'interfaz' => $idInterfaz])->findAll()[0];
            $medidasGenerales = json_decode($getAPCGeneral['medidas_gene']);
            $deletedMedidasGene = 0;
            foreach ($medidasGenerales as $idMedida) {
                $medidaGeneToDelete = $medidaGeneModel->where('medida_gene_id', $idMedida)->delete();
                if ($medidaGeneToDelete) $deletedMedidasGene = $deletedMedidasGene + 1;
                else {
                    return $this->response->setJSON(0);
                    break;
                }
            }
            $apcGeneToDelete = $apcGeneModel->where('apc_gene_id', $getAPCGeneral['apc_gene_id'])->delete();
            
            if (($deletedMedidasEspe == sizeof($medidasEspecificas)) && ($deletedAPCEspe == sizeof($idsAPCEspecifica)) && ($deletedMedidasGene == sizeof($medidasGenerales)) && $apcGeneToDelete) {
                $interfazToDelete = $interfazModel->where('interfaz_id', $idInterfaz)->delete();
                if ($interfazToDelete) return $this->response->setJSON(1);
                else return $this->response->setJSON(0);
            }
        }
    }

    /** return a view with the comuna reports */
    public function reportsComunaUsuario() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser) {
            $userData = ['userInfo' => $loggedUser];
            $schema_table_incendios = 'public."incendios_quinquenio"';
            $schema_table_combustible = 'public."combustible"';
            $schema_table_vivienda_poblacion = 'public."vivienda_poblacion"';
            $apcEspeComunaModel = new ApcEspecificaComunalModel($this->db);
            $interfazModel = new InterfazModel($this->db);
            $apcEspeModel = new ApcEspecificaModel($this->db);
            $apcGeneModel = new ApcGeneralModel($this->db);
            $medidaEspeModel = new MedidaEspecificaModel($this->db);
            $medidaGeneModel = new MedidaGeneralModel($this->db);
            $vivPblModel = new ViviendaPoblacionModel($this->cp);
            
            if (!empty($loggedUser['comuna'])) {
                /** gets and prepares the Riesgo data of the comuna */
                /** gets the combustible data of the comuna */
                $getCombustible = $this->cp->query("SELECT uso_tierra, puntaje, COALESCE(SUM(superf_ha), 0) AS hectareas
                    FROM ".$schema_table_combustible." WHERE UNACCENT(LOWER(comuna)) = UNACCENT(LOWER('".$loggedUser['comuna']."')) GROUP BY (uso_tierra, puntaje);");
                $getTotalCombustible = $this->cp->query("SELECT COALESCE(SUM(superf_ha), 0) AS total FROM ".$schema_table_combustible." WHERE UNACCENT(LOWER(comuna)) = UNACCENT(LOWER('".$loggedUser['comuna']."'));");
                
                /** gets the poblacion data of the comuna */
                $getPoblacion = $this->cp->query("SELECT COALESCE(SUM(personas), 0) AS n_habitantes, COALESCE(SUM(dens_pbl), 0) AS dens_habitantes, COALESCE(SUM(total_viv), 0) AS n_viviendas, COALESCE(SUM(dens_viv), 0) AS dens_viviendas, COUNT(*) AS n_poligonos
                        FROM ".$schema_table_vivienda_poblacion." WHERE UNACCENT(LOWER(comuna)) = UNACCENT(LOWER('".$loggedUser['comuna']."'));");
                    
                /** gets the techo viviendas data of the comuna */
                $getViviendas = $this->cp->query("SELECT COALESCE(SUM(p03b_6), 0) AS techo_tipo_1a, COALESCE(SUM(p03b_5), 0) AS techo_tipo_1b, COALESCE(SUM(p03b_4), 0) AS techo_tipo_2, COALESCE(SUM(p03b_1), 0) AS techo_tipo_3, COALESCE(SUM(p03b_3), 0) AS techo_tipo_4
                        FROM ".$schema_table_vivienda_poblacion." WHERE UNACCENT(LOWER(comuna)) = UNACCENT(LOWER('".$loggedUser['comuna']."'));");
                $getViviendas = $getViviendas->getRowArray();
                $totalViviendas = $getViviendas['techo_tipo_1a'] + $getViviendas['techo_tipo_1b'] + $getViviendas['techo_tipo_2'] + $getViviendas['techo_tipo_3'] + $getViviendas['techo_tipo_4'];
                $puntuacionTipo1 = (($getViviendas['techo_tipo_1a'] + $getViviendas['techo_tipo_1b'])/$totalViviendas)*100;
                $puntuacionTipo2 = ($getViviendas['techo_tipo_2']/$totalViviendas)*75;
                $puntuacionTipo3 = ($getViviendas['techo_tipo_3']/$totalViviendas)*50;
                $puntuacionTipo4 = ($getViviendas['techo_tipo_4']/$totalViviendas)*25;
                $puntuacionTechosViviendas = round($puntuacionTipo1 + $puntuacionTipo2 + $puntuacionTipo3 + $puntuacionTipo4);

                /** gets and prepares the Causas data of the comuna */
                /** gets the causas data of the comuna */
                $totalCausasComuna = $apcEspeComunaModel->select('causa_especifica')->where('propietario_id', $loggedUser['usuario_id'])->groupBy('causa_especifica')->countAllResults();
                
                /** gets the sintesis causas data of the comuna */
                $getCausas = $apcEspeComunaModel->select('SUM(cantidad_causa_especifica) AS cantidad, grupo_causa')->where('propietario_id', $loggedUser['usuario_id'])->groupBy('grupo_causa')->orderBy('cantidad', 'DESC')->findAll();
                $getGrupoEspecificas = $apcEspeComunaModel->select('SUM(cantidad_causa_especifica) AS cantidad, grupo_causa, grupo_especifica')->where('propietario_id', $loggedUser['usuario_id'])->groupBy('grupo_causa, grupo_especifica')->orderBy('cantidad', 'DESC')->findAll();

                /** gets and prepares the Sintesis APC data of the comuna */
                /** gets the prevalencia data of the comuna */                
                $getInterfaces = $interfazModel->select('interfaz_id, nombre, prevalencia, limpieza_techo, residuos_agricolas, residuos_forestales, residuos_domesticos, residuos_industriales')->where('propietario_id', $loggedUser['usuario_id'])->orderBy('created_at', 'DESC')->findAll();
                if (!empty($getInterfaces)) {
                    $idsInterfaces = [];
                    $limpiezaTechosViviendas = [];
                    $residuosAgricolas = [];
                    $residuosForestales = [];
                    $residuosDomesticos = [];
                    $residuosIndustriales = [];
                    $incendiosComuna = [];
                    $incendiosInterfaces = [];

                    $getIncendiosComuna = $this->cp->query("SELECT id FROM ".$schema_table_incendios." WHERE UNACCENT(LOWER(comuna)) = UNACCENT(LOWER('".$loggedUser['comuna']."'));");
                    $getIncendiosComuna = $getIncendiosComuna->getResultArray();
                    
                    foreach ($getIncendiosComuna as $incendio) {
                        array_push($incendiosComuna, $incendio['id']);
                    }
                    foreach ($getInterfaces as $key => $interfaz) {
                        array_push($idsInterfaces, $interfaz['interfaz_id']);
                        array_push($limpiezaTechosViviendas, $interfaz['limpieza_techo']);
                        array_push($residuosAgricolas, $interfaz['residuos_agricolas']);
                        array_push($residuosForestales, $interfaz['residuos_forestales']);
                        array_push($residuosDomesticos, $interfaz['residuos_domesticos']);
                        array_push($residuosIndustriales, $interfaz['residuos_industriales']);
                        foreach (json_decode($interfaz['prevalencia'], true) as $incendio) {
                            array_push($incendiosInterfaces, $incendio['id']);
                        }
                    }

                    $allIncendiosWithinComuna = !array_diff($incendiosInterfaces, $incendiosComuna); /** checks if all incendios of interfaces are within comuna. If false then there is incendios that not within comuna. */
                    if ($allIncendiosWithinComuna) { /** if true */
                        /** gets amount of APCs in the comuna */
                        $setCountPlanes = sizeof($idsInterfaces);
    
                        /** gets amount of personas and viviendas that includes the interfaces in the comuna */
                        $getViviendaPoblacion = $interfazModel->select('vivienda_poblacion')->whereIn('interfaz_id', $idsInterfaces)->findAll();
                        $setCountPersonas = 0;
                        $setCountViviendas = 0;
                        foreach ($getViviendaPoblacion as $data) {
                            foreach (json_decode($data['vivienda_poblacion'], true) as $id) {
                                $setCountPersonas = $setCountPersonas + $vivPblModel->select('COALESCE(SUM(personas), 0) AS personas')->where('id', $id)->first()['personas'];
                                $setCountViviendas = $setCountViviendas + $vivPblModel->select('COALESCE(SUM(total_viv), 0) AS total_viv')->where('id', $id)->first()['total_viv'];
                            }
                        }
    
                        /** set an array of the medidas data by interfaz */
                        $setMedidasInterfaces = [];
                        foreach ($getInterfaces as $key => $interfaz) {
                            $setMedidasInterfaces[$interfaz['interfaz_id']] = [$interfaz['nombre'], [], [], 0];
                        }
    
                        /** gets amount of medidas especificas */
                        $getMedidasEspe = $apcEspeModel->select('interfaz, medidas_espe')->whereIn('interfaz', $idsInterfaces)->findAll();
                        $medidasEspecificas = [];
                        foreach ($getMedidasEspe as $apc) {
                            foreach (json_decode($apc['medidas_espe']) as $id) {
                                if (!in_array($id, $medidasEspecificas)) array_push($medidasEspecificas, $id);
                                if (array_key_exists($apc['interfaz'], $setMedidasInterfaces) && !in_array($id, $setMedidasInterfaces[$apc['interfaz']][1])) array_push($setMedidasInterfaces[$apc['interfaz']][1], $id);
                            }
                        }
                        $setCountMedidasEspe = sizeof($medidasEspecificas);
                        
                        /** gets amount of medidas generales */
                        $medidasGenerales = [];
                        $getMedidasGene = $apcGeneModel->select('interfaz, medidas_gene')->whereIn('interfaz', $idsInterfaces)->findAll();
                        foreach ($getMedidasGene as $apc) {
                            foreach (json_decode($apc['medidas_gene']) as $id) {
                                if (!in_array($id, $medidasGenerales)) array_push($medidasGenerales, $id);
                                if (array_key_exists($apc['interfaz'], $setMedidasInterfaces) && !in_array($id, $setMedidasInterfaces[$apc['interfaz']][2])) array_push($setMedidasInterfaces[$apc['interfaz']][2], $id);
                            }
                        }
                        $setCountMedidasGene = sizeof($medidasGenerales);
                        
                        /** gets the sum of avances of the medidas especificas (based on the interfaces) */
                        $sumAvanceMedidas = 0;
                        $getAvanceMedidasEspe = $medidaEspeModel->select('medida_espe_id, avance')->whereIn('medida_espe_id', $medidasEspecificas)->findAll();
                        foreach ($getAvanceMedidasEspe as $medida) {
                            $sumAvanceMedidas = $sumAvanceMedidas + $medida['avance'];
                            foreach ($setMedidasInterfaces as $id => $medidaInterfaz) {
                                if (in_array($medida['medida_espe_id'], $medidaInterfaz[1])) $setMedidasInterfaces[$id][3] = $setMedidasInterfaces[$id][3] + $medida['avance'];
                            }
                        }
                        
                        /** gets the sum of avances of the medidas generales (based on the interfaces) */
                        $getAvanceMedidasGene = $medidaGeneModel->select('medida_gene_id, avance')->whereIn('medida_gene_id', $medidasGenerales)->findAll();
                        foreach ($getAvanceMedidasGene as $medida) {
                            $sumAvanceMedidas = $sumAvanceMedidas + $medida['avance'];
                            foreach ($setMedidasInterfaces as $id => $medidaInterfaz) {
                                if (in_array($medida['medida_gene_id'], $medidaInterfaz[2])) $setMedidasInterfaces[$id][3] = $setMedidasInterfaces[$id][3] + $medida['avance'];
                            }
                        }
                        
                        /** set mean of avance of the medidas especificas and generales (based on the interfaces) */
                        $meanAvanceMedidas = $sumAvanceMedidas / ($setCountMedidasEspe + $setCountMedidasGene);
                        
                        $response = [
                            'dataUsuario' => $loggedUser,
                            'datosCombustible' => $getCombustible->getResultArray(),
                            'combustibleTotal' => $getTotalCombustible->getRowArray()['total'],
                            'datosPoblacion' => $getPoblacion->getRowArray(),
                            'datosViviendas' => $getViviendas,
                            'totalViviendas' => $totalViviendas,
                            'puntuacionTechos' => $puntuacionTechosViviendas,
                            'datosLimpiezaTechos' => $limpiezaTechosViviendas,
                            'datosResiduosAgricolas' => $residuosAgricolas,
                            'datosResiduosForestales' => $residuosForestales,
                            'datosResiduosDomesticos' => $residuosDomesticos,
                            'datosResiduosIndustriales' => $residuosIndustriales,
                            'totalCausasComuna' => (int)$totalCausasComuna,
                            'grupoCausas' => $getCausas,
                            'grupoEspecificas' => $getGrupoEspecificas,
                            'countPlanes' => $setCountPlanes,
                            'countPersonas' => $setCountPersonas,
                            'countViviendas' => $setCountViviendas,
                            'meanAvancePlanes' => $meanAvanceMedidas,
                            'countMedidasEspecificas' => $setCountMedidasEspe,
                            'countMedidasGenerales' => $setCountMedidasGene,
                            'medidasInterfaces' => $setMedidasInterfaces
                        ];
                        return view('admin/templates/principal-panel', $userData).view('admin/reports/comunales/view-comunas-interfaces', $response);
                    } else {
                        /** if there are fires outside the comuna */
                        $incendioOutsideComuna = array_values(array_diff($incendiosInterfaces, $incendiosComuna))[0]; /** get the id of the first incendio outside the comuna */
                        $interfazUsuario = null;
                        foreach ($getInterfaces as $key => $interfaz) {
                            foreach (json_decode($interfaz['prevalencia'], true) as $incendio) {
                                if ($incendio['id'] == $incendioOutsideComuna) {
                                    $interfazUsuario = $interfaz['nombre'];
                                    break;
                                }
                            }
                        }
                        $response = [
                            'dataUsuario' => $loggedUser,
                            'interfazOutsideComuna' => $interfazUsuario
                        ];
                        return view('admin/templates/principal-panel', $userData).view('admin/reports/comunales/view-comunas-interfaces', $response);
                    }
                } else {
                    $response = [
                        'dataUsuario' => $loggedUser,
                        'datosCombustible' => $getCombustible->getResultArray(),
                        'combustibleTotal' => $getTotalCombustible->getRowArray()['total'],
                        'datosPoblacion' => $getPoblacion->getRowArray(),
                        'datosViviendas' => $getViviendas,
                        'totalViviendas' => $totalViviendas,
                        'puntuacionTechos' => $puntuacionTechosViviendas,
                        'totalCausasComuna' => (int)$totalCausasComuna,
                        'grupoCausas' => $getCausas,
                        'grupoEspecificas' => $getGrupoEspecificas,
                    ];
                    return view('admin/templates/principal-panel', $userData).view('admin/reports/comunales/view-comuna-data', $response);    
                }
            } else {
                $response = ['dataUsuario' => $loggedUser];
                return view('admin/templates/principal-panel', $userData).view('admin/reports/comunales/comuna-not-defined');
            }
        }
    }

    /** return the riesgo data of the comuna */
    public function showRiesgoComuna() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser && !empty($loggedUser['comuna'])) {
            $schema_table_incendios = 'public."incendios_quinquenio"';
            $schema_table_combustible = 'public."combustible"';
            $schema_table_vivienda_poblacion = 'public."vivienda_poblacion"';
            $interfazModel = new InterfazModel($this->db);

            $query_incendios = $this->cp->query("SELECT
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2015 - Junio 2016' THEN 1 ELSE 0 END), 0) AS inc_2015,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2016 - Junio 2017' THEN 1 ELSE 0 END), 0) AS inc_2016,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2017 - Junio 2018' THEN 1 ELSE 0 END), 0) AS inc_2017,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2018 - Junio 2019' THEN 1 ELSE 0 END), 0) AS inc_2018,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2019 - Junio 2020' THEN 1 ELSE 0 END), 0) AS inc_2019,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2020 - Junio 2021' THEN 1 ELSE 0 END), 0) AS inc_2020
                    FROM ".$schema_table_incendios." WHERE UNACCENT(LOWER(comuna)) = UNACCENT(LOWER('".$loggedUser['comuna']."')) AND id_especifico <> 999;");
            
            $query_superficie = $this->cp->query("SELECT
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2015 - Junio 2016' THEN sup_total ELSE 0 END), 0) AS sup_2015,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2016 - Junio 2017' THEN sup_total ELSE 0 END), 0) AS sup_2016,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2017 - Junio 2018' THEN sup_total ELSE 0 END), 0) AS sup_2017,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2018 - Junio 2019' THEN sup_total ELSE 0 END), 0) AS sup_2018,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2019 - Junio 2020' THEN sup_total ELSE 0 END), 0) AS sup_2019,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2020 - Junio 2021' THEN sup_total ELSE 0 END), 0) AS sup_2020
                    FROM ".$schema_table_incendios." WHERE UNACCENT(LOWER(comuna)) = UNACCENT(LOWER('".$loggedUser['comuna']."')) AND id_especifico <> 999;");
            
            /*$schema_table_pendientes = 'public."pendientes_pais"';
            $query_pendiente = $this->cp->query("SELECT gridcode,
                        (ST_Area(ST_Intersection(geom, ".$latlng_to_buffer.")) / ST_Area(".$latlng_to_buffer."))*100 AS pc_superficie
                    FROM ".$schema_table_pendientes." WHERE LOWER(comuna) = LOWER('".$loggedUser['comuna']."');");*/

            $query_combustible = $this->cp->query("SELECT uso_tierra, puntaje, COALESCE(SUM(superf_ha), 0) AS hectareas
                    FROM ".$schema_table_combustible." WHERE UNACCENT(LOWER(comuna)) = UNACCENT(LOWER('".$loggedUser['comuna']."')) GROUP BY (uso_tierra, puntaje);");
            $query_total_combustible = $this->cp->query("SELECT COALESCE(SUM(superf_ha), 0) AS total FROM ".$schema_table_combustible." WHERE UNACCENT(LOWER(comuna)) = UNACCENT(LOWER('".$loggedUser['comuna']."'));");
            
            $query_poblacion = $this->cp->query("SELECT COALESCE(sum(dens_pbl), 0) AS sum_densidad, COUNT(*) AS cantidad
                    FROM ".$schema_table_vivienda_poblacion." WHERE UNACCENT(LOWER(comuna)) = UNACCENT(LOWER('".$loggedUser['comuna']."'));");
            
            $query_mat_techo = $this->cp->query("SELECT
                COALESCE(sum(p03b_6), 0) AS techo_tipo_1a, COALESCE(sum(p03b_5), 0) AS techo_tipo_1b,
                COALESCE(sum(p03b_4), 0) AS techo_tipo_2,
                COALESCE(sum(p03b_1), 0) AS techo_tipo_3,
                COALESCE(sum(p03b_3), 0) AS techo_tipo_4
            FROM ".$schema_table_vivienda_poblacion." WHERE UNACCENT(LOWER(comuna)) = UNACCENT(LOWER('".$loggedUser['comuna']."'));");

            $getInterfaces = $interfazModel->select('interfaz_id')->where('propietario_id', $loggedUser['usuario_id'])->findAll();
            if (!empty($getInterfaces)) {
                $idsInterfaces = [];
                foreach ($getInterfaces as $interfaz) {
                    array_push($idsInterfaces, $interfaz['interfaz_id']);
                }
                $dataInterfaces = $interfazModel->select('limpieza_techo, residuos_agricolas, residuos_forestales, residuos_domesticos, residuos_industriales')->whereIn('interfaz_id', $idsInterfaces)->findAll();
                  
                $response = [
                    'incendios' => $query_incendios->getRowArray(),
                    'superficie' => $query_superficie->getRowArray(),
                    //'pendiente' => $query_pendiente->getResultArray(),
                    'combustible' =>  $query_combustible->getResultArray(),
                    'combustible_total' => $query_total_combustible->getRowArray(),
                    'poblacion' => $query_poblacion->getRowArray(),
                    'vivienda_techos' => $query_mat_techo->getRowArray(),
                    'interfaces' => $dataInterfaces
                ];
                return $this->response->setJSON($response);
            } else {
                $response = [
                    'incendios' => $query_incendios->getRowArray(),
                    'superficie' => $query_superficie->getRowArray(),
                    //'pendiente' => $query_pendiente->getResultArray(),
                    'combustible' =>  $query_combustible->getResultArray(),
                    'combustible_total' => $query_total_combustible->getRowArray(),
                    'poblacion' => $query_poblacion->getRowArray(),
                    'vivienda_techos' => $query_mat_techo->getRowArray()
                ];
                return $this->response->setJSON($response);
            }
            
        }
    }

    /** return the prevalencia data of the comuna */
    public function showPrevalenciaComuna() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser && !empty($loggedUser['comuna'])) {
            $incendiosModel = new IncendiosModel($this->cp);
            $schema_table_incendios = 'public."incendios_quinquenio"';

            $resPrevalenciaQuinquenio = [];
            $resPrevalenciaUltima = [];
            $prevalenciaComuna = $this->cp->query("SELECT id FROM ".$schema_table_incendios." WHERE UNACCENT(LOWER(comuna)) = UNACCENT(LOWER('".$loggedUser['comuna']."'));");
            $prevalenciaComuna = $prevalenciaComuna->getResultArray();
            foreach ($prevalenciaComuna as $prevalencia) {
                $getDataIncendio = $incendiosModel->select('sup_total, mes_ocurre, temporada')->where('id', $prevalencia['id'])->first();
                if ($getDataIncendio['temporada'] == 'Julio 2020 - Junio 2021') array_push($resPrevalenciaUltima, $getDataIncendio);
                else array_push($resPrevalenciaQuinquenio, $getDataIncendio);
            }

            $response = ['quinquenio' => $resPrevalenciaQuinquenio, 'ultima_temporada' => $resPrevalenciaUltima];
            return $this->response->setJSON($response);
        }
    }

    /** return the causas especificas data of the comuna */
    public function showCausasOfGroupComuna() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser && !empty($_POST)) {
            $apcEspeComunalModel = new ApcEspecificaComunalModel($this->db);

            $grupoCausa = $this->request->getPost('grupo-causa');
            $grupoEspecifica = $this->request->getPost('grupo-especifica');
            
            $getCausasEspecificas = $apcEspeComunalModel->select('SUM(cantidad_causa_especifica) AS cantidad, grupo_causa, grupo_especifica, causa_especifica')->where(['propietario_id' => $loggedUser['usuario_id'], 'grupo_causa' => $grupoCausa, 'grupo_especifica' => $grupoEspecifica])->groupBy('grupo_causa, grupo_especifica, causa_especifica')->orderBy('cantidad', 'DESC')->findAll();
            return $this->response->setJSON($getCausasEspecificas);
        }
    }

    /** return a view with the interfaces reports */
    public function reportsInterfacesUsuario() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser) {
            $userData = ['userInfo' => $loggedUser];
            $interfazModel = new InterfazModel($this->db);
            $apcEspeModel = new ApcEspecificaModel($this->db);
            $apcGeneModel = new ApcGeneralModel($this->db);
            $medidaEspeModel = new MedidaEspecificaModel($this->db);
            $medidaGeneModel = new MedidaGeneralModel($this->db);
            $vivPblModel = new ViviendaPoblacionModel($this->cp);
            $schema_table_combustible = 'public."combustible"';
    
            $getInterfaces = $interfazModel->select('interfaz_id, nombre, vegetacion_combustible, vivienda_poblacion, limpieza_techo, residuos_agricolas, residuos_forestales, residuos_domesticos, residuos_industriales')->where('propietario_id', $loggedUser['usuario_id'])->orderBy('created_at', 'DESC')->findAll();
            if (!empty($getInterfaces)) {
                $idsInterfaces = [];
                $limpiezaTechosViviendas = [];
                $residuosAgricolas = [];
                $residuosForestales = [];
                $residuosDomesticos = [];
                $residuosIndustriales = [];
                $dataTipoCombustible = [];
                $idsViviendaPoblacion = [];
                foreach ($getInterfaces as $key => $interfaz) {
                    array_push($idsInterfaces, $interfaz['interfaz_id']);
                    array_push($limpiezaTechosViviendas, $interfaz['limpieza_techo']);
                    array_push($residuosAgricolas, $interfaz['residuos_agricolas']);
                    array_push($residuosForestales, $interfaz['residuos_forestales']);
                    array_push($residuosDomesticos, $interfaz['residuos_domesticos']);
                    array_push($residuosIndustriales, $interfaz['residuos_industriales']);
                    foreach (json_decode($interfaz['vegetacion_combustible'], true) as $combustible) {
                        if (array_key_exists($combustible['uso_tierra'], $dataTipoCombustible)) {
                            $dataTipoCombustible[$combustible['uso_tierra']][0] = $dataTipoCombustible[$combustible['uso_tierra']][0] + ($combustible['pc_superficie'] / sizeof($getInterfaces));
                        } else {
                            $dataTipoCombustible[$combustible['uso_tierra']][0] = $combustible['pc_superficie'] / sizeof($getInterfaces);
                            $dataTipoCombustible[$combustible['uso_tierra']][1] = $combustible['puntaje'];
                        }
                    }
                    foreach (json_decode($interfaz['vivienda_poblacion'], true) as $vivPbl) {
                        array_push($idsViviendaPoblacion, $vivPbl['id']);
                    }
                }
                
                if (!empty($idsViviendaPoblacion)) {
                    /** gets the poblacion data of the interfaces */
                    $getDataPoblacion = $vivPblModel->select('COALESCE(SUM(personas), 0) AS n_habitantes, COALESCE(SUM(dens_pbl), 0) AS dens_habitantes, COALESCE(SUM(total_viv), 0) AS n_viviendas, COALESCE(SUM(dens_viv), 0) AS dens_viviendas')->whereIn('id', $idsViviendaPoblacion)->findAll()[0];
                    $getDataPoblacion['dens_habitantes'] = $getDataPoblacion['dens_habitantes'] / sizeof($idsViviendaPoblacion);
                    $getDataPoblacion['dens_viviendas'] = $getDataPoblacion['dens_viviendas'] / sizeof($idsViviendaPoblacion);

                    /** gets the techo viviendas data of the interfaces */
                    $getDataViviendas = $vivPblModel->select('COALESCE(SUM(p03b_6), 0) AS techo_tipo_1a, COALESCE(SUM(p03b_5), 0) AS techo_tipo_1b, COALESCE(SUM(p03b_4), 0) AS techo_tipo_2, COALESCE(SUM(p03b_1), 0) AS techo_tipo_3, COALESCE(SUM(p03b_3), 0) AS techo_tipo_4')->whereIn('id', $idsViviendaPoblacion)->findAll()[0];
                    $totalViviendas = $getDataViviendas['techo_tipo_1a'] + $getDataViviendas['techo_tipo_1b'] + $getDataViviendas['techo_tipo_2'] + $getDataViviendas['techo_tipo_3'] + $getDataViviendas['techo_tipo_4'];
                    $puntuacionTipo1 = (($getDataViviendas['techo_tipo_1a'] + $getDataViviendas['techo_tipo_1b'])/$totalViviendas)*100;
                    $puntuacionTipo2 = ($getDataViviendas['techo_tipo_2']/$totalViviendas)*75;
                    $puntuacionTipo3 = ($getDataViviendas['techo_tipo_3']/$totalViviendas)*50;
                    $puntuacionTipo4 = ($getDataViviendas['techo_tipo_4']/$totalViviendas)*25;
                    $puntuacionTechosViviendas = round($puntuacionTipo1 + $puntuacionTipo2 + $puntuacionTipo3 + $puntuacionTipo4);
                } else {
                    $getDataPoblacion = ['n_habitantes' => 0, 'dens_habitantes' => 0, 'n_viviendas' => 0, 'dens_viviendas' => 0];
                    $getDataViviendas = 0;
                    $totalViviendas = 0;
                    $puntuacionTechosViviendas = 0;
                }

                /** gets and prepares the Causas data of the interfaces */
                /** gets the causas data of the interfaces */
                $totalCausasInterfaces = $apcEspeModel->select('causa_especifica')->whereIn('interfaz', $idsInterfaces)->groupBy('causa_especifica')->countAllResults();
                
                /** gets the sintesis causas data of the interfaces */
                $getCausas = $apcEspeModel->select('SUM(cantidad_causa_especifica) AS cantidad, grupo_causa')->whereIn('interfaz', $idsInterfaces)->groupBy('grupo_causa')->orderBy('cantidad', 'DESC')->findAll();
                $getGrupoEspecificas = $apcEspeModel->select('SUM(cantidad_causa_especifica) AS cantidad, grupo_causa, grupo_especifica')->whereIn('interfaz', $idsInterfaces)->groupBy('grupo_causa, grupo_especifica')->orderBy('cantidad', 'DESC')->findAll();

                /** gets and prepare the Sintesis APC data of the interfaces */
                /** gets amount of APCs */
                $setCountPlanes = sizeof($idsInterfaces);
                
                /** set an array of the medidas data by interfaz */
                $setMedidasInterfaces = [];
                foreach ($getInterfaces as $key => $interfaz) {
                    $setMedidasInterfaces[$interfaz['interfaz_id']] = [$interfaz['nombre'], [], [], 0];
                }

                /** gets amount of medidas especificas */
                $getMedidasEspe = $apcEspeModel->select('interfaz, medidas_espe')->whereIn('interfaz', $idsInterfaces)->findAll();
                $medidasEspecificas = [];
                foreach ($getMedidasEspe as $apc) {
                    foreach (json_decode($apc['medidas_espe']) as $id) {
                        if (!in_array($id, $medidasEspecificas)) array_push($medidasEspecificas, $id);
                        if (array_key_exists($apc['interfaz'], $setMedidasInterfaces) && !in_array($id, $setMedidasInterfaces[$apc['interfaz']][1])) array_push($setMedidasInterfaces[$apc['interfaz']][1], $id);
                    }
                }
                $setCountMedidasEspe = sizeof($medidasEspecificas);
                
                /** gets amount of medidas generales */
                $medidasGenerales = [];
                $getMedidasGene = $apcGeneModel->select('interfaz, medidas_gene')->whereIn('interfaz', $idsInterfaces)->findAll();
                foreach ($getMedidasGene as $apc) {
                    foreach (json_decode($apc['medidas_gene']) as $id) {
                        if (!in_array($id, $medidasGenerales)) array_push($medidasGenerales, $id);
                        if (array_key_exists($apc['interfaz'], $setMedidasInterfaces) && !in_array($id, $setMedidasInterfaces[$apc['interfaz']][2])) array_push($setMedidasInterfaces[$apc['interfaz']][2], $id);
                    }
                }
                $setCountMedidasGene = sizeof($medidasGenerales);
                
                /** gets the sum of avances of the medidas especificas (based on the interfaces) */
                $sumAvanceMedidas = 0;
                $getAvanceMedidasEspe = $medidaEspeModel->select('medida_espe_id, avance')->whereIn('medida_espe_id', $medidasEspecificas)->findAll();
                foreach ($getAvanceMedidasEspe as $medida) {
                    $sumAvanceMedidas = $sumAvanceMedidas + $medida['avance'];
                    foreach ($setMedidasInterfaces as $id => $medidaInterfaz) {
                        if (in_array($medida['medida_espe_id'], $medidaInterfaz[1])) $setMedidasInterfaces[$id][3] = $setMedidasInterfaces[$id][3] + $medida['avance'];
                    }
                }
                
                /** gets the sum of avances of the medidas generales (based on the interfaces) */
                $getAvanceMedidasGene = $medidaGeneModel->select('medida_gene_id, avance')->whereIn('medida_gene_id', $medidasGenerales)->findAll();
                foreach ($getAvanceMedidasGene as $medida) {
                    $sumAvanceMedidas = $sumAvanceMedidas + $medida['avance'];
                    foreach ($setMedidasInterfaces as $id => $medidaInterfaz) {
                        if (in_array($medida['medida_gene_id'], $medidaInterfaz[2])) $setMedidasInterfaces[$id][3] = $setMedidasInterfaces[$id][3] + $medida['avance'];
                    }
                }
                
                /** set mean of avance of the medidas especificas and generales (based on the interfaces) */
                $meanAvanceMedidas = $sumAvanceMedidas / ($setCountMedidasEspe + $setCountMedidasGene);
                
                $response = [
                    'dataUsuario' => $loggedUser,
                    'datosCombustible' => $dataTipoCombustible,
                    'datosPoblacion' => $getDataPoblacion,
                    'datosViviendas' => $getDataViviendas,
                    'totalViviendas' => $totalViviendas,
                    'puntuacionTechos' => $puntuacionTechosViviendas,
                    'datosLimpiezaTechos' => $limpiezaTechosViviendas,
                    'datosResiduosAgricolas' => $residuosAgricolas,
                    'datosResiduosForestales' => $residuosForestales,
                    'datosResiduosDomesticos' => $residuosDomesticos,
                    'datosResiduosIndustriales' => $residuosIndustriales,
                    'totalCausasInterfaces' => (int)$totalCausasInterfaces,
                    'grupoCausas' => $getCausas,
                    'grupoEspecificas' => $getGrupoEspecificas,
                    'countPlanes' => $setCountPlanes,
                    'meanAvancePlanes' => $meanAvanceMedidas,
                    'medidasInterfaces' => $setMedidasInterfaces,
                    'countMedidasEspecificas' => $setCountMedidasEspe,
                    'countMedidasGenerales' => $setCountMedidasGene
                ];
                return view('admin/templates/principal-panel', $userData).view('admin/reports/interfaces/view-interfaces', $response);
            } else {
                return view('admin/templates/principal-panel', $userData).view('admin/templates/no-interfaces');
            }
            
        }
    }

    /** return the riesgo data of the interfaces */
    public function showRiesgoInterfaces() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser) {
            $interfazModel = new InterfazModel($this->db);
            $schema_table_incendios = 'public."incendios_quinquenio"';
            $schema_table_combustible = 'public."combustible"';
            $schema_table_vivienda_poblacion = 'public."vivienda_poblacion"';

            $getInterfaces = $interfazModel->select('interfaz_id, prevalencia, pendiente, vegetacion_combustible, vivienda_poblacion, limpieza_techo, residuos_agricolas, residuos_forestales, residuos_domesticos, residuos_industriales')->where('propietario_id', $loggedUser['usuario_id'])->findAll();
            $idsInterfaces = [];
            $idsPrevalencia = [];
            $dataPendientes = [];
            $dataTipoCombustible = [];
            $idsViviendaPoblacion = [];
            $dataLimpiezaTecho = [];
            $dataResiduosAgricolas = [];
            $dataResiduosForestales = [];
            $dataResiduosDomesticos = [];
            $dataResiduosIndustriales = [];
            foreach ($getInterfaces as $key => $interfaz) {
                array_push($idsInterfaces, $interfaz['interfaz_id']);
                array_push($dataLimpiezaTecho, $interfaz['limpieza_techo']);
                array_push($dataResiduosAgricolas, $interfaz['residuos_agricolas']);
                array_push($dataResiduosForestales, $interfaz['residuos_forestales']);
                array_push($dataResiduosDomesticos, $interfaz['residuos_domesticos']);
                array_push($dataResiduosIndustriales, $interfaz['residuos_industriales']);
                foreach (json_decode($interfaz['prevalencia'], true) as $incendio) {
                    array_push($idsPrevalencia, $incendio['id']);
                }
                foreach (json_decode($interfaz['pendiente'], true) as $pendiente) {
                    array_push($dataPendientes, $pendiente);
                }
                foreach (json_decode($interfaz['vegetacion_combustible'], true) as $combustible) {
                    if (array_key_exists($combustible['uso_tierra'], $dataTipoCombustible)) {
                        $dataTipoCombustible[$combustible['uso_tierra']][0] = $dataTipoCombustible[$combustible['uso_tierra']][0] + ($combustible['pc_superficie'] / sizeof($getInterfaces));
                    } else {
                        $dataTipoCombustible[$combustible['uso_tierra']][0] = $combustible['pc_superficie'] / sizeof($getInterfaces);
                        $dataTipoCombustible[$combustible['uso_tierra']][1] = $combustible['puntaje'];
                    }
                }
                foreach (json_decode($interfaz['vivienda_poblacion'], true) as $vivPbl) {
                    array_push($idsViviendaPoblacion, $vivPbl['id']);
                }
            }
            
            $query_incendios = $this->cp->query("SELECT
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2015 - Junio 2016' THEN 1 ELSE 0 END), 0) AS inc_2015,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2016 - Junio 2017' THEN 1 ELSE 0 END), 0) AS inc_2016,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2017 - Junio 2018' THEN 1 ELSE 0 END), 0) AS inc_2017,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2018 - Junio 2019' THEN 1 ELSE 0 END), 0) AS inc_2018,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2019 - Junio 2020' THEN 1 ELSE 0 END), 0) AS inc_2019,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2020 - Junio 2021' THEN 1 ELSE 0 END), 0) AS inc_2020
                    FROM ".$schema_table_incendios." WHERE id = ANY(ARRAY[".implode(',', $idsPrevalencia)."]) AND id_especifico <> 999;");
            
            $query_superficie = $this->cp->query("SELECT
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2015 - Junio 2016' THEN sup_total ELSE 0 END), 0) AS sup_2015,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2016 - Junio 2017' THEN sup_total ELSE 0 END), 0) AS sup_2016,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2017 - Junio 2018' THEN sup_total ELSE 0 END), 0) AS sup_2017,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2018 - Junio 2019' THEN sup_total ELSE 0 END), 0) AS sup_2018,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2019 - Junio 2020' THEN sup_total ELSE 0 END), 0) AS sup_2019,
                        COALESCE(sum(CASE WHEN temporada = 'Julio 2020 - Junio 2021' THEN sup_total ELSE 0 END), 0) AS sup_2020
                    FROM ".$schema_table_incendios." WHERE id = ANY(ARRAY[".implode(',', $idsPrevalencia)."]) AND id_especifico <> 999;");
            
            if (!empty($idsViviendaPoblacion)) {
                $query_poblacion = $this->cp->query("SELECT COALESCE(sum(dens_pbl), 0) AS sum_densidad, COUNT(*) AS cantidad
                    FROM ".$schema_table_vivienda_poblacion." WHERE id = ANY(ARRAY[".implode(',', $idsViviendaPoblacion)."]);");
                $poblacionData = $query_poblacion->getRowArray();

                $query_mat_techo = $this->cp->query("SELECT
                    COALESCE(sum(p03b_6), 0) AS techo_tipo_1a, COALESCE(sum(p03b_5), 0) AS techo_tipo_1b,
                    COALESCE(sum(p03b_4), 0) AS techo_tipo_2,
                    COALESCE(sum(p03b_1), 0) AS techo_tipo_3,
                    COALESCE(sum(p03b_3), 0) AS techo_tipo_4
                FROM ".$schema_table_vivienda_poblacion." WHERE id = ANY(ARRAY[".implode(',', $idsViviendaPoblacion)."]);");
                $matTechosData = $query_mat_techo->getRowArray();
            } else {
                $poblacionData = ['sum_densidad' => 0, 'cantidad' => 0];
                $matTechosData = ['techo_tipo_1a' => 0, 'techo_tipo_1b' => 0, 'techo_tipo_2' => 0, 'techo_tipo_3' => 0, 'techo_tipo_4' => 0];
            }
            
            
            $response = [
                'incendios' => $query_incendios->getRowArray(),
                'superficie' => $query_superficie->getRowArray(),
                'pendiente' => $dataPendientes,
                'combustible' =>  $dataTipoCombustible,
                'poblacion' => $poblacionData,
                'vivienda_techos' => $matTechosData,
                'interfaces' => ['total' => sizeof($idsInterfaces), 'limpieza_techo' => $dataLimpiezaTecho, 'residuos_agricolas' => $dataResiduosAgricolas, 'residuos_forestales' => $dataResiduosForestales, 'residuos_domesticos' => $dataResiduosDomesticos, 'residuos_industriales' => $dataResiduosIndustriales]
            ];
            return $this->response->setJSON($response);
        }
    }

    /** return the prevalencia data of the interfaces */
    public function showPrevalenciaInterfaces() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser) {
            $interfazModel = new InterfazModel($this->db);
            $incendiosModel = new IncendiosModel($this->cp);
            $schema_table_incendios = 'public."incendios_quinquenio"';

            $getInterfaces = $interfazModel->select('prevalencia')->where('propietario_id', $loggedUser['usuario_id'])->findAll();
            $idsPrevalencia = [];
            foreach ($getInterfaces as $key => $interfaz) {
                foreach (json_decode($interfaz['prevalencia'], true) as $incendio) {
                    array_push($idsPrevalencia, $incendio['id']);
                }
            }

            $resPrevalenciaQuinquenio = [];
            $resPrevalenciaUltima = [];
            $prevalenciaInterfaces = $this->cp->query("SELECT id FROM ".$schema_table_incendios." WHERE id = ANY(ARRAY[".implode(',', $idsPrevalencia)."]);");
            $prevalenciaInterfaces = $prevalenciaInterfaces->getResultArray();
            foreach ($prevalenciaInterfaces as $prevalencia) {
                $getDataIncendio = $incendiosModel->select('sup_total, mes_ocurre, temporada')->where('id', $prevalencia['id'])->first();
                if ($getDataIncendio['temporada'] == 'Julio 2020 - Junio 2021') array_push($resPrevalenciaUltima, $getDataIncendio);
                else array_push($resPrevalenciaQuinquenio, $getDataIncendio);
            }

            $response = ['quinquenio' => $resPrevalenciaQuinquenio, 'ultima_temporada' => $resPrevalenciaUltima];
            return $this->response->setJSON($response);
        }
    }

    /** return the pendiente data of the interfaces */
    public function showPendienteInterfaces() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser) {
            $interfazModel = new InterfazModel($this->db);

            $getInterfaces = $interfazModel->select('pendiente')->where('propietario_id', $loggedUser['usuario_id'])->findAll();
            $countInterfaces = 0;
            $dataPendientes = [];
            foreach ($getInterfaces as $key => $interfaz) {
                $countInterfaces = $countInterfaces + 1;
                foreach (json_decode($interfaz['pendiente'], true) as $pendiente) {
                    array_push($dataPendientes, $pendiente);
                }
            }

            return $this->response->setJSON(['interfaces' => $countInterfaces, 'pendientes' => $dataPendientes]);
        }
    }

    /** return the causas especificas data of the interfaces */
    public function showCausasOfGroupInterfaces() {
        $loggedUser = $this->getActiveUser();
        if ($loggedUser && !empty($_POST)) {
            $apcEspeModel = new ApcEspecificaModel($this->db);

            $grupoCausa = $this->request->getPost('grupo-causa');
            $grupoEspecifica = $this->request->getPost('grupo-especifica');
            
            $getCausasEspecificas = $apcEspeModel->select('SUM(cantidad_causa_especifica) AS cantidad, grupo_causa, grupo_especifica, causa_especifica')->where(['propietario_id' => $loggedUser['usuario_id'], 'grupo_causa' => $grupoCausa, 'grupo_especifica' => $grupoEspecifica])->groupBy('grupo_causa, grupo_especifica, causa_especifica')->orderBy('cantidad', 'DESC')->findAll();
            return $this->response->setJSON($getCausasEspecificas);
        }
    }
}
