<?php
	class RealVirtualWooCommerceConfiguracion
	{
		static $serie		   						= '';
		static $estado_orden   						= '';
		static $titulo   							= '';
		static $descripcion   						= '';
		static $color_fondo_encabezado   			= '';
		static $color_texto_encabezado   			= '';
		static $color_fondo_formulario   			= '';
		static $color_texto_formulario   			= '';
		static $color_texto_controles_formulario 	= '';
		static $color_boton   						= '';
		static $color_texto_boton   				= '';
		
		static function guardarConfiguracion($configuracion, $rfcEmisor, $usuarioEmisor, $claveEmisor, $urlSistemaAsociado)
		{
			global $wp_version;
			
			$archivoConfiguracion = fopen(dirname(__FILE__).'/realvirtual_woocommerce.conf', 'w') or die('No se puede abrir el archivo de configuracion');
			
			fwrite($archivoConfiguracion, base64_encode($configuracion['serie'])."\n");
			fwrite($archivoConfiguracion, base64_encode($configuracion['estado_orden'])."\n");
			fwrite($archivoConfiguracion, base64_encode($configuracion['titulo'])."\n");
			fwrite($archivoConfiguracion, base64_encode($configuracion['descripcion'])."\n");
			fwrite($archivoConfiguracion, base64_encode($configuracion['color_fondo_encabezado'])."\n");
			fwrite($archivoConfiguracion, base64_encode($configuracion['color_texto_encabezado'])."\n");
			fwrite($archivoConfiguracion, base64_encode($configuracion['color_fondo_formulario'])."\n");
			fwrite($archivoConfiguracion, base64_encode($configuracion['color_texto_formulario'])."\n");
			fwrite($archivoConfiguracion, base64_encode($configuracion['color_texto_controles_formulario'])."\n");
			fwrite($archivoConfiguracion, base64_encode($configuracion['color_boton'])."\n");
			fwrite($archivoConfiguracion, base64_encode($configuracion['color_texto_boton'])."\n");
			fclose($archivoConfiguracion);
			
			$opcion = 'GuardarConfiguracion';
			
			$parametros = array
			(
				'OPCION' => $opcion,
				'EMISOR_RFC' => $rfcEmisor,
				'EMISOR_USUARIO' => $usuarioEmisor,
				'EMISOR_CLAVE' => $claveEmisor,
				'SERIE' => base64_encode($configuracion['serie']),
				'ESTADO_ORDEN' => base64_encode($configuracion['estado_orden']),
				'TITULO' => base64_encode($configuracion['titulo']),
				'DESCRIPCION' => base64_encode($configuracion['descripcion']),
				'COLOR_FONDO_ENCABEZADO' => base64_encode($configuracion['color_fondo_encabezado']),
				'COLOR_TEXTO_ENCABEZADO' => base64_encode($configuracion['color_texto_encabezado']),
				'COLOR_FONDO_FORMULARIO' => base64_encode($configuracion['color_fondo_formulario']),
				'COLOR_TEXTO_FORMULARIO' => base64_encode($configuracion['color_texto_formulario']),
				'COLOR_TEXTO_CONTROLES_FORMULARIO' => base64_encode($configuracion['color_texto_controles_formulario']),
				'COLOR_BOTON' => base64_encode($configuracion['color_boton']),
				'COLOR_TEXTO_BOTON' => base64_encode($configuracion['color_texto_boton'])
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
					return json_decode($body);
				}
			}
			catch(Exception $e)
			{
				print('Exception occured: ' . $e->getMessage());
			}
		}
		
		static function guardarConfiguracionLocal($configuracion)
		{
			global $wp_version;
			
			$archivoConfiguracion = fopen(dirname(__FILE__).'/realvirtual_woocommerce.conf', 'w') or die('No se puede abrir el archivo de configuracion');
			
			fwrite($archivoConfiguracion, $configuracion['serie']."\n");
			fwrite($archivoConfiguracion, $configuracion['estado_orden']."\n");
			fwrite($archivoConfiguracion, $configuracion['titulo']."\n");
			fwrite($archivoConfiguracion, $configuracion['descripcion']."\n");
			fwrite($archivoConfiguracion, $configuracion['color_fondo_encabezado']."\n");
			fwrite($archivoConfiguracion, $configuracion['color_texto_encabezado']."\n");
			fwrite($archivoConfiguracion, $configuracion['color_fondo_formulario']."\n");
			fwrite($archivoConfiguracion, $configuracion['color_texto_formulario']."\n");
			fwrite($archivoConfiguracion, $configuracion['color_texto_controles_formulario']."\n");
			fwrite($archivoConfiguracion, $configuracion['color_boton']."\n");
			fwrite($archivoConfiguracion, $configuracion['color_texto_boton']."\n");
			fclose($archivoConfiguracion);
			
			return true;
		}
		
		static function configuracionEntidad()
		{
			$datosConfiguracion = self::obtenerConfiguracion();

			return array
			(
				'serie'     								=> base64_decode($datosConfiguracion[0]),
				'estado_orden' 								=> base64_decode($datosConfiguracion[1]),
				'titulo'   	 								=> base64_decode($datosConfiguracion[2]),
				'descripcion'   							=> base64_decode($datosConfiguracion[3]),
				'color_fondo_encabezado'   					=> base64_decode($datosConfiguracion[4]),
				'color_texto_encabezado'   					=> base64_decode($datosConfiguracion[5]),
				'color_fondo_formulario'   					=> base64_decode($datosConfiguracion[6]),
				'color_texto_formulario'   					=> base64_decode($datosConfiguracion[7]),
				'color_texto_controles_formulario'   		=> base64_decode($datosConfiguracion[8]),
				'color_boton'   							=> base64_decode($datosConfiguracion[9]),
				'color_texto_boton'   						=> base64_decode($datosConfiguracion[10]),
			);
		}
		
		static function obtenerConfiguracion()
		{
			$archivo = @fopen(dirname(__FILE__).'/realvirtual_woocommerce.conf', 'r');

			if($archivo)
			   $datosConfiguracion = explode("\n", fread($archivo, filesize(dirname(__FILE__) .'/realvirtual_woocommerce.conf')));

			return $datosConfiguracion;
		}
	}
?>