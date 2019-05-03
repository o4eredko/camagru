/*-----------------Events-----------------*/

document.onclick = function(event) {
	let id = this.activeElement.getAttribute("data-toggle-id");
	if (!id) return ;
	event.preventDefault();
	let elem = document.getElementById(id);
	elem.classList.toggle("active");
};

elem = document.querySelector(".forgot-pass__link");
if (elem) elem.onclick = function forgotPass(event) {
	event.preventDefault();
	let form = document.getElementById("loginForm");
	if (!form) return ;
	document.getElementById("login").classList.remove("active");
};

/*-----------------Forms-----------------*/

function getLabels(form) {
	if (!form) return;
	let labels = {};
	for (let i = form.elements.length - 1; i >= 0; i--) {
		if (form.elements[i].nextElementSibling) {
			labels[form.elements[i].name] = form.elements[i].nextElementSibling;
		}
	}
	return labels;
}

function validatePass(form, labels) {
	if (!labels || !("pass" in labels) || !("repass" in labels))
		return true;
	if (form.pass && form.pass.value.length)
		labels.pass.hidden = (form.pass.value.length >= 6);
	if (form.pass && labels.repass)
		labels.repass.hidden = (form.pass.value === form.repass.value);
	return labels.pass.hidden && labels.repass.hidden;
}

function sendRegistrationForm(e) {
	e.preventDefault();
	let loading = document.querySelector("#registrationForm .loading");
	let labels = getLabels(e.target);
	let confirmation = document.querySelector("#registrationForm .form__confirmation");
	if (!validatePass(e.target, labels)) return ;
	let form = new FormData(e.target);
	form.append("action", "register");
	let xhr = new XMLHttpRequest();

	xhr.open('POST', 'ajax', true);
	xhr.send(form);
	xhr.onreadystatechange = function () {
		if (xhr.readyState !== 4) {
			if (loading) loading.classList.add("d-block");
		} else {
			if (loading) loading.classList.remove("d-block");
			if (xhr.status === 200) {
				let arr = JSON.parse(xhr.responseText);
				labels.username.hidden = !arr.user_exists;
				labels.email.hidden = !arr.email_exists;
				if (arr.user_exists || arr.email_exists) return ;
			}
			e.target.reset();
			confirmation.hidden = false;
		}
	}
}

function sendLoginForm(e) {
	e.preventDefault();
	let loading = document.querySelector("#loginForm .loading");
	let labels = getLabels(e.target);
	let confirmation = document.querySelector("#loginForm .form__confirmation");
	let form = new FormData(e.target);
	form.append("action", "login");
	let xhr = new XMLHttpRequest();

	xhr.open('POST', 'ajax', true);
	xhr.send(form);
	xhr.onreadystatechange = function() {
		if (xhr.readyState !== 4) {
			if (loading) loading.classList.add("d-block");
		} else {
			if (loading) loading.classList.remove("d-block");
			if (xhr.status === 200) {
				let arr = JSON.parse(xhr.responseText);
				labels.username.hidden = !arr.wrong_user;
				labels.pass.hidden = !arr.wrong_pass;
				if (arr.wrong_user || arr.wrong_pass) return ;
				confirmation.hidden = arr.email_confirmed;
				if (!arr.email_confirmed) return ;
				location.replace(location.origin);
			}
		}
	}
}

function sendChangeForm(e) {
	e.preventDefault();
	let loading = document.querySelector("#changeForm .loading");
	let labels = getLabels(e.target);
	let confirmation = document.querySelector("#changeForm .form__confirmation");
	if (!validatePass(e.target, labels)) return;
	let form = new FormData(e.target, labels);
	form.append("action", "change");
	let xhr = new XMLHttpRequest();

	xhr.open('POST', 'ajax', true);
	xhr.send(form);
	xhr.onreadystatechange = function() {
		if (xhr.readyState !== 4) {
			if (loading) loading.classList.add("d-block");
		} else {
			if (loading) loading.classList.remove("d-block");
			if (xhr.status === 200) {
				let arr = JSON.parse(xhr.responseText);
				labels.newuser.hidden = !arr.user_exists;
				labels.newemail.hidden = !arr.email_exists;
				labels.oldpass.hidden = !arr.wrong_oldpass;
				if (arr.user_exists || arr.email_exists || arr.wrong_oldpass) return ;
				confirmation.hidden = arr.email_confirmed;
				if (!arr.email_confirmed) return ;
			}
			location.reload();
		}
	}
}

function sendForgotForm(e) {
	e.preventDefault();
	let loading = document.querySelector("#forgotForm .loading");
	let labels = getLabels(e.target);
	let confirmation = document.querySelector("#forgotForm .form__confirmation");
	if (!validatePass(e.target, labels)) return ;
	let toReload = !("email" in labels);
	let form = new FormData(e.target);
	form.append("action", "forget");
	if ("id" in $_GET)
		form.append("id", $_GET["id"]);
	let xhr = new XMLHttpRequest();

	xhr.open('POST', 'ajax', true);
	xhr.send(form);
	xhr.onreadystatechange = function() {
		if (xhr.readyState !== 4) {
			if (loading) loading.classList.add("d-block");
		} else {
			if (loading) loading.classList.remove("d-block");
			if (xhr.status === 200) {
				let arr = JSON.parse(xhr.responseText);
				if ("email" in labels) {
					labels.email.hidden = !arr.wrong_email;
					if (arr.wrong_email) return ;
				}
				confirmation.hidden = arr.email_confirmed;
				if (!arr.email_confirmed) return ;
				if (toReload) {
					location.replace(location.origin);
					document.getElementById("forgot").classList.remove("active");
				} else {
					confirmation.textContent = "To change your password, go via link, which was sent to your email!";
					confirmation.hidden = false;
				}
			}
		}
	}
}

var elem;
if ((elem = document.getElementById("registrationForm")))
	elem.onsubmit = sendRegistrationForm;
if ((elem = document.getElementById("loginForm")))
	elem.onsubmit = sendLoginForm;
if ((elem = document.getElementById("changeForm")))
	elem.onsubmit = sendChangeForm;
if ((elem = document.getElementById("forgotForm")))
	elem.onsubmit = sendForgotForm;

/*-----------------GET-----------------*/

let parts = window.location.search.substr(1).split("&");
let $_GET = {};
for (let i = 0; i < parts.length; i++) {
	let temp = parts[i].split("=");
	$_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
}

/*-----------------Verification-----------------*/

function showVerificationStatus() {
	let confirmation = document.querySelector("#loginForm .form__confirmation");

	if (login) login.classList.add("active");
	let xhr = new XMLHttpRequest();
	xhr.open('GET', 'ajax' + window.location.search, true);
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

elem = document.querySelector(".login__button");
if (elem && $_GET["action"] === "verificate" && "id" in $_GET && "token" in $_GET) {
	showVerificationStatus();
}

elem = document.getElementById("forgot");
if (elem && $_GET["action"] === "forgot" && "id" in $_GET && "token" in $_GET) {
	elem.classList.add("active");
}

/*-----------------Pagination-----------------*/
function pagination(container, settings) {
	if (!container) return;
	settings.maxPages = Math.ceil(container.children.length / settings.maxElems);
	console.log(settings.maxPages);
	let pages = document.createElement("div");
	pages.classList.add("slider");
	container.appendChild(pages);
	for (let i = 0; i < settings.maxPages; i++) {
		let page = document.createElement("span");
		page.classList.add("slider__link");
		page.textContent = i + 1;
		pages.appendChild(page);
	}
}

let paginationSettings = {
	maxElems: 4,
};
pagination(document.querySelector(".posts"), paginationSettings);
