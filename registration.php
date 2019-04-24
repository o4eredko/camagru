<?php

require_once 'connect.php';

if ($_POST["pass"] === $_POST["repass"]) {
	$allowed = array("username", "name", "surname", "email", "pass");
	$sql = "INSERT INTO users SET " . pdoSet($allowed, $values);
	$values["pass"] = hash("whirlpool", $values["pass"]);
	$stm = $pdo->prepare($sql);
	$stm->execute($values);
}
