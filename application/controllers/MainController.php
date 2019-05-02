<?php


namespace application\controllers;
use application\core\Controller;

class MainController extends Controller {

    public function indexAction() {
        $data = $this->model->getUserData();
        $data["posts"] = $this->model->getPosts();
        $this->view->render($data);
    }

	public function logoutAction() {
		if (!empty($_SESSION["user"])) {
			unset($_SESSION["user"]);
		}
		header("Location: /" . BASE_DIR);
	}

	public function add_photoAction() {
		$data = $this->model->getUserData();
		$this->view->render($data);
	}

}