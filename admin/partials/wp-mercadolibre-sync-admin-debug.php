<?php
global $global_meli;
global $global_meli_code;
global $global_meli_tokens; 

$wp_mercadolibre_sync_settings = wp_mercadolibre_sync_settings();
extract($wp_mercadolibre_sync_settings); 

$meli = new Meli($appId, $secretKey);

?>

<h1 class="wp-heading-inline"><?php echo __( 'WP Mercadolibre Sync', 'wp-mercadolibre-sync' ); ?> > <?php echo __( 'Debug', 'wp-mercadolibre-sync' ); ?></h1>
<div class="clear"></div>
<br>

<form action='<?php $admin_url = admin_url('admin.php'); echo $admin_url; ?>' method='get'>

<div class="wpmlsync__postbox-container">
  
  <div class="wpmlsync__postbox-inner">

    <div class="wpmlsync__row">

      <div class="wpmlsync__col w-100">
        <div class="wpmlsync__card">

            <input type="hidden" readonly id="test_user" name="test_user" class="regular-text" value="1" >
            <input type="hidden" readonly id="page" name="page" class="regular-text" value="wp-mercadolibre-sync-debug" >

            <h3 class="wpmlsync__postbox-subtitle">Cerar usuario test:</h3>
            <p>Para crear un usuario test debes usar un token desde una cuenta real. Para desarrollo y testeo es recomendable crear dos cuentas test, una para usar como vendedor, y otra para usar como comprador y así poder hacer comentarios y compras.</p>

            <?php 
            if(isset($_GET['test_user'])){
              $body['site_id'] = 'MLU';
              $params['access_token'] = $_GET['access_token']; 
              $response = $meli->post('/users/test_user', $body, $params);
              echo "<p>Se ha generado el siguiete usuario test:</p>";
              echo '<pre>';
              print_r($response);
              echo '</pre>'; 
            } 
            ?>
            <table class="form-table">
              <tbody>
                <tr>
                  <th scope="row">Ingresar un token válido</th>
                  <td><input type="text" class="wpmlsync__control" id="access_token" name="access_token" value="APP_USR-XXXXXXXXXXXXXX-XXXXXX-XXXXXXXXXXXXXXXXXXXXXX-XXXXXX"></td>
                </tr>
              </tbody>
            </table>
            
          

        </div>
      </div>

      <div class="wpmlsync__col w-100 wpmlsync__col_buttons">
        <input type="submit" class="button button-primary wpmlsync__button" value="Crear Usuario Test">
      </div>

    </div>

  </div><!-- .wpmlsync__postbox-inner -->
</div><!-- .wpmlsync__postbox-container -->

</form>

<div class="clear"></div>
<!-- #post-body-content end -->

<form action='<?php $admin_url = admin_url('admin.php'); echo $admin_url; ?>' method='get'>
<div class="wpmlsync__postbox-container">
  
  <div class="wpmlsync__postbox-inner">

    <div class="wpmlsync__row">

      <div class="wpmlsync__col w-100">
        <div class="wpmlsync__card">

          <h3 class="wpmlsync__postbox-subtitle">Quick publish:</h3>

          <?php
              
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

          <input type="hidden" readonly id="page" name="page" class="regular-text" value="wp-mercadolibre-sync-debug" >
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

      </div>

      <div class="wpmlsync__col w-100 wpmlsync__col_buttons">
        <input type="submit" class="button button-primary wpmlsync__button" value="Publicar Test">
      </div>

    </div>

  </div>

</div>
</form>