<?php

$wpmlsync = wp_mercadolibre_sync_settings();

$item_id = $_GET['get_product'];

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
 
	?>

	<table width="100%">
		<thead>
			<tr>
				<th align="left" style="padding:10px 0;">ML item:</th>
				<th align="left" style="padding:10px 0;">Woo product:</th>
			</tr>
		</thead>
		<tr>
			<td width="50%" style="vertical-align: top; border-bottom:1px solid #eee;">
				<?php
				echo "item ID: ".$item->id;

				echo "<pre>item: ";
				print_r($item);
				echo "</pre>";
				// date_created
				// last_updated 
				echo "<pre>date_created: ";
				$d=strtotime($item->date_created);
				echo date("Y-m-d h:i:sa", $d); 
				echo "</pre>";
				echo "<pre>last_updated: ";
				$d=strtotime($item->last_updated);
				echo date("Y-m-d h:i:sa", $d); 
				echo "</pre>";

				echo "<pre>descriptions: ";
				$description = wp_mercadolibre_sync_get_item_description($item, $meli);
				print_r($description); 
				echo "</pre>";

				echo "<pre>pictures: ";
				$pictures = wp_mercadolibre_sync_get_item_pictures($item, $meli);
				print_r($pictures); 
				echo "</pre>";
				?>
			</td>
			<td width="50%" style="vertical-align: top; border-bottom:1px solid #eee;">
				<?php 
				/*

				array(
			    'name'               => '',
			    'slug'               => '',
			    'date_created'       => null,
			    'date_modified'      => null,
			    'status'             => false,
			    'featured'           => false,
			    'catalog_visibility' => 'visible',
			    'description'        => '',
			    'short_description'  => '',
			    'sku'                => '',
			    'price'              => '',
			    'regular_price'      => '',
			    'sale_price'         => '',
			    'date_on_sale_from'  => null,
			    'date_on_sale_to'    => null,
			    'total_sales'        => '0',
			    'tax_status'         => 'taxable',
			    'tax_class'          => '',
			    'manage_stock'       => false,
			    'stock_quantity'     => null,
			    'stock_status'       => 'instock',
			    'backorders'         => 'no',
			    'low_stock_amount'   => '',
			    'sold_individually'  => false,
			    'weight'             => '',
			    'length'             => '',
			    'width'              => '',
			    'height'             => '',
			    'upsell_ids'         => array(),
			    'cross_sell_ids'     => array(),
			    'parent_id'          => 0,
			    'reviews_allowed'    => true,
			    'purchase_note'      => '',
			    'attributes'         => array(),
			    'default_attributes' => array(),
			    'menu_order'         => 0,
			    'post_password'      => '',
			    'virtual'            => false,
			    'downloadable'       => false,
			    'category_ids'       => array(),
			    'tag_ids'            => array(),
			    'shipping_class_id'  => 0,
			    'downloads'          => array(),
			    'image_id'           => '',
			    'gallery_image_ids'  => array(),
			    'download_limit'     => -1,
			    'download_expiry'    => -1,
			    'rating_counts'      => array(),
			    'average_rating'     => 0,
			    'review_count'       => 0,
			)

				*/

			$woo_product_id = wc_get_product_id_by_sku( $item->id );
			if(empty($woo_product_id)){
				echo "This item is not sincronized as Woocommerce Product yet.";
			}

				
				
				$woo_product_obj = wc_get_product($woo_product_id); 
				$woo_product_data = $woo_product_obj->get_data();

				echo "product ID: ".$woo_product_id;
				echo " / product sku: ".$woo_product_data['sku']; 

				$json = get_post_meta( $woo_product_id, '_wpmlsync_json', true);
				echo "<pre>json: ";
				print_r(json_decode($json, true));
				echo "</pre>";
				/*
				echo "<pre>date_created: ";
				$d = $woo_product_data['date_created']->getTimestamp(); 
				echo date("Y-m-d h:i:sa", $d); 
				echo "</pre>";
				echo "<pre>date_modified: ";
				$d = $woo_product_data['date_modified']->getTimestamp(); 
				echo date("Y-m-d h:i:sa", $d); 
				echo "</pre>"; 
				*/
				?>
			</td>
		</tr>
	</table>

	<?php	
}