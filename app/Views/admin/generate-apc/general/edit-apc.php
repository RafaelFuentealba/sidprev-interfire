<section class="content">
    <div class="body_scroll">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <h2>Edición medida</h2>
                    <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('admin/apc/general/listar'); ?>"><i class="zmdi zmdi-assignment-o"></i> Generar APC</a></li>                        
                        <li class="breadcrumb-item">Medidas generales</li>
                        <li class="breadcrumb-item active">Edición medida</li>
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
                            <a href="<?= site_url('admin/apc/general/ver/'.$medida['medida_gene_id']); ?>" class="btn btn-primary waves-effect waves-float waves-blue btn-icon float-right" title="Ver medida"><i class="zmdi zmdi-eye"></i></a>
                            <div class="row clearfix mt-2">
                                <div class="col-md-3"></div>
                                <div class="col-md-7">
                                    <form action="" id="form-actualizar-medida-general">
                                            <div class="form-group">     
                                                <label for="">Nombre</label>                               
                                                <input type="text" id="nombre-medida" class="form-control" value="<?= $medida['nombre']; ?>" disabled />
                                            </div>
                                            <?php
                                            if ($medida['nombre'] == 'Construcción de cortafuegos' || $medida['nombre'] == 'Mantención de cortafuegos') {
                                            ?>
                                            <div class="form-group mt-2">                                   
                                                <label for="">Detalle de la medida</label>
                                                <input type="text" id="zonas-medida" name="zonasMedida" class="form-control" value="<?= $medida['zonas_objetivo']; ?>" placeholder = "Ejemplo: 3 cortafuegos de 500 x 20 en sector Los Aromos" value="<?= $medida['zonas_objetivo']; ?>" />
                                            </div>
                                            <?php
                                            } else {
                                            ?>
                                            <div class="form-group">     
                                                <label for="">Zonas donde se aplicará la medida</label>                               
                                                <input type="text" id="zonas-medida" name="zonasMedida" class="form-control" value="<?= $medida['zonas_objetivo']; ?>" placeholder = "Ejemplo: Sector Espejo, Villa Alegre" value="<?= $medida['zonas_objetivo']; ?>" />
                                            </div>
                                            <?php
                                            }
                                            ?>
                                            <div class="form-group">     
                                                <label for="">Responsable</label>                               
                                                <input type="text" id="responsable-medida" name="responsableMedida" class="form-control" value="<?= $medida['responsable']; ?>" placeholder = "Ejemplo: <?= $medida['responsable']; ?>"  />
                                            </div>
                                            <div class="form-group">     
                                                <label for="">Contacto responsable de la medida</label>                               
                                                <input type="text" id="contacto-medida" name="contactoMedida" class="form-control" value="<?= $medida['contacto_responsable']; ?>" placeholder = "Ejemplo: Mauricio Peña Calderón" />
                                            </div>
                                            <div class="form-group">     
                                                <label for="">Fecha de inicio</label>                               
                                                <input type="date" id="inicio-medida" name="inicioMedida" class="form-control" value="<?= $medida['fecha_inicio']; ?>">
                                            </div>
                                            <div class="form-group">     
                                                <label for="">Fecha de termino</label>                               
                                                <input type="date" id="termino-medida" name="terminoMedida" class="form-control" value="<?= $medida['fecha_termino']; ?>">
                                            </div>
                                            <div class="form-group">     
                                                <label for="">Avance</label>                               
                                                <input type="number" id="avance-medida" name="avanceMedida" class="form-control" value="<?= $medida['avance']; ?>" min="0" max="100" required>
                                            </div>
                                            <div class="text-center mt-2">
                                                <input type="submit" id="btn-actualizar-medida" class="btn btn-success btn-round waves-effect waves-green" value="ACTUALIZAR MEDIDA">
                                            </div>
                                    </form>
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
                            <h4 class="title">Confirmar acción</h4>
                        </div>
                        <div class="modal-body text-center">
                            <p>
                            La siguiente medida se eliminará de forma permanente: <br>
                            <?php
                            if ($medida['nombre'] == 'Construcción de cortafuegos' || $medida['nombre'] == 'Mantención de cortafuegos') {
                                if ($medida['zonas_objetivo'] == '') echo $medida['nombre'];
                                else echo str_replace('cortafuegos', $medida['zonas_objetivo'], $medida['nombre']);
                            } else {
                                if ($medida['objetivo'] != '' && $medida['zonas_objetivo'] == '') echo $medida['nombre'].' '.$medida['objetivo'];
                                else if ($medida['objetivo'] == '' && $medida['zonas_objetivo'] != '') echo $medida['nombre'].' en '.$medida['zonas_objetivo'];
                                else if ($medida['objetivo'] == '' && $medida['zonas_objetivo'] == '') echo $medida['nombre'];
                            }
                            ?>
                            </p>
                            <p>¿Desea continuar?</p>
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