<section class="content">
    <div class="body_scroll">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <h2>Eliminar interfaz</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/gestion/listar'); ?>"><i class="zmdi zmdi-widgets"></i> Gestionar interfaces</a></li>
                        <li class="breadcrumb-item active">Eliminar interfaz</li>
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
                            <div class="header">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3"></div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="alert alert-warning text-center"><h6>Confirmar eliminación</h6></div>
                                        <input type="hidden" id="id-delete-interfaz" value="<?= $interfaz['interfaz_id']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="body">
                                <p>
                                    Toda la información relacionada con la interfaz "<b><?= $interfaz['nombre']; ?></b>" será eliminada de forma permanente del sistema. El riesgo, el análisis
                                    de causas y la Agenda de Prevención Comunitaria se perderá de nuestros registros. ¿Desea continuar con esta acción? 
                                </p>
                                <div class="text-center">
                                    <input type="button" id="btn-eliminar-interfaz" class="btn btn-primary btn-round waves-effect waves-float waves-blue" value="ELIMINAR INTERFAZ">
                                    <a href="<?= site_url('admin/gestion/listar'); ?>" class="btn btn-danger btn-round waves-effect waves-float waves-red">CANCELAR</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?= base_url('public/js/pages/manage-interfaces/delete_interfaz.js'); ?>"></script>