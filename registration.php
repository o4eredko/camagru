<?php

require_once 'connect.php';

header('Content-Type: text/json');
$res = ['user_exists' => false, 'email_exists' => false];

$stm1 = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stm1->execute([$_POST["username"]]);
$stm2 = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stm2->execute([$_POST["email"]]);
if ($stm1->rowCount() > 0) {
	$res["user_exists"] = true;
} else if ($stm2->rowCount() > 0) {
	$res["email_exists"] = true;
} else {
	$allowed = array("username", "name", "surname", "email", "pass");
	$sql = "INSERT INTO users SET " . pdoSet($allowed, $values);
	$values["pass"] = hash("whirlpool", $values["pass"]);
	$stm = $pdo->prepare($sql);
	$stm->execute($values);
	mail($_POST["email"], "Confirm your E-mail", "You have to confirm you e-mail");
}
echo json_encode($res);
