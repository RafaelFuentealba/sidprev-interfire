$(document).ready(function() {
    $('#grupo-causa-especifica').on('change', function() {
        let grupoEspecifica = $(this).val();
        $('[name=data-causas]').hide();
        $('#data-causas-' + grupoEspecifica).show();
    });
});

function updateMeasure(id) {
    idButtonSave = id.getAttribute('value');
    formSave = $('form[id="'+idButtonSave+'"]');
    let measureID = $('#measure-id'+idButtonSave).val();
    let grupo = $('#objetive-group'+idButtonSave).val();
    let zones = $('#objetive-zones'+idButtonSave).val();
    let responsibleEntity = $('#responsible-entity'+idButtonSave).val();
	let responsibleName = $('#responsible-name'+idButtonSave).val();
    let startDate = $('#start-date'+idButtonSave).val();
	let endDate = $('#end-date'+idButtonSave).val();
	let progressLevel = $('#progress-level'+idButtonSave).val();

    if ((grupo != '') && (zones != '') && (responsibleEntity != '') && (responsibleName != '') && (startDate != '') && (endDate != '') && ((progressLevel >= 0) && (progressLevel <= 100))) {
        $('#fail-updateMeasure').css({ 'display':'none' });
        $.ajax({
            url: window.location.origin + '/admin/apc/especifica/causa/actualizar', 
            type: 'POST',             
            data: {
                'medida-id': measureID,
                'medida-grupo-objetivo': grupo,
                'medida-zonas-objetivo': zones,
                'medida-responsable': responsibleEntity,
                'medida-contacto-responsable': responsibleName,
                'medida-fecha-inicio': startDate,
                'medida-fecha-termino': endDate,
                'medida-avance': progressLevel
            },
            success: function(response) {
                if (response == 1) {
                    swal({
                        title: "¡Excelente!",
                        text: "La información de la medida ha sido actualizada correctamente.",								
                        buttons: {
                            confirm: true,
                        }
                    }).then(function() {
                        location.reload();
                    });		
                }
                else {
                    swal({
                        title: "¡Algo salió mal!",
                        text: "No se pudo actualizar la información de la medida. Inténtelo más tarde.",
                        buttons: {
                            confirm: true,
                        }
                    });
                }
            },
            error: function(xhr, textStatus, error){
                console.log(xhr.statusText);
                console.log(textStatus);
                console.log(error);
            }
        });
    } else {
        $('#fail-updateMeasure').css({ 'display':'block' });
    }
}

$('#close-fail-updateMeasure').on('click', function() {
    $('#fail-updateMeasure').css({ 'display':'none' });
});

$('#close-fail-addMeasureForm').on('click', function() {
    $('#fail-addMeasureForm').css({ 'display':'none' });
});

function deleteMeasure(id) {
    idButtonDelete = id.getAttribute('value');
    let measureID = $('#measure-id'+idButtonDelete).val();
    if (measureID != '') {
        $('#deleteMeasureModal').modal('show');
        $('#input-medida-to-delete').val(measureID);
        $('#fail-updateMeasure').hide();
    }
}

$('#btn-measure-delete').on('click', function() {
    let medidaID = $('#input-medida-to-delete').val();
    let APCid = $('#id-apc-especifica').val();
    console.log(APCid);
    $.ajax({
        url: window.location.origin + '/admin/apc/especifica/medida/eliminar', 
        type: 'POST',             
        data: {
            'apc-id': APCid,
            'apc-medida-id': medidaID
        },
        success: function(response) {
            $('#deleteMeasureModal').modal('hide');
            if (response == 1) {
                swal({
                    title: "¡Excelente!",
                    text: "La medida ha sido eliminada correctamente.",								
                    buttons: {
                        confirm: true,
                    }
                }).then(function() {
                    location.reload();
                });
            }
            else {
                swal({
                    title: "¡Algo salió mal!",
                    text: "No se pudo eliminar la medida. Inténtelo más tarde.",
                    buttons: {
                        confirm: true,
                    }
                });
            }
        },
        error: function(xhr, textStatus, error){
            console.log(xhr.statusText);
            console.log(textStatus);
            console.log(error);
        }
    });
});

$("#btn-agregar-medida").on('click', function() {
    $('#wizard_vertical-t-0').click();
    $('#wizard_vertical-t-1').parent().attr('class', 'disabled last');
    $('#wizard_vertical-t-2').parent().attr('class', 'disabled last');
    $('#wizard_vertical-t-3').parent().attr('class', 'disabled last');
    $('#wizard_vertical-t-4').parent().attr('class', 'disabled last');
    $('#wizard_vertical-t-5').parent().attr('class', 'disabled last');
    $('#fail-addMeasureForm').css({ 'display':'none' });
    $('#sel-medida').val('').trigger('change');
    $('#objective-group').val('');
    $('#objective-zones').val('');
    $('[name=responsibleEntity]').hide();
    $('#responsible-name').val('');
    $('#start-date').val('');
    $('#end-date').val('');
    $('#progress-level').val('0');
    $('#addMeasureModal').modal('show');
});

var selectedResponsable = null;
$(document).ready(function() {
    $('#sel-medida').on('change', function() {
        let idSelMedida = $(this).val();
        if (idSelMedida !== null) {
            $('[name=responsibleEntity]').hide();
            $('#add-responsible-entity-' + idSelMedida).show();
            selectedResponsable = $('#add-responsible-entity-' + idSelMedida).val();
        }
    });
});

$('#btn-measure-save').on('click', function() {
    $('#addMeasureForm').validate({
        rules: {
            objectiveGroup: { required: true },
            objectiveZones: { required: true },
            responsibleEntity: { required: true },
            responsibleName: { required: true },
            startDate: { 
                required: true,
                date: true
            },
            endDate: {
                required: true,
                date: true
            },
            progressLevel: {
                required: true,
                min: 0,
                max: 100,
                digits: true
            }
        },
        messages: {
            objectiveGroup: { required: 'Este dato es requerido' },
            objectiveZones: { required: 'Este dato es requerido' },
            responsibleEntity: { required: 'Este dato es requerido' },
            responsibleName: { required: 'Este dato es requerido' },
            startDate: { 
                required: 'Este dato es requerido',
                date: 'La fecha ingresada no es válida'
            },
            endDate: {
                required: 'Este dato es requerido',
                date: 'La fecha ingresada no es válida'
            },
            progressLevel: {
                required: 'Este dato es requerido',
                min: 'El valor mínimo permitido es 0',
                max: 'El valor máximo permitido es 100',
                digits: 'El valor debe ser un número entero entre 0 y 100'
            }
        },
        submitHandler: function(form) {
            let measure = $('#sel-medida option:selected').text();
            let group = $('#objective-group').val();
            let zones = $('#objective-zones').val();
            let entity = selectedResponsable;
            let responsible = $('#responsible-name').val();
            let start = $('#start-date').val()
            let end = $('#end-date').val();
            let progress = $('#progress-level').val();
            let APCid = $('#id-apc-especifica').val();
            
            if ((measure !== null) && (group != '') && (zones != '') && (entity != null) && (responsible !== '') && (start !== '') && (end !== '') && (progress !== '')) {
                $('#fail-addMeasureForm').css({ 'display':'none' });
                $.ajax({
					url: window.location.origin + '/admin/apc/especifica/medida/agregar', 
					type: 'POST',             
					data: {
                        'apc-id': APCid,
						'medida-nombre': measure,
                        'medida-grupo': group,
                        'medida-zonas': zones,
						'medida-responsable': entity,
						'medida-responsable-contacto': responsible,
						'medida-fecha-inicio': start,
						'medida-fecha-termino': end,
						'medida-avance': progress
					},
					success: function(response) {
                        $('#addMeasureModal').modal('hide');
						if (response == 1) {
							swal({
								title: "¡Excelente!",
								text: "La medida ha sido registrada correctamente.",								
								buttons: {
									confirm: true,
								}
							}).then(function() {
								location.reload();
							});
						}
						else {
							swal({
								title: "¡Algo salió mal!",
								text: "No se pudo registrar la medida. Inténtelo más tarde.",
								buttons: {
									confirm: true,
								}
							});
						}
					},
					error: function(xhr, textStatus, error){
						console.log(xhr.statusText);
						console.log(textStatus);
						console.log(error);
					}
				});
            } else {
                $('#fail-addMeasureForm').css({ 'display':'block' });
            }
        }
    });
});

var nameGrupoToDelete = null;
var idAPCToDelete = null;
var apcInterfaz = null;
function deleteGrupo(grupo) {
    let dataGrupo = grupo.getAttribute('value').split(',');
    nameGrupoToDelete = dataGrupo[0];
    idAPCToDelete = dataGrupo[1];
    apcInterfaz = dataGrupo[2];
    $('#modal-delete-grupo').modal('show');
}

$('#btn-delete-grupo').on('click', function() {
    $.ajax({
        url: window.location.origin + '/admin/apc/especifica/grupo/eliminar', 
        type: 'POST',             
        data: {
            'nombre-grupo': nameGrupoToDelete,
            'id-apc': idAPCToDelete
        },
        success: function(response) {
            $('#modal-delete-grupo').modal('hide');
            if (response == 1) {
                swal({
                    title: "¡Excelente!",
                    text: "El grupo de causas específicas ha sido eliminado correctamente.",								
                    buttons: {
                        confirm: true,
                    }
                }).then(function() {
                    window.location.href = window.location.origin + '/admin/gestion/apcespecifica/listar/interfaz/' + apcInterfaz;
                });
            }
            else {
                swal({
                    title: "¡Algo salió mal!",
                    text: "No se pudo eliminar el grupo de causas específicas. Inténtelo más tarde.",
                    buttons: {
                        confirm: true,
                    }
                });
            }
        },
        error: function(xhr, textStatus, error){
            console.log(xhr.statusText);
            console.log(textStatus);
            console.log(error);
        }
    });
});

$('#btn-close-delete-grupo').on('click', function() {
    $('#modal-delete-grupo').modal('hide');
});

var selectedMedidas = [];
$('#btn-delete-multiples-medidas').on('click', function() {
    selectedMedidas = [];
    $('input:checkbox[name=check-medida]:checked').each(function() {
        selectedMedidas.push($(this).attr('value'));
    });
    if (selectedMedidas.length > 0) $('#modal-eliminar-multiples-medidas').modal('show');
});

$('#btn-cancelar-eliminacion-multiples').on('click', function() {
    $('#modal-eliminar-multiples-medidas').modal('hide');
});

$('#btn-confirmar-eliminacion-multiples').on('click', function() {
    let APCid = $('#id-apc-especifica').val();
    let interfaz = $('#id-apc-interfaz').val();
    
    $.ajax({
        url: window.location.origin + '/admin/apc/especifica/medida/eliminar/multiples', 
        type: 'POST',
        data: {
            'medidas': selectedMedidas,
            'apc-id': APCid
        },
        success: function(response) {
            $('#modal-eliminar-multiples-medidas').modal('hide');
            if (response == 1) {
                swal({
                    title: "¡Excelente!",
                    text: "Las medidas seleccionadas han sido eliminadas correctamente.",								
                    buttons: {
                        confirm: true,
                    }
                }).then(function() {
                    window.location.href = window.location.origin + '/admin/gestion/apcespecifica/editar/' + APCid + '/interfaz/' + interfaz;
                });
            }
            else {
                swal({
                    title: "¡Algo salió mal!",
                    text: "No se pudo eliminar las medidas. Inténtelo más tarde.",
                    buttons: {
                        confirm: true,
                    }
                });
            }
        },
        error: function(xhr, textStatus, error){
            console.log(xhr.statusText);
            console.log(textStatus);
            console.log(error);
        }
    });
});

function exportarPDF() {
    let elementToPrint = document.createElement('div');
    let divMedidasEspecificas = document.getElementById('pdf-body');
    let header = document.getElementById('pdf-header');
    elementToPrint.appendChild(header.cloneNode(true));
    elementToPrint.appendChild(divMedidasEspecificas.cloneNode(true));

    html2pdf()
        .set({
            margin: 2,
            filename: 'Medidas_especificas.pdf',
            html2canvas: {
                scale: 5,
                letterRendering: true
            },
            jsPDF: {
                unit: 'cm',
                format: 'letter',
                orientation: 'portrait'
            }
        })
        .from(elementToPrint)
        .save()
        .finally();
}