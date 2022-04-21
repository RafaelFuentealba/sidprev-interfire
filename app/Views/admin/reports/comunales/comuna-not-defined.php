<section class="content">
    <div class="body_scroll">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <h2>Reportes comunales</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/reportes/comunales'); ?>"><i class="zmdi zmdi-print"></i> Reportes</a></li>
                        <li class="breadcrumb-item active">Reportes comunales</li>
                    </ul>
                    <button class="btn btn-primary btn-icon mobile_menu" type="button"><i class="zmdi zmdi-sort-amount-desc"></i></button>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12">                                    
                    <button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i class="zmdi zmdi-arrow-right"></i></button>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="alert alert-danger text-center">Para obtener estos reportes debes completar tu perfil de usuario</div>
            <div class="text-center">
                <a href="<?= site_url('admin/perfil/editar'); ?>" class="btn btn-success btn-icon waves-effect waves-float waves-green" type="button" title="Ir a mi perfil"><i class="zmdi zmdi-account"></i></a>
            </div>
        </div>
    </div>
</section>