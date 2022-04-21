<section class="content">
    <div class="body_scroll">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <h2>Detalles grupo</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/gestion/listar'); ?>"><i class="zmdi zmdi-widgets"></i> Gestionar interfaces</a></li>
                        <li class="breadcrumb-item">APC</li>
                        <li class="breadcrumb-item">Medidas específicas</li>
                        <li class="breadcrumb-item active">Detalles grupo</li>
                    </ul>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12">                
                    <button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i class="zmdi zmdi-arrow-right"></i></button>                                
                    <a href="<?= site_url('admin/gestion/apcespecifica/listar/interfaz/'.$apc['interfaz']); ?>" class="btn btn-secondary btn-icon float-right waves-effect waves-float waves-gray" type="button" title="Medidas específicas"><i class="zmdi zmdi-chevron-left"></i></a>
                </div>
            </div>
        </div>
        
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="alert bg-black text-center"><h6><?= $apc['nombre_interfaz']; ?> <br> Grupo: <?= $apc['grupo_especifica']; ?></h6></div>
                                </div>
                                <div class="col-md-3">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="body">                              
                            <a href="<?= site_url('admin/gestion/apcespecifica/editar/'.$apc['apc_espe_id'].'/interfaz/'.$apc['interfaz']); ?>" class="btn btn-warning waves-effect waves-float waves-yellow btn-icon float-right" title="Editar medidas"><i class="zmdi zmdi-edit"></i></a>
                            <h2 class="card-inside-title">Medidas</h2>
                            <div class="row clearfix">
                            <?php
                            foreach ($medidas as $medida) {
                            ?>
                                <div class="col-lg-4 col-md-6 col-sm-6 col-6 text-center">
                                    <div class="card">
                                        <div class="body" style="border: 1px solid; border-color: green;">
                                            <input type="text" class="knob" value="<?= $medida['avance']; ?>" data-linecap="round" data-width="100" data-height="100" data-thickness="0.08"
                                                data-fgColor="
                                                <?php
                                                if (($medida['avance'] >= 0) && ($medida['avance'] <= 25)) echo '#ff1b19';
                                                else if (($medida['avance'] > 25) && ($medida['avance'] <= 50)) echo '#ff7300';
                                                else if (($medida['avance'] > 50) && ($medida['avance'] <= 75)) echo '#ffbb00';
                                                else if (($medida['avance'] > 75) && ($medida['avance'] <= 100)) echo '#44bd00';
                                                ?>" readonly>
                                            <p>% Avance</p>
                                            <div class="d-flex bd-highlight text-left mt-4">
                                                <div class="flex-fill bd-highlight">
                                                    <small class="text-muted">Medida</small>
                                                    <p>
                                                        <?php
                                                        if ($medida['grupo_objetivo'] == '' && $medida['zonas_objetivo'] == '') echo $medida['nombre'];
                                                        else echo $medida['nombre'].' a '.$medida['grupo_objetivo'].' en '.$medida['zonas_objetivo'];
                                                        ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="d-flex bd-highlight text-left">
                                                <div class="flex-fill bd-highlight">
                                                    <small class="text-muted">Responsable</small>
                                                    <p>
                                                        <?php
                                                        if ($medida['responsable'] != '') echo $medida['responsable'];
                                                        else echo 'no indicado';
                                                        ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="d-flex bd-highlight text-left">
                                                <div class="flex-fill bd-highlight">
                                                    <small class="text-muted">Contacto</small>
                                                    <p>
                                                        <?php
                                                        if ($medida['contacto_responsable'] != '') echo $medida['contacto_responsable'];
                                                        else echo 'no indicado';
                                                        ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="d-flex bd-highlight text-left">
                                                <div class="flex-fill bd-highlight">
                                                    <small class="text-muted">Fecha de inicio</small>
                                                    <p><?= date('d/m/Y', strtotime($medida['fecha_inicio'])); ?></p>
                                                </div>
                                            </div>
                                            <div class="d-flex bd-highlight text-left">
                                                <div class="flex-fill bd-highlight">
                                                    <small class="text-muted">Fecha de termino</small>
                                                    <p><?= date('d/m/Y', strtotime($medida['fecha_termino'])); ?></p>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>