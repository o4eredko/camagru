<?php
require_once 'config/database.php';

try {
	$dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
} catch(PDOException $e) {
	echo "Error!: " . $e->getMessage();
	die();
}

?>