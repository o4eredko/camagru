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

function validateEmail(email) {
	let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(String(email).toLowerCase());
}

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

/*-----------------Likes and comments-----------------*/

function likePost(e) {
	e.preventDefault();
	let link = e.currentTarget;
	let liked = link.classList.contains("active");
	let xhr = new XMLHttpRequest();
	let params = "?action=like&post_id=" + e.target.getAttribute("data-post-id") + "&liked=" + liked;

	xhr.open("GET", "ajax" + params, true);
	xhr.setRequestHeader("Content-type", "application/json");
	xhr.send();
	xhr.onreadystatechange = function() {
		if (xhr.readyState === 4 && xhr.status === 200) {
			link.classList.toggle("active");
			let likesNum = parseInt(link.childNodes[2].textContent);
			likesNum += (liked) ? -1 : 1;
			link.childNodes[2].textContent = likesNum.toString();
		}
	}
}
let likes = document.querySelectorAll(".post__like[data-post-id]");
for (let i = likes.length - 1; i >= 0; i--) {
	likes[i].onclick = likePost;
}

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
let commentsContainer = document.querySelector(".post-comment__list");
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
			elem.childNodes[1].textContent = commentsNum.toString();
		}
	}
}
elem = document.querySelector(".post-comment__form");
if (elem) elem.onsubmit = commentPost;

function delComment(e) {
	let csrf = e.target.getAttribute("data-csrf");
	let params = "?action=delElem&where=comments&id=" + e.target.getAttribute("data-comment-id") +
		"&csrf=" + csrf;
	let xhr = new XMLHttpRequest();
	xhr.open("GET", "ajax" + params, true);
	xhr.send();

	xhr.onreadystatechange = function() {
		if (xhr.readyState === 4 && xhr.status === 200) {
			showComments(commentsContainer);
			elem = document.querySelector(".post__stat .post__comment");
			if (!elem) return ;
			let commentsNum = parseInt(elem.childNodes[1].textContent);
			commentsNum--;
			elem.childNodes[1].textContent = commentsNum.toString();
		}
	}
}

function delPost(e) {
	let id = e.target.getAttribute("data-post-id");
	let params = "?action=delPost&id=" + id;
	let xhr = new XMLHttpRequest();
	xhr.open("GET", "ajax" + params, true);
	xhr.send();

	xhr.onreadystatechange = function () {
		if (xhr.readyState === 4 && xhr.status === 200) {
			let post = document.querySelector(".post[data-id='" + id + "']").parentNode;
			post.parentNode.removeChild(post);
		}
	}
}
elem = document.querySelectorAll(".post__del");
for (let i = elem.length - 1; i >= 0; i--)
	elem[i].onclick = delPost;


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

pagination(document.querySelector(".slider"), {
	maxElems: 8
});

/*-----------------Camera-----------------*/

function showSnaphots(container) {
	let xhr = new XMLHttpRequest();
	xhr.open("GET", "ajax?action=showSnapshots", true);
	xhr.send();
	xhr.onreadystatechange = () => {
		if (xhr.readyState === 4 && xhr.status === 200) {
			container.innerHTML = xhr.responseText;
			elem = document.querySelectorAll(".snapshot .upload");
			for (let i = elem.length - 1; i >= 0; i--)
				elem[i].onclick = uploadSnapshot;
			elem =  document.querySelectorAll(".snapshots .remove");
			for (let i = elem.length - 1; i >= 0; i--)
				elem[i].onclick = delSnapshot;
			elem = document.querySelectorAll(".snapshots canvas");
			for (let i = elem.length - 1; i >= 0; i--) {
				let img = new Image();
				img.src = elem[i].getAttribute("data-src");
				elem[i].getContext("2d").drawImage(img, 0, 0);
			}
		}
	}
}
let snapshotContainer = document.querySelector(".snapshots");
if (snapshotContainer) {
	showSnaphots(snapshotContainer);
}

function uploadSnapshot(e) {
	e.preventDefault();
	let img = e.target.parentNode.firstElementChild;
	let canvas = document.createElement("canvas");

	document.body.appendChild(canvas);
	canvas.width = img.naturalWidth;
	canvas.height = img.naturalHeight;
	canvas.getContext("2d").drawImage(img, 0, 0);
	canvas.toBlob((blob) => {
		addPhoto(blob, img.src);
	});
	canvas.parentNode.removeChild(canvas);
}
function delSnapshot(e) {
	e.preventDefault();
	let id = e.target.getAttribute("data-remove-id");
	let csrf = e.target.getAttribute("data-csrf");
	let xhr = new XMLHttpRequest();

	xhr.open("GET", "ajax?action=delElem&where=snapshots&id=" + id + "&csrf=" + csrf, true);
	xhr.send();
	xhr.onreadystatechange = () => {
		if (xhr.readyState === 4 && xhr.status === 200) {
			showSnaphots(snapshotContainer);
		}
	}
}

const camSupported = "mediaDevices" in navigator;
const cam = document.getElementById("cam");
if (camSupported && cam) {
	const snapshot = document.getElementById("snapshot");
	const context = snapshot.getContext("2d");
	const snapButton = document.getElementById("snap-button");

	navigator.mediaDevices.getUserMedia({video: true})
	.then((stream) => {
		cam.srcObject = stream;
		cam.addEventListener("playing", () => {
			const camStyles = getComputedStyle(cam);
			snapshot.height = parseInt(camStyles.height);
			snapshot.width = parseInt(camStyles.width);
			cam.hidden = true;
			function step() {
				context.drawImage(cam, 0, 0);
				requestAnimationFrame(step);
			}
			requestAnimationFrame(step);
		});
	});

	snapButton.onclick = () => {
		const overlays = document.querySelectorAll(".snapshot__area .dragImg img");
		let overlaysToUpload = [];
		let data = new FormData;
		let xhr = new XMLHttpRequest();

		for (let i = overlays.length - 1; i >= 0; i--) {
			const imgPos = overlays[i].getBoundingClientRect();
			const containerPos = overlays[i].parentElement.parentElement.getBoundingClientRect();
			overlays[i].onmousedown = null;
			overlaysToUpload.push({
				"src": overlays[i].src,
				"posX": imgPos.left - containerPos.left,
				"posY": imgPos.top - containerPos.top,
				"width": imgPos.width,
				"height": imgPos.height,
				"rotation": parseFloat(overlays[i].style.transform.split("(")[1])
			});
			overlays[i].parentNode.parentNode.removeChild(overlays[i].parentNode);
		}
		data.append("overlays", JSON.stringify(overlaysToUpload));
		data.append("img", snapshot.toDataURL());
		xhr.open("POST", "ajax?action=snapshot", true);
		xhr.send(data);
		xhr.onreadystatechange = () => {
			if (xhr.readyState === 4 && xhr.status === 200) {
				showSnaphots(snapshotContainer);
			}
		}
	};
}

/*-----------------Drag And Drop-----------------*/

let	isDragAllowed = function() {
	let div = document.createElement('div');
	return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
}();

let add_photo = new FormData();
let dropArea = document.querySelector(".add_photo__form .add_photo__area");

function addPhoto(file, filename = null) {
	if (filename) {
		add_photo.append("img", file, filename);
	} else {
		add_photo.append("img", file);
	}
	dropArea.classList.add("highlight");
}

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
	data.append("action", "addPost");
	data.set("img", add_photo.get("img"));
	let xhr = new XMLHttpRequest();

	xhr.open('POST', 'ajax', true);
	xhr.send(data);
	xhr.onreadystatechange = function() {
		if (xhr.readyState === 4 && xhr.status === 200) {
			location.replace(location.origin);
		}
	}
};

function dragImgOnCanvas(e) {
	if (!e.currentTarget.classList.contains("dragImg"))
		return ;
	let img = e.currentTarget;
	let cords = getCords(img);
	let shiftX = e.pageX - cords.left;
	let shiftY = e.pageY - cords.top;
	let parentPos = document.querySelector(".snapshot__area").getBoundingClientRect();

	function moveAt(e) {
		img.style.left = e.pageX - parentPos.left - window.scrollX - shiftX + "px";
		img.style.top = e.pageY - parentPos.top - window.scrollY - shiftY + "px";
	}
	moveAt(e);

	document.addEventListener("mousemove", moveAt);
	img.addEventListener("mouseup", () => {
		document.removeEventListener("mousemove", moveAt);
	});
	img.ondragstart = () => {return false};

	function getCords(elem) {
		let box = elem.getBoundingClientRect();
		return {
			left: box.left + pageXOffset,
			top: box.top + pageYOffset
		};
	}
}

function appendSticker(e) {
	let container = document.querySelector(".snapshot__area");
	let wrapper = document.createElement("div");
	const resizerPos = [
		"top-left",
		"top-right",
		"bottom-left",
		"bottom-right"
	];

	wrapper.classList.add("dragImg");
	for (let i = 0; i < 4; i++) {
		elem = document.createElement("div");
		elem.classList.add("resizer");
		elem.classList.add(resizerPos[i]);
		wrapper.appendChild(elem);
		switchOnResizing(elem);
	}
	elem = document.createElement("div");
	elem.classList.add("rotator");
	wrapper.appendChild(elem);
	switchOnRotation(elem);

	let copy = document.createElement("img");
	copy.src = e.target.src;
	copy.alt = e.target.alt;
	wrapper.appendChild(copy);
	wrapper.onmousedown = dragImgOnCanvas;

	elem = document.createElement("i");
	elem.classList.add("removeSticker", "fas", "fa-times");
	wrapper.appendChild(elem);
	elem.addEventListener("click", (e) => {
		e.target.parentNode.parentNode.removeChild(e.target.parentNode);
	});
	container.appendChild(wrapper);
}

elem = document.querySelectorAll(".sticker");
for (let i = elem.length - 1; i >= 0; i--) {
	elem[i].onclick = appendSticker;
}

elem = document.querySelector(".addSticker");
if (elem) {
	function handleStickerDrop(e) {
		let dt = e.dataTransfer;
		let files = dt.files;
		console.log(files);
	}

	['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
		elem.addEventListener(eventName, (e) => {
			e.stopPropagation();
			e.preventDefault();
		}, false)
	});
	['dragenter', 'dragover'].forEach(eventName => {
		elem.addEventListener(eventName, dragOver, false)
	});
	['dragleave', 'drop'].forEach(eventName => {
		elem.addEventListener(eventName, dragLeave, false)
	});
	elem.addEventListener('drop', handleStickerDrop, false);
}

/*-----------------Resize Sticker-----------------*/

function switchOnResizing(elem) {
	const minimum_size = 20;
	let original_width = 0;
	let original_height = 0;
	let original_x = 0;
	let original_y = 0;
	let original_mouse_x = 0;
	let original_mouse_y = 0;
	let toResize = elem.parentNode;

	elem.addEventListener('mousedown', (e) => {
		e.preventDefault();
		e.stopPropagation();
		let toResizeStyles = getComputedStyle(toResize);
		original_width = parseFloat(toResizeStyles.width);
		original_height = parseFloat(toResizeStyles.height);
		original_x = parseFloat(toResizeStyles.left);
		original_y = parseFloat(toResizeStyles.top);
		original_mouse_x = e.pageX;
		original_mouse_y = e.pageY;
		window.addEventListener("mousemove", resize);
		window.addEventListener("mouseup", () => {
			window.removeEventListener("mousemove", resize);
		});
	});

	function resize(e) {
		e.preventDefault();
		if (elem.classList.contains('bottom-right')) {
			const width = original_width + (e.pageX - original_mouse_x);
			const height = original_height + (e.pageY - original_mouse_y);
			if (width > minimum_size) {
				toResize.style.width = width + 'px'
			}
			if (height > minimum_size) {
				toResize.style.height = height + 'px'
			}
		}
		else if (elem.classList.contains('bottom-left')) {
			const height = original_height + (e.pageY - original_mouse_y);
			const width = original_width - (e.pageX - original_mouse_x);
			if (height > minimum_size) {
				toResize.style.height = height + 'px'
			}
			if (width > minimum_size) {
				toResize.style.width = width + 'px';
				toResize.style.left = original_x + (e.pageX - original_mouse_x) + 'px'
			}
		}
		else if (elem.classList.contains('top-right')) {
			const width = original_width + (e.pageX - original_mouse_x);
			const height = original_height - (e.pageY - original_mouse_y);
			if (width > minimum_size) {
				toResize.style.width = width + 'px'
			}
			if (height > minimum_size) {
				toResize.style.height = height + 'px';
				toResize.style.top = original_y + (e.pageY - original_mouse_y) + 'px'
			}
		}
		else if (elem.classList.contains("top-left")) {
			const width = original_width - (e.pageX - original_mouse_x);
			const height = original_height - (e.pageY - original_mouse_y);
			if (width > minimum_size) {
				toResize.style.width = width + 'px';
				toResize.style.left = original_x + (e.pageX - original_mouse_x) + 'px'
			}
			if (height > minimum_size) {
				toResize.style.height = height + 'px';
				toResize.style.top = original_y + (e.pageY - original_mouse_y) + 'px'
			}
		}
	}
}

function switchOnRotation(elem) {
	let orig_x, orig_y;

	elem.addEventListener("mousedown", (e) => {
		e.preventDefault();
		e.stopPropagation();
		orig_x = e.pageX;
		orig_y = e.pageY;
		window.addEventListener("mousemove", rotate);
		window.addEventListener("mouseup", () => {
			window.removeEventListener("mousemove", rotate);
		});
	});

	function rotate(e) {
		let elemPos = getComputedStyle(elem.parentNode);
		e.preventDefault();
		let center_x = (parseFloat(elemPos.left) + parseFloat(elemPos.right)) / 2,
			center_y = (parseFloat(elemPos.top) + parseFloat(elemPos.bottom)) / 2,
			mouse_x = e.pageX - parseFloat(elemPos.left),
			mouse_y = e.pageY - parseFloat(elemPos.top),
			radians = Math.atan2(mouse_x - center_x, mouse_y - center_y),
			degree = Math.round((radians * (180 / Math.PI) * -1) + 100);
		elem.nextElementSibling.style.transform = "rotate(" + (degree + 170) + "deg)";
	}
}
