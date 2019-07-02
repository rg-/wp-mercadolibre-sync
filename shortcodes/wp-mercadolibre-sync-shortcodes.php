<?php

function WPMLSYNC_get_user_FN( $atts, $content = "" ) {  
	$shortcode_atts = wp_parse_args($shortcode_atts, array( ));
	$out = '';   
	// Check if expired/actived token 
	ob_start();  
		$file = wp_mercadolibre_sync_get_template('user');
		if(!empty($file)){
			include ($file); 
		} 
		$out = ob_get_contents();
	ob_end_clean();
	return $out; 
}

function WPMLSYNC_get_item_FN( $shortcode_atts, $content = "" ) {  
	$shortcode_atts = wp_parse_args($shortcode_atts, array( ));
	$out = '';   
	// Check if expired/actived token 
	ob_start();
		
		$file = wp_mercadolibre_sync_get_template('item');
		if(!empty($file)){
			include ($file); 
		} 
		$out = ob_get_contents();
	ob_end_clean();
	return $out; 
}

function WPMLSYNC_get_items_FN( $shortcode_atts, $content = "" ) { 
	$shortcode_atts = wp_parse_args($shortcode_atts, array( ));
	$out = '';   
	// Check if expired/actived token 
	ob_start();  
		$file = wp_mercadolibre_sync_get_template('items-row');
		if(!empty($file)){
			include ($file); 
		} 
		$out = ob_get_contents();
	ob_end_clean();
	return $out; 
}

add_shortcode( 'WPMLSYNC_get_user', 'WPMLSYNC_get_user_FN' );
add_shortcode( 'WPMLSYNC_get_item', 'WPMLSYNC_get_item_FN' );
add_shortcode( 'WPMLSYNC_get_items', 'WPMLSYNC_get_items_FN' ); 

function WPMLSYNC_get_single_item_FN( $shortcode_atts, $content = "" ) { 
	$shortcode_atts = wp_parse_args($shortcode_atts, array( ));
	$out = '';   
	// Check if expired/actived token 
	ob_start();
		if(isset($_GET['itemId'])){
			$shortcode_atts['id'] = $_GET['itemId'];
		}
		$file = wp_mercadolibre_sync_get_template('item-single');
		if(!empty($file)){
			include ($file); 
		} 
		$out = ob_get_contents();
	ob_end_clean();
	return $out; 
}

add_shortcode( 'WPMLSYNC_get_single_item', 'WPMLSYNC_get_single_item_FN' );