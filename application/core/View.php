<?php
/**
 * Created by PhpStorm.
 * User: Scur
 * Date: 28.04.2019
 * Time: 21:42
 */

namespace application\core;

class View {

	public $route;
	public $path;
	public $layout = "default";

	public function __construct($route) {
		$this->route = $route;
		$this->path = $route["controller"] . "/" . $route["action"];
//		echo $this->path;
	}

	public function render($title, $vars = []) {
		ob_start();
		if (file_exists("application/views/" . $this->path . ".php")) {
			require_once "application/views/" . $this->path . ".php";
		} else {
			echo "View doesn't exists";
		}
		$content = ob_get_clean();
		if (file_exists("application/views/layouts/" . $this->layout . ".php")) {
			require_once "application/views/layouts/" . $this->layout . ".php";
		} else {
			echo "Layout doesn't exists";
		}
	}

}