<?php

// Settings & Sections

register_setting(
	'wp_mercadolibre_sync_api',
	'wp_mercadolibre_sync_settings',
	array($this, '_validate' )

); 

add_settings_section(
	'wp_mercadolibre_sync_settings_section', 
	'',  // Could be _x( '', 'wp-mercadolibre-sync' )
	array( $this, 'wp_mercadolibre_sync_settings_section_callback' ) , 
	$this->plugin_name
); 
add_settings_section(
	'wp_mercadolibre_sync_settings_section_advanced', 
	'', 
	array( $this, 'wp_mercadolibre_sync_settings_section_advanced_callback' ) , 
	$this->plugin_name.'-advanced'
); 
add_settings_section(
	'wp_mercadolibre_sync_settings_section_private', 
	'',
	array( $this, 'wp_mercadolibre_sync_settings_section_private_callback' ) , 
	$this->plugin_name.'-private'
); 

register_setting(
	'wp_mercadolibre_sync_api',
	'wp_mercadolibre_sync_curl_settings',
	array($this, '_validate' )
); 


// Settings & Sections END

// Set user fields

$fields = $this->_get_setting_fields();
foreach($fields as $field){
	add_settings_field( 
		'wp_mercadolibre_sync_'.$field, 
		$field, 
		function() use ( $field ) {
			$options = get_option( 'wp_mercadolibre_sync_settings' );
			$value = isset($options['wp_mercadolibre_sync_'.$field]) ? $options['wp_mercadolibre_sync_'.$field] : ''; 

			if($field=='siteId'){ 
				$meli = new Meli('','');
				$sites = $meli->get('/sites');
				if( isset($sites['httpCode']) == 200 ) {
					?>
					<select class='wpmlsync__control' name='wp_mercadolibre_sync_settings[wp_mercadolibre_sync_<?php echo $field; ?>]'>
						<option value=0><?php _e( 'Select Country', 'wp-mercadolibre-sync' ); ?></option>
						<?php
						foreach ($sites['body'] as $site) { 
							if($value==$site->id){
								$selected = "selected";
							}else{
								$selected = "";
							}
							?><option value='<?php echo $site->id; ?>' <?php echo $selected; ?>><?php echo $site->name; ?></option><?php
						}
						?>
					</select>
					<?php
				} 
			}else{
				?>
				<input required type='text' name='wp_mercadolibre_sync_settings[wp_mercadolibre_sync_<?php echo $field; ?>]' value='<?php echo $value; ?>' class='wpmlsync__control'>
				<?php
			}
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
			<label class="wpmlsync__label_control"><input type='checkbox' name='wp_mercadolibre_sync_settings[wp_mercadolibre_sync_<?php echo $field; ?>]' <?php echo $checked; ?> class='wpmlsync__checkbox'><span class=""><?php echo _e('Enable Auto Token?','wp-mercadolibre-sync'); ?> </span></label>
			<p><?php echo _e('Tokens exprie each 6 hours, enable this option to refresh it automaticly when needed.','wp-mercadolibre-sync'); ?></p>
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
				$checked = '';
			}else{
				$checked = isset($options['wp_mercadolibre_sync_'.$field]) ? 'checked' : ''; 
			}
			?> 
			<label class="wpmlsync__label_control"><input type='checkbox' name='wp_mercadolibre_sync_settings[wp_mercadolibre_sync_<?php echo $field; ?>]' <?php echo $checked; ?> class='wpmlsync__checkbox'><span class=""><?php echo _e('Enable Debug?','wp-mercadolibre-sync'); ?> </span></label>
			<p><?php echo _e('A file will be created/updated at: wp-content/wp-mercadolibre-sync-debug.txt with debug information.','wp-mercadolibre-sync'); ?></p>
			<?php  
	},
	$this->plugin_name.'-advanced',
		'wp_mercadolibre_sync_settings_section_advanced' 
); 

add_settings_field( 
	'curl_ssl',
	'SSL_VERIFYPEER',
	function() { 
		  $options = get_option( 'wp_mercadolibre_sync_curl_settings', true ); 
			$checked = isset($options['curl_ssl']) ? 'checked' : ''; 
			?>
			<label class="wpmlsync__label_control"><input type='checkbox' name='wp_mercadolibre_sync_curl_settings[curl_ssl]' <?php echo $checked; ?> class='wpmlsync__checkbox'><span class=""><?php echo _e('Use SSL_VERIFYPEER?','wp-mercadolibre-sync'); ?> </span></label>
			<p><?php echo _e('If using localhost or no SSL actived, you can disable this for testings.','wp-mercadolibre-sync'); ?></p>
			<?php  
	},
	$this->plugin_name.'-advanced',
	'wp_mercadolibre_sync_settings_section_advanced' 
); 