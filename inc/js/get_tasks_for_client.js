function tt_update_task_dropdown() {
  var clientName =  encodeURIComponent(document.getElementsByName('client-name')[0].value);
  var send = {
      'security': wp_ajax_object_tt_update_task_list.security,
      'action': 'tt_update_task_list',
      'client': clientName
  };
  jQuery.ajax({
    action: 'tt_update_task_list',
    url: wp_ajax_object_tt_update_task_list.ajax_url,
    type: 'POST',
    data: send,
    success: function(response) {
      if (response.success) {
        //success
        //console.log(response.data.details);
        document.getElementsByName('task-name')[0].innerHTML = response.data.details;
      } else {
        //failed
        console.log('Get tasks for client function failed' + response.data.details + '. Error: ' + response.data.message);
      }
    }
  });
}