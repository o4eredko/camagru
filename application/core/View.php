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
	}

	public function render($title, $vars = []) {
	    extract($vars);
        $path = "application/views/" . $this->path . ".php";
        if (file_exists($path)) {
            ob_start();
            require_once $path;
            $content = ob_get_clean();
            require_once "application/views/layouts/" . $this->layout . ".php";
        }
    }

    public static function errorCode($code) {
	    http_response_code($code);
	    $path = "application/views/errors/" . $code . ".php";
	    if (file_exists($path)) {
            require_once $path;
        }
	    exit;
    }

    public function redirect($url) {
	    header("Location: $url");
	    exit;
    }

}