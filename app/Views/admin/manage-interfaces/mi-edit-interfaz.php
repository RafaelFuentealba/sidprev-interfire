<section class="content">
    <div class="body_scroll">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <h2>Edición interfaz</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/gestion/listar'); ?>"><i class="zmdi zmdi-widgets"></i> Gestionar interfaces</a></li>
                        <li class="breadcrumb-item active">Edición interfaz</li>
                    </ul>
                    <button class="btn btn-primary btn-icon mobile_menu" type="button"><i class="zmdi zmdi-sort-amount-desc"></i></button>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12">                
                    <button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i class="zmdi zmdi-arrow-right"></i></button>
                    <a href="<?= site_url('admin/gestion/listar'); ?>" class="btn btn-secondary btn-icon float-right waves-effect waves-float waves-gray" type="button" title="Gestionar interfaces"><i class="zmdi zmdi-chevron-left"></i></a>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="body">
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-12"></div>
                                <div class="col-xl-4 col-lg-4 col-md-12">
                                    <div class="alert alert-danger text-center" id="fail-update-interfaz" style="display: none;">Debes completar toda la información solicitada</div>
                                    <input type="hidden" id="id-update-interfaz" value="<?= $interfaz['interfaz_id']; ?>">
                                    <input type="hidden" id="riesgo-actual-interfaz" value="<?= $interfaz['indice_riesgo']; ?>">
                                    <input type="hidden" id="vulnerabilidad-actual-interfaz" value="<?= $interfaz['vulnerabilidad']; ?>">
                                    <input type="hidden" id="res-limpieza-techo-interfaz" value="<?= $interfaz['limpieza_techo']; ?>">
                                    <input type="hidden" id="res-agricolas-interfaz" value="<?= $interfaz['residuos_agricolas']; ?>">
                                    <input type="hidden" id="res-forestales-interfaz" value="<?= $interfaz['residuos_forestales']; ?>">
                                    <input type="hidden" id="res-domesticos-interfaz" value="<?= $interfaz['residuos_domesticos']; ?>">
                                    <input type="hidden" id="res-industriales-interfaz" value="<?= $interfaz['residuos_industriales']; ?>">
                                    <form id="form-update-interfaz">
                                        <div class="row clearfix">
                                            <div class="col-sm-12">
                                                <div class="form-group">    
                                                    <label for="">Nombre interfaz</label>                                
                                                    <input type="text" id="interfaz-name" name="interfazName" class="form-control" placeholder="Indicar nombre de la interfaz" value="<?= $interfaz['nombre']; ?>"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row clearfix">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="">Percepción limpieza del techo</label>                                    
                                                    <select id="sel-limpieza-techo" name="selLimpiezaTecho" class="form-control select2" style="width: 100%;">
                                                        <option value="" disabled>-- Seleccionar --</option>
                                                        <option value="25" <?php if ($interfaz['limpieza_techo'] == 25) echo 'selected="true"'; ?>>Permanente limpio</option>
                                                        <option value="50" <?php if ($interfaz['limpieza_techo'] == 50) echo 'selected="true"'; ?>>Casi siempre limpio</option>
                                                        <option value="75" <?php if ($interfaz['limpieza_techo'] == 75) echo 'selected="true"'; ?>>Rara vez limpio</option>
                                                        <option value="100" <?php if ($interfaz['limpieza_techo'] == 100) echo 'selected="true"'; ?>>Nunca limpio</option>
                                                        <option value="80" <?php if ($interfaz['limpieza_techo'] == 80) echo 'selected="true"'; ?>>No sabe</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row clearfix">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="">Existencia de residuos agrícolas</label>                                  
                                                    <select id="sel-residuos-agricolas" name="selResiduosAgricolas" class="form-control select2" style="width: 100%;">
                                                        <option value="" disabled>-- Seleccionar --</option>
                                                        <option value="100" <?php if ($interfaz['residuos_agricolas'] == 100) echo 'selected="true"'; ?>>Mucho</option>
                                                        <option value="50" <?php if ($interfaz['residuos_agricolas'] == 50) echo 'selected="true"'; ?>>Poco</option>
                                                        <option value="0" <?php if ($interfaz['residuos_agricolas'] == 0) echo 'selected="true"'; ?>>Nada</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row clearfix">
                                            <div class="col-sm-12">
                                                <div class="form-group">      
                                                    <label for="">Existencia de residuos forestales</label>                              
                                                    <select id="sel-residuos-forestales" name="selResiduosForestales" class="form-control select2" style="width: 100%;">
                                                        <option value="" disabled>-- Seleccionar --</option>
                                                        <option value="100" <?php if ($interfaz['residuos_forestales'] == 100) echo 'selected="true"'; ?>>Mucho</option>
                                                        <option value="50" <?php if ($interfaz['residuos_forestales'] == 50) echo 'selected="true"'; ?>>Poco</option>
                                                        <option value="0" <?php if ($interfaz['residuos_forestales'] == 0) echo 'selected="true"'; ?>>Nada</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row clearfix">
                                            <div class="col-sm-12">
                                                <div class="form-group">   
                                                    <label for="">Existencia de residuos domésticos</label>                                
                                                    <select id="sel-residuos-domesticos" name="selResiduosDomesticos" class="form-control select2" style="width: 100%;">
                                                        <option value="" disabled>-- Seleccionar --</option>
                                                        <option value="100" <?php if ($interfaz['residuos_domesticos'] == 100) echo 'selected="true"'; ?>>Mucho</option>
                                                        <option value="50" <?php if ($interfaz['residuos_domesticos'] == 50) echo 'selected="true"'; ?>>Poco</option>
                                                        <option value="0" <?php if ($interfaz['residuos_domesticos'] == 0) echo 'selected="true"'; ?>>Nada</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row clearfix">
                                            <div class="col-sm-12">
                                                <div class="form-group">  
                                                    <label for="">Existencia de residuos industriales</label>                                  
                                                    <select id="sel-residuos-industriales" name="selResiduosIndustriales" class="form-control select2" style="width: 100%;">
                                                        <option value="" disabled>-- Seleccionar --</option>
                                                        <option value="100" <?php if ($interfaz['residuos_industriales'] == 100) echo 'selected="true"'; ?>>Mucho</option>
                                                        <option value="50" <?php if ($interfaz['residuos_industriales'] == 50) echo 'selected="true"'; ?>>Poco</option>
                                                        <option value="0" <?php if ($interfaz['residuos_industriales'] == 0) echo 'selected="true"'; ?>>Nada</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <input type="submit" id="btn-update-interfaz" class="btn btn-primary btn-round waves-effect" value="ACTUALIZAR DATOS">
                                            <a href="<?= site_url('admin/gestion/listar'); ?>" class="btn btn-danger btn-round waves-effect waves-float waves-red">CANCELAR</a>
                                        </div>
                                    </form>     
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?= base_url('public/js/pages/manage-interfaces/edit_interfaz.js'); ?>"></script>