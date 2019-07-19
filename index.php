<?php
session_start();

use application\core\Router;

$path = explode('/', trim($_SERVER["PHP_SELF"], '/'));

spl_autoload_register(function ($class) {
	$path = str_replace("\\", "/", $class . ".php");
	if (file_exists($path)) {
		require_once $path;
	}
});
$router = new Router();
