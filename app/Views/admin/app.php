<section class="content">
    <div class="body_scroll">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <h2>Aplicación</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/interfaz/crear'); ?>"><i class="zmdi zmdi-map"></i> Crear interfaz</a></li>
                        <li class="breadcrumb-item active">Aplicación</li>
                    </ul>
                    <button class="btn btn-primary btn-icon mobile_menu" type="button"><i class="zmdi zmdi-sort-amount-desc"></i></button>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12">                
                    <button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i class="zmdi zmdi-arrow-right"></i></button>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <!-- Principal Map -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="header">
                            <h2>Vista <strong>Principal</strong></h2>
                        </div>
                        <div class="body">
                            <div id="map" class="gmap"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Principal Map -->

            <form action="" id="completeInfoForm">
                <div class="modal fade" id="completeInfoModal1" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="title" id="largeModalLabel">Indice de riesgo</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="card">
                                            <div class="body">
                                                <div id="fail-completeInfoForm" class="alert alert-danger text-center" style="display: none;">
                                                    Debes completar toda la información solicitada <a href="javascript:void(0);" id="close-fail-completeInfoForm" class="float-right"><i class="zmdi zmdi-close" title="Cerrar"></i></a>
                                                </div>
                                                <div id="wizard_horizontal">
                                                    <h2>Vivienda</h2>
                                                    <section>
                                                        <div class="row clearfix">
                                                            <div class="col-sm-12">
                                                                <label for="">Percepción limpieza del techo</label>
                                                                <select id="selLimpiezaTecho" name="selLimpiezaTecho" class="form-control select2" style="width: 100%;">
                                                                    <option value="" selected="true" disabled>-- Seleccionar --</option>
                                                                    <option value="25">Permanente limpio</option>
                                                                    <option value="50">Casi siempre limpio</option>
                                                                    <option value="75">Rara vez limpio</option>
                                                                    <option value="100">Nunca limpio</option>
                                                                    <option value="80">No sabe</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </section>
                                                    <h2>Combustibles</h2>
                                                    <section>
                                                        <div class="row clearfix">
                                                            <div class="col-sm-12">
                                                                <label for="">Existencia de residuos agrícolas</label>
                                                                <select id="selResiduosAgricolas" name="selResiduosAgricolas" class="form-control select2" style="width: 100%;">
                                                                    <option value="" selected="true" disabled>-- Seleccionar --</option>
                                                                    <option value="100">Mucho</option>
                                                                    <option value="50">Poco</option>
                                                                    <option value="0">Nada</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row clearfix mt-4">
                                                            <div class="col-sm-12">
                                                                <label for="">Existencia de residuos forestales</label>
                                                                <select id="selResiduosForestales" name="selResiduosForestales" class="form-control select2" style="width: 100%;">
                                                                    <option value="" selected="true" disabled>-- Seleccionar --</option>
                                                                    <option value="100">Mucho</option>
                                                                    <option value="50">Poco</option>
                                                                    <option value="0">Nada</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row clearfix mt-4">
                                                            <div class="col-sm-12">
                                                                <label for="">Existencia de residuos domésticos</label>
                                                                <select id="selResiduosDomesticos" name="selResiduosDomesticos" class="form-control select2" style="width: 100%;">
                                                                    <option value="" selected="true" disabled>-- Seleccionar --</option>
                                                                    <option value="100">Mucho</option>
                                                                    <option value="50">Poco</option>
                                                                    <option value="0">Nada</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row clearfix mt-4">
                                                            <div class="col-sm-12">
                                                                <label for="">Existencia de residuos industriales</label>
                                                                <select id="selResiduosIndustriales" name="selResiduosIndustriales" class="form-control select2" style="width: 100%;">
                                                                    <option value="" selected="true" disabled>-- Seleccionar --</option>
                                                                    <option value="100">Mucho</option>
                                                                    <option value="50">Poco</option>
                                                                    <option value="0">Nada</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <br>
                                                    </section>
                                                    <h2>General</h2>
                                                    <section>
                                                        <div class="row clearfix">
                                                            <div class="col-sm-12">
                                                                <div class="form-group">    
                                                                    <label for="">Nombre</label>                                
                                                                    <input type="text" id="nombreInterfaz" name="nombreInterfaz" class="form-control" placeholder="Definir nombre de la interfaz" value="" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="show-actual-risk" class="row clearfix" style="display: block;">
                                                            <div class="col-sm-12">
                                                                <div class="form-group">    
                                                                    <label for="">Indice de riesgo actual</label>                                
                                                                    <input type="text" id="actualRisk" name="actualRisk" class="form-control" placeholder="" value="" disabled/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="show-recalculated-risk" class="row clearfix" style="display: none;">
                                                            <div class="col-sm-12">
                                                                <div class="form-group">    
                                                                    <label for="">Indice de riesgo actualizado</label>                                
                                                                    <input type="text" id="recalculatedRisk" name="recalculatedRisk" class="form-control" placeholder="" value="" disabled/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <br>
                                                    </section>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="button" id="btn-recalculate-risk" class="btn btn-success btn-round waves-effect" value="RECALCULAR RIESGO">
                                <input type="submit" id="btn-interface-save" class="btn btn-primary btn-round waves-effect" value="GUARDAR INTERFAZ">
                                <button type="button" id="btn-modal-close" class="btn btn-danger btn-round waves-effect" data-dismiss="modal">CERRAR</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</section>


<link rel="stylesheet" href="<?= base_url('public/css/interface-create/font-awesome.min.css'); ?>" />
<link rel="stylesheet" href="<?= base_url('public/css/interface-create/app.css'); ?>" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
<link rel="stylesheet" href="<?= base_url('public/css/interface-create/leaflet-panel-layers.css'); ?>" />
<link rel="stylesheet" href="<?= base_url('public/css/interface-create/icons.css'); ?>" />
<link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder/dist/esri-leaflet-geocoder.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
<link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css" />  


<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
<script src="<?= base_url('public/js/pages/interface-create/leaflet-panel-layers.js'); ?>"></script>
<script src="<?= base_url('public/js/pages/interface-create/L.TileLayer.BetterWMS.js'); ?>"></script>
<script src="https://unpkg.com/esri-leaflet"></script>
<script src="https://unpkg.com/esri-leaflet-geocoder"></script>
<script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.js" charset="utf-8"></script> <!-- not used -->
<script src="<?= base_url('public/js/pages/interface-create/Leaflet.Control.Custom.js'); ?>"></script>
<script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.min.js"></script>
<script src="<?= base_url('public/js/pages/interface-create/L.Geoserver.js'); ?>"></script> <!-- not used -->
<script src="<?= base_url('public/js/pages/interface-create/app.js'); ?>"></script>