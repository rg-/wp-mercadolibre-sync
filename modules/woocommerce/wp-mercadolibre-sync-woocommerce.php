<?php

/*
 *
 * Woocommerce module addon
 *
*/ 

class Wp_Mercadolibre_Sync_Woocommerce { 

	public function __construct() {

		$this->name = 'wp-mercadolibre-sync-woocommerce';
		$this->label = __('Mercado Libre Sync Woocommerce module', 'TEXTDOMAIN'); 
		
		$parent = new Wp_Mercadolibre_Sync();
		$this->parent = $parent;
		$this->plugin_name = $parent->get_plugin_name();

		// This is just for testings
		// add_action( 'admin_notices', array($this, 'admin_notice_installed'));  

		// Adding the admin sub menu for module
		add_filter('wpmlsync/admin_menu', array($this, 'admin_menu'), 10, 1);
 
		

		// the scripts
		add_action( 'admin_enqueue_scripts', array($this, 'admin_enqueue_scripts') ); 
		add_action( 'wp_enqueue_scripts', array($this, 'wp_enqueue_scripts') ); 

		// ajax features
		add_action( 'wp_ajax_nopriv_'.'wpmlsync_woo', array($this, 'ajax') );
		add_action( 'wp_ajax_'.'wpmlsync_woo', array($this, 'ajax') );

		add_action( 'init', array($this, 'load_module') );

	}

	public function load_module() { 

		 add_filter('wpmlsync/item-single/content/after', array($this, 'WPMLSYNC_Woocommerce_template_item_single_after'), 10, 2);
		
		 add_filter('wpmlsync/create-woo-product/post_params', array($this, 'wpmlsync_woo_create_product_params'), 10, 3);

		 add_shortcode( 'WPMLSYNC_Woocommerce', array($this, 'WPMLSYNC_Woocommerce_FN') ); 
	
	}

	public function ajax(){
		if( isset($_GET['create_product']) ){
			include dirname( __FILE__ ). '/ajax/create-product.php';
		}
		if( isset($_GET['get_product']) ){
			include dirname( __FILE__ ). '/ajax/get-product.php';
		}
		if( isset($_GET['update_product']) ){
			include dirname( __FILE__ ). '/ajax/update-product.php';
		}

		if( isset($_GET['get_items_table']) ){
			include dirname( __FILE__ ). '/ajax/get-items-table.php';
		} 

		if( isset($_GET['get_category']) ){
			include dirname( __FILE__ ). '/ajax/get-category.php';
		} 
		if( isset($_GET['create_category']) ){
			include dirname( __FILE__ ). '/ajax/create-category.php';
		}
		// allways die at last !
		wp_die();
	}

	public function WPMLSYNC_Woocommerce_FN( $shortcode_atts, $content = "" ) {  
		$shortcode_atts = wp_parse_args($shortcode_atts, array( ));
		$out = '';
		ob_start();
			$from_shortcode = true;
			if( isset($shortcode_atts['action']) == 'get_items_table' ){
				include plugin_dir_path( __FILE__ ) . 'ajax/get-items-table.php';
			}
			$out = ob_get_contents();
		ob_end_clean();
		return $out; 
	}

	public function wp_enqueue_scripts(){
		global $wpdb; // access to WP database
		wp_enqueue_script( 'wpmlsync_woocommerce_public', plugins_url( '/scripts-public.js', __FILE__ ), array('jquery'), '1.0', true ); 
		// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
		wp_localize_script( 'wpmlsync_woocommerce_public', 'ajax_object',
            array(
            		'ajax_url' => admin_url( 'admin-ajax.php' ),
            		'we_value' => 1234
            	)
          );
	}
	public function admin_enqueue_scripts(){
		global $wpdb; // access to WP database
		wp_enqueue_script( 'wpmlsync_woocommerce', plugins_url( '/scripts.js', __FILE__ ), array('jquery'), '1.0', true ); 
		// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
		wp_localize_script( 'wpmlsync_woocommerce', 'ajax_object',
            array(
            		'ajax_url' => admin_url( 'admin-ajax.php' ),
            		'we_value' => 1234
            	)
          );
	}

	public function admin_menu($pages){
		 
		$pages[] = add_submenu_page(
			$this->plugin_name,
			__( 'Woocommerce', 'wp-mercadolibre-sync' ),
			__( 'Woocommerce', 'wp-mercadolibre-sync' ),
			'manage_options',
			$this->plugin_name.'-woocommerce',
			array( $this, 'wp_mercadolibre_sync_woocommerce_page' )
		);

		return $pages; 
	}

	public function wp_mercadolibre_sync_woocommerce_page(){
			
		$plugin_name = $this->plugin_name;
		$plugin_version = $this->version;

		$n = new Wp_Mercadolibre_Sync_Admin($plugin_name, $plugin_version);
		$n->wp_mercadolibre_sync_options_page_start_ev(array(
			'headline'=> __( 'WP Mercadolibre Sync', 'wp-mercadolibre-sync' ).' > '.__( 'Woocommerce', 'wp-mercadolibre-sync' )
		));
		require_once plugin_dir_path( __FILE__ ) . 'partials/admin-woocommerce-page.php';
		$n->wp_mercadolibre_sync_options_page_end_ev();

		//
	}

	public function admin_notice_installed(){
		// print_r($this->parent);
		?>
		<div class="notice notice-success is-dismissible">
			<p>Woocommerce is actived.</p>
		</div>
		<?php
	} 

	public function wpmlsync_woo_create_product_params($params, $item ,$meli){
		/*
	
	Create a filter to manage the params ($POST_params behind) to use, that is
	make the relation between how Woo products use them and how ML uses them too.
	They are similar in some cases, very diferent in others.

	For reference you can use the ajax call:
	/wp-admin/admin-ajax.php?action=wpmlsync_woo&get_product=MLU464505861

	This will output the data from ML item published, and also if exists,
	with the data saved into the Woocommerce Product syncronized post. Yeah!

  Ej:

		array(
	    'name'               => '',
	    'slug'               => '',
	    'date_created'       => null,
	    'date_modified'      => null,
	    'status'             => false,
	    'featured'           => false,
	    'catalog_visibility' => 'visible',
	    'description'        => '',
	    'short_description'  => '',
	    'sku'                => '',
	    'price'              => '',
	    'regular_price'      => '',
	    'sale_price'         => '',
	    'date_on_sale_from'  => null,
	    'date_on_sale_to'    => null,
	    'total_sales'        => '0',
	    'tax_status'         => 'taxable',
	    'tax_class'          => '',
	    'manage_stock'       => false,
	    'stock_quantity'     => null,
	    'stock_status'       => 'instock',
	    'backorders'         => 'no',
	    'low_stock_amount'   => '',
	    'sold_individually'  => false,
	    'weight'             => '',
	    'length'             => '',
	    'width'              => '',
	    'height'             => '',
	    'upsell_ids'         => array(),
	    'cross_sell_ids'     => array(),
	    'parent_id'          => 0,
	    'reviews_allowed'    => true,
	    'purchase_note'      => '',
	    'attributes'         => array(),
	    'default_attributes' => array(),
	    'menu_order'         => 0,
	    'post_password'      => '',
	    'virtual'            => false,
	    'downloadable'       => false,
	    'category_ids'       => array(),
	    'tag_ids'            => array(),
	    'shipping_class_id'  => 0,
	    'downloads'          => array(),
	    'image_id'           => '',
	    'gallery_image_ids'  => array(),
	    'download_limit'     => -1,
	    'download_expiry'    => -1,
	    'rating_counts'      => array(),
	    'average_rating'     => 0,
	    'review_count'       => 0,
	) 
	*/

	$status = ($item->status == 'active') ? 'publish' : 'draft';
  
  $description = wp_mercadolibre_sync_get_item_description($item, $meli);
  
  /*
	Get the images, once $api->create_item is done, they will be uploaded too, response will end once all images have been uploaded. So, no need for media_uploader and those things. Anyway, if needed, ther´s a function for that too.
  */
  $enable_upload_images = false;
  $pictures = wp_mercadolibre_sync_get_item_pictures($item);
  $images = array();
  $image_id = ''; // There´s no need for this
  $gallery_image_ids = array();  // There´s no need for this
  if(!empty($pictures) && $enable_upload_images ){
  	if(!empty($pictures['images'])) $images = $pictures['images'];
	  if(!empty($pictures['image_id'])) $image_id = $pictures['image_id'];
	  if(!empty($pictures['gallery_image_ids'])) $gallery_image_ids = $pictures['gallery_image_ids'];
  }

  $params = array (
		'sku' => $item->id,
    'name' => $item->title,
    //'slug' => 'new-product', // will be auto-created from name
    'type' => 'simple',
    'status' => $status, // See rest of ML status keys
    'regular_price' => $item->price, // Whis is regular on ML?
    'sale_price' => $item->price, // Whis is sale on ML?
    'description' => $description,
  	// 'short_description' => '', // What to do here?
  	'images' => $images,
  	// 'image_id'           => $image_id,
		// 'gallery_image_ids'  => $gallery_image_ids,
		// 'category_ids'       => array(),
		// 'tag_ids'            => array(),
	);
		return $params; 
	}

	public function WPMLSYNC_Woocommerce_template_item_single_after($content, $body){
		$date_created = '';
		$last_updated = '';
		$json = '';
		$id = $body->id;
		if(wc_get_product_id_by_sku($id)){ 
				$woo_product_id = wc_get_product_id_by_sku($id);  
				$json = get_post_meta( $woo_product_id, '_wpmlsync_json', true ); 
		} 
		if(!empty($json)){ 
			$json = json_decode($json, true);
			$date_created=strtotime($json['date_created']);
			$last_updated=strtotime($json['last_updated']);  
		}
		
		$sync_class = '';
		$update_class = 'd-none';
		$re_sync_class = 'd-none';
		if( wc_get_product_id_by_sku($id) ){
			
			$sync_class = 'd-none';
			$re_sync_class = '';
		 
			if( ( !empty($last_updated) ) && ( $last_updated != strtotime($body->last_updated) )  ){ 
				$update_class = '';
				$re_sync_class = 'd-none';
			} 
		}

		if(current_user_can('manage_options')){
		?>
		<button data-item-id="<?php echo $id; ?>" type="button" class="btn btn-danger sync__button <?php echo $sync_class; ?>"><i class="spinner"></i>Syncronize</button>

		<button data-item-id="<?php echo $id; ?>" type="button" class="btn btn-success sync_up__button <?php echo $update_class; ?>"><i class="spinner"></i>Update</button>
		
		<button disabled data-item-id="<?php echo $id; ?>" type="button" class="btn btn-secondary sync_re__button <?php echo $re_sync_class; ?>"><i class="spinner"></i>Syncronized</button>
		<?php
		}
	} 

}
/**/
function run_wp_mercadolibre_sync_Woocommerce() { 
	$module = new Wp_Mercadolibre_Sync_Woocommerce();  
	// $module->load_module(); 
}
run_wp_mercadolibre_sync_Woocommerce(); 