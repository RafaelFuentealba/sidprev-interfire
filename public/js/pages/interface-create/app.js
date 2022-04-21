var latitude = -35.67;
var longitude = -71.54;
var zoom = 9;

/** maps and controls */
var map, openstreetmap, Opentopomap, Carto_voyager, Stamen_Terrain, osm_monocromatico, google_maps, google_terrain;
var sidebar, searchControl, results;

/** layers of the database */
var wmsIncendios2020, wmsIncendiosQuinquenio, wmsCausas2020, vmsCausasQuinquenio, vmsComunas;

/** panel of layers control */
var panelLayers, baseLayers, overLayers;

/** DOM elements of the map */
var marker;
var aPopup = [];
var aGeomanLayers = [];
var legend = L.control({position: 'bottomright'});
var controlCustomButtons, controlCustomLegends;

/** DOM elements of the webpage */
var layerSection = document.getElementById('layers');
var titleLayerSection = document.getElementById('layers-title');
var div = L.DomUtil.create('div', 'legends');

var activeOverlayers = [];



/** setting the base map and maps services */
map = L.map('map', { zoomControl: false, attributionControl: true}).setView([latitude, longitude], zoom);
		
openstreetmap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
	attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);
Opentopomap = L.tileLayer('https://tile.opentopomap.org/{z}/{x}/{y}.png', {
	attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
});
Carto_voyager = L.tileLayer('https://basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
	attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
});
Stamen_Terrain = L.tileLayer('https://stamen-tiles.a.ssl.fastly.net/terrain/{z}/{x}/{y}.png', {
	attribution: 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, under <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a>. Data by <a href="http://openstreetmap.org">OpenStreetMap</a>, under <a href="http://creativecommons.org/licenses/by-sa/3.0">CC BY SA</a>.'
});
osm_monocromatico = L.tileLayer('http://a.tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png', {
	attribution: 'Wikimedia Labs | Map data &copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
});
google_maps = L.tileLayer('https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}');
google_terrain = L.tileLayer('https://mt1.google.com/vt/lyrs=p&x={x}&y={y}&z={z}');


/** create the geocoding control and add it to the map */
searchControl = L.esri.Geocoding.geosearch({
	position: 'topleft',
    placeholder: 'Buscar lugares o direcciones',
    useMapBounds: 15,
    title: 'Búsqueda de lugares o direcciones',
	expanded: true,
	providers: [
        L.esri.Geocoding.arcgisOnlineProvider({
	        /** API Key to be passed to the ArcGIS Online Geocoding Service */
	        apikey: 'AAPK2753c497e422499f815f91d6d00526c1xbRwtMNqFXhUlGZr2uQoWJgvSIyw4-maMRTenvM5_KtrZnRTFK65j5FIlSQMMGYB'
	    })
	]
}).addTo(map);

/** create an empty layer group to store the results and add it to the map */
results = L.layerGroup().addTo(map);

/** listen for the results event and add every result to the map */
searchControl.on("results", function (data) {
    results.clearLayers();
	for (var i = data.results.length - 1; i >= 0; i--) {
	    latitude = data.results[i].latlng.lat;
	    longitude = data.results[i].latlng.lng;
	}
	getActiveOverlayers();
});

$(window).on('load', function () {
	getPanelLayers([false, false, false, false, false]);
	$('.leaflet-panel-layers').hide();
	controlCustomLegends = L.control.custom({
		position: 'bottomright',
		classes : '',
		style   :
		{
			margin: '0px 10px 10px 0',
			padding: '0px',
		},
	});
});

/** create the control custom */
L.control.custom({
    position: 'bottomleft',
    content : '<button type="button" id="btn-polygons" class="btn btn-secondary" title="Abrir herramientas de creación">'+
              '    <i class="zmdi zmdi-settings"></i>'+
              '</button>'+
              '<button type="button" id="btn-get-interface-data" class="btn btn-primary" title="Guardar interfaz">'+
              '    <i class="zmdi zmdi-cloud-done"></i>'+
              '</button>'+
			  '<button type="button" id="btn-layers" class="btn btn-success" title="Grupo de capas">'+
              '    <i class="zmdi zmdi-layers"></i>'+
              '</button>'+
              '<button type="button" id="btn-legends" class="btn btn-info" title="Ver leyendas">'+
              '    <i class="zmdi zmdi-info-outline"></i>'+
              '</button>'+
			  '<button type="button" id="btn-polygons-close" class="btn btn-danger" title="Cerrar todo">'+
              '    <i class="zmdi zmdi-close-circle-o"></i>'+
              '</button>',
    classes : 'btn-group-vertical btn-group-sm',
    style   :
    {
        margin: '12px',
        padding: '0px 0 0 0',
        cursor: 'pointer',
    }
})
.addTo(map);

function getActiveOverlayers() {
	var overlayers = $('input.leaflet-panel-layers-selector:checkbox');
	var overlayersID = [];
	for (var layer of overlayers) {
		overlayersID.push(layer.checked);
	}
	return overlayersID;
}

$('#btn-layers').on('click', function() {
	$('.leaflet-panel-layers').show();
	map.pm.removeControls();
	controlCustomLegends.options.content = '';
	controlCustomLegends.addTo(map);
});

$('#btn-polygons').on('click', function() {
	$('.leaflet-panel-layers').hide();
	controlCustomLegends.options.content = '';
	controlCustomLegends.addTo(map);

	map.pm.addControls({
		position: 'bottomright',
		oneBlock: true,
		drawMarker: false,
		drawPolyline: false,
		drawRectangle: false,
		drawPolygon: false,
		drawCircleMarker: false,
		cutPolygon: false,
		rotateMode: false,
		maxRadiusCircle: 5000
	});
	map.pm.setLang('es'); 

	map.pm.setGlobalOptions({
		maxRadiusCircle: 5000
	});
});

$('#btn-legends').on('click', function() {
	$('.leaflet-panel-layers').hide();
	map.pm.removeControls();

	layers = getActiveOverlayers();
	if (layers[0] == true || layers[1] == true) {
		controlCustomLegends.options.content = '<img src="'+ window.location.origin +':8080/geoserver/interfire/wms?REQUEST=GetLegendGraphic&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=Incendios_2020_2021" style="display: block;">';
		controlCustomLegends.addTo(map);
	} else if (layers[2] == true || layers[3] == true) {
		controlCustomLegends.options.content = '<img src="'+ window.location.origin +':8080/geoserver/interfire/wms?REQUEST=GetLegendGraphic&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=Causas_Gral_2020_2021" style="display: block;">';
		controlCustomLegends.addTo(map);
	} else {
		controlCustomLegends.options.content = '';
		controlCustomLegends.addTo(map);
	}
});
   

$('#btn-get-interface-data').on('click', function() {
	aPopup.forEach(element => {
		map.removeLayer(element);
		map.closePopup();
	});
	aPopup = [];
	aGeomanLayers = [];
	if (map.pm.getGeomanLayers().length > 1) {
		swal({
			title: "¡Oh no!",
			text: "Sólo se permite crear una interfaz a la vez.",								
			buttons: {
				confirm: true,
			}
		});
	} else if (map.pm.getGeomanLayers().length == 1) {
		swal({
			title: "¡Ya casi!",
			text: "Estamos recuperando la información solicitada. Espere un momento.",
			timer: 30000*map.pm.getGeomanLayers().length,
			buttons: false
		});
		let layer = 0;
		let shape = map.pm.getGeomanLayers()[layer].pm.getShape();
		if (shape == 'Circle') {
			let center = map.pm.getGeomanLayers()[layer]._latlng;
			let radius = map.pm.getGeomanLayers()[layer].getRadius() / 100000;
			$.ajax({
				url: window.location.origin + '/admin/interfaz/crear/circle',
				method: 'POST',
				data: { "longitude-zone": center.lng, "latitude-zone": center.lat, "radius-zone": radius },
				success: function (response) {
					let incendiosTotal = 0;
					let riesgo = 0;
					let amenaza = 0;
					let vulnerabilidad = 0;

					/** variables to prevalencia */
					let inc_quin_mean = 0;
					let inc_actual = 0;
					let inc_years = 0;
					let inc_percentage_dif = 0;
					let sup_quin_mean = 0;
					let sup_actual = 0;
					let sup_years = 0;
					let sup_percentage_dif = 0;
					let res_prevalence = 0;

					/** variables to pendiente */
					let pen_total = 0;
					let pen_percentage_clase_1 = 0;
					let pen_percentage_clase_2 = 0;
					let pen_percentage_clase_3 = 0;
					let pen_average = 0;
					let res_pen_average = 0;

					/** variables to vegetación combustible */
					let res_combustible = 0;
						
					/** variables to densidad poblacional */
					let pbl_density = 0;
					let res_pbl_density = 0;

					/** variable to estructura techos viviendas */
					let techo_tipo_1 = 0;
					let techo_tipo_1a = 0;
					let techo_tipo_1b = 0;
					let techo_tipo_2 = 0;
					let techo_tipo_3 = 0;
					let techo_tipo_4 = 0;
					let techo_total = 0;
					let res_techo = 0;


					/** check for incendios in the interfaz  */
					for (let key in response['incendios']) {
						incendiosTotal += parseInt(response['incendios'][key]);
					}

					if (incendiosTotal == 0) {
						let popup = L.popup({ closeOnClick: false, autoClose: false })
						.setLatLng([center.lat, center.lng])
						.setContent(
							'<div class="text-center">' +
								'No existen incendios registrados en esta interfaz' +
							'</div>'
						)
						.openOn(map);
					} else {
						/** calculates prevalencia */
						for (let key in response['incendios']) {
							if (key != 'inc_2020') {
								inc_quin_mean += parseInt(response['incendios'][key]);
								inc_years += 1;
							} else {
								inc_actual += parseInt(response['incendios'][key]);
							}
						}

						for (let key in response['superficie']) {
							if (key != 'sup_2020') {
								sup_quin_mean += parseFloat(response['superficie'][key]);
								sup_years += 1;
							} else {
								sup_actual += parseFloat(response['superficie'][key]);
							}
						}

						inc_quin_mean = inc_quin_mean / inc_years;
						if (inc_quin_mean == 0) inc_percentage_dif = 0;
						else inc_percentage_dif = Math.round(((inc_actual - inc_quin_mean) / inc_quin_mean) * 100);
						sup_quin_mean = sup_quin_mean / sup_years;
						if (sup_quin_mean == 0) sup_percentage_dif = 0;
						else sup_percentage_dif = Math.round(((sup_actual - sup_quin_mean) / sup_quin_mean) * 100);
						res_prevalence = getPrevalenceScore(inc_percentage_dif, sup_percentage_dif);
						
						/** calculates pendiente */
						for (let key in response['pendiente']) {
							if (response['pendiente'][key]['gridcode'] == 1) pen_percentage_clase_1 += ((parseInt(response['pendiente'][key]['gridcode']) * parseFloat(response['pendiente'][key]['pc_superficie'])));
							else if (response['pendiente'][key]['gridcode'] == 2) pen_percentage_clase_2 += ((parseInt(response['pendiente'][key]['gridcode']) * parseFloat(response['pendiente'][key]['pc_superficie'])));
							else if (response['pendiente'][key]['gridcode'] == 3) pen_percentage_clase_3 += ((parseInt(response['pendiente'][key]['gridcode']) * parseFloat(response['pendiente'][key]['pc_superficie'])));
							pen_total = pen_total + 1;
						}
						pen_average = Math.round((pen_percentage_clase_1 + pen_percentage_clase_2 + pen_percentage_clase_3) / pen_total);
						res_pen_average = getSlopeScore(pen_average);

						/** combustible vegetación combustible */
						for (let key in response['combustible']) {
							res_combustible = res_combustible + ((parseInt(response['combustible'][key]['puntaje']) * parseFloat(response['combustible'][key]['pc_superficie'])) / 100);
						}
						res_combustible = Math.round(res_combustible);

						/** calculates densidad poblacional  */
						pbl_density = Math.round(parseFloat(response['poblacion']['sum_densidad']) / parseInt(response['poblacion']['cantidad']));
						res_pbl_density = getDensityScore(pbl_density);
							
						/** calculation of the materialidad de techos viviendas */
						for (let key in response['vivienda_techos']) {
							if (key == 'techo_tipo_1a') techo_tipo_1a += parseInt(response['vivienda_techos'][key]);
							else if (key == 'techo_tipo_1b') techo_tipo_1b += parseInt(response['vivienda_techos'][key]);
							else if (key == 'techo_tipo_2') techo_tipo_2 += parseInt(response['vivienda_techos'][key]);
							else if (key == 'techo_tipo_3') techo_tipo_3 += parseInt(response['vivienda_techos'][key]);
							else if (key == 'techo_tipo_4') techo_tipo_4 += parseInt(response['vivienda_techos'][key]);
							techo_total += parseInt(response['vivienda_techos'][key]);
						}
						if (techo_total == 0) {
							res_techo = 0;
						} else {
							techo_tipo_1 = ((techo_tipo_1a + techo_tipo_1b) / techo_total) * 100;
							techo_tipo_2 = (techo_tipo_2 / techo_total) * 75;
							techo_tipo_3 = (techo_tipo_3 / techo_total) * 50;
							techo_tipo_4 = (techo_tipo_4 / techo_total) * 25;
							res_techo = Math.round(techo_tipo_1 + techo_tipo_2 + techo_tipo_3 + techo_tipo_4);
						}
						
						if (res_pen_average == null) res_pen_average = 0;
						if (res_combustible == null) res_combustible = 0;
						if (res_pbl_density == null) res_pbl_density = 0;

						amenaza = Math.round(0.5*res_prevalence + 0.25*res_pen_average + 0.25*res_combustible);
						vulnerabilidad = Math.round(0.2*res_pbl_density + 0.1*res_techo);
						if (amenaza < 0) amenaza = 0;
						if (vulnerabilidad < 0) vulnerabilidad = 0;
						riesgo = Math.round(0.5*amenaza + 0.5*vulnerabilidad);
						if (riesgo < 0) riesgo = 0;
						let popup = L.popup({ closeOnClick: false, autoClose: false })
							.setLatLng([center.lat, center.lng])
							.setContent(
								'<div class="text-center">' +
									'Riesgo: ' + riesgo + '<br>' +
									'<input type="button" id="' + layer + '" value="Completar información" class="btn btn-primary" onclick="getCompleteInfo(this)">' +
								'</div>'
							);
						popup.id = layer;
						aPopup.push(popup);
						map.pm.getGeomanLayers()[layer].id = layer;
						map.pm.getGeomanLayers()[layer].riesgo = riesgo;
						map.pm.getGeomanLayers()[layer].amenaza = amenaza;
						map.pm.getGeomanLayers()[layer].vulnerabilidad = vulnerabilidad;
						aGeomanLayers.push(map.pm.getGeomanLayers()[layer]);
						aPopup.forEach(element => {
							element.addTo(map);
						});
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(textStatus, errorThrown);
				}
			});
		}
	}
});

$('#btn-polygons-close').on('click', function() {
	$('.leaflet-panel-layers').hide();
	map.pm.removeControls();
	controlCustomLegends.options.content = '';
	controlCustomLegends.addTo(map);
});

var interfaceID;
var interfaceData;
function getCompleteInfo(button) {
	$('#wizard_horizontal-t-0').click();
	$('#wizard_horizontal-t-1').parent().attr('class', 'disabled last');
	$('#wizard_horizontal-t-2').parent().attr('class', 'disabled last');
	$('#completeInfoModal1').modal('show');
	$('#selLimpiezaTecho').val('').trigger('change');
	$('#selResiduosAgricolas').val('').trigger('change');
	$('#selResiduosForestales').val('').trigger('change');
	$('#selResiduosDomesticos').val('').trigger('change');
	$('#selResiduosIndustriales').val('').trigger('change');
	$('#nombreInterfaz').val('');
	$("label.error").hide();
	$('#fail-completeInfoForm').css({ 'display':'none' });
	$('#show-recalculated-risk').css({ 'display':'none' });
	$('#recalculatedRisk').val('');

	interfaceID = button.id;

	aGeomanLayers.forEach(element => {
		if (element.id == interfaceID) {
			interfaceData = element
		}
	});

	$('#actualRisk').val(interfaceData.riesgo);

	$('#completeInfoForm').validate({
		rules: {
			nombreInterfaz: {
				required: true,
				maxlength: 255
			}
		},
		messages: {
			nombreInterfaz: {
				required: 'Este dato es requerido',
				maxlength: 'El dato no debe exceder los 255 caracteres'
			}
		},
		submitHandler: function(form) {
			let limpiezaTecho = $('#selLimpiezaTecho').val();
			let residuosAgricolas = $('#selResiduosAgricolas').val();
			let residuosForestales = $('#selResiduosForestales').val();
			let residuosDomesticos = $('#selResiduosDomesticos').val();
			let residuosIndustriales = $('#selResiduosIndustriales').val();
			let nombre = $('#nombreInterfaz').val();
			
			if ((limpiezaTecho !== null) && (residuosAgricolas !== null) && (residuosForestales !== null) && (residuosDomesticos !== null) && (residuosIndustriales !== null) && (nombre != '')) {
				$('#fail-completeInfoForm').css({ 'display':'none' });
				let res_desechos = 0.5*residuosAgricolas + 0.5*residuosForestales;
				let res_basuras = 0.5*residuosDomesticos + 0.5*residuosIndustriales;
				let res_otros_comb = 0.5*res_desechos + 0.5*res_basuras;
				let act_vulnerabilidad = Math.round(interfaceData.vulnerabilidad + (0.4*res_otros_comb + 0.3*limpiezaTecho));
				if (act_vulnerabilidad < 0) act_vulnerabilidad = 0;
				let recalculatedRisk = Math.round(0.5*interfaceData.amenaza + 0.5*act_vulnerabilidad);
				if (recalculatedRisk < 0) recalculatedRisk = 0;
				interfaceData.vulnerabilidad = act_vulnerabilidad;
				interfaceData.riesgo = recalculatedRisk;
				
				swal({
					title: "¡Estamos preparando todo!",
					text: "Gracias por esperar. Nos estamos asegurando de preparar todo lo necesario.",
					timer: 20000,
					buttons: false
				});

				if (interfaceData.pm.getShape() == 'Circle') {
					$.ajax({
						url: window.location.origin + '/admin/interfaz/guardar', 
						type: 'POST',    
						data: {
							'interfaz-nombre': nombre,
							'interfaz-forma': JSON.stringify(['Circle', [interfaceData._latlng.lng, interfaceData._latlng.lat], interfaceData.getRadius()/100000]),
							'interfaz-riesgo': interfaceData.riesgo,
							'interfaz-amenaza': interfaceData.amenaza,
							'interfaz-vulnerabilidad': interfaceData.vulnerabilidad,
							'limpieza-techo': limpiezaTecho,
							'residuos-agricolas': residuosAgricolas,
							'residuos-forestales': residuosForestales,
							'residuos-domesticos': residuosDomesticos,
							'residuos-industriales': residuosIndustriales
						},
						success: function(response) {
							$('#completeInfoModal1').modal('hide');
							if (response == 1) {
								swal({
									title: "¡Excelente!",
									text: "La interfaz ha sido registrada correctamente.",								
									buttons: {
										confirm: true,
									}
								}).then(function() {
									window.location.href = window.location.origin + '/admin/analisis/riesgo'
								});
							} else {
								swal({
									title: "¡Algo salió mal!",
									text: "No se pudo registrar la interfaz. Inténtelo más tarde.",
									buttons: {
										confirm: true,
									}
								}).then(function() {
									window.location.href = window.location.origin + '/admin/interfaz/crear'
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
			else {
				$('#fail-completeInfoForm').css({ 'display':'block' });
			}
		}
	});	
}

$('#close-fail-completeInfoForm').on('click', function() {
    $('#fail-completeInfoForm').css({ 'display':'none' });
});

$(document).ready(function() {
	$('#btn-recalculate-risk').on('click', function() {
		let limpiezaTecho = $('#selLimpiezaTecho').val();
		let residuosAgricolas = $('#selResiduosAgricolas').val();
		let residuosForestales = $('#selResiduosForestales').val();
		let residuosDomesticos = $('#selResiduosDomesticos').val();
		let residuosIndustriales = $('#selResiduosIndustriales').val();
		let nombre = $('#nombreInterfaz').val();
		if ((limpiezaTecho !== null) && (residuosAgricolas !== null) && (residuosForestales !== null) && (residuosDomesticos !== null) && (residuosIndustriales !== null) && (nombre !== '')) {
			$('#fail-completeInfoForm').css({ 'display':'none' });
			let res_desechos = 0.5*residuosAgricolas + 0.5*residuosForestales;
			let res_basuras = 0.5*residuosDomesticos + 0.5*residuosIndustriales;
			let res_otros_comb = 0.5*res_desechos + 0.5*res_basuras;
			let act_vulnerabilidad = Math.round(interfaceData.vulnerabilidad + (0.4*res_otros_comb + 0.3*limpiezaTecho));
			if (act_vulnerabilidad < 0) act_vulnerabilidad = 0;
			let recalculatedRisk = Math.round(0.5*interfaceData.amenaza + 0.5*act_vulnerabilidad);
			if (recalculatedRisk < 0) recalculatedRisk = 0;
			$('#show-recalculated-risk').css({ 'display':'block' });
			$('#recalculatedRisk').val(recalculatedRisk);
		} else {
			$('#fail-completeInfoForm').css({ 'display':'block' });
			$('#show-recalculated-risk').css({ 'display':'none' });
		}
	});
});

function getPrevalenceScore(ocurrency, damage) {
	var json_matrix = {
		"-101,-101": { "-101,-101": -100, "-76,-100": -90, "-51,-75": -80, "-26,-50": -70, "-1,-25": -60, "0,0": -50, "1,25": -40, "26,50": -30, "51,75": -20, "76,100": -10, "101,101": 0 },
		"-76,-100": { "-101,-101": -90, "-76,-100": -80, "-51,-75": -70, "-26,-50": -60, "-1,-25": -50, "0,0": -40, "1,25": -30, "26,50": -20, "51,75": -10, "76,100": 0, "101,101": 10 },
		"-51,-75": { "-101,-101": -80, "-76,-100": -70, "-51,-75": -60, "-26,-50": -50, "-1,-25": -40, "0,0": -30, "1,25": -20, "26,50": -10, "51,75": 0, "76,100": 10, "101,101": 20 },
		"-26,-50": { "-101,-101": -70, "-76,-100": -60, "-51,-75": -50, "-26,-50": -40, "-1,-25": -30, "0,0": -20, "1,25": -10, "26,50": 0, "51,75": 10, "76,100": 20, "101,101": 30 },
		"-1,-25": { "-101,-101": -60, "-76,-100": -50, "-51,-75": -40, "-26,-50": -30, "-1,-25": -20, "0,0": -10, "1,25": 0, "26,50": 10, "51,75": 20, "76,100": 30, "101,101": 40 },
		"0,0": { "-101,-101": -50, "-76,-100": -40, "-51,-75": -30, "-26,-50": -20, "-1,-25": -10, "0,0": 0, "1,25": 10, "26,50": 20, "51,75": 30, "76,100": 40, "101,101": 50 },
		"1,25": { "-101,-101": -40, "-76,-100": -30, "-51,-75": -20, "-26,-50": -10, "-1,-25": 0, "0,0": 10, "1,25": 20, "26,50": 30, "51,75": 40, "76,100": 50, "101,101": 60 },
		"26,50": { "-101,-101": -30, "-76,-100": -20, "-51,-75": -10, "-26,-50": 0, "-1,-25": 10, "0,0": 20, "1,25": 30, "26,50": 40, "51,75": 50, "76,100": 60, "101,101": 70 },
		"51,75": { "-101,-101": -20, "-76,-100": -10, "-51,-75": 0, "-26,-50": 10, "-1,-25": 20, "0,0": 30, "1,25": 40, "26,50": 50, "51,75": 60, "76,100": 70, "101,101": 80 },
		"76,100": { "-101,-101": -10, "-76,-100": 0, "-51,-75": 10, "-26,-50": 20, "-1,-25": 30, "0,0": 40, "1,25": 50, "26,50": 60, "51,75": 70, "76,100": 80, "101,101": 90 },
		"101,101": { "-101,-101": 0, "-76,-100": 10, "-51,-75": 20, "-26,-50": 30, "-1,-25": 40, "0,0": 50, "1,25": 60, "26,50": 70, "51,75": 80, "76,100": 90, "101,101": 100 }
	}
	
	var $score = null;
	var $resDamage = function(ocurrencyKey) {
		let $result = null;
		for (damageKey in json_matrix[ocurrencyKey]) {
			let damageLimit = damageKey.split(',');
			if ((damage == 0) && (damage == damageLimit[0])) {
				$result = json_matrix[ocurrencyKey][damageKey];
			} else if ((damage >= 101) && (damage >= damageLimit[0])) {
				$result = json_matrix[ocurrencyKey][damageKey];
			} else if ((damage <= -101) && (damage <= damageLimit[0])) {
				$result = json_matrix[ocurrencyKey][damageKey];
			} else if (((damage >= 1) && (damage <= 100)) && ((damage >= damageLimit[0]) && (damage <= damageLimit[1]))) {
				$result = json_matrix[ocurrencyKey][damageKey];
			} else if (((damage >= -100) && (damage <= -1)) && ((damage >= damageLimit[1]) && (damage <= damageLimit[0]))) {
				$result = json_matrix[ocurrencyKey][damageKey];
			}
		}
		return $result;
	};
	
	for (ocurrencyKey in json_matrix) {
		let ocurrencyLimit = ocurrencyKey.split(',');
		if ((ocurrency == 0) && (ocurrency == ocurrencyLimit[0])) {
			$score = $resDamage(ocurrencyKey);
		} else if ((ocurrency >= 101) && (ocurrency >= ocurrencyLimit[0])) {
			$score = $resDamage(ocurrencyKey);
		} else if ((ocurrency <= -101) && (ocurrency <= ocurrencyLimit[0])) {
			$score = $resDamage(ocurrencyKey);
		} else if (((ocurrency >= 1) && (ocurrency <= 100)) && ((ocurrency >= ocurrencyLimit[0]) && (ocurrency <= ocurrencyLimit[1]))) {
			$score = $resDamage(ocurrencyKey);
		} else if (((ocurrency >= -100) && (ocurrency <= -1)) && ((ocurrency >= ocurrencyLimit[1]) && (ocurrency <= ocurrencyLimit[0]))) {
			$score = $resDamage(ocurrencyKey);
		}
	}
	return $score;
}

function getSlopeScore($slope) {
	let $score = null;
	if ($slope <= 3) $score = 0;
	else if (($slope >= 4) & ($slope <= 12)) $score = 33;
	else if (($slope >= 13) & ($slope <= 35)) $score = 66;
	else if ($slope >= 35) $score = 100;
	return $score;
}

function getDensityScore($density) {
	let $score = null;
	if (($density >= 0) && ($density <= 100)) $score = 0;
	else if (($density >= 101) & ($density <= 300)) $score = 50;
	else if ($density >= 301) $score = 100;
	return $score;
}

function getPanelLayers(overlayers) {
/** VMS Services */

	/** layers of the our service */
	wmsIncendios2020 = L.tileLayer.betterWms(window.location.origin + ':8080/geoserver/interfire/wms?', {
		layers: 'interfire:Incendios_2020_2021',
		format: 'image/png',
		transparent: true
	});
			
	wmsIncendiosQuinquenio = L.tileLayer.betterWms(window.location.origin + ':8080/geoserver/interfire/wms?', {
		layers: 'interfire:Incendios_Quinquenio',
		format: 'image/png',
		transparent: true		
	});	 

	wmsCausas2020 = L.tileLayer.betterWms(window.location.origin + ':8080/geoserver/interfire/wms?', {
		layers: 'interfire:Causas_Gral_2020_2021',
		format: 'image/png',
		transparent: true		
	});	
	
	wmsCausasQuinquenio = L.tileLayer.betterWms(window.location.origin + ':8080/geoserver/interfire/wms?', {
		layers: 'interfire:Causas_Gral_Quinquenio',
		format: 'image/png',
		transparent: true		
	});

	vmsComunas = L.tileLayer.betterWms(window.location.origin + ':8080/geoserver/interfire/wms?', {
		layers: 'interfire:Comunas_Chile',
		format: 'image/png',
		transparent: true		
	});
			
	overLayers = [
	    {	
		    group: "Indicadores Interfire",
			collapsed: false,
			layers: [
                {
					active: overlayers[0],
					name: "Incendios 2020 - 2021",
					icon: '<i class="zmdi zmdi-fire"></i>',
					layer: wmsIncendios2020
				},
				{
					active: overlayers[1],
					name: "Incendios Quinquenio",
					icon: '<i class="zmdi zmdi-fire"></i>',
					layer: wmsIncendiosQuinquenio
				},
				{
					active: overlayers[2],
					name: "Causas 2020 - 2021",
					icon: '<i class="zmdi zmdi-help-outline"></i>',
					layer: wmsCausas2020
				},
				{
					active: overlayers[3],
					name: "Causas Quinquenio",
					icon: '<i class="zmdi zmdi-help-outline"></i>',
					layer: wmsCausasQuinquenio
				},
				{
					active: overlayers[4],
					name: "Comunas Chile",
					icon: '<i class="zmdi zmdi-city-alt"></i>',
					layer: vmsComunas
				}
			]
		}
	];

	baseLayers = [
		{
			group: "Mapas Base",
			collapsed: false,
			layers: [
				{
					name: "OpenStreetMap",
					layer: openstreetmap
				},
				{
					name: "OpenTopoMap",
					layer: Opentopomap
				},
				{
					name: "Carto Voyager",
					layer: Carto_voyager
				},
				{
					name: "Stamen Terrain",
					layer: Stamen_Terrain
				},
				{
					name: "Google Satelite",
					layer: google_maps
				},
				{
					name: "Google Terrain",
					layer: google_terrain
				}
			]
		}		
	];

	panelLayers = new L.Control.PanelLayers(baseLayers, overLayers, {
		compact: false,
		collapsed: false,	
		collapsibleGroups: true
	}).addTo(map);
}
