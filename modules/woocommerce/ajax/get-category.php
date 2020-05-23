<?php
$get_category = $_GET['get_category'];

$wp_mercadolibre_sync_settings = wp_mercadolibre_sync_settings();
extract($wp_mercadolibre_sync_settings); 
$meli = new Meli($appId, $secretKey);
if(empty($meli)) return false; 

$api_params = array( 
  'access_token' => $access_token
);
$meli_result = $meli->get(
  '/categories/'.$get_category,
  $api_params
);
if(empty($meli_result)) return false; 
// wpmlsync_print_pre($meli_result['body']);  

// id, name, picture, permalink, total_items_in_this_category, path_from_root
if(!empty($meli_result['body']->children_categories)){
$children_categories = $meli_result['body']->children_categories;
?><ul class="ml_cat_list"><?php
  foreach ($children_categories as $cat) {
  	// id, name, total_items_in_this_category
  	?>
  	<li><a href="#" data-get-category="<?php echo $cat->id; ?>"><?php echo $cat->id; ?> <?php echo $cat->name; ?></a></li>
  	<?php
  }  
  ?></ul>
  <?php } else { ?>
  <ul class="ml_cat_list"><li>No childredn categories</li></ul>
  <?php } ?>