<div class="authentication">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-sm-12">
                <form class="card auth_form" action="<?= site_url('auth/ingresar'); ?>" method="POST" autocomplete="off">

                    <?= csrf_field(); ?>

                    <div class="header">
                        <img class="logo" src="<?= base_url('public/images/logo-interfire.png'); ?>" alt="" style="width:90%;">
                        <h5 class="mt-4">Iniciar sesión</h5>
                    </div>

                    <?php
                    if (!empty(session()->getFlashdata('auth-success'))) :
                    ?>
                        <div class="alert alert-success text-center"><?= session()->getFlashdata('auth-success'); ?></div>
                    <?php
                    endif
                    ?>

                    <?php
                    if (!empty(session()->getFlashdata('auth-fail'))) :
                    ?>
                        <div class="alert alert-danger text-center"><?= session()->getFlashdata('auth-fail'); ?></div>
                    <?php
                    endif
                    ?>

                    <?php
                    if (!empty(session()->getFlashdata('success-password-update'))) :
                    ?>
                        <div class="alert alert-success text-center"><?= session()->getFlashdata('success-password-update'); ?></div>
                    <?php
                    endif
                    ?>

                    <div class="body">
                        <span class="text-danger"><?= isset($validation) ? display_error($validation, 'email') : '' ?></span>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" name="email" placeholder="Correo electrónico" value="<?= set_value('email'); ?>">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="zmdi zmdi-email"></i></span>
                            </div>
                        </div>
                        <span class="text-danger"><?= isset($validation) ? display_error($validation, 'password') : '' ?></span>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="password" placeholder="Contraseña">
                            <div class="input-group-append">                                
                                <span class="input-group-text"><a href="<?= site_url('auth/clave/recuperar'); ?>" class="forgot" title="Olvidé mi contraseña"><i class="zmdi zmdi-lock"></i></a></span>
                            </div>                            
                        </div>
                        <input type="submit" class="btn btn-primary btn-block waves-effect waves-light" value="INGRESAR">
                        <div class="text-center">
                            <a href="<?= site_url('auth/registro'); ?>">¿No tienes una cuenta?</a> / <a href="<?= site_url('auth/clave/recuperar'); ?>">¿Olvidaste tu contraseña?</a>
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
                <div class="card">
                    <div class="body text-center">
                        <img src="<?= base_url('public/images/servicio-01.svg'); ?>" style="border-radius:50px;" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>