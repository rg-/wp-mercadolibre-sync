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
	 * The DB version of this plugin.
	 *
	 * @since    1.0.!
	 * @access   private
	 * @var      string    $db_version    The DB version of this plugin.
	 */
	private $db_version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $db_version ) { 
		$this->plugin_name = $plugin_name;
		$this->version = $version; 
		$this->db_version = $db_version; 
	}
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function activate() { 

		/*

		See also deactivate on Wp_Mercadolibre_Sync_Deactivator() class

		*/

		if ( defined( 'WP_MERCADOLIBRE_SYNC_DB_VERSION' ) ) {
			$db_version = WP_MERCADOLIBRE_SYNC_DB_VERSION;
		} else {
			$db_version = '1.0';
		}

		update_option( 'wp_mercadolibre_sync_db_version', $db_version );

		// Get global $wpdb and some stuff
		global $wpdb; 
		$charset_collate = $wpdb->get_charset_collate();
 		$prefix = $wpdb->prefix;  

 		/*

 		Remember TODO, check WP_MERCADOLIBRE_SYNC_DB_VERSION if update needed, see
 		https://codex.wordpress.org/Creating_Tables_with_Plugins#Create_Database_Tables

		This is a clone of wp_options table as example of usage

		`option_id` bigint(20) UNSIGNED NOT NULL,
		`option_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
		`option_value` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
		`autoload` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes'
 		*/

 		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		// Create ddbb tables for plugin options/settings
		$sql_options = "CREATE TABLE ".$prefix."mercadolibre_sync_options (
			name text NOT NULL,
			value text NOT NULL
		) $charset_collate;";
		dbDelta( $sql_options );
		 
	} 

} 