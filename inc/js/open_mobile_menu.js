//Toggle button to open mobile menu for Time Tracker Pages Navigation
function tt_open_mobile_menu() {
	var x = document.getElementById("tt-nav-links");
	if (x.style.display === "block") {
		x.style.display = "none";
	} else {
		x.style.display = "block";
	} 
}