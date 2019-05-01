<?php


namespace application\controllers;
use application\core\Controller;
use application\core\View;
use application\lib\Db;

class MainController extends Controller {

    public function indexAction() {
        $db = new Db;
        $data = $this->model->getUserData();
        $this->view->render($data);
    }

	public function logoutAction() {
		session_start();
		if (!empty($_SESSION["user"])) {
			unset($_SESSION["user"]);
		}
		header("Location: /" . BASE_DIR);
	}

}