<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/rg-
 * @since      1.0.0
 *
 * @package    Wp_Mercadolibre_Sync
 * @subpackage Wp_Mercadolibre_Sync/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wp_Mercadolibre_Sync
 * @subpackage Wp_Mercadolibre_Sync/includes
 * @author     Roberto García <roberto.jg@gmail.com>
 */
class Wp_Mercadolibre_Sync {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wp_Mercadolibre_Sync_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WP_MERCADOLIBRE_SYNC_VERSION' ) ) {
			$this->version = WP_MERCADOLIBRE_SYNC_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wp-mercadolibre-sync'; 

		$this->load_dependencies(); 
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();  

	} 

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_Mercadolibre_Sync_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Mercadolibre_Sync_i18n. Defines internationalization functionality.
	 * - Wp_Mercadolibre_Sync_Admin. Defines all hooks for the admin area.
	 * - Wp_Mercadolibre_Sync_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
 
		/**
		 * 3rd party Meli SDK
		 * https://github.com/mercadolibre/php-sdk
		 * meli-debug.php has a very, very little change to disable SSL for CURL in order to let work on localhost or test sites
		 */
		$use_debug_meli = true;
		$meli_path = $use_debug_meli ? 'Meli/meli-debug.php' : 'Meli/meli.php'; 
		require_once plugin_dir_path( dirname( __FILE__ ) ) . $meli_path;

		/**
		 * Many plugin functions 
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/wp-mercadolibre-sync-functions.php'; 

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-mercadolibre-sync-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-mercadolibre-sync-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-mercadolibre-sync-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-mercadolibre-sync-public.php'; 


		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'shortcodes/wp-mercadolibre-sync-shortcodes.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'ajax/wp-mercadolibre-sync-ajax.php';

		$this->loader = new Wp_Mercadolibre_Sync_Loader();

	}  


	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Mercadolibre_Sync_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wp_Mercadolibre_Sync_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}
 

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wp_Mercadolibre_Sync_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		/* 
		// could be usedfull do something on sessin in/out on future....
		$this->loader->add_action( 'init', $plugin_admin, 'session_init', 1 );
		$this->loader->add_action( 'wp_logout', $plugin_admin, 'session_end' );
		$this->loader->add_action( 'wp_login', $plugin_admin, 'session_end');
		$this->loader->add_action( 'end_session_action', $plugin_admin, 'session_end');
		*/

		// TODO, put some widget with status and so on on dashboard
		// $this->loader->add_action( 'wp_dashboard_setup', $plugin_admin, 'wp_dashboard_setup', 99 );
		
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu' );

		if ( is_admin() && ( ! wp_doing_ajax() ) ) { // is this needed??
			$this->loader->add_action( 'admin_init', $plugin_admin, 'admin_init_settings', 1 );
			$this->loader->add_action( 'admin_init', $plugin_admin, 'admin_register_settings', 2 );
			$this->loader->add_action( 'admin_notices', $plugin_admin, 'admin_notices' );
		} 
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wp_Mercadolibre_Sync_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		if ( !is_admin() && ( ! wp_doing_ajax() ) ) { // is this needed??
			$this->loader->add_action( 'init', $plugin_public, 'init_settings', 1 );
		} 

	}
 

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wp_Mercadolibre_Sync_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
