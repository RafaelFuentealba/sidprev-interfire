/** Messages for Riesgo by interfaz */

$('#riesgo-info').on('click', function() {
	$('#information-modal-body').html(
        '<b>Riesgo</b> ' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Probabilidad de ocurrencia de pérdidas o daños ambientales, materiales o vidas, humanas y animales en un territorio expuesto a amenazas de incendios, durante un tiempo específico.' +
        '</div>' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Índice cuantitativo en rango de 0 (riesgo mínimo) a 100 (riesgo máximo), que resulta de  la relación entre amenaza y vulnerabilidad.' +
        '<div/>' +
        '<hr>' +
        '<b>Amenaza</b> ' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Fenómeno multidimensional capaz de generar pérdidas o daños ambientales, materiales o vidas, humanas y animales en un territorio.' +
        '</div>' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Índice cuantitativo en rango de 0 (riesgo mínimo) a 100 (riesgo máximo), contribuye al riesgo.' +
        '<div/>' +
        '<hr>' +
        '<b>Vulnerabilidad</b> ' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Condiciones multicausales que  aumentan la susceptibilidad de un territorio  a los efectos de las amenazas.' +
        '</div>' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Índice cuantitativo en rango de 0 (riesgo mínimo) a 100 (riesgo máximo), contribuye al riesgo.' +
        '<div/>'
    );
	$('#information-modal').modal('show');
});

$('#prevalencia-info').on('click', function() {
	$('#information-modal-body').html(
        '<b>Prevalencia</b> ' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Diferencia porcentual entre el promedio de ocurrencia y daño del período quinquenio y período última temporada. Un valor elevado de prevalencia contribuye más al riesgo.' +
        '</div>' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Rango: -100 a 100.' +
        '<div/>'
    );
	$('#information-modal').modal('show');
});

$('#ocurrencia-info').on('click', function() {
	$('#information-modal-body').html(
        '<b>Ocurrencia</b> ' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Inicio y propagación de un incendio en zona de interfaz urbano - rural.' +
        '</div>' +
        '<hr>' +
        '<b>Porcentaje de ocurrencia</b> ' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Diferencia porcentual entre los incendios de la última temporada y el promedio de incendios del quinquenio.' +
        '<div/>'
    );
	$('#information-modal').modal('show');
});

$('#dano-info').on('click', function() {
	$('#information-modal-body').html(
        '<b>Daño</b> ' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Afectación de vegetación provocada por el incendio.' +
        '</div>' +
        '<hr>' +
        '<b>Porcentaje de daño</b> ' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Diferencia porcentual entre el daño de los incendios de la última temporada y el promedio de daño de los incendios del quinquenio.' +
        '<div/>'
    );
	$('#information-modal').modal('show');
});

$('#pendiente-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Suma ponderada entre la clase de pendiente y el porcentaje de superficie que cubre cada clase en la interfaz.' +
            '<ul>' +
                '<li>Pendiente baja: 0% - 30%</li>' +
                '<li>Pendiente media: 30% - 60%</li>' + 
                '<li>Pendiente alta: 60% - 100%</li>' +
            '</ul>' +
        '</div>'
    );
	$('#information-modal').modal('show');
});

$('#combustible-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Tipos de vegetación que existen dentro de la interfaz junto a su respectivo porcentaje de ocupación territorial. Puntaje normalizado según sistema Quitral.' +
        '</div>'
    );
	$('#information-modal').modal('show');
});

$('#poblacion-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Cantidad de habitantes dentro de la interfaz.' +
        '</div>'
    );
	$('#information-modal').modal('show');
});

$('#densidad-poblacional-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Cantidad de habitantes por hectárea dentro de la interfaz.' +
        '</div>'
    );
	$('#information-modal').modal('show');
});

$('#viviendas-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Cantidad de viviendas dentro de la interfaz.' +
        '</div>'
    );
	$('#information-modal').modal('show');
});

$('#densidad-viviendas-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Cantidad de viviendas por hectárea dentro de la interfaz.' +
        '</div>'
    );
	$('#information-modal').modal('show');
});

$('#techo-viviendas-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Suma ponderada entre la puntuación del tipo de techo y el porcentaje de viviendas en la interfaz.' +
        '</div>' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Rango: 0 a 100.' +
        '<div/>' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'La tabla señala la cantidad de viviendas según el material que compone el techo. Las categorías van desde más vulnerables a menos vulnerables.' +
        '<div/>'
    );
	$('#information-modal').modal('show');
});

$('#suciedad-techos-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Puntuación de la percepción de limpieza de los techos de las viviendas. Un valor elevado contribuye más al riesgo.' +
        '</div>' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Rango: 0 - 100.' +
        '<div/>'
    );
	$('#information-modal').modal('show');
});

$('#residuos-agricolas-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Puntuación de la percepción de residuos agrícolas. Un valor elevado contribuye más al riesgo.' +
        '</div>' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Rango: 0 - 100.' +
        '<div/>'
    );
	$('#information-modal').modal('show');
});

$('#residuos-forestales-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Puntuación de la percepción de residuos forestales. Un valor elevado contribuye más al riesgo.' +
        '</div>' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Rango: 0 - 100.' +
        '<div/>'
    );
	$('#information-modal').modal('show');
});

$('#residuos-domesticos-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Puntuación de la percepción de residuos domésticos. Un valor elevado contribuye más al riesgo.' +
        '</div>' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Rango: 0 - 100.' +
        '<div/>'
    );
	$('#information-modal').modal('show');
});

$('#residuos-industriales-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Puntuación de la percepción de residuos industriales. Un valor elevado contribuye más al riesgo.' +
        '</div>' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Rango: 0 - 100.' +
        '<div/>'
    );
	$('#information-modal').modal('show');
});

$('#causas-interfaz-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Cantidad de causas específicas encontradas dentro de la interfaz respecto del total de causas reportadas por CONAF.' +
        '</div>'
    );
	$('#information-modal').modal('show');
});



/** Messages for Riesgo comunal section */

$('#combustible-comunal-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Tipos de vegetación que existen dentro de la comuna junto a su respectivo porcentaje de ocupación territorial. Puntaje normalizado según sistema Quitral.' +
        '</div>'
    );
	$('#information-modal').modal('show');
});

$('#poblacion-comunal-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Cantidad de habitantes dentro de la comuna.' +
        '</div>'
    );
	$('#information-modal').modal('show');
});

$('#densidad-poblacional-comunal-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Cantidad de habitantes por hectárea dentro de la comuna.' +
        '</div>'
    );
	$('#information-modal').modal('show');
});

$('#viviendas-comunal-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Cantidad de viviendas dentro de la comuna.' +
        '</div>'
    );
	$('#information-modal').modal('show');
});

$('#densidad-viviendas-comunal-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Cantidad de viviendas por hectárea dentro de la comuna.' +
        '</div>'
    );
	$('#information-modal').modal('show');
});

$('#techo-viviendas-comunal-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Suma ponderada entre la puntuación del tipo de techo y el porcentaje de viviendas en la comuna.' +
        '</div>' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Rango: 0 a 100.' +
        '<div/>' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'La tabla señala la cantidad de viviendas según el material que compone el techo. Las categorías van desde más vulnerables a menos vulnerables.' +
        '<div/>'
    );
	$('#information-modal').modal('show');
});

$('#causas-comunal-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Cantidad de causas específicas encontradas dentro de la comuna respecto del total de causas reportadas por CONAF.' +
        '</div>'
    );
	$('#information-modal').modal('show');
});

$('#apc-comunal-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Número de Agendas de Prevención Comunitarias que están contempladas para la comuna.' +
        '</div>'
    );
	$('#information-modal').modal('show');
});

$('#apc-habitantes-comunal-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Porcentaje de habitantes, respecto del total de la comuna, que se encuentran contemplados en las Agendas de Prevención Comunitarias.' +
        '</div>' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Rango: 0% - 100%.' +
        '<div/>'
    );
	$('#information-modal').modal('show');
});

$('#apc-viviendas-comunal-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Porcentaje de viviendas, respecto del total de la comuna, que se encuentran contempladas en las Agendas de Prevención Comunitarias.' +
        '</div>' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Rango: 0% - 100%.' +
        '<div/>'
    );
	$('#information-modal').modal('show');
});

$('#apc-avance-comunal-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Porcentaje de avance de las medidas específicas y generales de las Agendas de Prevención Comunitarias que están contempladas para la comuna.' +
        '</div>' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Rango: 0% - 100%.' +
        '<div/>'
    );
	$('#information-modal').modal('show');
});



/** Messages for Riesgo interfaces section */
$('#pendiente-interfaces-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Suma ponderada entre la clase de pendiente y el porcentaje de superficie que cubre cada una de las clases en las interfaces.' +
            '<ul>' +
                '<li>Pendiente baja: 0% - 30%</li>' +
                '<li>Pendiente media: 30% - 60%</li>' + 
                '<li>Pendiente alta: 60% - 100%</li>' +
            '</ul>' +
        '</div>'
    );
	$('#information-modal').modal('show');
});

$('#combustible-interfaces-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Tipos de vegetación que existen dentro de las interfaces junto a su respectivo porcentaje de ocupación territorial. Puntaje normalizado según sistema Quitral.' +
        '</div>'
    );
	$('#information-modal').modal('show');
});

$('#poblacion-interfaces-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Cantidad de habitantes dentro de las interfaces.' +
        '</div>'
    );
	$('#information-modal').modal('show');
});

$('#densidad-poblacional-interfaces-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Cantidad de habitantes por hectárea dentro de las interfaces.' +
        '</div>'
    );
	$('#information-modal').modal('show');
});

$('#viviendas-interfaces-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Cantidad de viviendas dentro de las interfaces.' +
        '</div>'
    );
	$('#information-modal').modal('show');
});

$('#densidad-viviendas-interfaces-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Cantidad de viviendas por hectárea dentro de las interfaces.' +
        '</div>'
    );
	$('#information-modal').modal('show');
});

$('#techo-viviendas-interfaces-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Suma ponderada entre la puntuación del tipo de techo y el porcentaje de viviendas en las interfaces.' +
        '</div>' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Rango: 0 a 100.' +
        '<div/>' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'La tabla señala la cantidad de viviendas según el material que compone el techo. Las categorías van desde más vulnerables a menos vulnerables.' +
        '<div/>'
    );
	$('#information-modal').modal('show');
});

$('#suciedad-techos-interfaces-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Puntuación promedio de la percepción de limpieza de los techos de las viviendas; basándose en las interfaces definidas. Un valor elevado contribuye más al riesgo.' +
        '</div>' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Rango: 0 - 100.' +
        '<div/>'
    );
	$('#information-modal').modal('show');
});

$('#residuos-agricolas-interfaces-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Puntuación promedio de la percepción de residuos agrícolas; basándose en las interfaces definidas. Un valor elevado contribuye más al riesgo.' +
        '</div>' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Rango: 0 - 100.' +
        '<div/>'
    );
	$('#information-modal').modal('show');
});

$('#residuos-forestales-interfaces-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Puntuación promedio de la percepción de residuos forestales; basándose en las interfaces definidas. Un valor elevado contribuye más al riesgo.' +
        '</div>' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Rango: 0 - 100.' +
        '<div/>'
    );
	$('#information-modal').modal('show');
});

$('#residuos-domesticos-interfaces-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Puntuación promedio de la percepción de residuos domésticos; basándose en las interfaces definidas. Un valor elevado contribuye más al riesgo.' +
        '</div>' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Rango: 0 - 100.' +
        '<div/>'
    );
	$('#information-modal').modal('show');
});

$('#residuos-industriales-interfaces-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Puntuación promedio de la percepción de residuos industriales; basándose en las interfaces definidas. Un valor elevado contribuye más al riesgo.' +
        '</div>' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Rango: 0 - 100.' +
        '<div/>'
    );
	$('#information-modal').modal('show');
});

$('#causas-interfaces-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Cantidad de causas específicas encontradas dentro de las interfaces respecto del total de causas reportadas por CONAF.' +
        '</div>'
    );
	$('#information-modal').modal('show');
});

$('#apc-interfaces-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Número de Agendas de Prevención Comunitarias que están contempladas para todas las interfaces.' +
        '</div>'
    );
	$('#information-modal').modal('show');
});

$('#apc-habitantes-interfaces-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Cantidad de habitantes que se encuentran contemplados en las Agendas de Prevención Comunitarias.' +
        '</div>'
    );
	$('#information-modal').modal('show');
});

$('#apc-viviendas-interfaces-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Cantidad de viviendas que se encuentran contempladas en las Agendas de Prevención Comunitarias.' +
        '</div>'
    );
	$('#information-modal').modal('show');
});

$('#apc-avance-interfaces-info').on('click', function() {
	$('#information-modal-body').html(
        '<div style="margin-top:10px; text-align:justify;">' +
            'Porcentaje de avance de las medidas específicas y generales de las Agendas de Prevención Comunitarias que están contempladas para todas las interfaces.' +
        '</div>' +
        '<div style="margin-top:10px; text-align:justify;">' +
            'Rango: 0% - 100%.' +
        '<div/>'
    );
	$('#information-modal').modal('show');
});