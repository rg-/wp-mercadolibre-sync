(function( $ ) {
	'use strict';

	/** 
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * }); 
	 */
	 function get_category(me){  
	 	console.log('get_category: '+me.data('get-category'));
	 		var data = { 
				//'dataType': 'html',
				'action': 'wpmlsync_woo',
				'get_category': me.data('get-category'),
				//'whatever': ajax_object.we_value      // cab pass php values
			};
			jQuery.get(ajax_object.ajax_url, data, function(response) { 
				me.parent().append(response);
				me.parent().find('[data-get-category]').on('click',function(){
		 			get_category($(this)); 
					return false; 
		 		});
			});
	 }
	 $(function() { 
	 	var btn_data_get_category = $('[data-get-category]');
	 	btn_data_get_category.each(function(){

	 		$(this).on('click',function(){
	 			get_category($(this)); 
				return false; 
	 		}); 
	 	}); 
	 });

	// 
  
	function load_more_items(target, scroll_id=''){
		var table = $(target);
		var results_row = table.find('.wpmlsync__items-results');
		var load_more = table.find('.wpmlsync__button_load_more');
		
		table.find('> .spinner').addClass('is-active'); 
		results_row.append('<tr class="loading-tr"><td colspan="6">Loading...</td></tr>');

		var data = { 
			//'dataType': 'html',
			'action': 'wpmlsync_woo',
			'get_items_table__test': 'scan',
			//'whatever': ajax_object.we_value      // cab pass php values
		};
		if(scroll_id){
			data.scroll_id = scroll_id;
		}

		jQuery.get(ajax_object.ajax_url, data, function(response) {  

			// table.find('.scroll_id').html( $(response).attr('data-scroll-id') );
			load_more.attr('data-scroll-id', $(response).attr('data-scroll-id') );
			table.find('> .spinner').removeClass('is-active'); 
			results_row.find('.loading-tr').remove(); 
			results_row.append($(response).find('tr'));
			results_row.find('.sync__button').on('click',function(){ 
	  		sync_button_create_product($(this)); 
		 	});
		});

	}

	$( window ).load(function() {
		var table = $('[data-load-items]');
		var load_more = table.find('.wpmlsync__button_load_more');
		load_more.on('click', function(){ 
			var me = $(this); 
 			if(me.data('scroll-id')){
 				load_more_items(me.data('target'), me.data('scroll-id'));
 			}else{
 				load_more_items(me.data('target'));
 			}

		});
		// No need for this, doing same on php side for first page load
		// load_more.trigger('click');
	});


	/*
	* 
	* sync__button 
	* 
	*/

	function sync_button_create_product(me){

		// var me = $(this);

 		var data = {
 			'dataType': 'json',
			'action': 'wpmlsync_woo',
			'create_product': me.attr('data-item-id'),
			'whatever': ajax_object.we_value // We pass php values differently!
		};

		var tr = $('tr[data-item='+me.attr('data-item-id')+']');
		 
		tr.addClass('doing_ajax');
		me.addClass('doing_ajax');
		me.attr('disabled','disabled'); 
		me.find('.spinner').addClass('is-active');
		jQuery.get(ajax_object.ajax_url, data, function(response) {  
			
			me.removeClass('doing_ajax');
			tr.removeClass('doing_ajax');
			
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
					
					tr.find('.woo_date_created').html(date_created);
					tr.find('.woo_last_updated').html(last_updated);
				}

			} // 


		});
	}

  $(function() { 
  	 
  	$('.sync__button').on('click',function(){ 
  		sync_button_create_product($(this)); 
	 	});

	});
  /* 
	* sync__button END 
	*/

})( jQuery );