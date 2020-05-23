<div class="wpmlsync__row">

  <div class="wpmlsync__col w-100">
    <div class="wpmlsync__card">

    	<h2 class="wpmlsync__postbox-title"><?php echo __( 'Mercado Libre Publications', 'wp-mercadolibre-sync' ); ?></h2>
    	<p>Esta es la lista de las publicaciones en Mercado Libre. Al sincronizarlas, se creara un Producto de Woocommerce con el mismo SKU del item de ML. Si algún cambio se hace en la publicacion de Mercado Libre, aparecerá el boton de "Actualizar" para poder re-sincronizar el item de ML con el Producto de Woocommerce ya existente en el sitio. </p>

    </div>
  </div>

  <div id="get-items-table" data-load-items="ml">
    
    <i class="spinner"></i>
    
    <table class="wpmlsync_list_table wp-list-table widefat fixed striped posts">

      <thead>
        <tr>
          <th class="column-thumb"><span class="wc-image tips">Imagen</span></th>
          <th>ID (ML)</th>
          <th width="20%">Title</th>
          <th>ML Dates</th>
          <th>WOO Dates</th>
          <th>Actions</th>
        </tr>
      </thead>

      <?php echo do_shortcode('[WPMLSYNC_Woocommerce action="get_items_table"/]');?>

    </table>

  </div>

</div>