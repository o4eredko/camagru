<?php

require_once 'connect.php';

$res = ["wrong_user" => false, "wrong_pass" => false];

$stm = $pdo->prepare("SELECT pass FROM `users` WHERE username = ?");
$stm->execute([$_POST["username"]]);
if (!$stm->rowCount()) {
	$res["wrong_user"] = true;
} else if ($stm->fetchColumn() != hash("whirlpool", $_POST["pass"])) {
	$res["wrong_pass"] = true;
} else {

}

echo json_encode($res);
