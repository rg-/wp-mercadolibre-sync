<?php 
$wp_mercadolibre_sync_settings = wp_mercadolibre_sync_settings();
// extract varis like $appId, $secretKey...
extract($wp_mercadolibre_sync_settings);  

// create Meli
$meli = new Meli($appId, $secretKey);
if(empty($meli)) return false;

// Set params to use 
$_item = $meli->get(
	'/items/'.$shortcode_atts['id'].'',
	array( 
  	'access_token' => $access_token
  ));
if( empty($_item['body']->id) ) {
  echo "not_found";
  return false;
}
echo "<pre>";
//print_r($_item);
echo "</pre>";
$body = $_item['body'];
$id = $body->id;
$title = $body->title;
$permalink = $body->permalink;  
$thumbnail = $body->thumbnail; 
$pictures = $body->pictures; // array
$video_id = $body->video_id; // youtube
$descriptions = $body->descriptions; 

$category_id = $body->category_id;  
?>

<div class="container">
	<div class="card border-0">
		<div class="card-body">
      <div class="row">
      	<div class="col-md-4 text-center d-flex align-items-center justify-content-center">
      		<img src="<?php echo $pictures[0]->url; ?>" class=" " alt="...">
      	</div>
      	<div class="col-md-8">
      		<div>
        		<div class="card-text"><small class="text-muted">#<?php echo $id;?></small></div>
        		<h1 class="card-title"><?php echo $title;?></h1> 
            <p><?php echo _e('Category','wp-mercadolibre-sync'); ?><?php 
            echo do_shortcode('[WPMLSYNC_get_categories category_id='.$category_id.' /]'); 
            ?></p> 
		       </div>
		       <div>
        		<p class="gmb-0"><?php echo _e('Price','wp-mercadolibre-sync'); ?> <?php echo $body->currency_id; ?> <?php echo $body->price; ?></p>
        		<p class="gmb-0"><?php echo _e('Quantity','wp-mercadolibre-sync'); ?> <?php echo $body->available_quantity; ?></p>
        	 </div>
        	 <div class="gpy-2">
        	 	<a target="_blank" href="<?php echo $permalink;?>" class="btn btn-primary"><?php echo _e('See in Mercado Libre','wp-mercadolibre-sync'); ?></a>
        	 </div>

      	</div>
      </div>
    </div>
	</div>
</div>