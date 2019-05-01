<?php
/**
 * Created by PhpStorm.
 * User: Scur
 * Date: 28.04.2019
 * Time: 21:29
 */

namespace application\core;

class Controller {

	public $route;
	public $view;
	public $model;

	public function __construct($route) {
		$this->route = $route;
		$this->view = new View($route);
		$this->model = $this->loadModel($route["controller"]);
	}

	private function loadModel($name) {
	    $path = "application\models\\" . ucfirst($name);
        if (class_exists($path)) {
	        return new $path;
        }
        return NULL;
    }

}
