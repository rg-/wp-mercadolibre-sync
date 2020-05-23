<?php
$wp_mercadolibre_sync_settings = wp_mercadolibre_sync_settings();
// extract varis like $appId, $secretKey...
extract($wp_mercadolibre_sync_settings);  
$meli = new Meli($appId, $secretKey);
if(empty($meli)) return false;
// /categories/{Category_id}
// /categories/{Category_id}/attributes
// Set params to use 
$categories = $meli->get(
  '/categories/'.$shortcode_atts['category_id'].'',
  array( 
    'access_token' => $access_token
  ));
if( empty($categories['body']->id) ) {
  echo "not_found"; 
}else{
  ?>
  <ul>
    <?php 
    // ->children_categories 
    $category_id = $categories['body']->id; 

    if(!empty($categories['body']->path_from_root)){
      $path_from_root = $categories['body']->path_from_root;
      foreach ($path_from_root as $path) {
        // wpmlsync_print_pre($path); 
        if($category_id!=$path->id){
        ?>
        <li><?php echo $path->id; ?> <?php echo $path->name; ?></li>
        <?php
        }
      }
    }?>
    <ul><li><?php echo $category_id; ?> <?php echo $categories['body']->name; ?></li></ul>
  </ul>
  <?php
}


?>