//Update Task List When Client Name Changed
//function tt_watch_for_client_change() {
document.addEventListener('DOMContentLoaded', function () {  //make sure doc is done loading before looking for element
    var clientField = document.getElementsByName('client-name');
    var taskField = document.getElementsByName('task-name');
    if (clientField.length > 0 && taskField.length > 0) {
      clientField[0].addEventListener('change', tt_update_task_dropdown);
      //window.getElementsByName("client-name").addEventListener("change", updateProjectList);
    }
});
//}
  