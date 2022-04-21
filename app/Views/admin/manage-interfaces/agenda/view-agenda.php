<section class="content">
    <div class="body_scroll">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <h2>Agenda de Prevención Comunitaria</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/gestion/listar'); ?>"><i class="zmdi zmdi-widgets"></i> Gestionar interfaces</a></li>
                        <li class="breadcrumb-item active">Agenda de Prevención Comunitaria</li>
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
                            <div class="row clearfix">
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="alert bg-black text-center"><h6><?= $interfaz['nombre']; ?></h6></div>
                                    <input type="hidden" id="nombre-interfaz" value="<?= $interfaz['nombre']; ?>">
                                </div>
                                <div class="col-md-3 mt-2 text-center">
                                    <button onclick="exportarPDF()" class="btn btn-warning btn-icon waves-effect waves-float waves-yellow" type="button" title="Exportar a PDF"><i class="zmdi zmdi-cloud-download"></i></button> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $countNewLine = [];             
                    if (sizeof($grupoCausas) > 0) {
                        foreach ($grupoCausas as $grupo) {
                            foreach ($medidasGrupos as $dataMedida) {
                                foreach ($dataMedida as $medida) {
                                    if ($medida['grupo'] == $grupo['grupo_especifica']) {
                                        if (strlen($medida['nombre'].' a '.$medida['grupo_objetivo'].' en '.$medida['zonas_objetivo']) > 60) {
                                            $medidaToPrint = wordwrap($medida['nombre'].' a '.$medida['grupo_objetivo'].' en '.$medida['zonas_objetivo'], 60, '\n');
                                            array_push($countNewLine, substr_count($medidaToPrint, '\n'));
                                        } else array_push($countNewLine, 0);
                                    }
                                }
                                foreach ($dataMedida as $medida) {
                                    if ($medida['grupo'] == $grupo['grupo_especifica']) {
                                        if (strlen($medida['responsable']) > 60) {
                                            $responsable = wordwrap($medida['responsable'], 60, '\n');
                                            array_push($countNewLine, substr_count($responsable, '\n'));
                                        } else array_push($countNewLine, 0);
                                    }
                                }
                                foreach ($dataMedida as $medida) {
                                    if ($medida['grupo'] == $grupo['grupo_especifica']) {
                                        if (strlen($medida['contacto_responsable']) > 60) {
                                            $contacto = wordwrap($medida['contacto_responsable'], 60, '\n');
                                            array_push($countNewLine, substr_count($contacto, '\n'));
                                        } else array_push($countNewLine, 0);
                                    }
                                }
                            }
                        }
                        $maxLines = max($countNewLine);
                    } else $maxLines = 0;

                    function medidaCompleta($datoMedida, $maxlines) {
                        if (strlen($datoMedida) > 60) {
                            $datoMedida = wordwrap($datoMedida, 60, '\n');
                            $lines = substr_count($datoMedida, '\n');
                            $br = $maxlines - $lines;
                            for ($i = 0; $i < $br; $i++) { 
                                $datoMedida = $datoMedida.'\n';
                            }
                            echo str_replace('\n', '<br>', $datoMedida).'<br><hr>';
                        } else {
                            for ($i = 0; $i < $maxlines; $i++) { 
                                $datoMedida = $datoMedida.'<br>';
                            }
                            echo $datoMedida.'<br><hr>';
                        }
                    }
                    ?>
                    <div class="card">
                        <div class="body">
                            <div class="row clearfix mt-2">
                                <div class="col-lg-12">
                                    <a href="<?= site_url('admin/gestion/apcespecifica/listar/interfaz/'.$interfaz['interfaz_id']); ?>" class="btn btn-warning waves-effect waves-float float-right btn-sm waves-yellow" title="Editar medidas específicas"><i class="zmdi zmdi-edit"></i></a>
                                    <h6>Medidas específicas</h6>
                                    <div class="table-responsive">
                                        <table class="table table-hover product_item_list c_table theme-color mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="bg-green">Grupo</th>
                                                    <th class="bg-green">Medida</th>
                                                    <th class="bg-green">Responsables</th>
                                                    <th class="bg-green">Contacto</th>
                                                    <th class="bg-green">Plazos</th>
                                                    <th class="bg-green">Avance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($grupoCausas as $grupo) {
                                                ?>
                                                <tr style="outline: thin solid">
                                                    <td>
                                                        <?= $grupo['grupo_especifica']; ?>
                                                    </td>
                                                    <td>
                                                        
                                                        <?php
                                                        foreach ($medidasGrupos as $dataMedida) {
                                                            foreach ($dataMedida as $medida) {
                                                                if ($medida['grupo'] == $grupo['grupo_especifica']) {
                                                                    if ($medida['grupo_objetivo'] != '' && $medida['zonas_objetivo'] != '') {
                                                                        $medidaObjetivos = $medida['nombre'].' a '.$medida['grupo_objetivo'].' en '.$medida['zonas_objetivo'];
                                                                        medidaCompleta($medidaObjetivos, $maxLines);
                                                                    } else {
                                                                        for ($i = 0; $i < $maxLines; $i++) { 
                                                                            $medida['nombre'] = $medida['nombre'].'<br>';
                                                                        }
                                                                        echo $medida['nombre'].'<br><hr>';
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        foreach ($medidasGrupos as $dataMedida) {
                                                            foreach ($dataMedida as $medida) {
                                                                if ($medida['grupo'] == $grupo['grupo_especifica']) {
                                                                    if ($medida['responsable'] != '') medidaCompleta($medida['responsable'], $maxLines);
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        foreach ($medidasGrupos as $dataMedida) {
                                                            foreach ($dataMedida as $medida) {
                                                                if ($medida['grupo'] == $grupo['grupo_especifica']) {
                                                                    if ($medida['contacto_responsable'] != '') medidaCompleta($medida['contacto_responsable'], $maxLines);
                                                                    else {
                                                                        for ($i = 0; $i < $maxLines; $i++) { 
                                                                            $medida['contacto_responsable'] = $medida['contacto_responsable'].'<br>';
                                                                        }
                                                                        echo $medida['contacto_responsable'].'<br><hr>';
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        foreach ($medidasGrupos as $dataMedida) {
                                                            foreach ($dataMedida as $medida) {
                                                                if ($medida['grupo'] == $grupo['grupo_especifica']) {
                                                                    if ($medida['fecha_inicio'] != '' && $medida['fecha_termino'] != '') medidaCompleta(date('d/m/Y', strtotime($medida['fecha_inicio'])).' - '.date('d/m/Y', strtotime($medida['fecha_termino'])), $maxLines);
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        foreach ($medidasGrupos as $dataMedida) {
                                                            foreach ($dataMedida as $medida) {
                                                                if ($medida['grupo'] == $grupo['grupo_especifica']) {
                                                                    if ($medida['avance'] != '') medidaCompleta($medida['avance'], $maxLines);
                                                                }
                                                            }
                                                        }
                                                        ?>
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
                    
                    <div class="card">
                        <div class="body">
                            <div class="row clearfix mt-2">
                                <div class="col-lg-12">
                                    <a href="<?= site_url('admin/gestion/apcgeneral/listar/interfaz/'.$interfaz['interfaz_id']); ?>" class="btn btn-warning waves-effect waves-float float-right btn-sm waves-yellow" title="Editar medidas generales"><i class="zmdi zmdi-edit"></i></a>
                                    <h6>Medidas generales</h6>
                                    <div class="table-responsive">
                                        <table class="table table-hover product_item_list c_table theme-color mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="bg-green">Medida</th>
                                                    <th class="bg-green">Responsables</th>
                                                    <th class="bg-green">Contacto</th>
                                                    <th class="bg-green">Plazos</th>
                                                    <th class="bg-green">Avance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($medidasGenerales as $medida) {
                                                ?>
                                                <tr style="outline: thin solid">
                                                    <td>
                                                        <?php
                                                        if ($medida['nombre'] == 'Construcción de cortafuegos' || $medida['nombre'] == 'Mantención de cortafuegos') {
                                                            if ($medida['zonas_objetivo'] == '') {
                                                                $printMedida = $medida['nombre'];
                                                                if (strlen($printMedida) > 40) echo wordwrap($printMedida, 40, '<br>');
                                                                else echo $printMedida;
                                                            } else {
                                                                $printMedida = str_replace('cortafuegos', $medida['zonas_objetivo'], $medida['nombre']);
                                                                if (strlen($printMedida) > 40) echo wordwrap($printMedida, 40, '<br>');
                                                                else echo $printMedida;
                                                            }
                                                        } else {
                                                            if ($medida['objetivo'] != '' && $medida['zonas_objetivo'] == '') {
                                                                $printMedida = $medida['nombre'].' '.$medida['objetivo'];
                                                                if (strlen($printMedida) > 40) echo wordwrap($printMedida, 40, '<br>');
                                                                else echo $printMedida;
                                                            } else if ($medida['objetivo'] == '' && $medida['zonas_objetivo'] != '') {
                                                                $printMedida = $medida['nombre'].' en '.$medida['zonas_objetivo'];
                                                                if (strlen($printMedida) > 40) echo wordwrap($printMedida, 40, '<br>');
                                                                else echo $printMedida;
                                                            } else if ($medida['objetivo'] == '' && $medida['zonas_objetivo'] == '') {
                                                                $printMedida = $medida['nombre'];
                                                                if (strlen($printMedida) > 40) echo wordwrap($printMedida, 40, '<br>');
                                                                else echo $printMedida;
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if (strlen($medida['responsable']) > 40) echo wordwrap($medida['responsable'], 40, '<br>');
                                                        else echo $medida['responsable'];
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if (strlen($medida['contacto_responsable']) > 40) echo wordwrap($medida['contacto_responsable'], 40, '<br>');
                                                        else echo $medida['contacto_responsable'];
                                                        ?>
                                                    </td>
                                                    <td><?php echo date('d/m/Y', strtotime($medida['fecha_inicio'])).' - '.date('d/m/Y', strtotime($medida['fecha_termino'])); ?></td>
                                                    <td><?= $medida['avance']; ?></td>
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

                    <div class="card" style="display:none;">
                        <div class="header" id="pdf-header">
                            <div class="alert bg-green text-center">
                                <h6><?= $interfaz['nombre']; ?></h6>
                            </div>
                            <hr>
                        </div>
                        <div class="body" id="pdf-body" style="page-break-inside:avoid; font-size:10pt; font-family:Arial;">
                            <div class="text-center" style="page-break-inside:avoid;">
                                <span class="badge badge-success" style="font-size:11pt;">Medidas específicas</span>
                            </div>
                            <?php
                            foreach ($grupoCausas as $grupo) {
                            ?>
                                <span class="badge badge-primary" style="font-size:10pt;"><?= $grupo['grupo_especifica']; ?></span><br>
                                <?php
                                foreach ($medidasGrupos as $dataMedida) {
                                    foreach ($dataMedida as $medida) {
                                        if ($medida['grupo'] == $grupo['grupo_especifica']) {
                                            ?>
                                            <span class="badge badge-warning" style="font-size:10pt;"><?= $medida['avance'] ?> %</span>
                                            <?php
                                            if ($medida['grupo_objetivo'] != '' && $medida['zonas_objetivo'] != '') {
                                                $medidaObjetivos = $medida['nombre'].' a '.$medida['grupo_objetivo'].' en '.$medida['zonas_objetivo'];
                                                if ($medida['contacto_responsable'] != '') {
                                                ?>
                                                    <b>Medida: </b> <?= $medidaObjetivos; ?>. <b>Responsables: </b> <?= $medida['responsable'] ?>. <b>Contacto: </b> <?= $medida['contacto_responsable'] ?>. <b>Plazos: </b> <?= date('d/m/Y', strtotime($medida['fecha_inicio'])) ?> - <?= date('d/m/Y', strtotime($medida['fecha_termino'])) ?>.
                                                <?php
                                                } else {
                                                ?>
                                                    <b>Medida: </b> <?= $medidaObjetivos; ?>. <b>Responsables: </b> <?= $medida['responsable'] ?>. <b>Contacto: </b> no indicado. <b>Plazos: </b> <?= date('d/m/Y', strtotime($medida['fecha_inicio'])) ?> - <?= date('d/m/Y', strtotime($medida['fecha_termino'])) ?>.
                                                <?php
                                                }
                                            ?>
                                            <?php
                                            } else {
                                                if ($medida['contacto_responsable'] != '') {
                                                ?>
                                                    <b>Medida: </b><?= $medida['nombre']; ?>. <b>Responsables: </b> <?= $medida['responsable'] ?>. <b>Contacto: </b> <?= $medida['contacto_responsable'] ?>. <b>Plazos: </b> <?= date('d/m/Y', strtotime($medida['fecha_inicio'])) ?> - <?= date('d/m/Y', strtotime($medida['fecha_termino'])) ?>.
                                                <?php
                                                } else {
                                                ?>
                                                    <b>Medida: </b><?= $medida['nombre']; ?>. <b>Responsables: </b> <?= $medida['responsable'] ?>. <b>Contacto: </b> no indicado. <b>Plazos: </b> <?= date('d/m/Y', strtotime($medida['fecha_inicio'])) ?> - <?= date('d/m/Y', strtotime($medida['fecha_termino'])) ?>.
                                                <?php
                                                }
                                            }
                                            ?>
                                            <br>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                                <br>
                            <?php
                            }
                            ?>

                            <hr>

                            <div class="text-center" style="page-break-inside:avoid;">
                                <span class="badge badge-success" style="font-size:11pt;">Medidas generales</span>
                            </div>
                            <?php
                            foreach ($medidasGenerales as $medida) {
                            ?>
                                <span class="badge badge-warning" style="font-size:10pt;"><?= $medida['avance'] ?> %</span>
                            <?php
                                if ($medida['nombre'] == 'Construcción de cortafuegos' || $medida['nombre'] == 'Mantención de cortafuegos') {
                                    if ($medida['zonas_objetivo'] != '' && $medida['contacto_responsable'] != '') {
                                        $printMedida = str_replace('cortafuegos', $medida['zonas_objetivo'], $medida['nombre']);
                                    ?>
                                        <b>Medida: </b> <?= $printMedida ?>. <b>Responsables: </b><?= $medida['responsable'] ?>. <b>Contacto: </b> <?= $medida['contacto_responsable'] ?>. <b>Plazos: </b> <?= date('d/m/Y', strtotime($medida['fecha_inicio'])) ?> - <?= date('d/m/Y', strtotime($medida['fecha_termino'])) ?>.
                                    <?php
                                    } else {                                        
                                    ?>
                                        <b>Medida: </b> <?= $medida['nombre'] ?>. <b>Responsables: </b><?= $medida['responsable'] ?>. <b>Contacto: </b> no indicado. <b>Plazos: </b> <?= date('d/m/Y', strtotime($medida['fecha_inicio'])) ?> - <?= date('d/m/Y', strtotime($medida['fecha_termino'])) ?>.
                                    <?php
                                    }
                                } else if ($medida['nombre'] == 'Generar ordenanza local') {
                                    if ($medida['contacto_responsable'] != '') {
                                    ?>
                                        <b>Medida: </b> <?= $medida['nombre'] ?> <?= $medida['objetivo'] ?>. <b>Responsables: </b><?= $medida['responsable'] ?>. <b>Contacto: </b> <?= $medida['contacto_responsable'] ?>. <b>Plazos: </b> <?= date('d/m/Y', strtotime($medida['fecha_inicio'])) ?> - <?= date('d/m/Y', strtotime($medida['fecha_termino'])) ?>.
                                    <?php
                                    } else {
                                    ?>
                                        <b>Medida: </b> <?= $medida['nombre'] ?> <?= $medida['objetivo'] ?>. <b>Responsables: </b><?= $medida['responsable'] ?>. <b>Contacto: </b> no indicado. <b>Plazos: </b> <?= date('d/m/Y', strtotime($medida['fecha_inicio'])) ?> - <?= date('d/m/Y', strtotime($medida['fecha_termino'])) ?>.
                                    <?php
                                    }
                                } else {
                                    if ($medida['zonas_objetivo'] != '' && $medida['contacto_responsable']) {
                                    ?>
                                        <b>Medida: </b> <?= $medida['nombre'] ?> en <?= $medida['zonas_objetivo'] ?>. <b>Responsables: </b><?= $medida['responsable'] ?>. <b>Contacto: </b> <?= $medida['contacto_responsable'] ?>. <b>Plazos: </b> <?= date('d/m/Y', strtotime($medida['fecha_inicio'])) ?> - <?= date('d/m/Y', strtotime($medida['fecha_termino'])) ?>.
                                    <?php
                                    } else {
                                    ?>
                                        <b>Medida: </b> <?= $medida['nombre'] ?>. <b>Responsables: </b><?= $medida['responsable'] ?>. <b>Contacto: </b> no indicado. <b>Plazos: </b> <?= date('d/m/Y', strtotime($medida['fecha_inicio'])) ?> - <?= date('d/m/Y', strtotime($medida['fecha_termino'])) ?>.
                                    <?php
                                    }
                                }
                            ?>
                            <br><br>
                            <?php
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?= base_url('public/js/pages/manage-interfaces/main_agenda.js'); ?>"></script>