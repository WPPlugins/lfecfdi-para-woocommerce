<?php
	class RealVirtualWooCommerceCuenta
	{
		static $rfc      = '';
		static $usuario  = '';
		static $clave    = '';
		
		static function guardarCuenta($cuenta, $rfcEmisor, $usuarioEmisor, $claveEmisor, $urlSistemaAsociado)
		{
			global $wp_version;
			
			$archivoCuenta = fopen(dirname(__FILE__).'/realvirtual_woocommerce_cuenta.conf', 'w') or die('No se puede abrir el archivo de cuenta');
			
			fwrite($archivoCuenta, base64_encode($cuenta['rfc'])."\n");
			fwrite($archivoCuenta, base64_encode($cuenta['usuario'])."\n");
			fwrite($archivoCuenta, base64_encode($cuenta['clave'])."\n");
			fclose($archivoCuenta);
			
			$opcion = 'EstadoEmisor';
			
			$parametros = array
			(
				'OPCION' => $opcion,
				'EMISOR_RFC' => $rfcEmisor,
				'EMISOR_USUARIO' => $usuarioEmisor,
				'EMISOR_CLAVE' => $claveEmisor
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
		
		static function cuentaEntidad()
		{
			$datosCuenta = self::obtenerCuenta();

			return array
			(
				'rfc'      									=> base64_decode($datosCuenta[0]),
				'usuario'   								=> base64_decode($datosCuenta[1]),
				'clave'     								=> base64_decode($datosCuenta[2])
			);
		}
		
		static function obtenerCuenta()
		{
			$archivo = @fopen(dirname(__FILE__).'/realvirtual_woocommerce_cuenta.conf', 'r');

			if($archivo)
			   $datosCuenta = explode("\n", fread($archivo, filesize(dirname(__FILE__) .'/realvirtual_woocommerce_cuenta.conf')));

			return $datosCuenta;
		}
	}
?>