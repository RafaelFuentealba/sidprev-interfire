$('#region').on('change', function() {
    $('#provincia').find('option').remove().end().append('<option value="" selected="true" disabled>-- Seleccionar provincia --</option>');
    let region = $(this).val();
    $.ajax({
        url: window.location.origin + '/admin/perfil/listar/provincias', 
        type: 'GET',             
        data: {
            'region-name': region
        },
        success: function(response) {
            response.forEach(element => {
                $('#provincia').append(`<option value="${element.nom_provin}"> ${element.nom_provin}</option>`);
            });
        },
        error: function(xhr, textStatus, error){
            console.log(xhr.statusText);
            console.log(textStatus);
            console.log(error);
        }
    });
});

$('#provincia').on('change', function() {
    $('#comuna').find('option').remove().end().append('<option value="" selected="true" disabled>-- Seleccionar comuna --</option>');
    let provincia = $(this).val();
    $.ajax({
        url: window.location.origin + '/admin/perfil/listar/comunas', 
        type: 'GET',             
        data: {
            'provincia-name': provincia
        },
        success: function(response) {
            response.forEach(element => {
                $('#comuna').append(`<option value="${element.nom_comuna}"> ${element.nom_comuna}</option>`);
            });
        },
        error: function(xhr, textStatus, error){
            console.log(xhr.statusText);
            console.log(textStatus);
            console.log(error);
        }
    });
});

$('#btn-save-profile').on('click', function() {
    $('#form-save-profile').validate({
        rules: {
            nombre: {
                required: true,
                maxlength: 100
            },
            apellido: {
                required: true,
                maxlength: 100
            },
            contacto: { maxlength: 25 },
            organizacion: { maxlength: 100 },
            cargoLaboral: { maxlength: 100 }
        },
        messages: {
            nombre: {
                required: 'Este dato es requerido',
                maxlength: 'El dato excede los 100 caracteres'
            },
            apellido: {
                required: 'Este dato es requerido',
                maxlength: 'El dato excede los 100 caracteres'
            },
            contacto: { maxlength: 'El dato excede los 100 caracteres' },
            organizacion: { maxlength: 'El dato excede los 100 caracteres' },
            cargoLaboral: { maxlength: 'El dato excede los 100 caracteres' }
        },
        submitHandler: function(form) {
            let nombre = $('#nombre').val();
            let apellido = $('#apellido').val();
            let contacto = $('#contacto').val();
            let organizacion = $('#nombre-organizacion').val();
            let cargo = $('#cargo-laboral').val();
            let region = $('#region').val();
            let provincia = $('#provincia').val();
            let comuna = $('#comuna').val();

            if (nombre != '' && apellido != '' && region != '' && provincia != '' && comuna != '') {
                $.ajax({
					url: window.location.origin + '/admin/perfil/basico/guardar', 
					type: 'POST',     
					data: {
                        'usuario-nombre': nombre,
						'usuario-apellido': apellido,
                        'usuario-contacto': contacto,
                        'usuario-organizacion': organizacion,
                        'usuario-cargo': cargo,
                        'usuario-region': region,
                        'usuario-provincia': provincia,
                        'usuario-comuna': comuna
					},
					success: function(response) {
                        if (response == 1) {
                            swal({
                                title: "¡Excelente!",
                                text: "Los datos han sido actualizados correctamente.",								
                                buttons: {
                                    confirm: true,
                                }
                            }).then(function() {
                                window.location.href = window.location.origin + '/admin/perfil';
                            });
                        }
                        else {
                            swal({
                                title: "¡Algo salió mal!",
                                text: "No se pudo eliminar el grupo de causas específicas. Inténtelo más tarde.",
                                buttons: {
                                    confirm: true,
                                }
                            }).then(function() {
                                window.location.href = window.location.origin + '/admin/perfil';
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

$('#btn-save-profile-security').on('click', function() {
    $('#form-save-profile-security').validate({
        rules: {
            usuarioPassword: {
                required: true,
                minlength: 8,
                maxlength: 25
            },
            usuarioNewPassword: {
                required: true,
                minlength: 8,
                maxlength: 25
            }
        },
        messages: {
            usuarioPassword: {
                required: 'Este dato es requerido',
                minlength: 'El dato debe tener más de 8 caracteres',
                maxlength: 'El dato excede los 25 caracteres'
            },
            usuarioNewPassword: {
                required: 'Este dato es requerido',
                minlength: 'El dato debe tener más de 8 caracteres',
                maxlength: 'El dato excede los 25 caracteres'
            }
        },
        submitHandler: function(form) {
            let password = $('#usuario-password').val();
            let newPassword = $('#usuario-new-password').val();
            let failAlert = $('#fail-change-password');
            if (password != '' && newPassword != '') {
                $.ajax({
					url: window.location.origin + '/admin/perfil/seguridad/guardar', 
					type: 'POST',     
					data: {
                        'usuario-password': password,
						'usuario-new-password': newPassword
					},
					success: function(response) {
                        if (response == 1) {
                            swal({
                                title: "¡Excelente!",
                                text: "La contraseña ha sido actualizada correctamente.",								
                                buttons: {
                                    confirm: true,
                                }
                            }).then(function() {
                                window.location.href = window.location.origin + '/admin/perfil';
                            });
                        } else if (response == 0) {
                            swal({
                                title: "¡Algo salió mal!",
                                text: "No se pudo cambiar la contraseña. Inténtelo más tarde.",
                                buttons: {
                                    confirm: true,
                                }
                            }).then(function() {
                                window.location.href = window.location.origin + '/admin/perfil';
                            });
                        } else {
                            failAlert.html(JSON.parse(response));
                            failAlert.css({ 'display':'block' });
                            $('#usuario-new-password').val('');
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