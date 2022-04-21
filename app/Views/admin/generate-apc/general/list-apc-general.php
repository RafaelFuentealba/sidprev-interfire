<section class="content">
    <div class="body_scroll">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <h2>Medidas generales</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/apc/general/listar'); ?>"><i class="zmdi zmdi-assignment-o"></i> Generar APC</a></li>
                        <li class="breadcrumb-item active">Medidas generales</li>
                    </ul>
                    <button class="btn btn-primary btn-icon mobile_menu" type="button"><i class="zmdi zmdi-sort-amount-desc"></i></button>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12">                                    
                    <button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i class="zmdi zmdi-arrow-right"></i></button>
                    <a href="<?= site_url('admin/gestion/listar'); ?>" class="btn btn-secondary btn-icon float-right waves-effect waves-float waves-gray" type="button" title="Gestionar interfaces"><i class="zmdi zmdi-chevron-right"></i></a>
                    <a href="<?= site_url('admin/apc/especifica/listar'); ?>" class="btn btn-secondary btn-icon float-right waves-effect waves-float waves-gray" type="button" title="Medidas específicas"><i class="zmdi zmdi-chevron-left"></i></a>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="alert bg-black text-center"><h6><?= $interfaz['nombre']; ?></h6></div>
                                    <input type="hidden" id="id-interfaz" value="<?= $interfaz['interfaz_id']; ?>">
                                </div>
                                <div class="col-md-3 mt-2 text-center">
                                    <button onclick="exportarPDF()" class="btn btn-warning btn-icon waves-effect waves-float waves-yellow" type="button" title="Exportar a PDF"><i class="zmdi zmdi-cloud-download"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="body">
                            <div class="row clearfix mt-2">
                                <div class="col-lg-12">
                                    <button id="btn-delete-multiples-medidas" class="btn btn-danger waves-effect waves-float waves-red btn-icon float-right" type="button" title="Eliminar medidas seleccionadas"><i class="zmdi zmdi-delete"></i></button>
                                    <button id="btn-agregar-medida" class="btn btn-success waves-effect waves-float waves-green btn-icon float-right" type="button" title="Agregar medida"><i class="zmdi zmdi-plus"></i></button>
                                    <div class="table-responsive">
                                        <table class="table table-hover product_item_list c_table theme-color mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="bg-green"></th>
                                                    <th class="bg-green">Medida</th>
                                                    <th class="bg-green">Responsables</th>
                                                    <th class="bg-green">Contacto</th>
                                                    <th class="bg-green">Plazos</th>
                                                    <th class="bg-green">Avance</th>
                                                    <th class="bg-green">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($medidasGenerales as $medida) {
                                                ?>
                                                <tr style="outline: thin solid">
                                                    <td>
                                                        <input name="check-medida" type="checkbox" class="checkbox mt-0" value="<?= $medida['medida_gene_id']; ?>">
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if ($medida['nombre'] == 'Construcción de cortafuegos' || $medida['nombre'] == 'Mantención de cortafuegos') {
                                                            if ($medida['zonas_objetivo'] == '') {
                                                                $printMedida = $medida['nombre'];
                                                                if (strlen($printMedida) > 40) echo wordwrap($printMedida, 40, '<br>');
                                                                else echo $printMedida;
                                                            } else {
                                                                $printMedida = str_replace('cortafuegos', $medida['zonas_objetivo'], $medida['nombre']);
                                                                if (strlen($printMedida) > 40) echo wordwrap($printMedida, 40, '<br>');
                                                                else echo $printMedida;
                                                            }
                                                        } else {
                                                            if ($medida['objetivo'] != '' && $medida['zonas_objetivo'] == '') {
                                                                $printMedida = $medida['nombre'].' '.$medida['objetivo'];
                                                                if (strlen($printMedida) > 40) echo wordwrap($printMedida, 40, '<br>');
                                                                else echo $printMedida;
                                                            } else if ($medida['objetivo'] == '' && $medida['zonas_objetivo'] != '') {
                                                                $printMedida = $medida['nombre'].' en '.$medida['zonas_objetivo'];
                                                                if (strlen($printMedida) > 40) echo wordwrap($printMedida, 40, '<br>');
                                                                else echo $printMedida;
                                                            } else if ($medida['objetivo'] == '' && $medida['zonas_objetivo'] == '') {
                                                                $printMedida = $medida['nombre'];
                                                                if (strlen($printMedida) > 40) echo wordwrap($printMedida, 40, '<br>');
                                                                else echo $printMedida;
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if (strlen($medida['responsable']) > 40) echo wordwrap($medida['responsable'], 40, '<br>');
                                                        else echo $medida['responsable'];
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if (strlen($medida['contacto_responsable']) > 40) echo wordwrap($medida['contacto_responsable'], 40, '<br>');
                                                        else echo $medida['contacto_responsable'];
                                                        ?>
                                                    </td>
                                                    <td><?php echo date('d/m/Y', strtotime($medida['fecha_inicio'])).' - '.date('d/m/Y', strtotime($medida['fecha_termino'])); ?></td>
                                                    <td><?= $medida['avance']; ?></td>
                                                    <td>
                                                        <a href="<?= site_url('admin/apc/general/ver/'.$medida['medida_gene_id']); ?>" class="btn btn-primary waves-effect waves-float btn-sm waves-blue" title="Ver detalles"><i class="zmdi zmdi-eye"></i></a>
                                                        <a href="<?= site_url('admin/apc/general/editar/'.$medida['medida_gene_id']); ?>" class="btn btn-warning waves-effect waves-float btn-sm waves-yellow" title="Editar"><i class="zmdi zmdi-edit"></i></a>
                                                        <button onclick="eliminarMedidaListado(<?= $medida['medida_gene_id'] ?>)" class="btn btn-danger waves-effect waves-float btn-sm waves-red" title="Eliminar medida"><i class="zmdi zmdi-delete"></i></button>
                                                    </td>
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
                    
                    <div class="card" style="display:none;">
                        <div class="header" id="pdf-header">
                            <div class="alert bg-green text-center">
                                <h6><?= $interfaz['nombre']; ?></h6>
                                <p>Medidas generales</p>
                            </div>
                            <hr>
                        </div>
                        <div class="body" id="pdf-body" style="page-break-inside:avoid; font-size:10pt; font-family:Arial;">
                            <?php
                            foreach ($medidasGenerales as $medida) {
                            ?>
                                <span class="badge badge-warning" style="font-size:10pt;"><?= $medida['avance'] ?> %</span>
                            <?php
                                if ($medida['nombre'] == 'Construcción de cortafuegos' || $medida['nombre'] == 'Mantención de cortafuegos') {
                                    if ($medida['zonas_objetivo'] != '' && $medida['contacto_responsable'] != '') {
                                        $printMedida = str_replace('cortafuegos', $medida['zonas_objetivo'], $medida['nombre']);
                                    ?>
                                        <b>Medida: </b> <?= $printMedida ?>. <b>Responsables: </b><?= $medida['responsable'] ?>. <b>Contacto: </b> <?= $medida['contacto_responsable'] ?>. <b>Plazos: </b> <?= date('d/m/Y', strtotime($medida['fecha_inicio'])) ?> - <?= date('d/m/Y', strtotime($medida['fecha_termino'])) ?>.
                                    <?php
                                    } else {                                        
                                    ?>
                                        <b>Medida: </b> <?= $medida['nombre'] ?>. <b>Responsables: </b><?= $medida['responsable'] ?>. <b>Contacto: </b> no indicado. <b>Plazos: </b> <?= date('d/m/Y', strtotime($medida['fecha_inicio'])) ?> - <?= date('d/m/Y', strtotime($medida['fecha_termino'])) ?>.
                                    <?php
                                    }
                                } else if ($medida['nombre'] == 'Generar ordenanza local') {
                                    if ($medida['contacto_responsable'] != '') {
                                    ?>
                                        <b>Medida: </b> <?= $medida['nombre'] ?> <?= $medida['objetivo'] ?>. <b>Responsables: </b><?= $medida['responsable'] ?>. <b>Contacto: </b> <?= $medida['contacto_responsable'] ?>. <b>Plazos: </b> <?= date('d/m/Y', strtotime($medida['fecha_inicio'])) ?> - <?= date('d/m/Y', strtotime($medida['fecha_termino'])) ?>.
                                    <?php
                                    } else {
                                    ?>
                                        <b>Medida: </b> <?= $medida['nombre'] ?> <?= $medida['objetivo'] ?>. <b>Responsables: </b><?= $medida['responsable'] ?>. <b>Contacto: </b> no indicado. <b>Plazos: </b> <?= date('d/m/Y', strtotime($medida['fecha_inicio'])) ?> - <?= date('d/m/Y', strtotime($medida['fecha_termino'])) ?>.
                                    <?php
                                    }
                                } else {
                                    if ($medida['zonas_objetivo'] != '' && $medida['contacto_responsable']) {
                                    ?>
                                        <b>Medida: </b> <?= $medida['nombre'] ?> en <?= $medida['zonas_objetivo'] ?>. <b>Responsables: </b><?= $medida['responsable'] ?>. <b>Contacto: </b> <?= $medida['contacto_responsable'] ?>. <b>Plazos: </b> <?= date('d/m/Y', strtotime($medida['fecha_inicio'])) ?> - <?= date('d/m/Y', strtotime($medida['fecha_termino'])) ?>.
                                    <?php
                                    } else {
                                    ?>
                                        <b>Medida: </b> <?= $medida['nombre'] ?>. <b>Responsables: </b><?= $medida['responsable'] ?>. <b>Contacto: </b> no indicado. <b>Plazos: </b> <?= date('d/m/Y', strtotime($medida['fecha_inicio'])) ?> - <?= date('d/m/Y', strtotime($medida['fecha_termino'])) ?>.
                                    <?php
                                    }
                                }
                            ?>
                            <br><br>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <form action="" id="form-add-medida-general">
                <div class="modal fade" id="modal-add-medida-general" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="title">Agregar medida</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="card">
                                            <div class="body">
                                                <div id="fail-add-medida-general" class="alert alert-danger text-center" style="display: none;">
                                                    Debes completar toda la información solicitada <a href="javascript:void(0);" id="close-fail-add-medida-general" class="float-right"><i class="zmdi zmdi-close" title="Cerrar"></i></a>
                                                </div>
                                                <input type="text" id="id-apc-general" value="<?= $apcGeneral['apc_gene_id']; ?>" style="display: none;">
                                                <div id="wizard_vertical">
                                                    <h2>Medida</h2>
                                                    <section>
                                                        <div class="row clearfix">
                                                            <div class="col-sm-12">
                                                                <label for="">Seleccionar medida</label>
                                                                <select id="sel-medida" name="selMedida" class="form-control select2" style="width: 100%;">
                                                                    <option value="" selected="true" disabled>-- Seleccionar --</option>
                                                                    <?php
                                                                    foreach ($medidas as $medida) {
                                                                    ?>
                                                                    <option value="<?= $medida[0]; ?>"><?= $medida[0]; ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </section>
                                                    <h2>Zonas</h2>
                                                    <section>
                                                        <div class="row clearfix">
                                                            <div class="col-sm-12 mt-4">
                                                                <label for="" id="label-detalle-medida" style="display: none;">Indicar detalle de la medida</label>      
                                                                <label for="" id="label-zonas-objetivo" style="display: none;">Indicar zonas donde se aplicará la medida</label>                            
                                                                <input type="text" id="zonas-objetivo" style="display: none;" name="zonasObjetivo" class="form-control" value="">
                                                            </div>
                                                        </div>
                                                    </section>
                                                    <h2>Responsable</h2>
                                                    <section>
                                                        <div class="row clearfix">
                                                            <div class="col-sm-12">
                                                                <label for="">Responsables</label>
                                                                <?php
                                                                foreach ($medidas as $medida) {
                                                                ?>
                                                                <input type="text" id="responsables-<?= str_replace(' ', '-', $medida[0]); ?>" name="responsables" class="form-control" value="<?= $medida[3]; ?>"/>
                                                                <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </section>
                                                    <h2>Contacto</h2>
                                                    <section>
                                                        <div class="row clearfix">
                                                            <div class="col-sm-12">
                                                                <div class="form-group"> 
                                                                    <label for="">Indicar persona de contacto para la medida</label>                                   
                                                                    <input type="text" id="contacto-responsable" name="contactoResponsable" class="form-control" value="" placeholder="Ejemplo: Mauricio Peña Calderón" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </section>
                                                    <h2>Plazos</h2>
                                                    <section>
                                                        <div class="row clearfix">
                                                            <div class="col-md-12">
                                                                <label>Fecha de inicio para la medida</label>
                                                                <div class="input-group masked-input">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="zmdi zmdi-calendar"></i></span>
                                                                    </div>
                                                                    <input type="date" id="fecha-inicio" name="fechaInicio" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mt-4">
                                                                <label>Fecha de termino para la medida</label>
                                                                <div class="input-group masked-input">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="zmdi zmdi-calendar"></i></span>
                                                                    </div>
                                                                    <input type="date" id="fecha-termino" name="fechaTermino" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </section>
                                                    <h2>Avances</h2>
                                                    <section>
                                                        <div class="row clearfix">
                                                            <div class="col-md-12">
                                                                <label for="">Porcentaje de avance de la medida</label>
                                                                <input type="number" id="avance" name="avance" class="form-control text-center" value="0" min="0" max="100">
                                                            </div>
                                                        </div>
                                                    </section>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="submit" id="btn-save-medida-general" class="btn btn-primary btn-round waves-effect" value="GUARDAR MEDIDA">
                                <button type="button" id="btn-modal-close-medida" class="btn btn-danger btn-round waves-effect" data-dismiss="modal">CERRAR</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="modal fade" id="modal-eliminar-medida" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="title">Confirmar acción</h4>
                        </div>
                        <div class="modal-body text-center">
                            <p>La medida se eliminará de forma permanente ¿Desea continuar?</p>
                        </div>
                        <div class="modal-footer mt-4">
                            <button type="button" id="btn-eliminar-medida-listado" class="btn btn-primary btn-round waves-effect">CONFIRMAR</button>
                            <button type="button" id="btn-cancelar-eliminacion" class="btn btn-danger btn-round waves-effect" data-dismiss="modal">CANCELAR</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal-eliminar-multiples-medidas" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="title">Confirmar acción</h4>
                        </div>
                        <div class="modal-body text-center">
                            <p>Las medidas seleccionadas se eliminarán de forma permanente. ¿Desea continuar?</p>
                        </div>
                        <div class="modal-footer mt-4">
                            <button type="button" id="btn-confirmar-eliminacion-multiples" class="btn btn-primary btn-round waves-effect">CONFIRMAR</button>
                            <button type="button" id="btn-cancelar-eliminacion-multiples" class="btn btn-danger btn-round waves-effect" data-dismiss="modal">CANCELAR</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<script src="<?= base_url('public/js/pages/generate-apc/main_general.js'); ?>"></script>