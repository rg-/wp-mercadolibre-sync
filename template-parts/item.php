<?php
// print_r($item);
// passed $item [obj]

// $body = $item->body;
$body = $item;
$id = $body->id;
$title = $body->title;
$permalink = $body->permalink;  
$thumbnail = $body->thumbnail; 
$descriptions = $body->descriptions; 

$category_id = $body->descriptions; // de aca saco la info de la categoria con otro get()
$attributes = $body->descriptions; // array

$date_created = $body->date_created; 
$last_updated = $body->last_updated; 
 
?>
<div class="col-12 gmy-1">
	<div class="card">
		<div class="card-body">
      <div class="row">
      	<div class="col-md-2 text-center d-flex align-items-center justify-content-center">
      		<img src="<?php echo $thumbnail; ?>" class=" " alt="...">
      	</div>
      	<div class="col-md-4 d-flex align-items-start justify-content-start">
      		<div>
        		<div class="card-text"><small class="text-muted">#<?php echo $id;?></small></div>
        		<h5 class="card-title"><?php echo $title;?></h5>
            
            <p>
              <small><?php echo _e('Created','wp-mercadolibre-sync'); ?> <?php echo $date_created; ?></small><br>
              <small><?php echo _e('Updated','wp-mercadolibre-sync'); ?> <?php echo $last_updated; ?></small>
            </p>
		       </div>
      	</div>
      	<div class="col-md-3 d-flex align-items-center justify-content-center">
      		<div>
        		<p class="gmb-0"><?php echo _e('Price','wp-mercadolibre-sync'); ?> <?php echo $body->currency_id; ?> <?php echo $body->price; ?></p>
        		<p class="gmb-0"><?php echo _e('Quantity','wp-mercadolibre-sync'); ?> <?php echo $body->available_quantity; ?></p>
        	</div>
      	</div>
      	<div class="col-md-3 d-flex align-items-center justify-content-center"> 
      		<a target="_blank" href="<?php echo $permalink;?>" class="btn btn-primary"><?php echo _e('See in Mercado Libre','wp-mercadolibre-sync'); ?></a>
          <?php

          $single_item_page = 190;
          $single_permalink = get_permalink(190);

          ?>
          <a target="_blank" href="<?php echo $single_permalink;?>?itemId=<?php echo $id; ?>" class="btn btn-secondary"><?php echo _e('See in Single Template','wp-mercadolibre-sync'); ?></a>
      	</div>
      </div>
    </div>
	</div>
</div>