<section class="content">
    <div class="body_scroll">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <h2>Causas</h2>
                    <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('admin/analisis/causas'); ?>"><i class="zmdi zmdi-spinner"></i> Análisis</a></li>
                        <li class="breadcrumb-item active">Causas</li>
                    </ul>
                    <button class="btn btn-primary btn-icon mobile_menu" type="button"><i class="zmdi zmdi-sort-amount-desc"></i></button>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12">                
                    <button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i class="zmdi zmdi-arrow-right"></i></button>
                    <a href="<?= site_url('admin/analisis/sintesis'); ?>" class="btn btn-secondary btn-icon float-right waves-effect waves-float waves-gray" type="button" title="Síntesis de causas"><i class="zmdi zmdi-chevron-right"></i></a>
                    <a href="<?= site_url('admin/analisis/riesgo'); ?>" class="btn btn-secondary btn-icon float-right waves-effect waves-float waves-gray" type="button" title="Análisis de riesgo"><i class="zmdi zmdi-chevron-left"></i></a>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="body">
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-12">
                                    <div class="preview preview-pic tab-content">
                                        <div class="text-center">
                                            <div class="card">
                                                <div class="body"> 
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="h6 mb-0"></div>
                                                        <div class="action">
                                                            <a href="javascript:void(0);" id="causas-interfaz-info"><i class="zmdi zmdi-info"></i></a>
                                                        </div>
                                                    </div>                            
                                                    <input type="text" class="knob" value="<?= $totalCausasInterfaz; ?>" data-linecap="round" data-width="100" data-height="100" data-thickness="0.08" readonly
                                                    data-fgColor="
                                                    <?php
                                                    if ($totalCausasInterfaz <= 25) echo '#44bd00';
                                                    else if ($totalCausasInterfaz > 25 && $totalCausasInterfaz <= 50) echo '#ffbb00';
                                                    else if ($totalCausasInterfaz > 50 && $totalCausasInterfaz <= 75) echo '#ff7300';
                                                    else if ($totalCausasInterfaz > 75) echo '#ff1b19';
                                                    ?>
                                                    ">
                                                    <p>Causas específicas</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <label for="">Interfaz: <b class="text-success"><?= $interfaz['nombre']; ?></b></label>
                                        </div>
                                        <div>
                                            <label for="">Ultima actualización: <b class="text-success"><?= date('d/m/Y', strtotime($interfaz['updated_at'])); ?></b></label>
                                        </div>
                                    </div>               
                                </div>
                                <div class="col-xl-8 col-lg-8 col-md-12">
                                    <div id="map" class="gmap"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="body">
                            <ul class="nav nav-tabs nav-tabs-warning">
                                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#causas-quinquenio">Quinquenio</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#causas-ultima-temporada">Última temporada</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#causas-comparativo">Comparativo</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card">
                        <div class="body">                            
                            <div class="tab-content">
                                <div class="tab-pane active" id="causas-quinquenio">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="card">
                                                <div class="body">
                                                    <div id="chart-causas-quinquenio-1" class="c3_chart"></div>
                                                </div>
                                            </div>                
                                        </div>
                                        <div class="col-lg-12 col-md-12">
                                            <div class="card">
                                                <div class="body">
                                                    <div id="chart-causas-quinquenio-2" class="c3_chart"></div>
                                                </div>
                                            </div>                
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="causas-ultima-temporada">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="card">
                                                <div class="body">
                                                    <div id="chart-causas-ultima-1" class="c3_chart"></div>
                                                </div>
                                            </div>                
                                        </div>
                                        <div class="col-lg-12 col-md-12">
                                            <div class="card">
                                                <div class="body">
                                                    <div id="chart-causas-ultima-2" class="c3_chart"></div>
                                                </div>
                                            </div>                
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="causas-comparativo">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="card">
                                                <div class="body">
                                                    <div id="chart-causas-together-1" class="c3_chart"></div>
                                                </div>
                                            </div>                
                                        </div>
                                        <div class="col-lg-12 col-md-12">
                                            <div class="card">
                                                <div class="body">
                                                    <div id="chart-causas-together-2" class="c3_chart"></div>
                                                </div>
                                            </div>                
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="information-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body" id="information-modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-round waves-effect waves-red" data-dismiss="modal">CERRAR</button>
            </div>
        </div>
    </div>
</div>


<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
<link rel="stylesheet" href="<?= base_url('public/css/interface-create/leaflet-panel-layers.css'); ?>" />
<link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css" />


<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
<script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.min.js"></script>
<script src="<?= base_url('public/js/pages/interface-create/leaflet-panel-layers.js'); ?>"></script>
<script src="<?= base_url('public/js/pages/interface-create/L.TileLayer.BetterWMS.js'); ?>"></script>
<script src="<?= base_url('public/js/pages/interface-create/Leaflet.Control.Custom.js'); ?>"></script>
<script src="<?= base_url('public/js/pages/analysis/main_causas.js'); ?>"></script>
<script src="<?= base_url('public/js/pages/info_messages.js'); ?>"></script>