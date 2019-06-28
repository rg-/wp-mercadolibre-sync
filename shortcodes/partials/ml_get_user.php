<?php 
	
	$meli = new Meli($appId, $secretKey);

	echo wp_mercadolibre_sync_settings('seller_id');

	$params = array(
  	'access_token' => wp_mercadolibre_sync_settings('access_token')
  );
  
  echo "<p>GET '/users/me'</p>";
	$url = '/users/me';  
	$meli_result = $meli->get($url, $params); 
	
	$id = $meli_result['body']->id;
	$nickname = $meli_result['body']->nickname;
	$permalink = $meli_result['body']->permalink;

	echo "<p>user_id/seller_id: ".$id."</p>";
	echo "<p>nickname: ".$nickname."</p>";
	echo "<p>permalink: ".$permalink."</p>";
	echo "<pre>";
	// print_r(wp_ml_get_options('access_token'));
	echo "</pre>";

?>