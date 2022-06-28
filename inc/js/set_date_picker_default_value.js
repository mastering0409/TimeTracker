//Set default values for date pickers, if they exist in get value
document.addEventListener('DOMContentLoaded', function () {  //make sure doc is done loading before looking for element  
  var startDateField = document.getElementById('first-date');
  var endDateField = document.getElementById('last-date');  

  if (startDateField || endDateField ) {
    //https://stackoverflow.com/a/901144/7303640
    const params = new Proxy(new URLSearchParams(window.location.search), {
      get: (searchParams, prop) => searchParams.get(prop),
    });
    let start = params['first-date'];
    let end = params['last-date'];

    if (startDateField && start) {
      startDateField.setAttribute('value', start);
    }

    if (endDateField && end ) {
      endDateField.setAttribute('value', end);
    }
  }
});