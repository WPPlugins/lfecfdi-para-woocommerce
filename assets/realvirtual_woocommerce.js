jQuery(document).ready( function($) 
{
	$('#paso_dos').hide();
	$('#paso_tres').hide();
	$('#paso_cuatro').hide();
	$('#paso_cinco').hide();
	
	try
	{
		var modalConfiguracion = document.getElementById('ventanaModalConfiguracion');
		var spanConfiguracion = document.getElementsByClassName('closeConfiguracion')[0];
		var botonConfiguracion = document.getElementById('botonModalConfiguracion');
		
		function mostrarVentanaConfiguracion(texto)
		{
			modalConfiguracion.style.display = "block";
			document.getElementById('tituloModalConfiguracion').innerHTML = 'Aviso';
			document.getElementById('textoModalConfiguracion').innerHTML = texto;
		}
		
		botonConfiguracion.onclick = function()
		{
			modalConfiguracion.style.display = "none";
			document.getElementById('tituloModalConfiguracion').innerHTML = '';
			document.getElementById('textoModalConfiguracion').innerHTML = '';
		}
		
		spanConfiguracion.onclick = function()
		{
			modalConfiguracion.style.display = "none";
			document.getElementById('tituloModalConfiguracion').innerHTML = '';
			document.getElementById('textoModalConfiguracion').innerHTML = '';
		}
	}
	catch(error)
	{
		
	}
	
	try
	{
		var modalCuenta = document.getElementById('ventanaModalCuenta');
		var spanCuenta = document.getElementsByClassName('closeCuenta')[0];
		var botonCuenta = document.getElementById('botonModalCuenta');
		
		function mostrarVentanaCuenta(texto, titulo)
		{
			modalCuenta.style.display = "block";
			document.getElementById('tituloModalCuenta').innerHTML = titulo;
			document.getElementById('textoModalCuenta').innerHTML = texto;
		}
		
		botonCuenta.onclick = function()
		{
			modalCuenta.style.display = "none";
			document.getElementById('tituloModalCuenta').innerHTML = '';
			document.getElementById('textoModalCuenta').innerHTML = '';
		}
		
		spanCuenta.onclick = function()
		{
			modalCuenta.style.display = "none";
			document.getElementById('tituloModalCuenta').innerHTML = '';
			document.getElementById('textoModalCuenta').innerHTML = '';
		}
	}
	catch(error)
	{
		
	}
	
	window.onclick = function(event)
	{
		if (event.target == modal)
		{
			modal.style.display = "none";
			document.getElementById('textoModal').innerHTML ='';
		}
		
		if (event.target == modalConfiguracion)
		{
			modalConfiguracion.style.display = "none";
			document.getElementById('textoModalConfiguracion').innerHTML ='';
		}
		
		if (event.target == modalCuenta)
		{
			modalCuenta.style.display = "none";
			document.getElementById('textoModalCuenta').innerHTML ='';
		}
    }
	
	var plugin_dir_url = '';
	var urlSistemaAsociado = '';
	var numero_pedido = '';
	var datosPedido = '';
	var arrayConceptos = new Array();
	var subtotal = '';
	var descuento = '';
	var total = '';
	var arrayImpuestosFederales = new Array();
	var xml = '';
	var CFDI_ID = '';
	
	if($('#realvirtual_woocommerce_cuenta').length)
	{
        $('#realvirtual_woocommerce_enviar_cuenta').click(function(event)
		{
            event.preventDefault();
			document.getElementById('cargandoCuenta').style.visibility = 'visible';
            
			var formularioValido = validarFormularioCuenta();

            if(formularioValido != '')
			{
				mostrarVentanaCuenta('<center>' + formularioValido + '</center>', 'Aviso');
				document.getElementById('cargandoCuenta').style.visibility = 'hidden';
                return false;
            }

            datosFormulario = $('#realvirtual_woocommerce_cuenta').serializeArray();
            
			data =
			{
				action      						: 'realvirtual_woocommerce_guardar_cuenta',
				rfc   								: datosFormulario[0].value,
				usuario   							: datosFormulario[1].value,
				clave       						: datosFormulario[2].value
            }

            $.post(myAjax.ajaxurl, data, function(response)
			{
				document.getElementById('cargandoCuenta').style.visibility = 'hidden';
				var response = JSON.parse(response);
				
				var EMISOR_RENOVACION = response.EMISOR_RENOVACION;
				var EMISOR_VIGENCIA = response.EMISOR_VIGENCIA;
				var EMISOR_ESTADO = response.EMISOR_ESTADO;
				var EMISOR_TIPO_USUARIO = response.EMISOR_TIPO_USUARIO;
				var sistema = response.sistema;
				var message = response.message;
				
				if(!response.success)
				{
					mostrarVentanaCuenta('<center>Ocurrió un error al guardar la cuenta:<br/><br/>' + message + '</center>', 'Aviso');
				}
				else
				{
					var estadoEmisor = '<b>VALIDACIÓN DE LA CUENTA</b><br/><font color="#515151" size="2">Tu RFC, Usuario y Clave Cifrada son correctos. Tu cuenta ha sido guardada con éxito.</font>';
					
					if(sistema == 'RVCFDI')
						estadoEmisor += '<br/><br/><b>ESTADO DE LA CUENTA</b><br/><font color="#515151" size="2"><b>Vigencia de timbrado</b>: Del ' + EMISOR_RENOVACION + ' al ' + EMISOR_VIGENCIA + ', <b>Estado: </b>' + EMISOR_ESTADO + ', <b>Tipo: </b>' + EMISOR_TIPO_USUARIO + '.</font>';
					else
						estadoEmisor += '<br/><br/><b>ESTADO DE LA CUENTA</b><br/><font color="#515151" size="2"><b>Estado: </b>' + EMISOR_ESTADO + ', <b>Tipo: </b>' + EMISOR_TIPO_USUARIO + '.</font>';
					
					estadoEmisor += '<br/><br/><b>CONFIGURACIÓN DEL PLUGIN</b><br/><font color="#515151" size="2">' + message + '</font>';
					
					mostrarVentanaCuenta(estadoEmisor, 'PROCESO COMPLETADO');
				}
            });
			
            return false;
        });
    }
	
	function validarFormularioCuenta()
	{
        var respuesta = false;
                
		var rfc = $('#realvirtual_woocommerce_cuenta').find('#rfc');
                
		if(rfc.val().length == 0)
			respuesta = "Ingresa el RFC.";

        var usuario = $('#realvirtual_woocommerce_cuenta').find('#usuario');
                
		if(usuario.val().length == 0)
			respuesta = "Ingresa el Usuario.";

		var clave = $('#realvirtual_woocommerce_cuenta').find('#clave');
                
		if(clave.val().length == 0)
			respuesta = "Ingresa la contraseña.";
		
        return respuesta;
    }
	
    if($('#realvirtual_woocommerce_configuracion').length)
	{
        $('#realvirtual_woocommerce_enviar_configuracion').click(function(event)
		{
            event.preventDefault();
			document.getElementById('cargandoConfiguracion').style.visibility = 'visible';
            
			var formularioValido = validarFormularioConfiguracion();

            if(formularioValido != '')
			{
				mostrarVentanaConfiguracion(formularioValido);
				document.getElementById('cargandoConfiguracion').style.visibility = 'hidden';
                return false;
            }

            datosFormulario = $('#realvirtual_woocommerce_configuracion').serializeArray();
            
			data =
			{
				action      									: 'realvirtual_woocommerce_guardar_configuracion',
				serie       									: datosFormulario[0].value,
				estado_orden   									: datosFormulario[1].value,
				titulo       									: datosFormulario[2].value,
				descripcion     								: datosFormulario[3].value,
				color_fondo_encabezado_hexadecimal    			: datosFormulario[4].value,
				color_texto_encabezado_hexadecimal      		: datosFormulario[6].value,
				color_fondo_formulario_hexadecimal    			: datosFormulario[8].value,
				color_texto_formulario_hexadecimal      		: datosFormulario[10].value,
				color_texto_controles_formulario_hexadecimal  	: datosFormulario[12].value,
				color_boton_hexadecimal       					: datosFormulario[14].value,
				color_texto_boton_hexadecimal       			: datosFormulario[16].value
            }

            $.post(myAjax.ajaxurl, data, function(response)
			{
				document.getElementById('cargandoConfiguracion').style.visibility = 'hidden';
				var response = JSON.parse(response);
				
				var message = response.message;
				
				if(!response.success)
				{
					mostrarVentanaConfiguracion('Ocurrió un error al guardar la configuración. ' + message);
				}
				else
				{
					mostrarVentanaConfiguracion('Configuración guardada con éxito.');
				}
            });
			
            return false;
        });
    }
	
    function validarFormularioConfiguracion()
	{
        var respuesta = false;
        
		var serie = $('#realvirtual_woocommerce_configuracion').find('#serie');
		
		if(serie.val().length == 0)
			respuesta = "Ingresa la serie.";	
		
		var estado_orden = $('#realvirtual_woocommerce_configuracion').find('#estado_orden');
		
		if(estado_orden.val().length == 0)
			respuesta = "Selecciona el estado de la orden.";	
		
		var color_fondo_encabezado_hexadecimal = $('#realvirtual_woocommerce_configuracion').find('#color_fondo_encabezado_hexadecimal');
		var color_texto_encabezado_hexadecimal = $('#realvirtual_woocommerce_configuracion').find('#color_texto_encabezado_hexadecimal');
		var color_fondo_formulario_hexadecimal = $('#realvirtual_woocommerce_configuracion').find('#color_fondo_formulario_hexadecimal');
		var color_texto_formulario_hexadecimal = $('#realvirtual_woocommerce_configuracion').find('#color_texto_formulario_hexadecimal');
		var color_texto_controles_formulario_hexadecimal = $('#realvirtual_woocommerce_configuracion').find('#color_texto_controles_formulario_hexadecimal');
		var color_boton_hexadecimal = $('#realvirtual_woocommerce_configuracion').find('#color_boton_hexadecimal');
		var color_texto_boton_hexadecimal = $('#realvirtual_woocommerce_configuracion').find('#color_texto_boton_hexadecimal');
		
		if(color_fondo_encabezado_hexadecimal.val().length == 0 ||
			color_texto_encabezado_hexadecimal.val().length == 0 ||
			color_fondo_formulario_hexadecimal.val().length == 0 ||
			color_texto_formulario_hexadecimal.val().length == 0 ||
			color_texto_controles_formulario_hexadecimal.val().length == 0 ||
			color_boton_hexadecimal.val().length == 0 ||
			color_texto_boton_hexadecimal.val().length == 0)
			respuesta = "Verifica que todos los colores estén establecidos.";	
		
        return respuesta;
    }

	function validarFormularioPaso1()
	{
        var numero_pedido = $("#numero_pedido");
        var monto_pedido  = $("#monto_pedido");
		
        if(numero_pedido.val().length == 0)
		{
			mostrarVentana('Ingresa el número del pedido.');
			return false;
		}
		
        if(monto_pedido.val().length == 0)
		{
			mostrarVentana('Ingresa el monto del pedido.');
			return false;
		}
		else
		{
			if(isNaN(monto_pedido.val()))
			{
				mostrarVentana('El monto del pedido es inválido.');
				return false;
			}
			else
			{
				if(monto_pedido.val() <= 0)
				{
					mostrarVentana('El monto del pedido debe ser mayor a cero.');
					return false;
				}
			}
        }
		
        return true;
    }
	
	function validarFormularioPaso2()
	{
		var receptor_rfc   = $("#receptor_rfc");
        var receptor_email = $("#receptor_email");
		var receptor_pais = $("#receptor_pais");
		
		if(receptor_rfc.val().length == 0)
		{
			mostrarVentana('Ingresa tu RFC.');
			return false;
		}
		
		if(!(receptor_rfc.val().length == 12 || receptor_rfc.val().length == 13))
		{
			mostrarVentana('El RFC debe tener 12 o 13 caracteres.');
			return false;
		}
		
		texto = receptor_rfc.val().toUpperCase();
		re = /^([A-Z]|&|Ñ){3,4}[0-9]{2}[0-1][0-9][0-3][0-9]([A-Z]|[0-9]){2}([0-9]|A){1}$/;
	   
		if (re.test(texto) == false)
		{
			mostrarVentana('El RFC tiene un formato inválido.');
			return false;
		}
		
		if(receptor_pais.val().length == 0)
		{
			mostrarVentana('Ingresa el país.');
			return false;
		}
		
		if(receptor_email.val().length == 0)
		{
			mostrarVentana('Ingresa tu correo electrónico.');
			return false;
		}
		
		texto = receptor_email.val();
		re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	   
		if (re.test(texto) == false)
		{
			mostrarVentana('El correo electrónico tiene un formato inválido.');
			return false;
		}
		
		return true;
    }
	
	function validarFormularioPaso2BuscarRFC()
	{
		var receptor_rfc   = $("#receptor_rfc");
		
		if(receptor_rfc.val().length == 0)
		{
			mostrarVentana('Ingresa tu RFC.');
			return false;
		}
		
		if(!(receptor_rfc.val().length == 12 || receptor_rfc.val().length == 13))
		{
			mostrarVentana('El RFC debe tener 12 o 13 caracteres.');
			return false;
		}
		
		texto = receptor_rfc.val().toUpperCase();
		re = /^([A-Z]|&|Ñ){3,4}[0-9]{2}[0-1][0-9][0-3][0-9]([A-Z]|[0-9]){2}([0-9]|A){1}$/;
	   
		if (re.test(texto) == false)
		{
			mostrarVentana('El RFC tiene un formato inválido.');
			return false;
		}
		
		return true;
    }
	
	$('#paso_uno_formulario').submit(function(e)
	{
        e.preventDefault();
		document.getElementById('cargandoPaso1').style.visibility = 'visible';
		
        if(!validarFormularioPaso1())
		{
			document.getElementById('cargandoPaso1').style.visibility = 'hidden';
			return false;
		}
		
        datosFormulario = $(this).serializeArray();

        data = 
		{
			action 			: 'realvirtual_woocommerce_paso_uno',
            numero_pedido   : datosFormulario[0].value,
            monto_pedido    : datosFormulario[1].value
        }

        $.post(myAjax.ajaxurl, data, function(response)
		{
			document.getElementById('cargandoPaso1').style.visibility = 'hidden';
			
            if(!response.success)
			{
				if(response.CFDI_ID > '0')
				{
					CFDI_ID = response.CFDI_ID;
					urlSistemaAsociado = response.urlSistemaAsociado;
					
					$('#paso_cinco_boton_xml').click(function(event)
					{
						event.preventDefault();
						location.href = urlSistemaAsociado + 'Php/Archivos_Proyecto/realvirtual_woocommerce_plugin.php?opcion=DescargarXML&CFDI_ID=' + CFDI_ID;
					});
					
					$('#paso_cinco_boton_pdf').click(function(event)
					{
						event.preventDefault();
						location.href = urlSistemaAsociado + 'Php/Archivos_Proyecto/realvirtual_woocommerce_plugin.php?opcion=DescargarPDF&CFDI_ID=' + CFDI_ID;
					});
					
					$('#paso_uno').stop().hide();
					$('#paso_cinco').stop().fadeIn('slow');
					return false;
				}
				else
				{
					mostrarVentana(response.message);
					return false;
				}
            }
			
			urlSistemaAsociado = response.urlSistemaAsociado;
			plugin_dir_url = response.plugin_dir_url;
			numero_pedido = response.numero_pedido;
			datosPedido = response.datosPedido;
			
			document.getElementById('receptor_id').value = '';
			document.getElementById('receptor_rfc').value = '';
			document.getElementById('receptor_razon_social').value = '';
			document.getElementById('receptor_calle').value = '';
			document.getElementById('receptor_no_exterior').value = '';
			document.getElementById('receptor_no_interior').value = '';
			document.getElementById('receptor_colonia').value = '';
			document.getElementById('receptor_localidad').value = '';
			document.getElementById('receptor_referencia').value = '';
			document.getElementById('receptor_municipio').value = '';
			document.getElementById('receptor_estado').value = '';
			document.getElementById('receptor_pais').value = '';
			document.getElementById('receptor_codigo_postal').value = '';
			document.getElementById('receptor_email').value = '';
			document.getElementById('receptor_telefono').value = '';
			
			document.getElementById('receptor_id').disabled = false;
			document.getElementById('receptor_rfc').disabled = false;
			document.getElementById('receptor_razon_social').disabled = false;
			document.getElementById('receptor_calle').disabled = false;
			document.getElementById('receptor_no_exterior').disabled = false;
			document.getElementById('receptor_no_interior').disabled = false;
			document.getElementById('receptor_colonia').disabled = false;
			document.getElementById('receptor_localidad').disabled = false;
			document.getElementById('receptor_referencia').disabled = false;
			document.getElementById('receptor_municipio').disabled = false;
			document.getElementById('receptor_estado').disabled = false;
			document.getElementById('receptor_pais').disabled = false;
			document.getElementById('receptor_codigo_postal').disabled = false;
			document.getElementById('receptor_email').disabled = false;
			document.getElementById('receptor_telefono').disabled = false;
			
			if(datosPedido.billing_email != '')
			{
				var razon_social = datosPedido.billing_company;
				
				if(razon_social == '')
					razon_social = datosPedido.billing_company + ' ' + datosPedido.billing_company;
				
				razon_social = razon_social.trim();
				
				document.getElementById('receptor_razon_social').value = razon_social;
				document.getElementById('receptor_calle').value = '';
				document.getElementById('receptor_no_exterior').value = '';
				document.getElementById('receptor_no_interior').value = '';
				document.getElementById('receptor_colonia').value = '';
				document.getElementById('receptor_localidad').value = '';
				document.getElementById('receptor_referencia').value = '';
				document.getElementById('receptor_municipio').value = datosPedido.billing_city;
				document.getElementById('receptor_estado').value = datosPedido.billing_state;
				
				var pais = datosPedido.billing_country;
				
				if(pais.toUpperCase() == 'MX')
					pais = 'México';
				
				document.getElementById('receptor_pais').value = pais;
				document.getElementById('receptor_codigo_postal').value = datosPedido.billing_postcode;
				document.getElementById('receptor_email').value = datosPedido.billing_email;
				document.getElementById('receptor_telefono').value = datosPedido.billing_phone;
			}
			
            $('#paso_uno').stop().hide();
            $('#paso_dos').stop().fadeIn('slow');
			
        }, 'json');

        return true;
    });
	
	/*$('#paso_dos_boton_buscar_cliente').click(function(event)
	{
		event.preventDefault();
		document.getElementById('imagen_paso_dos_boton_buscar_cliente').src = plugin_dir_url + '/assets/realvirtual_woocommerce_cargando.gif';
		
		document.getElementById('receptor_id').value = '';
		document.getElementById('receptor_razon_social').value = '';
		document.getElementById('receptor_calle').value = '';
		document.getElementById('receptor_no_exterior').value = '';
		document.getElementById('receptor_no_interior').value = '';
		document.getElementById('receptor_colonia').value = '';
		document.getElementById('receptor_localidad').value = '';
		document.getElementById('receptor_referencia').value = '';
		document.getElementById('receptor_municipio').value = '';
		document.getElementById('receptor_estado').value = '';
		document.getElementById('receptor_pais').value = '';
		document.getElementById('receptor_codigo_postal').value = '';
		document.getElementById('receptor_email').value = '';
		document.getElementById('receptor_telefono').value = '';
		
		document.getElementById('receptor_id').disabled = false;
		document.getElementById('receptor_razon_social').disabled = false;
		document.getElementById('receptor_calle').disabled = false;
		document.getElementById('receptor_no_exterior').disabled = false;
		document.getElementById('receptor_no_interior').disabled = false;
		document.getElementById('receptor_colonia').disabled = false;
		document.getElementById('receptor_localidad').disabled = false;
		document.getElementById('receptor_referencia').disabled = false;
		document.getElementById('receptor_municipio').disabled = false;
		document.getElementById('receptor_estado').disabled = false;
		document.getElementById('receptor_pais').disabled = false;
		document.getElementById('receptor_codigo_postal').disabled = false;
		document.getElementById('receptor_email').disabled = false;
		document.getElementById('receptor_telefono').disabled = false;
				
		if(!validarFormularioPaso2BuscarRFC())
		{
			document.getElementById('imagen_paso_dos_boton_buscar_cliente').src = plugin_dir_url + '/assets/realvirtual_woocommerce_buscar.png';
			return false;
		}
		
		var receptor_rfc   = $("#receptor_rfc").val().toUpperCase();
        var receptor_email = $("#receptor_email").val();
		
		data = 
		{
			action 			: 'realvirtual_woocommerce_paso_dos_buscar_cliente',
            receptor_rfc   	: receptor_rfc,
			receptor_email  : receptor_email
        }
		
		$.post(myAjax.ajaxurl, data, function(response)
		{
			document.getElementById('imagen_paso_dos_boton_buscar_cliente').src = plugin_dir_url + '/assets/realvirtual_woocommerce_buscar.png';
			
            if(!response.success)
			{
				document.getElementById('receptor_id').value = '';
				document.getElementById('receptor_rfc').value = '';
				document.getElementById('receptor_razon_social').value = '';
				document.getElementById('receptor_calle').value = '';
				document.getElementById('receptor_no_exterior').value = '';
				document.getElementById('receptor_no_interior').value = '';
				document.getElementById('receptor_colonia').value = '';
				document.getElementById('receptor_localidad').value = '';
				document.getElementById('receptor_referencia').value = '';
				document.getElementById('receptor_municipio').value = '';
				document.getElementById('receptor_estado').value = '';
				document.getElementById('receptor_pais').value = '';
				document.getElementById('receptor_codigo_postal').value = '';
				document.getElementById('receptor_email').value = '';
				document.getElementById('receptor_telefono').value = '';
				
				document.getElementById('receptor_id').disabled = false;
				document.getElementById('receptor_rfc').disabled = false;
				document.getElementById('receptor_razon_social').disabled = false;
				document.getElementById('receptor_calle').disabled = false;
				document.getElementById('receptor_no_exterior').disabled = false;
				document.getElementById('receptor_no_interior').disabled = false;
				document.getElementById('receptor_colonia').disabled = false;
				document.getElementById('receptor_localidad').disabled = false;
				document.getElementById('receptor_referencia').disabled = false;
				document.getElementById('receptor_municipio').disabled = false;
				document.getElementById('receptor_estado').disabled = false;
				document.getElementById('receptor_pais').disabled = false;
				document.getElementById('receptor_codigo_postal').disabled = false;
				document.getElementById('receptor_email').disabled = false;
				document.getElementById('receptor_telefono').disabled = false;
				
				mostrarVentana(response.message);
				return false;
            }
			else
			{
				document.getElementById('receptor_id').value = response.receptor_id;
				document.getElementById('receptor_rfc').value = response.receptor_rfc.toUpperCase();
				document.getElementById('receptor_razon_social').value = response.receptor_razon_social;
				document.getElementById('receptor_calle').value = response.receptor_calle;
				document.getElementById('receptor_no_exterior').value = response.receptor_no_exterior;
				document.getElementById('receptor_no_interior').value = response.receptor_no_interior;
				document.getElementById('receptor_colonia').value = response.receptor_colonia;
				document.getElementById('receptor_localidad').value = response.receptor_localidad;
				document.getElementById('receptor_referencia').value = response.receptor_referencia;
				document.getElementById('receptor_municipio').value = response.receptor_municipio;
				document.getElementById('receptor_estado').value = response.receptor_estado;
				document.getElementById('receptor_pais').value = response.receptor_pais;
				document.getElementById('receptor_codigo_postal').value = response.receptor_codigo_postal;
				document.getElementById('receptor_email').value = response.receptor_email;
				document.getElementById('receptor_telefono').value = response.receptor_telefono;
				
				document.getElementById('receptor_id').disabled = true;
				document.getElementById('receptor_rfc').disabled = false;
				document.getElementById('receptor_razon_social').disabled = true;
				document.getElementById('receptor_calle').disabled = true;
				document.getElementById('receptor_no_exterior').disabled = true;
				document.getElementById('receptor_no_interior').disabled = true;
				document.getElementById('receptor_colonia').disabled = true;
				document.getElementById('receptor_localidad').disabled = true;
				document.getElementById('receptor_referencia').disabled = true;
				document.getElementById('receptor_municipio').disabled = true;
				document.getElementById('receptor_estado').disabled = true;
				document.getElementById('receptor_pais').disabled = true;
				document.getElementById('receptor_codigo_postal').disabled = true;
				document.getElementById('receptor_email').disabled = true;
				document.getElementById('receptor_telefono').disabled = true;
			}
			
        }, 'json');
		
		return true;
	});*/
	
	$('#paso_dos_formulario').submit(function(e)
	{
        e.preventDefault();
		document.getElementById('cargandoPaso2').style.visibility = 'visible';
		
        if(!validarFormularioPaso2())
		{
			document.getElementById('cargandoPaso2').style.visibility = 'hidden';
			return false;
		}
		
		var receptor_id = document.getElementById('receptor_id').value;
		
		var receptor_rfc = document.getElementById('receptor_rfc').value;
		receptor_rfc = receptor_rfc.toUpperCase();
		document.getElementById('receptor_rfc').value = receptor_rfc;
		
		var receptor_razon_social = document.getElementById('receptor_razon_social').value;
		var receptor_calle = document.getElementById('receptor_calle').value;
		var receptor_no_exterior = document.getElementById('receptor_no_exterior').value;
		var receptor_no_interior = document.getElementById('receptor_no_interior').value;
		var receptor_colonia = document.getElementById('receptor_colonia').value;
		var receptor_localidad = document.getElementById('receptor_localidad').value;
		var receptor_referencia = document.getElementById('receptor_referencia').value;
		var receptor_municipio = document.getElementById('receptor_municipio').value;
		var receptor_estado = document.getElementById('receptor_estado').value;
		var receptor_pais = document.getElementById('receptor_pais').value;
		var receptor_codigo_postal = document.getElementById('receptor_codigo_postal').value;
		var receptor_email = document.getElementById('receptor_email').value;
		var receptor_telefono = document.getElementById('receptor_telefono').value;
		
		var datos_receptor = '<font size="3"><b>RECEPTOR</b></font>';
		var domicilio_receptor = '';
		var contacto_receptor = '';
		
		if(receptor_razon_social.length > 0)
			datos_receptor += '<br/>' + receptor_razon_social;
	
		if(receptor_rfc.length > 0)
			datos_receptor += '<br/>' + receptor_rfc;
	
		if(domicilio_receptor != '' && receptor_calle.length > 0)
			domicilio_receptor += ', ';
	
		if(receptor_calle.length > 0)
			domicilio_receptor += 'Calle: ' + receptor_calle;
		
		if(domicilio_receptor != '' && receptor_no_exterior.length > 0)
			domicilio_receptor += ', ';
		
		if(receptor_no_exterior.length > 0)
			domicilio_receptor += 'No Ext: ' + receptor_no_exterior;
		
		if(domicilio_receptor != '' && receptor_no_interior.length > 0)
			domicilio_receptor += ', ';
		
		if(receptor_no_interior.length > 0)
			domicilio_receptor += 'No Int: ' + receptor_no_interior;
		
		if(domicilio_receptor != '' && receptor_colonia.length > 0)
			domicilio_receptor += ', ';
		
		if(receptor_colonia.length > 0)
			domicilio_receptor += 'Col. ' + receptor_colonia;
		
		if(domicilio_receptor != '' && receptor_localidad.length > 0)
			domicilio_receptor += ', ';
		
		if(receptor_localidad.length > 0)
			domicilio_receptor += 'Loc: ' + receptor_localidad;
		
		if(domicilio_receptor != '' && receptor_referencia.length > 0)
			domicilio_receptor += ', ';
		
		if(receptor_referencia.length > 0)
			domicilio_receptor += 'Ref: ' + receptor_referencia;
		
		if(domicilio_receptor != '' && receptor_municipio.length > 0)
			domicilio_receptor += ', ';
		
		if(receptor_municipio.length > 0)
			domicilio_receptor += receptor_municipio;
		
		if(domicilio_receptor != '' && receptor_estado.length > 0)
			domicilio_receptor += ', ';
		
		if(receptor_estado.length > 0)
			domicilio_receptor += receptor_estado;
		
		if(domicilio_receptor != '' && receptor_pais.length > 0)
			domicilio_receptor += ', ';
		
		if(receptor_pais.length > 0)
			domicilio_receptor += receptor_pais;
		
		if(domicilio_receptor != '' && receptor_codigo_postal.length > 0)
			domicilio_receptor += ', ';
		
		if(receptor_codigo_postal.length > 0)
			domicilio_receptor += 'CP: ' + receptor_codigo_postal;
		
		if(contacto_receptor != '' && receptor_email.length > 0)
			contacto_receptor += '<br/>';
		
		if(receptor_email.length > 0)
			contacto_receptor += 'E-mail: ' + receptor_email;
		
		if(contacto_receptor != '' && receptor_telefono.length > 0)
			contacto_receptor += '<br/>';
		
		if(receptor_telefono.length > 0)
			contacto_receptor += 'Tel: ' + receptor_telefono;
		
		document.getElementById('paso_3_datos_receptor').innerHTML = datos_receptor + '<br/><br/>' + domicilio_receptor + '<br/><br/>' + contacto_receptor;
		
		data = 
		{
			action : 'realvirtual_woocommerce_paso_tres_buscar_emisor'
        }
		
		$.post(myAjax.ajaxurl, data, function(response)
		{
			document.getElementById('cargandoPaso2').style.visibility = 'hidden';
			
            if(!response.success)
			{
				mostrarVentana(response.message);
				return false;
			}
			else
			{
				var emisor_id = response.emisor_id;
				var emisor_rfc = response.emisor_rfc;
				var emisor_razon_social = response.emisor_razon_social;
				var emisor_calle = response.emisor_calle;
				var emisor_no_exterior = response.emisor_no_exterior;
				var emisor_no_interior = response.emisor_no_interior;
				var emisor_colonia = response.emisor_colonia;
				var emisor_referencia = response.emisor_referencia;
				var emisor_codigo_postal = response.emisor_codigo_postal;
				var emisor_estado = response.emisor_estado;
				var emisor_municipio = response.emisor_municipio;
				var emisor_localidad = response.emisor_localidad;
				var emisor_pais = response.emisor_pais;
				var emisor_email = response.emisor_email;
				var emisor_telefono = response.emisor_telefono;
				
				var datos_emisor = '<font size="3"><b>EMISOR</b></font>';
				var domicilio_emisor = '';
				var contacto_emisor = '';
				
				if(emisor_razon_social.length > 0)
					datos_emisor += '<br/>' + emisor_razon_social;
		
				if(emisor_rfc.length > 0)
					datos_emisor += '<br/>' + emisor_rfc;
			
				if(domicilio_emisor != '' && emisor_calle.length > 0)
					domicilio_emisor += ', ';
			
				if(emisor_calle.length > 0)
					domicilio_emisor += 'Calle: ' + emisor_calle;
				
				if(domicilio_emisor != '' && emisor_no_exterior.length > 0)
					domicilio_emisor += ', ';
				
				if(emisor_no_exterior.length > 0)
					domicilio_emisor += 'No Ext: ' + emisor_no_exterior;
				
				if(domicilio_emisor != '' && emisor_no_interior.length > 0)
					domicilio_emisor += ', ';
				
				if(emisor_no_interior.length > 0)
					domicilio_emisor += 'No Int: ' + emisor_no_interior;
				
				if(domicilio_emisor != '' && emisor_colonia.length > 0)
					domicilio_emisor += ', ';
				
				if(emisor_colonia.length > 0)
					domicilio_emisor += 'Col. ' + emisor_colonia;
				
				if(domicilio_emisor != '' && emisor_localidad.length > 0)
					domicilio_emisor += ', ';
				
				if(emisor_localidad.length > 0)
					domicilio_emisor += 'Loc: ' + emisor_localidad;
				
				if(domicilio_emisor != '' && emisor_referencia.length > 0)
					domicilio_emisor += ', ';
				
				if(emisor_referencia.length > 0)
					domicilio_emisor += 'Ref: ' + emisor_referencia;
				
				if(domicilio_emisor != '' && emisor_municipio.length > 0)
					domicilio_emisor += ', ';
				
				if(emisor_municipio.length > 0)
					domicilio_emisor += emisor_municipio;
				
				if(domicilio_emisor != '' && emisor_estado.length > 0)
					domicilio_emisor += ', ';
				
				if(emisor_estado.length > 0)
					domicilio_emisor += emisor_estado;
				
				if(domicilio_emisor != '' && emisor_pais.length > 0)
					domicilio_emisor += ', ';
				
				if(emisor_pais.length > 0)
					domicilio_emisor += emisor_pais;
				
				if(domicilio_emisor != '' && emisor_codigo_postal.length > 0)
					domicilio_emisor += ', ';
				
				if(emisor_codigo_postal.length > 0)
					domicilio_emisor += 'CP: ' + emisor_codigo_postal;
				
				if(contacto_emisor != '' && emisor_email.length > 0)
					contacto_emisor += '<br/>';
				
				if(emisor_email.length > 0)
					contacto_emisor += 'E-mail: ' + emisor_email;
				
				if(contacto_emisor != '' && emisor_telefono.length > 0)
					contacto_emisor += '<br/>';
				
				if(emisor_telefono.length > 0)
					contacto_emisor += 'Tel: ' + emisor_telefono;
				
				document.getElementById('paso_3_datos_emisor').innerHTML = datos_emisor + '<br/><br/>' + domicilio_emisor + '<br/><br/>' + contacto_emisor;
			}
		}, 'json');
		
		document.getElementById('cargandoPaso2').style.visibility = 'visible';
		
		data = 
		{
			action : 'realvirtual_woocommerce_paso_tres_metodos_pago'
        }
		
		$.post(myAjax.ajaxurl, data, function(response)
		{
			document.getElementById('cargandoPaso2').style.visibility = 'hidden';
			
            if(!response.success)
			{
				mostrarVentana(response.message);
				return false;
			}
			else
			{
				var registros = response.registros;
				
				document.getElementById('paso_3_metodos_pago').options.length = 0;
				
				for(var i = 0; i < registros.length; i++)
				{
					var elemento = document.createElement("option");
					elemento.value = registros[i]['Codigo'];
					elemento.text = registros[i]['Descripcion'];
					document.getElementById('paso_3_metodos_pago').add(elemento);
				}
			}
		}, 'json');
		
		document.getElementById('cargandoPaso2').style.visibility = 'visible';
		
		var conceptos = datosPedido.line_items;
		
		var tasaIVA = 0.16;
		var tasaIVAPorcentaje = 16;
        var calculoIVA = (1 + tasaIVA);
		
		subtotal = 0;
		var totalIVA = 0;
		descuento = Number(datosPedido.total_discount);
		
		var conceptosHTML = '';
		var fila = -1;
		
		arrayConceptos = new Array();
		var posicion = 0;
		
        for(var i = 0; i < conceptos.length; i++)
		{
            precioUnitario = Number(conceptos[i]['price'] / conceptos[i]['quantity']);
            precioUnitario = Number(precioUnitario / calculoIVA);
            
            subtotalUnitario = conceptos[i]['quantity'] * precioUnitario;

            conceptosHTML += '<tr><td style="text-align:left; border-color: #dedede;">';
            conceptosHTML += conceptos[i]['name'];
            conceptosHTML += '</td><td style="text-align:left; border-color: #dedede;">';
            conceptosHTML += conceptos[i]['quantity'];
            conceptosHTML += '</td><td style="text-align:right; border-color: #dedede;">$';
            conceptosHTML += (precioUnitario).formatMoney(2, '.', ',');
            conceptosHTML += '</td><td style="text-align:right; border-color: #dedede;">$';
            conceptosHTML += (subtotalUnitario).formatMoney(2, '.', ',');
            conceptosHTML += '</td></tr>';

            subtotal = Number(subtotal) + Number(subtotalUnitario);
            totalIVA = Number(totalIVA) + Number(conceptos[i]['subtotal_tax']);
            
			arrayConceptos[posicion] = new Array(24);				
			arrayConceptos[posicion][0] = conceptos[i]['quantity'];
			arrayConceptos[posicion][1] = 'Pieza';
			arrayConceptos[posicion][2] = 'vacio';			
			arrayConceptos[posicion][3] = conceptos[i]['name'];
			arrayConceptos[posicion][4] = Number(precioUnitario);
			arrayConceptos[posicion][5] = Number(subtotalUnitario);
			arrayConceptos[posicion][6] = '';
			arrayConceptos[posicion][7] = 'vacio';
			arrayConceptos[posicion][8] = 'vacio';
			arrayConceptos[posicion][9] = 'vacio';
			arrayConceptos[posicion][10] = tasaIVAPorcentaje;
			arrayConceptos[posicion][11] = Number(conceptos[i]['subtotal_tax']);
			arrayConceptos[posicion][12] = 0;
			arrayConceptos[posicion][13] = 0;
			arrayConceptos[posicion][14] = 0;
			arrayConceptos[posicion][15] = 0;
			arrayConceptos[posicion][16] = 0;
			arrayConceptos[posicion][17] = 0;
			arrayConceptos[posicion][18] = 'vacio'; //ID DEL PRODUCTO
			arrayConceptos[posicion][19] = '';
			arrayConceptos[posicion][20] = ''; //CLAVE DEL PRODUCTO
			arrayConceptos[posicion][21] = conceptos[i]['name'];
			arrayConceptos[posicion][22] = 'Pieza';
			arrayConceptos[posicion][23] = Number(precioUnitario);
			
			posicion++;
			precioUnitario = 0;
        }
		
		document.getElementById('conceptos_cuerpo_tabla').innerHTML = conceptosHTML;
		
		total = Number(datosPedido.total);
		
		var totalesHTML = '';
		
		totalesHTML += '<tr><td style="text-align:right; width:80%;"><b>Subtotal</b></td><td style="text-align:right; width:20%;">';
		totalesHTML += '$' + subtotal.formatMoney(2, '.', ',');
        totalesHTML += '</td></tr>';
			
		if(descuento > 0)
		{
			preDescuento = Number(descuento / calculoIVA);
			preSubtotal = Number(subtotal - preDescuento);
			totalIVA = Number(preSubtotal * tasaIVA);
			total = Number(preSubtotal + totalIVA);
			
			totalesHTML += '<tr><td style="text-align:right; width:80%;"><b>Descuento</b></td><td style="text-align:right; width:20%;">';
			totalesHTML += '$' + descuento.formatMoney(2, '.', ',');
			totalesHTML += '</td></tr>';
		}
		else
		{
			totalIVA = total - subtotal;
			total = Number(subtotal + totalIVA);
		}
		
		arrayImpuestosFederales = new Array()
		arrayImpuestosFederales[0] = new Array(4);
		arrayImpuestosFederales[0][0] = '1';
		arrayImpuestosFederales[0][1] = 'IVA';
		arrayImpuestosFederales[0][2] = (tasaIVA) * 100;
		arrayImpuestosFederales[0][3] = totalIVA;
		
		totalesHTML += '<tr><td style="text-align:right; width:80%;"><b>IVA</b></td><td style="text-align:right; width:20%;">';
		totalesHTML += '$' + (totalIVA).formatMoney(2, '.', ',');
		totalesHTML += '</td></tr>';
		
		totalesHTML += '<tr><td style="text-align:right; width:80%;"><b>Total</b></td><td style="text-align:right; width:20%;">';
		totalesHTML += '$' + (total).formatMoney(2, '.', ',');
		totalesHTML += '</td></tr>';
		
		document.getElementById('totales_cuerpo_tabla').innerHTML = totalesHTML;
		document.getElementById('cargandoPaso2').style.visibility = 'hidden';
		
		$('#paso_dos').stop().hide();
		$('#paso_tres').stop().fadeIn('slow');			
		
        return true;
    });
	
	$('#paso_tres_formulario').submit(function(e)
	{
        e.preventDefault();
		document.getElementById('paso_tres_boton_regresar').disabled = true;
		document.getElementById('paso_tres_boton_generar').disabled = true;
		document.getElementById('cargandoPaso3').style.visibility = 'visible';
		
		var receptor_id = document.getElementById('receptor_id').value;
		var receptor_rfc = document.getElementById('receptor_rfc').value;
		var receptor_razon_social = document.getElementById('receptor_razon_social').value;
		var receptor_calle = document.getElementById('receptor_calle').value;
		var receptor_no_exterior = document.getElementById('receptor_no_exterior').value;
		var receptor_no_interior = document.getElementById('receptor_no_interior').value;
		var receptor_colonia = document.getElementById('receptor_colonia').value;
		var receptor_localidad = document.getElementById('receptor_localidad').value;
		var receptor_referencia = document.getElementById('receptor_referencia').value;
		var receptor_municipio = document.getElementById('receptor_municipio').value;
		var receptor_estado = document.getElementById('receptor_estado').value;
		var receptor_pais = document.getElementById('receptor_pais').value;
		var receptor_codigo_postal = document.getElementById('receptor_codigo_postal').value;
		var receptor_email = document.getElementById('receptor_email').value;
		var receptor_telefono = document.getElementById('receptor_telefono').value;
		
		var metodo_pago = document.getElementById('paso_3_metodos_pago').value;
		var no_cuenta = document.getElementById('paso_3_no_cuenta').value;
        
		if(metodo_pago == '02' || metodo_pago == '03' || metodo_pago == '04' || metodo_pago == '05' || metodo_pago == '06')
		{
			if(no_cuenta.length == 0)
			{
				document.getElementById('paso_tres_boton_regresar').disabled = false;
				document.getElementById('paso_tres_boton_generar').disabled = false;
				document.getElementById('cargandoPaso3').style.visibility = 'hidden';
				mostrarVentana('El número de cuenta es obligatorio para el método de pago seleccionado.');
				return false;
			}
		}
		
		data = 
		{
			action 					: 'realvirtual_woocommerce_paso_tres_generar_cfdi',
			receptor_id				: receptor_id,
			receptor_rfc			: receptor_rfc,
			receptor_razon_social	: receptor_razon_social,
			receptor_calle			: receptor_calle,
			receptor_no_exterior	: receptor_no_exterior,
			receptor_no_interior	: receptor_no_interior, 
			receptor_colonia		: receptor_colonia,
			receptor_localidad		: receptor_localidad,
			receptor_referencia		: receptor_referencia,
			receptor_municipio		: receptor_municipio,
			receptor_estado			: receptor_estado,
			receptor_pais			: receptor_pais,
			receptor_codigo_postal	: receptor_codigo_postal,
			receptor_email			: receptor_email,
			receptor_telefono		: receptor_telefono,
			metodo_pago 			: metodo_pago,
			no_cuenta				: no_cuenta,
			conceptos				: JSON.stringify(arrayConceptos),
			subtotal				: subtotal,
			descuento				: descuento,
			total					: total,
			impuesto_federal		: JSON.stringify(arrayImpuestosFederales),
			numero_pedido			: numero_pedido
        }
		
		$.post(myAjax.ajaxurl, data, function(response)
		{
			document.getElementById('paso_tres_boton_regresar').disabled = false;
			document.getElementById('paso_tres_boton_generar').disabled = false;
			document.getElementById('cargandoPaso3').style.visibility = 'hidden';
			
            if(!response.success)
			{
				mostrarVentana(response.message);
				return false;
			}
			else
			{
				mostrarVentana(response.message);
				xml = response.XML;
				CFDI_ID = response.CFDI_ID;
				
				$('#paso_cuatro_boton_xml').click(function(event)
				{
					event.preventDefault();
					location.href = urlSistemaAsociado + 'Php/Archivos_Proyecto/realvirtual_woocommerce_plugin.php?opcion=DescargarXML&CFDI_ID=' + CFDI_ID;
				});
				
				$('#paso_cuatro_boton_pdf').click(function(event)
				{
					event.preventDefault();
					location.href = urlSistemaAsociado + 'Php/Archivos_Proyecto/realvirtual_woocommerce_plugin.php?opcion=DescargarPDF&CFDI_ID=' + CFDI_ID;
				});
				
				$('#paso_tres').stop().hide();
				$('#paso_cuatro').stop().fadeIn('slow');
				
				return true;
			}
		}, 'json');
	});
	
	$('#paso_dos_boton_regresar').click(function(event)
	{
		event.preventDefault();
		$('#paso_uno').stop().fadeIn('slow');
		$('#paso_dos').stop().hide();
	});
	
	$('#paso_tres_boton_regresar').click(function(event)
	{
		event.preventDefault();
		$('#paso_dos').stop().fadeIn('slow');
		$('#paso_tres').stop().hide();
	});
	
	$('#paso_cuatro_boton_regresar').click(function(event)
	{
		event.preventDefault();
		LimpiarFormularios();
		$('#paso_uno').stop().fadeIn('slow');
		$('#paso_dos').hide();
		$('#paso_tres').hide();
		$('#paso_cuatro').hide();
		$('#paso_cinco').hide();
	});
	
	$('#paso_cinco_boton_regresar').click(function(event)
	{
		event.preventDefault();
		LimpiarFormularios();
		$('#paso_uno').stop().fadeIn('slow');
		$('#paso_dos').hide();
		$('#paso_tres').hide();
		$('#paso_cuatro').hide();
		$('#paso_cinco').hide();
	});
	
	function LimpiarFormularios()
	{
		document.getElementById('numero_pedido').value = '';
		document.getElementById('monto_pedido').value = '';
		
		document.getElementById('receptor_id').value = '';
		document.getElementById('receptor_rfc').value = '';
		document.getElementById('receptor_razon_social').value = '';
		document.getElementById('receptor_calle').value = '';
		document.getElementById('receptor_no_exterior').value = '';
		document.getElementById('receptor_no_interior').value = '';
		document.getElementById('receptor_colonia').value = '';
		document.getElementById('receptor_localidad').value = '';
		document.getElementById('receptor_referencia').value = '';
		document.getElementById('receptor_municipio').value = '';
		document.getElementById('receptor_estado').value = '';
		document.getElementById('receptor_pais').value = '';
		document.getElementById('receptor_codigo_postal').value = '';
		document.getElementById('receptor_email').value = '';
		document.getElementById('receptor_telefono').value = '';
	}
	
	Number.prototype.formatMoney = function(c, d, t)
	{
		var n = this, 
		c = isNaN(c = Math.abs(c)) ? 2 : c, 
		d = d == undefined ? "." : d, 
		t = t == undefined ? "," : t, 
		s = n < 0 ? "-" : "", 
		i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
		j = (j = i.length) > 3 ? j % 3 : 0;
	   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
	};
	
	try
	{
		var modal = document.getElementById('ventanaModal');
		var span = document.getElementsByClassName('close')[0];
		var boton = document.getElementById('botonModal');
		
		function mostrarVentana(texto)
		{
			modal.style.display = "block";
			document.getElementById('tituloModal').innerHTML = 'Aviso';
			document.getElementById('textoModal').innerHTML = texto;
		}

		boton.onclick = function()
		{
			modal.style.display = "none";
			document.getElementById('tituloModal').innerHTML = '';
			document.getElementById('textoModal').innerHTML = '';
		}
		
		span.onclick = function()
		{
			modal.style.display = "none";
			document.getElementById('tituloModal').innerHTML = '';
			document.getElementById('textoModal').innerHTML = '';
		}
	}
	catch(error)
	{
		
	}
});