<?php

// Wp_Mercadolibre_Sync_Module NOT USED

class Wp_Mercadolibre_Sync_Module { 

	public function __construct() {

		$this->name = 'MODULE_NAME';
		$this->label = __('MODULE_NAME', 'TEXTDOMAIN'); 
		
	}

	public function load_module() { 
		 add_shortcode( 'WPMLSYNC_test', array($this, 'WPMLSYNC_test_FN') ); 
	}

	public function WPMLSYNC_test_FN(){
		return $this->name;
	}

}
/**/
function run_wp_mercadolibre_sync_module() { 
	$module = new Wp_Mercadolibre_Sync_Module();  
	$module->load_module(); 
}
run_wp_mercadolibre_sync_module(); 
