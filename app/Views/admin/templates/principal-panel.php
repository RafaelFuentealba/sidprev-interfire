<!doctype html>
<html class="no-js " lang="es">


<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<title>SIDPREV - Interfire</title>
<link rel="icon" href="<?= base_url('public/images/interfire-favicon.png'); ?>" type="image/png"> <!-- Favicon-->

<link rel="stylesheet" href="<?= base_url('public/plugins/dropify/css/dropify.min.css'); ?>">

<link rel="stylesheet" href="<?= base_url('public/plugins/bootstrap/css/bootstrap.min.css'); ?>">
<link rel="stylesheet" href="<?= base_url('public/plugins/jvectormap/jquery-jvectormap-2.0.3.min.css'); ?>"/>
<link rel="stylesheet" href="<?= base_url('public/plugins/charts-c3/plugin.css'); ?>"/>
<link rel="stylesheet" href="<?= base_url('public/plugins/morrisjs/morris.min.css'); ?>" />
<link rel="stylesheet" href="<?= base_url('public/plugins/sweetalert/sweetalert.css'); ?>"/>

<link rel="stylesheet" href="<?= base_url('public/plugins/jquery-steps/jquery.steps.css'); ?>"> <!-- Jquery Steps -->
<link rel="stylesheet" href="<?= base_url('public/plugins/bootstrap-select/css/bootstrap-select.css'); ?>" /> <!-- Bootstrap Select Css -->
<link rel="stylesheet" href="<?= base_url('public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>" /> <!-- Bootstrap Material Datetime Picker Css -->

<link rel="stylesheet" href="<?= base_url('public/plugins/footable-bootstrap/css/footable.bootstrap.min.css'); ?>">
<link rel="stylesheet" href="<?= base_url('public/plugins/footable-bootstrap/css/footable.standalone.min.css'); ?>">
<link rel="stylesheet" href="<?= base_url('public/plugins/jquery-datatable/dataTables.bootstrap4.min.css'); ?>"> <!-- JQuery DataTable Css -->

<link rel="stylesheet" href="<?= base_url('public/plugins/charts-c3/plugin.css'); ?>"/>
<link rel="stylesheet" href="<?= base_url('public/plugins/jvectormap/jquery-jvectormap-2.0.3.css'); ?>"/>
<link rel="stylesheet" href="<?= base_url('public/plugins/morrisjs/morris.css'); ?>" />

<link rel="stylesheet" href="<?= base_url('public/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css'); ?>" /> <!-- Colorpicker Css -->
<link rel="stylesheet" href="<?= base_url('public/plugins/jquery-spinner/css/bootstrap-spinner.css'); ?>"> <!-- Bootstrap Spinner Css -->

<link rel="stylesheet" href="<?= base_url('public/css/style.min.css'); ?>"> <!-- Custom Css -->
</head>

<body class="theme-green">

<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img class="zmdi-hc-spin" src="<?= base_url('public/images/loader.svg'); ?>" width="48" height="48" alt="Sin Incendios"></div>
        <p>Cargando...</p>
    </div>
</div>

<!-- Overlay For Sidebars -->
<div class="overlay"></div>

<!-- Main Search -->
<div id="search">
    <button id="close" type="button" class="close btn btn-primary btn-icon btn-icon-mini btn-round">x</button>
    <form>
      <input type="search" value="" placeholder="Search..." />
      <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>

<!-- Right Icon menu Sidebar -->
<div class="navbar-right">
    <ul class="navbar-nav">        
        <li><a href="<?= site_url('logout'); ?>" class="mega-menu" title="Cerrar sesión"><i class="zmdi zmdi-power"></i></a></li>
    </ul>
</div>


<!-- Left Sidebar -->
<aside id="leftsidebar" class="sidebar">
    <div class="navbar-brand">
        <button class="btn-menu ls-toggle-btn" type="button"><i class="zmdi zmdi-menu"></i></button>
        <a href="javascript:void(0);"><img src="<?= base_url('public/images/logo-interfire.png'); ?>" width="75" alt="SIDPREV"><span class="m-l-10">SIDPREV</span></a>
    </div>
    <div class="menu">
        <ul class="list">
            <li>
                <div class="user-info">
                    <a class="image" href="<?= site_url('admin/perfil'); ?>"><img src="<?= base_url('public/uploads/users/'.$userInfo['img_perfil']); ?>" alt="User"></a>
                    <div class="detail">
                        <h4><?= $userInfo['nombre']; ?></h4>
                        <small><?= $userInfo['tipo_acceso']; ?></small>                        
                    </div>
                </div>
            </li>
            <!--<li class="<?php if(uri_string()=='admin/inicio') echo 'active open'; ?>"><a href="<?= site_url('admin/inicio'); ?>"><i class="zmdi zmdi-home"></i><span>Inicio</span></a></li>-->
            <li class="<?php if(uri_string()=='admin/interfaz/crear') echo 'active open'; ?>"><a href="<?= site_url('admin/interfaz/crear'); ?>"><i class="zmdi zmdi-map"></i><span>Crear interfaz</span></a></li>
            <li class="<?php
                            if ((service('uri')->getSegment(2) == 'analisis') && (service('uri')->getSegment(3) == 'riesgo') && (service('uri')->getSegment(4) == '')) echo 'active open';
                            else if ((service('uri')->getSegment(2) == 'analisis') && (service('uri')->getSegment(3) == 'causas') && (service('uri')->getSegment(4) == '')) echo 'active open';
                            else if ((service('uri')->getSegment(2) == 'analisis') && (service('uri')->getSegment(3) == 'sintesis') && (service('uri')->getSegment(4) == '')) echo 'active open';
                        ?>">
                <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-spinner"></i><span>Análisis</span></a>
                <ul class="ml-menu">
                    <li><a href="<?= site_url('admin/analisis/riesgo'); ?>">Riesgo</a></li>
                    <li><a href="<?= site_url('admin/analisis/causas'); ?>">Causas</a></li>
                    <li><a href="<?= site_url('admin/analisis/sintesis'); ?>">Síntesis de causas</a></li>                
                </ul>
            </li>
            <li class="<?php
                            if ((uri_string() == 'admin/apc/especifica/listar') || uri_string() == 'admin/apc/general/listar') {
                                echo 'active open';
                            } else if ((service('uri')->getSegment(2) == 'apc') && (service('uri')->getSegment(3) == 'especifica') && (service('uri')->getSegment(4) == 'editar')) {
                                $segmentID = service('uri')->getSegment(5);
                                $apcModuleURL = array('admin/apc/especifica/editar/'.$segmentID);
                                if(in_array(uri_string(), $apcModuleURL, true)) echo 'active open';
                            } else if ((service('uri')->getSegment(2) == 'apc') && (service('uri')->getSegment(3) == 'especifica') && (service('uri')->getSegment(4) == 'ver')) {
                                $segmentID = service('uri')->getSegment(5);
                                $apcModuleURL = array('admin/apc/especifica/ver/'.$segmentID);
                                if(in_array(uri_string(), $apcModuleURL, true)) echo 'active open';
                            } else if ((service('uri')->getSegment(2) == 'apc') && (service('uri')->getSegment(3) == 'general') && (service('uri')->getSegment(4) == 'ver')) {
                                $segmentID = service('uri')->getSegment(5);
                                $apcModuleURL = array('admin/apc/general/ver/'.$segmentID);
                                if(in_array(uri_string(), $apcModuleURL, true)) echo 'active open';
                            }  else if ((service('uri')->getSegment(2) == 'apc') && (service('uri')->getSegment(3) == 'general') && (service('uri')->getSegment(4) == 'editar')) {
                                $segmentID = service('uri')->getSegment(5);
                                $apcModuleURL = array('admin/apc/general/editar/'.$segmentID);
                                if(in_array(uri_string(), $apcModuleURL, true)) echo 'active open';
                            }
                        ?>">
                <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-assignment-o"></i><span>Generar APC</span></a>
                <ul class="ml-menu">
                    <li><a href="<?= site_url('admin/apc/especifica/listar'); ?>">Medidas específicas</a></li>
                    <li><a href="<?= site_url('admin/apc/general/listar'); ?>">Medidas generales</a></li>                 
                </ul>
            </li>
            <li class="<?php
                            if ((service('uri')->getSegment(2) == 'gestion') && (service('uri')->getSegment(3) == 'riesgo') && (service('uri')->getSegment(4) == 'interfaz')) {
                                $segmentID = service('uri')->getSegment(5);
                                $riskURL = array('admin/gestion/riesgo/interfaz/'.$segmentID);
                                if(in_array(uri_string(), $riskURL, true)) echo 'active open';
                            } else if ((service('uri')->getSegment(2) == 'gestion') && (service('uri')->getSegment(3) == 'causas') && (service('uri')->getSegment(4) == 'interfaz')) {
                                $segmentID = service('uri')->getSegment(5);
                                $causesURL = array('admin/gestion/causas/interfaz/'.$segmentID);
                                if(in_array(uri_string(), $causesURL, true)) echo 'active open';
                            } else if ((service('uri')->getSegment(2) == 'gestion') && (service('uri')->getSegment(3) == 'sintesis') && (service('uri')->getSegment(4) == 'interfaz')) {
                                $segmentID = service('uri')->getSegment(5);
                                $causesURL = array('admin/gestion/sintesis/interfaz/'.$segmentID);
                                if(in_array(uri_string(), $causesURL, true)) echo 'active open';
                            } else if ((service('uri')->getSegment(2) == 'gestion') && (service('uri')->getSegment(3) == 'apcespecifica') && (service('uri')->getSegment(4) == 'listar') && (service('uri')->getSegment(5) == 'interfaz')) {
                                $interfazID = service('uri')->getSegment(6);
                                $listAPCEspeURL = array('admin/gestion/apcespecifica/listar/interfaz/'.$interfazID);
                                if(in_array(uri_string(), $listAPCEspeURL, true)) echo 'active open';
                            } else if ((service('uri')->getSegment(2) == 'gestion') && (service('uri')->getSegment(3) == 'apcespecifica') && (service('uri')->getSegment(4) == 'ver') && (service('uri')->getSegment(6) == 'interfaz')) {
                                $apcEspeID = service('uri')->getSegment(5);
                                $interfazID = service('uri')->getSegment(7);
                                $viewAPCEspeURL = array('admin/gestion/apcespecifica/ver/'.$apcEspeID.'/interfaz/'.$interfazID);
                                if(in_array(uri_string(), $viewAPCEspeURL, true)) echo 'active open';
                            } else if ((service('uri')->getSegment(2) == 'gestion') && (service('uri')->getSegment(3) == 'apcespecifica') && (service('uri')->getSegment(4) == 'editar') && (service('uri')->getSegment(6) == 'interfaz')) {
                                $apcEspeID = service('uri')->getSegment(5);
                                $interfazID = service('uri')->getSegment(7);
                                $editAPCEspeURL = array('admin/gestion/apcespecifica/editar/'.$apcEspeID.'/interfaz/'.$interfazID);
                                if(in_array(uri_string(), $editAPCEspeURL, true)) echo 'active open';
                            } else if ((service('uri')->getSegment(2) == 'gestion') && (service('uri')->getSegment(3) == 'apcgeneral') && (service('uri')->getSegment(4) == 'listar') && (service('uri')->getSegment(5) == 'interfaz')) {
                                $interfazID = service('uri')->getSegment(6);
                                $listAPCGeneURL = array('admin/gestion/apcgeneral/listar/interfaz/'.$interfazID);
                                if(in_array(uri_string(), $listAPCGeneURL, true)) echo 'active open';
                            } else if ((service('uri')->getSegment(2) == 'gestion') && (service('uri')->getSegment(3) == 'apcgeneral') && (service('uri')->getSegment(4) == 'ver') && (service('uri')->getSegment(6) == 'interfaz')) {
                                $apcGeneID = service('uri')->getSegment(5);
                                $interfazID = service('uri')->getSegment(7);
                                $viewAPCGeneURL = array('admin/gestion/apcgeneral/ver/'.$apcGeneID.'/interfaz/'.$interfazID);
                                if(in_array(uri_string(), $viewAPCGeneURL, true)) echo 'active open';
                            } else if ((service('uri')->getSegment(2) == 'gestion') && (service('uri')->getSegment(3) == 'apcgeneral') && (service('uri')->getSegment(4) == 'editar') && (service('uri')->getSegment(6) == 'interfaz')) {
                                $apcGeneID = service('uri')->getSegment(5);
                                $interfazID = service('uri')->getSegment(7);
                                $editAPCGeneURL = array('admin/gestion/apcgeneral/editar/'.$apcGeneID.'/interfaz/'.$interfazID);
                                if(in_array(uri_string(), $editAPCGeneURL, true)) echo 'active open';
                            } else if ((service('uri')->getSegment(2) == 'gestion') && (service('uri')->getSegment(3) == 'agenda') && (service('uri')->getSegment(4) == 'interfaz')) {
                                $interfazID = service('uri')->getSegment(5);
                                $agendaURL = array('admin/gestion/agenda/interfaz/'.$interfazID);
                                if(in_array(uri_string(), $agendaURL, true)) echo 'active open';
                            } else if ((service('uri')->getSegment(2) == 'gestion') && (service('uri')->getSegment(3) == 'editar') && (service('uri')->getSegment(4) == 'interfaz')) {
                                $interfazID = service('uri')->getSegment(5);
                                $editionURL = array('admin/gestion/editar/interfaz/'.$interfazID);
                                if(in_array(uri_string(), $editionURL, true)) echo 'active open';
                            } else if ((service('uri')->getSegment(2) == 'gestion') && (service('uri')->getSegment(3) == 'eliminar') && (service('uri')->getSegment(4) == 'interfaz')) {
                                $interfazID = service('uri')->getSegment(5);
                                $deletionURL = array('admin/gestion/eliminar/interfaz/'.$interfazID);
                                if(in_array(uri_string(), $deletionURL, true)) echo 'active open';
                            }

                            if(uri_string()=='admin/gestion/listar') echo 'active open';
                        ?>">
                <a href="<?= site_url('admin/gestion/listar'); ?>"><i class="zmdi zmdi-widgets"></i><span>Gestionar interfaces</span></a></li>
            <li class="<?php
                            if ((service('uri')->getSegment(2) == 'reportes') && (service('uri')->getSegment(3) == 'comunales')) echo 'active open';
                            else if ((service('uri')->getSegment(2) == 'reportes') && (service('uri')->getSegment(3) == 'interfaces')) echo 'active open';
                        ?>">
                <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-print"></i><span>Reportes</span></a>
                <ul class="ml-menu">
                    <li><a href="<?= site_url('admin/reportes/comunales'); ?>">Comunales</a></li>
                    <li><a href="<?= site_url('admin/reportes/interfaces'); ?>">Interfaces</a></li>                
                </ul>
            </li>
        </ul>
    </div>
</aside>


<!-- Jquery Core Js --> 
<script src="<?= base_url('public/bundles/libscripts.bundle.js'); ?>"></script> <!-- Lib Scripts Plugin Js ( jquery.v3.2.1, Bootstrap4 js) --> 
<script src="<?= base_url('public/bundles/vendorscripts.bundle.js'); ?>"></script> <!-- slimscroll, waves Scripts Plugin Js -->

<script src="<?= base_url('public/plugins/dropify/js/dropify.min.js'); ?>"></script>
<script src="<?= base_url('public/js/pages/forms/dropify.js'); ?>"></script>

<script src="<?= base_url('public/bundles/jvectormap.bundle.js'); ?>"></script> <!-- JVectorMap Plugin Js -->
<script src="<?= base_url('public/bundles/sparkline.bundle.js'); ?>"></script> <!-- Sparkline Plugin Js -->
<script src="<?= base_url('public/bundles/c3.bundle.js'); ?>"></script>

<script src="<?= base_url('public/plugins/sweetalert/sweetalert.min.js'); ?>"></script> <!-- SweetAlert Plugin Js --> 
<script src="<?= base_url('public/js/pages/ui/sweetalert.js'); ?>"></script>
<script src="<?= base_url('public/plugins/jquery-validation/jquery.validate.js'); ?>"></script> <!-- Jquery Validation Plugin Css -->
<script src="<?= base_url('public/plugins/jquery-steps/jquery.steps.js'); ?>"></script> <!-- JQuery Steps Plugin Js -->
<script src="<?= base_url('public/js/pages/forms/form-wizard.js'); ?>"></script>
<script src="<?= base_url('public/plugins/momentjs/moment.js'); ?>"></script> <!-- Moment Plugin Js --> 
<script src="<?= base_url('public/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js'); ?>"></script> <!-- Bootstrap Material Datetime Picker Plugin Js -->

<script src="<?= base_url('public/bundles/datatablescripts.bundle.js'); ?>"></script> <!-- Jquery DataTable Plugin Js --> 
<script src="<?= base_url('public/js/pages/tables/jquery-datatable.js'); ?>"></script>

<script src="<?= base_url('public/bundles/c3.bundle.js'); ?>"></script>
<script src="<?= base_url('public/bundles/morrisscripts.bundle.js'); ?>"></script> <!-- Morris Plugin Js -->
<script src="<?= base_url('public/bundles/jvectormap.bundle.js'); ?>"></script> <!-- JVectorMap Plugin Js -->
<script src="<?= base_url('public/bundles/sparkline.bundle.js'); ?>"></script> <!-- Sparkline Plugin Js -->
<script src="<?= base_url('public/bundles/knob.bundle.js'); ?>"></script> <!-- Jquery Knob Plugin Js -->
<script src="<?= base_url('public/js/pages/ecommerce.js'); ?>"></script>
<script src="<?= base_url('public/js/pages/charts/jquery-knob.min.js'); ?>"></script>

<script src="<?= base_url('public/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js'); ?>"></script> <!-- Bootstrap Colorpicker Js --> 
<script src="<?= base_url('public/plugins/jquery-inputmask/jquery.inputmask.bundle.js'); ?>"></script> <!-- Input Mask Plugin Js -->
<script src="<?= base_url('public/plugins/multi-select/js/jquery.multi-select.js'); ?>"></script> <!-- Multi Select Plugin Js --> 
<script src="<?= base_url('public/plugins/jquery-spinner/js/jquery.spinner.js'); ?>"></script> <!-- Jquery Spinner Plugin Js --> 
<script src="<?= base_url('public/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js'); ?>"></script> <!-- Bootstrap Tags Input Plugin Js --> 
<script src="<?= base_url('public/plugins/nouislider/nouislider.js'); ?>"></script> <!-- noUISlider Plugin Js -->
<!--<script src="<?= base_url('public/plugins/select2/select2.min.js'); ?>"></script>--> <!-- Select2 Js -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="<?= base_url('public/js/pages/forms/advanced-form-elements.js'); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.0/html2pdf.bundle.js"></script> <!-- html2pdf plugin -->

<script src="<?= base_url('public/bundles/mainscripts.bundle.js'); ?>"></script>
<script src="<?= base_url('public/js/pages/index.js'); ?>"></script>


</body>


</html>