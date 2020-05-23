<?php

$limit_items = 3; 
$offset_items = 0; 

$wp_mercadolibre_sync_settings = wp_mercadolibre_sync_settings();
extract($wp_mercadolibre_sync_settings); 
$meli = new Meli($appId, $secretKey);
if(empty($meli)) return false;
$meli_result = $meli->get(
	'/users/'.$seller_id.'/items/search',
	array(
		'status' => 'active',
		'query' => !empty($shortcode_atts['query']) ? $shortcode_atts['query'] : '',
		'offset' => !empty($_GET['offset']) ? $_GET['offset'] : $offset_items,
		'limit' => !empty($shortcode_atts['limit']) ? $shortcode_atts['limit'] : $limit_items,
		'order' => 'start_time_desc',
  	'access_token' => $access_token
  )
);
if(empty($meli_result)) return false; 
$results = $meli_result['body']->results; // array of IDS
if( !empty($results) ){
 
	$total_items = $meli_result['body']->paging->total;
	
	?>

	<p>Showing <?php echo $limit_items; ?> of <?php echo $total_items; ?> items.</p>

	<?php 

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
		<table id="wpmlsyncItemsTable" class="wpmlsync_list_table wp-list-table widefat fixed striped posts">

		<thead>
			<tr>
				<th class="column-thumb"><span class="wc-image tips">Imagen</span></th>
				<th>ID (ML)</th>
				<th width="20%">Title</th>
				<!--<th>Price</th>-->
				<!--<th>Category</th>-->
				<!--<th>Quantity</th>-->
				<!--<th>Condition</th>-->
				<th>ML Dates</th>
				<th>WOO Dates</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
		<?php
		ksort($array_test,SORT_NUMERIC); 
		foreach($array_test as $body){ 
		  $id = $body->id;
			$title = $body->title;
			$permalink = $body->permalink;
			?>
			<tr data-item="<?php echo $id; ?>">

				<td class="column-thumb">
					<?php
					$thumbnail = $body->thumbnail;
					echo "<img height='auto' width='40' src='".$thumbnail."'/>";
					?>
				</td>
				<td><?php echo $id; ?><br><small><a href="<?php echo $permalink; ?>" target="_blank">Ver en ML</a></small></td>
				
				<td><?php echo $title; ?></td>
				<!--<td><?php echo $body->price; ?> <?php echo $body->currency_id; ?></td>-->
				<!--<td><?php echo $body->category_id; ?></td>-->
				<!--<td><?php echo $body->available_quantity; ?></td>-->
				<!--<td><?php echo $body->condition; ?></td>-->
				
				<td>
					<small>date_created</small><br>
					<small><?php 
						$d=strtotime($body->date_created);
						echo date("Y-m-d h:i:sa", $d);
						?>
					</small>
				<br>
					<small>last_updated</small><br>
					<small>
						<?php 
						$d=strtotime($body->last_updated);
						echo date("Y-m-d h:i:sa", $d);
						?>
					</small>
				</td>

				<td>
					
					<?php
					$date_created = '';
					$last_updated = '';
					if(wc_get_product_id_by_sku($id)){ 
							$woo_product_id = wc_get_product_id_by_sku($id);  
							$json = get_post_meta( $woo_product_id, '_wpmlsync_json', true );
							if(!empty($json)){ 
								$json = json_decode($json, true);
								$date_created=strtotime($json['date_created']);
								$last_updated=strtotime($json['last_updated']); 
							}
					} 
					?>

					<small>woo_date_created</small><br>
					<small class="woo_date_created">
						<?php echo !empty($date_created) ? date("Y-m-d h:i:sa", $date_created) : ''; ?>
					</small><br>
					<small>woo_last_updated</small><br>
					<small class="woo_last_updated">
						<?php echo !empty($last_updated) ? date("Y-m-d h:i:sa", $last_updated) : 'Not syncronized yet'; ?>
					</small>
					<?php
					// This should be standard function somewhere
					if( ( !empty($last_updated) ) && ( $last_updated != strtotime($body->last_updated) )  ){
						echo "<br><b style='color:red;'>ML Item has changes</b>";
					}
					?>

				</td>

				<td>
					<?php
					$sync_class = '';
					$update_class = 'd-none';
					$re_sync_class = 'd-none';
					if( wc_get_product_id_by_sku($id) ){
						
						$sync_class = 'd-none';
						$re_sync_class = '';
					 
						if( ( !empty($last_updated) ) && ( strtotime($last_updated) != strtotime($body->last_updated) )  ){ 
							$update_class = '';
							$re_sync_class = 'd-none';
						} 
					}
					?>

					<button data-item-id="<?php echo $id; ?>" type="button" class="wpmlsync__button_sm button button-primary sync__button <?php echo $sync_class; ?>"><i class="spinner"></i>Syncronize</button>

					<button data-item-id="<?php echo $id; ?>" type="button" class="wpmlsync__button_sm button button-success sync_up__button <?php echo $update_class; ?>"><i class="spinner"></i>Update</button>
					
					<button disabled data-item-id="<?php echo $id; ?>" type="button" class="wpmlsync__button_sm button button-secondary sync_re__button <?php echo $re_sync_class; ?>"><i class="spinner"></i>Syncronized</button>

				</td>
			</tr>
			<?php
		}
		?>
		</tbody>
		</table>
		<?php
	}
}
?>