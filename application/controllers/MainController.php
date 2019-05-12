<?php


namespace application\controllers;
use application\core\Controller;

class MainController extends Controller {

	public function indexAction() {
        $data = $this->model->getUserData();
        $data["posts"] = $this->model->getPosts();
        $data["passChangeAllowed"] = ($this->model->checkToken($_GET) &&
			isset($_GET["action"]) && $_GET["action"] == "forgot");
        $data["likedPosts"] = $this->model->getLikedPosts();
        $this->view->render($data);
    }

	public function logoutAction() {
		if (!empty($_SESSION["user"])) {
			unset($_SESSION["user"]);
		}
		header("Location: /");
	}

	public function add_photoAction() {
    	if (!isset($_SESSION["user"])) {
    		header("Location: /");
		}
		$data = $this->model->getUserData();
		$this->view->render($data);
	}

	public function postAction() {
		if (!isset($_GET["id"]) || !isset($_SESSION["user"]))
			header("Location: /");
		$data = $this->model->getUserData();
		$data["post"] = $this->model->getPost($_GET["id"]);
		$data["likedPosts"] = $this->model->getLikedPosts();
		$this->view->render($data);
	}

	public function profileAction() {
    	if (!isset($_SESSION["user"]))
    		header("Location: /");
    	$data = $this->model->getUserData();
    	$data["posts"] = $this->model->getPosts();
		$data["likedPosts"] = $this->model->getLikedPosts();
		$this->view->render($data);
	}

}