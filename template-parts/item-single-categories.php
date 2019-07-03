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
    <li><?php echo $categories['body']->name; ?></li>
  </ul>
  <?php
}
//echo "<pre>";
//print_r($categories);
//echo "</pre>";

?>