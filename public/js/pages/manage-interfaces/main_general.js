$('#btn-actualizar-medida').on('click', function() {
    $('#form-actualizar-medida-general').validate({
        rules: {
            zonasMedida: { required: true },
            responsableMedida: { required: true },
            contactoMedida: { required: true },
            inicioMedida: { 
                required: true,
                date: true
            },
            terminoMedida: {
                required: true,
                date: true
            },
            avanceMedida: {
                required: true,
                min: 0,
                max: 100,
                digits: true
            }
        },
        messages: {
            zonasMedida: { required: 'Este dato es requerido' },
            responsableMedida: { required: 'Este dato es requerido' },
            contactoMedida: { required: 'Este dato es requerido' },
            inicioMedida: { 
                required: 'Este dato es requerido',
                date: 'La fecha ingresada no es válida'
            },
            terminoMedida: {
                required: 'Este dato es requerido',
                date: 'La fecha ingresada no es válida'
            },
            avanceMedida: {
                required: 'Este dato es requerido',
                min: 'El valor mínimo permitido es 0',
                max: 'El valor máximo permitido es 100',
                digits: 'El valor debe ser un número entero entre 0 y 100'
            }
        },
        submitHandler: function(form) {
            let nombre = $('#nombre-medida').val();
            let zonas = $('#zonas-medida').val();
            let responsable = $('#responsable-medida').val();
            let contacto = $('#contacto-medida').val();
            let inicio = $('#inicio-medida').val()
            let termino = $('#termino-medida').val();
            let avance = $('#avance-medida').val();
            let idMedida = $('#id-medida-general').val();
            let idInterfaz = $('#id-interfaz').val();
            
            if ((zonas != '') && (responsable != '') && (contacto != '') && (inicio != '') && (termino != '') && (avance != '')) {
                $.ajax({
					url: window.location.origin + '/admin/apc/general/actualizar', 
					type: 'POST',             
					data: {
                        'medida-id': idMedida,
                        'medida-zonas': zonas,
						'medida-responsable': responsable,
						'medida-contacto-responsable': contacto,
						'medida-fecha-inicio': inicio,
						'medida-fecha-termino': termino,
						'medida-avance': avance
					},
					success: function(response) {
						if (response == 1) {
							swal({
								title: "¡Excelente!",
								text: "La medida ha sido actualizada correctamente.",								
								buttons: {
									confirm: true,
								}
							}).then(function() {
								window.location.href = window.location.origin + '/admin/gestion/apcgeneral/ver/' + idMedida + '/interfaz/' + idInterfaz
							});
						}
						else {
                            $('#btn-measure-modal-close').click();
							swal({
								title: "¡Algo salió mal!",
								text: "No se pudo actualizar la medida. Inténtelo más tarde.",
								buttons: {
									confirm: true,
								}
							}).then(function() {
                                window.location.href = window.location.origin + '/admin/gestion/apcgeneral/ver/' + idMedida + '/interfaz/' + idInterfaz
                            });
						}
					},
					error: function(xhr, textStatus, error){
						console.log(xhr.statusText);
						console.log(textStatus);
						console.log(error);
					}
				});
            }
        }
    });
});

$("#btn-eliminar-medida").on('click', function() {
    $('#modal-eliminar-medida').modal('show');
});

$("#btn-confirmar-eliminacion").on('click', function() {
    let idMedida = $('#id-medida-general').val();
    let idInterfaz = $('#id-interfaz').val();

    $.ajax({
        url: window.location.origin + '/admin/apc/general/eliminar', 
        type: 'POST',
        data: {
            'id-medida': idMedida,
            'id-interfaz-apc': idInterfaz
        },
        success: function(response) {
            if (response == 1) {
                $('#modal-eliminar-medida').modal('hide');
                swal({
                    title: "¡Excelente!",
                    text: "La medida ha sido eliminada correctamente.",								
                    buttons: {
                        confirm: true,
                    }
                }).then(function() {
                    window.location.href = window.location.origin + '/admin/gestion/apcgeneral/listar/interfaz/' + idInterfaz;
                });
            }
            else {
                $('#modal-eliminar-medida').modal('hide');
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

$('#btn-cancelar-eliminacion').on('click', function() {
    $('#modal-eliminar-medida').modal('hide');
});

var medidaToDelete = null;
function eliminarMedidaListado(id) {
    $('#modal-eliminar-medida').modal('show');
    medidaToDelete = id;
}

$('#btn-eliminar-medida-listado').on('click', function() {
    let idInterfaz = $('#id-interfaz').val();

    $.ajax({
        url: window.location.origin + '/admin/apc/general/eliminar', 
        type: 'POST',
        data: {
            'id-medida': medidaToDelete,
            'id-interfaz-apc': idInterfaz
        },
        success: function(response) {
            $('#modal-eliminar-medida').modal('hide');
            if (response == 1) {
                swal({
                    title: "¡Excelente!",
                    text: "La medida ha sido eliminada correctamente.",								
                    buttons: {
                        confirm: true,
                    }
                }).then(function() {
                    window.location.href = window.location.origin + '/admin/gestion/apcgeneral/listar/interfaz/' + idInterfaz
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
    let idInterfaz = $('#id-interfaz').val();

    $.ajax({
        url: window.location.origin + '/admin/apc/general/eliminar/multiples', 
        type: 'POST',
        data: { 
            'medidas': selectedMedidas,
            'id-interfaz-apc': idInterfaz
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
                    window.location.href = window.location.origin + '/admin/gestion/apcgeneral/listar/interfaz/' + idInterfaz
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


$("#btn-agregar-medida").on('click', function() {
    $('#wizard_vertical-t-0').click();
    $('#wizard_vertical-t-1').parent().attr('class', 'disabled last');
    $('#wizard_vertical-t-2').parent().attr('class', 'disabled last');
    $('#wizard_vertical-t-3').parent().attr('class', 'disabled last');
    $('#wizard_vertical-t-4').parent().attr('class', 'disabled last');
    $('#wizard_vertical-t-5').parent().attr('class', 'disabled last');
    $('#fail-add-medida-general').css({ 'display':'none' });
    $('#sel-medida').val('').trigger('change');
    $('#label-detalle-medida').css({ 'display':'none' });
    $('#label-detalle-medida').css({ 'display':'none' });
    $('#zonas-objetivo').css({ 'display':'none' });
    $('#zonas-objetivo').val('');
    $('[name=responsables]').hide();
    $('#contacto-responsable').val('');
    $('#fecha-inicio').val('');
    $('#fecha-termino').val('');
    $('#avance').val('0');
    $('#modal-add-medida-general').modal('show');
});

var selectedResponsable = null;
$(document).ready(function() {
    $('#sel-medida').on('change', function() {
        selectedResponsable = null;
        let medida = $(this).val();
        if (medida != null) {
            if (medida == 'Construcción de cortafuegos' || medida == 'Mantención de cortafuegos') {
                $('#label-detalle-medida').css({ 'display':'block' });
                $('#label-zonas-objetivo').css({ 'display':'none' });
                $('#zonas-objetivo').css({ 'display':'block' });
                $('#zonas-objetivo').attr("placeholder", "Ejemplo: 3 cortafuegos de 500 x 20 en sector Los Aromos");
            } else {
                $('#label-detalle-medida').css({ 'display':'none' });
                $('#label-zonas-objetivo').css({ 'display':'block' });
                $('#zonas-objetivo').css({ 'display':'block' });
                $('#zonas-objetivo').attr("placeholder", "Ejemplo: Sector Espejo, Villa Alegre");
            }
            medida = medida.split(" ").join("-");
            $('[name=responsables]').hide();
            $('#responsables-' + medida).show();
            selectedResponsable = $('#responsables-' + medida).val();
        }
    });
});

$('#btn-save-medida-general').on('click', function() {
    $('#form-add-medida-general').validate({
        rules: {
            zonasObjetivo: { required: true },
            responsables: { required: true },
            contactoResponsable: { required: true },
            fechaInicio: { 
                required: true,
                date: true
            },
            fechaTermino: {
                required: true,
                date: true
            },
            avance: {
                required: true,
                min: 0,
                max: 100,
                digits: true
            }
        },
        messages: {
            zonasObjetivo: { required: 'Este dato es requerido' },
            responsables: { required: 'Este dato es requerido' },
            contactoResponsable: { required: 'Este dato es requerido' },
            fechaInicio: { 
                required: 'Este dato es requerido',
                date: 'La fecha ingresada no es válida'
            },
            fechaTermino: {
                required: 'Este dato es requerido',
                date: 'La fecha ingresada no es válida'
            },
            avance: {
                required: 'Este dato es requerido',
                min: 'El valor mínimo permitido es 0',
                max: 'El valor máximo permitido es 100',
                digits: 'El valor debe ser un número entero entre 0 y 100'
            }
        },
        submitHandler: function(form) {
            let idInterfaz = $('#id-interfaz').val();
            let APCid = $('#id-apc-general').val();
            let medida = $('#sel-medida option:selected').text();
            let zonas = $('#zonas-objetivo').val();
            let responsables = selectedResponsable;
            let contacto = $('#contacto-responsable').val();
            let inicio = $('#fecha-inicio').val()
            let termino = $('#fecha-termino').val();
            let avance = $('#avance').val();
            let objetivo = null;
            if (medida == 'Generar ordenanza local') objetivo = 'para regular actividades y conductas de mayor riesgo';
            else objetivo = '';
            
            if ((medida !== null) && (zonas != '') && (responsables != null) && (contacto != '') && (inicio != '') && (termino != '') && (avance !== '')) {
                $('#fail-add-medida-general').css({ 'display':'none' });
                $.ajax({
					url: window.location.origin + '/admin/apc/general/agregar', 
					type: 'POST',             
					data: {
                        'apc-id': APCid,
						'medida-nombre': medida,
                        'medida-objetivo': objetivo,
                        'medida-zonas': zonas,
						'medida-responsable': responsables,
						'medida-contacto-responsable': contacto,
						'medida-fecha-inicio': inicio,
						'medida-fecha-termino': termino,
						'medida-avance': avance
					},
					success: function(response) {
                        $('#modal-add-medida-general').modal('hide');
						if (response == 1) {
							swal({
								title: "¡Excelente!",
								text: "La medida ha sido registrada correctamente.",								
								buttons: {
									confirm: true,
								}
							}).then(function() {
								window.location.href = window.location.origin + '/admin/gestion/apcgeneral/listar/interfaz/' + idInterfaz
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
                $('#fail-add-medida-general').css({ 'display':'block' });
            }
        }
    });
});

$('#close-fail-add-medida-general').on('click', function() {
    $('#fail-add-medida-general').css({ 'display':'none' });
});

$('#btn-modal-close-medida').on('click', function() {
    $('#modal-add-medida-general').modal('hide');
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
            filename: 'Medidas_generales.pdf',
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