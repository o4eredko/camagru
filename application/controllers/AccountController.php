<?php
/**
 * Created by PhpStorm.
 * User: Scur
 * Date: 28.04.2019
 * Time: 21:14
 */

namespace application\controllers;
use application\core\Controller;

class AccountController extends Controller {

	public function loginAction() {
		$this->view->render("Login Page");
	}

	public function registerAction() {
		$this->view->render("Registration Page");
	}

}