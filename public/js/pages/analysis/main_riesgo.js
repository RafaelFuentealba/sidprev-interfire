var latitude = -35.67;
var longitude = -71.54;
var zoom = 10;

/** maps and controls */
var map, openstreetmap, Opentopomap, Carto_voyager, Stamen_Terrain, osm_monocromatico, google_maps, google_terrain;
var sidebar, searchControl, results;

/** layers of the database */
var wmsIncendios2020, wmsIncendiosQuinquenio, wmsCausas2020, vmsCausasQuinquenio,
vmsHeatmap2020, vmsHeatmapQuinquenio, vmsComunas, vmsDensidadPob, vmsDensidadViv;

/** panel of layers control */
var panelLayers, baseLayers, overLayers;

/** DOM elements of the map */
var marker;
var aPopup = [];
var aGeomanLayers = [];
var legend = L.control({position: 'bottomright'});
var controlCustomButtons, controlCustomLegends;

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


/** create the control custom */
L.control.custom({
    position: 'bottomleft',
    content : '<button type="button" id="btn-layers" class="btn btn-success" title="Grupo de capas">'+
			  '    <i class="zmdi zmdi-layers"></i>'+
	          '</button>'+
			  '<button type="button" id="btn-legends" class="btn btn-info" title="Ver leyendas">'+
              '    <i class="zmdi zmdi-info-outline"></i>'+
              '</button>'+
			  '<button type="button" id="btn-close" class="btn btn-danger" title="Cerrar todo">'+
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

$('#btn-legends').on('click', function() {
	$('.leaflet-panel-layers').hide();

	layers = getActiveOverlayers();
	if (layers[0] == true || layers[1] == true) {
		controlCustomLegends.options.content = '<img src="'+ window.location.origin +':8080/geoserver/interfire/wms?REQUEST=GetLegendGraphic&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=Incendios_2020_2021" style="display: block;">';
		controlCustomLegends.addTo(map);
	} else {
		controlCustomLegends.options.content = '';
		controlCustomLegends.addTo(map);
	}
});

$('#btn-layers').on('click', function() {
	$('.leaflet-panel-layers').show();
	controlCustomLegends.options.content = '';
	controlCustomLegends.addTo(map);
});

$('#btn-close').on('click', function() {
	$('.leaflet-panel-layers').hide();
	controlCustomLegends.options.content = '';
	controlCustomLegends.addTo(map);
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

function checkIntegerOnDecimal(value) {
	if (value % 1 == 0) value = Math.round(value);
	else value = value.toFixed(2);
	return value;
}

$(window).on('load', function() {
	getPanelLayers([true, true]);
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

	/** get data of interfaz to print shape on map */
	let interfaz = window.location.pathname.split("/").pop();
	let urlRiesgo = null;
	if (interfaz % 1 != 0) urlRiesgo = window.location.origin + '/admin/analisis/interfaz/riesgo';
	else urlRiesgo = window.location.origin + '/admin/gestion/interfaz/'+ interfaz +'/riesgo/forma';
    $.ajax({
		url: urlRiesgo,
		type: 'GET',
		success: function(response) {
			let shapeToPrint = JSON.parse(response.interfaz_forma);
			if (shapeToPrint[0] == 'Circle') {
				let setLatLng = new L.LatLng(shapeToPrint[1][1], shapeToPrint[1][0]);
				let newShape = new L.circle(setLatLng, {radius: shapeToPrint[2]*100000});
				map.setView(setLatLng, 15);
				newShape.addTo(map);
			}
		},
		error: function(xhr, textStatus, error){
			console.log(xhr.statusText);
			console.log(textStatus);
			console.log(error);
		}
	});
	
	/** get data of Prevalencia */
	let urlPrevalencia = null;
	if (interfaz % 1 != 0) urlPrevalencia = window.location.origin + '/admin/analisis/interfaz/riesgo/prevalencia';
	else urlPrevalencia = window.location.origin + '/admin/gestion/interfaz/'+ interfaz +'/riesgo/prevalencia';
	
	$.ajax({
		url: urlPrevalencia,
		type: 'GET',
		success: function(response) {
			let yearsQuinquenio = 5;
			let ocurrenciaQuinquenio = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
			let ocurrenciaUltima = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
			let danoQuinquenio = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
			let danoUltima = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
			let months = ['Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'];
			let sumOcurrenciaQuinquenio = 0;
			let sumOcurrenciaUltima = 0;
			var sumDanoQuinquenio = 0;
			let sumDanoUltima = 0;
			let percentageOcurrencia = 0;
			let percentageDano = 0;
			let valuePrevalencia = null;
			
			response.quinquenio.forEach(element => {
				for (let index = 0; index < months.length; index++) {
					if (element.mes_ocurre.match(months[index])) ocurrenciaQuinquenio[index] = ocurrenciaQuinquenio[index] + 1;
				}
			});
			response.ultima_temporada.forEach(element => {
				for (let index = 0; index < months.length; index++) {
					if (element.mes_ocurre.match(months[index])) ocurrenciaUltima[index] = ocurrenciaUltima[index] + 1;
				}
			});
			response.quinquenio.forEach(element => {
				for (let index = 0; index < months.length; index++) {
					if (element.mes_ocurre.match(months[index])) danoQuinquenio[index] = danoQuinquenio[index] + parseFloat(element.sup_total);
				}
			});
			response.ultima_temporada.forEach(element => {
				for (let index = 0; index < months.length; index++) {
					if (element.mes_ocurre.match(months[index])) danoUltima[index] = danoUltima[index] + parseFloat(element.sup_total);
				}
			});
			for (let index = 0; index < months.length; index++) {
				ocurrenciaQuinquenio[index] = parseFloat((ocurrenciaQuinquenio[index] / yearsQuinquenio).toFixed(2));
				danoQuinquenio[index] = parseFloat((danoQuinquenio[index] / yearsQuinquenio).toFixed(2));
				danoUltima[index] = parseFloat(danoUltima[index].toFixed(2));
			}
			sumOcurrenciaQuinquenio = ocurrenciaQuinquenio.reduce(function(pv, cv) { if (isNaN(cv)) return pv + 0; else return pv + parseFloat(cv); }, 0);
			sumOcurrenciaUltima = ocurrenciaUltima.reduce(function(pv, cv) { if (isNaN(cv)) return pv + 0; else return pv + parseInt(cv); }, 0);
			sumDanoQuinquenio = danoQuinquenio.reduce(function(pv, cv) { if (isNaN(cv)) return pv + 0; else return pv + parseFloat(cv); }, 0);
			sumDanoUltima = danoUltima.reduce(function(pv, cv) { if (isNaN(cv)) return pv + 0; else return pv + parseFloat(cv); }, 0);
			
			if (sumOcurrenciaQuinquenio == 0) percentageOcurrencia = 0;
			else percentageOcurrencia = Math.round(((sumOcurrenciaUltima - sumOcurrenciaQuinquenio) / sumOcurrenciaQuinquenio)*100);

			if (sumDanoQuinquenio == 0) percentageDano = 0;
			else percentageDano = Math.round(((sumDanoUltima - sumDanoQuinquenio) / sumDanoQuinquenio)*100);
			
			sumOcurrenciaQuinquenio = checkIntegerOnDecimal(sumOcurrenciaQuinquenio);
			sumDanoQuinquenio = checkIntegerOnDecimal(sumDanoQuinquenio);
			sumDanoUltima = checkIntegerOnDecimal(sumDanoUltima);
			valuePrevalencia = getPrevalenceScore(percentageOcurrencia, percentageDano);
			
			$('#val-ocurrencia-quinquenio').text(sumOcurrenciaQuinquenio);
			$('#val-ocurrencia-ultima').text(sumOcurrenciaUltima);
			$('#val-dano-quinquenio').text(sumDanoQuinquenio + ' ha');
			$('#val-dano-ultima').text(sumDanoUltima + ' ha');
			percentageOcurrencia = Math.round(percentageOcurrencia);
			percentageDano = Math.round(percentageDano);
			
			let colors = ['#44bd00', '#ffbb00', '#ff7300', '#ff1b19']
			/** sets value of prevalencia and complete knob object with colors */
			if (valuePrevalencia <= -50) {
				$('#val-prevalencia').trigger('configure', {
					'fgColor': colors[0],
					'min': -100,
					'max': 100
				}).val(valuePrevalencia).trigger('change');
				$('#val-prevalencia').css({ 'color': colors[0] });
			} else if (valuePrevalencia > -50 && valuePrevalencia <= 0) {
				$('#val-prevalencia').trigger('configure', {
					'fgColor': colors[1],
					'min': -100,
					'max': 100
				}).val(valuePrevalencia).trigger('change');
				$('#val-prevalencia').css({ 'color': colors[1] });
			} else if (valuePrevalencia > 0 && valuePrevalencia <= 50) {
				$('#val-prevalencia').trigger('configure', {
					'fgColor': colors[2],
					'min': -100,
					'max': 100
				}).val(valuePrevalencia).trigger('change');
				$('#val-prevalencia').css({ 'color': colors[2] });
			} else if (valuePrevalencia > 50) {
				$('#val-prevalencia').trigger('configure', {
					'fgColor': colors[3],
					'min': -100,
					'max': 100
				}).val(valuePrevalencia).trigger('change');
				$('#val-prevalencia').css({ 'color': colors[3] });
			}
			
			/** sets value of ocurrencia and complete knob object with colors */
			$('#val-ocurrencia').val(percentageOcurrencia);
			if (percentageOcurrencia <= 25) $('#val-ocurrencia').css({ 'color': colors[0] });
			else if (percentageOcurrencia > 25 && percentageOcurrencia <= 50) $('#val-ocurrencia').css({ 'color': colors[1] });
			else if (percentageOcurrencia > 50 && percentageOcurrencia <= 75) $('#val-ocurrencia').css({ 'color': colors[2] });
			else if (percentageOcurrencia > 75) $('#val-ocurrencia').css({ 'color': colors[3] });

			/** sets value of daño and complete knob object with colors */
			$('#val-dano').val(percentageDano);
			if (percentageDano <= 25) $('#val-dano').css({ 'color': colors[0] });
			else if (percentageDano > 25 && percentageDano <= 50) $('#val-dano').css({ 'color': colors[1] });
			else if (percentageDano > 50 && percentageDano <= 75) $('#val-dano').css({ 'color': colors[2] });
			else if (percentageDano > 75) $('#val-dano').css({ 'color': colors[3] });

			/** sets graphics of ocurrencia and daño */
			ocurrenciaQuinquenio.unshift('data1');
			ocurrenciaUltima.unshift('data2');
			danoQuinquenio.unshift('data1');
			danoUltima.unshift('data2');

			c3.generate({
                bindto: '#chart-ocurrencia', // id of chart wrapper
                data: {
                    columns: [
                        // each columns data
                        ocurrenciaQuinquenio,
                        ocurrenciaUltima
                    ],
                    type: 'area', // default type of chart
                    colors: {
                        'data1': Aero.colors["red"],
                        'data2': Aero.colors["green"]
                    },
                    names: {
                        // name of each serie
                        'data1': 'Promedio quinquenio',
                        'data2': 'Última temporada'
                    }
                },
                axis: {
                    x: {
                        type: 'category',
                        // name of each category
                        categories: ['Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun']
                    },
                },
                legend: {
                    show: true, //hide legend
                },
                padding: {
                    bottom: 0,
                    top: 0
                },
            });
			c3.generate({
                bindto: '#chart-dano', // id of chart wrapper
                data: {
                    columns: [
                        // each columns data
                        danoQuinquenio,
                        danoUltima
                    ],
                    type: 'area', // default type of chart
                    colors: {
                        'data1': Aero.colors["orange"],
                        'data2': Aero.colors["blue"]
                    },
                    names: {
                        // name of each serie
                        'data1': 'Promedio quinquenio',
                        'data2': 'Última temporada'
                    }
                },
                axis: {
                    x: {
                        type: 'category',
                        // name of each category
                        categories: ['Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun']
                    },
					y: {
                        'label': {
                            'text': 'hectáreas'
                        }
                    }
                },
                legend: {
                    show: true, //hide legend
                },
                padding: {
                    bottom: 0,
                    top: 0
                },
            });
		},
		error: function(xhr, textStatus, error){
			console.log(xhr.statusText);
			console.log(textStatus);
			console.log(error);
		}
	});

	/** get data of Pendiente */
	let urlPendiente = null;
	if (interfaz % 1 != 0) urlPendiente = window.location.origin + '/admin/analisis/interfaz/riesgo/pendiente';
	else urlPendiente = window.location.origin + '/admin/gestion/interfaz/'+ interfaz +'/riesgo/pendiente';

	$.ajax({
		url: urlPendiente,
		type: 'GET',
		success: function(response) {
			let dataPendiente = JSON.parse(response.pendiente);
			let pendienteTotal = dataPendiente.length;
			let pendienteClase1 = 0;
            let pendienteClase2 = 0;
            let pendienteClase3 = 0;

			if (pendienteTotal == 0) {
				c3.generate({
					bindto: '#chart-pendiente', // id of chart wrapper
					data: {
						columns: [
							// each columns data
							['data1', 100]
						],
						type: 'donut', // default type of chart
						colors: {
							'data1': Aero.colors["gray"]
						},
						names: {
							'data1': 'Sin datos'
						}
					},
					axis: {
					},
					legend: {
						show: true, //hide legend
					},
					padding: {
						bottom: 0,
						top: 0
					},
				});
			} else {
				dataPendiente.forEach(element => {
					if (element.gridcode == 1) pendienteClase1 = pendienteClase1 + parseFloat(element.pc_superficie);
					else if (element.gridcode == 2) pendienteClase2 = pendienteClase2 + parseFloat(element.pc_superficie);
					else if (element.gridcode == 3) pendienteClase3 = pendienteClase3 + parseFloat(element.pc_superficie);
				});
				
				c3.generate({
					bindto: '#chart-pendiente', // id of chart wrapper
					data: {
						columns: [
							// each columns data
							['data1', pendienteClase1.toFixed(2)],
							['data2', pendienteClase2.toFixed(2)],
							['data3', pendienteClase3.toFixed(2)]
						],
						type: 'donut', // default type of chart
						colors: {
							'data1': Aero.colors["green"],
							'data2': Aero.colors["yellow"],
							'data3': Aero.colors["red"]
						},
						names: {
							// name of each serie
							'data1': 'Baja',
							'data2': 'Media',
							'data3': 'Alta'
						}
					},
					axis: {
					},
					legend: {
						show: true, //hide legend
					},
					padding: {
						bottom: 0,
						top: 0
					},
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

function getActiveOverlayers() {
	var overlayers = $('input.leaflet-panel-layers-selector:checkbox');
	var overlayersID = [];
	for (var layer of overlayers) {
		overlayersID.push(layer.checked);
	}
	return overlayersID;
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