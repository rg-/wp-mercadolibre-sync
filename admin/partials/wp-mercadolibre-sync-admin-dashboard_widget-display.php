<?php

var_dump(extension_loaded('curl'));

$options = get_option( 'wp_mercadolibre_sync_settings' );
echo "<pre>";
print_r($options);
echo "</pre>";

$appId = $options['wp_mercadolibre_sync_appId'];
$secretKey = $options['wp_mercadolibre_sync_secretKey'];
$redirectURI = $options['wp_mercadolibre_sync_redirectURI'];
$siteId = $options['wp_mercadolibre_sync_siteId']; 

$meli = new Meli($appId, $secretKey);
echo "<pre>";
print_r($meli);
echo "</pre>"; 

$oAuth_link = $meli->getAuthUrl($redirectURI , Meli::$AUTH_URL[$siteId]); 
echo '<a href="' . $oAuth_link . '">Login using MercadoLibre oAuth 2.0</a>'; 


if( isset($_GET['code']) ){

	$user = $meli->authorize($_GET["code"], $redirectURI);
	echo "<pre>";
	print_r($user);
	echo "</pre>";  
}

?>