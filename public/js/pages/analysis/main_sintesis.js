function getDetailsGrupoCausas(btn) {
    let data = btn.value.split(',');
    let grupoCausa = data[0];
    let grupoEspecifica = data[1];

    let interfaz = window.location.pathname.split("/").pop();
	let urlSintesis = null;
	if (isNaN(interfaz)) urlSintesis = window.location.origin + '/admin/analisis/sintesis/grupo';
	else urlSintesis = window.location.origin + '/admin/gestion/sintesis/interfaz/'+ interfaz +'/grupo';

    $.ajax({
        url: urlSintesis, 
        type: 'POST',             
        data: {
            'grupo-causa': grupoCausa,
            'grupo-especifica': grupoEspecifica
        },
        success: function(response) {
            let totalIncendios = 0;
            $('#div-content-causas-especificas').empty();

            response.forEach(element => {
                totalIncendios = totalIncendios + parseInt(element.cantidad);
            });
            response.forEach(element => {
                let content = 
                '<div class="col-lg-4 col-md-6 col-sm-6 col-6 text-center">' +
                    '<div class="card">' +
                        '<div class="body" style="border: 1px solid green;">' +
                            '<div class="d-flex bd-highlight text-left mt-0">' +
                                '<div class="flex-fill bd-highlight">' +
                                    '<small class="text-muted">Causa específica</small>' +
                                    '<p>' + element.causa_especifica + '</p>' +
                                '</div>' +
                            '</div>' +
                            '<div class="d-flex bd-highlight text-left">' +
                                '<div class="flex-fill bd-highlight">' +
                                    '<small class="text-muted">N° incendios</small>' +
                                    '<p>' + element.cantidad + '</p>' +
                                '</div>' +
                            '</div>' +
                            '<div class="d-flex bd-highlight text-left">' +
                                '<div class="flex-fill bd-highlight">' +
                                    '<small class="text-muted">% incendios</small>' +
                                    '<p>' + ((element.cantidad/totalIncendios)*100).toFixed(1) + '</p>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>';
                $('#div-content-causas-especificas').append(content);
            });
            $('#modal-nombre-grupo-causa').text(grupoEspecifica);            
            $('#modal-causas-especificas').modal('show');
        },
        error: function(xhr, textStatus, error){
            console.log(xhr.statusText);
            console.log(textStatus);
            console.log(error);
        }
    });
}

function exportarPDF() {
    let divSintesisCausas = document.getElementById('table-sintesis-causas');
    $("#table-body-sintesis tr").each(function(){
        $(this).attr('style', 'border: solid 1px black;');
    });
    html2pdf()
        .set({
            margin: 0.75,
            filename: 'Sintesis_causas.pdf',
            html2canvas: {
                scale: 5,
                letterRendering: true
            },
            jsPDF: {
                unit: 'in',
                format: 'A4',
                orientation: 'landscape'
            }
        })
        .from(divSintesisCausas)
        .save()
        .finally();
}