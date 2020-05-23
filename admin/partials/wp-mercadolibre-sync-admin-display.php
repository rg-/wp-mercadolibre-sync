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
// get api settings from options
$wp_mercadolibre_sync_settings = wp_mercadolibre_sync_settings();
// extract varis like $appId, $secretKey...
extract($wp_mercadolibre_sync_settings);  
 
$MELI = new Meli($appId, $secretKey);  
$api_status = wp_mercadolibre_sync_get_api_status();
$meli_code_array = wp_mercadolibre_sync_meli_code_array(); 

$api_status_ok = false;
if($api_status==6 || $api_status==4 || $api_status==5  || $api_status==8 ){
  $api_status_ok = true;
}

?>
<div class="wpmlsync__postbox-container">
  
  <div class="wpmlsync__postbox-inner">

    <div class="wpmlsync__row">

      <div class="wpmlsync__col w-33">
        <div class="wpmlsync__card">
            <h3 class="wpmlsync__postbox-title"><?php _e('System status','wp-mercadolibre-sync'); ?></h3>
            <?php 

            if( $api_status_ok ){
              ?>
              <p><span class="dashicons dashicons-yes color-success"></span> <?php _e('Connected API','wp-mercadolibre-sync'); ?></p>
              <?php
              if(!empty($auto_token)){
                echo '<p><span class="dashicons dashicons-yes color-success"></span> auto_token ON</p>';
              }else{
                echo '<p><span class="dashicons dashicons-warning color-danger"></span> auto_token OFF</p>';
              } 

              $curl_settings = get_option( 'wp_mercadolibre_sync_curl_settings' );
              if(!empty($curl_settings['curl_ssl'])){
                echo '<p><span class="dashicons dashicons-yes color-success"></span> SSL_VERIFYPEER ON</p>';
              }else{
                echo '<p><span class="dashicons dashicons-warning color-danger"></span> SSL_VERIFYPEER OFF</p>';
              }

              if(!empty($debug)){
                echo '<p><span class="dashicons dashicons-warning color-warning"></span> debug ON</p>';
              }else{
                echo '<p><span class="dashicons dashicons-yes color-success"></span> debug OFF</p>';
              }

            }else{
              if( $api_status==7 ) {
                  ?>
                  <p><span class="dashicons dashicons-warning color-warning"></span> <?php _e('Authenticate the application','wp-mercadolibre-sync'); ?></p>
                  <?php
                }else{
                   ?>
                  <p><span class="dashicons dashicons-warning color-danger"></span> <?php _e('No data set','wp-mercadolibre-sync'); ?></p>
                  <?php
                } 
            }
            echo "<p>api_status: ".$api_status."</p>";
            ?> 
        </div>
      </div>

      <div class="wpmlsync__col w-33">
        <div class="wpmlsync__card">
            <h3 class="wpmlsync__postbox-subtitle"><?php _e('Application','wp-mercadolibre-sync'); ?></h3>
            <?php 
              if( $api_status_ok ){
                ?>
                <p><span class="dashicons dashicons-yes color-primary"></span> <?php _e('Connected','wp-mercadolibre-sync'); ?></p>
                <ul class="wpmlsync_ul"> 
                     
                    <?php
                    $params = array(
                      'access_token'=>$access_token
                    ); 
                    $url = '/applications/'.$appId;  
                    $meli_result = $MELI->get($url, $params); 
                    $name = $meli_result['body']->name;
                    $url = $meli_result['body']->url;
                    ?>
                    <li>App id: <b><?php echo $appId; ?></b></li>
                    <li>App name: <a href="<?php echo $url; ?>" target="_blank"><b><?php echo $name; ?></b></a></li>
                </ul> 
                <?php
                $current = new DateTime(date('Y-m-d H:i:s', time()));//start time
                $expiration = new DateTime(date('Y-m-d H:i:s', $expires_in));//end time
                $interval = $current->diff($expiration);
                // %Y years %m months %d days %H hours %i minutes %s seconds
                ?> 
                <ul class="wpmlsync_ul">
                  <li><b><?php _e('Token life','wp-mercadolibre-sync'); ?></b></li>
                  <li><?php echo $interval->format('%H hours %i minutes %s seconds'); ?></li>
                  <li><?php _e('Expires in (UTC)','wp-mercadolibre-sync'); ?> <small><?php echo date('Y-m-d H:i:s', $expires_in); ?></small></li>
                </ul>
                <?php
              }else{ 
                ?>
                <p><span class="dashicons dashicons-warning color-danger"></span> <?php _e('There are no linked applications','wp-mercadolibre-sync'); ?></p>
                <?php
              }
            ?>
        </div>
      </div>

      <div class="wpmlsync__col w-33">

          <div class="wpmlsync__card">
            <h3 class="wpmlsync__postbox-subtitle"><?php _e('User','wp-mercadolibre-sync'); ?></h3>
            <?php 
              if( $api_status_ok ){
                ?>
                <p><span class="dashicons dashicons-yes color-primary"></span> <?php _e('Authorized','wp-mercadolibre-sync'); ?></p>
                <ul class="wpmlsync_ul"> 
                     
                    <?php
                    $params = array(
                      'access_token'=>$access_token
                    );   
                    $url = '/users/me';  
                    $meli_result = $MELI->get($url, $params); 
                    $nickname = $meli_result['body']->nickname;
                    $permalink = $meli_result['body']->permalink; 
                    ?> 
                    <li>Seller Id: <b><?php echo $seller_id; ?></b></li>
                    <li>Nickname: <a href="<?php echo $permalink; ?>" target="_blank"><?php echo $nickname; ?></a></li> 
                </ul> 
                <?php
              }else{ 
                ?>
                <p><span class="dashicons dashicons-warning color-danger"></span> <?php _e('There are no authorized users','wp-mercadolibre-sync'); ?></p>
                <?php
              }
            ?>
          </div>

      </div>

    </div>
    <!-- .wpmlsync__row -->

    <div class="wpmlsync__row">
      <div class="wpmlsync__col w-100 wpmlsync__col_buttons">
      
      <?php echo '<p>'.$meli_code_array[$api_status]['desc'].'</p>'; ?>  

      <?php  

        if( $api_status==0 ){
          echo '<a class="button button-secondary wpmlsync__button" href="https://applications.mercadolibre.com/" target="_blank">'.__('Manage your Mercado Libre Applications','wp-mercadolibre-sync').'</a>';
        }else{
          $oAuth_button_text = __('Authorize API','wp-mercadolibre-sync');
          if($api_status==4 || $api_status==6){
            $oAuth_button_text = __('Refresh API Authorization','wp-mercadolibre-sync');
          } 
          
          echo '<a class="button button-primary wpmlsync__button" href="' . $MELI->getAuthUrl($redirectURI, Meli::$AUTH_URL[$siteId]) . '"><span class="dashicons dashicons-shield-alt"></span> '.$oAuth_button_text.'</a>';  
         
          echo '<a class="button wpmlsync__button" href="' . $redirectURI . '&refresh_token=1"><span class="dashicons dashicons-update-alt"></span> '.__('Manual refresh Token','wp-mercadolibre-sync').'</a>'; 
         
          echo '<a class="button wpmlsync__button" href="https://applications.mercadolibre.com/" target="_blank">'.__('Manage your Mercado Libre Applications','wp-mercadolibre-sync').'</a>';
        } 
      ?> 

      </div>
    </div>
    <!-- .wpmlsync__row -->

  </div><!-- .wpmlsync__postbox-inner -->
</div><!-- .wpmlsync__postbox-container -->

<div class="clear"></div>
<!-- #post-body-content end -->