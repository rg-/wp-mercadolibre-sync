<?php
global $global_meli;
global $global_meli_code;
global $global_meli_tokens; 

$wp_mercadolibre_sync_settings = wp_mercadolibre_sync_settings();
extract($wp_mercadolibre_sync_settings); 

$meli = new Meli($appId, $secretKey);

?>

<form action='<?php $admin_url = admin_url('admin.php'); echo $admin_url; ?>' method='get'>

<div class="wpmlsync__postbox-container">
  
  <div class="wpmlsync__postbox-inner">

    <div class="wpmlsync__row">

      <div class="wpmlsync__col w-100">
        <div class="wpmlsync__card">

            <input type="hidden" readonly id="test_user" name="test_user" class="regular-text" value="1" >
            <input type="hidden" readonly id="page" name="page" class="regular-text" value="wp-mercadolibre-sync-tests" >

            <h2 class="wpmlsync__postbox-title"><?php echo __( 'Create "test" user', 'wp-mercadolibre-sync' ); ?></h2>
            <p class="about-description"><?php echo __( 'To create a user test you must use a token from a real account. For development and testing, it is advisable to create two test accounts, one to use as a seller, and another to use as a buyer so that you can make comments and purchases.', 'wp-mercadolibre-sync' ); ?></p>

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
                  <th scope="row"><?php echo __( 'Enter a valid token', 'wp-mercadolibre-sync' ); ?></th>
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

<input type="hidden" readonly id="page" name="page" class="regular-text" value="wp-mercadolibre-sync-tests" >
<input type="hidden" readonly id="post_test" name="post_test" class="regular-text" value="1" >

<div class="wpmlsync__postbox-container">
  
  <div class="wpmlsync__postbox-inner">

    <div class="wpmlsync__row">

      <div class="wpmlsync__col w-100">
        <div class="wpmlsync__card">

          <h2 class="wpmlsync__postbox-title"><?php echo __( 'Test publications', 'wp-mercadolibre-sync' ); ?></h2>
          <p class="about-description"><?php echo __( 'While this tool is for creating test publications, it can be used to create real publications if a real app and user is being used in the plugin settings. Take care. ', 'wp-mercadolibre-sync' ); ?></p>
          <?php
              
            if(isset($_GET['post_test'])){
              $params = array(
                'access_token' => wp_mercadolibre_sync_settings('access_token')
              );

              $wp_mercadolibre_sync_get_item_test = wp_mercadolibre_sync_get_item_test($_GET);

              $response = $meli->post('/items', $wp_mercadolibre_sync_get_item_test, $params); 
              ?>
              <p class="about-description"><?php echo __( 'Success! Your test item was listed!', 'wp-mercadolibre-sync' ); ?></p>
              <p class="about-description"><?php echo __( "Go to the permalink to see how it's looking in our site.", 'wp-mercadolibre-sync' ); ?>: </p>
              <p class="about-description"><a target="_blank" class="" href="<?php echo $response["body"]->permalink; ?>"><?php echo $response["body"]->permalink; ?></a></p>
              <?php 
            }

          ?> 

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