<?php

$api_status = 0; 
		
$WPMLSync = wp_mercadolibre_sync_settings(); 

if( array_filter($WPMLSync) ){

	$api_status = 1;
	$MELI = new Meli($WPMLSync['appId'], $WPMLSync['secretKey']);
	$Exception = '';

	if(isset($_GET['code']) || !empty($WPMLSync['access_token']) ) {
		
		$api_status = 2;

		// If code exist and session is empty
		if(isset($_GET['code']) && empty($WPMLSync['access_token'])) {
			// //If the code was in get parameter we authorize
			$api_status = 3;
			try{
				$user = $MELI->authorize($_GET["code"], $WPMLSync['redirectURI']);  
				$_seller_id = wp_mercadolibre_sync_get_seller_id($MELI, $user['body']->access_token); 

				wp_mercadolibre_sync_update_settings(array(
					'access_token' => $user['body']->access_token,
					'expires_in' => $user['body']->expires_in,
					'refresh_token' => $user['body']->refresh_token,
					'seller_id' => $_seller_id
				));

				$api_status = 4;
			}catch(Exception $e){
				$Exception .= $e->getMessage(). "\n";
			}
		} else {
			
			// Check if the access token is invalid checking time vs expires_in
			$_expire_test = isset($_GET['refresh_token']) ? true : false;
			if(isset($_GET['code'])){
				$_expire_test = true;
			}
			$_check_expires_in = !empty($WPMLSync['expires_in']) ? $WPMLSync['expires_in'] : time();
			if( ($_check_expires_in < time() && !empty($WPMLSync['auto_token']) ) || $_expire_test) {	
				try {
					// Make the refresh proccess 

					$refresh_MELI = new Meli($WPMLSync['appId'], $WPMLSync['secretKey'], $WPMLSync['access_token'], $WPMLSync['refresh_token']);

					$refresh = $refresh_MELI->refreshAccessToken();  
					$_seller_id = wp_mercadolibre_sync_get_seller_id($refresh_MELI, $refresh['body']->access_token);

					wp_mercadolibre_sync_update_settings(array(
						'access_token' => $refresh['body']->access_token,
						'expires_in' => $refresh['body']->expires_in,
						'refresh_token' => $refresh['body']->refresh_token,
						'seller_id' => $_seller_id
					));

					$api_status = 5;
					if(isset($_GET['code'])){
						$api_status = 8;
					}

					$refresh_public_count = (null !== get_option('wp_mercadolibre_sync_refresh_admin_count')) ? get_option('wp_mercadolibre_sync_refresh_admin_count') : 0;
					update_option('wp_mercadolibre_sync_refresh_admin_count', ($refresh_public_count + 1));


				} catch (Exception $e) {
				  	$Exception .= $e->getMessage(). "\n";
				}
			}else{ 
				$api_status = 6;
			}
		}  

	} else {
		$api_status = 7; 
	}

}

update_option('wp_mercadolibre_sync_status',$api_status);
wp_mercadolibre_sync_debug('update_option::wp_mercadolibre_sync_status status: '.$api_status.'');