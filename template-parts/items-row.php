<?php
/*

	Get the API settings from options

*/
$wp_mercadolibre_sync_settings = wp_mercadolibre_sync_settings();
/*

	Extract variables like $appId, $secretKey...

*/
extract($wp_mercadolibre_sync_settings);  

/*

	Create Meli

*/
$meli = new Meli($appId, $secretKey);
if(empty($meli)) return false;

/*

	Set params to use 

*/

/*

	#1
	En esta parte se definen los filtros a usar en el ?search
	Por ej. el "order" de los items basado en price, start_time_desc, etc
	debería hacerse en este lugar.

	Ver #2

*/

$meli_result = $meli->get(
	'/users/'.$seller_id.'/items/search',
	array(
		'status' => 'active',
		'query' => !empty($shortcode_atts['query']) ? $shortcode_atts['query'] : '',
		'offset' => !empty($shortcode_atts['offset']) ? $shortcode_atts['offset'] : 0,
		'limit' => !empty($shortcode_atts['limit']) ? $shortcode_atts['limit'] : 50,
		'order' => 'start_time_desc',
  	'access_token' => $access_token
  ));
if(empty($meli_result)) return false; 

$paging = $meli_result['body']->paging;
$results = $meli_result['body']->results; // array of IDS
$filters = $meli_result['body']->filters;
$orders = $meli_result['body']->orders; // The loop order
$available_orders = $meli_result['body']->available_orders; // The loop order
$available_filters = $meli_result['body']->available_filters; 

// echo "<pre>";
// print_r($results);
// echo "</pre>";
// echo "<pre>";
// print_r($orders);
// echo "</pre>";

if( !empty($results) ){

	?>
	<div class="position-relative row row-no-gutters" data-mmasonry-item=".card">
		<?php
		$ids = join(',', $results);  
		$url = '/items';   
		$items = $meli->get($url, array(
			'ids' => $ids,
			'access_token' => $access_token
		)); 
		// echo "<pre>";
		// print_r($results);
		// echo "</pre>";
		
		/*
			
			#2 

			Como el results de la api no me toma el orden de los ?ids= pasados
			Lo vuelvo a re-ordenar en un array temporal según justamente el 
			mismísimo orden que viene en el results anterior.

			Es decir, el filtro del orden del ?search, se define antes de crear este
			array temporal y su orden ksort(), esto no debería ser cambiado.

			Ver #1

		*/
		$array_test = array();
		foreach($items['body'] as $item){  
			foreach($results as $i => $r){
				if($item->body->id == $r){
					$array_test[$i] = $item->body;
				}
			} 
		}
		ksort($array_test,SORT_NUMERIC);

		foreach($array_test as $item){ 
		  $file = wp_mercadolibre_sync_get_template('item');
			if(!empty($file)){
				include ($file); 
			} 
		}
		?>
	</div>
	<?php

}

?>
