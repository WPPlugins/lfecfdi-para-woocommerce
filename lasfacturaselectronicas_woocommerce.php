<?php

/*
Plugin Name: LFECFDI para WooCommerce
Plugin URI: https://realvirtual.com.mx/woocommerce-y-wordpress-con-facturacion-electronica/
Description: Conecta tu tienda WooCommerce con LasFacturasElectrónicas.com para que tus clientes puedan facturar sus compras
Version: 2.0
Author: Gustavo Arizmendi
Author URI: http://garizmendi.wordpress.com
Text Domain: lfecfdi-para-woocommerce
Domain Path: /languages/
License:     GPL2

LFECFDI para WooCommerce is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
LFECFDI para WooCommerce is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with LFECFDI para WooCommerce. If not, see https://www.gnu.org/licenses/gpl.html.
*/

$versionPlugin = '2.0';
$sistema = 'LFECFDI';
$nombreSistema = 'RVCFDI para WooCommerce';
$nombreSistemaAsociado = 'RV Factura Electrónica Web';
$urlSistemaAsociado = 'http://comprobante.realvirtual.com.mx/comprobante_digital/';
$sitioOficialSistema = 'https://realvirtual.com.mx/';

if($sistema == 'LFECFDI')
{
	$nombreSistema = 'LFECFDI para WooCommerce';
	$nombreSistemaAsociado = 'LasFacturasElectrónicas.com';
	$urlSistemaAsociado = 'https://secure.lasfacturaselectronicas.com/lfe-misfacturas/';
	$sitioOficialSistema = 'https://lasfacturaselectronicas.com/';
}

require_once(plugin_dir_path( __FILE__ ).'recursos/realvirtual_woocommerce_cuenta.php');
require_once(plugin_dir_path( __FILE__ ).'recursos/realvirtual_woocommerce_configuracion.php');
require_once(plugin_dir_path( __FILE__ ).'recursos/realvirtual_woocommerce_pedido.php');
require_once(plugin_dir_path( __FILE__ ).'recursos/realvirtual_woocommerce_cliente.php');
require_once(plugin_dir_path( __FILE__ ).'recursos/realvirtual_woocommerce_emisor.php');
require_once(plugin_dir_path( __FILE__ ).'recursos/realvirtual_woocommerce_metodopago.php');
require_once(plugin_dir_path( __FILE__ ).'recursos/realvirtual_woocommerce_cfdi.php');

add_action('init', 'realvirtual_woocommerce_cargar_scripts');
add_action('admin_menu', 'realvirtual_woocommerce_back_end');
add_shortcode(strtolower($sistema).'_woocommerce_formulario', 'realvirtual_woocommerce_front_end');

function realvirtual_woocommerce_cargar_scripts()
{
	global $sistema, $nombreSistema, $nombreSistemaAsociado, $urlSistemaAsociado, $sitioOficialSistema;
	
	wp_enqueue_script('realvirtual_woocommerce_script', plugin_dir_url(__FILE__).'/assets/realvirtual_woocommerce.js', array('jquery'), '3.3.5', true);
	wp_register_style('realvirtual_woocommerce_style', plugin_dir_url(__FILE__).'assets/realvirtual_woocommerce.css', array(), false, 'all');
	wp_localize_script('realvirtual_woocommerce_script', 'myAjax', array( 'ajaxurl' => admin_url('admin-ajax.php')));
    wp_enqueue_style('realvirtual_woocommerce_style');
	wp_enqueue_script('jquery');
	wp_enqueue_script('realvirtual_woocommerce_script');
}

function realvirtual_woocommerce_back_end()
{
	global $sistema, $nombreSistema, $nombreSistemaAsociado, $urlSistemaAsociado, $sitioOficialSistema;
	
	add_menu_page($sistema, $sistema, 'manage_woocommerce', 'realvirtual_woo_dashboard', 'realvirtual_woocommerce_dashboard', plugin_dir_url( __FILE__ ).'/assets/realvirtual_woocommerce.png');
}

function realvirtual_woocommerce_dashboard()
{
	global $sistema, $nombreSistema, $nombreSistemaAsociado, $urlSistemaAsociado, $sitioOficialSistema, $versionPlugin;
	
	?>
		<div>
        <font color="#000000" size="5"><b><?php echo esc_html($nombreSistema); ?></b></font><font color="#505050" size="2" style="font-style: italic;"><?php echo '&nbsp;versión '.esc_html($versionPlugin); ?></font>

        <?php
			if(isset($_GET['tab']))
				$opcion = $_GET['tab'];
			else
				$opcion = 'cuenta';
		?>

        <h2>
			<font color="#000000" size="4"><a href="?page=realvirtual_woo_dashboard&tab=cuenta" style="text-decoration: <?php echo $opcion == 'cuenta' ? 'underline' : 'none'; ?>;">Mi Cuenta</a>&nbsp;|</font>
            <font color="#000000" size="4"><a href="?page=realvirtual_woo_dashboard&tab=configuracion" style="text-decoration: <?php echo $opcion == 'configuracion' ? 'underline' : 'none'; ?>;">Configuración</a>&nbsp;|</font>
			<font color="#000000" size="4"><a href="?page=realvirtual_woo_dashboard&tab=ventas" style="text-decoration: <?php echo $opcion == 'ventas' ? 'underline' : 'none'; ?>;">Ventas</a>&nbsp;|</font>
			<font color="#000000" size="4"><a href="?page=realvirtual_woo_dashboard&tab=soporte" style="text-decoration: <?php echo $opcion == 'soporte' ? 'underline' : 'none'; ?>;">Soporte Técnico</a>&nbsp;|</font>
			<font color="#000000" size="4"><a href="?page=realvirtual_woo_dashboard&tab=preguntas" style="text-decoration: <?php echo $opcion == 'preguntas' ? 'underline' : 'none'; ?>;">Preguntas Frecuentes</a></font>
        </h2>

        <?php
			if($opcion == 'ventas')
                realvirtual_woocommerce_ventas();
			else if($opcion == 'cuenta')
                realvirtual_woocommerce_cuenta();
			else if($opcion == 'configuracion')
                realvirtual_woocommerce_configuracion();
			else if($opcion == 'soporte')
				realvirtual_woocommerce_soporte();
			else if($opcion == 'preguntas')
				realvirtual_woocommerce_preguntas();
        ?>
		
		</div>
	<?php
}

function realvirtual_woocommerce_ventas()
{
	global $sistema, $nombreSistema, $nombreSistemaAsociado, $urlSistemaAsociado, $sitioOficialSistema;
	
	$cuenta = RealVirtualWooCommerceCuenta::cuentaEntidad();
	
	if(!($cuenta['rfc'] != '' && $cuenta['usuario'] != '' && $cuenta['clave'] != ''))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'No se pueden obtener las ventas realizadas porque es necesario antes ingresar correctamente tu RFC, Usuario y Clave Cifrada en la sección <b>Mi Cuenta</b>.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	$filtro = '||||';
	$datosVentas = RealVirtualWooCommerceCFDI::obtenerVentas($cuenta['rfc'], $cuenta['usuario'], $cuenta['clave'], $filtro, $urlSistemaAsociado);
	
	?>
		<div style="background-color: #FFFFFF; padding: 20px;">
		<label><font color="#006fa8" size="5"><b>Ventas</b></font></label>
		<br/><br/>
		<font color="#000000" size="3"><label id="total_cfdi_ventas"><?php echo ($datosVentas->success == true) ? esc_html($datosVentas->TOTAL_CFDI) : esc_html(0) ?> CFDI encontrados.</label></font>
		<br/>
		<p><b>NOTA:</b> Puedes administrar tus CFDI directamente en <a href="<?php echo esc_url($urlSistemaAsociado); ?>" target="_blank"><b><?php echo esc_html($nombreSistemaAsociado); ?></b></a>.</p>
		<font color="#000000" size="3"><label id="ingresos_ventas" style="padding-left: 4em;">Ingresos: <b>$<?php echo esc_html($datosVentas->INGRESOS); ?></label></b><label id="iva_ventas" style="padding-left: 4em;">IVA: <b>$<?php echo esc_html($datosVentas->IVA); ?></label></b><label id="total_ventas" style="padding-left: 4em;">Total: <b>$<?php echo esc_html($datosVentas->TOTAL); ?></label></b></font>
		<br/><br/>
		<center>
		<table border="0" style="background-color:#006fa8;" width="95%">
			<tr>
				<td style="width: 1px;white-space: nowrap;">
					<button style="background-color:#0087cc;" class="botonVentas" id="ventas_boton_xml"><img src="<?php echo esc_url(plugin_dir_url( __FILE__ )."/assets/realvirtual_woocommerce_xml.PNG"); ?>" height="16" width="16"><font color="#FFFFFF" size="2">&nbsp;Descargar XML</font></button>
					<button style="background-color:#0087cc;" class="botonVentas" id="ventas_boton_pdf"><img src="<?php echo esc_url(plugin_dir_url( __FILE__ )."/assets/realvirtual_woocommerce_pdf.png"); ?>" height="16" width="16"><font color="#FFFFFF" size="2">&nbsp;Descargar PDF</font></button>
					<button style="background-color:#0087cc;" class="botonVentas" id="ventas_boton_enviar"><img src="<?php echo esc_url(plugin_dir_url( __FILE__ )."/assets/realvirtual_woocommerce_enviar.png"); ?>" height="16" width="16"><font color="#FFFFFF" size="2">&nbsp;Enviar</font></button>
					<button style="background-color:#0087cc;" class="botonVentas" id="ventas_boton_cancelar"><img src="<?php echo esc_url(plugin_dir_url( __FILE__ )."/assets/realvirtual_woocommerce_cancelar.gif"); ?>" height="16" width="16"><font color="#FFFFFF" size="2">&nbsp;Cancelar</font></button>
					<button style="background-color:#0087cc;" class="botonVentas" id="ventas_boton_acuse"><img src="<?php echo esc_url(plugin_dir_url( __FILE__ )."/assets/realvirtual_woocommerce_acuse.png"); ?>" height="16" width="16"><font color="#FFFFFF" size="2">&nbsp;Acuse de cancelación</font></button>
					<button style="background-color:#0087cc;" class="botonVentas" id="ventas_boton_filtrar"><img src="<?php echo esc_url(plugin_dir_url( __FILE__ )."/assets/realvirtual_woocommerce_filtrar.png"); ?>" height="16" width="16"><font color="#FFFFFF" size="2">&nbsp;Filtrar</font></button>
					<button style="background-color:#0087cc;" class="botonVentas" id="ventas_boton_refresh"><img src="<?php echo esc_url(plugin_dir_url( __FILE__ )."/assets/realvirtual_woocommerce_refresh.png"); ?>" height="16" width="16"><font color="#FFFFFF" size="2"></font></button>
					<img id="cargandoVentas" src="<?php echo esc_url(plugin_dir_url( __FILE__ )."/assets/realvirtual_woocommerce_cargando_ventas.gif"); ?>" alt="Cargando" height="16" width="16" style="visibility: hidden;">
				</td>
			</tr>
		</table>
		<table border="1" style="border-collapse: collapse; background-color:#FFFFFF; border-color:#004c91;" width="95%">
			<thead>
				<tr>
					<td style="text-align:center; border-color: #004c91; background-color: #006fa8; padding: 5px;"><font color="#FFFFFF" size="3">Pedido</font></td>
					<td style="text-align:center; border-color: #004c91; background-color: #006fa8; padding: 5px;"><font color="#FFFFFF" size="3">Folio</font></td>
					<td style="text-align:center; border-color: #004c91; background-color: #006fa8; padding: 5px;"><font color="#FFFFFF" size="3">Folio Fiscal (UUID)</font></td>
					<td style="text-align:center; border-color: #004c91; background-color: #006fa8; padding: 5px;"><font color="#FFFFFF" size="3">Fecha Emisión</font></td>
					<td style="text-align:center; border-color: #004c91; background-color: #006fa8; padding: 5px;"><font color="#FFFFFF" size="3">Cliente</font></td>
					<td style="text-align:center; border-color: #004c91; background-color: #006fa8; padding: 5px;"><font color="#FFFFFF" size="3">Ingresos</font></td>
					<td style="text-align:center; border-color: #004c91; background-color: #006fa8; padding: 5px;"><font color="#FFFFFF" size="3">IVA</font></td>
					<td style="text-align:center; border-color: #004c91; background-color: #006fa8; padding: 5px;"><font color="#FFFFFF" size="3">Total</font></td>
					<td style="text-align:center; border-color: #004c91; background-color: #006fa8; padding: 5px;"><font color="#FFFFFF" size="3">Moneda</font></td>
					<td style="text-align:center; border-color: #004c91; background-color: #006fa8; padding: 5px;"><font color="#FFFFFF" size="3">Estado</font></td>
				</tr>
			</thead>
			<tbody id="catalogoFacturas"><?php echo $datosVentas->VENTAS; ?></tbody>
		</table>
		</center>
		</div>
		
		<div id="ventanaModalVentas" class="modalVentas">
			<div class="modal-contentVentas">
				<span class="closeVentas">&times;</span>
				<br/>
				<center>
					<font color="#000000" size="5"><b>
						<div id="tituloModalVentas"></div>
					</b></font>
					<br/>
					<font color="#000000" size="3">
						<div id="textoModalVentas"></div>
					</font>
					<br/>
					<input type="button" style="background-color:#0087cc;" class="boton" id="botonModalVentas" value="Aceptar" />
				</center>
			</div>
		</div>
		
		<div id="ventanaModalFiltrar" class="modalFiltrar">
			<div class="modal-contentFiltrar">
				<span class="closeFiltrar">&times;</span>
				<br/>
				<center>
					<font color="#000000" size="5"><b>
						<div id="tituloModalFiltrar">Filtrar</div>
					</b></font>
					<br/>
					<font color="#000000" size="3">
						<div class="rowFiltrar">
							<label><font color="#000000">* Año</font></label>
							<select id="año_ventas" name="año_ventas">
							<?php
								$añoInicial = 2017;
								$añoActual = date("Y");
								
								for($i = $añoInicial; $i <= $añoActual; $i++)
								
								if($i == date("Y"))
								{
								?>
									<option value="<?php echo esc_html($i); ?>" selected><?php echo esc_html($i); ?></option>
								<?php 
								}
								else
								{
								?>
									<option value="<?php echo esc_html($i); ?>"><?php echo esc_html($i); ?></option>
								<?php 
								}
							?>
							</select>
							<br/>
							<label><font color="#000000">* Mes</font></label>
							<select id="mes_ventas" name="mes_ventas">
								<option value="01">Enero</option>
								<option value="02">Febrero</option>
								<option value="03">Marzo</option>
								<option value="04">Abril</option>
								<option value="05">Mayo</option>
								<option value="06">Junio</option>
								<option value="07">Julio</option>
								<option value="08">Agosto</option>
								<option value="09">Septiembre</option>
								<option value="10">Octubre</option>
								<option value="11">Noviembre</option>
								<option value="12">Diciembre</option>
								<option value="0">Todo el año</option>
							</select>
							<br/>
							<label><font color="#000000">* Estado</font></label>
							<select id="estado_ventas" name="estado_ventas">
								<option value="1">Vigentes</option>
								<option value="2">Canceladas</option>
								<option value="">Vigentes y Canceladas</option>
							</select>
							<br/>
							<label><font color="#000000">Número de Pedido</font></label>
							<input type="text" id="numero_pedido_ventas" name="numero_pedido_ventas" value="" placeholder="Sin símbolo #">
							<br/>
							<label><font color="#000000">Cliente</font></label>
							<input type="text" id="cliente_ventas" name="cliente_ventas" value="">
						</div>	
					</font>
					<br/>
					<input type="button" style="background-color:#0087cc;" class="boton" id="botonModalFiltrar" value="Aceptar" />
				</center>
			</div>
		</div>
				
		<script type="text/javascript">
			jQuery(document).ready(function($)
			{
				var urlSistemaAsociado = '<?php echo esc_url($urlSistemaAsociado); ?>';
				var nombreSistemaAsociado = '<?php echo esc_html($nombreSistemaAsociado); ?>';
				var urlSistemaAsociado = '<?php echo esc_url($urlSistemaAsociado); ?>';
				var CFDI_ID = '';
				var CFDI_UUID = '';
				var RECEPTOR_EMAIL = '';
				var EMISOR_ID = '';
				
				$("#catalogoFacturas tr").click(function()
				{ 
					$(this).addClass('selected').siblings().removeClass('selected');    
					CFDI_ID = $(this).find('td:first-child').html();
					CFDI_UUID = $(this).find('td:nth-child(2)').html();
					RECEPTOR_EMAIL = $(this).find('td:nth-child(4)').html();
					EMISOR_ID = $(this).find('td:nth-child(5)').html();
				});
				
				$('#ventas_boton_xml').click(function(event)
				{
					if(CFDI_ID == '')
					{
						mostrarVentanaVentas('Seleccione un CFDI.');
						return;
					}
					
					location.href = urlSistemaAsociado + 'Php/Archivos_Proyecto/realvirtual_woocommerce_plugin.php?opcion=DescargarXML&CFDI_ID=' + CFDI_ID;
				});
				
				$('#ventas_boton_pdf').click(function(event)
				{
					if(CFDI_ID == '')
					{
						mostrarVentanaVentas('Seleccione un CFDI.');
						return;
					}
					
					location.href = urlSistemaAsociado + 'Php/Archivos_Proyecto/realvirtual_woocommerce_plugin.php?opcion=DescargarPDF&CFDI_ID=' + CFDI_ID;
				});
				
				$('#ventas_boton_enviar').click(function(event)
				{
					if(CFDI_ID == '')
					{
						mostrarVentanaVentas('Seleccione un CFDI.');
						return;
					}
					
					if(RECEPTOR_EMAIL == '')
					{
						mostrarVentanaVentas('No se puede enviar el CFDI porque el cliente no tiene correo electrónico especificado. Por favor, revise los datos del cliente en ' + nombreSistemaAsociado);
						return;
					}
					
					document.getElementById('cargandoVentas').style.visibility = 'visible';
					
					data =
					{
						action  			: 'realvirtual_woocommerce_enviar',
						CFDI_ID   			: CFDI_ID,
						EMISOR_ID   		: EMISOR_ID,
						RECEPTOR_EMAIL   	: RECEPTOR_EMAIL
					}

					$.post(myAjax.ajaxurl, data, function(response)
					{
						document.getElementById('cargandoVentas').style.visibility = 'hidden';
						var response = JSON.parse(response);
						mostrarVentanaVentas(response.message);
					});
				});
				
				$('#ventas_boton_cancelar').click(function(event)
				{
					if(CFDI_UUID == '')
					{
						mostrarVentanaVentas('Seleccione un CFDI.');
						return;
					}
					
					document.getElementById('cargandoVentas').style.visibility = 'visible';
					
					data =
					{
						action  			: 'realvirtual_woocommerce_cancelar',
						CFDI_UUID   		: CFDI_UUID,
						EMISOR_ID   		: EMISOR_ID
					}

					$.post(myAjax.ajaxurl, data, function(response)
					{
						document.getElementById('cargandoVentas').style.visibility = 'hidden';
						var response = JSON.parse(response);
						mostrarVentanaVentas(response.message);
					});
				});
				
				$('#ventas_boton_acuse').click(function(event)
				{
					if(CFDI_UUID == '')
					{
						mostrarVentanaVentas('Seleccione un CFDI.');
						return;
					}
					
					location.href = urlSistemaAsociado + 'Php/Archivos_Proyecto/realvirtual_woocommerce_plugin.php?opcion=DescargarPDF&CFDI_UUID=' + CFDI_UUID;
				});
				
				$('#ventas_boton_filtrar').click(function(event)
				{
					mostrarVentanaFiltrar();
				});
				
				$('#botonModalFiltrar').click(function(event)
				{
					var año = document.getElementById('año_ventas').value;
					var mes = document.getElementById('mes_ventas').value;
					var estado = document.getElementById('estado_ventas').value;
					var numero_pedido = document.getElementById('numero_pedido_ventas').value;
					var cliente = document.getElementById('cliente_ventas').value;
					
					if(año == '')
					{
						mostrarVentanaVentas('Seleccione un año.');
						return;
					}
					
					if(mes == '')
					{
						mostrarVentanaVentas('Seleccione un mes.');
						return;
					}
					
					if(estado == '')
					{
						mostrarVentanaVentas('Seleccione un estado.');
						return;
					}
					
					document.getElementById('cargandoVentas').style.visibility = 'visible';
					
					data =
					{
						action  			: 'realvirtual_woocommerce_filtrar',
						AÑO   				: año,
						MES   				: mes,
						ESTADO   			: estado,
						NUMERO_PEDIDO   	: numero_pedido,
						CLIENTE   			: cliente
					}

					$.post(myAjax.ajaxurl, data, function(response)
					{
						document.getElementById('cargandoVentas').style.visibility = 'hidden';
						var response = JSON.parse(response);
						
						if(response.success == false)
						{
							mostrarVentanaVentas(response.message);
							return;
						}
						else
						{
							document.getElementById('catalogoFacturas').innerHTML = response.ventas;
							document.getElementById('total_cfdi_ventas').innerHTML = response.total_cfdi + ' CFDI encontrados.';
							document.getElementById('ingresos_ventas').innerHTML = 'Ingresos: <b>$' + response.ingresos;
							document.getElementById('iva_ventas').innerHTML = 'IVA: <b>$' + response.iva;
							document.getElementById('total_ventas').innerHTML = 'Total: <b>$' + response.total;
							
							$("#catalogoFacturas tr").click(function()
							{ 
								$(this).addClass('selected').siblings().removeClass('selected');    
								CFDI_ID = $(this).find('td:first-child').html();
								CFDI_UUID = $(this).find('td:nth-child(2)').html();
								RECEPTOR_EMAIL = $(this).find('td:nth-child(4)').html();
								EMISOR_ID = $(this).find('td:nth-child(5)').html();
							});
						}
					});
				});
				
				$('#ventas_boton_refresh').click(function(event)
				{
					var año = '';
					var mes = '';
					var estado = '';
					var numero_pedido = '';
					var cliente = '';
					
					document.getElementById('cargandoVentas').style.visibility = 'visible';
					
					data =
					{
						action  			: 'realvirtual_woocommerce_filtrar',
						AÑO   				: año,
						MES   				: mes,
						ESTADO   			: estado,
						NUMERO_PEDIDO   	: numero_pedido,
						CLIENTE   			: cliente
					}

					$.post(myAjax.ajaxurl, data, function(response)
					{
						document.getElementById('cargandoVentas').style.visibility = 'hidden';
						var response = JSON.parse(response);
						
						if(response.success == false)
						{
							mostrarVentanaVentas(response.message);
							return;
						}
						else
						{
							document.getElementById('catalogoFacturas').innerHTML = response.ventas;
							document.getElementById('total_cfdi_ventas').innerHTML = response.total_cfdi + ' CFDI encontrados.';
							document.getElementById('ingresos_ventas').innerHTML = 'Ingresos: <b>$' + response.ingresos;
							document.getElementById('iva_ventas').innerHTML = 'IVA: <b>$' + response.iva;
							document.getElementById('total_ventas').innerHTML = 'Total: <b>$' + response.total;
							
							$("#catalogoFacturas tr").click(function()
							{ 
								$(this).addClass('selected').siblings().removeClass('selected');    
								CFDI_ID = $(this).find('td:first-child').html();
								CFDI_UUID = $(this).find('td:nth-child(2)').html();
								RECEPTOR_EMAIL = $(this).find('td:nth-child(4)').html();
								EMISOR_ID = $(this).find('td:nth-child(5)').html();
							});
						}
					});
				});
				
				var modalVentas = document.getElementById('ventanaModalVentas');
				var spanVentas = document.getElementsByClassName('closeVentas')[0];
				var botonVentas = document.getElementById('botonModalVentas');
				
				var modalFiltrar = document.getElementById('ventanaModalFiltrar');
				var spanFiltrar = document.getElementsByClassName('closeFiltrar')[0];
				var botonFiltrar = document.getElementById('botonModalFiltrar');
				
				function mostrarVentanaVentas(texto)
				{
					modalVentas.style.display = "block";
					document.getElementById('tituloModalVentas').innerHTML = 'Aviso';
					document.getElementById('textoModalVentas').innerHTML = texto;
				}
				
				botonVentas.onclick = function()
				{
					modalVentas.style.display = "none";
					document.getElementById('tituloModalVentas').innerHTML = '';
					document.getElementById('textoModalVentas').innerHTML = '';
				}
				
				spanVentas.onclick = function()
				{
					modalVentas.style.display = "none";
					document.getElementById('tituloModalVentas').innerHTML = '';
					document.getElementById('textoModalVentas').innerHTML = '';
				}
				
				function mostrarVentanaFiltrar()
				{
					modalFiltrar.style.display = "block";
				}
				
				botonFiltrar.onclick = function()
				{
					modalFiltrar.style.display = "none";
				}
				
				spanFiltrar.onclick = function()
				{
					modalFiltrar.style.display = "none";
				}
				
				window.onclick = function(event)
				{
					if (event.target == modalVentas)
					{
						modalVentas.style.display = "none";
						document.getElementById('textoModalVentas').innerHTML ='';
					}
					
					if (event.target == modalFiltrar)
					{
						modalFiltrar.style.display = "none";
					}
				}
			});
		</script>
	<?php
}

add_action('wp_ajax_realvirtual_woocommerce_enviar', 'realvirtual_woocommerce_enviar_callback');
add_action('wp_ajax_nopriv_realvirtual_woocommerce_enviar', 'realvirtual_woocommerce_enviar_callback');

function realvirtual_woocommerce_enviar_callback()
{
	global $sistema, $nombreSistema, $nombreSistemaAsociado, $urlSistemaAsociado, $sitioOficialSistema, $post;
	
	$CFDI_ID = sanitize_text_field($_POST['CFDI_ID']);
	update_post_meta($post->ID, 'CFDI_ID', $CFDI_ID);
	
	$EMISOR_ID = sanitize_text_field($_POST['EMISOR_ID']);
	update_post_meta($post->ID, 'EMISOR_ID', $EMISOR_ID);
	
	$RECEPTOR_EMAIL = sanitize_text_field($_POST['RECEPTOR_EMAIL']);
	update_post_meta($post->ID, 'RECEPTOR_EMAIL', $RECEPTOR_EMAIL);
	
	$CFDI_ID 			= $_POST['CFDI_ID'];
	$EMISOR_ID 			= $_POST['EMISOR_ID'];
	$RECEPTOR_EMAIL 	= $_POST['RECEPTOR_EMAIL'];
	
	if(!intval($CFDI_ID))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El ID del CFDI no es válido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!intval($EMISOR_ID))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El ID del Emisor no es válido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!filter_var($RECEPTOR_EMAIL, FILTER_VALIDATE_EMAIL))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El correo electrónico del Receptor tiene un formato inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}

	$opcion = 'EnviarCFDI';
	
	$parametros = array
	(
		'OPCION' => $opcion,
		'CFDI_ID' => $CFDI_ID,
		'EMISOR_ID' => $EMISOR_ID,
		'RECEPTOR_EMAIL' => $RECEPTOR_EMAIL
	);
	
	$params = array
	(
		'method' => 'POST',
		'timeout' => 45,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking' => true,
		'headers' => array(),
		'body' => $parametros,
		'cookies' => array()
	);
			
	try
	{
		$response = wp_remote_post($urlSistemaAsociado.'Php/Archivos_Proyecto/realvirtual_woocommerce_plugin.php', $params);
				
		if(is_array($response))
		{
			$header = $response['headers'];
			$body = $response['body'];
		}
	}
	catch(Exception $e)
	{
		print('Exception occured: ' . $e->getMessage());
	}
	
	$respuestaEnvio = json_decode($body);
	
	if($respuestaEnvio->success == false)
	{
		$respuesta = array
		(
			'success' => false,
			'message' => $respuestaEnvio->message
		);
	}
	else
	{
		$respuesta = array
		(
			'success' => true,
			'message' => $respuestaEnvio->message
		);
	}
		
	echo json_encode($respuesta, JSON_PRETTY_PRINT);
	wp_die();
}

add_action('wp_ajax_realvirtual_woocommerce_cancelar', 'realvirtual_woocommerce_cancelar_callback');
add_action('wp_ajax_nopriv_realvirtual_woocommerce_cancelar', 'realvirtual_woocommerce_cancelar_callback');

function realvirtual_woocommerce_cancelar_callback()
{
	global $sistema, $nombreSistema, $nombreSistemaAsociado, $urlSistemaAsociado, $sitioOficialSistema, $post;
	
	$cuenta = RealVirtualWooCommerceCuenta::cuentaEntidad();
	
	$CFDI_UUID = sanitize_text_field($_POST['CFDI_UUID']);
	update_post_meta($post->ID, 'CFDI_UUID', $CFDI_UUID);
	
	$EMISOR_ID = sanitize_text_field($_POST['EMISOR_ID']);
	update_post_meta($post->ID, 'EMISOR_ID', $EMISOR_ID);
	
	$CFDI_UUID 			= $_POST['CFDI_UUID'];
	$EMISOR_ID 			= $_POST['EMISOR_ID'];
	$EMISOR_RFC 		= $cuenta['rfc'];
	$SISTEMA 			= $sistema;
	
	if(!($CFDI_UUID != ''))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El UUID del CFDI no es válido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!intval($EMISOR_ID))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El ID del Emisor no es válido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!preg_match("/^([A-Z]|&|Ñ){3,4}[0-9]{2}[0-1][0-9][0-3][0-9]([A-Z]|[0-9]){2}([0-9]|A){1}$/", $EMISOR_RFC))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El RFC del Emisor tiene un formato inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	$opcion = 'CancelarCFDI';
	
	$parametros = array
	(
		'OPCION' => $opcion,
		'CFDI_UUID' => $CFDI_UUID,
		'EMISOR_ID' => $EMISOR_ID,
		'EMISOR_RFC' => $EMISOR_RFC,
		'SISTEMA' => $SISTEMA
	);
	
	$params = array
	(
		'method' => 'POST',
		'timeout' => 45,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking' => true,
		'headers' => array(),
		'body' => $parametros,
		'cookies' => array()
	);
			
	try
	{
		$response = wp_remote_post($urlSistemaAsociado.'Php/Archivos_Proyecto/realvirtual_woocommerce_plugin.php', $params);
				
		if(is_array($response))
		{
			$header = $response['headers'];
			$body = $response['body'];
		}
	}
	catch(Exception $e)
	{
		print('Exception occured: ' . $e->getMessage());
	}
	
	$respuestaCancelacion = json_decode($body);
	
	if($respuestaCancelacion->success == false)
	{
		$respuesta = array
		(
			'success' => false,
			'message' => $respuestaCancelacion->message
		);
	}
	else
	{
		$respuesta = array
		(
			'success' => true,
			'message' => $respuestaCancelacion->message
		);
	}
	
	echo json_encode($respuesta, JSON_PRETTY_PRINT);
	wp_die();
}

add_action('wp_ajax_realvirtual_woocommerce_filtrar', 'realvirtual_woocommerce_filtrar_callback');
add_action('wp_ajax_nopriv_realvirtual_woocommerce_filtrar', 'realvirtual_woocommerce_filtrar_callback');

function realvirtual_woocommerce_filtrar_callback()
{
	global $sistema, $nombreSistema, $nombreSistemaAsociado, $urlSistemaAsociado, $sitioOficialSistema, $post;
	
	$cuenta = RealVirtualWooCommerceCuenta::cuentaEntidad();
	
	$AÑO = sanitize_text_field($_POST['AÑO']);
	update_post_meta($post->ID, 'AÑO', $AÑO);
	
	$MES = sanitize_text_field($_POST['MES']);
	update_post_meta($post->ID, 'MES', $MES);
	
	$ESTADO = sanitize_text_field($_POST['ESTADO']);
	update_post_meta($post->ID, 'ESTADO', $ESTADO);
	
	$NUMERO_PEDIDO = sanitize_text_field($_POST['NUMERO_PEDIDO']);
	update_post_meta($post->ID, 'NUMERO_PEDIDO', $NUMERO_PEDIDO);
	
	$CLIENTE = sanitize_text_field($_POST['CLIENTE']);
	update_post_meta($post->ID, 'CLIENTE', $CLIENTE);
	
	$AÑO 				= $_POST['AÑO'];
	$MES 				= $_POST['MES'];
	$ESTADO 			= $_POST['ESTADO'];
	$NUMERO_PEDIDO 		= $_POST['NUMERO_PEDIDO'];
	$CLIENTE 			= $_POST['CLIENTE'];
	
	$fechaDesde = '';
	$fechaHasta = '';
	
	if($MES > '0')
	{
		$fechaDesde = $AÑO.'-'.$MES.'-01';
		$fecha = new DateTime($fechaDesde); 
		$fechaHasta = $fecha->format('Y-m-t');
	}
	else if($MES == '0')
	{
		$fechaDesde = $AÑO.'-'.$MES.'-01';
		$fechaHasta = $AÑO.'-12-31';
	}
	
	$filtro = $fechaDesde.'|'.$fechaHasta.'|'.$ESTADO.'|'.$NUMERO_PEDIDO.'|'.$CLIENTE;
	$datosVentas = RealVirtualWooCommerceCFDI::obtenerVentas($cuenta['rfc'], $cuenta['usuario'], $cuenta['clave'], $filtro, $urlSistemaAsociado);
	
	if($datosVentas->success == false)
	{
		$respuesta = array
		(
			'success' => false,
			'message' => $datosVentas->message
		);
	}
	else
	{
		$respuesta = array
		(
			'success' => true,
			'ventas' => $datosVentas->VENTAS,
			'total_cfdi' => $datosVentas->TOTAL_CFDI,
			'ingresos' => $datosVentas->INGRESOS,
			'iva' => $datosVentas->IVA,
			'total' => $datosVentas->TOTAL
		);
	}
	
	echo json_encode($respuesta, JSON_PRETTY_PRINT);
	wp_die();
}

function realvirtual_woocommerce_cuenta()
{
	global $sistema, $nombreSistema, $nombreSistemaAsociado, $urlSistemaAsociado, $sitioOficialSistema;
	
	$cuenta = RealVirtualWooCommerceCuenta::cuentaEntidad();
	
	?>
		<form id="realvirtual_woocommerce_cuenta" method="post" style="background-color: #FFFFFF; padding: 20px;">
			<label><font color="#006fa8" size="5"><b>Mi Cuenta</b></font></label>
			<br/><br/>
			<label><font color="#000000" size="4"><b>Inicio de sesión</b></font></label>
			<br/>
			<label><font color="#505050" size="2">Ingresa los datos de acceso de tu cuenta en <a href="<?php echo esc_url($urlSistemaAsociado); ?>" target="_blank"><b><?php echo esc_html($nombreSistemaAsociado); ?></b></a> para que <b><?php echo esc_html($nombreSistema); ?></b> pueda conectarse con ella.</font></label>
			<br/><br/><br/>
			<center>
			<div class="rowCuenta">
				<label><font color="#000000">* RFC</font></label>
				<input type="text" id="rfc" name="rfc" value="<?php echo esc_html($cuenta['rfc']); ?>">
				<br/>		
				<label><font color="#000000">* Usuario</font></label>
				<input type="text" id="usuario" name="usuario" value="<?php echo esc_html($cuenta['usuario']); ?>">
				<br/>			
				<label><font color="#000000">* Clave Cifrada</font></label>
				<input type="text" id="clave" name="clave" value="<?php echo esc_html($cuenta['clave']); ?>">
			</div>
			<br/>
			</center>
			<label><font color="#505050" size="2" style="font-style: italic;"><b>NOTAS:</b><br/><br/>1) Obtén tus datos de acceso en la sección <b>"<?php echo esc_html($nombreSistema); ?> > Datos de acceso"</b> de tu sistema <a href="<?php echo esc_url($urlSistemaAsociado); ?>" target="_blank"><b><?php echo esc_html($nombreSistemaAsociado); ?></b></a>.<br/>2) Por seguridad, siempre que actualices este plugin será necesario ingresar de nuevo tus datos de acceso.<br/>3) Al pulsar el botón Guardar, se recuperará tu configuración del plugin realizada en la sección <b>Configuración</b> en caso de existir previamente guardada internamente en <b><?php echo esc_html($nombreSistemaAsociado); ?></b></a>.</font></label>
			<br/><br/>
			<center>
			<div>
				<input type="button" style="background-color:#006fa8;" class="boton" id="realvirtual_woocommerce_enviar_cuenta"  value="Guardar" />
				<img id="cargandoCuenta" src="<?php echo esc_url(plugin_dir_url( __FILE__ )."/assets/realvirtual_woocommerce_cargando.gif"); ?>" alt="Cargando" height="32" width="32" style="visibility: hidden;">
			</div>
			</center>
		</form>
		
		<div id="ventanaModalCuenta" class="modalCuenta">
			<div class="modal-contentCuenta">
				<span class="closeCuenta">&times;</span>
				<br/>
					<center><font color="#000000" size="5"><b>
						<div id="tituloModalCuenta"></div>
					</b></font></center>
					<br/>
					<font color="#000000" size="3">
						<div id="textoModalCuenta"></div>
					</font>
					<br/>
					<center><input type="button" style="background-color:#006fa8;" class="boton" id="botonModalCuenta" value="Aceptar" /></center>
			</div>
		</div>
    <?php
}

function realvirtual_woocommerce_configuracion()
{
	global $sistema, $nombreSistema, $nombreSistemaAsociado, $urlSistemaAsociado, $sitioOficialSistema;
	
	$configuracion = RealVirtualWooCommerceConfiguracion::configuracionEntidad();
	
	?>
		<form id="realvirtual_woocommerce_configuracion" method="post" style="background-color: #FFFFFF; padding: 20px;">
			<label><font color="#006fa8" size="5"><b>Configuración</b></font></label>
			<br/><br/>
			<label><font color="#000000" size="4"><b>Serie de facturación</b></font></label>
			<br/>
			<label><font color="#505050" size="2">Ingresa la serie de facturación que desees para la emisión de CFDI.</font></label>
			<br/><br/>
			<center>
			<div class="rowConfiguracion">
				<label><font color="#000000">* Serie de facturación</font></label>
				<input type="text" id="serie" name="serie" value="<?php echo esc_html($configuracion['serie']); ?>">
			</div>
			</center>
			<br/>
			<label><font color="#000000" size="4"><b>Módulo de facturación para clientes</b></font></label>
			<br/>
			<label><font color="#505050" size="2">Personaliza el módulo de facturación que utilizan tus clientes.</font></label>
			<br/><br/>
			<center>
			<div class="rowConfiguracion">
				<label><font color="#000000">* Estado del pedido para permitir facturación</font></label>
				<select id="estado_orden" name="estado_orden">
				<?php 
					$estado_orden = $configuracion['estado_orden'];
					
					if($estado_orden == 'processing')
					{
					?>
						<option value="processing" selected>Procesando (recomendado)</option>
					<?php 
					}
					else
					{
					?>
						<option value="processing">Procesando (recomendado)</option>
					<?php 
					}
					
					if($estado_orden == 'completed')
					{
					?>
						<option value="completed" selected>Completado</option>
					<?php 
					}
					else
					{
					?>
						<option value="completed">Completado</option>
					<?php 
					}
				?>
				</select>
				<br/>
				<label><font color="#000000">Título</font></label>
				<input type="text" id="titulo" name="titulo" value="<?php echo esc_html($configuracion['titulo']); ?>">
				<br/>
				<label><font color="#000000">Texto descriptivo</font></label>
				<input type="text" id="descripcion" name="descripcion" value="<?php echo esc_html($configuracion['descripcion']); ?>">
			</div>
			</center>
			<div class="rowConfiguracionColores">
				<br/>
				<label><font color="#000000">Color de fondo en encabezado</font></label>
				<input type="text" id="color_fondo_encabezado_hexadecimal" name="color_fondo_encabezado_hexadecimal" value="<?php echo esc_html($configuracion['color_fondo_encabezado']); ?>" placeholder="Valor hexadecimal con símbolo #">
				<input type="color" id="color_fondo_encabezado" name="color_fondo_encabezado" value="<?php echo esc_html($configuracion['color_fondo_encabezado']); ?>">
				<br/>
				<label><font color="#000000">Color de texto en encabezado</font></label>
				<input type="text" id="color_texto_encabezado_hexadecimal" name="color_texto_encabezado_hexadecimal" value="<?php echo esc_html($configuracion['color_texto_encabezado']); ?>" placeholder="Valor hexadecimal con símbolo #">
				<input type="color" id="color_texto_encabezado" name="color_texto_encabezado" value="<?php echo esc_html($configuracion['color_texto_encabezado']); ?>">
				<br/>
				<label><font color="#000000">Color de fondo en formulario</font></label>
				<input type="text" id="color_fondo_formulario_hexadecimal" name="color_fondo_formulario_hexadecimal" value="<?php echo esc_html($configuracion['color_fondo_formulario']); ?>" placeholder="Valor hexadecimal con símbolo #">
				<input type="color" id="color_fondo_formulario" name="color_fondo_formulario" value="<?php echo esc_html($configuracion['color_fondo_formulario']); ?>">
				<br/>
				<label><font color="#000000">Color de texto en formulario</font></label>
				<input type="text" id="color_texto_formulario_hexadecimal" name="color_texto_formulario_hexadecimal" value="<?php echo esc_html($configuracion['color_texto_formulario']); ?>" placeholder="Valor hexadecimal con símbolo #">
				<input type="color" id="color_texto_formulario" name="color_texto_formulario" value="<?php echo esc_html($configuracion['color_texto_formulario']); ?>">
				<br/>
				<label><font color="#000000">Color de texto en campos del formulario</font></label>
				<input type="text" id="color_texto_controles_formulario_hexadecimal" name="color_texto_controles_formulario_hexadecimal" value="<?php echo esc_html($configuracion['color_texto_controles_formulario']); ?>" placeholder="Valor hexadecimal con símbolo #">
				<input type="color" id="color_texto_controles_formulario" name="color_texto_controles_formulario" value="<?php echo esc_html($configuracion['color_texto_controles_formulario']); ?>">
				<br/>	
				<label><font color="#000000">Color de botones</font></label>
				<input type="text" id="color_boton_hexadecimal" name="color_boton_hexadecimal" value="<?php echo esc_html($configuracion['color_boton']); ?>" placeholder="Valor hexadecimal con símbolo #">
				<input type="color" id="color_boton" name="color_boton" value="<?php echo esc_html($configuracion['color_boton']); ?>">
				<br/>
				<label><font color="#000000">Color de texto en botones</font></label>
				<input type="text" id="color_texto_boton_hexadecimal" name="color_texto_boton_hexadecimal" value="<?php echo esc_html($configuracion['color_texto_boton']); ?>" placeholder="Valor hexadecimal con símbolo #">
				<input type="color" id="color_texto_boton" name="color_texto_boton" value="<?php echo esc_html($configuracion['color_texto_boton']); ?>">
			</div>
			<br/>
			<label><font color="#505050" size="2" style="font-style: italic;"><b>NOTAS:</b><br/><br/>1) Para la emisión de CFDI con el plugin es necesario haber configurado previamente todos tus datos en la sección <b>Mi Cuenta</b> del sistema de facturación <a href="<?php echo esc_url($urlSistemaAsociado); ?>" target="_blank"><b><?php echo esc_html($nombreSistemaAsociado); ?></b></a>.<br/>2) Al pulsar el botón Guardar, tu configuración se guardará tanto en tu Wordpress como de manera interna en <a href="<?php echo esc_url($urlSistemaAsociado); ?>" target="_blank"><b><?php echo esc_html($nombreSistemaAsociado); ?></b></a>. Así, en caso de extravío o siempre que actualices este plugin e ingreses tus datos de acceso en la sección <b>Mi Cuenta</b>, se recuperará tu configuración automáticamente.</font></label>
			<br/><br/>
			<center>
			<div>
				<input type="button" style="background-color:#006fa8;" class="boton" id="realvirtual_woocommerce_enviar_configuracion"  value="Guardar" />
				<img id="cargandoConfiguracion" src="<?php echo esc_url(plugin_dir_url( __FILE__ )."/assets/realvirtual_woocommerce_cargando.gif"); ?>" alt="Cargando" height="32" width="32" style="visibility: hidden;">
			</div>
			</center>
		</form>
		
		<div id="ventanaModalConfiguracion" class="modalConfiguracion">
			<div class="modal-contentConfiguracion">
				<span class="closeConfiguracion">&times;</span>
				<br/>
				<center>
					<font color="#000000" size="5"><b>
						<div id="tituloModalConfiguracion"></div>
					</b></font>
					<br/>
					<font color="#000000" size="3">
						<div id="textoModalConfiguracion"></div>
					</font>
					<br/>
					<input type="button" style="background-color:#006fa8;" class="boton" id="botonModalConfiguracion" value="Aceptar" />
				</center>
			</div>
		</div>
		
		<script type="text/javascript">
			jQuery(document).ready(function($)
			{
				$("#color_fondo_encabezado_hexadecimal").change(function(){
					var valor = document.getElementById('color_fondo_encabezado_hexadecimal').value;
					
					re = /(^#[0-9A-F]{6}$)/i;
	   
					if (re.test(valor) == false)
					{
						mostrarVentanaConfiguracion2('Por favor, ingresa un valor hexadecimal válido.');
						document.getElementById('color_fondo_encabezado_hexadecimal').value = '#FFFFFF';
						document.getElementById('color_fondo_encabezado').value = '#FFFFFF';
					}
					else
						document.getElementById('color_fondo_encabezado').value = valor;
				});
				
				$("#color_fondo_encabezado").change(function(){
					var valor = document.getElementById('color_fondo_encabezado').value;
					document.getElementById('color_fondo_encabezado_hexadecimal').value = valor;
				});
				
				$("#color_texto_encabezado_hexadecimal").change(function(){
					var valor = document.getElementById('color_texto_encabezado_hexadecimal').value;
					
					re = /(^#[0-9A-F]{6}$)/i;
	   
					if (re.test(valor) == false)
					{
						mostrarVentanaConfiguracion2('Por favor, ingresa un valor hexadecimal válido.');
						document.getElementById('color_texto_encabezado_hexadecimal').value = '#FFFFFF';
						document.getElementById('color_texto_encabezado').value = '#FFFFFF';
					}
					else
						document.getElementById('color_texto_encabezado').value = valor;
				});
				
				$("#color_texto_encabezado").change(function(){
					var valor = document.getElementById('color_texto_encabezado').value;
					document.getElementById('color_texto_encabezado_hexadecimal').value = valor;
				});
				
				$("#color_fondo_formulario_hexadecimal").change(function(){
					var valor = document.getElementById('color_fondo_formulario_hexadecimal').value;
					
					re = /(^#[0-9A-F]{6}$)/i;
	   
					if (re.test(valor) == false)
					{
						mostrarVentanaConfiguracion2('Por favor, ingresa un valor hexadecimal válido.');
						document.getElementById('color_fondo_formulario_hexadecimal').value = '#FFFFFF';
						document.getElementById('color_fondo_formulario').value = '#FFFFFF';
					}
					else
						document.getElementById('color_fondo_formulario').value = valor;
				});
				
				$("#color_fondo_formulario").change(function(){
					var valor = document.getElementById('color_fondo_formulario').value;
					document.getElementById('color_fondo_formulario_hexadecimal').value = valor;
				});
				
				$("#color_texto_formulario_hexadecimal").change(function(){
					var valor = document.getElementById('color_texto_formulario_hexadecimal').value;
					
					re = /(^#[0-9A-F]{6}$)/i;
	   
					if (re.test(valor) == false)
					{
						mostrarVentanaConfiguracion2('Por favor, ingresa un valor hexadecimal válido.');
						document.getElementById('color_texto_formulario_hexadecimal').value = '#FFFFFF';
						document.getElementById('color_texto_formulario').value = '#FFFFFF';
					}
					else
						document.getElementById('color_texto_formulario').value = valor;
				});
				
				$("#color_texto_formulario").change(function(){
					var valor = document.getElementById('color_texto_formulario').value;
					document.getElementById('color_texto_formulario_hexadecimal').value = valor;
				});
				
				$("#color_texto_controles_formulario_hexadecimal").change(function(){
					var valor = document.getElementById('color_texto_controles_formulario_hexadecimal').value;
					
					re = /(^#[0-9A-F]{6}$)/i;
	   
					if (re.test(valor) == false)
					{
						mostrarVentanaConfiguracion2('Por favor, ingresa un valor hexadecimal válido.');
						document.getElementById('color_texto_controles_formulario_hexadecimal').value = '#FFFFFF';
						document.getElementById('color_texto_controles_formulario').value = '#FFFFFF';
					}
					else
						document.getElementById('color_texto_controles_formulario').value = valor;
				});
				
				$("#color_texto_controles_formulario").change(function(){
					var valor = document.getElementById('color_texto_controles_formulario').value;
					document.getElementById('color_texto_controles_formulario_hexadecimal').value = valor;
				});
				
				$("#color_boton_hexadecimal").change(function(){
					var valor = document.getElementById('color_boton_hexadecimal').value;
					
					re = /(^#[0-9A-F]{6}$)/i;
	   
					if (re.test(valor) == false)
					{
						mostrarVentanaConfiguracion2('Por favor, ingresa un valor hexadecimal válido.');
						document.getElementById('color_boton_hexadecimal').value = '#FFFFFF';
						document.getElementById('color_boton').value = '#FFFFFF';
					}
					else
						document.getElementById('color_boton').value = valor;
				});
				
				$("#color_boton").change(function(){
					var valor = document.getElementById('color_boton').value;
					document.getElementById('color_boton_hexadecimal').value = valor;
				});
				
				$("#color_texto_boton_hexadecimal").change(function(){
					var valor = document.getElementById('color_texto_boton_hexadecimal').value;
					
					re = /(^#[0-9A-F]{6}$)/i;
	   
					if (re.test(valor) == false)
					{
						mostrarVentanaConfiguracion2('Por favor, ingresa un valor hexadecimal válido.');
						document.getElementById('color_texto_boton_hexadecimal').value = '#FFFFFF';
						document.getElementById('color_texto_boton').value = '#FFFFFF';
					}
					else
						document.getElementById('color_texto_boton').value = valor;
				});
				
				$("#color_texto_boton").change(function(){
					var valor = document.getElementById('color_texto_boton').value;
					document.getElementById('color_texto_boton_hexadecimal').value = valor;
				});
				
				var modalConfiguracion2 = document.getElementById('ventanaModalConfiguracion');
				var spanConfiguracion2 = document.getElementsByClassName('closeConfiguracion')[0];
				var botonConfiguracion2 = document.getElementById('botonModalConfiguracion');
				
				function mostrarVentanaConfiguracion2(texto)
				{
					modalConfiguracion2.style.display = "block";
					document.getElementById('tituloModalConfiguracion').innerHTML = 'Aviso';
					document.getElementById('textoModalConfiguracion').innerHTML = texto;
				}
				
				botonConfiguracion2.onclick = function()
				{
					modalConfiguracion2.style.display = "none";
					document.getElementById('tituloModalConfiguracion').innerHTML = '';
					document.getElementById('textoModalConfiguracion').innerHTML = '';
				}
				
				spanConfiguracion2.onclick = function()
				{
					modalConfiguracion2.style.display = "none";
					document.getElementById('tituloModalConfiguracion').innerHTML = '';
					document.getElementById('textoModalConfiguracion').innerHTML = '';
				}
			});
		</script>
    <?php
}

function realvirtual_woocommerce_soporte()
{
	global $sistema, $nombreSistema, $nombreSistemaAsociado, $urlSistemaAsociado, $sitioOficialSistema;
	
	$configuracion = RealVirtualWooCommerceConfiguracion::configuracionEntidad();
	
	?>
		<div style="background-color: #FFFFFF; padding: 20px;">
		<label><font color="#006fa8" size="5"><b>Soporte Técnico</b></font></label>
		<br/><br/>
		<label><font color="#000000" size="4"><b>Shortcode</b></font></label>
		<br/>
		<label><font color="#505050" size="2">Crea una nueva página e ingresa el siguiente shortcode para mostrar el plugin <b><?php echo esc_html($nombreSistema); ?></b> en tu sitio web.<br/><b>[<?php echo esc_html(strtolower($sistema)); ?>_woocommerce_formulario]</b>
		</font></label>
		<br/><br/>
		<label><font color="#000000" size="4"><b>Manual de Usuario</b></font></label>
		<br/>
		<label><font color="#505050" size="2">Consulta nuestro manual de usuario para conocer todo acerca de <b><?php echo esc_html($nombreSistema); ?></b>.
		</font></label>
		<br/><br/>
		<a href="<?php echo esc_url($urlSistemaAsociado); ?><?php echo ($sistema == 'RVCFDI') ? esc_html('plugin_rv') : esc_html('plugin_lfe'); ?>/<?php echo ($sistema == 'RVCFDI') ? esc_html('Manual-RVCFDI_para_WooCommerce.pdf') : esc_html('Manual-LFECFDI_para_WooCommerce.pdf'); ?>" target="_blank"><input type="button" style="background-color:#006fa8;" class="boton" value="Ver el Manual" /></a>
		<br/><br/>
		<label><font color="#000000" size="4"><b>Chat en Línea</b></font></label>
		<br/>
		<label><font color="#505050" size="2">Estimado usuario, nuestro servicio de Soporte Técnico no tiene costo.
		Estás a punto de entrar a nuestro Sitio Oficial para utilizar nuestro servicio de Chat en Línea.
		Cuando contactes con un asesor, por favor menciona que utilizas el sistema <b><?php echo esc_html($nombreSistema); ?></b> seguido de tu solicitud para poder ubicar fácilmente el sistema que utilizas de entre varios que manejamos y poder brindarte un mejor servicio.
		</font></label>
		<br/><br/>
		<a href="<?php echo esc_url($sitioOficialSistema); ?>" target="_blank"><input type="button" style="background-color:#006fa8;" class="boton" value="Ir al Chat" /></a>
		</div>
	<?php
}

function realvirtual_woocommerce_preguntas()
{
	global $sistema, $nombreSistema, $nombreSistemaAsociado, $urlSistemaAsociado, $sitioOficialSistema;
	
	$configuracion = RealVirtualWooCommerceConfiguracion::configuracionEntidad();
	
	?>
		<div style="background-color: #FFFFFF; padding: 20px;">
		<label><font color="#006fa8" size="5"><b>Preguntas Frecuentes</b></font></label>
		<br/><br/>
		<label><font color="#000000" size="4"><b>¿Cómo puedo mostrar el módulo de facturación para mis clientes en mi sitio web?</b></font></label>
		<br/>
		<label><font color="#505050" size="2">Añade una nueva página en tu wordpress e ingresa el shortcode <font color="#000000" size="2"><b>[<?php echo esc_html(strtolower($sistema)); ?>_woocommerce_formulario]</b></font> para mostrar el módulo de facturación para clientes en tu sitio web.
		</font></label>
		<br/><br/>
		<label><font color="#000000" size="4"><b>¿Puedo realizar pruebas con el plugin?</b></font></label>
		<br/>
		<label><font color="#505050" size="2">Sí, usted dispone de un ambiente de pruebas del plugin. Para ello, ingrese los siguientes datos de acceso en la sección <b>Mi Cuenta</b> del plugin.
		<br/><br/>
		<b>RFC:</b> LAN7008173R5
		<br/><b>Usuario:</b> <?php echo ($sistema == 'RVCFDI') ? esc_html('PRUEBASRV') : esc_html('LFEPRUEBAS'); ?>
		<br/><b>Clave cifrada:</b> fafefdfafde094c3f9ac29a682a7072edda868c8a91e063a800b58a02c3457c3
		</font></label>
		<br/><br/>
		<label><font color="#000000" size="4"><b>¿Puedo utilizar este plugin en otra plataforma que no sea wordpress?</b></font></label>
		<br/>
		<label><font color="#505050" size="2">No, el plugin únicamente es compatible con wordpress. Además, es necesario tener el plugin woocommerce para poder funcionar correctamente.
		</font></label>
		<br/><br/>
		<label><font color="#000000" size="4"><b>¿Puedo emitir CFDI desde el panel de administración del plugin en wordpress?</b></font></label>
		<br/>
		<label><font color="#505050" size="2">No, la emisión de CFDI únicamente se puede realizar en el módulo de facturación para clientes desde tu sitio web.
		</font></label>
		<br/><br/>
		<label><font color="#000000" size="4"><b>¿Puedo administrar mis CFDI emitidos tanto en el panel de administración del plugin en wordpress como en el sistema de facturación <a href="<?php echo esc_url($urlSistemaAsociado); ?>" target="_blank"><b><?php echo esc_html($nombreSistemaAsociado); ?></b></a>?</b></font></label>
		<br/>
		<label><font color="#505050" size="2">Sí, es posible administrar los CFDI emitidos en ambas plataformas.
		</font></label>
		<br/><br/>
		<label><font color="#000000" size="4"><b>¿Por qué no aparecen en el panel de administración del plugin en wordpress los CFDI emitidos con el sistema de facturación <a href="<?php echo esc_url($urlSistemaAsociado); ?>" target="_blank"><b><?php echo esc_html($nombreSistemaAsociado); ?></b></a>?</b></font></label>
		<br/>
		<label><font color="#505050" size="2">Únicamente se pueden visualizar en el panel de administración del plugin en wordpress los CFDI emitidos con el plugin. Sin embargo, en el sistema de facturación se pueden visualizar los CFDI emitidos tanto con el sistema de facturación como con el plugin.
		</font></label>
		<br/><br/>
		<label><font color="#000000" size="4"><b>¿Cómo se ve un CFDI emitido en formato PDF?</b></font></label>
		<br/>
		<label><font color="#505050" size="2">El aspecto visual de un CFDI emitido tanto con el plugin como con el sistema de facturación se muestra haciendo clic en el siguiente enlace.
		<br/><a href="<?php echo esc_url(plugin_dir_url(__FILE__)."assets/ejemplo.pdf"); ?>" target="_blank">Haz clic aquí para ver un ejemplo de un CFDI emitido</a>
		</font></label>
		<br/><br/>
		<label><font color="#000000" size="4"><b>¿Cómo personalizo mi logotipo para que aparezca en la versión PDF de los CFDI emitidos?</b></font></label>
		<br/>
		<label><font color="#505050" size="2">Esta configuración se realiza directamente en el sistema de facturación <a href="<?php echo esc_url($urlSistemaAsociado); ?>" target="_blank"><b><?php echo esc_html($nombreSistemaAsociado); ?></b></a> en la sección <b>Mi Cuenta > Logotipo</b>.
		</font></label>
		<br/><br/>
		<label><font color="#000000" size="4"><b>¿Dónde cargo mi Certificado de Sello Digital, Llave Privada y Contraseña para poder emitir CFDI?</b></font></label>
		<br/>
		<label><font color="#505050" size="2">Esta configuración se realiza directamente en el sistema de facturación <a href="<?php echo esc_url($urlSistemaAsociado); ?>" target="_blank"><b><?php echo esc_html($nombreSistemaAsociado); ?></b></a> en la sección <b>Mi Cuenta > Certificados</b>.
		</font></label>
		<br/><br/>
		<label><font color="#000000" size="4"><b>¿Qué puedo hacer si aún no tengo cuenta en el sistema de facturación <a href="<?php echo esc_url($urlSistemaAsociado); ?>" target="_blank"><b><?php echo esc_html($nombreSistemaAsociado); ?></b></a>?</b></font></label>
		<br/>
		<label><font color="#505050" size="2">Por favor, vaya a la sección <b>Soporte Técnico</b> de este plugin y contacte a un asesor de ventas utilizando nuestro servicio de Chat en Línea para recibir información en un horario de 9 AM a 7 PM (Tiempo de la Ciudad de México).
		</font></label>
		<br/><br/>
		<label><font color="#000000" size="4"><b>¿Puedo emitir un CFDI en el sistema de facturación <a href="<?php echo esc_url($urlSistemaAsociado); ?>" target="_blank"><b><?php echo esc_html($nombreSistemaAsociado); ?></b></a> con la misma serie que utilizo en el plugin?</b></font></label>
		<br/>
		<label><font color="#505050" size="2">Sí. Sin embargo, no aparecerá en la sección <b>Ventas</b> del panel de administración del plugin en wordpress porque el CFDI habrá sido emitido con el sistema de facturación <a href="<?php echo esc_url($urlSistemaAsociado); ?>" target="_blank"><b><?php echo esc_html($nombreSistemaAsociado); ?></b></a>.
		</font></label>
		<br/><br/>
		<label><font color="#000000" size="4"><b>¿El plugin cumple con todos los requisitos que el SAT establece para la correcta estructura de un CFDI con valor fiscal?</b></font></label>
		<br/>
		<label><font color="#505050" size="2">Sí. El plugin se encuentra actualizado para emitir CFDI que cumplan con la estructura establecida por el SAT. Además, ante cualquier cambio establecido por el SAT actualizaremos el plugin y será posible actualizarlo a la versión más reciente desde tu wordpress.
		</font></label>
		<br/><br/>
		</div>
	<?php
}

add_action('wp_ajax_realvirtual_woocommerce_guardar_cuenta', 'realvirtual_woocommerce_guardar_cuenta_callback');

function realvirtual_woocommerce_guardar_cuenta_callback()
{
	global $wpdb, $sistema, $nombreSistema, $nombreSistemaAsociado, $urlSistemaAsociado, $sitioOficialSistema, $post;

	$rfc = sanitize_text_field($_POST['rfc']);
	update_post_meta($post->ID, 'rfc', $rfc);
	
	$usuario = sanitize_text_field($_POST['usuario']);
	update_post_meta($post->ID, 'usuario', $usuario);
	
	$clave = sanitize_text_field($_POST['clave']);
	update_post_meta($post->ID, 'clave', $clave);
	
	if(!preg_match("/^([A-Z]|&|Ñ){3,4}[0-9]{2}[0-1][0-9][0-3][0-9]([A-Z]|[0-9]){2}([0-9]|A){1}$/", $_POST['rfc']))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El RFC tiene un formato inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!preg_match("/^[a-zA-Z0-9\s\#\$\+\%\(\)\[\]\*\¡\!\=\\/\&\.\,\;\:\-\_\ñ\á\é\í\ó\ú\Á\É\Í\Ó\Ú\Ñ]*$/", $_POST['usuario']))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El usuario tiene un formato inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!preg_match("/^[0-9a-zA-Z]+$/", $_POST['clave']))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'La clave tiene un formato inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
    $cuenta = array
	(
        'rfc'      	=> $_POST['rfc'],
        'usuario'   => $_POST['usuario'],
        'clave'     => $_POST['clave']
    );

    $guardado = RealVirtualWooCommerceCuenta::guardarCuenta($cuenta, $_POST['rfc'], $_POST['usuario'], $_POST['clave'], $urlSistemaAsociado);

    $respuesta = array
	(
       'success' => $guardado->success,
	   'message' => $guardado->message,
	   'EMISOR_RENOVACION' => $guardado->EMISOR_RENOVACION,
	   'EMISOR_VIGENCIA' => $guardado->EMISOR_VIGENCIA,
	   'EMISOR_ESTADO' => $guardado->EMISOR_ESTADO,
	   'EMISOR_TIPO_USUARIO' => $guardado->EMISOR_TIPO_USUARIO,
	   'sistema' => $sistema
    );
	
	if($guardado->ESTADO_ORDEN != '')
	{
		$configuracion = array
		(
			'serie'       							=> trim($guardado->SERIE),
			'estado_orden'       					=> trim($guardado->ESTADO_ORDEN),
			'titulo'       							=> trim($guardado->TITULO),
			'descripcion'   						=> trim($guardado->DESCRIPCION),
			'color_fondo_encabezado'      			=> trim($guardado->COLOR_FONDO_ENCABEZADO),
			'color_texto_encabezado'       			=> trim($guardado->COLOR_TEXTO_ENCABEZADO),
			'color_fondo_formulario'      			=> trim($guardado->COLOR_FONDO_FORMULARIO),
			'color_texto_formulario'       			=> trim($guardado->COLOR_TEXTO_FORMULARIO),
			'color_texto_controles_formulario'      => trim($guardado->COLOR_TEXTO_CONTROLES_FORMULARIO),
			'color_boton'       					=> trim($guardado->COLOR_BOTON),
			'color_texto_boton'       				=> trim($guardado->COLOR_TEXTO_BOTON)
		);

		$guardado = RealVirtualWooCommerceConfiguracion::guardarConfiguracionLocal($configuracion);
	}
	
    echo json_encode($respuesta, JSON_PRETTY_PRINT);
	wp_die();
}

add_action('wp_ajax_realvirtual_woocommerce_guardar_configuracion', 'realvirtual_woocommerce_guardar_configuracion_callback');

function realvirtual_woocommerce_guardar_configuracion_callback()
{
	global $wpdb, $sistema, $nombreSistema, $nombreSistemaAsociado, $urlSistemaAsociado, $sitioOficialSistema, $post;

	$serie = sanitize_text_field($_POST['serie']);
	update_post_meta($post->ID, 'serie', $serie);
	
	$estado_orden = sanitize_text_field($_POST['estado_orden']);
	update_post_meta($post->ID, 'estado_orden', $estado_orden);
	
	$titulo = sanitize_text_field($_POST['titulo']);
	update_post_meta($post->ID, 'titulo', $titulo);
	
	$descripcion = sanitize_text_field($_POST['descripcion']);
	update_post_meta($post->ID, 'descripcion', $descripcion);
	
	$color_fondo_encabezado_hexadecimal = sanitize_text_field($_POST['color_fondo_encabezado_hexadecimal']);
	update_post_meta($post->ID, 'color_fondo_encabezado_hexadecimal', $color_fondo_encabezado_hexadecimal);
	
	$color_texto_encabezado_hexadecimal = sanitize_text_field($_POST['color_texto_encabezado_hexadecimal']);
	update_post_meta($post->ID, 'color_texto_encabezado_hexadecimal', $color_texto_encabezado_hexadecimal);
	
	$color_fondo_formulario_hexadecimal = sanitize_text_field($_POST['color_fondo_formulario_hexadecimal']);
	update_post_meta($post->ID, 'color_fondo_formulario_hexadecimal', $color_fondo_formulario_hexadecimal);
	
	$color_texto_formulario_hexadecimal = sanitize_text_field($_POST['color_texto_formulario_hexadecimal']);
	update_post_meta($post->ID, 'color_texto_formulario_hexadecimal', $color_texto_formulario_hexadecimal);
	
	$color_texto_controles_formulario_hexadecimal = sanitize_text_field($_POST['color_texto_controles_formulario_hexadecimal']);
	update_post_meta($post->ID, 'color_texto_controles_formulario_hexadecimal', $color_texto_controles_formulario_hexadecimal);
	
	$color_boton_hexadecimal = sanitize_text_field($_POST['color_boton_hexadecimal']);
	update_post_meta($post->ID, 'color_boton_hexadecimal', $color_boton_hexadecimal);
	
	$color_texto_boton_hexadecimal = sanitize_text_field($_POST['color_texto_boton_hexadecimal']);
	update_post_meta($post->ID, 'color_texto_boton_hexadecimal', $color_texto_boton_hexadecimal);
	
	if(!preg_match("/^[a-zA-Z0-9\s\#\$\+\%\(\)\[\]\*\¡\!\=\\/\&\.\,\;\:\-\_\ñ\á\é\í\ó\ú\Á\É\Í\Ó\Ú\Ñ]*$/", $_POST['serie']))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'La serie tiene un formato inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!preg_match("/^[a-zA-Z0-9\s\#\$\+\%\(\)\[\]\*\¡\!\=\\/\&\.\,\;\:\-\_\ñ\á\é\í\ó\ú\Á\É\Í\Ó\Ú\Ñ]*$/", $_POST['estado_orden']))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'La estado del pedido tiene un formato inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!preg_match("/^[a-zA-Z0-9\s\#\$\+\%\(\)\[\]\*\¡\!\=\\/\&\.\,\;\:\-\_\ñ\á\é\í\ó\ú\Á\É\Í\Ó\Ú\Ñ]*$/", $_POST['titulo']))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El título tiene un formato inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!preg_match("/^[a-zA-Z0-9\s\#\$\+\%\(\)\[\]\*\¡\!\=\\/\&\.\,\;\:\-\_\ñ\á\é\í\ó\ú\Á\É\Í\Ó\Ú\Ñ]*$/", $_POST['descripcion']))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'La descripción tiene un formato inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!preg_match("/(^#[0-9A-F]{6}$)/i", $_POST['color_fondo_encabezado_hexadecimal']))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El valor del campo "Color de fondo en encabezado" tiene un formato inválido. Por favor, ingresa un valor hexadecimal que represente el color deseado.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!preg_match("/(^#[0-9A-F]{6}$)/i", $_POST['color_texto_encabezado_hexadecimal']))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El valor del campo "Color de texto en encabezado" tiene un formato inválido. Por favor, ingresa un valor hexadecimal que represente el color deseado.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!preg_match("/(^#[0-9A-F]{6}$)/i", $_POST['color_fondo_formulario_hexadecimal']))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El valor del campo "Color de fondo en formulario" tiene un formato inválido. Por favor, ingresa un valor hexadecimal que represente el color deseado.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!preg_match("/(^#[0-9A-F]{6}$)/i", $_POST['color_texto_formulario_hexadecimal']))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El valor del campo "Color de texto en formulario" tiene un formato inválido. Por favor, ingresa un valor hexadecimal que represente el color deseado.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!preg_match("/(^#[0-9A-F]{6}$)/i", $_POST['color_texto_controles_formulario_hexadecimal']))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El valor del campo "Color de texto en campos del formulario" tiene un formato inválido. Por favor, ingresa un valor hexadecimal que represente el color deseado.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!preg_match("/(^#[0-9A-F]{6}$)/i", $_POST['color_boton_hexadecimal']))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El valor del campo "Color de botones" tiene un formato inválido. Por favor, ingresa un valor hexadecimal que represente el color deseado.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!preg_match("/(^#[0-9A-F]{6}$)/i", $_POST['color_texto_boton_hexadecimal']))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El valor del campo "Color de texto en botones" tiene un formato inválido. Por favor, ingresa un valor hexadecimal que represente el color deseado.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
    $configuracion = array
	(
		'serie'       							=> $_POST['serie'],
		'estado_orden'       					=> $_POST['estado_orden'],
		'titulo'       							=> $_POST['titulo'],
		'descripcion'   						=> $_POST['descripcion'],
		'color_fondo_encabezado'      			=> $_POST['color_fondo_encabezado_hexadecimal'],
		'color_texto_encabezado'       			=> $_POST['color_texto_encabezado_hexadecimal'],
		'color_fondo_formulario'      			=> $_POST['color_fondo_formulario_hexadecimal'],
		'color_texto_formulario'       			=> $_POST['color_texto_formulario_hexadecimal'],
		'color_texto_controles_formulario'      => $_POST['color_texto_controles_formulario_hexadecimal'],
		'color_boton'       					=> $_POST['color_boton_hexadecimal'],
		'color_texto_boton'       				=> $_POST['color_texto_boton_hexadecimal']
    );

	$cuenta = RealVirtualWooCommerceCuenta::cuentaEntidad();
	
	if(!($cuenta['rfc'] != '' && $cuenta['usuario'] != '' && $cuenta['clave'] != ''))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'No se puede guardar la configuración porque es necesario antes ingresar correctamente tu RFC, Usuario y Clave Cifrada en la sección <b>Mi Cuenta</b>.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
    $guardado = RealVirtualWooCommerceConfiguracion::guardarConfiguracion($configuracion, $cuenta['rfc'], $cuenta['usuario'], $cuenta['clave'], $urlSistemaAsociado);
	
    $respuesta = array
	(
       'success' => $guardado->success,
	   'message' => $guardado->message
    );
	
    echo json_encode($respuesta, JSON_PRETTY_PRINT);
	wp_die();
}

function realvirtual_woocommerce_front_end()
{
	global $sistema, $nombreSistema, $nombreSistemaAsociado, $urlSistemaAsociado, $sitioOficialSistema;
	
	$configuracion = RealVirtualWooCommerceConfiguracion::configuracionEntidad();
	
	$formulario = '<center><div>';
	
	if(!empty($configuracion['titulo']))
        $formulario .= '<br/><font color="#848484" size="4">'.$configuracion['titulo'].'</font>';
    
	if(!empty($configuracion['descripcion']))
        $formulario .= '<br/><font color="#848484" size="2">'.$configuracion['descripcion'].'</font>';
                                        
	$formulario .= '</div>
				<br/><div id="realvirtual_woocommerce_facturacion">
                    <div id="paso_uno" style="width: 50%;">
                        <div style="background:'.$configuracion['color_fondo_encabezado'].'; height: 80px; line-height: 20px; margin-bottom: 0px;">
							<br/>
                            <font color="'.$configuracion['color_texto_encabezado'].'" size="6">Paso 1/4</font>
							<br/>
                            <font color="'.$configuracion['color_texto_encabezado'].'" size="4">Identificar pedido</font>
                        </div>
                        
						<div style="background-color:'.$configuracion['color_fondo_formulario'].';">
							<br/>
                            <p><font color="'.$configuracion['color_texto_formulario'].'" size="2">Ingresa el n&uacute;mero de pedido y el monto.</font></p>
                            <form name="paso_uno_formulario" id="paso_uno_formulario" action="<?php echo esc_url(get_permalink()); ?>" method="post">
                                <div class="rowPaso1">
									<label><font color="'.$configuracion['color_texto_formulario'].'">* N&uacute;mero de Pedido</font></label>
									<input type="text" style="color: '.$configuracion['color_texto_controles_formulario'].';" id="numero_pedido" name="numero_pedido" value="" placeholder="Sin símbolo #"  />
									<br/>
									<label><font color="'.$configuracion['color_texto_formulario'].'">* Monto</font></label>
									<input type="text" style="color: '.$configuracion['color_texto_controles_formulario'].';" id="monto_pedido" name="monto_pedido" value="" placeholder=""  />
                                </div>
								<br/>
								<div>
									<input type="submit" style="background-color: '.$configuracion['color_boton'].'; color:'.$configuracion['color_texto_boton'].';" class="boton" id="paso_uno_boton_siguiente" name="paso_uno_boton_siguiente" value="Siguiente" />
									<img id="cargandoPaso1" src="'.plugin_dir_url( __FILE__ )."/assets/realvirtual_woocommerce_cargando.gif".'" alt="Cargando" height="32" width="32" style="visibility: hidden;">
								</div>
                            </form>
							<br/>
                        </div>
						<br/><br/>
                    </div>
                    
					<div id="paso_dos" style="width: 80%;">
                        <div style="background:'.$configuracion['color_fondo_encabezado'].'; height: 80px; line-height: 20px; margin-bottom: 0px;">
                           <br/>
                            <font color="'.$configuracion['color_texto_encabezado'].'" size="6">Paso 2/4</font>
							<br/>
                            <font color="'.$configuracion['color_texto_encabezado'].'" size="4">Identificar cliente</font>
                        </div>
						
                        <div style="background-color:'.$configuracion['color_fondo_formulario'].';">
							<br/>
							<!--<p><font color="'.$configuracion['color_texto_formulario'].'" size="2">Ingresa tu RFC y pulsa el bot&oacute;n <img src="'.plugin_dir_url( __FILE__ )."/assets/realvirtual_woocommerce_buscar.png".'" width="24" height="24" alt="Buscar" /> si ya eres un cliente registrado.</font></p>
							<p><font color="'.$configuracion['color_texto_formulario'].'" size="2">Si no eres un cliente registrado, llena los campos y pulsa el bot&oacute;n Siguiente.</font></p>-->
                            <form name="paso_dos_formulario" id="paso_dos_formulario" action="<?php echo esc_url(get_permalink()); ?>" method="post">
                                <input type="text" id="receptor_id" name="receptor_id" value="" placeholder="" hidden /><br/>
								<table width="90%">
								<tr>
								<td>
								<div class="rowPaso2">
									<label><font color="'.$configuracion['color_texto_formulario'].'">* RFC</label></font>
									<input type="text" style="text-transform: uppercase; color: '.$configuracion['color_texto_controles_formulario'].';" id="receptor_rfc" name="receptor_rfc" value="" placeholder="" maxlength="13" /><!--<button type="button" style="background-color: '.$configuracion['color_fondo_formulario'].';" id="paso_dos_boton_buscar_cliente" name="paso_dos_boton_buscar_cliente" ><img id="imagen_paso_dos_boton_buscar_cliente" name="imagen_paso_dos_boton_buscar_cliente" src="'.plugin_dir_url( __FILE__ )."/assets/realvirtual_woocommerce_buscar.png".'" width="24" height="24" alt="Buscar" /></button>-->
									<br/>
								</div>
								<div class="rowPaso2">
									<label><font color="'.$configuracion['color_texto_formulario'].'">Razón Social</label></font>
									<input type="text" style="color: '.$configuracion['color_texto_controles_formulario'].';" id="receptor_razon_social" name="receptor_razon_social" value="" placeholder="" />
									<br/>
									<label><font color="'.$configuracion['color_texto_formulario'].'">Calle</label></font>
									<input type="text" style="color: '.$configuracion['color_texto_controles_formulario'].';" id="receptor_calle" name="receptor_calle" value="" placeholder="" />
									<br/>
									<label><font color="'.$configuracion['color_texto_formulario'].'">No. Ext.</label></font>
									<input type="text" style="color: '.$configuracion['color_texto_controles_formulario'].';" id="receptor_no_exterior" name="receptor_no_exterior" value="" placeholder="" />
									<br/>
									<label><font color="'.$configuracion['color_texto_formulario'].'">No. Int.</label></font>
									<input type="text" style="color: '.$configuracion['color_texto_controles_formulario'].';" id="receptor_no_interior" name="receptor_no_interior" value="" placeholder="" />
									<br/>
									<label><font color="'.$configuracion['color_texto_formulario'].'">Colonia</label></font>
									<input type="text" style="color: '.$configuracion['color_texto_controles_formulario'].';" id="receptor_colonia" name="receptor_colonia" value="" placeholder="" />
									<br/>
									<label><font color="'.$configuracion['color_texto_formulario'].'">Referencia</label></font>
									<input type="text" style="color: '.$configuracion['color_texto_controles_formulario'].';" id="receptor_referencia" name="receptor_referencia" value="" placeholder="" />
								</div>
								</td>
								<td>
								<div class="rowPaso2">
									<label><font color="'.$configuracion['color_texto_formulario'].'">C.P.</label></font>
									<input type="text" style="color: '.$configuracion['color_texto_controles_formulario'].';" id="receptor_codigo_postal" name="receptor_codigo_postal" value="" placeholder="" />
									<br/>
									<label><font color="'.$configuracion['color_texto_formulario'].'">Estado</label></font>
									<input type="text" style="color: '.$configuracion['color_texto_controles_formulario'].';" id="receptor_estado" name="receptor_estado" value="" placeholder="" />
									<br/>
									<label><font color="'.$configuracion['color_texto_formulario'].'">Municipio</label></font>
									<input type="text" style="color: '.$configuracion['color_texto_controles_formulario'].';" id="receptor_municipio" name="receptor_municipio" value="" placeholder="" />
									<br/>
									<label><font color="'.$configuracion['color_texto_formulario'].'">Localidad</label></font>
									<input type="text" style="color: '.$configuracion['color_texto_controles_formulario'].';" id="receptor_localidad" name="receptor_localidad" value="" placeholder="" />
									<br/>
									<label><font color="'.$configuracion['color_texto_formulario'].'">* Pa&iacute;s</label></font>
									<input type="text" style="color: '.$configuracion['color_texto_controles_formulario'].';" id="receptor_pais" name="receptor_pais" value="México" placeholder="" />
									<br/>
									<label><font color="'.$configuracion['color_texto_formulario'].'">* E-mail</label></font>
									<input type="text" style="color: '.$configuracion['color_texto_controles_formulario'].';" id="receptor_email" name="receptor_email" value="" placeholder="" />
									<br/>
									<label><font color="'.$configuracion['color_texto_formulario'].'">Tel&eacute;fono</label></font>
									<input type="text" style="color: '.$configuracion['color_texto_controles_formulario'].';" id="receptor_telefono" name="receptor_telefono" value="" placeholder="" />
                                </div>
								</td>
								</tr>
								</table>
								<br/>
								<div>
									<input type="button" style="background-color: '.$configuracion['color_boton'].'; color:'.$configuracion['color_texto_boton'].';" class="boton" id="paso_dos_boton_regresar" name="paso_dos_boton_regresar" value="Regresar" />
									<input type="submit" style="background-color: '.$configuracion['color_boton'].'; color:'.$configuracion['color_texto_boton'].';" class="boton" id="paso_dos_boton_siguiente" name="paso_dos_boton_siguiente" value="Siguiente" />
									<img id="cargandoPaso2" src="'.plugin_dir_url( __FILE__ )."/assets/realvirtual_woocommerce_cargando.gif".'" alt="Cargando" height="32" width="32" style="visibility: hidden;">
								</div>
                            </form>
							<br/>
                        </div>
						<br/><br/>
                    </div>
					<div id="paso_tres" style="width: 80%;">
                        <div style="background:'.$configuracion['color_fondo_encabezado'].'; height: 80px; line-height: 20px; margin-bottom: 0px;">
                            <br/>
                            <font color="'.$configuracion['color_texto_encabezado'].'" size="6">Paso 3/4</font>
							<br/>
                            <font color="'.$configuracion['color_texto_encabezado'].'" size="4">Verificar datos de CFDI</font>
                        </div>
                        <div style="background-color:'.$configuracion['color_fondo_formulario'].';">
							<br/>
                            <form name="paso_tres_formulario" id="paso_tres_formulario" action="<?php echo esc_url(get_permalink()); ?>" method="post">
								<table width="750px">
									<tr>
										<td style="vertical-align:top; width:50%;">
											<font color="'.$configuracion['color_texto_formulario'].'" size="2"><label id="paso_3_datos_emisor"></label></font>
										</td>
										<td style="vertical-align:top; width:50%;">
											<font color="'.$configuracion['color_texto_formulario'].'" size="2"><div id="paso_3_datos_receptor"></div></font>
										</td>
									</tr>
								</table>
								<table width="750px">
									<tr>
									<td style="vertical-align:top">
										<div class="rowPaso3">
											<font color="'.$configuracion['color_texto_formulario'].'"><label>M&eacute;todo de pago</label></font>
											<font color="'.$configuracion['color_texto_formulario'].'"><select id="paso_3_metodos_pago" style="color: '.$configuracion['color_texto_controles_formulario'].';"></select></font>
										</div>
									</td>
									<td style="vertical-align:top">
										<div class="rowPaso3">
											<label><font color="'.$configuracion['color_texto_formulario'].'">No. Cuenta</label></font>
											<input type="text" style="color: '.$configuracion['color_texto_controles_formulario'].';" id="paso_3_no_cuenta" name="paso_3_no_cuenta" value="" placeholder="" />
										</div>
									</td>
									</tr>
								</table>
								<div>
									<font color="'.$configuracion['color_texto_formulario'].'"><b><label style="text-align: left;">CONCEPTOS</label></b></font><br/><br/>
									<font color="'.$configuracion['color_texto_formulario'].'" size="3">
										<table id="conceptos_tabla" border="1" style="background-color:#FFFFFF; border-color:#dedede;" width="750px">
											<thead>
												<tr>
													<td style="text-align:center; border-color: #dedede; background-color: #ececec;">Descripci&oacute;n</td>
													<td style="text-align:center; border-color: #dedede; background-color: #ececec;">Cantidad</td>
													<td style="text-align:center; border-color: #dedede; background-color: #ececec;">Precio unitario</td>
													<td style="text-align:center; border-color: #dedede; background-color: #ececec;">Total</td>
												</tr>
											</thead>
											<tbody id="conceptos_cuerpo_tabla">
											</tbody>
										</table>
									</font>
								</div>
								<font color="'.$configuracion['color_texto_formulario'].'" size="3">
								<div>
									<table border="0" style="background-color:#FFFFFF;" width="750px">
										<tbody id="totales_cuerpo_tabla">
										</tbody>
									</table>
								</div>
								</font>
								<div>
									<input type="button" style="background-color: '.$configuracion['color_boton'].'; color:'.$configuracion['color_texto_boton'].';" class="boton" id="paso_tres_boton_regresar" name="paso_tres_boton_regresar" value="Regresar" />
									<input type="submit" style="background-color: '.$configuracion['color_boton'].'; color:'.$configuracion['color_texto_boton'].';" class="boton" id="paso_tres_boton_generar" name="paso_tres_boton_generar" value="Generar CFDI" />
									<img id="cargandoPaso3" src="'.plugin_dir_url( __FILE__ )."/assets/realvirtual_woocommerce_cargando.gif".'" alt="Cargando" height="32" width="32" style="visibility: hidden;">
								</div>
							</form>
							<br/>
						</div>
						<br/><br/>
					</div>
					<div id="paso_cuatro" style="width: 50%;">
                        <div style="background:'.$configuracion['color_fondo_encabezado'].'; height: 80px; line-height: 20px; margin-bottom: 0px;">
                            <br/>
                            <font color="'.$configuracion['color_texto_encabezado'].'" size="6">Paso 4/4</font>
							<br/>
                            <font color="'.$configuracion['color_texto_encabezado'].'" size="4">Descargar CFDI</font>
                        </div>
                        <div style="background-color:'.$configuracion['color_fondo_formulario'].';">
							<br/>
							<p><font color="'.$configuracion['color_texto_formulario'].'" size="2">Este pedido ha sido facturado</font></p>
                            <form name="paso_cuatro_formulario" id="paso_cuatro_formulario" action="<?php echo esc_url(get_permalink()); ?>" method="post">
                                <font color="'.$configuracion['color_texto_formulario'].'"><b><label>Archivo XML</label></b></font><br/>
								<a href="#" id="paso_cuatro_boton_xml" target="_blank">Descargar</a><br/>
                                <br/><font color="'.$configuracion['color_texto_formulario'].'"><b><label>Archivo PDF</label></b></font><br/>
								<a href="#" id="paso_cuatro_boton_pdf" target="_blank">Descargar</a><br/>
								<br/>
								<div>
									<input type="button" style="background-color: '.$configuracion['color_boton'].'; color:'.$configuracion['color_texto_boton'].';" class="boton" id="paso_cuatro_boton_regresar" name="paso_cuatro_boton_regresar" value="Salir" />
								</div>
							</form>
							<br/>
						</div>
						<br/><br/>
					</div>
					<div id="paso_cinco" style="width: 50%;">
                        <div style="background:'.$configuracion['color_fondo_encabezado'].'; height: 80px; line-height: 20px; margin-bottom: 0px;">
							<br/>
                            <font color="'.$configuracion['color_texto_encabezado'].'" size="6">Descargar CFDI</font>
                        </div>
                        <div style="background-color:'.$configuracion['color_fondo_formulario'].';">
							<br/>
							<p><font color="'.$configuracion['color_texto_formulario'].'" size="2">Este pedido ya fue facturado</font></p>
                            <form name="paso_cinco_formulario" id="paso_cinco_formulario" action="<?php echo esc_url(get_permalink()); ?>" method="post">
                                <font color="'.$configuracion['color_texto_formulario'].'"><b><label>Archivo XML</label></b></font><br/>
								<a href="#" id="paso_cinco_boton_xml" target="_blank">Descargar</a><br/>
                                <br/><font color="'.$configuracion['color_texto_formulario'].'"><b><label>Archivo PDF</label></b></font><br/>
								<a href="#" id="paso_cinco_boton_pdf" target="_blank">Descargar</a><br/>
								<br/>
								<div>
									<input type="button" style="background-color: '.$configuracion['color_boton'].'; color:'.$configuracion['color_texto_boton'].';" class="boton" id="paso_cinco_boton_regresar" name="paso_cinco_boton_regresar" value="Salir" />
								</div>
							</form>
							<br/>
						</div>
						<br/><br/>
					</div>
                </div></center>
				
				<div id="ventanaModal" class="modal">
					<div class="modal-content">
						<span class="close">&times;</span>
						<br/>
						<center>
							<font color="#000000" size="5"><b>
								<div id="tituloModal"></div>
							</b></font>
							<br/>
							<font color="#000000" size="3">
								<div id="textoModal"></div>
							</font>
							<br/>
							<input type="button" style="background-color: '.$configuracion['color_boton'].'; color:'.$configuracion['color_texto_boton'].';" class="boton" id="botonModal" value="Aceptar" />
						</center>
					</div>
				</div>';
	
	echo $formulario;
}

add_action('wp_ajax_realvirtual_woocommerce_paso_uno', 'realvirtual_woocommerce_paso_uno_callback');
add_action('wp_ajax_nopriv_realvirtual_woocommerce_paso_uno', 'realvirtual_woocommerce_paso_uno_callback');

function realvirtual_woocommerce_paso_uno_callback()
{
    global $wpdb, $sistema, $nombreSistema, $nombreSistemaAsociado, $urlSistemaAsociado, $sitioOficialSistema, $post;
    
	$numero_pedido = sanitize_text_field($_POST['numero_pedido']);
	update_post_meta($post->ID, 'numero_pedido', $numero_pedido);
	
	$monto_pedido = sanitize_text_field($_POST['monto_pedido']);
	update_post_meta($post->ID, 'monto_pedido', $monto_pedido);
	
	$numero_pedido 	= trim($_POST['numero_pedido']);
	$monto_pedido 	= trim($_POST['monto_pedido']);
	
	if(!intval($numero_pedido))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El número de pedido no es válido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!is_numeric($monto_pedido))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El monto del pedido no es válido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!isset($numero_pedido))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'No se ha recibido el número del pedido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	else if(!isset($monto_pedido))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'No se ha recibido el monto del pedido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	else
	{
		$datosPedido = RealVirtualWooCommercePedido::obtenerPedido($numero_pedido);
		
		$respuesta = array
		(
			'success' => true,
			'datosPedido' => $datosPedido,
			'numero_pedido' => $numero_pedido,
			'plugin_dir_url' => plugin_dir_url( __FILE__ ),
			'urlSistemaAsociado' => $urlSistemaAsociado
		);
		
		if($datosPedido->mensajeError != '')
		{
			$respuesta = array
			(
				'success' => false,
				'message' => $datosPedido->mensajeError
			);
			
			echo json_encode($respuesta, JSON_PRETTY_PRINT);
			wp_die();
			return;
		}
		
		if($datosPedido->status == false)
		{
			$respuesta = array
			(
				'success' => false,
				'message' => 'No existe ningún pedido con el número "'.$numero_pedido.'".'
			);
			
			echo json_encode($respuesta, JSON_PRETTY_PRINT);
			wp_die();
			return;
		}
		else
		{
			if(!isset($datosPedido))
			{
				$respuesta = array
				(
					'success' => false,
					'message' => 'No se ha recibido el pedido.'
				);
				
				echo json_encode($respuesta, JSON_PRETTY_PRINT);
				wp_die();
				return;
			}
			
			if($datosPedido->total != $monto_pedido)
			{
				$respuesta = array
				(
					'success' => false,
					'message' => 'El monto del pedido no coincide.'
				);
				
				echo json_encode($respuesta, JSON_PRETTY_PRINT);
				wp_die();
				return;
			}
			
			$cuenta = RealVirtualWooCommerceCuenta::cuentaEntidad();
			$datosCFDI = RealVirtualWooCommercePedido::obtenerCFDIID($numero_pedido, $cuenta['rfc'], $cuenta['usuario'], $cuenta['clave'], $urlSistemaAsociado);
				
			if($datosCFDI->success == true)
			{
				$respuesta = array
				(
					'success' => false,
					'message' => 'Este pedido se encuentra facturado.',
					'numero_pedido' => $numero_pedido,
					'CFDI_ID' => $datosCFDI->CFDI_ID,
					'urlSistemaAsociado' => $urlSistemaAsociado
				);
					
				echo json_encode($respuesta, JSON_PRETTY_PRINT);
				wp_die();
				return;
			}
			else
			{
				if($datosCFDI->codigo == '-2')
				{
					$configuracion = RealVirtualWooCommerceConfiguracion::configuracionEntidad();
			
					if($datosPedido->status != $configuracion['estado_orden'])
					{
						$respuesta = array
						(
							'success' => false,
							'message' => 'Este pedido todavía no se puede facturar.',
							'estado_orden1' => $datosPedido->status,
							'estado_orden2' => $configuracion['estado_orden']
						);
						
						echo json_encode($respuesta, JSON_PRETTY_PRINT);
						wp_die();
						return;
					}
				}
				else if($datosCFDI->codigo == '-1')
				{
					$respuesta = array
					(
						'success' => false,
						'message' => 'Ocurrió un error al realizar la operación. '.$datosCFDI->message,
						'numero_pedido' => $numero_pedido,
						'CFDI_ID' => '0',
						'urlSistemaAsociado' => $urlSistemaAsociado
					);
				}
				else
				{
					$respuesta = array
					(
						'success' => false,
						'message' => 'Este pedido se encuentra facturado pero no se pudo recuperar el CFDI para descargar en XML y PDF. '.$datosCFDI->message,
						'numero_pedido' => $numero_pedido,
						'CFDI_ID' => '0',
						'urlSistemaAsociado' => $urlSistemaAsociado
					);
				}
				
				echo json_encode($respuesta, JSON_PRETTY_PRINT);
				wp_die();
				return;
			}
		}
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
}

add_action('wp_ajax_realvirtual_woocommerce_paso_dos_buscar_cliente', 'realvirtual_woocommerce_paso_dos_buscar_cliente_callback');
add_action('wp_ajax_nopriv_realvirtual_woocommerce_paso_dos_buscar_cliente', 'realvirtual_woocommerce_paso_dos_buscar_cliente_callback');

function realvirtual_woocommerce_paso_dos_buscar_cliente_callback()
{
    global $wpdb, $sistema, $nombreSistema, $nombreSistemaAsociado, $urlSistemaAsociado, $sitioOficialSistema, $post;
    
	$receptor_rfc = sanitize_text_field($_POST['receptor_rfc']);
	update_post_meta($post->ID, 'receptor_rfc', $receptor_rfc);
	
	$receptor_email = sanitize_text_field($_POST['receptor_email']);
	update_post_meta($post->ID, 'receptor_email', $receptor_email);
	
	$receptor_rfc 	= trim($_POST['receptor_rfc']);
	$receptor_email = trim($_POST['receptor_email']);
	
	if(!preg_match("/^([A-Z]|&|Ñ){3,4}[0-9]{2}[0-1][0-9][0-3][0-9]([A-Z]|[0-9]){2}([0-9]|A){1}$/", $receptor_rfc))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El RFC del Receptor tiene un formato inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!filter_var($receptor_email, FILTER_VALIDATE_EMAIL))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El correo electrónico del Receptor tiene un formato inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	$respuesta = array
	(
		'success' => true,
		'message' => 'OK'
	);
		
	if(!isset($receptor_rfc))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'No se ha recibido el RFC del cliente.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	else if(!isset($receptor_email))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'No se ha recibido el E-mail del cliente.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	else
	{
		$cuenta = RealVirtualWooCommerceCuenta::cuentaEntidad();
		$datosCliente = RealVirtualWooCommerceCliente::obtenerCliente($receptor_rfc, $receptor_email, $cuenta['rfc'], $cuenta['usuario'], $cuenta['clave'], $urlSistemaAsociado);
		
		if($datosCliente->success == false)
		{
			$respuesta = array
			(
				'success' => false,
				'message' => $datosCliente->message
			);
		}
		else
		{
			$respuesta = array
			(
				'success' => true,
				'receptor_id' => trim($datosCliente->RECEPTOR_ID),
				'receptor_rfc' => trim($datosCliente->RECEPTOR_RFC),
				'receptor_razon_social' => trim($datosCliente->RECEPTOR_NOMBRE),
				'receptor_calle' => trim($datosCliente->RECEPTOR_CALLE),
				'receptor_no_exterior' => trim($datosCliente->RECEPTOR_NOEXT),
				'receptor_no_interior' => trim($datosCliente->RECEPTOR_NOINT),
				'receptor_colonia' => trim($datosCliente->RECEPTOR_COLONIA),
				'receptor_referencia' => trim($datosCliente->RECEPTOR_REFERENCIA),
				'receptor_codigo_postal' => trim($datosCliente->RECEPTOR_CODIGOPOSTAL),
				'receptor_estado' => trim($datosCliente->RECEPTOR_ESTADO),
				'receptor_municipio' => trim($datosCliente->RECEPTOR_MUNICIPIO),
				'receptor_localidad' => trim($datosCliente->RECEPTOR_LOCALIDAD),
				'receptor_pais' => trim($datosCliente->RECEPTOR_PAIS),
				'receptor_email' => trim($datosCliente->RECEPTOR_EMAIL),
				'receptor_telefono' => trim($datosCliente->RECEPTOR_TELEFONO)
			);
		}
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
}

add_action('wp_ajax_realvirtual_woocommerce_paso_tres_buscar_emisor', 'realvirtual_woocommerce_paso_tres_buscar_emisor_callback');
add_action('wp_ajax_nopriv_realvirtual_woocommerce_paso_tres_buscar_emisor', 'realvirtual_woocommerce_paso_tres_buscar_emisor_callback');

function realvirtual_woocommerce_paso_tres_buscar_emisor_callback()
{
    global $wpdb, $sistema, $nombreSistema, $nombreSistemaAsociado, $urlSistemaAsociado, $sitioOficialSistema;
    
	$cuenta = RealVirtualWooCommerceCuenta::cuentaEntidad();
	$datosEmisor = RealVirtualWooCommerceEmisor::obtenerEmisor($cuenta['rfc'], $cuenta['usuario'], $cuenta['clave'], $urlSistemaAsociado);
	
	if($datosEmisor->success == false)
	{
		$respuesta = array
		(
			'success' => false,
			'message' => $datosEmisor->message
		);
	}
	else
	{
		$respuesta = array
		(
			'success' => true,
			'emisor_id' => trim($datosEmisor->EMISOR_ID),
			'emisor_rfc' => trim($datosEmisor->EMISOR_RFC),
			'emisor_razon_social' => trim($datosEmisor->EMISOR_NOMBRE),
			'emisor_calle' => trim($datosEmisor->EMISOR_CALLE),
			'emisor_no_exterior' => trim($datosEmisor->EMISOR_NOEXT),
			'emisor_no_interior' => trim($datosEmisor->EMISOR_NOINT),
			'emisor_colonia' => trim($datosEmisor->EMISOR_COLONIA),
			'emisor_referencia' => trim($datosEmisor->EMISOR_REFERENCIA),
			'emisor_codigo_postal' => trim($datosEmisor->EMISOR_CODIGOPOSTAL),
			'emisor_estado' => trim($datosEmisor->EMISOR_ESTADO),
			'emisor_municipio' => trim($datosEmisor->EMISOR_MUNICIPIO),
			'emisor_localidad' => trim($datosEmisor->EMISOR_LOCALIDAD),
			'emisor_pais' => trim($datosEmisor->EMISOR_PAIS),
			'emisor_email' => trim($datosEmisor->EMISOR_EMAIL),
			'emisor_telefono' => trim($datosEmisor->EMISOR_TELEFONO)
		);
	}
		
	echo json_encode($respuesta, JSON_PRETTY_PRINT);
	wp_die();
}

add_action('wp_ajax_realvirtual_woocommerce_paso_tres_metodos_pago', 'realvirtual_woocommerce_paso_tres_metodos_pago_callback');
add_action('wp_ajax_nopriv_realvirtual_woocommerce_paso_tres_metodos_pago', 'realvirtual_woocommerce_paso_tres_metodos_pago_callback');

function realvirtual_woocommerce_paso_tres_metodos_pago_callback()
{
    global $wpdb, $sistema, $nombreSistema, $nombreSistemaAsociado, $urlSistemaAsociado, $sitioOficialSistema;
    
	$cuenta = RealVirtualWooCommerceCuenta::cuentaEntidad();
	$datosMetodosPago = RealVirtualWooCommerceMetodoPago::obtenerCatalogoMetodosPago($cuenta['rfc'], $cuenta['usuario'], $cuenta['clave'], $urlSistemaAsociado);
	
	if($datosMetodosPago->success == false)
	{
		$respuesta = array
		(
			'success' => false,
			'message' => $datosMetodosPago->message
		);
	}
	else
	{
		$respuesta = array
		(
			'success' => true,
			'registros' => $datosMetodosPago->registros
		);
	}
	
	echo json_encode($respuesta, JSON_PRETTY_PRINT);
	wp_die();
}

add_action('wp_ajax_realvirtual_woocommerce_paso_tres_generar_cfdi', 'realvirtual_woocommerce_paso_tres_generar_cfdi_callback');
add_action('wp_ajax_nopriv_realvirtual_woocommerce_paso_tres_generar_cfdi', 'realvirtual_woocommerce_paso_tres_generar_cfdi_callback');

function realvirtual_woocommerce_paso_tres_generar_cfdi_callback()
{
    global $wpdb, $sistema, $nombreSistema, $nombreSistemaAsociado, $urlSistemaAsociado, $sitioOficialSistema, $post;
    
	$receptor_id = sanitize_text_field($_POST['receptor_id']);
	update_post_meta($post->ID, 'receptor_id', $receptor_id);
	
	$receptor_rfc = sanitize_text_field($_POST['receptor_rfc']);
	update_post_meta($post->ID, 'receptor_rfc', $receptor_rfc);
	
	$receptor_razon_social = sanitize_text_field($_POST['receptor_razon_social']);
	update_post_meta($post->ID, 'receptor_razon_social', $receptor_razon_social);
	
	$receptor_calle = sanitize_text_field($_POST['receptor_calle']);
	update_post_meta($post->ID, 'receptor_calle', $receptor_calle);
	
	$receptor_no_exterior = sanitize_text_field($_POST['receptor_no_exterior']);
	update_post_meta($post->ID, 'receptor_no_exterior', $receptor_no_exterior);
	
	$receptor_no_interior = sanitize_text_field($_POST['receptor_no_interior']);
	update_post_meta($post->ID, 'receptor_no_interior', $receptor_no_interior);
	
	$receptor_colonia = sanitize_text_field($_POST['receptor_colonia']);
	update_post_meta($post->ID, 'receptor_colonia', $receptor_colonia);
	
	$receptor_localidad = sanitize_text_field($_POST['receptor_localidad']);
	update_post_meta($post->ID, 'receptor_localidad', $receptor_localidad);
	
	$receptor_referencia = sanitize_text_field($_POST['receptor_referencia']);
	update_post_meta($post->ID, 'receptor_referencia', $receptor_referencia);
	
	$receptor_municipio = sanitize_text_field($_POST['receptor_municipio']);
	update_post_meta($post->ID, 'receptor_municipio', $receptor_municipio);
	
	$receptor_estado = sanitize_text_field($_POST['receptor_estado']);
	update_post_meta($post->ID, 'receptor_estado', $receptor_estado);
	
	$receptor_pais = sanitize_text_field($_POST['receptor_pais']);
	update_post_meta($post->ID, 'receptor_pais', $receptor_pais);
	
	$receptor_codigo_postal = sanitize_text_field($_POST['receptor_codigo_postal']);
	update_post_meta($post->ID, 'receptor_codigo_postal', $receptor_codigo_postal);
	
	$receptor_email = sanitize_text_field($_POST['receptor_email']);
	update_post_meta($post->ID, 'receptor_email', $receptor_email);
	
	$receptor_telefono = sanitize_text_field($_POST['receptor_telefono']);
	update_post_meta($post->ID, 'receptor_telefono', $receptor_telefono);
	
	$metodo_pago = sanitize_text_field($_POST['metodo_pago']);
	update_post_meta($post->ID, 'metodo_pago', $metodo_pago);
	
	$no_cuenta = sanitize_text_field($_POST['no_cuenta']);
	update_post_meta($post->ID, 'no_cuenta', $no_cuenta);
	
	$conceptos = sanitize_text_field($_POST['conceptos']);
	update_post_meta($post->ID, 'conceptos', $conceptos);
	
	$subtotal = sanitize_text_field($_POST['subtotal']);
	update_post_meta($post->ID, 'subtotal', $subtotal);
	
	$descuento = sanitize_text_field($_POST['descuento']);
	update_post_meta($post->ID, 'descuento', $descuento);
	
	$total = sanitize_text_field($_POST['total']);
	update_post_meta($post->ID, 'total', $total);
	
	$serie = sanitize_text_field($_POST['serie']);
	update_post_meta($post->ID, 'serie', $serie);
	
	$impuesto_federal = sanitize_text_field($_POST['impuesto_federal']);
	update_post_meta($post->ID, 'impuesto_federal', $impuesto_federal);
	
	$numero_pedido = sanitize_text_field($_POST['numero_pedido']);
	update_post_meta($post->ID, 'numero_pedido', $numero_pedido);
	
	$receptor_id 				= trim($_POST['receptor_id']);
	$receptor_rfc 				= trim($_POST['receptor_rfc']);
	$receptor_razon_social 		= trim($_POST['receptor_razon_social']);
	$receptor_calle 			= trim($_POST['receptor_calle']);
	$receptor_no_exterior 		= trim($_POST['receptor_no_exterior']);
	$receptor_no_interior 		= trim($_POST['receptor_no_interior']);
	$receptor_colonia 			= trim($_POST['receptor_colonia']);
	$receptor_localidad 		= trim($_POST['receptor_localidad']);
	$receptor_referencia 		= trim($_POST['receptor_referencia']);
	$receptor_municipio 		= trim($_POST['receptor_municipio']);
	$receptor_estado 			= trim($_POST['receptor_estado']);
	$receptor_pais 				= trim($_POST['receptor_pais']);
	$receptor_codigo_postal 	= trim($_POST['receptor_codigo_postal']);
	$receptor_email 			= trim($_POST['receptor_email']);
	$receptor_telefono 			= trim($_POST['receptor_telefono']);
	$metodo_pago 				= trim($_POST['metodo_pago']);
	$no_cuenta					= trim($_POST['no_cuenta']);
	$conceptos 					= trim($_POST['conceptos']);
	$subtotal 					= trim($_POST['subtotal']);
	$descuento 					= trim($_POST['descuento']);
	$total 						= trim($_POST['total']);
	$serie 						= trim($_POST['serie']);
	$impuesto_federal 			= trim($_POST['impuesto_federal']);
	$numero_pedido				= trim($_POST['numero_pedido']);
	
	if(!intval($receptor_id))
	{
		$receptor_id = '';
	}
	
	if(!preg_match("/^([A-Z]|&|Ñ){3,4}[0-9]{2}[0-1][0-9][0-3][0-9]([A-Z]|[0-9]){2}([0-9]|A){1}$/", $receptor_rfc))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El RFC del Receptor tiene un formato inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!preg_match("/^[a-zA-Z0-9\s\#\$\+\%\(\)\[\]\*\¡\!\=\\/\&\.\,\;\:\-\_\ñ\á\é\í\ó\ú\Á\É\Í\Ó\Ú\Ñ]*$/", $receptor_razon_social))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'La razón social del Receptor tiene un formato inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!preg_match("/^[a-zA-Z0-9\s\#\$\+\%\(\)\[\]\*\¡\!\=\\/\&\.\,\;\:\-\_\ñ\á\é\í\ó\ú\Á\É\Í\Ó\Ú\Ñ]*$/", $receptor_calle))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'La calle del Receptor tiene un formato inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!preg_match("/^[a-zA-Z0-9\s\#\$\+\%\(\)\[\]\*\¡\!\=\\/\&\.\,\;\:\-\_\ñ\á\é\í\ó\ú\Á\É\Í\Ó\Ú\Ñ]*$/", $receptor_no_exterior))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El número exterior del Receptor tiene un formato inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!preg_match("/^[a-zA-Z0-9\s\#\$\+\%\(\)\[\]\*\¡\!\=\\/\&\.\,\;\:\-\_\ñ\á\é\í\ó\ú\Á\É\Í\Ó\Ú\Ñ]*$/", $receptor_no_interior))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El número interior del Receptor tiene un formato inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!preg_match("/^[a-zA-Z0-9\s\#\$\+\%\(\)\[\]\*\¡\!\=\\/\&\.\,\;\:\-\_\ñ\á\é\í\ó\ú\Á\É\Í\Ó\Ú\Ñ]*$/", $receptor_colonia))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'La colonia del Receptor tiene un formato inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!preg_match("/^[a-zA-Z0-9\s\#\$\+\%\(\)\[\]\*\¡\!\=\\/\&\.\,\;\:\-\_\ñ\á\é\í\ó\ú\Á\É\Í\Ó\Ú\Ñ]*$/", $receptor_localidad))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'La localidad del Receptor tiene un formato inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!preg_match("/^[a-zA-Z0-9\s\#\$\+\%\(\)\[\]\*\¡\!\=\\/\&\.\,\;\:\-\_\ñ\á\é\í\ó\ú\Á\É\Í\Ó\Ú\Ñ]*$/", $receptor_referencia))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'La referencia del Receptor tiene un formato inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!preg_match("/^[a-zA-Z0-9\s\#\$\+\%\(\)\[\]\*\¡\!\=\\/\&\.\,\;\:\-\_\ñ\á\é\í\ó\ú\Á\É\Í\Ó\Ú\Ñ]*$/", $receptor_municipio))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El municipio del Receptor tiene un formato inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!preg_match("/^[a-zA-Z0-9\s\#\$\+\%\(\)\[\]\*\¡\!\=\\/\&\.\,\;\:\-\_\ñ\á\é\í\ó\ú\Á\É\Í\Ó\Ú\Ñ]*$/", $receptor_estado))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El estado del Receptor tiene un formato inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!preg_match("/^[a-zA-Z0-9\s\#\$\+\%\(\)\[\]\*\¡\!\=\\/\&\.\,\;\:\-\_\ñ\á\é\í\ó\ú\Á\É\Í\Ó\Ú\Ñ]*$/", $receptor_pais))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El país del Receptor tiene un formato inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!intval($receptor_codigo_postal) && !strlen($receptor_codigo_postal) == '5')
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El código postal del Receptor no es válido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!filter_var($receptor_email, FILTER_VALIDATE_EMAIL))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El correo electrónico del Receptor tiene un formato inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!preg_match("/^[a-zA-Z0-9\s\#\$\+\%\(\)\[\]\*\¡\!\=\\/\&\.\,\;\:\-\_\ñ\á\é\í\ó\ú\Á\É\Í\Ó\Ú\Ñ]*$/", $receptor_telefono))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El teléfono del Receptor tiene un formato inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if($metodo_pago == '02' || $metodo_pago == '03' || $metodo_pago == '04' || $metodo_pago == '05' || $metodo_pago == '06')
	{
		if(!intval($no_cuenta))
		{
			$respuesta = array
			(
				'success' => false,
				'message' => 'El número de cuenta del CFDI tiene un formato inválido.'
			);
			
			echo json_encode($respuesta, JSON_PRETTY_PRINT);
			wp_die();
		}
	}
	
	if(!strlen($conceptos) > 0)
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'No hay conceptos para el CFDI.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!strlen($subtotal) > 0)
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El subtotal del CFDI es inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!strlen($descuento) > 0)
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El descuento del CFDI es inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!strlen($total) > 0)
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El total del CFDI es inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!strlen($impuesto_federal) > 0)
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'No hay impuestos para el CFDI.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	if(!intval($numero_pedido))
	{
		$respuesta = array
		(
			'success' => false,
			'message' => 'El número de pedido tiene un formato inválido.'
		);
		
		echo json_encode($respuesta, JSON_PRETTY_PRINT);
		wp_die();
	}
	
	$cuenta = RealVirtualWooCommerceCuenta::cuentaEntidad();
	$configuracion = RealVirtualWooCommerceConfiguracion::configuracionEntidad();
	
	$datosCFDI = RealVirtualWooCommerceCFDI::generarCFDI
				(
					$cuenta['rfc'],
					$cuenta['usuario'],
					$cuenta['clave'],
					$receptor_id,
					$receptor_rfc,
					$receptor_razon_social,
					$receptor_calle,
					$receptor_no_exterior,
					$receptor_no_interior,
					$receptor_colonia,
					$receptor_localidad,
					$receptor_referencia,
					$receptor_municipio,
					$receptor_estado,
					$receptor_pais,
					$receptor_codigo_postal,
					$receptor_email,
					$receptor_telefono,
					$metodo_pago,
					$no_cuenta,
					$conceptos,
					$subtotal,
					$descuento,
					$total,
					$configuracion['serie'],
					$impuesto_federal,
					$numero_pedido,
					$urlSistemaAsociado,
					$sistema
				);
	
	if($datosCFDI->success == false)
	{
		$respuesta = array
		(
			'success' => false,
			'message' => $datosCFDI->message
		);
	}
	else
	{
		$respuesta = array
		(
			'success' => true,
			'message' => $datosCFDI->message,
			'LAYOUT' => $datosCFDI->LAYOUT,
			'XML' => $datosCFDI->XML,
			'CFDI_ID' => $datosCFDI->CFDI_ID
		);
		
		//$datosPedido = RealVirtualWooCommercePedido::obtenerPedido($numero_pedido);
		//$pedido = new WC_Order($datosPedido->id);
        //$pedido->update_status('wc-invoiced', '');
		//$pedido->update_status('wc-completed', '');
	}
		
	echo json_encode($respuesta, JSON_PRETTY_PRINT);
	wp_die();
}

?>