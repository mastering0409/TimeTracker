//Update Project List When Client Name Changed
//function tt_watch_for_client_change_project() {
document.addEventListener('DOMContentLoaded', function () {  //make sure doc is done loading before looking for element
    var clientField = document.getElementsByName('client-name');
    var projectField = document.getElementsByName('project-name');
    if (clientField.length > 0 && projectField.length > 0) {
      clientField[0].addEventListener('change', tt_update_project_dropdown);
    }
});
//}
  