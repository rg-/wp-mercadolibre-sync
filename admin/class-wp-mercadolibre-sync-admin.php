<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/rg-
 * @since      1.0.0
 *
 * @package    Wp_Mercadolibre_Sync
 * @subpackage Wp_Mercadolibre_Sync/admin
 * @author     Roberto García <roberto.jg@gmail.com>
 */

class Wp_Mercadolibre_Sync_Admin {

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
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() { 

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-mercadolibre-sync-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() { 

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-mercadolibre-sync-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add dashboard widget for plugin quick info.
	 *
	 * @since    1.0.0
	 */
	public function wp_dashboard_setup(){
		/*
		wp_add_dashboard_widget( 'custom_dashboard_widget', 'Custom Dashoard Widget', function() { 
			require_once plugin_dir_path( __FILE__ ) . 'partials/wp-mercadolibre-sync-admin-dashboard_widget-display.php'; 
		} );
		*/
	}  

	/**
	 * Adding the admin menu & pages 
	 *
	 * @since    1.0.0
	 */

	public function admin_menu() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/add_action-admin_menu.php'; 
	}

	/**
	 * Initialize the admin settings
	 *
	 * @since    1.0.0
	 */
	public function admin_init_settings() { 
		require_once plugin_dir_path( __FILE__ ) . 'partials/add_action-admin_init_settings.php';  
	}

	/**
	 * Register settings
	 *
	 * @since    1.0.0
	 */
	public function admin_register_settings() {   
		require_once plugin_dir_path( __FILE__ ) . 'partials/add_action-admin_register_settings.php'; 
	}

	/**
	 * Set the admin noticies depending on, mosty, the API status reported.
	 *
	 * @since    1.0.0
	 */
	public function admin_notices(){
		require_once plugin_dir_path( __FILE__ ) . 'partials/add_action-admin_notices.php'; 	
	}  

	/**
	 * Add admin body class.
	 *
	 * @since    1.0.1
	 */
	public function admin_body_class($classes){
		return "$classes wp-mercadolibre-sync";
	}

	/**
	 * Remove query args on urls.
	 *
	 * @since    1.0.1
	 */
	public function admin_removable_query_args($args){
		/**
		 * An array list of query parameters removed by WordPress is returned by wp_removable_query_args()
		 * It includes a removable_query_args filter. Thus, we can add our plugin query parameters. 
		*/
		$args[] = 'refresh_token';
		$args[] = 'code';
		$args[] = 'access_token';
		$args[] = 'test_user';
		$args[] = 'post_test';
		
		return $args;
	}

	/**
	 * Fillter plugin action links on admin plugins page.
	 *
	 * @since    1.0.1
	 */
	public function plugin_action_links( $actions, $plugin_file ){ 

		static $plugin;

		if (!isset($plugin))
			$plugin = WP_PLUGIN_PATH;
			if ($plugin == $plugin_file) { 
				$settings = array('settings' => '<a href="options-general.php?page='.$this->plugin_name.'">' . __('Settings', 'wp-mercadolibre-sync') . '</a>');  
	    		$actions = array_merge($settings, $actions);  
			} 
		return $actions;
	}

	/**
	 * Simple validate for input texts.
	 *
	 * @since    1.0.0
	 */
	public function _validate($input){

		$validated = array();

		if( isset( $input['appId'] ) ){
			$input['appId'] = absint( $input['appId'] ); 
		}
    if( isset( $input['secretKey'] ) ){
			$input['secretKey'] = sanitize_text_field( $input['secretKey'] ); 
		}
		if( isset( $input['redirectURI'] ) ){
			$input['redirectURI'] = sanitize_url( $input['redirectURI'] ); 
		}
		if( isset( $input['siteId'] ) ){
			$input['siteId'] = $input['siteId']; 
		}         
 
    return $input;

	}  

	/**
	 * Get an array with all the public settings fields used. Required, user editable
	 *
	 * @since    1.0.0
	 */
	public function _get_setting_fields(){ 
		$fields = array('appId','secretKey','redirectURI','siteId'); 
		return $fields;  
	}

	/**
	 * Get an array with all the private settings fields used. Required, not editable by user.
	 *
	 * @since    1.0.0
	 */
	public function _get_private_fields(){ 
		$fields = array('access_token','expires_in','refresh_token'); 
		return $fields;  
	}

	/*********************
	 *********************
	 **									**
	 ** Template parts 	**
	 **									**
	 *********************
	 ********************/

	/**
	 * Callback for admin page
	 *
	 * @since    1.0.0
	 */
	public function wp_mercadolibre_sync_options_page() {  
		$this->wp_mercadolibre_sync_options_page_start_ev(array(
			'headline'=> __( 'WP Mercadolibre Sync', 'wp-mercadolibre-sync' ),
			'partials'=> array(
				'wp-mercadolibre-sync-admin-display',
				'wp-mercadolibre-sync-admin-display-settings'
			)
		));  
		$this->wp_mercadolibre_sync_options_page_end_ev();
	}

	/**
	 * Callback for test page
	 *
	 * @since    1.0.0
	 */
	public function wp_mercadolibre_sync_tests_page( ) { 
		$this->wp_mercadolibre_sync_options_page_start_ev(array(
			'headline'=> __( 'WP Mercadolibre Sync', 'wp-mercadolibre-sync' ).' > '.__( 'Tests', 'wp-mercadolibre-sync' ),
			'partials'=> array(
				'wp-mercadolibre-sync-admin-tests'
			)
		)); 
		$this->wp_mercadolibre_sync_options_page_end_ev(); 
	}

	/**
	 * Callback for modules page
	 *
	 * @since    1.0.1
	 */
	public function wp_mercadolibre_sync_integration_page( ) { 
		$this->wp_mercadolibre_sync_options_page_start_ev(array(
			'headline'=> __( 'WP Mercadolibre Sync', 'wp-mercadolibre-sync' ).' > '.__( 'Integration', 'wp-mercadolibre-sync' ),
			'partials'=> array(
				'wp-mercadolibre-sync-admin-integration'
			)
		));  
		$this->wp_mercadolibre_sync_options_page_end_ev();  
	} 

	/**
	 * Callback for settings section
	 *
	 * @since    1.0.0
	 */
	public function wp_mercadolibre_sync_settings_section_advanced_callback( ) {  
		?>
		<h2 class="wpmlsync__postbox-title"><?php echo __( 'Advanced Settings', 'wp-mercadolibre-sync' ); ?></h2>
		<p class='about-description'><?php echo __( 'We recommend using these options only for development. The option to activate Auto Token should always be active on production sites.', 'wp-mercadolibre-sync' ); ?></p>
		<?php
	}


	/**
	 * Callback for settings section
	 *
	 * @since    1.0.0
	 */
	public function wp_mercadolibre_sync_settings_section_private_callback( ) {  
		?>
		<h2 class="wpmlsync__postbox-title"><?php echo __( 'oAuth Data', 'wp-mercadolibre-sync' ); ?></h2>
		<p class='about-description'><?php echo __( 'These data are private, can be used to interact with the API. See the Mercado Libre API documentation for more information.', 'wp-mercadolibre-sync' ); ?></p>
		<br><span class='wpmlsync__badge'><span class="dashicons dashicons-warning"></span> <?php echo __( 'Never share this data.', 'wp-mercadolibre-sync' ); ?></span>
		<?php 
 
	}

	/**
	 * Callback for settings section
	 *
	 * @since    1.0.0
	 */
	public function wp_mercadolibre_sync_settings_section_callback( ) {  
		?>
		<h2 class="wpmlsync__postbox-title"><?php echo __( 'API Settings', 'wp-mercadolibre-sync' ); ?></h2>
		<p class='about-description'><?php echo __( 'These data must be exactly the same as those set in the Application created in Mercado Libre previously. ', 'wp-mercadolibre-sync' ); ?></p>
		<p class='about-description'>"siteId" <?php echo __('refers to the country of the user used for the application.', 'wp-mercadolibre-sync');?></p>
		<?php 
 
	}
	
	/**
	 * Build start html for page options
	 *
	 * @since    1.0.0
	 */
	public function wp_mercadolibre_sync_options_page_start_ev($args=array()){
		?>
		<div class="wrap wpmlsync__wrap">
			<div id="poststuff">
			  <div id="post-body" class="metabox-holder columns-1">

			  	<h1 class="wp-heading-inline"><?php echo $args['headline']; ?></h1>
					<div class="clear"></div>
					<br>
		<?php
		if(!empty($args['partials'])){
			foreach ($args['partials'] as $partial) {
				require_once plugin_dir_path( __FILE__ ) . 'partials/'.$partial.'.php';
			}
		}
	}

	/**
	 * Build end html for page options
	 *
	 * @since    1.0.0
	 */
	public function wp_mercadolibre_sync_options_page_end_ev($args=array()){
		?>
				</div>
		  </div>
		</div>
		<?php
	}

}