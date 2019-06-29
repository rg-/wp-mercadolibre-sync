<?php

// TODO SEE how to implement tabs http://qnimate.com/add-tabs-using-wordpress-settings-api/

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/rg-
 * @since      1.0.0
 *
 * @package    Wp_Mercadolibre_Sync
 * @subpackage Wp_Mercadolibre_Sync/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. --> 
<?php

global $global_meli;
global $global_meli_code;
global $global_meli_tokens; 

// get api settings from options
$wp_mercadolibre_sync_settings = wp_mercadolibre_sync_settings();
// extract varis like $appId, $secretKey...
extract($wp_mercadolibre_sync_settings); 

$meli_code_array = wp_mercadolibre_sync_meli_code_array();  
?>

<div class="wpmlsync__postbox-container">
  
  <div class="wpmlsync__postbox-inner">
    
    <div class="wpmlsync__card">
      <h1 class="wp-heading-inline"><?php echo __( 'WP Mercadolibre Sync', 'wp-mercadolibre-sync' ); ?></h1>
    </div>

    <div class="wpmlsync__row">

      <div class="wpmlsync__col w-33">
        <div class="wpmlsync__card">
            <h3 class="wpmlsync__postbox-subtitle">Estado del sistema</h3>
            <?php
            if( $global_meli_code==6 || $global_meli_code==4 || $global_meli_code==5){
              ?>
              <p><span class="dashicons dashicons-yes"></span> API conectada</p>
              <?php
            }else{
              if( $global_meli_code==7 ) {
                  ?>
                  <p><span class="dashicons dashicons-warning"></span> Autentificar la aplicación</p>
                  <?php
                }else{
                   ?>
                  <p><span class="dashicons dashicons-warning"></span> No hay datos seteados</p>
                  <?php
                }
             
            }
            ?> 
        </div>
      </div>

      <div class="wpmlsync__col w-33">
        <div class="wpmlsync__card">
            <h3 class="wpmlsync__postbox-subtitle">Applicacion</h3>
            <?php 
              if( $global_meli_code==6 || $global_meli_code==4 || $global_meli_code==5){
                ?>
                <p><span class="dashicons dashicons-yes"></span> Autorizada</p>
                <?php
              }else{ 
                ?>
                <p><span class="dashicons dashicons-warning"></span> No hay applicaciones vinculadas</p>
                <?php
              }
            ?>
            <ul class="wpmlsync_ul">
              <?php if($global_meli_code==6 || $global_meli_code==4 || $global_meli_code==5){ ?>
                <li>appId: <b><?php echo $appId; ?></b></li>
                <li>seller_id: <b><?php echo $seller_id; ?></b></li>
              <?php } ?>
            </ul> 

        </div>
      </div>

      <div class="wpmlsync__col w-33">

          <div class="wpmlsync__card">
            <h3 class="wpmlsync__postbox-subtitle">Token health</h3>
            <?php  
              if($global_meli_code==6 || $global_meli_code==4 || $global_meli_code==5){

                ?>
                <p><span class="dashicons dashicons-yes"></span> Activo</p>
                <?php

                $current = new DateTime(date('Y-m-d H:i:s', time()));//start time
                $expiration = new DateTime(date('Y-m-d H:i:s', $expires_in));//end time
                $interval = $current->diff($expiration);
                // %Y years %m months %d days %H hours %i minutes %s seconds
                ?>
                <ul class="wpmlsync_ul">
                  <li><?php echo $interval->format('%H hours %i minutes %s seconds'); ?></li>
                  <li>Expires in (UTC): <small><?php echo date('Y-m-d H:i:s', $expires_in); ?></small></li>
                </ul>
                <?php } else { ?>
                <p><span class="dashicons dashicons-warning"></span> No existen datos para analizar</p>
                <?php
              }
              $_check_expires_in = !empty($expires_in) ? $expires_in : 0;
              if( $_check_expires_in < time()) {  
                  // echo "<p>Token expirado.</p>";
                }else{ 
                  
                }  

              if($global_meli_code==5){  

              } 
              ?>
          </div>

      </div>

    </div>
    <!-- .wpmlsync__row -->

    <div class="wpmlsync__row">
      <div class="wpmlsync__col w-100 wpmlsync__col_buttons">
      
      <?php echo '<p>'.$meli_code_array[$global_meli_code]['desc'].'</p>';?>  

      <?php  

        if( $global_meli_code==0 ){
          echo '<a class="button button-secondary wpmlsync__button" href="https://applications.mercadolibre.com/" target="_blank">Manage your Mercado Libre applications</a>';
        }else{
          $oAuth_button_text = 'Autorize API';
          if($global_meli_code==4 || $global_meli_code==6){
            $oAuth_button_text = 'Refresh API Autorization';
          } 
          echo '<a class="button button-primary wpmlsync__button" href="' . $global_meli->getAuthUrl($redirectURI, Meli::$AUTH_URL[$siteId]) . '"><span class="dashicons dashicons-shield-alt"></span> '.$oAuth_button_text.'</a>';  
          echo '<a class="button button-secondary wpmlsync__button" href="' . $redirectURI . '&refresh_token=1"><span class="dashicons dashicons-update-alt"></span> Manual refresh token</a>'; 
          echo '<a class="button button-secondary wpmlsync__button" href="https://applications.mercadolibre.com/" target="_blank">Manage your Mercado Libre applications</a>';
        } 
      ?>

      <?php echo '<p>code '.$global_meli_code.'</p>';?>
      </div>
    </div>
    <!-- .wpmlsync__row -->

  </div><!-- .wpmlsync__postbox-inner -->
</div><!-- .wpmlsync__postbox-container -->

<div class="clear"></div>
<!-- #post-body-content end -->

<!-- -->

<form action='options.php' method='post'>
  <?php
  settings_fields( 'wp_mercadolibre_sync_api' );
  ?>
  
  <div class="wpmlsync__postbox-container">
    <div class="wpmlsync__postbox-inner">
      <div class="wpmlsync__card">
        <h2 class="wpmlsync__postbox-title"><?php echo __( 'API Settings', 'wp-mercadolibre-sync' ); ?></h2>
      </div>
      <div class="wpmlsync__card">
      <?php
      do_settings_sections( 'wp-mercadolibre-sync' );
      ?>
      </div>
    </div>
  </div>

  <div class="wpmlsync__postbox-container">
    <div class="wpmlsync__postbox-inner">
      <div class="wpmlsync__card">
        <h2 class="wpmlsync__postbox-title"><?php echo __( 'Advanced Settings', 'wp-mercadolibre-sync' ); ?></h2>
      </div>
      <div class="wpmlsync__card">
      <?php
      do_settings_sections( 'wp-mercadolibre-sync-advanced' ); 
      ?>
      </div>
      <div class="wpmlsync__col w-100 wpmlsync__col_buttons">
        <?php 
        submit_button('Guardar Cambios', 'button button-primary wpmlsync__button', 'submit', false); 
        ?>
      </div>
    </div>
  </div>

  <div class="wpmlsync__postbox-container">
    <div class="wpmlsync__postbox-inner">
      <div class="wpmlsync__card">
        <h2 class="wpmlsync__postbox-title"><?php echo __( 'oAuth Data', 'wp-mercadolibre-sync' ); ?></h2>
      </div>
      <div class="wpmlsync__card">
        <?php
        do_settings_sections( 'wp-mercadolibre-sync-private' ); 
        ?>
      </div>
    </div>
  </div>

</form>