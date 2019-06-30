<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/rg-
 * @since      1.0.0
 *
 * @package    Wp_Mercadolibre_Sync
 * @subpackage Wp_Mercadolibre_Sync/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
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

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Mercadolibre_Sync_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Mercadolibre_Sync_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-mercadolibre-sync-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Mercadolibre_Sync_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Mercadolibre_Sync_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-mercadolibre-sync-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function session_init(){
		if(!session_id()){
			session_start();
		}
	}
	public function session_end(){
		session_destroy();
	}

	public function wp_dashboard_setup(){
		wp_add_dashboard_widget( 'custom_dashboard_widget', 'Custom Dashoard Widget', function() { 
			require_once plugin_dir_path( __FILE__ ) . 'partials/wp-mercadolibre-sync-admin-dashboard_widget-display.php'; 
		} );
	} 

	public function admin_body_class( $classes ) {
		$screen = get_current_screen();
		if(isset($screen) && $screen->parent_base == $this->plugin_name){
			 $classes = "$classes wp-mercadolibre-sync";
		}
		return $classes;
	}

	public function admin_menu() {

		add_menu_page(
        __( 'Mercadolibre Sync', 'wp-mercadolibre-sync' ),
        __( 'Mercadolibre Sync', 'wp-mercadolibre-sync' ),
        'manage_options',
        $this->plugin_name,
        array( $this, 'wp_mercadolibre_sync_options_page' ),
        'dashicons-cart',
        80
    );
    add_submenu_page(
    	$this->plugin_name,
    	__( 'Debug', 'wp-mercadolibre-sync' ),
    	__( 'Debug', 'wp-mercadolibre-sync' ),
    	'manage_options',
    	$this->plugin_name.'-debug',
    	array( $this, 'wp_mercadolibre_sync_debug_page' )
    );
		// add_options_page('WP Mercadolibre Sync', 'WP Mercadolibre Sync', 'manage_options', $this->plugin_name, array( $this, 'wp_mercadolibre_sync_options_page' )  );

	}

	/*

	TODO, add description, see also init_settings() on public hoocks, it´s quite same thing

	*/
	public function admin_notices(){
		$WPMLSync = wp_mercadolibre_sync_settings(); 
		$api_status = wp_mercadolibre_sync_get_api_status();
		$meli_code_array = wp_mercadolibre_sync_meli_code_array(); 

		$screen = get_current_screen();
		if(isset($screen) && $screen->parent_base == $this->plugin_name){

			if( $api_status == 5 ){
				?>
		    <div class="notice notice-success is-dismissible">
		        <p><?php _e( 'Token refreshed!!', 'wp-mercadolibre-sync' ); ?></p>
		    </div>
		    <?php
			}
			if( $api_status == 8 ){
				?>
		    <div class="notice notice-success is-dismissible">
		        <p><?php _e( 'Re-autorized API. Token refreshed!!', 'wp-mercadolibre-sync' ); ?></p>
		    </div>
		    <?php
			}
			if( $api_status == 4 ){
				?>
		    <div class="notice notice-success is-dismissible">
		        <p><?php _e( 'Perfecto!! Plugin configurado correctamente.', 'wp-mercadolibre-sync' ); ?></p>
		    </div>
		    <?php
			}
			if( $api_status == 7 ){
				?>
		    <div class="notice notice-warning is-dismissible">
		        <p><?php _e( 'Plugin needs more settings.', 'wp-mercadolibre-sync' ); ?></p>
		    </div>
		    <?php
			}

		}
		
	}
	public function admin_init_settings() {

		$api_status = 0; 
		
		$WPMLSync = wp_mercadolibre_sync_settings(); 

		if( array_filter($WPMLSync) ){

			$api_status = 1;
			$MELI = new Meli($WPMLSync['appId'], $WPMLSync['secretKey']);
			$Exception = '';

			if(isset($_GET['code']) || !empty($WPMLSync['access_token']) ) {
				
				$api_status = 2;

				// If code exist and session is empty
				if(isset($_GET['code']) && empty($WPMLSync['access_token'])) {
					// //If the code was in get parameter we authorize
					$api_status = 3;
					try{
						$user = $MELI->authorize($_GET["code"], $WPMLSync['redirectURI']);  
						$_seller_id = wp_mercadolibre_sync_get_seller_id($MELI, $user['body']->access_token); 

						wp_mercadolibre_sync_update_settings(array(
							'access_token' => $user['body']->access_token,
							'expires_in' => $user['body']->expires_in,
							'refresh_token' => $user['body']->refresh_token,
							'seller_id' => $_seller_id
						));

						$api_status = 4;
					}catch(Exception $e){
						$Exception .= $e->getMessage(). "\n";
					}
				} else {
					
					// Check if the access token is invalid checking time vs expires_in
					$_expire_test = isset($_GET['refresh_token']) ? true : false;
					if(isset($_GET['code'])){
						$_expire_test = true;
					}
					$_check_expires_in = !empty($WPMLSync['expires_in']) ? $WPMLSync['expires_in'] : time();
					if( ($_check_expires_in < time() && !empty($WPMLSync['auto_token']) ) || $_expire_test) {	
						try {
							// Make the refresh proccess 
 
							$refresh_MELI = new Meli($WPMLSync['appId'], $WPMLSync['secretKey'], $WPMLSync['access_token'], $WPMLSync['refresh_token']);

							$refresh = $refresh_MELI->refreshAccessToken();  
							$_seller_id = wp_mercadolibre_sync_get_seller_id($refresh_MELI, $refresh['body']->access_token);

							wp_mercadolibre_sync_update_settings(array(
								'access_token' => $refresh['body']->access_token,
								'expires_in' => $refresh['body']->expires_in,
								'refresh_token' => $refresh['body']->refresh_token,
								'seller_id' => $_seller_id
							));

							$api_status = 5;
							if(isset($_GET['code'])){
								$api_status = 8;
							}

							$refresh_public_count = (null !== get_option('wp_mercadolibre_sync_refresh_admin_count')) ? get_option('wp_mercadolibre_sync_refresh_admin_count') : 0;
							update_option('wp_mercadolibre_sync_refresh_admin_count', ($refresh_public_count + 1));


						} catch (Exception $e) {
						  	$Exception .= $e->getMessage(). "\n";
						}
					}else{ 
						$api_status = 6;
					}
				}  

			} else {
				$api_status = 7; 
			}
 
		}

		update_option('wp_mercadolibre_sync_status',$api_status);
		wp_mercadolibre_sync_debug('update_option::wp_mercadolibre_sync_status status: '.$api_status.'');
	}

	public function admin_register_settings() {  

		// Register settings

		register_setting( 'wp_mercadolibre_sync_api', 'wp_mercadolibre_sync_settings', array($this, '_validate' ) );

		add_settings_section(
			'wp_mercadolibre_sync_settings_section', 
			__( '', 'wp-mercadolibre-sync' ), 
			array( $this, 'wp_mercadolibre_sync_settings_section_callback' ) , 
			$this->plugin_name
		); 
		add_settings_section(
			'wp_mercadolibre_sync_settings_section_advanced', 
			__( '', 'wp-mercadolibre-sync' ), 
			array( $this, 'wp_mercadolibre_sync_settings_section_advanced_callback' ) , 
			$this->plugin_name.'-advanced'
		); 
		

		add_settings_section(
			'wp_mercadolibre_sync_settings_section_private', 
			__( '', 'wp-mercadolibre-sync' ), 
			array( $this, 'wp_mercadolibre_sync_settings_section_private_callback' ) , 
			$this->plugin_name.'-private'
		); 
 

		// Set user fields

		$fields = $this->_get_setting_fields();
		foreach($fields as $field){
			add_settings_field( 
				'wp_mercadolibre_sync_'.$field, 
				$field, 
				function() use ( $field ) {
					$options = get_option( 'wp_mercadolibre_sync_settings' );
					$value = isset($options['wp_mercadolibre_sync_'.$field]) ? $options['wp_mercadolibre_sync_'.$field] : '';
					?>
					<input required type='text' name='wp_mercadolibre_sync_settings[wp_mercadolibre_sync_<?php echo $field; ?>]' value='<?php echo $value; ?>' class='wpmlsync__control'>
					<?php 
				} , 
				$this->plugin_name, 
				'wp_mercadolibre_sync_settings_section' 
			);
		} 

		// Set seller_id field TODO pass it to private fields array, has no utility to leave it alone

		add_settings_field( 
			'wp_mercadolibre_sync_seller_id',
			'seller_id',
			function() {   
				 $field = 'seller_id';
				 $options = get_option( 'wp_mercadolibre_sync_settings' );
					$value = isset($options['wp_mercadolibre_sync_'.$field]) ? $options['wp_mercadolibre_sync_'.$field] : ''; 
					?>
					<input readonly type='text' name='wp_mercadolibre_sync_settings[wp_mercadolibre_sync_<?php echo $field; ?>]' value='<?php echo $value; ?>' class='wpmlsync__control'>
					<?php  
			},
			$this->plugin_name.'-private',
				'wp_mercadolibre_sync_settings_section_private' 
		);  

		// Set private fields

		$private_fields = $this->_get_private_fields();
		foreach($private_fields as $field){
			add_settings_field( 
				'wp_mercadolibre_sync_'.$field, 
				$field, 
				function() use ( $field ) { 
					$options = get_option( 'wp_mercadolibre_sync_settings' );
					$value = isset($options['wp_mercadolibre_sync_'.$field]) ? $options['wp_mercadolibre_sync_'.$field] : ''; 
					?>
					<input readonly type='text' name='wp_mercadolibre_sync_settings[wp_mercadolibre_sync_<?php echo $field; ?>]' value='<?php echo $value; ?>' class='wpmlsync__control'>
					<?php  
				} , 
				$this->plugin_name.'-private',
				'wp_mercadolibre_sync_settings_section_private' 
			);
		} 

		/**
		 *
		 * 'auto_token'
		 * 
		 */
		add_settings_field( 
			'wp_mercadolibre_sync_auto_token',
			'auto_token',
			function() {  
					$field = 'auto_token';
					$options = get_option( 'wp_mercadolibre_sync_settings' ); 
					if(empty($options)){
						$checked = 'checked';
					}else{
						$checked = isset($options['wp_mercadolibre_sync_'.$field]) ? 'checked' : ''; 
					}
					?> 
					<label class="wpmlsync__label_control"><input type='checkbox' name='wp_mercadolibre_sync_settings[wp_mercadolibre_sync_<?php echo $field; ?>]' <?php echo $checked; ?> class='wpmlsync__checkbox'><span class="">Enable Auto Token? </span></label>
					<p>Tokens exprie each 6 hours, enable this option to refresh it automaticly when needed.</p>
					<?php  
			},
			$this->plugin_name.'-advanced',
				'wp_mercadolibre_sync_settings_section_advanced' 
		); 
		add_settings_field( 
			'wp_mercadolibre_sync_debug',
			'debug',
			function() {  
					$field = 'debug';
					$options = get_option( 'wp_mercadolibre_sync_settings' ); 
					if(empty($options)){
						$checked = 'checked';
					}else{
						$checked = isset($options['wp_mercadolibre_sync_'.$field]) ? 'checked' : ''; 
					}
					?> 
					<label class="wpmlsync__label_control"><input type='checkbox' name='wp_mercadolibre_sync_settings[wp_mercadolibre_sync_<?php echo $field; ?>]' <?php echo $checked; ?> class='wpmlsync__checkbox'><span class="">Enable Debug? </span></label>
					<p>A file will be created/updated at: wp-content/wp-mercadolibre-sync-debug.txt with debug information.</p>
					<?php  
			},
			$this->plugin_name.'-advanced',
				'wp_mercadolibre_sync_settings_section_advanced' 
		); 
		

	}
	// admin_register_settings() END 

	public function _validate($input){
		$validated = $input;
    return $validated;
	} 

	public function wp_mercadolibre_sync_debug_page(  ) {  
			?>
		<div class="wrap wpmlsync__wrap">
			<div id="poststuff">
			  <div id="post-body" class="metabox-holder columns-1">
					<?php
					require_once plugin_dir_path( __FILE__ ) . 'partials/wp-mercadolibre-sync-admin-debug.php'; 
					?>
					</div>
		  </div>
		</div>
		<?php
	}

	public function wp_mercadolibre_sync_options_page(  ) { 
		?>
		<div class="wrap wpmlsync__wrap">
			<div id="poststuff">
			  <div id="post-body" class="metabox-holder columns-1">
					<?php
					require_once plugin_dir_path( __FILE__ ) . 'partials/wp-mercadolibre-sync-admin-display.php'; 
					?>
					</div>
		  </div>
		</div>
		<?php
	}
	
	public function wp_mercadolibre_sync_settings_section_advanced_callback(  ) { 
		// echo __( 'This section description', 'wp-mercadolibre-sync' ); 
		 
 
	}


	public function wp_mercadolibre_sync_settings_section_private_callback(  ) { 
		// echo __( 'This section description', 'wp-mercadolibre-sync' ); 
			echo "<p>Estos datos son privados, pueden usarse para consultar la API por consola o resultado de curl.</p>";
 
	}

	public function wp_mercadolibre_sync_settings_section_callback(  ) { 
		// echo __( 'This section description', 'wp-mercadolibre-sync' ); 
			echo "<p>Los datos de appId, secretKey y redirectURI, deben ser los mismos previamente seteados en tu <a href='https://developers.mercadolibre.com/apps' target='_blank' title='developers.mercadolibre.com/apps'>Aplicación</a>. </p>";
 
	}

	public function _get_setting_fields(){ 
		$fields = array('appId','secretKey','redirectURI','siteId'); 
		return $fields;  
	}

	public function _get_private_fields(){ 
		$fields = array('access_token','expires_in','refresh_token'); 
		return $fields;  
	}

}