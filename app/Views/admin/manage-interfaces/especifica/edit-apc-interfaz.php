<section class="content">
    <div class="body_scroll">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <h2>Edición grupo</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/gestion/listar'); ?>"><i class="zmdi zmdi-widgets"></i> Gestionar interfaces</a></li>
                        <li class="breadcrumb-item">APC</li>
                        <li class="breadcrumb-item">Medidas específicas</li>
                        <li class="breadcrumb-item active">Edición grupo</li>
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
                                    <input type="hidden" id="id-apc-especifica" value="<?= $apc['apc_espe_id']; ?>">
                                    <input type="hidden" id="id-apc-interfaz" value="<?= $apc['interfaz']; ?>">
                                </div>
                                <div class="col-md-3">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="body">
                            <div class="row clearfix mt-2">
                                <div class="col-lg-12">
                                    <button id="btn-delete-multiples-medidas" class="btn btn-danger waves-effect waves-float waves-red btn-icon float-right" type="button" title="Eliminar medidas seleccionadas"><i class="zmdi zmdi-delete"></i></button>
                                    <a href="<?= site_url('admin/gestion/apcespecifica/ver/'.$apc['apc_espe_id'].'/interfaz/'.$apc['interfaz']); ?>" class="btn btn-primary waves-effect waves-float waves-blue btn-icon float-right" title="Ver medidas"><i class="zmdi zmdi-eye"></i></a>
                                    <button id="btn-agregar-medida" class="btn btn-success waves-effect waves-float waves-green btn-icon float-right" type="button" title="Agregar medida"><i class="zmdi zmdi-plus"></i></button>                                
                                    <div class="table-responsive">
                                        <div id="fail-updateMeasure" class="alert alert-danger text-center" style="display: none;">
                                            Error al actualizar la medida. Por favor, asegúrate de completar los datos correctamente. <a href="javascript:void(0);" id="close-fail-updateMeasure" class="float-right"><i class="zmdi zmdi-close" title="Cerrar"></i></a>
                                        </div>
                                        <div class="card">
                                            <table id="mainTable" class="table table-hover product_item_list c_table theme-color mb-0">
                                                <thead>
                                                    <tr>
                                                        <th class="bg-green"></th>
                                                        <th class="bg-green">Medida</th>
                                                        <th class="bg-green">Grupos objetivo</th>
                                                        <th class="bg-green">Zonas objetivo</th>
                                                        <th class="bg-green">Responsable</th>
                                                        <th class="bg-green">Contacto</th>
                                                        <th class="bg-green">Fecha de inicio</th>
                                                        <th class="bg-green">Fecha de termino</th>
                                                        <th class="bg-green">Avance</th>
                                                        <th class="bg-green">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($medidas as $medida) {
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <input name="check-medida" type="checkbox" class="checkbox mt-0" value="<?= $medida['medida_espe_id']; ?>">
                                                        </td>
                                                        <form action="" id="<?= $medida['medida_espe_id']; ?>">
                                                            <td>
                                                                <?= $medida['nombre']; ?>
                                                                <input type="hidden" id="measure-id<?= $medida['medida_espe_id']; ?>" value="<?= $medida['medida_espe_id']; ?>">
                                                            </td>
                                                            <td>
                                                                <input type="text" id="objetive-group<?= $medida['medida_espe_id']; ?>" class="form-control" value="<?= $medida['grupo_objetivo']; ?>" required style="width: 250px;">
                                                            </td>
                                                            <td>
                                                                <input type="text" id="objetive-zones<?= $medida['medida_espe_id']; ?>" class="form-control" value="<?= $medida['zonas_objetivo']; ?>" required style="width: 250px;">
                                                            </td>
                                                            <td>
                                                                <input type="text" id="responsible-entity<?= $medida['medida_espe_id']; ?>" class="form-control" value="<?= $medida['responsable']; ?>" required style="width: 250px;" />
                                                            </td>
                                                            <td>
                                                                <input type="text" id="responsible-name<?= $medida['medida_espe_id']; ?>" class="form-control" value="<?= $medida['contacto_responsable']; ?>" required style="width: 250px;">
                                                            </td>
                                                            <td>
                                                                <input type="date" id="start-date<?= $medida['medida_espe_id']; ?>" class="form-control" value="<?= $medida['fecha_inicio']; ?>" required>
                                                            </td>
                                                            <td>
                                                                <input type="date" id="end-date<?= $medida['medida_espe_id']; ?>" class="form-control" value="<?= $medida['fecha_termino']; ?>" required>
                                                            </td>
                                                            <td>
                                                                <input type="number" id="progress-level<?= $medida['medida_espe_id']; ?>" class="form-control text-center" value="<?= $medida['avance']; ?>" min="0" max="100" required style="width:80px;">
                                                            </td>
                                                            <td>
                                                                <button onclick="updateMeasure(this)" value="<?= $medida['medida_espe_id']; ?>" class="btn btn-primary waves-effect waves-float btn-sm waves-blue" title="Guardar cambios"><i class="zmdi zmdi-cloud-done"></i></button>
                                                                <button onclick="deleteMeasure(this)" value="<?= $medida['medida_espe_id']; ?>" class="btn btn-danger waves-effect waves-float btn-sm waves-red" title="Eliminar medida"><i class="zmdi zmdi-delete"></i></button>
                                                            </td>
                                                        </form>
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
            </div>

            <form action="" id="addMeasureForm">
                <div class="modal fade" id="addMeasureModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="title" id="largeModalLabel">Agregar medida</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="card">
                                            <div class="body">
                                                <div id="fail-addMeasureForm" class="alert alert-danger text-center" style="display: none;">
                                                    Debes completar toda la información solicitada <a href="javascript:void(0);" id="close-fail-addMeasureForm" class="float-right"><i class="zmdi zmdi-close" title="Cerrar"></i></a>
                                                </div>
                                                <div id="wizard_vertical">
                                                    <h2>Medida</h2>
                                                    <section>
                                                        <div class="row clearfix">
                                                            <div class="col-sm-12">
                                                                <label for="">Seleccionar medida</label>
                                                                <?php
                                                                ?>
                                                                <select id="sel-medida" name="selMeasure" class="form-control select2" style="width: 100%;">
                                                                    <option value="" selected="true" disabled>-- Seleccionar --</option>
                                                                    <?php
                                                                    foreach ($medidasGrupo as $medida) {
                                                                    ?>
                                                                    <option value="<?= $medida['nombre']; ?>"><?= $medida['nombre']; ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </section>
                                                    <h2>Grupos y Zonas</h2>
                                                    <section>
                                                        <div class="row clearfix">
                                                            <div class="col-sm-12">
                                                                <label for="">Indicar grupos a quiénes se aplicará la medida</label>                                   
                                                                <input type="text" id="objective-group" name="objectiveGroup" class="form-control" value="" placeholder="Ejemplo: Agricultores, Viajeros" />
                                                            </div>
                                                            <div class="col-sm-12 mt-4">
                                                                <label for="">Indicar zonas donde se aplicará la medida</label>                                   
                                                                <input type="text" id="objective-zones" name="objectiveZones" class="form-control" value="" placeholder="Ejemplo: Sector Espejo, Villa Alegre" />
                                                            </div>
                                                        </div>
                                                    </section>
                                                    <h2>Responsable</h2>
                                                    <section>
                                                        <div class="row clearfix">
                                                            <div class="col-sm-12">
                                                                <label for="">Responsables</label>
                                                                <?php
                                                                foreach ($medidasGrupo as $medida) {
                                                                ?>
                                                                <input type="text" id="add-responsible-entity-<?= $medida['nombre'] ?>" name="responsibleEntity" class="form-control" value="<?= $medida['responsable']; ?>"/>
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
                                                                    <input type="text" id="responsible-name" name="responsibleName" class="form-control" value="" placeholder="Ejemplo: Mauricio Peña Calderón" />
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
                                                                    <input type="date" id="start-date" name="startDate" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mt-4">
                                                                <label>Fecha de termino para la medida</label>
                                                                <div class="input-group masked-input">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="zmdi zmdi-calendar"></i></span>
                                                                    </div>
                                                                    <input type="date" id="end-date" name="endDate" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </section>
                                                    <h2>Avances</h2>
                                                    <section>
                                                        <div class="row clearfix">
                                                            <div class="col-md-12">
                                                                <label for="">Porcentaje de avance de la medida</label>
                                                                <input type="number" id="progress-level" name="progressLevel" class="form-control text-center" value="0" min="0" max="100">
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
                                <input type="submit" id="btn-measure-save" class="btn btn-primary btn-round waves-effect" value="GUARDAR MEDIDA">
                                <button type="button" id="btn-measure-modal-close" class="btn btn-danger btn-round waves-effect" data-dismiss="modal">CERRAR</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="modal fade" id="deleteMeasureModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="title">Confirmar acción</h4>
                        </div>
                        <div class="modal-body text-center">
                            Las medida se eliminará de forma permanente. ¿Desea continuar?
                            <input type="hidden" id="input-medida-to-delete" value="">
                        </div>
                        <div class="modal-footer mt-4">
                            <button type="button" id="btn-measure-delete" class="btn btn-primary btn-round waves-effect">CONFIRMAR</button>
                            <button type="button" id="btn-measure-delete-close" class="btn btn-danger btn-round waves-effect" data-dismiss="modal">CANCELAR</button>
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

<script src="<?= base_url('public/js/pages/manage-interfaces/main_especifica.js'); ?>"></script>