<?php


namespace application\models;
use application\core\Model;

class Ajax extends Model {

	public function register($params) {
		$res = ['user_exists' => false, 'email_exists' => false];

		$stm1 = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
		$stm1->execute([$params["username"]]);
		$stm2 = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
		$stm2->execute([$params["email"]]);
		if ($stm1->rowCount() > 0) {
			$res["user_exists"] = true;
		} else if ($stm2->rowCount() > 0) {
			$res["email_exists"] = true;
		} else {
			$allowed = array("username", "name", "surname", "email", "pass");
			$sql = "INSERT INTO users SET " . $this->db->pdoSet($allowed, $values);
			$values["pass"] = hash("whirlpool", $values["pass"]);
			$stm = $this->pdo->prepare($sql);
			$stm->execute($values);
			mail($params["email"], "Confirm your E-mail", "You have to confirm you e-mail");
		}
		echo json_encode($res);
	}

	public function login($params) {
		$res = ["wrong_user" => false, "wrong_pass" => false];

		$stm = $this->pdo->prepare("SELECT pass FROM `users` WHERE username = ?");
		$stm->execute([$params["username"]]);
		if (!$stm->rowCount()) {
			$res["wrong_user"] = true;
		} else if ($stm->fetchColumn() != hash("whirlpool", $params["pass"])) {
			$res["wrong_pass"] = true;
		} else {

		}

		echo json_encode($res);
	}

}
