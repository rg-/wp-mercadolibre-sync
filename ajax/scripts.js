jQuery(document).ready(function($) { 
	
	// We can also pass the url value separately from ajaxurl for front end AJAX implementations
	function do_ajax(first=true){
		var data = {
			'action': 'wpmlsync',
			'whatever': ajax_object.we_value      // We pass php values differently!
		};
		jQuery.post(ajax_object.ajax_url, data, function(response) { 
			console.log(response);
			ajax_loop(false); 
		});
	}
	do_ajax(true);

	function ajax_loop(first){
		if(first){
			console.log("first"); 
		}else{
			console.log("not first"); 
		}
		setTimeout(function(){ ajax_loop(false); }, 1000);

	}
});