<?php


namespace application\controllers;
use application\core\Controller;

if (version_compare(PHP_VERSION, "7.0.0", ">=")) {
	$_SESSION["csrf"] = bin2hex(random_bytes(32));
} else {
	$_SESSION["csrf"] = bin2hex(openssl_random_pseudo_bytes(32));
}

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
		header("Location: /" . BASE_DIR);
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

}