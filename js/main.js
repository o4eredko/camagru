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
	let form = new FormData(e.target);
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
			let currentLink = document.querySelector(".slider .slider-links__item.active");
			currentSlide.classList.remove("active");
			currentLink.classList.remove("active");
			slideToShow.classList.add("active");
			e.target.classList.add("active");
		};
		links.appendChild(link);
	}
	links.children[0].classList.add("active");
	slider.appendChild(links);
}

let paginationSettings = {
	maxElems: 8
};
pagination(document.querySelector(".slider"), paginationSettings);


/*-----------------Drag And Drop-----------------*/

let	isDragAllowed = function() {
	let div = document.createElement('div');
	return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
}();

let add_photo = new FormData();
let dropArea = document.querySelector(".add_photo__form .add_photo__area");
if (isDragAllowed && dropArea) {
	function dragOver() {
		dropArea.classList.add("highlight");
	}
	function dragLeave() {
		dropArea.classList.remove("highlight");
	}
	function handleDrop(e) {
		let dt = e.dataTransfer;
		let file = dt.files[0];

		addPhoto(file);
	}
	function addPhoto(file) {
		add_photo.append("img", file);
		dropArea.classList.add("selected");
	}

	['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
		dropArea.addEventListener(eventName, (e) => {
			e.stopPropagation();
			e.preventDefault();
		}, false)
	});
	['dragenter', 'dragover'].forEach(eventName => {
		dropArea.addEventListener(eventName, dragOver, false)
	});
	['dragleave', 'drop'].forEach(eventName => {
		dropArea.addEventListener(eventName, dragLeave, false)
	});
	dropArea.addEventListener('drop', handleDrop, false);
}

elem = document.querySelector(".add_photo__form");
if (elem) elem.onsubmit = (e) => {
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
};

/*-----------------Camera-----------------*/

const camSupported = "mediaDevices" in navigator;
const cam = document.getElementById("cam");
if (camSupported && cam) {
	const snapshot = document.getElementById("snapshot");
	const context = snapshot.getContext("2d");
	const snapButton = document.getElementById("snap-button");
	const constraints = {
		video: true
	};
	snapButton.addEventListener("click", () => {
		// snapshot.hidden = false;
		// cam.hidden = true;
		context.drawImage(cam, 0, 0, snapshot.width, snapshot.height);
		cam.srcObject.getVideoTracks().forEach(track => track.stop());
	});

	navigator.mediaDevices.getUserMedia(constraints)
	.then((stream) => {
		cam.srcObject = stream;
		cam.addEventListener("playing", () => {
			const styles = getComputedStyle(cam);
			snapshot.height = parseInt(styles.height);
			snapshot.width = parseInt(styles.width);
			let img = new Image();
			img.src = "img/leopard_sunglasses.png";
			console.log(img);
			function step() {
				context.drawImage(cam, 0, 0);
				context.drawImage(img, snapshot.width / 2 - img.width / 8, snapshot.height / 2 - img.height / 8, img.width / 4, img.height / 4);
				requestAnimationFrame(step);
			}
			requestAnimationFrame(step);
		});
	});
}

/*-----------------Likes and comments-----------------*/
function likePost(e) {
	let liked = e.target.classList.contains("active");
	let xhr = new XMLHttpRequest();
	let params = "?action=like&post_id=" + e.target.getAttribute("data-post-id") + "&liked=" + liked;

	xhr.open("GET", "ajax" + params, true);
	xhr.setRequestHeader("Content-type", "application/json");
	xhr.send();
	xhr.onreadystatechange = function() {
		if (xhr.readyState === 4 && xhr.status === 200) {
			e.target.classList.toggle("active");
			let likesNum = parseInt(e.target.nextSibling.textContent);
			likesNum += (liked) ? -1 : 1;
			e.target.nextSibling.textContent = likesNum;
		}
	}
}
let likes = document.querySelectorAll(".like");
for (let i = likes.length - 1; i >= 0; i--) {
	likes[i].onclick = likePost;
}

let commentsContainer = document.querySelector(".post-comment__list");
function showComments(container) {
	if (!container) return ;
	let xhr = new XMLHttpRequest();

	xhr.open("GET", "ajax?action=showComments&post_id=" + $_GET["id"], true);
	xhr.send();
	xhr.onreadystatechange = function() {
		if (xhr.readyState === 4 && xhr.status === 200) {
			container.innerHTML = xhr.responseText;
			elem = document.querySelectorAll(".post-comment__del");
			for (let i = elem.length - 1; i >= 0; i--)
				elem[i].onclick = delComment;
		}
	}
}
showComments(commentsContainer);

function commentPost(e) {
	e.preventDefault();
	let form = new FormData(e.target);
	let xhr = new XMLHttpRequest();
	form.append("action", "comment");
	form.append("post_id", $_GET["id"]);

	xhr.open("POST", "ajax", true);
	xhr.send(form);
	xhr.onreadystatechange = function() {
		if (xhr.readyState === 4 && xhr.status === 200) {
			e.target.reset();
			showComments(commentsContainer);
			elem = document.querySelector(".post__stat .post__comment");
			if (!elem) return ;
			let commentsNum = parseInt(elem.childNodes[1].textContent);
			commentsNum++;
			elem.childNodes[1].textContent = commentsNum;
		}
	}
}
elem = document.querySelector(".post-comment__form");
if (elem) elem.onsubmit = commentPost;

function delComment(e) {
	let xhr = new XMLHttpRequest();
	xhr.open("GET", "ajax?action=delComment&id=" +
		e.target.getAttribute("data-comment-id"), true);
	xhr.send();

	xhr.onreadystatechange = function() {
		if (xhr.readyState === 4 && xhr.status === 200) {
			showComments(commentsContainer);
			elem = document.querySelector(".post__stat .post__comment");
			if (!elem) return ;
			let commentsNum = parseInt(elem.childNodes[1].textContent);
			commentsNum--;
			elem.childNodes[1].textContent = commentsNum;
		}
	}
}
