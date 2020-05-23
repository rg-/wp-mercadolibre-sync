<?php

$create_category = $_GET['create_category'];

$wp_mercadolibre_sync_settings = wp_mercadolibre_sync_settings();
extract($wp_mercadolibre_sync_settings); 
$meli = new Meli($appId, $secretKey);
if(empty($meli)) return false; 

$api_params = array( 
  'access_token' => $access_token
);
$meli_result = $meli->get(
  '/categories/'.$create_category,
  $api_params
);
if(empty($meli_result)) return false; 
wpmlsync_print_pre($meli_result['body']);  