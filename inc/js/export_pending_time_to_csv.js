function export_pending_time_to_csv() {
  console.log('in function');
    var send = {
        'security': wp_ajax_object_tt_export_pending_time.security,
        'action': 'tt_export_pending_time',
        'export_to_csv': true
    };
    jQuery.ajax({
      action: 'tt_export_pending_time',
      url: wp_ajax_object_tt_export_pending_time.ajax_url,
      type: 'POST',
      data: send,
      success: function(response) {
        if (response.success) {
          //success
          //download each file
          let fils = response.data.files;
          for (let fil of fils){
            tt_download_file(fil['fname'], fil['fcontent']);
          };
        } else {
          //failed
          console.log('Pending time export to csv and download failed with error message: ' + response.msg);
        }
      }
    });
}

jQuery(document).ready(function () {
  var btn = document.getElementsByClassName('tt-export-pending-time');
  if (btn.length > 0) {
    jQuery(btn[0]).attr("onClick", "export_pending_time_to_csv(this)");
  }
});