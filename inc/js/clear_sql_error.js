function tt_clear_sql_error() {
    var send = {
		'security': wp_ajax_object_tt_clear_sql_error.security,
		'action': 'tt_clear_sql_error',
		'update': 'clear'
	};
	jQuery.ajax({
		action: 'tt_clear_sql_error',
        url: wp_ajax_object_tt_clear_sql_error.ajax_url,
        type: "POST",
        data: send ,
        success: function(results){
			if (results.success) {
            	//success
            	//window.alert('Your time tracker data has been backed up to a file in your home user directory in a folder called tt_logs.');
            	document.getElementById("sql-error-alert").innerHTML = "";
			} else {
            	//failure
				window.alert('There was an error clearing the message. Please check the logs or contact support.');
            	console.log(results.msg);				
			}
        }
    });
}