<section class="content">
    <div class="body_scroll">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <h2>Edición de perfil</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/perfil'); ?>"><i class="zmdi zmdi-account"></i> Cuenta</a></li>
                        <li class="breadcrumb-item">Mi perfil</li>
                        <li class="breadcrumb-item active">Edición de perfil</li>
                    </ul>
                    <button class="btn btn-primary btn-icon mobile_menu" type="button"><i class="zmdi zmdi-sort-amount-desc"></i></button>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12">                
                    <button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i class="zmdi zmdi-arrow-right"></i></button>
                    <a href="<?= site_url('admin/perfil'); ?>" class="btn btn-success btn-icon waves-effect float-right waves-float waves-green" type="button" title="Ir a mi perfil"><i class="zmdi zmdi-account"></i></a>
                </div>
            </div>
        </div> 
        <div class="container-fluid">
            <div class="row clearfix">
            <div class="col-lg-12">
                    <div class="card">
                        <div class="body">
                            <ul class="nav nav-tabs nav-tabs-warning">
                                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#basico">Básico</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#seguridad">Seguridad</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card">
                        <div class="body">                            
                            <div class="tab-content">
                                <div class="tab-pane active" id="basico">
                                    <div class="row clearfix">
                                        <div class="card">
                                            <form id="form-save-profile" autocomplete="off">
                                                <div class="body">
                                                    <div class="row clearfix">
                                                        <div class="col-lg-6 col-md-12">
                                                            <div class="form-group">
                                                                <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre" value="<?= $userData['nombre']; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-12">
                                                            <div class="form-group">
                                                                <input type="text" id="apellido" name="apellido" class="form-control" placeholder="Apellido" value="<?= $userData['apellido']; ?>">
                                                            </div>
                                                        </div> 
                                                        <div class="col-lg-4 col-md-12">
                                                            <div class="form-group">
                                                                <input type="text" id="contacto" name="contacto" class="form-control" placeholder="Teléfono de contacto" value="<?= $userData['telefono_contacto']; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-12">
                                                            <div class="form-group">
                                                                <input type="text" id="nombre-organizacion" name="organizacion" class="form-control" placeholder="Organización" value="<?= $userData['nombre_organizacion']; ?>">
                                                            </div>
                                                        </div> 
                                                        <div class="col-lg-4 col-md-12">
                                                            <div class="form-group">
                                                                <input type="text" id="cargo-laboral" name="cargoLaboral" class="form-control" placeholder="Cargo laboral" value="<?= $userData['nombre_cargo_laboral']; ?>">
                                                            </div>
                                                        </div>                                   
                                                        <div class="col-lg-4 col-md-12">
                                                            <div class="form-group">
                                                                <select id="region" name="region" class="form-control select2" style="width: 100%;">
                                                                    <option value="" selected="true" disabled>-- Seleccionar región --</option>
                                                                    <?php
                                                                    foreach ($regiones as $region) {
                                                                    ?>
                                                                    <option value="<?= $region ?>"><?= $region ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <span class="badge badge-success"><?php if (!empty($userData['region'])) echo 'Actual: '.$userData['region']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-12">
                                                            <div class="form-group">
                                                                <select id="provincia" name="provincia" class="form-control select2" style="width: 100%;">
                                                                    <option value="" selected="true" disabled>-- Seleccionar provincia --</option>
                                                                </select>
                                                                <span class="badge badge-success"><?php if (!empty($userData['provincia'])) echo 'Actual: '.$userData['provincia']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-12">
                                                            <div class="form-group">
                                                                <select id="comuna" name="comuna" class="form-control select2" style="width: 100%;">
                                                                    <option value="" selected="true" disabled>-- Seleccionar comuna --</option>
                                                                </select>
                                                                <span class="badge badge-success"><?php if (!empty($userData['comuna'])) echo 'Actual: '.$userData['comuna']; ?></span>
                                                            </div>
                                                        </div>
                                                        <!--<div class="col-lg-3 col-md-12"></div>
                                                        <div class="col-lg-6 col-md-12 mt-2">
                                                            <div class="card">
                                                                <div class="header">
                                                                    <h2>Foto de <strong style="color: #ff7300;"> Perfil</strong></h2>
                                                                </div>
                                                                <div class="body">
                                                                    <input type="file" id="foto-perfil" name="fotoPerfil" class="dropify" data-allowed-file-extensions="jpg png" data-max-file-size="250K">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-12"></div>-->
                                                        <div class="col-md-12">
                                                            <button id="btn-save-profile" class="btn btn-primary">Guardar cambios</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="seguridad">
                                    <div class="row clearfix">
                                            <div class="card">
                                                <form id="form-save-profile-security" autocomplete="off">
                                                    <div class="body">
                                                        <div id="fail-change-password" class="alert alert-danger text-center" style="display: none;"></div>
                                                        <div class="row">
                                                            <div class="col-lg-4 col-md-12">
                                                                <div class="form-group">
                                                                    <input type="email" id="usuario-email" name="usuarioEmail" class="form-control" placeholder="Email" value="<?= $userData['email'] ?>" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4 col-md-12">
                                                                <div class="form-group">
                                                                    <input type="password" id="usuario-password" name="usuarioPassword" class="form-control" placeholder="Contraseña actual" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,25}$" title="Debe contener al menos una letra mayúscula, una letra minúscula, un número, un carácter especial y contener entre 8 y 25 caracteres">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4 col-md-12">
                                                                <div class="form-group">
                                                                    <input type="password" id="usuario-new-password" name="usuarioNewPassword" class="form-control" placeholder="Nueva contraseña" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,25}$" title="Debe contener al menos una letra mayúscula, una letra minúscula, un número, un carácter especial y contener entre 8 y 25 caracteres">
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <button id="btn-save-profile-security" class="btn btn-primary">Guardar cambios</button>
                                                            </div>                                
                                                        </div>                              
                                                    </div>
                                                </form>
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
</section>

<script src="<?= base_url('public/js/pages/profile/edit_profile.js'); ?>"></script>