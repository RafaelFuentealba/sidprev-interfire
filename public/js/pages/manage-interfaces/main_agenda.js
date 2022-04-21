function exportarPDF() {
    let interfaz = $('#nombre-interfaz').val();
    let elementToPrint = document.createElement('div');
    let divMedidasEspecificas = document.getElementById('pdf-body');
    let header = document.getElementById('pdf-header');
    elementToPrint.appendChild(header.cloneNode(true));
    elementToPrint.appendChild(divMedidasEspecificas.cloneNode(true));

    html2pdf()
        .set({
            margin: 2,
            filename: 'Agenda '+ interfaz + '.pdf',
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