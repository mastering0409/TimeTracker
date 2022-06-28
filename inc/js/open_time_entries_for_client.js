//Open list of time entries for client
function open_time_entries_for_client(clientName) {
    client = encodeURIComponent(clientName);
    window.location.href = scriptDetails.tthomeurl + '/time-log/?client-name=' + client;
}