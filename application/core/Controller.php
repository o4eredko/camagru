<?php
/**
 * Created by PhpStorm.
 * User: Scur
 * Date: 28.04.2019
 * Time: 21:29
 */

namespace application\core;
use application\core\View;

class Controller {

	public $route;
	public $view;

	public function __construct($route) {
		$this->route = $route;
		$this->view = new View($route);
	}

}