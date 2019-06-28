<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/rg-
 * @since      1.0.0
 *
 * @package    Wp_Mercadolibre_Sync
 * @subpackage Wp_Mercadolibre_Sync/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Mercadolibre_Sync
 * @subpackage Wp_Mercadolibre_Sync/includes
 * @author     Roberto García <roberto.jg@gmail.com>
 */
class Wp_Mercadolibre_Sync_Activator {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;




	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) { 
		$this->plugin_name = $plugin_name;
		$this->version = $version; 
	}
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function activate() {

		// Create ddbb table for plugin options/settings ??  

		// update_option( 'wp_mercadolibre_sync_settings_cron', time() );


		/*
		if (! wp_next_scheduled ( 'Wp_Mercadolibre_Sync_Cron' )) {
    	wp_schedule_event(time(), 'MLexpire', 'Wp_Mercadolibre_Sync_Cron' );
    }

    */

		//update_option( 'wp_mercadolibre_sync_settings', '0' );
	
			// cron job for meli?? 
		 
	} 

} 

add_action('Wp_Mercadolibre_Sync_Cron', function(){
	update_option( 'wp_mercadolibre_sync_settings_cron', 'updateado' );
});

function Wp_Mercadolibre_cron_schedules($schedules){
    if(!isset($schedules["MLexpire"])){
        $schedules["MLexpire"] = array(
            'interval' => 5,
            'display' => __('Once every 5 seconds'));
    } 
    return $schedules;
}
add_filter('cron_schedules','Wp_Mercadolibre_cron_schedules');