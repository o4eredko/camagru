<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Camagru</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
                <div class="col-6">
                    <nav class="main-menu">
                        <ul class="main-menu__list">
                            <li class="main-menu__item"><a href="/">Main Page</a></li>
                            <li class="main-menu__item"><a href="/profile">My Profile</a></li>
                            <li class="main-menu__item"><a href="/add_photo">Add photo</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-3 d-flex justify-content-end account">
                    <?php if (empty($_SESSION["user"])): ?>
                        <a href="#" data-toggle-id="login" class="button account__buttons login__button"><i class="fas fa-user-alt"></i>Log in</a>
                        <a href="#" data-toggle-id="registration" class="button account__buttons signup__button"><i class="fas fa-lock"></i>Sign up</a>
                    <?php else: ?>
                        <a href="logout" class="button account__buttons logout__button"><i class="fas fa-sign-out-alt"></i>Log out</a>
                        <a href="#" data-toggle-id="settings" class="button account__buttons settings__button"><i class="fas fa-cog"></i>Settings</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
	<?= $content ?>
    <script src="js/main.js" defer></script>
    <footer>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-6">© 2019. All rights reserved.</div>
                <div class="col-6 text-right">Created by <b> yochered</b></div>
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
                        <div class="col-6">
                            <input type="text" name="username" placeholder="Login" required>
                            <label hidden for="username">User with this username already exists</label>
                        </div>
                        <div class="col-6">
                            <input type="email" name="email" placeholder="Email" required>
                            <label hidden for="email">User with this email already exists</label>
                        </div>
                        <div class="col-6">
                            <input type="password" name="pass" placeholder="Password" required>
                            <label hidden for="pass">Password must be at least of length: 6</label>
                        </div>
                        <div class="col-6">
                            <input type="password" name="repass" placeholder="Repeat Password" required>
                            <label hidden for="repass">Password and Repassword must be identical</label>
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
                        <div class="col-6">
                            <input type="text" name="username" placeholder="Login" required>
                            <label hidden for="username">Wrong username</label>
                        </div>
                        <div class="col-6">
                            <input type="password" name="pass" placeholder="Password" required>
                            <label hidden for="pass">Wrong password</label>
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
                        <div class="col-6">
                            <input type="password" name="pass" placeholder="New Password" required>
                            <label hidden for="pass">Password must be at least of length: 6</label>
                        </div>
                        <div class="col-6">
                            <input type="password" name="repass" placeholder="Repeat New Password" required>
                            <label hidden for="repass">Password and Repassword must be identical</label>
                        </div>
                        <?php else: ?>
                        <div class="col-6">
                            <input type="email" name="email" placeholder="Email" required>
                            <label hidden for="username">Wrong email</label>
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
                        <div class="col-6">
                            <input type="text" value="<?= $username ?>" name="newuser" placeholder="Login" required>
                            <label hidden for="newuser">User with this username already exists</label>
                        </div>
                        <div class="col-6">
                            <input type="email" value="<?= $email ?>" name="newemail" placeholder="Email" required>
                            <label hidden for="email">User with this email already exists</label>
                        </div>
                        <div class="col-6">
                            <input type="password" name="oldpass" placeholder="Old Password" required>
                            <label hidden for="oldpass">Wrong password</label>
                        </div>
                        <div class="col-6">
                            <input type="password" name="pass" placeholder="New Password">
                            <label hidden for="pass">Password must be at least of length: 6</label>
                        </div>
                        <div class="col-6">
                            <input type="password" name="repass" placeholder="Repeat New Password">
                            <label hidden for="repass">Password and Repassword must be identical</label>
                        </div>
                        <div class="col-6">
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