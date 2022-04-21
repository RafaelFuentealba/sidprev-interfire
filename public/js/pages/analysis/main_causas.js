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
		controlCustomLegends.options.content = '<img src="'+ window.location.origin +':8080/geoserver/interfire/wms?REQUEST=GetLegendGraphic&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=Causas_Gral_2020_2021" style="display: block;">';
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

    let interfaz = window.location.pathname.split("/").pop();
	let urlRiesgo = null;
	if (isNaN(interfaz)) urlRiesgo = window.location.origin + '/admin/analisis/interfaz/riesgo';
	else urlRiesgo = window.location.origin + '/admin/gestion/interfaz/'+ interfaz +'/riesgo/forma';

    $.ajax({
		url: urlRiesgo,
		type: 'GET',
		success: function(response) {
			let shapeToPrint = JSON.parse(response.interfaz_forma);
			let setLatLng = new L.LatLng(shapeToPrint[1][1], shapeToPrint[1][0]);
			let newShape = new L.circle(setLatLng, {radius: shapeToPrint[2]*100000});
			map.setView(setLatLng, 15);
			newShape.addTo(map);
		},
		error: function(xhr, textStatus, error){
			console.log(xhr.statusText);
			console.log(textStatus);
			console.log(error);
		}
	});

    let urlCausas = null;
	if (isNaN(interfaz)) urlCausas = window.location.origin + '/admin/analisis/interfaz/causas';
	else urlCausas = window.location.origin + '/admin/gestion/interfaz/'+ interfaz +'/causas/temporadas';

	$.ajax({
		url: urlCausas,
		type: 'GET',
		success: function(response) {
            let yearsQuinquenio = ['2015', '2016', '2017', '2018', '2019'];
			let uniqueYearsQuinquenio = [];
			let accidentalesQuinquenio = 0;
			let intencionalesQuinquenio = 0;
			let desconocidasQuinquenio = 0;
			let naturalesQuinquenio = 0;
			let accidentalesUltima = 0;
			let intencionalesUltima = 0;
			let desconocidasUltima = 0;
			let naturalesUltima = 0;
            let totalQuinquenio = response.quinquenio.length;
            let totalUltima = response.ultima_temporada.length;
            
            response.quinquenio.forEach(element => {
				for (let index = 0; index < yearsQuinquenio.length; index++) {
					if (element.temporada.match(yearsQuinquenio[index]) && !uniqueYearsQuinquenio.includes(yearsQuinquenio[index])) uniqueYearsQuinquenio.push(yearsQuinquenio[index]);
				}
			});
			response.quinquenio.forEach(element => {
				if (element.id_especifico.charAt(0) == 1) accidentalesQuinquenio = accidentalesQuinquenio + 1;
				if (element.id_especifico.charAt(0) == 2) intencionalesQuinquenio = intencionalesQuinquenio + 1;
				if (element.id_especifico.charAt(0) == 4) desconocidasQuinquenio = desconocidasQuinquenio + 1;
				if (element.id_especifico.charAt(0) == 3) naturalesQuinquenio = naturalesQuinquenio + 1;
			});
			response.ultima_temporada.forEach(element => {
				if (element.id_especifico.charAt(0) == 1) accidentalesUltima = accidentalesUltima + 1;
				if (element.id_especifico.charAt(0) == 2) intencionalesUltima = intencionalesUltima + 1;
				if (element.id_especifico.charAt(0) == 4) desconocidasUltima = desconocidasUltima + 1;
				if (element.id_especifico.charAt(0) == 3) naturalesUltima = naturalesUltima + 1;
			});

            let promedioAccidentalesQuinquenio = accidentalesQuinquenio / uniqueYearsQuinquenio.length;
            let promedioIntencionalesQuinquenio = intencionalesQuinquenio / uniqueYearsQuinquenio.length;
            let promedioDesconocidasQuinquenio = desconocidasQuinquenio / uniqueYearsQuinquenio.length;
            let promedioNaturalesQuinquenio = naturalesQuinquenio / uniqueYearsQuinquenio.length;
            
            c3.generate({
                bindto: '#chart-causas-quinquenio-1', // id of chart wrapper
                data: {
                    columns: [
                        // each columns data
                        ['data1', promedioAccidentalesQuinquenio.toFixed(2), promedioIntencionalesQuinquenio.toFixed(2), promedioDesconocidasQuinquenio.toFixed(2), promedioNaturalesQuinquenio.toFixed(2)],
                    ],
                    type: 'bar', // default type of chart
                    colors: {
                        'data1': Aero.colors["green"],
                    },
                    names: {
                        // name of each serie
                        'data1': 'Promedio quinquenio',
                    }
                },
                axis: {
                    x: {
                        type: 'category',
                        // name of each category
                        categories: ['Accidentales', 'Intencionales', 'Desconocidas', 'Naturales']
                    },
                    /*y: {
                        'label': {
                            'text': 'cantidad'
                        }
                    },*/
                    rotated: true,
                },
                bar: {
                    width: 16
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
                bindto: '#chart-causas-quinquenio-2', // id of chart wrapper
                data: {
                    columns: [
                        // each columns data
                        ['data1', ((accidentalesQuinquenio/totalQuinquenio)*100).toFixed(2)],
                        ['data2', ((intencionalesQuinquenio/totalQuinquenio)*100).toFixed(2)],
                        ['data3', ((desconocidasQuinquenio/totalQuinquenio)*100).toFixed(2)],
                        ['data4', ((naturalesQuinquenio/totalQuinquenio)*100).toFixed(2)]
                    ],
                    type: 'donut', // default type of chart
                    colors: {
                        'data1': Aero.colors["blue"],
                        'data2': Aero.colors["yellow"],
                        'data3': Aero.colors["red"],
                        'data4': Aero.colors["green"]
                    },
                    names: {
                        // name of each serie
                        'data1': 'Accidentales',
                        'data2': 'Intencionales',
                        'data3': 'Desconocidas',
                        'data4': 'Naturales'
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

            c3.generate({
                bindto: '#chart-causas-ultima-1', // id of chart wrapper
                data: {
                    columns: [
                        // each columns data
                        ['data2', accidentalesUltima, intencionalesUltima, desconocidasUltima, naturalesUltima]
                    ],
                    type: 'bar', // default type of chart
                    colors: {
                        'data2': Aero.colors["red"]
                    },
                    names: {
                        // name of each serie
                        'data2': 'Cantidad última temporada'
                    }
                },
                axis: {
                    x: {
                        type: 'category',
                        // name of each category
                        categories: ['Accidentales', 'Intencionales', 'Desconocidas', 'Naturales']
                    },
                    /*y: {
                        'label': {
                            'text': 'cantidad'
                        }
                    },*/
                    rotated: true,
                },
                bar: {
                    width: 16
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
                bindto: '#chart-causas-ultima-2', // id of chart wrapper
                data: {
                    columns: [
                        // each columns data
                        ['data1', ((accidentalesUltima/totalUltima)*100).toFixed(2)],
                        ['data2', ((intencionalesUltima/totalUltima)*100).toFixed(2)],
                        ['data3', ((desconocidasUltima/totalUltima)*100).toFixed(2)],
                        ['data4', ((naturalesUltima/totalUltima)*100).toFixed(2)]
                    ],
                    type: 'donut', // default type of chart
                    colors: {
                        'data1': Aero.colors["blue"],
                        'data2': Aero.colors["yellow"],
                        'data3': Aero.colors["red"],
                        'data4': Aero.colors["green"]
                    },
                    names: {
                        // name of each serie
                        'data1': 'Accidentales',
                        'data2': 'Intencionales',
                        'data3': 'Desconocidas',
                        'data4': 'Naturales'
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

            c3.generate({
                bindto: '#chart-causas-together-1', // id of chart wrapper
                data: {
                    columns: [
                        // each columns data
                        ['data1', promedioAccidentalesQuinquenio, promedioIntencionalesQuinquenio, promedioDesconocidasQuinquenio, promedioNaturalesQuinquenio],
                        ['data2', accidentalesUltima, intencionalesUltima, desconocidasUltima, naturalesUltima]
                    ],
                    type: 'bar', // default type of chart
                    colors: {
                        'data1': Aero.colors["green"],
                        'data2': Aero.colors["red"]
                    },
                    names: {
                        // name of each serie
                        'data1': 'Promedio quinquenio',
                        'data2': 'Cantidad última temporada'
                    }
                },
                axis: {
                    x: {
                        type: 'category',
                        // name of each category
                        categories: ['Accidentales', 'Intencionales', 'Desconocidas', 'Naturales']
                    },
                    /*y: {
                        'label': {
                            'text': 'cantidad'
                        }
                    },*/
                    rotated: true,
                },
                bar: {
                    width: 16
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
                bindto: '#chart-causas-together-2', // id of chart wrapper
                data: {
                    columns: [
                        // each columns data
                        ['data1', ((accidentalesQuinquenio/totalQuinquenio)*100).toFixed(2), ((intencionalesQuinquenio/totalQuinquenio)*100).toFixed(2), ((desconocidasQuinquenio/totalQuinquenio)*100).toFixed(2), ((naturalesQuinquenio/totalQuinquenio)*100).toFixed(2)], // en porcentaje
                        ['data2', ((accidentalesUltima/totalUltima)*100).toFixed(2), ((intencionalesUltima/totalUltima)*100).toFixed(2), ((desconocidasUltima/totalUltima)*100).toFixed(2), ((naturalesUltima/totalUltima)*100).toFixed(2)] // en porcentaje
                    ],
                    type: 'bar', // default type of chart
                    colors: {
                        'data1': Aero.colors["yellow"],
                        'data2': Aero.colors["blue"]
                    },
                    names: {
                        // name of each serie
                        'data1': 'Porcentaje quinquenio',
                        'data2': 'Porcentaje última temporada'
                    }
                },
                axis: {
                    x: {
                        type: 'category',
                        // name of each category
                        categories: ['Accidentales', 'Intencionales', 'Desconocidas', 'Naturales']
                    },
                    /*y: {
                        'label': {
                            'text': 'porcentaje (%)'
                        }
                    },*/
                    rotated: true,
                },
                bar: {
                    width: 16
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
				
	overLayers = [
		{	
			group: "Indicadores Interfire",
			collapsed: false,
			layers: [
				{
					active: overlayers[0],
					name: "Causas 2020 - 2021",
					icon: '<i class="zmdi zmdi-help-outline"></i>',
					layer: wmsCausas2020
				},
				{
					active: overlayers[1],
					name: "Causas Quinquenio",
					icon: '<i class="zmdi zmdi-help-outline"></i>',
					layer: wmsCausasQuinquenio
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
