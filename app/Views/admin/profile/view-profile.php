<section class="content">
    <div class="body_scroll">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <h2>Mi perfil</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/perfil'); ?>"><i class="zmdi zmdi-account"></i> Cuenta</a></li>
                        <li class="breadcrumb-item active">Mi perfil</li>
                    </ul>
                    <button class="btn btn-primary btn-icon mobile_menu" type="button"><i class="zmdi zmdi-sort-amount-desc"></i></button>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12">                
                    <button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i class="zmdi zmdi-arrow-right"></i></button>
                    <a href="<?= site_url('admin/perfil/editar'); ?>" class="btn bg-orange btn-icon float-right" title="Editar perfil"><i class="zmdi zmdi-edit"></i></a>
                </div>
            </div>
        </div> 
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-4 col-md-12">
                    <div class="card mcard_3">
                        <div class="body">
                            <img src="<?= base_url('public/uploads/users/'.$userData['img_perfil']); ?>" class="rounded-circle shadow " alt="profile-image">
                            <h4 class="m-t-10"><?= $userData['nombre'].' '.$userData['apellido']; ?></h4>                            
                            <div class="row">
                                <div class="col-12">
                                    <p class="text-muted text-center"><?= $userData['tipo_acceso']; ?></p>
                                </div>                          
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="body">
                            <small class="text-muted">Estado cuenta: </small>
                            <?php
                            if ($userData['deleted'] == 'f') {
                            ?>
                            <span class="badge badge-success">ACTIVO</span>
                            <?php
                            } else {
                            ?>
                            <span class="badge badge-danger">INACTIVO</span>
                            <?php
                            }
                            ?>
                            
                        </div>
                    </div>                
                </div>
                <div class="col-lg-8 col-md-12">
                    <div class="card">
                        <div class="body">
                            <small class="text-muted">Correo electrónico: </small>
                            <p><?= $userData['email']; ?></p>
                            <hr>
                            <small class="text-muted">Teléfono: </small>
                            <p><?= $userData['telefono_contacto']; ?></p>
                            <hr>
                            <small class="text-muted">Organización: </small>
                            <p><?= $userData['nombre_organizacion']; ?></p>
                            <hr>
                            <small class="text-muted">Cargo laboral: </small>
                            <p><?= $userData['nombre_cargo_laboral']; ?></p>
                            <hr>
                            <small class="text-muted">Región: </small>
                            <p><?= $userData['region']; ?></p>
                            <hr>
                            <small class="text-muted">Provincia: </small>
                            <p><?= $userData['provincia']; ?></p>
                            <hr>
                            <small class="text-muted">Comuna: </small>
                            <p><?= $userData['comuna']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>