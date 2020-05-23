<?php

echo $_GET['new_product']; 


// create Meli
$meli = new Meli($wp_mercadolibre_sync_settings['appId'], $wp_mercadolibre_sync_settings['secretKey']);
if(empty($meli)) return false;
 

// Set params to use 
$_item = $meli->get(
	'/items/'.$_GET['new_product'].'',
	array( 
  	'access_token' => $wp_mercadolibre_sync_settings['access_token']
  ));
if( empty($_item['body']->id) ) { 
  return false;
} else {

$item = $_item['body'];


// https://www.sbloggers.com/add-a-woocommerce-product-using-custom-php-code-programmatically

	if(class_exists('WC_Product')){ 
		// For simple product
		$objProduct = new WC_Product();
		// For variable product
		// $objProduct = new WC_Product_Variable();

		

		$objProduct->set_name($item->title);
		$objProduct->set_status("publish");  // can be publish,draft or any wordpress post status
		$objProduct->set_catalog_visibility('visible'); // add the product visibility status
		$objProduct->set_description("Product Description");
		$objProduct->set_sku($item->id); //can be blank in case you don't have sku, but You can't add duplicate sku's
		$objProduct->set_price($item->price); // set product price
		$objProduct->set_regular_price($item->base_price); // set product regular price
		$objProduct->set_manage_stock(true); // true or false
		$objProduct->set_stock_quantity($item->available_quantity);
		$objProduct->set_stock_status('instock'); // in stock or out of stock value
		$objProduct->set_backorders('no');
		$objProduct->set_reviews_allowed(false);
		$objProduct->set_sold_individually(false);

		//$objProduct->set_category_ids(array(1,2,3));

		
		// For upload images into product
		$pictures = $item->pictures; 
		$images = array(); // images url array of product
		foreach ($pictures as $picture){ 
			$images[] = $picture->url;
		}
		if(!empty($images)){
			function uploadMedia($image_url){
				require_once( ABSPATH . 'wp-admin/includes/image.php');
				require_once( ABSPATH . 'wp-admin/includes/file.php');
				require_once( ABSPATH . 'wp-admin/includes/media.php');
				$media = media_sideload_image($image_url,0);
				$attachments = get_posts(array(
					'post_type' => 'attachment',
					'post_status' => null,
					'post_parent' => 0,
					'orderby' => 'post_date',
					'order' => 'DESC'
				));
				return $attachments[0]->ID;
			} 
			$productImagesIDs = array();  
			foreach($images as $image){
				$mediaID = uploadMedia($image);  
				if($mediaID) $productImagesIDs[] = $mediaID; 
			}
			if($productImagesIDs){
				$objProduct->set_image_id($productImagesIDs[0]); 
				if(count($productImagesIDs) > 1){
					$objProduct->set_gallery_image_ids($productImagesIDs);
				}
			}
		}

		$product_id = $objProduct->save();

		echo "<pre>product_id: <br>";
		print_r($objProduct);
		echo "</pre>";

	}
	
	
} 
?>