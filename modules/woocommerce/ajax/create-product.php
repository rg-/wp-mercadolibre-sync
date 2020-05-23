<?php

// https://rudrastyh.com/woocommerce/rest-api-create-update-remove-products.html
// https://woocommerce.github.io/woocommerce-rest-api-docs/?php#create-a-product
// https://gist.github.com/maddisondesigns/e7ee7eef7588bbba2f6d024a11e8875a
// https://wordpress.stackexchange.com/questions/286732/add-products-to-woocommerce-through-wp-api

$wpmlsync = wp_mercadolibre_sync_settings();

$item_id = $_GET['create_product'];
$item_id = isset($_POST['create_product']) ? $_POST['create_product'] : $item_id;

if( !empty(wc_get_product_id_by_sku( $item_id )) ){
	// echo "Product exists!";
	return false;
}

// create Meli
$meli = new Meli($wpmlsync['appId'], $wpmlsync['secretKey']);
if(empty($meli)) return false;
 

// Set params to use 
$_item = $meli->get(
	'/items/'.$item_id.'',
	array( 
  	'access_token' => $wpmlsync['access_token']
  ));
if( empty($_item['body']->id) ) { 
  return false;
} else {

	$item = $_item['body']; 
	
	/* Create an API controller */
	$api = new WC_REST_Products_Controller();
	
	/* Build the request to create a new product */
  $request = new WP_REST_Request ('POST', '', '');
   
	$POST_params = apply_filters('wpmlsync/create-woo-product/post_params', $POST_params, $item, $meli);
  $request->set_body_params( $POST_params );
	 
  /* Here the magic ! */
  $response = $api->create_item( $request );

  /* Then i could take a product by same SKU passed, that´s de ML Item ID in fact. So SKU in woocommerce will be the ML ID. Clear. */ 
  $woo_product_id = wc_get_product_id_by_sku( $item->id ); 
  /* Encoding the ML item response into a JSON format */
	$json_encode = json_encode($item);
	/* Update/Create a custom product meta with the JSON/ITEM object */
	update_post_meta( $woo_product_id, '_wpmlsync_json', $json_encode ); 
  
  /* Some testings */
	$woo_product_id = wc_get_product_id_by_sku( $item->id );
	$woo_product_obj = wc_get_product($woo_product_id); 
	$woo_product_data = $woo_product_obj->get_data();
	//echo "<pre>response: <br>";

	/*

	IMPORTANT, this is the response taked by scripts.js for woocommerce module.
	Basicly we have two things retruned, the woo product itself, and the json saved as meta.
	This little thing, makes the heart for the sync/update Woo products on admin, since it will check against changes on the ML items publications using the last updated date. 
	This date will have nothing to do with the post type Product that Woocommerce use. In fact, in both sides, this date meta values can´t be changed from APIS, both APIS, the woo one and the ML one too. And that´s how it should be.

	*/
	$date_created = strtotime($item->date_created);
	$date_created = date("Y-m-d h:i:sa", $date_created);
	$last_updated = strtotime($item->last_updated);
	$last_updated = date("Y-m-d h:i:sa", $last_updated);
	$returned = array(
		'product_data' => json_encode($woo_product_data),
		'wpmlsync_json' => json_encode($item),
		'date_created' => $date_created,
		'last_updated' => $last_updated
	);
	print_r( json_encode($returned) );
	//echo "</pre>"; 
	//return json_encode($woo_product_data);
	
	
}
?>