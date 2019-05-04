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

function pagination(slider, settings) {
	if (!slider) return;
	settings.maxPages = Math.ceil(slider.children.length / settings.maxElems);
	let i = 0;
	let len = slider.children.length;
	while (i < len) {
		let container = document.createElement("div");
		container.classList.add("slide");
		let j = -1;
		while (++j < settings.maxElems && i++ < len)
			container.appendChild(slider.children[0]);
		slider.appendChild(container);
	}
	slider.children[0].classList.add("active");
	for (i = 0; i < slider.children.length; i++)
		slider.children[i].setAttribute("data-slide-id", i);

	let links = document.createElement("div");
	links.classList.add("slider-links");
	for (i = 0; i < settings.maxPages; i++) {
		let link = document.createElement("span");
		link.classList.add("slider-links__item");
		link.setAttribute("data-link-id", i);
		link.onclick = function(e) {
			let id = e.target.getAttribute("data-link-id");
			let slideToShow = document.querySelector(".slider .slide[data-slide-id='" + id + "']");
			let currentSlide = document.querySelector(".slider .slide.active");
			currentSlide.classList.remove("active");
			slideToShow.classList.add("active");
		};
		links.appendChild(link);
	}
	slider.appendChild(links);
}

let paginationSettings = {
	maxElems: 8
};
pagination(document.querySelector(".slider"), paginationSettings);


/*Drag And Drop*/

let	isAdvancedUpload = function() {
	let div = document.createElement('div');
	return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
}();

let add_photo = new FormData();
if (isAdvancedUpload) {
	let dropArea = document.querySelector(".add_photo__form .add_photo__area");

	function preventDefaults (e) {
		e.preventDefault();
		e.stopPropagation();
	}
	['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
		dropArea.addEventListener(eventName, preventDefaults, false)
	});

	function highlight() {
		dropArea.classList.add("highlight");
	}
	['dragenter', 'dragover'].forEach(eventName => {
		dropArea.addEventListener(eventName, highlight, false)
	});

	function unhighlight() {
		dropArea.classList.remove("highlight");
	}
	['dragleave', 'drop'].forEach(eventName => {
		dropArea.addEventListener(eventName, unhighlight, false)
	});

	function handleDrop(e) {
		let dt = e.dataTransfer;
		let file = dt.files[0];

		uploadFile(file);
	}
	dropArea.addEventListener('drop', handleDrop, false);

	function uploadFile(file) {
		add_photo.append("img", file);
		console.log(add_photo.get("img"));

	}
}

function sendAddPhotoForm(e) {
	e.preventDefault();
	let data = new FormData(e.target);
	data.append("action", "addPhoto");
	data.set("img", add_photo.get("img"));
	let xhr = new XMLHttpRequest();

	xhr.open('POST', 'ajax', true);
	xhr.send(data);
	xhr.onreadystatechange = function() {
		if (xhr.readyState !== 4) {
		} else {
			if (xhr.status === 200) {
				console.log(xhr.responseText);
			}
		}
	}
}

elem = document.querySelector(".add_photo__form");
if (elem) elem.onsubmit = sendAddPhotoForm;

