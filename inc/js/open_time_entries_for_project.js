//Open list of time entries for project
function open_time_entries_for_project(projectName) {
    project = encodeURIComponent(projectName);
    window.location.href = scriptDetails.tthomeurl + '/time-log/?project-name=' + project;
}