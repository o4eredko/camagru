/*-----------------Events-----------------*/

function switchElement(elem, option) {
	if (!elem) return;
	elem.style.display = (option) ? "block" : "none";
}

var signUp = document.querySelectorAll(".signup__button");
for (var i = signUp.length - 1; i >= 0; i--) {
	signUp[i].onclick = function(e) {
		e.preventDefault();
		if (registration) registration.classList.add("active");
	}
}

function closeModal(event) {
	event.preventDefault();
	var target = event.target;

	while (target.parentNode && !target.classList.contains("modal")) {
		target = target.parentNode;
	}
	if (target.classList.contains("modal")) {
		target.classList.remove("active");
	}
}
var closeButtons = document.querySelectorAll(".close");
for (var i = closeButtons.length - 1; i >= 0; i--) {
	closeButtons[i].onclick = closeModal;
}

var logIn = document.querySelector(".login__button");
if (logIn && document.getElementById("login")) {
	logIn.onclick = function(event) {
		event.preventDefault();
		login.classList.add("active");
	}
}

/*-----------------Forms-----------------*/

function validateForm(form) {
	var label1 = form.pass.nextElementSibling;
	var label2 = form.repass.nextElementSibling;
	switchElement(label1, false);
	switchElement(label2, false);

	if (form.pass.value.length < 6) {
		switchElement(label1, true);
	} else if (form.pass.value != form.repass.value) {
		switchElement(label2, false);
	} else {
		return true;
	}
	return false;
}

function sendRegistrationForm(e) {
	e.preventDefault();
	var err1 = e.target.username.nextElementSibling;
	var err2 = e.target.email.nextElementSibling;
	switchElement(err1, false);
	switchElement(err2, false);
	if (!validateForm(e.target)) return false;

	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'registration.php', true);
	xhr.send(new FormData(e.target));
	xhr.onreadystatechange = function () {
		if (xhr.readyState != 4) {
			switchElement(document.querySelector("#registrationForm .loading"), true);
		} else {
			switchElement(document.querySelector("#registrationForm .loading"), false);
			if (xhr.status != 200) {
				console.log("Ajax Post Request: Error");
			} else {
				var arr = JSON.parse(xhr.responseText);
				if (arr.user_exists || arr.email_exists) {
					switchElement((arr.user_exists ? err1 : err2), true);
					return false;
				}
			}
			e.target.reset();
			registration.classList.remove("active");
		}
	}
}

function sendLoginForm(e) {
	e.preventDefault();
	var err1 = e.target.username.nextElementSibling;
	var err2 = e.target.pass.nextElementSibling;
	switchElement(err1, false);
	switchElement(err2, false);

	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'login.php', true);
	xhr.send(new FormData(e.target));
	xhr.onreadystatechange = function() {
		if (xhr.readyState != 4) {
			switchElement(document.querySelector("#loginForm .loading"), true);
		} else {
			switchElement(document.querySelector("#loginForm .loading"), false);
			if (xhr.status != 200) {
				console.log("Ajax Post Request: Error");
			} else {
				var arr = JSON.parse(xhr.responseText);
				if (arr.wrong_user || arr.wrong_pass) {
					switchElement((arr.wrong_user ? err1 : err2), true);
					return false;
				}
			}
			e.target.reset();
			login.classList.remove("active");
		}
	}
}

if (registrationForm) registrationForm.onsubmit = sendRegistrationForm;
if (loginForm) loginForm.onsubmit = sendLoginForm;
