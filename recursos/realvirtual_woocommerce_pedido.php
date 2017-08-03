<?php
	class RealVirtualWooCommercePedido
	{
		static function obtenerPedido($idPedido)
		{
			try
			{
				//$pedido = wc_get_order($idPedido); //Para versión inferior a 2.2 de WooCommerce
				$pedido = new WC_Order($idPedido); //Para versión superior o igual a 2.2 de WooCommerce
				$order_post = get_post($idPedido);

				$total = 0;
				$total_tax = 0;
				$total_shipping = 0;
				$cart_tax = 0;
				$shipping_tax = 0;
				$total_discount = 0;
				
				if (is_numeric($pedido->get_total()))
					$total = $pedido->get_total();
				
				if (is_numeric($pedido->get_total_tax()))
					$total_tax = $pedido->get_total_tax();
				
				if (is_numeric($pedido->get_total_shipping()))
					$total_shipping = $pedido->get_total_shipping();
				
				if (is_numeric($pedido->get_cart_tax()))
					$cart_tax = $pedido->get_cart_tax();
				
				if (is_numeric($pedido->get_shipping_tax()))
					$shipping_tax = $pedido->get_shipping_tax();
				
				if (is_numeric($pedido->get_total_discount()))
					$total_discount = $pedido->get_total_discount();
				
				$datosPedido = array
				(
					'mensajeError'				=> '',
					'id'                        => $pedido->id,
					'order_number'              => $pedido->get_order_number(),
					'created_at'                => $order_post->post_date_gmt,
					'updated_at'                => $order_post->post_modified_gmt,
					'completed_at'              => $pedido->completed_date,
					'status'                    => $pedido->get_status(),
					'currency'                  => $pedido->order_currency,
					'total'                     => wc_format_decimal($total, 2 ),
					'total_line_items_quantity' => $pedido->get_item_count(),
					'total_tax'                 => wc_format_decimal($total_tax, 2),
					'total_shipping'            => wc_format_decimal($total_shipping, 2),
					'cart_tax'                  => wc_format_decimal($cart_tax, 2),
					'shipping_tax'              => wc_format_decimal($shipping_tax, 2),
					'total_discount'            => wc_format_decimal($total_discount, 2),
					'shipping_methods'          => $pedido->get_shipping_method(),
					'payment_details' => array
					(
						'method_id'    => $pedido->payment_method,
						'method_title' => $pedido->payment_method_title,
						'paid'         => isset( $pedido->paid_date ),
					),
					'billing_first_name' => $pedido->billing_first_name,
					'billing_last_name'  => $pedido->billing_last_name,
					'billing_company'    => $pedido->billing_company,
					'billing_address_1'  => $pedido->billing_address_1,
					'billing_address_2'  => $pedido->billing_address_2,
					'billing_city'       => $pedido->billing_city,
					'billing_state'      => $pedido->billing_state,
					'billing_postcode'   => $pedido->billing_postcode,
					'billing_country'    => $pedido->billing_country,
					'billing_email'      => $pedido->billing_email,
					'billing_phone'      => $pedido->billing_phone,
					'billing_address' => array
					(
						'first_name' => $pedido->billing_first_name,
						'last_name'  => $pedido->billing_last_name,
						'company'    => $pedido->billing_company,
						'address_1'  => $pedido->billing_address_1,
						'address_2'  => $pedido->billing_address_2,
						'city'       => $pedido->billing_city,
						'state'      => $pedido->billing_state,
						'postcode'   => $pedido->billing_postcode,
						'country'    => $pedido->billing_country,
						'email'      => $pedido->billing_email,
						'phone'      => $pedido->billing_phone,
					),
					'shipping_address' => array
					(
						'first_name' => $pedido->shipping_first_name,
						'last_name'  => $pedido->shipping_last_name,
						'company'    => $pedido->shipping_company,
						'address_1'  => $pedido->shipping_address_1,
						'address_2'  => $pedido->shipping_address_2,
						'city'       => $pedido->shipping_city,
						'state'      => $pedido->shipping_state,
						'postcode'   => $pedido->shipping_postcode,
						'country'    => $pedido->shipping_country,
					),
					'note'                      => $pedido->customer_note,
					'customer_ip'               => $pedido->customer_ip_address,
					'customer_user_agent'       => $pedido->customer_user_agent,
					'customer_id'               => $pedido->customer_user,
					'view_order_url'            => $pedido->get_view_order_url(),
					'line_items'                => array(),
					'shipping_lines'            => array(),
					'tax_lines'                 => array(),
					'fee_lines'                 => array(),
					'coupon_lines'              => array(),
				);
				// add line items
				foreach( $pedido->get_items() as $item_id => $item ) {
				  $product = $pedido->get_product_from_item( $item );

				  $datosPedido['line_items'][] = array(
					'id'         => $item_id,
					'subtotal'   => wc_format_decimal( $pedido->get_line_subtotal( $item ), 2 ),
					'total'      => wc_format_decimal( $pedido->get_line_total( $item ), 2 ),
					'total_tax'  => wc_format_decimal( $pedido->get_item_tax( $item ), 2 ),
					'price'      => wc_format_decimal( $pedido->get_item_total( $item ), 2 ) + wc_format_decimal( $pedido->get_item_tax( $item ), 2 ),
					'meta'       => array(
						'item_total' => wc_format_decimal( $pedido->get_item_total( $item ), 2 ),
						'line_tax'   => wc_format_decimal( $pedido->get_line_tax( $item ), 2 ),
						'item_tax'   => wc_format_decimal( $pedido->get_item_tax( $item ), 2 ),
					),
					'quantity'   => (int) $item['qty'],
					'tax_class'  => ( ! empty( $item['tax_class'] ) ) ? $item['tax_class'] : null,
					'name'       => $item['name'],
					'product_id' => ( isset( $product->variation_id ) ) ? $product->variation_id : $product->id,
					'sku'        => is_object( $product ) ? $product->get_sku() : null,
				  );

				}

				// add shipping as a product
				foreach($pedido->get_items('shipping') as $shipping_key => $shipping_item){

					if($shipping_item['method_id'] != 'free_shipping'){
						$datosPedido['line_items'][] = array(
							'id'         => $shipping_key,
							'subtotal'   => wc_format_decimal($shipping_item['cost'], 2),
							'total'      => wc_format_decimal($shipping_item['cost'], 2),
							'total_tax'  => round($pedido->order_shipping_tax, 2),
							'price'      => wc_format_decimal($shipping_item['cost'], 2) + round($pedido->order_shipping_tax, 2),
							'quantity'   => 1,
							'tax_class'  => null,
							'name'       => $shipping_item['name'],
							'product_id' =>$shipping_key,
							'sku'        => $shipping_item['method_id'],
						);
					}
				}
				
				return (object)$datosPedido;
			}
			catch(Exception $e)
			{
				$datosPedido = array
				(
					'mensajeError'      => $e->getMessage()
				);
				
				return (object)$datosPedido;
			}
		}
		
		static function obtenerCFDIID($idPedido, $rfcEmisor, $usuarioEmisor, $claveEmisor, $urlSistemaAsociado)
		{
			global $wp_version;
			
			$opcion = 'ObtenerCFDIID';
			
			$parametros = array
			(
				'OPCION' => $opcion,
				'EMISOR_RFC' => $rfcEmisor,
				'EMISOR_USUARIO' => $usuarioEmisor,
				'EMISOR_CLAVE' => $claveEmisor,
				'NUMERO_PEDIDO' => $idPedido
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