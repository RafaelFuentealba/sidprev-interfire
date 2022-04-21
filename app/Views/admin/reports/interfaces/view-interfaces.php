<section class="content">
    <div class="body_scroll">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <h2>Reportes interfaces</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/reportes/interfaces'); ?>"><i class="zmdi zmdi-print"></i> Reportes</a></li>
                        <li class="breadcrumb-item active">Reportes interfaces</li>
                    </ul>
                    <button class="btn btn-primary btn-icon mobile_menu" type="button"><i class="zmdi zmdi-sort-amount-desc"></i></button>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12">                                    
                    <button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i class="zmdi zmdi-arrow-right"></i></button>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <?php
            if (!empty($numInterfaces)) {
            ?>
            <div class="alert alert-danger text-center">Para obtener estos reportes debes crear tu interfaz</div>
            <div class="text-center">
                <a href="<?= site_url('admin/interfaz/crear'); ?>" class="btn btn-success btn-icon waves-effect waves-float waves-green" type="button" title="Crear interfaz"><i class="zmdi zmdi-map"></i></a>
            </div>
            <?php
            } else {
            ?>
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="body">
                            <ul class="nav nav-tabs nav-tabs-warning">
                                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#interfaces-riesgo">Riesgo</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#interfaces-causas">Causas</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#interfaces-sintesis-apc">Síntesis APC</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card">
                        <div class="body">                            
                            <div class="tab-content">
                                <div class="tab-pane active" id="interfaces-riesgo">
                                    <div class="row clearfix">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-3 text-center"></div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-6 text-center">
                                            <div class="card">
                                                <div class="body">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="h6 mb-0"></div>
                                                        <div class="action">
                                                            <a href="javascript:void(0);" id="riesgo-info"><i class="zmdi zmdi-info"></i></a>
                                                        </div>
                                                    </div>
                                                    <input id="val-riesgo-interfaces" type="text" class="knob" value="" data-linecap="round" data-width="100" data-height="100" data-thickness="0.08" data-fgColor="#44bd00" readonly>
                                                    <p>Riesgo</p>
                                                    <div class="d-flex bd-highlight text-center mt-4">
                                                        <div class="flex-fill bd-highlight">
                                                            <small class="text-muted">Amenaza</small>
                                                            <h5 class="mb-0" id="val-amenaza-interfaces"></h5>
                                                        </div>
                                                        <div class="flex-fill bd-highlight">
                                                            <small class="text-muted">Vulnerabilidad</small>
                                                            <h5 class="mb-0" id="val-vulnerabilidad-interfaces"></h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row clearfix">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-3 text-center">
                                            <div class="card">
                                                <div class="header">
                                                    <h2>Amenaza - <strong>Prevalencia</strong></h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-6 text-center">
                                            <div class="card">
                                                <div class="body">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="h6 mb-0"></div>
                                                        <div class="action">
                                                            <a href="javascript:void(0);" id="prevalencia-info"><i class="zmdi zmdi-info"></i></a>
                                                        </div>
                                                    </div>
                                                    <input id="val-prevalencia" type="text" class="knob" value="" data-linecap="round" data-width="100" data-height="100" data-thickness="0.08" data-fgColor="#44bd00" readonly>
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
                                                    <input id="val-ocurrencia" type="text" class="knob" value="" data-linecap="round" data-width="100" data-height="100" data-thickness="0.08" data-fgColor="#44bd00" readonly>
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
                                                    <input id="val-dano" type="text" class="knob" value="" data-linecap="round" data-width="100" data-height="100" data-thickness="0.08" data-fgColor="#44bd00" readonly>
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

                                    <hr>

                                    <div class="row clearfix">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-3 text-center">
                                            <div class="card">
                                                <div class="header">
                                                    <h2>Amenaza - <strong>Pendiente</strong></h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-6 text-center">
                                            <div class="card">
                                                <div class="body">
                                                    <div id="chart-pendiente" class="c3_chart"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-2 mt-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="h6 mb-0"></div>
                                                <div class="action">
                                                    <a href="javascript:void(0);" id="pendiente-interfaces-info"><i class="zmdi zmdi-info"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="card">
                                                <div class="header">
                                                    <h2>Amenaza - <strong>Combustible</strong></h2>
                                                </div>
                                                <div class="body">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="h6 mb-0"></div>
                                                        <div class="action">
                                                            <a href="javascript:void(0);" id="combustible-interfaces-info"><i class="zmdi zmdi-info"></i></a>
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

                                    <hr>

                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="card">
                                                <div class="header">
                                                    <h2>Vulnerabilidad - <strong>Población</strong></h2>
                                                </div>
                                                <div class="row clearfix">
                                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="h6 mb-0"></div>
                                                            <div class="action">
                                                                <a href="javascript:void(0);" id="poblacion-interfaces-info"><i class="zmdi zmdi-info"></i></a>
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
                                                                <a href="javascript:void(0);" id="densidad-poblacional-interfaces-info"><i class="zmdi zmdi-info"></i></a>
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
                                                                <a href="javascript:void(0);" id="viviendas-interfaces-info"><i class="zmdi zmdi-info"></i></a>
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
                                                                <a href="javascript:void(0);" id="densidad-viviendas-interfaces-info"><i class="zmdi zmdi-info"></i></a>
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

                                    <hr>

                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="card">
                                                <div class="header">
                                                    <h2>Vulnerabilidad - <strong>Viviendas</strong></h2>
                                                </div>
                                                <div class="body">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-sm-3"></div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 text-center">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div class="h6 mb-0"></div>
                                                                <div class="action">
                                                                    <a href="javascript:void(0);" id="techo-viviendas-interfaces-info"><i class="zmdi zmdi-info"></i></a>
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
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="card">
                                                            <div class="body">
                                                            <div class="table-responsive">
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
                                                    <div class="row mt-2">
                                                        <div class="col-lg-3 col-md-3 col-sm-3"></div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 text-center">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div class="h6 mb-0"></div>
                                                                <div class="action">
                                                                    <a href="javascript:void(0);" id="suciedad-techos-interfaces-info"><i class="zmdi zmdi-info"></i></a>
                                                                </div>
                                                            </div>
                                                            <?php
                                                            $suciedadTechos = 0;
                                                            foreach ($datosLimpiezaTechos as $data) {
                                                                $suciedadTechos = $suciedadTechos + $data;
                                                            }
                                                            $meanSuciedadTechos = round($suciedadTechos / sizeof($datosLimpiezaTechos));
                                                            ?>                      
                                                            <input type="text" class="knob" value="<?= $meanSuciedadTechos ?>" data-linecap="round" data-width="100" data-height="100" data-thickness="0.08"
                                                            data-fgColor="
                                                            <?php
                                                            if ($meanSuciedadTechos <= 25) echo '#44bd00';
                                                            else if ($meanSuciedadTechos > 25 && $meanSuciedadTechos <= 50) echo '#ffbb00';
                                                            else if ($meanSuciedadTechos > 50 && $meanSuciedadTechos <= 75) echo '#ff7300';
                                                            else if ($meanSuciedadTechos > 75 && $meanSuciedadTechos <= 100) echo '#ff1b19';
                                                            ?>
                                                            " readonly>
                                                            <p>Suciedad de Techos</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row clearfix">
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-6 text-center">
                                            <div class="card">
                                                <div class="body">       
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="h6 mb-0"></div>
                                                        <div class="action">
                                                            <a href="javascript:void(0);" id="residuos-agricolas-interfaces-info"><i class="zmdi zmdi-info"></i></a>
                                                        </div>
                                                    </div>    
                                                    <?php
                                                    $residuosAgricolas = 0;
                                                    foreach ($datosResiduosAgricolas as $puntos) {
                                                        $residuosAgricolas = $residuosAgricolas + $puntos;
                                                    }
                                                    $meanResiduosAgricolas = round($residuosAgricolas / sizeof($datosResiduosAgricolas));
                                                    ?>         
                                                    <input type="text" class="knob" value="<?= $meanResiduosAgricolas ?>" data-linecap="round" data-width="100" data-height="100" data-thickness="0.08"
                                                    data-fgColor="
                                                        <?php
                                                        if ($meanResiduosAgricolas <= 25) echo '#44bd00';
                                                        else if ($meanResiduosAgricolas > 25 && $meanResiduosAgricolas <= 50) echo '#ffbb00';
                                                        else if ($meanResiduosAgricolas > 50 && $meanResiduosAgricolas <= 75) echo '#ff7300';
                                                        else if ($meanResiduosAgricolas > 75 && $meanResiduosAgricolas <= 100) echo '#ff1b19';
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
                                                            <a href="javascript:void(0);" id="residuos-forestales-interfaces-info"><i class="zmdi zmdi-info"></i></a>
                                                        </div>
                                                    </div>     
                                                    <?php
                                                    $residuosForestales = 0;
                                                    foreach ($datosResiduosForestales as $puntos) {
                                                        $residuosForestales = $residuosForestales + $puntos;
                                                    }
                                                    $meanResiduosForestales = round($residuosForestales / sizeof($datosResiduosForestales));
                                                    ?>                       
                                                    <input type="text" class="knob" value="<?= $meanResiduosForestales ?>" data-linecap="round" data-width="100" data-height="100" data-thickness="0.08"
                                                    data-fgColor="
                                                        <?php
                                                        if ($meanResiduosForestales <= 25) echo '#44bd00';
                                                        else if ($meanResiduosForestales > 25 && $meanResiduosForestales <= 50) echo '#ffbb00';
                                                        else if ($meanResiduosForestales > 50 && $meanResiduosForestales <= 75) echo '#ff7300';
                                                        else if ($meanResiduosForestales > 75 && $meanResiduosForestales <= 100) echo '#ff1b19';
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
                                                            <a href="javascript:void(0);" id="residuos-domesticos-interfaces-info"><i class="zmdi zmdi-info"></i></a>
                                                        </div>
                                                    </div>       
                                                    <?php
                                                    $residuosDomesticos = 0;
                                                    foreach ($datosResiduosDomesticos as $puntos) {
                                                        $residuosDomesticos = $residuosDomesticos + $puntos;
                                                    }
                                                    $meanResiduosDomesticos = round($residuosDomesticos / sizeof($datosResiduosDomesticos));
                                                    ?>                      
                                                    <input type="text" class="knob" value="<?= $meanResiduosDomesticos ?>" data-linecap="round" data-width="100" data-height="100" data-thickness="0.08"
                                                    data-fgColor="
                                                        <?php
                                                        if ($meanResiduosDomesticos <= 25) echo '#44bd00';
                                                        else if ($meanResiduosDomesticos > 25 && $meanResiduosDomesticos <= 50) echo '#ffbb00';
                                                        else if ($meanResiduosDomesticos > 50 && $meanResiduosDomesticos <= 75) echo '#ff7300';
                                                        else if ($meanResiduosDomesticos > 75 && $meanResiduosDomesticos <= 100) echo '#ff1b19';
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
                                                            <a href="javascript:void(0);" id="residuos-industriales-interfaces-info"><i class="zmdi zmdi-info"></i></a>
                                                        </div>
                                                    </div>       
                                                    <?php
                                                    $residuosIndustriales = 0;
                                                    foreach ($datosResiduosIndustriales as $puntos) {
                                                        $residuosIndustriales = $residuosIndustriales + $puntos;
                                                    }
                                                    $meanResiduosIndustriales = round($residuosIndustriales / sizeof($datosResiduosIndustriales));
                                                    ?>                         
                                                    <input type="text" class="knob" value="<?= $meanResiduosIndustriales ?>" data-linecap="round" data-width="100" data-height="100" data-thickness="0.08"
                                                    data-fgColor="
                                                        <?php
                                                        if ($meanResiduosIndustriales <= 25) echo '#44bd00';
                                                        else if ($meanResiduosIndustriales > 25 && $meanResiduosIndustriales <= 50) echo '#ffbb00';
                                                        else if ($meanResiduosIndustriales > 50 && $meanResiduosIndustriales <= 75) echo '#ff7300';
                                                        else if ($meanResiduosIndustriales > 75 && $meanResiduosIndustriales <= 100) echo '#ff1b19';
                                                        ?>
                                                    " readonly>
                                                    <p>Existencia de Residuos Industriales</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="interfaces-causas">
                                    <div class="row clearfix">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-6">
                                            <div class="text-center">
                                                <div class="card">
                                                    <div class="body"> 
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="h6 mb-0"></div>
                                                            <div class="action">
                                                                <a href="javascript:void(0);" id="causas-interfaces-info"><i class="zmdi zmdi-info"></i></a>
                                                            </div>
                                                        </div>                            
                                                        <input type="text" class="knob" value="<?= $totalCausasInterfaces; ?>" data-linecap="round" data-width="100" data-height="100" data-thickness="0.08" readonly
                                                        data-fgColor="
                                                        <?php
                                                        if ($totalCausasInterfaces <= 25) echo '#44bd00';
                                                        else if ($totalCausasInterfaces > 25 && $totalCausasInterfaces <= 50) echo '#ffbb00';
                                                        else if ($totalCausasInterfaces > 50 && $totalCausasInterfaces <= 75) echo '#ff7300';
                                                        else if ($totalCausasInterfaces > 75) echo '#ff1b19';
                                                        ?>
                                                        ">
                                                        <p>Causas específicas</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="table-responsive">
                                                <table class="table table-hover product_item_list c_table theme-color mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th class="bg-green">Causas</th>
                                                            <th class="bg-green">Total</th>
                                                            <th class="bg-green">% total</th>
                                                            <th class="bg-green">Grupo específica</th>
                                                            <th class="bg-green">Total específica</th>
                                                            <th class="bg-green">% especifica</th>
                                                            <th class="bg-green">Detalles</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $totalCausas = 0;
                                                        foreach ($grupoCausas as $causa) {
                                                            $totalCausas = $totalCausas + $causa['cantidad'];
                                                        }

                                                        foreach ($grupoCausas as $causa) {
                                                        ?>
                                                        <tr style="outline: thin solid;">
                                                            <td><?= $causa['grupo_causa']; ?></td>
                                                            <td><?= $causa['cantidad']; ?></td>
                                                            <td><?= round(($causa['cantidad']/$totalCausas)*100, 1); ?>%</td>
                                                            <td>
                                                                <?php
                                                                foreach ($grupoEspecificas as $especifica) {
                                                                    if ($causa['grupo_causa'] == $especifica['grupo_causa']) {
                                                                        echo $especifica['grupo_especifica'].'<br><hr>';
                                                                    }
                                                                }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                foreach ($grupoEspecificas as $especifica) {
                                                                    if ($causa['grupo_causa'] == $especifica['grupo_causa']) {
                                                                        echo $especifica['cantidad'].'<br><hr>';
                                                                    }
                                                                }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                foreach ($grupoEspecificas as  $especifica) {
                                                                    if ($causa['grupo_causa'] == $especifica['grupo_causa']) {
                                                                        echo round(($especifica['cantidad']/$causa['cantidad'])*100, 1).'%<br><hr>';
                                                                    }
                                                                }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                foreach ($grupoEspecificas as $especifica) {
                                                                    if ($causa['grupo_causa'] == $especifica['grupo_causa']) {
                                                                    ?>
                                                                    <div class="text-center">
                                                                        <button onclick="getDetailsGrupoCausas(this)" value="<?= $causa['grupo_causa'].','.$especifica['grupo_especifica']; ?>" class="btn btn-primary waves-effect waves-float btn-sm waves-blue" type="button" title="Ver detalles" style="margin-top: -10px; margin-bottom: -10px;"><i class="zmdi zmdi-eye"></i></button><br><hr>
                                                                    </div>
                                                                    <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        }
                                                        ?>   
                                                        <tr>
                                                            <td>Total</td>
                                                            <td><?= $totalCausas; ?></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td><?= $totalCausas; ?></td>
                                                        </tr>    
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="tab-pane" id="interfaces-sintesis-apc">
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="card">
                                                <div class="row clearfix">
                                                    <div class="col-lg-4 col-md-6 col-sm-6">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="h6 mb-0"></div>
                                                            <div class="action">
                                                                <a href="javascript:void(0);" id="apc-interfaces-info"><i class="zmdi zmdi-info"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="card w_data_1">
                                                            <div class="body">
                                                                <div class="w_icon green"><i class="zmdi zmdi-assignment"></i></div>
                                                                <h4 class="mt-3"><?= $countPlanes; ?></h4>
                                                                <span class="text-muted">N° de planes</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6 col-sm-6">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="h6 mb-0"></div>
                                                            <div class="action">
                                                                <a href="javascript:void(0);" id="apc-habitantes-interfaces-info"><i class="zmdi zmdi-info"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="card w_data_1">
                                                            <div class="body">
                                                                <div class="w_icon blue"><i class="zmdi zmdi-accounts-add"></i></div>
                                                                <h4 class="mt-3"><?= $datosPoblacion['n_habitantes'] ?></h4>
                                                                <span class="text-muted">N° de habitantes</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6 col-sm-6">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="h6 mb-0"></div>
                                                            <div class="action">
                                                                <a href="javascript:void(0);" id="apc-viviendas-interfaces-info"><i class="zmdi zmdi-info"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="card w_data_1">
                                                            <div class="body">
                                                                <div class="w_icon indigo"><i class="zmdi zmdi-home"></i></div>
                                                                <h4 class="mt-3"><?= $datosPoblacion['n_viviendas'] ?></h4>
                                                                <span class="text-muted">N° de viviendas</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>

                                                <div class="row clearfix">
                                                    <div class="col-lg-12 col-md-12">
                                                        <div class="card">
                                                            <div class="body">
                                                                <div class="row">
                                                                    <div class="col-lg-4 col-md-4 col-sm-4"></div>
                                                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                            <div class="h6 mb-0"></div>
                                                                            <div class="action">
                                                                                <a href="javascript:void(0);" id="apc-avance-interfaces-info"><i class="zmdi zmdi-info"></i></a>
                                                                            </div>
                                                                        </div>
                                                                        <div class="card w_data_1">
                                                                            <div class="body">
                                                                                <div class="w_icon orange"><i class="zmdi zmdi-trending-up"></i></div>
                                                                                <h4 class="mt-3"><?= round($meanAvancePlanes, 2); ?></h4>
                                                                                <span class="text-muted">% avance de planes</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-0">
                                                                    <div class="card">
                                                                        <div class="body">
                                                                            <div class="table-responsive">
                                                                                <table class="table table-hover table-striped product_item_list c_table theme-color mb-0">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th class="bg-green">Interfaz</th>
                                                                                            <th class="bg-green">N° medidas específicas</th>
                                                                                            <th class="bg-green">N° medidas generales</th>
                                                                                            <th class="bg-green">% avance medidas</th>
                                                                                            <th class="bg-green">APC</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <?php
                                                                                        foreach ($medidasInterfaces as $id => $interfaz) {
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td><?= $interfaz[0]; ?></td>
                                                                                            <td><?= sizeof($interfaz[1]); ?></td>
                                                                                            <td><?= sizeof($interfaz[2]); ?></td>
                                                                                            <td><?= round($interfaz[3] / (sizeof($interfaz[1]) + sizeof($interfaz[2])), 2); ?></td>
                                                                                            <td><a href="<?= site_url('admin/gestion/agenda/interfaz/'.$id); ?>" class="btn btn-primary waves-effect waves-float btn-sm waves-blue" title="Ver agenda"><i class="zmdi zmdi-assignment"></i></a></td>
                                                                                        </tr>
                                                                                        <?php
                                                                                        }
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td>Total</td>
                                                                                            <td><?= $countMedidasEspecificas; ?></td>
                                                                                            <td><?= $countMedidasGenerales; ?></td>
                                                                                            <td></td>
                                                                                            <td></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
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

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-causas-especificas" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="modal-nombre-grupo-causa"></h4>
            </div>
            <div class="modal-body">
                <div id="div-content-causas-especificas" class="row clearfix"></div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btn-measure-modal-close" class="btn btn-danger btn-round waves-effect" data-dismiss="modal">CERRAR</button>
            </div>
        </div>
    </div>
</div>

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


<script src="<?= base_url('public/js/pages/reports/main_interfaces.js'); ?>"></script>
<script src="<?= base_url('public/js/pages/info_messages.js'); ?>"></script>