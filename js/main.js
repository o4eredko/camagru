var form = document.querySelector(".modal");
var signUp = document.querySelectorAll(".sign-up-button");
for (var i = signUp.length - 1; i >= 0; i--) {
	signUp[i].onclick = function(e) {
		e.preventDefault();
		form.classList.add("active");
	}
}

document.querySelector(".close").onclick = function(e) {
	e.preventDefault();
	form.classList.remove("active");
}
