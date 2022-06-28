var acc = document.getElementsByClassName("tt-accordion");
var i;

for (i = 0; i < acc.length; i++) {
	let btntext = acc[i].innerHTML;
	if (acc[i].classList.contains("active")) {
	  acc[i].innerHTML = "︽ " + btntext;
	  acc[i].nextElementSibling.style.display = "block";
  	} else {
		acc[i].innerHTML = "︾ " + btntext;
  	}
  
  
	acc[i].addEventListener("click", function() {
		this.classList.toggle("active");

		var panel = this.nextElementSibling;
		if (panel.style.display === "block") {
      		panel.style.display = "none";
      		let txt = this.innerHTML;
      		this.innerHTML = txt.replace("︽", "︾");
		} else {
			panel.style.display = "block";
			let txt = this.innerHTML;
			this.innerHTML = txt.replace("︾", "︽");
		}
  });
}