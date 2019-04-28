<?php

require_once 'config/database.php';

try {
	$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, $DB_OPT);
} catch(PDOException $e) {
	die("Connection Error");
}
	
function pdoSet($allowed, &$values, $source = array()) {
	$set = '';
	$values = array();
	if (!$source) $source = &$_POST;
	foreach ($allowed as $field) {
		if (isset($source[$field])) {
			$set.="`".str_replace("`","``",$field)."`". "=:$field, ";
			$values[$field] = $source[$field];
		}
	}
	return substr($set, 0, -2); 
}
