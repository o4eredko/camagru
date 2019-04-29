<?php

namespace application\models;
use application\core\Model;

class Account extends Model {

	public function login() {
		$res = ["wrong_user" => false, "wrong_pass" => false];
		if (!empty($_POST["username"]) && !empty($_POST["pass"])) {
			$params = ["username" => $_POST["username"]];
		} else {
			$res["wrong_user"] = true;
			$res["wrong_pass"] = true;
			return json_encode($res);
		}
		$stm = $this->db->query("SELECT pass FROM `users` WHERE username = :username", $params);
		if (!$stm->rowCount()) {
			$res["wrong_user"] = true;
		} else if ($stm->fetchColumn() != hash("whirlpool", $_POST["pass"])) {
			$res["wrong_pass"] = true;
		} else {

		}
		return json_encode($res);
	}

}