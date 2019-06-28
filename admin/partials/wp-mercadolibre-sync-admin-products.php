<div class="wrap">

<h1><?php echo __( 'WP Mercadolibre Sync', 'wp-mercadolibre-sync' ); ?> > Products</h1>

<?php 

// Test para publicar un item a ver que pasa....  

  $wp_mercadolibre_sync_settings = wp_mercadolibre_sync_settings();
  extract($wp_mercadolibre_sync_settings); 
	$meli = new Meli($appId, $secretKey);

	
  ?>

<form action='<?php $admin_url = admin_url('admin.php'); echo $admin_url; ?>' method='get'>
 
    <input type="hidden" readonly id="test_user" name="test_user" class="regular-text" value="1" >
    <input type="hidden" readonly id="page" name="page" class="regular-text" value="wp-mercadolibre-sync-products" >
    <?php 
    if(isset($_GET['test_user'])){
      $body['site_id'] = 'MLU';
      $params['access_token'] = wp_mercadolibre_sync_settings('access_token');
      $params = array(
        'access_token'=>$access_token
      );
      $response = $meli->post('/users/test_user', $body, $params);
      echo "<p>Se ha generado el siguiete usuario test:</p>";
      echo '<pre>';
      print_r($response);
      echo '</pre>'; 
    } 
    ?>

    <h2 class="title">Usuario Test</h2>
    <input type="submit" class="button button-secondary" value="Crear Usuario Test">
    <p><br><small>Un usuario nuevo no necesariamente reemplazará los seteos de la API. <br>Eso necesariamente se deberá configurar tanto en API Settings, como en la cuenta de Mercado Libre previamente (o actualmente) habilitada. </small></p>
    <p>Por ej. se pueden crear mas usuarios test para usar como "comprador", y a la vez usar otro usuario test como "vendedor". Este último será el que usemos para crear la Aplicación y cuyos datos usaremos para setear en API Settings del plugin.</p>

  </form>

<form action='<?php $admin_url = admin_url('admin.php'); echo $admin_url; ?>' method='get'>
<?php

// Test para publicar un item a ver que pasa....  
  ?> 
  
  <div id="wp_mercadolibre_sync_get_item_test">

    <h2 class="title">Quick publish</h2>

    <input type="hidden" readonly id="page" name="page" class="regular-text" value="wp-mercadolibre-sync-products" >
    <input type="hidden" readonly id="post_test" name="post_test" class="regular-text" value="1" >
    <table class="form-table">
      <tbody>
        <?php
        $fields = wp_mercadolibre_sync_get_item_test_fields();
        foreach ($fields as $field) {
          $value = '';
          if($field=='title'){
            $value = 'Item De Prueba - Por Favor, No Ofertar --kc:off';
          }
          if($field=='price'){
            $value = 10;
          }
          ?>
          <tr>
          <th scope="row"><?php echo $field; ?></th>
            <td>
              <input type="text" id="wp_ml_sync_test__<?php echo $field; ?>" name="wp_ml_sync_test__<?php echo $field; ?>" class="regular-text" value="<?php echo $value; ?>" >
            </td>
          </tr>
          <?php
        }
        ?>
        
      </tbody>
    </table> 
  </div>
  <?php
  echo '<input type="submit" class="button button-secondary" value="Publicar">'; 
  if(isset($_GET['post_test'])){
    $params = array(
      'access_token' => wp_mercadolibre_sync_settings('access_token')
    );

    $wp_mercadolibre_sync_get_item_test = wp_mercadolibre_sync_get_item_test($_GET);
    echo '<pre>';
     // print_r($wp_mercadolibre_sync_get_item_test);
    echo '</pre>'; 

    $response = $meli->post('/items', $wp_mercadolibre_sync_get_item_test, $params); 
    echo "<h4>Success! Your test item was listed!</h4>";
    echo "<p>Go to the permalink to see how it's looking in our site.</p>";
    echo '<a target="_blank" class="" href="'.$response["body"]->permalink.'">'.$response["body"]->permalink.'</a><br />';
  }

?>
</form>
</div>