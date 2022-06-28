function deleteRecord(tablename, idfield, itemid) {
	var send = {
		'security': wp_ajax_object_tt_delete_record.security,
		'action': 'tt_delete_record',
		'table': tablename,
        'field': idfield,
		'id': itemid
	};
	jQuery.ajax({
	    action: 'tt_delete_record',
	    url: wp_ajax_object_tt_delete_record.ajax_url,
	    type: 'POST',
	    data: send ,
        success: function(response){
            if (response.success) {
                document.getElementById('tt-delete-confirmation-result').innerHTML = idfield.replace("-", " ") + itemid + " has been successfully deleted";
            } else {
                document.getElementById('tt-delete-confirmation-result').innerHTML = "there was a problem - " + idfield.replace("-", " ") + itemid + " was not deleted";
                console.log('Record deletion failed for table ' + tablename + ', ID ' + itemid + '. Attempt ' + response.details + '. Error: ' + response.message);
            }
        },
        fail: function(response) {
            document.getElementById('tt-delete-confirmation-result').innerHTML = "there was a problem - " + idfield.replace("-", " ") + itemid + " was not deleted";
            console.log('Record deletion failed for table ' + tablename + ', ID ' + itemid + '. Response ' + response.textStatus);            
        }

	});
  }