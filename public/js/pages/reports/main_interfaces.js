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

function checkIntegerOnDecimal(value) {
	if (value % 1 == 0) value = Math.round(value);
	else value = value.toFixed(2);
	return value;
}

$(window).on('load', function() {
    /** calculates riesgo of all interfaces */	
    $.ajax({
		url: window.location.origin + '/admin/reportes/interfaces/riesgo',
		type: 'GET',
		success: function(response) {
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

			/** variables to limpieza techos and residuos */
			let totalInterfaces = response['interfaces']['total'];
			let meanLimpiezaTechos = response['interfaces']['limpieza_techo'].reduce(function(pv, cv) { return pv + parseInt(cv); }, 0);
			let meanResiduosAgricolas = response['interfaces']['residuos_agricolas'].reduce(function(pv, cv) { return pv + parseInt(cv); }, 0);
			let meanResiduosForestales = response['interfaces']['residuos_forestales'].reduce(function(pv, cv) { return pv + parseInt(cv); }, 0);
			let meanResiduosDomesticos = response['interfaces']['residuos_domesticos'].reduce(function(pv, cv) { return pv + parseInt(cv); }, 0);
			let meanResiduosIndustriales = response['interfaces']['residuos_industriales'].reduce(function(pv, cv) { return pv + parseInt(cv); }, 0);

			let colors = ['#44bd00', '#ffbb00', '#ff7300', '#ff1b19'];

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
			response['pendiente'].forEach(element => {
				if (element.gridcode == 1) pen_percentage_clase_1 = pen_percentage_clase_1 + parseFloat(element.pc_superficie);
				else if (element.gridcode == 2) pen_percentage_clase_2 = pen_percentage_clase_2 + parseFloat(element.pc_superficie);
				else if (element.gridcode == 3) pen_percentage_clase_3 = pen_percentage_clase_3 + parseFloat(element.pc_superficie);
				pen_total = pen_total + 1;
			});
			pen_percentage_clase_1 = (pen_percentage_clase_1 / totalInterfaces) * 1;
			pen_percentage_clase_2 = (pen_percentage_clase_2 / totalInterfaces) * 2;
			pen_percentage_clase_3 = (pen_percentage_clase_3 / totalInterfaces) * 3;
			pen_average = Math.round((pen_percentage_clase_1 + pen_percentage_clase_2 + pen_percentage_clase_3) / pen_total);
			res_pen_average = getSlopeScore(pen_average);
			
			/** combustible vegetación combustible */
			for (let key in response['combustible']) {
                res_combustible = res_combustible + (parseInt(response['combustible'][key][1]) * (parseFloat(response['combustible'][key][0]) / 100));
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
			
			/** calculates mean of limpieza techos and residuos */
			meanLimpiezaTechos = meanLimpiezaTechos / totalInterfaces;
			meanResiduosAgricolas = meanResiduosAgricolas / totalInterfaces;
			meanResiduosForestales = meanResiduosForestales / totalInterfaces;
			meanResiduosDomesticos = meanResiduosDomesticos / totalInterfaces;
			meanResiduosIndustriales = meanResiduosIndustriales / totalInterfaces;
			
			if (res_pen_average == null) res_pen_average = 0;
			if (res_combustible == null) res_combustible = 0;
			if (res_pbl_density == null) res_pbl_density = 0;

			if ((res_prevalence != null) && (res_pen_average != null) && (res_combustible != null) && (res_pbl_density != null) && (!isNaN(res_techo))) {
				let res_desechos = 0.5*meanResiduosAgricolas + 0.5*meanResiduosForestales;
				let res_basuras = 0.5*meanResiduosDomesticos + 0.5*meanResiduosIndustriales;
				let res_otros_comb = 0.5*res_desechos + 0.5*res_basuras;
				let amenaza = Math.round(0.5*res_prevalence + 0.25*res_pen_average + 0.25*res_combustible);
				let vulnerabilidad = Math.round(0.2*res_pbl_density + 0.1*res_techo + 0.4*res_otros_comb + 0.3*meanLimpiezaTechos);
				if (amenaza < 0) amenaza = 0;
				if (vulnerabilidad < 0) vulnerabilidad = 0;
				
				let riesgo = Math.round(0.5*amenaza + 0.5*vulnerabilidad);
				if (riesgo < 0) riesgo = 0;
                $('#val-amenaza-interfaces').html(amenaza);
                $('#val-vulnerabilidad-interfaces').html(vulnerabilidad);

				if (riesgo <= 25) {
					$('#val-riesgo-interfaces').trigger('configure', {
						'fgColor': colors[0],
						'min': 0,
						'max': 100
					}).val(riesgo).trigger('change');
					$('#val-riesgo-interfaces').css({ 'color': colors[0] });
				} else if (riesgo > 25 && riesgo <= 50) {
					$('#val-riesgo-interfaces').trigger('configure', {
						'fgColor': colors[1],
						'min': 0,
						'max': 100
					}).val(riesgo).trigger('change');
					$('#val-riesgo-interfaces').css({ 'color': colors[1] });
				} else if (riesgo > 50 && riesgo <= 75) {
					$('#val-riesgo-interfaces').trigger('configure', {
						'fgColor': colors[2],
						'min': 0,
						'max': 100
					}).val(riesgo).trigger('change');
					$('#val-riesgo-interfaces').css({ 'color': colors[2] });
				} else if (riesgo > 75) {
					$('#val-riesgo-interfaces').trigger('configure', {
						'fgColor': colors[3],
						'min': 0,
						'max': 100
					}).val(riesgo).trigger('change');
					$('#val-riesgo-interfaces').css({ 'color': colors[3] });
				}
			}
		},
		error: function(xhr, textStatus, error){
			console.log(xhr.statusText);
			console.log(textStatus);
			console.log(error);
		}
	});

    /** gets data of Prevalencia interfaces */
    $.ajax({
		url: window.location.origin + '/admin/reportes/interfaces/riesgo/prevalencia',
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
			let valuePrevalencia = 0;
			
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

	/** gets data of Pendiente interfaces */
	$.ajax({
		url: window.location.origin + '/admin/reportes/interfaces/riesgo/pendiente',
		type: 'GET',
		success: function(response) {
			let totalInterfaces = response['interfaces'];
			let pendienteClase1 = 0;
            let pendienteClase2 = 0;
            let pendienteClase3 = 0;

			response['pendientes'].forEach(element => {
				if (element.gridcode == 1) pendienteClase1 = pendienteClase1 + parseFloat(element.pc_superficie);
				else if (element.gridcode == 2) pendienteClase2 = pendienteClase2 + parseFloat(element.pc_superficie);
				else if (element.gridcode == 3) pendienteClase3 = pendienteClase3 + parseFloat(element.pc_superficie);
			});
			pendienteClase1 = pendienteClase1 / totalInterfaces;
			pendienteClase2 = pendienteClase2 / totalInterfaces;
			pendienteClase3 = pendienteClase3 / totalInterfaces;

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
		},
		error: function(xhr, textStatus, error){
			console.log(xhr.statusText);
			console.log(textStatus);
			console.log(error);
		}		
	});
});

function getDetailsGrupoCausas(btn) {
    let data = btn.value.split(',');
    let grupoCausa = data[0];
    let grupoEspecifica = data[1];

    $.ajax({
        url: window.location.origin + '/admin/reportes/interfaces/causas/grupos', 
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