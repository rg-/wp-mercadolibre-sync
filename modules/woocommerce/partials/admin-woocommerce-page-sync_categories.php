<div class="wpmlsync__row">
  <div class="wpmlsync__col w-100">
    <div class="wpmlsync__card">
    
      <h2 class="wpmlsync__postbox-title"><?php echo __( 'Mercado Libre Categories', 'wp-mercadolibre-sync' ); ?></h2>

      <?php
      // https://developers-forum.mercadolibre.com/topic/169-listado-dump-de-categor%C3%ADas/#comment-606
      $wp_mercadolibre_sync_settings = wp_mercadolibre_sync_settings();
      extract($wp_mercadolibre_sync_settings); 
      $meli = new Meli($appId, $secretKey);
      if(empty($meli)) return false; 

      $api_params = array( 
        'access_token' => $access_token
      );
      $meli_result = $meli->get(
        '/sites/'.$siteId.'/categories',
        $api_params
      );
      if(empty($meli_result)) return false; 

      $categories = $meli_result['body']; 

      //wpmlsync_print_pre($categories); 
      
      ?><ul class="ml_cat_list"><?php
      foreach ($categories as $cat) {
      	?>
      	<li><a href="#" data-get-category="<?php echo $cat->id; ?>"><?php echo $cat->id; ?> <?php echo $cat->name; ?></a></li>
      	<?php
      }  
      ?></ul><?php ?>
    </div>
  </div> 
</div>