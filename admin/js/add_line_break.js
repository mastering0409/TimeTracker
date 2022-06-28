function tt_add_line_break(event) {
    var key = event.which || event.keyCode;
    var field_id = document.activeElement.id;
    var newLine = "\r\n";

    //if user pressed enter
    if (key == '13') {
        //add a line break to the field and stop the form submit from running
        document.getElementById(field_id).value = document.getElementById(field_id).value + newLine;
        //don't submit form
        return false;
    } else {
        return true;
    }
}