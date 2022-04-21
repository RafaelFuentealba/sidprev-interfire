$('#btn-update-interfaz').on('click', function() {
    $('#form-update-interfaz').validate({
		rules: {
			interfazName: {
                required: true,
                maxlength: 255
            }
		},
		messages: {
			interfazName: {
				required: 'Este dato es requerido',
				maxlength: 'El dato excede los 255 caracteres'
			}
		},
		submitHandler: function(form) {
			let limpiezaTecho = $('#sel-limpieza-techo').val();
			let residuosAgricolas = $('#sel-residuos-agricolas').val();
			let residuosForestales = $('#sel-residuos-forestales').val();
			let residuosDomesticos = $('#sel-residuos-domesticos').val();
			let residuosIndustriales = $('#sel-residuos-industriales').val();
			let nombre = $('#interfaz-name').val();
            let interfaz = $('#id-update-interfaz').val();
            
			if ((limpiezaTecho !== null) && (residuosAgricolas !== null) && (residuosForestales !== null) && (residuosDomesticos !== null) && (residuosIndustriales !== null) && (nombre !== '')) {
				$('#fail-update-interfaz').css({ 'display':'none' });
				let res_desechos = 0.5*residuosAgricolas + 0.5*residuosForestales;
				let res_basuras = 0.5*residuosDomesticos + 0.5*residuosIndustriales;
				let res_otros_comb = 0.5*res_desechos + 0.5*res_basuras;
				let riesgo = 0;
				let vulnerabilidad = 0;
				
				let actualRiesgo = $('#riesgo-actual-interfaz').val();
				let actualVulnerabilidad = $('#vulnerabilidad-actual-interfaz').val();
				let actualLimpiezaTechos = $('#res-limpieza-techo-interfaz').val();
				let actualResAgricolas = $('#res-agricolas-interfaz').val();
				let actualResForestales = $('#res-forestales-interfaz').val();
				let actualResDomesticos = $('#res-domesticos-interfaz').val();
				let actualResIndustriales = $('#res-industriales-interfaz').val();
				let actualResDesechos = 0.5*actualResAgricolas + 0.5*actualResForestales;
				let actualResBasuras = 0.5*actualResDomesticos + 0.5*actualResIndustriales;
				let actualCombustibles = 0.5*actualResDesechos + 0.5*actualResBasuras;
				
				/** set riesgo and vulnerabilidad to original values */
				actualRiesgo = actualRiesgo - (0.5*actualVulnerabilidad);
				actualVulnerabilidad = actualVulnerabilidad - (0.4*actualCombustibles + 0.3*actualLimpiezaTechos);
				
				/** update riesgo and vulnerabilidad */
				vulnerabilidad = Math.round(actualVulnerabilidad + (0.4*res_otros_comb + 0.3*limpiezaTecho));
				riesgo = Math.round(actualRiesgo + 0.5*vulnerabilidad);
				if (riesgo < 0) riesgo = 0;
				
				$.ajax({
					url: window.location.origin + '/admin/gestion/actualizar/interfaz', 
					type: 'POST',    
					data: {
						'interfaz-id': interfaz,
						'interfaz-nombre': nombre,
						'interfaz-riesgo': riesgo,
						'interfaz-vulnerabilidad': vulnerabilidad,
						'limpieza-techo': limpiezaTecho,
						'residuos-agricolas': residuosAgricolas,
						'residuos-forestales': residuosForestales,
						'residuos-domesticos': residuosDomesticos,
						'residuos-industriales': residuosIndustriales
					},
					success: function(response) {
						if (response == 1) {
							swal({
								title: "¡Excelente!",
								text: "La interfaz ha sido actualizada correctamente.",								
								buttons: {
									confirm: true,
								}
							}).then(function() {
								window.location.href = window.location.origin + '/admin/gestion/listar'
							});
						} else {
							swal({
								title: "¡Algo salió mal!",
								text: "No se pudo actualizar la interfaz. Inténtelo más tarde.",
								buttons: {
									confirm: true,
								}
							}).then(function() {
								window.location.href = window.location.origin + '/admin/gestion/listar'
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
			else {
				$('#fail-update-interfaz').css({ 'display':'block' });
			}
		}
	});	
});