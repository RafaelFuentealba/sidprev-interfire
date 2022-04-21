<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('ingresar');
$routes->setTranslateURIDashes(false);
$routes->set404Override(function() {
    return view('errors/templates/header').view('errors/error-404.php');
});
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

/** routes group of authentication without filters */
$routes->post('auth/ingresar', 'Auth::checkLogin');
$routes->post('auth/registro', 'Auth::save');
$routes->get('logout', 'Auth::logout');
$routes->post('auth/clave/recuperar', 'Auth::sendMailTokenPassword');
$routes->post('auth/clave/actualizar', 'Auth::updatePassword');


/** routes group of admin profile */
$routes->group('admin', ['filter' => 'AuthCheck'], function($routes) {

    /** routes of access admin profile */
    $routes->get('perfil', 'Admin::viewMyProfile');
    $routes->get('perfil/editar', 'Admin::editMyProfile');
    $routes->post('perfil/basico/guardar', 'Admin::saveMyProfile');
    $routes->post('perfil/seguridad/guardar', 'Admin::changeMyPassword');
    $routes->get('perfil/listar/provincias', 'Admin::getProvinciasByRegion');
    $routes->get('perfil/listar/comunas', 'Admin::getComunasByProvincia');


    /** routes of Crear interfaz module */
    $routes->get('interfaz/crear', 'Admin::interfaceCreate');
    $routes->post('interfaz/crear/circle', 'Admin::getDataOfCircleInterfaces');
    $routes->post('interfaz/guardar', 'Admin::saveInterfaz');


    /** routes of Analisis module, Riesgo section */
    $routes->get('analisis/riesgo', 'Admin::savedInterfazRiesgo');
    $routes->get('analisis/interfaz/riesgo', 'Admin::savedInterfaz');
    $routes->get('analisis/interfaz/riesgo/prevalencia', 'Admin::savedPrevalenciaInterfaz');
    $routes->get('analisis/interfaz/riesgo/pendiente', 'Admin::savedPendienteInterfaz');
    /** routes of Analisis module, Causas section */
    $routes->get('analisis/causas', 'Admin::getInterfazCausas');
    $routes->get('analisis/interfaz/causas', 'Admin::getCausasTemporadas');
    /** routes of Analisis module, Sintesis section */
    $routes->get('analisis/sintesis', 'Admin::showCausasSintesis');
    $routes->post('analisis/sintesis/grupo', 'Admin::showAPCSintesisByGroup');


    /** routes of Generar APC module, Medidas especificas section */
    $routes->get('apc/especifica/listar', 'Admin::showGrupoCausasEspecificas');
    $routes->get('apc/especifica/ver/(:num)', 'Admin::showGrupoEspecifica/$1');
    $routes->get('apc/especifica/editar/(:num)', 'Admin::editGrupoEspecifica/$1');
    $routes->post('apc/especifica/causa/actualizar', 'Admin::updateGrupoEspecifica');
    $routes->post('apc/especifica/medida/agregar', 'Admin::addMedidaGrupoEspecifica');
    $routes->post('apc/especifica/medida/eliminar', 'Admin::deleteMedidaGrupoEspecifica');
    $routes->post('apc/especifica/medida/eliminar/multiples', 'Admin::deleteMultiplesMedidasEspecificas');
    $routes->post('apc/especifica/grupo/eliminar', 'Admin::deleteGrupoEspecifica');
    /** routes of Generar APC module, Medidas generales section */
    $routes->get('apc/general/listar', 'Admin::showMedidasGenerales');
    $routes->get('apc/general/ver/(:num)', 'Admin::showMedidaGeneral/$1');
    $routes->get('apc/general/editar/(:num)', 'Admin::editMedidaGeneral/$1');
    $routes->post('apc/general/actualizar', 'Admin::updateMedidaGeneral');
    $routes->post('apc/general/agregar', 'Admin::addMedidaGeneral');
    $routes->post('apc/general/eliminar', 'Admin::deleteMedidaGeneral');
    $routes->post('apc/general/eliminar/multiples', 'Admin::deleteMultiplesMedidasGenerales');


    /** routes of Gestionar interfaces module */
    $routes->get('gestion/listar', 'Admin::showAllGestionarInterfaces');
    /** routes of Gestionar interfaces module, Riesgo section */
    $routes->get('gestion/riesgo/interfaz/(:num)', 'Admin::savedInterfazRiesgo/$1');
    $routes->get('gestion/interfaz/(:num)/riesgo/forma', 'Admin::savedInterfaz/$1');
    $routes->get('gestion/interfaz/(:num)/riesgo/prevalencia', 'Admin::savedPrevalenciaInterfaz/$1');
    $routes->get('gestion/interfaz/(:num)/riesgo/pendiente', 'Admin::savedPendienteInterfaz/$1');
    /** routes of Gestionar interfaces module, Causas section*/
    $routes->get('gestion/causas/interfaz/(:num)', 'Admin::getInterfazCausas/$1');
    $routes->get('gestion/interfaz/(:num)/causas/temporadas', 'Admin::getCausasTemporadas/$1');
    /** routes of Gestionar interfaces module, Sintesis causas section */
    $routes->get('gestion/sintesis/interfaz/(:num)', 'Admin::showCausasSintesis/$1');
    $routes->post('gestion/sintesis/interfaz/(:num)/grupo', 'Admin::showAPCSintesisByGroup/$1');
    /** routes of Gestionar interfaces module, Medidas especificas section */
    $routes->get('gestion/apcespecifica/listar/interfaz/(:num)', 'Admin::showGrupoCausasEspecificas/$1');
    $routes->get('gestion/apcespecifica/ver/(:num)/interfaz/(:num)', 'Admin::showGrupoEspecifica/$1/$2');
    $routes->get('gestion/apcespecifica/editar/(:num)/interfaz/(:num)', 'Admin::editGrupoEspecifica/$1/$2');
    /** routes of Gestionar interfaces module, Medidas generales section */
    $routes->get('gestion/apcgeneral/listar/interfaz/(:num)', 'Admin::showMedidasGenerales/$1');
    $routes->get('gestion/apcgeneral/ver/(:num)/interfaz/(:num)', 'Admin::showMedidaGeneral/$1/$2');
    $routes->get('gestion/apcgeneral/editar/(:num)/interfaz/(:num)', 'Admin::editMedidaGeneral/$1/$2');
    /** routes of Gestionar interfaces module, Agenda section */
    $routes->get('gestion/agenda/interfaz/(:num)', 'Admin::showAgendaInterfaz/$1');
    /** routes of Gestionar interfaces module, Editar section */
    $routes->get('gestion/editar/interfaz/(:num)', 'Admin::editInterfaz/$1');
    $routes->post('gestion/actualizar/interfaz', 'Admin::updateInterfaz');
    /** routes of Gestionar interfaces module, Eliminar interfaz section */
    $routes->get('gestion/eliminar/interfaz/(:num)', 'Admin::deleteInterfaz/$1');
    $routes->post('gestion/eliminar/interfaz/confirmar', 'Admin::confirmDeletionInterfaz');


    /** routes of Reportes module, Comunales section */
    $routes->get('reportes/comunales', 'Admin::reportsComunaUsuario');
    $routes->get('reportes/comunales/riesgo', 'Admin::showRiesgoComuna');
    $routes->get('reportes/comunales/riesgo/prevalencia', 'Admin::showPrevalenciaComuna');
    $routes->post('reportes/comunales/causas/grupos', 'Admin::showCausasOfGroupComuna');
    /** routes of Reportes module, Interfaces section */
    $routes->get('reportes/interfaces', 'Admin::reportsInterfacesUsuario');
    $routes->get('reportes/interfaces/riesgo', 'Admin::showRiesgoInterfaces');
    $routes->get('reportes/interfaces/riesgo/prevalencia', 'Admin::showPrevalenciaInterfaces');
    $routes->get('reportes/interfaces/riesgo/pendiente', 'Admin::showPendienteInterfaces');
    $routes->post('reportes/interfaces/causas/grupos', 'Admin::showCausasOfGroupInterfaces');
});


/** routes group of logged in user */
$routes->group('', ['filter' => 'AlreadyLoggedIn'], function($routes) {
    $routes->get('/', 'Auth::login');
    $routes->get('auth/ingresar', 'Auth::login');
    $routes->get('auth/registro', 'Auth::register');
    $routes->get('auth/clave/recuperar', 'Auth::forgotPassword');
    $routes->get('auth/clave/actualizar', 'Auth::resetPassword');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
