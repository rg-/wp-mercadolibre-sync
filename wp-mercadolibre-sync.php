<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://github.com/rg-
 * @since             1.0.0
 * @package           Wp_Mercadolibre_Sync
 *
 * @wordpress-plugin
 * Plugin Name:       WP Mercadolibre Sync
 * Plugin URI:        https://github.com/rg-/wp-mercadolibre-sync
 * Description:       Wordpress & Mercadolibre syncronization using APIs.
 * Version:           1.0.1
 * Author:            Roberto García
 * Author URI:        https://github.com/rg-
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-mercadolibre-sync
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/rg-/wp-mercadolibre-sync
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_MERCADOLIBRE_SYNC_VERSION', '1.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-mercadolibre-sync-activator.php
 */
function activate_wp_mercadolibre_sync() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-mercadolibre-sync-activator.php';
	Wp_Mercadolibre_Sync_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-mercadolibre-sync-deactivator.php
 */
function deactivate_wp_mercadolibre_sync() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-mercadolibre-sync-deactivator.php';
	Wp_Mercadolibre_Sync_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_mercadolibre_sync' );
register_deactivation_hook( __FILE__, 'deactivate_wp_mercadolibre_sync' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-mercadolibre-sync.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_mercadolibre_sync() {

	$plugin = new Wp_Mercadolibre_Sync();
	$plugin->run();

}
run_wp_mercadolibre_sync();
