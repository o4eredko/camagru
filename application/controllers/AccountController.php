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
		$res = $this->model->login();
		echo $res;
	}

	public function registerAction() {
		$this->view->render("Registration Page");
	}

}