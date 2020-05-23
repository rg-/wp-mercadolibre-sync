<?php

$WPMLSync = wp_mercadolibre_sync_settings(); 
$api_status = wp_mercadolibre_sync_get_api_status();
$meli_code_array = wp_mercadolibre_sync_meli_code_array(); 

$screen = get_current_screen();

if(isset($screen) && $screen->parent_base == $this->plugin_name){

	if( $api_status == 5 ){
		?>
    <div class="notice notice-success is-dismissible">
    		<p><?php echo $meli_code_array[$api_status]['desc']; ?></p>
    </div>
    <?php
	}
	if( $api_status == 8 ){
		?>
    <div class="notice notice-success is-dismissible">
        <p><?php echo $meli_code_array[$api_status]['desc']; ?></p>
    </div>
    <?php
	}
	if( $api_status == 4 ){
		?>
    <div class="notice notice-success is-dismissible">
        <p><?php echo $meli_code_array[$api_status]['desc']; ?></p>
    </div>
    <?php
	}
	if( $api_status == 7 ){
		?>
    <div class="notice notice-warning is-dismissible">
        <p><?php echo $meli_code_array[$api_status]['desc']; ?></p>
    </div>
    <?php
	}

}