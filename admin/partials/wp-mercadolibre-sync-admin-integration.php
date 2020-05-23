<div class="wpmlsync__postbox-container">
  <div class="wpmlsync__postbox-inner">
    <div class="wpmlsync__card">
      
      <h2 class="wpmlsync__postbox-title"><?php echo __( 'Integration', 'wp-mercadolibre-sync' ); ?></h2>
      <?php 
      $wp_mercadolibre_sync_settings = wp_mercadolibre_sync_settings();
      extract($wp_mercadolibre_sync_settings); 
      $meli = new Meli($appId, $secretKey);
			if(empty($meli)) return false;
			$meli_result = $meli->get(
				'/users/'.$seller_id.'/items/search',
				array(
					'status' => 'active',
					'query' => !empty($shortcode_atts['query']) ? $shortcode_atts['query'] : '',
					'offset' => !empty($shortcode_atts['offset']) ? $shortcode_atts['offset'] : 0,
					'limit' => !empty($shortcode_atts['limit']) ? $shortcode_atts['limit'] : 50,
					'order' => 'start_time_desc',
			  	'access_token' => $access_token
			  )
		  );
			if(empty($meli_result)) return false; 
			$results = $meli_result['body']->results; // array of IDS
			if( !empty($results) ){
				$ids = join(',', $results);  
				$url = '/items';   
				$items = $meli->get($url, array(
					'ids' => $ids,
					'access_token' => $access_token
				)); 
				$array_test = array();
				foreach($items['body'] as $item){  
					foreach($results as $i => $r){
						if($item->body->id == $r){
							$array_test[$i] = $item->body;
						}
					} 
				}
				if(!empty($array_test)){ 
					?>
					<table id="wpmlsyncItemsTable" class="wp-list-table widefat fixed striped posts">
					<?php
					ksort($array_test,SORT_NUMERIC); 
					foreach($array_test as $body){ 
					  $id = $body->id;
						$title = $body->title;
						?>
						<tr>
							<td><?php echo $id; ?></td>
							<td><?php echo $title; ?></td>
							<td><button data-item-id="<?php echo $id; ?>" type="button" class="button button-primary sync__button">Sincronize</button></td>
						</tr>
						<?php
					}
					?>
					</table>
					<?php
				}
			}
      ?>
    </div>
  </div>
</div>