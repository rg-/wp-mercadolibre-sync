<?php

class Wp_Mercadolibre_Sync_Shortcodes {

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

	public  function ml_get_user_func( $atts, $content = "" ) {   
		$out = ''; 
		ob_start();
				include plugin_dir_path( dirname( __FILE__ ) ) . 'shortcodes/partials/ml_get_user.php';
		$out = ob_get_contents();
		ob_end_clean();
		return $out; 
	}

	public  function ml_get_item_func( $atts, $content = "" ) {  
		$atts = wp_parse_args($atts, array(
			'query' => false, 
		));
		extract($atts);  
		$out = ''; 
		ob_start();
				include plugin_dir_path( dirname( __FILE__ ) ) . 'shortcodes/partials/ml_get_item.php';
		$out = ob_get_contents();
		ob_end_clean();
		return $out; 
	}

	public  function ml_get_items_func( $atts, $content = "" ) {   
		$out = '';  
		// Check if expired/actived token 
		ob_start();
				include plugin_dir_path( dirname( __FILE__ ) ) . 'shortcodes/partials/ml_get_items.php';
		$out = ob_get_contents();
		ob_end_clean();
		return $out; 
	}
 
 }

add_shortcode( 'ml_get_user', array( 'Wp_Mercadolibre_Sync_Shortcodes', 'ml_get_user_func' ) );
add_shortcode( 'ml_get_item', array( 'Wp_Mercadolibre_Sync_Shortcodes', 'ml_get_item_func' ) );
add_shortcode( 'ml_get_items', array( 'Wp_Mercadolibre_Sync_Shortcodes', 'ml_get_items_func' ) );