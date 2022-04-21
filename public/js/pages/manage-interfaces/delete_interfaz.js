$('#btn-eliminar-interfaz').on('click', function() {
    let interfaz = $('#id-delete-interfaz').val();
    
    $.ajax({
        url: window.location.origin + '/admin/gestion/eliminar/interfaz/confirmar', 
        type: 'POST',             
        data: {
            'id-interfaz': interfaz,
        },
        success: function(response) {
            if (response == 1) {
                swal({
                    title: "¡Excelente!",
                    text: "La interfaz ha sido eliminada correctamente.",
                    buttons: {
                        confirm: true,
                    }
                }).then(function() {
                    window.location.href = window.location.origin + '/admin/gestion/listar';
                });
            }
            else {
                swal({
                    title: "¡Algo salió mal!",
                    text: "No se pudo eliminar la interfaz. Inténtelo más tarde.",
                    buttons: {
                        confirm: true,
                    }
                }).then(function() {
                    window.location.href = window.location.origin + '/admin/gestion/listar';
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