//Initiates when filter button pressed on page showing all time entries
function tt_filter_time_log(event) {
    var inputs = event.detail.inputs;
    var first_date = "";
    var last_date = "";
    var client = "";
    var notes = "";
    var ticket = "";
    var project = "";
    
    for (var i = 0; i < inputs.length; i++) {
        var input = inputs[i];
        if (input.name == 'first-date') {
            first_date = input.value;
        } else if (input.name == 'last-date') {
            last_date = input.value;
        } else if (input.name == 'client-name') {
            client = input.value;
        } else if (input.name == 'time-notes') {
            notes = input.value;
        } else if (input.name == 'project-name') {
            project = input.value;
        } else if (input.name == 'task-name') {
            //pull out task number, to the left of the hyphen  
            var task = input.value;
            ticket = task.split("-", 1);
            ticketname = task.split("-", 2);
            //ticket = inputs[i].value;
        } //end if
    }  //end for loop

    client = encodeURIComponent(client);
    notes = encodeURIComponent(notes);
    ticket = encodeURIComponent(ticket);
    project = encodeURIComponent(project);

    window.location.href = scriptDetails.tthomeurl + '/time-log/?client-name=' + client + '&notes=' + notes + '&task-number=' + ticket + '&task-name=' + task + '&project-name=' + project + '&first-date=' + first_date + '&last-date=' + last_date;

}