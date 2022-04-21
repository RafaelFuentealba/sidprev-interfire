<div class="authentication">    
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-sm-12">
                <form class="card auth_form" action="<?= site_url('auth/clave/actualizar?id='.$user['usuario_id'].'&token='.$user['clave']); ?>" method="POST" autocomplete="off">
                    <div class="header">
                        <img class="logo" src="<?= base_url('public/images/logo-interfire.png'); ?>" alt="" style="width:90%;">
                        <h5 class="mt-4">Cambiar contraseña</h5>
                    </div>
                    <?php
                    if (!empty(session()->getFlashdata('fail-password-update'))) :
                    ?>
                        <div class="alert alert-danger text-center"><?= session()->getFlashdata('fail-password-update'); ?></div>
                    <?php
                    endif
                    ?>
                    <div class="body">
                        <?php
                        if (!empty(session()->getFlashdata('fail-password-update'))) {
                        ?>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="password" placeholder="Nueva contraseña" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,25}$" title="Debe contener al menos una letra mayúscula, una letra minúscula, un número, un carácter especial y contener entre 8 y 25 caracteres">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="zmdi zmdi-lock"></i></span>
                            </div>
                        </div>
                        <?php
                        } else {
                        ?>
                        <span class="text-danger"><?= isset($validation) ? display_error($validation, 'password') : '' ?></span>  
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="password" placeholder="Nueva contraseña" value="<?= set_value('password'); ?>" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,25}$" title="Debe contener al menos una letra mayúscula, una letra minúscula, un número, un carácter especial y contener entre 8 y 25 caracteres">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="zmdi zmdi-lock"></i></span>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                        
                        
                        <span class="text-danger"><?= isset($validation) ? display_error($validation, 'cpassword') : '' ?></span>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="cpassword" placeholder="Repetir nueva contraseña" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,25}$" title="Debe contener al menos una letra mayúscula, una letra minúscula, un número, un carácter especial y contener entre 8 y 25 caracteres">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="zmdi zmdi-lock"></i></span>
                            </div>
                        </div>
                        <input type="submit" class="btn btn-primary btn-block waves-effect waves-light" value="GUARDAR CAMBIOS">
                        <div class="signin_with mt-3">
                            <a class="link" href="<?= site_url('/'); ?>">Iniciar sesión</a>
                        </div>
                    </div>
                </form>
                <div class="copyright text-center">
                    &copy;
                    <script>document.write(new Date().getFullYear())</script>,
                    <span><a href="https://interfire.cl/" target="_blank">Interfire SpA</a></span>
                </div>
            </div>
            <div class="col-lg-8 col-sm-12">
                <div class="card text-center">
                    <img src="<?= base_url('public/images/servicio-02.svg'); ?>" style="border-radius:10px;" />
                </div>
            </div>
        </div>
    </div>
</div>