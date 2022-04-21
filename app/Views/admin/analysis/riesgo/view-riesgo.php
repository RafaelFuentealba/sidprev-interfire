<section class="content">
    <div class="body_scroll">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <h2>Riesgo</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/analisis/riesgo'); ?>"><i class="zmdi zmdi-spinner"></i> Análisis</a></li>
                        <li class="breadcrumb-item active">Riesgo</li>
                    </ul>
                    <button class="btn btn-primary btn-icon mobile_menu" type="button"><i class="zmdi zmdi-sort-amount-desc"></i></button>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12">                
                    <button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i class="zmdi zmdi-arrow-right"></i></button>
                    <a href="<?= site_url('admin/analisis/causas'); ?>" class="btn btn-secondary btn-icon float-right waves-effect waves-float waves-gray" type="button" title="Análisis de causas"><i class="zmdi zmdi-chevron-right"></i></a>
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
                                                            <a href="javascript:void(0);" id="riesgo-info"><i class="zmdi zmdi-info"></i></a>
                                                        </div>
                                                    </div>                            
                                                    <input type="text" class="knob" value="<?= $interfaz['indice_riesgo']; ?>" data-linecap="round" data-width="100" data-height="100" data-thickness="0.08"
                                                    data-fgColor="
                                                        <?php
                                                        if (($interfaz['indice_riesgo'] >= 0) && ($interfaz['indice_riesgo'] <= 25)) echo '#44bd00';
                                                        else if (($interfaz['indice_riesgo']> 25) && ($interfaz['indice_riesgo'] <= 50)) echo '#ffbb00';
                                                        else if (($interfaz['indice_riesgo'] > 50) && ($interfaz['indice_riesgo'] <= 75)) echo '#ff7300';
                                                        else if (($interfaz['indice_riesgo'] > 75) && ($interfaz['indice_riesgo'] <= 100)) echo '#ff1b19'
                                                        ?>
                                                    " readonly>
                                                    <p>Riesgo</p>
                                                    <div class="d-flex bd-highlight text-center mt-4">
                                                        <div class="flex-fill bd-highlight">
                                                            <small class="text-muted">Amenaza</small>
                                                            <h5 class="mb-0"><?= $interfaz['amenaza']; ?></h5>
                                                        </div>
                                                        <div class="flex-fill bd-highlight">
                                                            <small class="text-muted">Vulnerabilidad</small>
                                                            <h5 class="mb-0"><?= $interfaz['vulnerabilidad']; ?></h5>
                                                        </div>
                                                    </div>
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
                                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#prevalencia">Prevalencia</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#pendiente">Pendiente</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#vegetacion">Vegetación combustible</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#poblacion">Población</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#vivienda">Vivienda</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#combustibles">Otros combustibles</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card">
                        <div class="body">                            
                            <div class="tab-content">
                                <div class="tab-pane active" id="prevalencia">
                                    <div class="row clearfix">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-3 text-center"></div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-6 text-center">
                                            <div class="card">
                                                <div class="body">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="h6 mb-0"></div>
                                                        <div class="action">
                                                            <a href="javascript:void(0);" id="prevalencia-info"><i class="zmdi zmdi-info"></i></a>
                                                        </div>
                                                    </div>
                                                    <input id="val-prevalencia" type="text" class="knob" value="" data-linecap="round" data-width="100" data-height="100" data-thickness="0.08" readonly data-fgColor="">
                                                    <p>Prevalencia</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-6 text-center">
                                            <div class="card">
                                                <div class="body">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="h6 mb-0"></div>
                                                        <div class="action">
                                                            <a href="javascript:void(0);" id="ocurrencia-info"><i class="zmdi zmdi-info"></i></a>
                                                        </div>
                                                    </div>
                                                    <input id="val-ocurrencia" type="text" class="knob" value="" data-linecap="round" data-width="100" data-height="100" data-thickness="0.08" data-fgColor="" readonly>
                                                    <p>% Ocurrencia</p>
                                                    <div class="d-flex bd-highlight text-center mt-4">
                                                        <div class="flex-fill bd-highlight">
                                                            <small class="text-muted">Promedio quinquenio</small>
                                                            <h5 class="mb-0" id="val-ocurrencia-quinquenio"></h5>
                                                        </div>
                                                        <div class="flex-fill bd-highlight">
                                                            <small class="text-muted">Última Temporada</small>
                                                            <h5 class="mb-0" id="val-ocurrencia-ultima"></h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-6 text-center">
                                            <div class="card">
                                                <div class="body">                            
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="h6 mb-0"></div>
                                                        <div class="action">
                                                            <a href="javascript:void(0);" id="dano-info"><i class="zmdi zmdi-info"></i></a>
                                                        </div>
                                                    </div> 
                                                    <input id="val-dano" type="text" class="knob" value="" data-linecap="round" data-width="100" data-height="100" data-thickness="0.08" data-fgColor="" readonly>
                                                    <p>% Daño</p>
                                                    <div class="d-flex bd-highlight text-center mt-4">
                                                        <div class="flex-fill bd-highlight">
                                                            <small class="text-muted">Promedio quinquenio</small>
                                                            <h5 class="mb-0" id="val-dano-quinquenio"></h5>
                                                        </div>
                                                        <div class="flex-fill bd-highlight">
                                                            <small class="text-muted">Última Temporada</small>
                                                            <h5 class="mb-0" id="val-dano-ultima"></h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6 col-md-12">
                                            <div class="card">
                                                <div class="header">
                                                    <h2>Gráfica <strong>Ocurrencia</strong></h2>
                                                </div>
                                                <div class="body">
                                                    <div id="chart-ocurrencia" class="c3_chart"></div>
                                                </div>
                                            </div>                
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="card">
                                                <div class="header">
                                                    <h2>Gráfica <strong>Daño</strong></h2>
                                                </div>
                                                <div class="body">
                                                    <div id="chart-dano" class="c3_chart"></div>
                                                </div>
                                            </div>                
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="pendiente">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="card">
                                            <div class="header">
                                                <h2>Gráfica<strong> Pendiente</strong></h2>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-centers">
                                                <div class="h6 mb-0"></div>
                                                <div class="action">
                                                    <a href="javascript:void(0);" id="pendiente-info"><i class="zmdi zmdi-info"></i></a>
                                                </div>
                                            </div>
                                            <div class="body">
                                                <div id="chart-pendiente" class="c3_chart"></div>
                                            </div>
                                        </div>                
                                    </div>
                                </div>
                                <div class="tab-pane" id="vegetacion">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="card">
                                            <div class="body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="h6 mb-0"></div>
                                                    <div class="action">
                                                        <a href="javascript:void(0);" id="combustible-info"><i class="zmdi zmdi-info"></i></a>
                                                    </div>
                                                </div>
                                                <div class="table-responsive mt-2">
                                                    <table class="table table-striped table-hover js-basic-example dataTable">
                                                        <thead>
                                                            <tr>
                                                                <th class="bg-green">Tipo de vegetación</th>
                                                                <th class="bg-green">% superficie</th>
                                                                <th class="bg-green">Puntaje</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($datosCombustible as $uso => $valores) {
                                                            ?>
                                                            <tr>
                                                                <td style="width: 60%;"><?= $uso; ?></td>
                                                                <td style="width: 20%;"><?= round($valores[0], 2); ?></td>
                                                                <td style="width: 20%;"><?= $valores[1]; ?></td>
                                                            </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="poblacion">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="card">
                                                <div class="row clearfix">
                                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="h6 mb-0"></div>
                                                            <div class="action">
                                                                <a href="javascript:void(0);" id="poblacion-info"><i class="zmdi zmdi-info"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="card w_data_1">
                                                        <div class="body">
                                                                <div class="w_icon blue"><i class="zmdi zmdi-male-alt"></i></div>
                                                                <h4 class="mt-3"><?= $datosPoblacion['n_habitantes']; ?></h4>
                                                                <span class="text-muted">Total de habitantes</span>
                                                        </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="h6 mb-0"></div>
                                                            <div class="action">
                                                                <a href="javascript:void(0);" id="densidad-poblacional-info"><i class="zmdi zmdi-info"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="card w_data_1">
                                                        <div class="body">
                                                                <div class="w_icon indigo"><i class="zmdi zmdi-accounts-add"></i></div>
                                                                <h4 class="mt-3"><?= round($datosPoblacion['dens_habitantes'], 2); ?></h4>
                                                                <span class="text-muted">Densidad de habitantes</span>
                                                        </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="h6 mb-0"></div>
                                                            <div class="action">
                                                                <a href="javascript:void(0);" id="viviendas-info"><i class="zmdi zmdi-info"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="card w_data_1">
                                                        <div class="body">
                                                                <div class="w_icon orange"><i class="zmdi zmdi-home"></i></div>
                                                                <h4 class="mt-3"><?= $datosPoblacion['n_viviendas']; ?></h4>
                                                                <span class="text-muted">Total de viviendas</span>
                                                        </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="h6 mb-0"></div>
                                                            <div class="action">
                                                                <a href="javascript:void(0);" id="densidad-viviendas-info"><i class="zmdi zmdi-info"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="card w_data_1">
                                                        <div class="body">
                                                                <div class="w_icon green"><i class="zmdi zmdi-city-alt"></i></div>
                                                                <h4 class="mt-3"><?= round($datosPoblacion['dens_viviendas'], 2); ?></h4>
                                                                <span class="text-muted">Densidad de viviendas</span>
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="vivienda">
                                    <div class="row">
                                        <div class="col-lg-8 col-md-6 col-sm-6 col-6 text-center">
                                            <div class="card">
                                                <div class="body">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="h6 mb-0"></div>
                                                        <div class="action">
                                                            <a href="javascript:void(0);" id="techo-viviendas-info"><i class="zmdi zmdi-info"></i></a>
                                                        </div>
                                                    </div>                       
                                                    <input type="text" class="knob" value="<?= $puntuacionTechos; ?>" data-linecap="round" data-width="100" data-height="100" data-thickness="0.08"
                                                    data-fgColor="
                                                        <?php
                                                        if ($puntuacionTechos <= 25) echo '#44bd00';
                                                        else if ($puntuacionTechos > 25 && $puntuacionTechos <= 50) echo '#ffbb00';
                                                        else if ($puntuacionTechos > 50 && $puntuacionTechos <= 75) echo '#ff7300';
                                                        else if ($puntuacionTechos > 75) echo '#ff1b19';
                                                        ?>
                                                    " readonly>
                                                    <p>Puntuación Materialidad Techo</p>
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="card">
                                                            <div class="body">
                                                                <div class="table-responsive text-left">
                                                                    <table class="table table-hover table-striped product_item_list c_table theme-color mb-0">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="bg-green">Materialidad</th>
                                                                                <th class="bg-green">N° viviendas</th>
                                                                                <th class="bg-green">% viviendas</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            if ($datosViviendas != 0) {
                                                                            ?>
                                                                            <tr>
                                                                                <td>
                                                                                    <?php
                                                                                    if (strlen('Lata, cartón, plásticos Paja, coirón, totora o caña') > 30) echo wordwrap('Lata, cartón, plásticos Paja, coirón, totora o caña', 30, '<br>');
                                                                                    else echo 'Lata, cartón, plásticos Paja, coirón, totora o caña';
                                                                                    ?>
                                                                                </td>
                                                                                <td><?= $datosViviendas['techo_tipo_1a'] + $datosViviendas['techo_tipo_1b']; ?></td>
                                                                                <td><?= round((($datosViviendas['techo_tipo_1a'] + $datosViviendas['techo_tipo_1b'])/$totalViviendas)*100, 2); ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <?php
                                                                                    if (strlen('Fonolita o plancha de fieltro embreado') > 30) echo wordwrap('Fonolita o plancha de fieltro embreado', 30, '<br>');
                                                                                    else echo 'Fonolita o plancha de fieltro embreado';
                                                                                    ?>
                                                                                </td>
                                                                                <td><?= $datosViviendas['techo_tipo_2']; ?></td>
                                                                                <td><?= round(($datosViviendas['techo_tipo_2']/$totalViviendas)*100, 2); ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <?php
                                                                                    if (strlen('Tejas o tejuelas de arcilla, metálicas, de cemento, de madera, asfálticas o plásticas') > 30) echo wordwrap('Tejas o tejuelas de arcilla, metálicas, de cemento, de madera, asfálticas o plásticas', 30, '<br>');
                                                                                    else echo 'Tejas o tejuelas de arcilla, metálicas, de cemento, de madera, asfálticas o plásticas';
                                                                                    ?>
                                                                                </td>
                                                                                <td><?= $datosViviendas['techo_tipo_3']; ?></td>
                                                                                <td><?= round(($datosViviendas['techo_tipo_3']/$totalViviendas)*100, 2); ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <?php
                                                                                    if (strlen('Planchas metálicas de zinc, cobre, etc. o fibrocemento (tipo pizarreño)') > 30) echo wordwrap('Planchas metálicas de zinc, cobre, etc. o fibrocemento (tipo pizarreño)', 30, '<br>');
                                                                                    else echo 'Planchas metálicas de zinc, cobre, etc. o fibrocemento (tipo pizarreño)';
                                                                                    ?>
                                                                                </td>
                                                                                <td><?= $datosViviendas['techo_tipo_4']; ?></td>
                                                                                <td><?= round(($datosViviendas['techo_tipo_4']/$totalViviendas)*100, 2); ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Total</td>
                                                                                <td><?= $totalViviendas; ?></td>
                                                                                <td><?= round((($datosViviendas['techo_tipo_1a'] + $datosViviendas['techo_tipo_1b'] + $datosViviendas['techo_tipo_2'] + $datosViviendas['techo_tipo_3'] + $datosViviendas['techo_tipo_4']) / $totalViviendas) * 100); ?></td>
                                                                            </tr>
                                                                            <?php
                                                                            } else {
                                                                            ?>
                                                                            <tr>
                                                                                <td>Sin resultados</td>
                                                                                <td>Sin resultados</td>
                                                                                <td>Sin resultados</td>
                                                                            </tr>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-6 col-6 text-center">
                                            <div class="card">
                                                <div class="body">                            
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="h6 mb-0"></div>
                                                        <div class="action">
                                                            <a href="javascript:void(0);" id="suciedad-techos-info"><i class="zmdi zmdi-info"></i></a>
                                                        </div>
                                                    </div> 
                                                    <input type="text" class="knob" value="<?= $interfaz['limpieza_techo']; ?>" data-linecap="round" data-width="100" data-height="100" data-thickness="0.08"
                                                    data-fgColor="
                                                        <?php
                                                        if ($interfaz['limpieza_techo'] <= 25) echo '#44bd00';
                                                        else if ($interfaz['limpieza_techo'] > 25 && $interfaz['limpieza_techo'] <= 50) echo '#ffbb00';
                                                        else if ($interfaz['limpieza_techo'] > 50 && $interfaz['limpieza_techo'] <= 75) echo '#ff7300';
                                                        else if ($interfaz['limpieza_techo'] > 75 && $interfaz['limpieza_techo'] <= 100) echo '#ff1b19';
                                                        ?>
                                                    " readonly>
                                                    <p>Suciedad de Techos</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="combustibles">
                                    <div class="row clearfix">
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-6 text-center">
                                            <div class="card">
                                                <div class="body">       
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="h6 mb-0"></div>
                                                        <div class="action">
                                                            <a href="javascript:void(0);" id="residuos-agricolas-info"><i class="zmdi zmdi-info"></i></a>
                                                        </div>
                                                    </div>             
                                                    <input type="text" class="knob" value="<?= $interfaz['residuos_agricolas']; ?>" data-linecap="round" data-width="100" data-height="100" data-thickness="0.08"
                                                    data-fgColor="
                                                        <?php
                                                        if ($interfaz['residuos_agricolas'] == 0) echo '#44bd00';
                                                        else if ($interfaz['residuos_agricolas'] == 50) echo '#ffbb00';
                                                        else if ($interfaz['residuos_agricolas'] == 100) echo '#ff1b19';
                                                        ?>
                                                    " readonly>
                                                    <p>Existencia de Residuos Agrícolas</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-6 text-center">
                                            <div class="card">
                                                <div class="body"> 
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="h6 mb-0"></div>
                                                        <div class="action">
                                                            <a href="javascript:void(0);" id="residuos-forestales-info"><i class="zmdi zmdi-info"></i></a>
                                                        </div>
                                                    </div>                            
                                                    <input type="text" class="knob" value="<?= $interfaz['residuos_forestales']; ?>" data-linecap="round" data-width="100" data-height="100" data-thickness="0.08"
                                                    data-fgColor="
                                                        <?php
                                                        if ($interfaz['residuos_forestales'] == 0) echo '#44bd00';
                                                        else if ($interfaz['residuos_forestales'] == 50) echo '#ffbb00';
                                                        else if ($interfaz['residuos_forestales'] == 100) echo '#ff1b19';
                                                        ?>
                                                    " readonly>
                                                    <p>Existencia de Residuos Forestales</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-6 text-center">
                                            <div class="card">
                                                <div class="body">   
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="h6 mb-0"></div>
                                                        <div class="action">
                                                            <a href="javascript:void(0);" id="residuos-domesticos-info"><i class="zmdi zmdi-info"></i></a>
                                                        </div>
                                                    </div>                          
                                                    <input type="text" class="knob" value="<?= $interfaz['residuos_domesticos'] ?>" data-linecap="round" data-width="100" data-height="100" data-thickness="0.08"
                                                    data-fgColor="
                                                        <?php
                                                        if ($interfaz['residuos_domesticos'] == 0) echo '#44bd00';
                                                        else if ($interfaz['residuos_domesticos'] == 50) echo '#ffbb00';
                                                        else if ($interfaz['residuos_domesticos'] == 100) echo '#ff1b19';
                                                        ?>
                                                    " readonly>
                                                    <p>Existencia de Residuos Domésticos</p> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-6 text-center">
                                            <div class="card">
                                                <div class="body">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="h6 mb-0"></div>
                                                        <div class="action">
                                                            <a href="javascript:void(0);" id="residuos-industriales-info"><i class="zmdi zmdi-info"></i></a>
                                                        </div>
                                                    </div>                             
                                                    <input type="text" class="knob" value="<?= $interfaz['residuos_industriales']; ?>" data-linecap="round" data-width="100" data-height="100" data-thickness="0.08"
                                                    data-fgColor="
                                                        <?php
                                                        if ($interfaz['residuos_industriales'] == 0) echo '#44bd00';
                                                        else if ($interfaz['residuos_industriales'] == 50) echo '#ffbb00';
                                                        else if ($interfaz['residuos_industriales'] == 100) echo '#ff1b19';
                                                        ?>
                                                    " readonly>
                                                    <p>Existencia de Residuos Industriales</p>
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
<script src="<?= base_url('public/js/pages/analysis/main_riesgo.js'); ?>"></script>
<script src="<?= base_url('public/js/pages/info_messages.js'); ?>"></script>