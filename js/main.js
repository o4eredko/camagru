function switchElement(elem, option)
{
	if (!elem) return;
	elem.style.display = (option) ? "block" : "none";
}

var modal = document.querySelector(".modal");
var signUp = document.querySelectorAll(".sign-up-button");
for (var i = signUp.length - 1; i >= 0; i--) {
	signUp[i].onclick = function(e) {
		e.preventDefault();
		modal.classList.add("active");
	}
}

function closeModal(e) {
	if (e) e.preventDefault();
	modal.classList.remove("active");
}
document.querySelector(".close").onclick = closeModal;

function validateForm(form) {
	var label = document.querySelector("label[for='pass']");
	if (form.pass.value.length < 6) {
		switchElement(label, true);
		return false;
	} else {
		switchElement(label, false);
	}
	label = document.querySelector("label[for='repass']");
	if (form.pass.value != form.repass.value) {
		switchElement(label, true);
		return false;
	} else {
		switchElement(label, false);
	}
	return true;
}

registration.onsubmit = postRequest;
function postRequest(e) {
	e.preventDefault();
	if (!validateForm(e.target)) return false;
	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function () {
		if (xhr.readyState != 4) {
			switchElement(document.querySelector(".loading"), true);
		} else {
			switchElement(document.querySelector(".loading"), false);
			if (xhr.status != 200) {
				console.log("Ajax Post Request: Error");
			} else {
				console.log("Ajax Post Request: Success");
			}
		}
	}
	xhr.open('POST', 'registration.php', true);
	xhr.send(new FormData(e.target));
	e.target.reset();
	closeModal();
}
