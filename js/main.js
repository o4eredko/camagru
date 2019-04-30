/*-----------------Events-----------------*/

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
	label1.hidden = label2.hidden = true;

	if (form.pass.value.length < 6) {
		label1.hidden = false;
	} else if (form.pass.value !== form.repass.value) {
		label2.hidden = false;
	} else {
		return true;
	}
	return false;
}

function sendRegistrationForm(e) {
	e.preventDefault();
	let loading = document.querySelector("#registrationForm .loading");
	let err1 = e.target.username.nextElementSibling;
	let err2 = e.target.email.nextElementSibling;
	let confirmation = document.querySelector("#registrationForm .form__confirmation");
	err1.hidden = err2.hidden = confirmation.hidden = true;
	if (!validateForm(e.target)) return false;
	let form = new FormData(e.target);
	form.append("action", "register");

	let xhr = new XMLHttpRequest();
	xhr.open('POST', 'ajax.php', true);
	xhr.send(form);
	xhr.onreadystatechange = function () {
		if (xhr.readyState !== 4) {
			if (loading) loading.classList.add("d-block");
		} else {
			if (loading) loading.classList.remove("d-block");
			if (xhr.status !== 200) {
				console.log("Ajax Post Request: Error");
			} else {
				let arr = JSON.parse(xhr.responseText);
				if (arr.user_exists || arr.email_exists)
					return ((arr.user_exists ? err1 : err2).hidden = false);
			}
			e.target.reset();
			confirmation.hidden = false;
		}
	}
}

function sendLoginForm(e) {
	e.preventDefault();
	let loading = document.querySelector("#loginForm .loading");
	let err1 = e.target.username.nextElementSibling;
	let err2 = e.target.pass.nextElementSibling;
	let confirmation = document.querySelector("#loginForm .form__confirmation");
	err1.hidden = err2.hidden = true;
	let form = new FormData(e.target);
	form.append("action", "login");

	let xhr = new XMLHttpRequest();
	xhr.open('POST', 'ajax.php', true);
	xhr.send(form);
	xhr.onreadystatechange = function() {
		if (xhr.readyState !== 4) {
			if (loading) loading.classList.add("d-block");
		} else {
			if (loading) loading.classList.remove("d-block");
			if (xhr.status !== 200) {
				console.log("Ajax Post Request: Error");
			} else {
				let arr = JSON.parse(xhr.responseText);
				if (arr.wrong_user || arr.wrong_pass)
					return ((arr.wrong_user ? err1 : err2).hidden = false);
				else if (!arr.email_confirmed) {
					if (confirmation) confirmation.textContent = "You have to confirm you e-mail first";
					return false;
				}
				login.classList.remove("active");
			}
			e.target.reset();
		}
	}
}

if (registrationForm) registrationForm.onsubmit = sendRegistrationForm;
if (loginForm) loginForm.onsubmit = sendLoginForm;


/*-----------------GET-----------------*/

var parts = window.location.search.substr(1).split("&");
var $_GET = {};
for (var i = 0; i < parts.length; i++) {
	var temp = parts[i].split("=");
	$_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
}

/*-----------------Verification-----------------*/

function showVerificationStatus() {
	let confirmation = document.querySelector("#loginForm .form__confirmation");

	if (login) login.classList.add("active");
	let xhr = new XMLHttpRequest();
	xhr.open('GET', 'ajax.php' + window.location.search, true);
	xhr.send();
	xhr.onreadystatechange = function() {
		if (xhr.readyState === 4 && confirmation) {
			confirmation.hidden = false;
			if (xhr.status !== 200) {
				confirmation.textContent = "Something was wrong";
			} else {
				if (xhr.responseText === "Done") {
					confirmation.textContent = "E-mail was confirmed! You can now log-in";
				} else {
					confirmation.textContent = "Something was wrong";
				}
			}
		}
	}
}

if ($_GET["action"] === "verificate" && $_GET["id"] !== undefined) {
	showVerificationStatus();
}
