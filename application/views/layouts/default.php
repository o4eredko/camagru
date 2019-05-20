<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Camagru</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="icon" type="image/png" href="https://img.icons8.com/clouds/100/000000/google-logo.png">
    <link rel="stylesheet" href="css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="css/helvetica_neue.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
</head>
<!DOCTYPE html>
<body>
    <header>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-3">
                    <a href="/" class="logo">Camagru</a>
                </div>
                <div class="d-none d-lg-block col-lg-5" id="switch-menu">
                    <nav class="main-menu">
                        <ul class="main-menu__list">
                            <li class="main-menu__item"><a href="/">Main Page</a></li>
                            <li class="main-menu__item"><a href="/profile">My Profile</a></li>
                            <li class="main-menu__item"><a href="/add_photo">Add photo</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-7 col-lg-4 d-flex justify-content-end account">
                    <?php if (empty($_SESSION["user"])): ?>
                        <a href="#" data-toggle-id="login" class="button account__buttons login__button"><i class="fas fa-user-alt"></i>Log in</a>
                        <a href="#" data-toggle-id="registration" class="button account__buttons signup__button"><i class="fas fa-lock"></i>Sign up</a>
                    <?php else: ?>
                        <a href="logout" class="button account__buttons logout__button"><i class="fas fa-sign-out-alt"></i>Log out</a>
                        <a href="#" data-toggle-id="settings" class="button account__buttons settings__button"><i class="fas fa-cog"></i>Settings</a>
                    <?php endif; ?>
                </div>
				<div class="col-1 d-block d-lg-none text-right">
					<a href="#" data-toggle-id="switch-menu"><i class="fas fa-bars hamburger" ></i></a>
				</div>
            </div>
        </div>
    </header>
	<?= $content ?>
    <script src="js/main.js" defer></script>
    <footer>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-sm-6">© 2019. All rights reserved.</div>
                <div class="col-12 col-sm-6 text-right">Created by <b> yochered</b></div>
            </div>
        </div>
    </footer>
    <div class="modal" id="registration">
        <div class="modal__content">
            <div class="container">
                <form class="modal__form" id="registrationForm">
                    <a href="#" class="close" data-toggle-id="registration"><i class="fas fa-times"></i></a>
                    <h3>Register</h3>
                    <div class="row modal__form_row">
                        <input type="hidden" name="csrf" value="<?= $_SESSION["csrf"] ?>">
                        <div class="col-sm-6">
                            <label>
								<input type="text" name="username" placeholder="Login" required>
								<span class="label__error" hidden>User with this username already exists</span>
							</label>
                        </div>
                        <div class="col-sm-6">
                            <label>
								<input type="email" name="email" placeholder="Email" required>
								<span class="label__error" hidden>User with this email already exists</span>
							</label>
                        </div>
						<div class="col-sm-6">
							<label>
								<input type="password" name="pass" placeholder="Password">
								<span class="label__error" hidden>Password must be at least of length: 6</span>
							</label>
						</div>
						<div class="col-sm-6">
							<label>
								<input type="password" name="repass" placeholder="Repeat Password">
								<span class="label__error" hidden>Password and Re-password must be identical</span>
							</label>
						</div>
                    </div>
                    <img class="loading" src="img/loading.gif" alt="Camagru Loading">
                    <p class="form__confirmation" hidden>
                        Registration is almost done. You should confirm your e-mail.
                    </p>
                    <button class="button">Sign Up</button>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="login">
        <div class="modal__content">
            <div class="container">
                <form class="modal__form" id="loginForm">
                    <a href="#" class="close" data-toggle-id="login"><i class="fas fa-times"></i></a>
                    <h3>Log In</h3>
                    <div class="row modal__form_row">
                        <input type="hidden" name="csrf" value="<?= $_SESSION["csrf"] ?>">
                        <div class="col-sm-6">
                            <label>
								<input type="text" name="username" placeholder="Login" required>
								<span class="label__error" hidden>Wrong username</span>
							</label>
                        </div>
                        <div class="col-sm-6">
							<label>
								<input type="password" name="pass" placeholder="Old Password" required>
								<span class="label__error" hidden>Wrong password</span>
							</label>
                        </div>
                    </div>
                    <img class="loading" src="img/loading.gif" alt="Camagru Loading">
                    <p class="form__confirmation" hidden>You have to confirm your e-mail first</p>
                    <a href="#" class="forgot-pass__link" data-toggle-id="forgot">Forgot your password?</a>
                    <button class="button">Log in</button>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="forgot">
        <div class="modal__content">
            <div class="container">
                <form class="modal__form" id="forgotForm">
                    <a href="#" class="close" data-toggle-id="forgot"><i class="fas fa-times"></i></a>
                    <h3>Forgot your password?</h3>
                    <div class="row modal__form_row">
                        <input type="hidden" name="csrf" value="<?= $_SESSION["csrf"] ?>">
                        <?php if ($passChangeAllowed): ?>
                        <div class="col-sm-6">
							<label>
								<input type="password" name="pass" placeholder="New Password">
								<span class="label__error" hidden>Password must be at least of length: 6</span>
							</label>
                        </div>
                        <div class="col-sm-6">
							<label>
								<input type="password" name="repass" placeholder="Repeat New Password">
								<span class="label__error" hidden>Password and Re-password must be identical</span>
							</label>
                        </div>
                        <?php else: ?>
                        <div class="col-sm-6">
                            <label>
								<input type="email" name="email" placeholder="Email" required>
								<span class="label__error" hidden>Wrong email</span>
							</label>
                        </div>
                        <?php endif; ?>
                    </div>
                    <img class="loading" src="img/loading.gif" alt="Camagru Loading">
                    <p class="form__confirmation" hidden>You have to confirm your e-mail first</p>
                    <button class="button">Reset password</button>
                </form>
            </div>
        </div>
    </div>
	<?php if (!empty($_SESSION["user"])): ?>
    <div class="modal" id="settings">
        <div class="modal__content">
            <div class="container">
                <form class="modal__form" id="changeForm">
                    <a href="#" class="close" data-toggle-id="settings"><i class="fas fa-times"></i></a>
                    <h3>Change your settings</h3>
                    <div class="row modal__form_row">
                        <input type="hidden" name="csrf" value="<?= $_SESSION["csrf"] ?>">
                        <div class="col-sm-6">
                            <label>
								<input type="text" value="<?= $username ?>" name="newuser" placeholder="Login" required>
								<span class="label__error" hidden>User with this username already exists</span>
							</label>
                        </div>
                        <div class="col-sm-6">
                            <label>
								<input type="email" value="<?= $email ?>" name="newemail" placeholder="Email" required>
								<span class="label__error" hidden>User with this email already exists</span>
							</label>
                        </div>
                        <div class="col-sm-6">
                            <label>
								<input type="password" name="oldpass" placeholder="Old Password" required>
								<span class="label__error" hidden>Wrong password</span>
							</label>
                        </div>
                        <div class="col-sm-6">
                            <label>
								<input type="password" name="pass" placeholder="New Password">
								<span class="label__error" hidden>Password must be at least of length: 6</span>
							</label>
                        </div>
                        <div class="col-sm-6">
                            <label>
								<input type="password" name="repass" placeholder="Repeat New Password">
								<span class="label__error" hidden>Password and Re-password must be identical</span>
							</label>
                        </div>
                        <div class="col-sm-6">
                            <label class="switch">
                            <?php if ($notifications): ?>
                                <input type="checkbox" name="notifications" checked class="switch__toggle">
                            <?php else: ?>
                                <input type="checkbox" name="notifications">
							<?php endif; ?>
                                <span class="switch__toggle"></span>
                            </label>
                        </div>
                    </div>
                    <img class="loading" src="img/loading.gif" alt="Camagru Loading">
                    <p class="form__confirmation" hidden>You have to confirm your email first</p>
                    <button class="button">Change settings</button>
                </form>
            </div>
        </div>
    </div>
	<?php endif; ?>
</body>
</html>