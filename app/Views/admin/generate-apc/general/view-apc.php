<section class="content">
    <div class="body_scroll">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <h2>Detalles medida</h2>
                    <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('admin/apc/general/listar'); ?>"><i class="zmdi zmdi-assignment-o"></i> Generar APC</a></li>                        
                        <li class="breadcrumb-item">Medidas generales</li>
                        <li class="breadcrumb-item active">Detalles medida</li>
                    </ul>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12">                
                    <button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i class="zmdi zmdi-arrow-right"></i></button>
                    <a href="<?= site_url('admin/apc/general/listar'); ?>" class="btn btn-secondary btn-icon float-right waves-effect waves-float waves-gray" type="button" title="Medidas generales"><i class="zmdi zmdi-chevron-left"></i></a>
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
                                    <div class="alert bg-black text-center"><h6><?= $apc['nombre_interfaz']; ?></h6></div>
                                    <input type="hidden" id="id-medida-general" value="<?= $medida['medida_gene_id']; ?>">
                                    <input type="hidden" id="id-interfaz" value="<?= $apc['interfaz']; ?>">
                                </div>
                                <div class="col-md-3">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="body">                              
                            <button id="btn-eliminar-medida" class="btn btn-danger waves-effect waves-float waves-red btn-icon float-right" type="button" title="Eliminar medida"><i class="zmdi zmdi-delete"></i></button>
                            <a href="<?= site_url('admin/apc/general/editar/'.$medida['medida_gene_id']); ?>" class="btn btn-warning waves-effect waves-float waves-yellow btn-icon float-right" title="Editar medida"><i class="zmdi zmdi-edit"></i></a>
                            <div class="row clearfix mt-2">
                                <div class="col-md-3"></div>
                                <div class="col-lg-7 col-md-6 col-sm-6 col-6 text-center">
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
                                                        if ($medida['nombre'] == 'Construcci??n de cortafuegos' || $medida['nombre'] == 'Mantenci??n de cortafuegos') {
                                                            if ($medida['zonas_objetivo'] == '') echo $medida['nombre'];
                                                            else echo str_replace('cortafuegos', $medida['zonas_objetivo'], $medida['nombre']);
                                                        } else {
                                                            if ($medida['objetivo'] != '' && $medida['zonas_objetivo'] == '') echo $medida['nombre'].' '.$medida['objetivo'];
                                                            else if ($medida['objetivo'] == '' && $medida['zonas_objetivo'] != '') echo $medida['nombre'].' en '.$medida['zonas_objetivo'];
                                                            else if ($medida['objetivo'] == '' && $medida['zonas_objetivo'] == '') echo $medida['nombre'];
                                                        }
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal-eliminar-medida" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="title">Confirmar acci??n</h4>
                        </div>
                        <div class="modal-body text-center">
                            <p>
                            La siguiente medida se eliminar?? de forma permanente: <br>
                            <?php
                            if ($medida['nombre'] == 'Construcci??n de cortafuegos' || $medida['nombre'] == 'Mantenci??n de cortafuegos') {
                                if ($medida['zonas_objetivo'] == '') echo $medida['nombre'];
                                else echo str_replace('cortafuegos', $medida['zonas_objetivo'], $medida['nombre']);
                            } else {
                                if ($medida['objetivo'] != '' && $medida['zonas_objetivo'] == '') echo $medida['nombre'].' '.$medida['objetivo'];
                                else if ($medida['objetivo'] == '' && $medida['zonas_objetivo'] != '') echo $medida['nombre'].' en '.$medida['zonas_objetivo'];
                                else if ($medida['objetivo'] == '' && $medida['zonas_objetivo'] == '') echo $medida['nombre'];
                            }
                            ?>
                            </p>
                            <p>??Desea continuar?</p>
                        </div>
                        <div class="modal-footer mt-4">
                            <button type="button" id="btn-confirmar-eliminacion" class="btn btn-primary btn-round waves-effect">CONFIRMAR</button>
                            <button type="button" id="btn-cancelar-eliminacion" class="btn btn-danger btn-round waves-effect" data-dismiss="modal">CANCELAR</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?= base_url('public/js/pages/generate-apc/main_general.js'); ?>"></script>