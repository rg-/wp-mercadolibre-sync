<?php

function wp_mercadolibre_sync_meli_code_array(){
	$meli_code_array = array(
			0 => array(
				'desc' => 'Hola, aún no hay datos guardados. Completa los datos de <b>appId</b>, <b>secretKey</b> y <b>redirectURI</b>.',
				'html' => '',
			),
			1 => array(
				'desc' => 'Los datos API estan guardados, aún falta autentificar para continuar.',
				'html' => ''
			),
			2 => array(
				'desc' => '',
				'html' => ''
			),
			3 => array(
				'desc' => '',
				'html' => ''
			),
			4 => array(
				'desc' => 'Muy bien, se ha autorizado la API. Ahora debes <b>Guardar cambios</b> antes de salir.',
				'html' => ''
			),
			5 => array(
				'desc' => '',
				'html' => ''
			),
			6 => array(
				'desc' => 'Excelente, tu API vinculada correctamente. Revisa el estado y otras opciones para mas detalles.',
				'html' => ''
			),
			7 => array(
				'desc' => 'Debes autentificar tu aplicación. Serás redireccionado para logearte con tu usuario con el cual previamente has creado tu app. Volverás luego a esta pagina con un "code" para generar los tokens.',
				'html' => ''
			), 
		);
	return $meli_code_array;
}

 function wp_mercadolibre_sync_settings($name=''){
		$options = get_option( 'wp_mercadolibre_sync_settings' );  
		$out = array(
			'appId' => $options['wp_mercadolibre_sync_appId'],
			'secretKey' => $options['wp_mercadolibre_sync_secretKey'],
			'redirectURI' => $options['wp_mercadolibre_sync_redirectURI'],
			'siteId' => $options['wp_mercadolibre_sync_siteId'],
			'access_token' => $options['wp_mercadolibre_sync_access_token'],
			'expires_in' => $options['wp_mercadolibre_sync_expires_in'],
			'refresh_token' => $options['wp_mercadolibre_sync_refresh_token'],

			'seller_id' => $options['wp_mercadolibre_sync_seller_id']
		);
		if($name){
			return $out[$name];
		}else{
			return $out;
		} 
	}

function wp_mercadolibre_sync_get_seller_id($meli, $access_token){
	$params = array(
  	'access_token'=>$access_token
  ); 
	$url = '/users/me';  
	$meli_result = $meli->get($url, $params); 
	$meli_user_id = $meli_result['body']->id;
	return $meli_user_id;
}

function wp_mercadolibre_sync_get_item_test_fields(){
	/*
	TODO, make multidimensional like:

		$fields = array(

			array(

				'name' => '',
				'default_value' => '',
				'type' => '' // string/array

			),
	
		);

	*/
	$fields = array(
		'title',
		'price'
	);
	return $fields; 
}
function wp_mercadolibre_sync_get_item_test($get_params){
	$item = array(
		"title" => "Item De Prueba - Por Favor, No Ofertar --kc:off",
    "category_id" => "MLU177823",
    "price" => 10,
    "currency_id" => "UYU",
    "available_quantity" => 1,
    "buying_mode" => "buy_it_now",
    "listing_type_id" => "bronze",
    "condition" => "new",
    "description" => array ("plain_text" => "Item de Teste WP Mercado Libre's PHP SDK."),
    "video_id" => "RXWn6kftTHY",
    "warranty" => "12 month",
    "pictures" => array(
        array(
            "source" => "https://upload.wikimedia.org/wikipedia/commons/thumb/6/64/IPhone_7_Plus_Jet_Black.svg/440px-IPhone_7_Plus_Jet_Black.svg.png"
        ),
        array(
            "source" => "https://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/IPhone7.jpg/440px-IPhone7.jpg"
        )
    ), 
  );
  $prefix = 'wp_ml_sync_test__';
  $fields = wp_mercadolibre_sync_get_item_test_fields();
  foreach ($fields as $field) {
  	$item[$field] = isset($get_params[$prefix.$field]) ? $get_params[$prefix.$field] : $item[$field];
  }
  //$item['title'] = isset($get_params[$prefix.'title']) ? $get_params[$prefix.'title'] : $item['title'];
	return $item; 
}