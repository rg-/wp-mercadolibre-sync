<?php 
$n = new Wp_Mercadolibre_Sync(); 
//echo $n->get_plugin_name(); 

$meli = new Meli($appId, $secretKey);
$seller_id = wp_mercadolibre_sync_settings('seller_id'); 

// https://api.mercadolibre.com/users/445752276/items/search?status=active&access_token=APP_USR-3233228283813631-062604-782e179d439ff3848ba85a5be382c686-445752276

$params = array(
		'status' => 'active',
  	'access_token' => wp_mercadolibre_sync_settings('access_token')
  );
$url = '/users/'.$seller_id.'/items/search';  
$meli_result = $meli->get($url, $params); 

// Check if valid token. todo that part from a filter?   
// TODO, esto tiene que ser una function hoockeable si o si.
$body_message = isset($meli_result['body']->message) ? $meli_result['body']->message : '';
if( $body_message == 'expired_token' || $body_message == 'invalid_token' ){ 

	echo "<p>[".$body_message."] ".__('error, please configure API settings.','wp-mercadolibre-sync')."</p>"; 
	


}


if( $body_message === '' ){

	$meli_items_results = $meli_result['body']->results;

	// other GET items from IDS comma separated
	$ids = join(',', $meli_items_results); 
	$url = '/items?ids='.$ids.'';  
	$meli_result = $meli->get($url, $params); 


	if( isset($meli_result['body']) ){
		?>
		<div class="position-relative row row-no-gutters" data-mmasonry-item=".card">
		<?php
		foreach($meli_result['body'] as $item){
			
			$body = $item->body;
			echo "<pre>";
			//print_r($body);
			echo "</pre>";
			$id = $body->id;
			$title = $body->title;
			$permalink = $body->permalink;  
		  $thumbnail = $body->thumbnail;  

		  $descriptions = $body->thumbnail;  
		  /*
			ej:
			[descriptions] => Array
			      (
			          [0] => stdClass Object
			              (
			                  [id] => MLU464340643-2156119411
			              )

			      )
		  */
			$parsed_query_string = wp_parse_args( $body, array() );
			$parsed_query_string = http_build_query($parsed_query_string, '', '&'); 
			$template_partial = plugin_dir_path( dirname( __FILE__ ) ) . 'partials/ml_get_item.php';
			$template_partial = apply_filters('wp_mercadolibre_sync/template/get_item', $template_partial);
			include $template_partial;  
		}
		?>
		</div>
		<?php
	} 

}
?>