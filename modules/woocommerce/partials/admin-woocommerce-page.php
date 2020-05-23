<?php
$active_tab = 'main_settings';
if( isset( $_GET[ 'tab' ] ) ) {
    $active_tab = $_GET[ 'tab' ];
} // end if


$tab_menu = array(

  array(
    'name' => 'main_settings',
    'title' => 'Main Settings',
  ),

  array(
    'name' => 'sync_publlications',
    'title' => 'Sync Publications',
  ),

  array(
    'name' => 'sync_categories',
    'title' => 'Sync Categories',
  ),

); 

?>

<div class="nav-tab-wrapper">
  <?php
  foreach($tab_menu as $tab){
  ?>
    <a href="?page=wp-mercadolibre-sync-woocommerce&tab=<?php echo $tab['name'];?>" class="nav-tab <?php echo $active_tab == $tab['name'] ? 'nav-tab-active' : ''; ?>"><?php echo $tab['title'];?></a>
    <?php
  }
  ?>
</div>

<div class="wpmlsync__postbox-container">
  <div class="wpmlsync__postbox-inner">
  
  <?php include plugin_dir_path( __FILE__ ) . 'admin-woocommerce-page-'.$active_tab.'.php';?> 

  </div> 
</div>