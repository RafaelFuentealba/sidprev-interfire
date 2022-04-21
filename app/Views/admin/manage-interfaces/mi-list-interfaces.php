<section class="content">
    <div class="body_scroll">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <h2>Todas las interfaces</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/gestion/listar'); ?>"><i class="zmdi zmdi-widgets"></i> Gestionar interfaces</a></li>
                        <li class="breadcrumb-item active">Todas las interfaces</li>
                    </ul>
                    <button class="btn btn-primary btn-icon mobile_menu" type="button"><i class="zmdi zmdi-sort-amount-desc"></i></button>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12">                
                    <button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i class="zmdi zmdi-arrow-right"></i></button>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover product_item_list c_table theme-color mb-0">
                                    <thead>
                                        <tr>
                                            <th class="bg-green">Nombre interfaz</th>
                                            <th class="bg-green">Riesgo</th>
                                            <th class="bg-green">Amenaza</th>
                                            <th class="bg-green">Vulnerabilidad</th>
                                            <th class="bg-green">N° Incendios</th>
                                            <th class="bg-green">Daño</th>
                                            <th class="bg-green">Actualizado</th>                                  
                                            <th class="bg-green">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($interfaces as $interfaz) {
                                        ?>
                                        <tr style="outline: thin solid">
                                            <td><?= $interfaz['nombre']; ?></td>
                                            <td><?= $interfaz['indice_riesgo']; ?></td>
                                            <td><?= $interfaz['amenaza']; ?></td>
                                            <td><?= $interfaz['vulnerabilidad']; ?></td>
                                            <td>
                                                <?php
                                                foreach ($cantIncendios as $id => $cantidad) {
                                                    if ($id == $interfaz['interfaz_id']) echo $cantidad;
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                foreach ($supAfectada as $id => $superficie) {
                                                    if ($id == $interfaz['interfaz_id']) echo round($superficie, 2).' ha';
                                                }
                                                ?>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($interfaz['updated_at'])); ?></td>
                                            <td>
                                                <a href="<?= site_url('admin/gestion/riesgo/interfaz/'.$interfaz['interfaz_id']); ?>" class="btn bg-cyan waves-effect waves-float btn-sm waves-cyan" title="Riesgo"><i class="zmdi zmdi-fire"></i></a>
                                                <a href="<?= site_url('admin/gestion/causas/interfaz/'.$interfaz['interfaz_id']); ?>" class="btn bg-orange waves-effect waves-float btn-sm waves-orange" title="Causas"><i class="zmdi zmdi-help-outline"></i></a>
                                                <a href="<?= site_url('admin/gestion/sintesis/interfaz/'.$interfaz['interfaz_id']); ?>" class="btn bg-blue-grey waves-effect waves-float btn-sm waves-blue-grey" title="Síntesis de causas"><i class="zmdi zmdi-chart-donut"></i></a>
                                                <a href="<?= site_url('admin/gestion/apcespecifica/listar/interfaz/'.$interfaz['interfaz_id']); ?>" class="btn bg-green waves-effect waves-float btn-sm waves-green" title="Medidas específicas"><i class="zmdi zmdi-view-list-alt"></i></a>
                                                <a href="<?= site_url('admin/gestion/apcgeneral/listar/interfaz/'.$interfaz['interfaz_id']); ?>" class="btn bg-brown waves-effect waves-float btn-sm waves-brown" title="Medidas generales"><i class="zmdi zmdi-view-agenda"></i></a>
                                                <a href="<?= site_url('admin/gestion/agenda/interfaz/'.$interfaz['interfaz_id']); ?>" class="btn bg-indigo waves-effect waves-float btn-sm waves-indigo" title="Agenda"><i class="zmdi zmdi-assignment"></i></a>
                                                <a href="<?= site_url('admin/gestion/editar/interfaz/'.$interfaz['interfaz_id']); ?>" class="btn btn-warning waves-effect waves-float btn-sm waves-yellow" title="Editar interfaz"><i class="zmdi zmdi-edit"></i></a>
                                                <a href="<?= site_url('admin/gestion/eliminar/interfaz/'.$interfaz['interfaz_id']); ?>" class="btn btn-danger waves-effect waves-float btn-sm waves-red" title="Eliminar interfaz"><i class="zmdi zmdi-delete"></i></a>
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
            <?php
            if (!empty($pager)) {
            ?>
                <div class="row">
                    <div class="col-md-12">
                        <?= $pager->links('default', 'modules_pagination'); ?>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</section>