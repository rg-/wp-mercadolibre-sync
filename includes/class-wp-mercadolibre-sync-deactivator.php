<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://github.com/rg-
 * @since      1.0.0
 *
 * @package    Wp_Mercadolibre_Sync
 * @subpackage Wp_Mercadolibre_Sync/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wp_Mercadolibre_Sync
 * @subpackage Wp_Mercadolibre_Sync/includes
 * @author     Roberto García <roberto.jg@gmail.com>
 */
class Wp_Mercadolibre_Sync_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_option( 'wp_mercadolibre_sync_curl_settings' );
		delete_option( 'wp_mercadolibre_sync_refresh_public_count' );
		delete_option( 'wp_mercadolibre_sync_refresh_admin_count' );
		delete_option( 'wp_mercadolibre_sync_settings' );
		delete_option( 'wp_mercadolibre_sync_status' );
		// wp_clear_scheduled_hook( 'Wp_Mercadolibre_Sync_Cron' );
	}

}
