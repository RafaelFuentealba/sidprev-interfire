<div class="authentication">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-sm-12">
                <form class="card auth_form" action="<?= site_url('auth/clave/recuperar'); ?>" method="POST" autocomplete="off">

                    <?= csrf_field(); ?>

                    <div class="header">
                        <img class="logo" src="<?= base_url('public/images/logo-interfire.png'); ?>" alt="" style="width:90%;">
                        <h5 class="mt-4">¿Olvidaste tu contraseña?</h5>
                        <span>Sigue las instrucciones para restablecer la contraseña.</span>
                    </div>
                    <?php
                    if (!empty(session()->getFlashdata('auth-forgot-password'))) :
                    ?>
                        <div class="alert alert-danger text-center"><?= session()->getFlashdata('auth-forgot-password'); ?></div>
                    <?php
                    endif
                    ?>

                    <?php
                    if (!empty(session()->getFlashdata('auth-forgot-password-email'))) :
                    ?>
                        <div class="alert alert-success text-center"><?= session()->getFlashdata('auth-forgot-password-email'); ?></div>
                    <?php
                    endif
                    ?>
                    <div class="body">
                        <?php
                        if (!empty(session()->getFlashdata('auth-forgot-password-email'))) {
                        ?>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" name="email" placeholder="Correo electrónico">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="zmdi zmdi-email"></i></span>
                            </div>
                        </div>
                        <?php  
                        } else {
                        ?>
                        <span class="text-danger"><?= isset($validation) ? display_error($validation, 'email') : '' ?></span>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" name="email" placeholder="Correo electrónico" value="<?= set_value('email'); ?>">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="zmdi zmdi-email"></i></span>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                        
                        <span class="text-danger"><?= isset($validation) ? display_error($validation, 'password') : '' ?></span>
                        <input type="submit" class="btn btn-primary btn-block waves-effect waves-light" value="ENVIAR">
                        <div class="signin_with mt-3">
                            <a href="<?= site_url('auth/ingresar'); ?>" class="link">Iniciar sesión</a>
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