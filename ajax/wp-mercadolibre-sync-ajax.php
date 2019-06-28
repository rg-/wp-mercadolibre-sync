<?php


class Wp_Mercadolibre_Sync_Ajax { 

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;  
  
	} 

	public function Wp_Mercadolibre_Sync_Ajax_Script(){

		global $wpdb; // access to WP database
		wp_enqueue_script( 'wpmlsync', plugins_url( '/scripts.js', __FILE__ ), array('jquery'), '1.0', true ); 
		// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
		wp_localize_script( 'wpmlsync', 'ajax_object',
            array(
            		'ajax_url' => admin_url( 'admin-ajax.php' ),
            		'we_value' => 1234
            	)
          );
		
	}

	public function Wp_Mercadolibre_Sync_Ajax_Get(){ 
		global $wpdb; // access to WP database 
		$whatever = intval( $_POST['whatever'] );
		echo time(); 
		// allways die at last !
		wp_die();
	}

	public  function Wp_Mercadolibre_Sync_Ajax_Shortcode( $atts, $content = "" ) {   
		$out = ''; 
		ob_start();
				echo "<div id='mlsync_ajax_result'></div>";
				// include plugin_dir_path( dirname( __FILE__ ) ) . 'shortcodes/partials/ml_get_user.php';
		$out = ob_get_contents();
		ob_end_clean();
		return $out; 
	} 

}

add_shortcode( 'ml_get_ajax', array( 'Wp_Mercadolibre_Sync_Ajax', 'Wp_Mercadolibre_Sync_Ajax_Shortcode' ) );
add_action( 'wp_ajax_nopriv_'.'wpmlsync', array('Wp_Mercadolibre_Sync_Ajax', 'Wp_Mercadolibre_Sync_Ajax_Get') );
add_action( 'wp_ajax_'.'wpmlsync', array('Wp_Mercadolibre_Sync_Ajax', 'Wp_Mercadolibre_Sync_Ajax_Get') );
// ej:  .../admin-ajax.php?action=wpmlsync 

add_action( 'wp_enqueue_scripts', array('Wp_Mercadolibre_Sync_Ajax', 'Wp_Mercadolibre_Sync_Ajax_Script')  ); 