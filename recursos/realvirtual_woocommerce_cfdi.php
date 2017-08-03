<?php
	class RealVirtualWooCommerceCFDI
	{
		public function generarCFDI($rfcEmisor, $usuarioEmisor, $claveEmisor, $receptor_id, $receptor_rfc, $receptor_razon_social, $receptor_calle, $receptor_no_exterior, $receptor_no_interior, $receptor_colonia, $receptor_localidad, $receptor_referencia, $receptor_municipio, $receptor_estado, $receptor_pais, $receptor_codigo_postal, $receptor_email, $receptor_telefono, $metodo_pago, $no_cuenta, $conceptos, $subtotal, $descuento, $total, $serie, $impuesto_federal, $numero_pedido, $urlSistemaAsociado, $sistema)
		{
			$opcion = 'GenerarCFDI';
			
			$parametros = array
			(
				'OPCION' => $opcion,
				'EMISOR_RFC' => $rfcEmisor,
				'EMISOR_USUARIO' => $usuarioEmisor,
				'EMISOR_CLAVE' => $claveEmisor,
				'RECEPTOR_ID' => $receptor_id,
				'RECEPTOR_RFC' => $receptor_rfc,
				'RECEPTOR_NOMBRE' => $receptor_razon_social,
				'RECEPTOR_CALLE' => $receptor_calle,
				'RECEPTOR_NOEXT' => $receptor_no_exterior,
				'RECEPTOR_NOINT' => $receptor_no_interior,
				'RECEPTOR_COLONIA' => $receptor_colonia,
				'RECEPTOR_LOCALIDAD' => $receptor_localidad,
				'RECEPTOR_REFERENCIA' => $receptor_referencia,
				'RECEPTOR_MUNICIPIO' => $receptor_municipio,
				'RECEPTOR_ESTADO' => $receptor_estado,
				'RECEPTOR_PAIS' => $receptor_pais,
				'RECEPTOR_CODIGOPOSTAL' => $receptor_codigo_postal,
				'RECEPTOR_EMAIL' => $receptor_email,
				'RECEPTOR_TELEFONO' => $receptor_telefono,
				'METODO_PAGO' => $metodo_pago,
				'NO_CUENTA' => $no_cuenta,
				'CONCEPTOS' => $conceptos,
				'SUBTOTAL' => $subtotal,
				'DESCUENTO' => $descuento,
				'TOTAL' => $total,
				'SERIE' => $serie,
				'IMPUESTO_FEDERAL' => $impuesto_federal,
				'NUMERO_PEDIDO' => $numero_pedido,
				'SISTEMA' => $sistema
			);
			
			$params = array
			(
				'method' => 'POST',
				'timeout' => 10000,
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
		
		public function obtenerVentas($rfcEmisor, $usuarioEmisor, $claveEmisor, $filtro, $urlSistemaAsociado)
		{
			$opcion = 'ObtenerVentas';
			
			$parametros = array
			(
				'OPCION' => $opcion,
				'EMISOR_RFC' => $rfcEmisor,
				'EMISOR_USUARIO' => $usuarioEmisor,
				'EMISOR_CLAVE' => $claveEmisor,
				'FILTRO' => $filtro
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
	}
?>