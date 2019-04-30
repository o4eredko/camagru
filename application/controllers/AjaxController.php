<?php


namespace application\controllers;
use application\core\Controller;
use application\core\View;

class AjaxController extends Controller {

	public function requestAction() {
		$actionName = $_POST["action"];
		$params = $_POST;
		unset($_POST["action"]);
		if ($this->model && method_exists($this->model, $actionName)) {
			$this->model->$actionName($params);
		} else {
			View::errorCode(404);
		}
	}

}