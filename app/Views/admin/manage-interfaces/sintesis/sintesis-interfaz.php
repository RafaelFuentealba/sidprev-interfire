<section class="content">
    <div class="body_scroll">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <h2>Síntesis de causas</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/gestion/listar'); ?>"><i class="zmdi zmdi-widgets"></i> Gestionar interfaces</a></li>
                        <li class="breadcrumb-item">Análisis</li>
                        <li class="breadcrumb-item active">Síntesis de causas</li>
                    </ul>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12">                
                    <button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i class="zmdi zmdi-arrow-right"></i></button>
                    <a href="<?= site_url('admin/gestion/listar'); ?>" class="btn btn-secondary btn-icon float-right waves-effect waves-float waves-gray" type="button" title="Gestionar interfaces"><i class="zmdi zmdi-chevron-left"></i></a>
                </div>
            </div>
        </div>
        
        <div class="container-fluid">
            <div class="card">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="alert bg-black text-center"><h6><?= $interfaz['nombre']; ?></h6></div>
                        </div>
                        <div class="col-md-3 mt-2 text-center">
                            <button onclick="exportarPDF()" class="btn btn-warning btn-icon waves-effect waves-float waves-yellow" type="button" title="Exportar a PDF"><i class="zmdi zmdi-cloud-download"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="body">
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
                                        foreach ($grupoCausas as $grupo) {
                                            $totalCausas = $totalCausas + $grupo['cantidad'];
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

                        <div class="table-responsive" style="display: none;">
                            <div id="table-sintesis-causas">
                                <h6><?= $interfaz['nombre']; ?></h6>
                                <table class="table table-hover product_item_list c_table theme-color mb-0">
                                    <thead>
                                        <tr>
                                            <th class="bg-green">Causas</th>
                                            <th class="bg-green">Total</th>
                                            <th class="bg-green">% total</th>
                                            <th class="bg-green">Grupo específica</th>
                                            <th class="bg-green">Total específica</th>
                                            <th class="bg-green">% especifica</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-body-sintesis">
                                        <?php
                                        $totalCausas = 0;
                                        foreach ($grupoCausas as $grupo) {
                                            $totalCausas = $totalCausas + $grupo['cantidad'];
                                        }

                                        foreach ($grupoCausas as $causa) {
                                        ?>
                                        <tr>
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
            </div>
        </div>
    </div>
</section>

<script src="<?= base_url('public/js/pages/analysis/main_sintesis.js'); ?>"></script>