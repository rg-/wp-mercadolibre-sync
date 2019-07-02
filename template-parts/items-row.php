<?php
// get api settings from options
$wp_mercadolibre_sync_settings = wp_mercadolibre_sync_settings();
// extract varis like $appId, $secretKey...
extract($wp_mercadolibre_sync_settings);  

// create Meli
$meli = new Meli($appId, $secretKey);
if(empty($meli)) return false;

// Set params to use 
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

echo "<pre>";
//print_r($results);
echo "</pre>";
echo "<pre>";
// print_r($orders);
echo "</pre>";

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
		echo "<pre>";
		//print_r($items);
		echo "</pre>";

		foreach($items['body'] as $item){ 
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
