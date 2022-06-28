function delete_tt_data(btn) {
    if (btn == 'first') {
        document.getElementById('delete-confirm').hidden = false;
    } else if (btn == 'second') {
		var send = {
			'security': wp_ajax_object_tt_delete_data.security,
			'action': 'tt_delete_data',
			'type': 'confirmed'
		};
        jQuery.ajax({
            url: wp_ajax_object_tt_delete_data.ajax_url,
            type: "POST",
            data: send,
            success: function(results){
				if (results.success) {
                	//success
                	window.alert('All of your time tracker data has been deleted.');
            	} else {
					//failure
					window.alert('There was an error when attempting to delete your time tracker data. Please check the logs or contact support.');
					console.log(results.msg);
				}
			}
        });
    }
}