<header>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-3">
                <a href="index.php" class="logo">Camagru</a>
            </div>
            <div class="col-6">
                <nav class="main-menu">
                    <ul class="main-menu__list">
                        <li class="main-menu__item"><a href="#">Main Page</a></li>
                        <li class="main-menu__item"><a href="#">Discover</a></li>
                        <li class="main-menu__item"><a href="#">My Profile</a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-3 d-flex justify-content-end account">
                <?php
                if (empty($_SESSION["user"])): ?>
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
<section id="title">
    <div class="container">
        <div class="row">
            <div class="col">
                <h1 class="main__title">Your Best Social Network</h1>
                <p class="main__subtitle">Donec in rhoncus tortor. Sed tristique auctor ligula vel viverra</p>
                <a href="#" class="button signup__button" data-toggle-id="registration"><i class="fas fa-lock"></i>Sign up now</a>
            </div>
        </div>
    </div>
</section>
<section id="main">
    <div class="container">
        <div class="row">
            <div class="col-3">
                <div class="post">
                    <img src="img/p2.jpg" alt="" class="post__img">
                    <div class="post__content">
                        <h4 class="post__title">Lorem ipsum</h4>
                        <hr>
                        <p class="post__owner">
                            by <a href="#">Hoang Nguyen</a>
                        </p>
                    </div>
                    <div class="post__stat">

                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="post">
                    <img src="img/p2.jpg" alt="" class="post__img">
                    <div class="post__content">
                        <h4 class="post__title">Lorem ipsum</h4>
                        <hr>
                        <p class="post__owner">
                            by <a href="#">Hoang Nguyen</a>
                        </p>
                    </div>
                    <div class="post__stat">

                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="post">
                    <img src="img/p2.jpg" alt="" class="post__img">
                    <div class="post__content">
                        <h4 class="post__title">Lorem ipsum</h4>
                        <hr>
                        <p class="post__owner">
                            by <a href="#">Hoang Nguyen</a>
                        </p>
                    </div>
                    <div class="post__stat">

                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="post">
                    <img src="img/p2.jpg" alt="" class="post__img">
                    <div class="post__content">
                        <h4 class="post__title">Lorem ipsum</h4>
                        <hr>
                        <p class="post__owner">
                            by <a href="#">Hoang Nguyen</a>
                        </p>
                    </div>
                    <div class="post__stat">

                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="post">
                    <img src="img/p2.jpg" alt="" class="post__img">
                    <div class="post__content">
                        <h4 class="post__title">Lorem ipsum</h4>
                        <hr>
                        <p class="post__owner">
                            by <a href="#">Hoang Nguyen</a>
                        </p>
                    </div>
                    <div class="post__stat">

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<footer>
    <div class="container">

    </div>
</footer>
<div class="modal" id="registration">
    <div class="modal__content">
        <div class="container">
            <form class="modal__form" id="registrationForm">
                <a href="#" class="close" data-toggle-id="registration"><i class="fas fa-times"></i></a>
                <h3>Register</h3>
                <div class="row modal__form_row">
                    <div class="col-6">
                        <input type="text" name="username" placeholder="Login" required>
                        <label hidden for="username">User with this username already exists</label>
                    </div>
                    <div class="col-6">
                        <input type="text" name="name" placeholder="First Name" required>
                    </div>
                    <div class="col-6">
                        <input type="text" name="surname" placeholder="Last Name" required>
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
                <button class="button">Log in</button>
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
                    <div class="col-6">
                        <input type="text" value="<?= $username ?>" name="newuser" placeholder="Login" required>
                        <label hidden for="newuser">User with this username already exists</label>
                    </div>
                    <div class="col-6">
                        <input type="text" value="<?= $name ?>" name="newname" placeholder="First Name" required>
                    </div>
                    <div class="col-6">
                        <input type="text" value="<?= $surname ?>" name="newsurname" placeholder="Last Name" required>
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
                        <input type="password" name="pass" placeholder="New Password" required>
                        <label hidden for="pass">Password must be at least of length: 6</label>
                    </div>
                    <div class="col-6">
                        <input type="password" name="repass" placeholder="Repeat Password" required>
                        <label hidden for="repass">Password and Repassword must be identical</label>
                    </div>
                </div>
                <img class="loading" src="img/loading.gif" alt="Camagru Loading">
                <p class="form__confirmation"></p>
                <button class="button">Change settings</button>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>