<?php

$limit_items = 50; 
$offset_items = 0; // when using search_type = scan, there´s no need for offset

$wp_mercadolibre_sync_settings = wp_mercadolibre_sync_settings();
extract($wp_mercadolibre_sync_settings); 
$meli = new Meli($appId, $secretKey);
if(empty($meli)) return false;

$api_params = array(
		'status' => 'active',
		'search_type' => 'scan',
		'query' => !empty($_GET['query']) ? $_GET['query'] : '',
		'offset' => !empty($_GET['offset']) ? $_GET['offset'] : $offset_items,
		'limit' => !empty($_GET['limit']) ? $_GET['limit'] : $limit_items,
		'order' => 'start_time_desc',
  	'access_token' => $access_token
  );
if(isset($_GET['scroll_id'])){
	$api_params['scroll_id'] = $_GET['scroll_id'];
}

$meli_result = $meli->get(
	'/users/'.$seller_id.'/items/search',
	$api_params
);
if(empty($meli_result)) return false; 

$results = $meli_result['body']->results; // array of IDS

if(isset($meli_result['body']->scroll_id)){
	$scroll_id = $meli_result['body']->scroll_id;
}else{
	$scroll_id = '';
}

if( !empty($results) ){
	$total_items = $meli_result['body']->paging->total;

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

		if(!isset($from_shortcode)){
			?><table data-scroll-id="<?php echo $scroll_id; ?>"><?php
		}else{
			?><tbody class="wpmlsync__items-results"><?php
		}

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
					 
						if( ( !empty($last_updated) ) && ( $last_updated != strtotime($body->last_updated) )  ){ 
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
		if(!isset($from_shortcode)){
			?></table><?php
		}else{
			?></tbody>
			<tfoot>
				<tr>
					<td colspan="6" align="center">
						<button data-target="#get-items-table" data-scroll-id="<?php echo $scroll_id; ?>" type="button" class="wpmlsync__button button button-primary wpmlsync__button_load_more">Load more</button> <?php // print_r($meli_result['body']->paging); ?>
					</td>
				</tr>
			</tfoot>
			<?php
		}
	}
}
?>