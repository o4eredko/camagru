<?php


namespace application\controllers;
use application\core\Controller;
use application\core\View;

class AjaxController extends Controller {

	public function requestAction() {
		if (isset($_REQUEST["action"])) {
			$actionName = $_REQUEST["action"];
		} else {
			$actionName = "";
		}
		unset($_REQUEST["action"]);
		$params = $_REQUEST;
		if ($this->model && method_exists($this->model, $actionName)) {
			$this->model->$actionName($params);
		} else {
			View::errorCode(404);
		}
	}

}