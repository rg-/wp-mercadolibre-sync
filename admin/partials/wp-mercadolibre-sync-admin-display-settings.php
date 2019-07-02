<!-- The settings fields form -->
<form action='options.php' method='post'>

  <?php
  settings_fields( 'wp_mercadolibre_sync_api' );
  ?>
  
  <div class="wpmlsync__postbox-container">
    <div class="wpmlsync__postbox-inner">
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
        <?php
          do_settings_sections( 'wp-mercadolibre-sync-private' ); 
          ?>
      </div>
    </div>
  </div>

  <div id="wpmlsync__advanced" class="wpmlsync__postbox-container">
    <div class="wpmlsync__postbox-inner">
      <div class="wpmlsync__card">
        <?php
        do_settings_sections( 'wp-mercadolibre-sync-advanced' ); 
        ?>
      </div>
    </div>
  </div>

  <div class="wpmlsync__postbox-container">
    <div class="wpmlsync__postbox-inner">
      <div class="wpmlsync__col w-100 wpmlsync__col_buttons">
        <?php 
        submit_button( __('Save Changes','wp-mercadolibre-sync'), 'button button-primary wpmlsync__button', 'submit', false ); 
        ?>
      </div>
    </div>
  </div>

</form>