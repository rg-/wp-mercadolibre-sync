<?php

$pages = array(); 
$pages[] = add_menu_page(
    __( 'Mercadolibre Sync', 'wp-mercadolibre-sync' ),
    __( 'Mercadolibre Sync', 'wp-mercadolibre-sync' ),
    'manage_options',
    $this->plugin_name,
    array( $this, 'wp_mercadolibre_sync_options_page' ),
    'dashicons-cart',
    80
); 
/*
$pages[] = add_submenu_page(
	$this->plugin_name,
	__( 'Integration', 'wp-mercadolibre-sync' ),
	__( 'Integration', 'wp-mercadolibre-sync' ),
	'manage_options',
	$this->plugin_name.'-integration',
	array( $this, 'wp_mercadolibre_sync_integration_page' )
);
*/
$pages[] = add_submenu_page(
	$this->plugin_name,
	__( 'Tests', 'wp-mercadolibre-sync' ),
	__( 'Tests', 'wp-mercadolibre-sync' ),
	'manage_options',
	$this->plugin_name.'-tests',
	array( $this, 'wp_mercadolibre_sync_tests_page' )
);

$pages = apply_filters('wpmlsync/admin_menu',$pages);

// Do something on load-[PAGE]
foreach($pages as $page){
	add_action( 'load-' . $page, function( ){  
	// add admin body class
	add_filter('admin_body_class', array($this, 'admin_body_class') ); 
	// remove query args when save or similar
	add_filter('removable_query_args', array($this, 'admin_removable_query_args') ); 
});

}