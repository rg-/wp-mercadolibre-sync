<?php

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

<form action='options.php' method='post'>
	
	<h2><?php echo __( 'WP Mercadolibre Sync', 'wp-mercadolibre-sync' ); ?></h2>
	
	<?php 
	$options = get_option( 'wp_mercadolibre_sync_settings' );

	$appId = $options['wp_mercadolibre_sync_appId'];
	$secretKey = $options['wp_mercadolibre_sync_secretKey'];
	$redirectURI = $options['wp_mercadolibre_sync_redirectURI'];
	$siteId = $options['wp_mercadolibre_sync_siteId'];
	
	echo $redirectURI;

	$meli = new Meli($appId, $secretKey);
	echo "<pre>";
	print_r($meli);
	echo "</pre>"; 

	$oAuth_link = $meli->getAuthUrl($redirectURI.'?page=wp-mercadolibre-sync', Meli::$AUTH_URL[$siteId]);
	echo $oAuth_link; 
	echo '<a href="' . $oAuth_link . '">Login using MercadoLibre oAuth 2.0</a>'; 

  // If code exist and session is empty
  if(isset($_GET['code']) && !isset($_SESSION['access_token'])) {
    // //If the code was in get parameter we authorize
    try{
    	echo "<p>Hay code pero no hay token en session</p>"; 
      $user = $meli->authorize($_GET["code"], $redirectURI);
      print_r($user);
      // Now we create the sessions with the authenticated user
      $_SESSION['access_token'] = $user['body']->access_token;
      $_SESSION['expires_in'] = time() + $user['body']->expires_in;
      $_SESSION['refresh_token'] = $user['body']->refresh_token;
    }catch(Exception $e){
      echo "Exception: ",  $e->getMessage(), "\n";
    }
  } else {
    // We can check if the access token in invalid checking the time
    if($_SESSION['expires_in'] < time()) {
      try {
        // Make the refresh proccess
        $refresh = $meli->refreshAccessToken(); 
        // Now we create the sessions with the new parameters
        $_SESSION['access_token'] = $refresh['body']->access_token;
        $_SESSION['expires_in'] = time() + $refresh['body']->expires_in;
        $_SESSION['refresh_token'] = $refresh['body']->refresh_token;
      } catch (Exception $e) {
          echo "Exception: ",  $e->getMessage(), "\n";
      }
    }
  }

  echo "<h4>SESSION</h4>"; 
	echo '<pre>';
    print_r($_SESSION);
  echo '</pre>';
  if(!session_id()){
  	echo "No hay session";
  }else{
  	echo "Hay session";
  }

  $params = array(
  	'access_token'=>$_SESSION['access_token']
  );
  echo "<p>GET '/users/me'</p>";
	$url = '/users/me';  
	$meli_result = $meli->get($url, $params); 
	$meli_user_id = $meli_result['body']->id;
	echo "<p>user_id/seller_id: ".$meli_user_id."</p>";
	echo "<pre>";
	print_r($meli_result);
	echo "</pre>";

	settings_fields( 'wp_mercadolibre_sync_api' );
	do_settings_sections( 'wp-mercadolibre-sync' );
	submit_button();
	?>
</form>