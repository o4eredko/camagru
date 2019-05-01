<?php
/**
 * Created by PhpStorm.
 * User: Scur
 * Date: 28.04.2019
 * Time: 19:03
 */

require_once "application/lib/Dev.php";
use application\core\Router;

$path = explode('/', trim($_SERVER["PHP_SELF"], '/'));
define('BASE_DIR', $path[0]);

spl_autoload_register(function ($class) {
	$path = str_replace("\\", "/", $class . ".php");
	if (file_exists($path)) {
		require_once $path;
	}
});
session_start();
$router = new Router();
