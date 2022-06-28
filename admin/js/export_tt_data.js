function export_tt_data(button_type) {
	var send = {
		'security': wp_ajax_object_tt_export_data.security,
		'action': 'tt_export_data',
		'type': +button_type	
	};
    jQuery.ajax({
        url: wp_ajax_object_tt_export_data.ajax_url,
        type: "POST",
        data: send,
        success: function(results){
        	if (results.success) {
				//success
        	    window.alert('Your time tracker data has been backed up to a file in your home user directory in a folder called tt_logs.');
       	 	} else {
         	   window.alert('There has been an error backing up your time tracker data. Please check the logs or contact support.');
          	  console.log(results.msg);
        	}
		}
	});
}