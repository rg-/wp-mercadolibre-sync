(function( $ ) {
	'use strict';

	$('[data-item-id].sync__button').on('click',function(){ 

		var me = $(this); 
 		var data = {
 			'dataType': 'json',
			'action': 'wpmlsync_woo',
			'create_product': me.attr('data-item-id'),
			'whatever': ajax_object.we_value // We pass php values differently!
		};

		me.attr('disabled','disabled'); 
		me.find('.spinner').addClass('is-active');

		jQuery.get(ajax_object.ajax_url, data, function(response) { 

			var obj = JSON.parse(response); 

				if(obj.product_data && obj.wpmlsync_json){
 
					var product_data = obj.product_data;
					product_data = JSON.parse(product_data);
					
					var wpmlsync_json = obj.wpmlsync_json; 
					wpmlsync_json = JSON.parse(wpmlsync_json);

					var date_created = obj.date_created;  
					var last_updated = obj.last_updated;  
					if(product_data['sku'] == me.attr('data-item-id') ){  
						me.find('.spinner').removeClass('is-active');
						me.addClass('d-none');
						me.parent().find('.sync_re__button').removeClass('d-none');  
					}

				} // 

		});

	});

})( jQuery );