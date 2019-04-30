<?php


namespace application\models;
use application\core\Model;

class Ajax extends Model {

	public function register($params) {
		$res = ['user_exists' => false, 'email_exists' => false];
		$stm1 = $this->db->query("SELECT id FROM users WHERE username = :username",
			["username" => $params["username"]]);
		$stm2 = $this->db->query("SELECT id FROM users WHERE email = :email",
			["email" => $params["email"]]);

		if ($stm1->rowCount() > 0) {
			$res["user_exists"] = true;
		} else if ($stm2->rowCount() > 0) {
			$res["email_exists"] = true;
		} else {
			$params["token"] = $this->generateToken(10);
			$params["pass"] = hash("whirlpool", $params["pass"]);
			$allowed = array("username", "name", "surname", "email", "pass", "token");
			$sql = "INSERT INTO users SET " . $this->db->pdoSet($allowed, $values, $params);
			$this->db->query($sql, $values);
			$id = $this->db->column("SELECT id FROM users WHERE username = :username",
				["username" => $params["username"]]);
			$this->sendVerification($id, $params["email"], $params["token"]);
		}
		echo json_encode($res);
	}

	public function login($params) {
		$res = ["wrong_user" => false, "wrong_pass" => false, "email_confirmed"=> false];

		$stm = $this->pdo->prepare("SELECT pass, status FROM `users` WHERE username = ?");
		$stm->execute([$params["username"]]);
		if (!$stm->rowCount()) {
			$res["wrong_user"] = true;
		} else if ($stm->fetchColumn(0) != hash("whirlpool", $params["pass"])) {
			$res["wrong_pass"] = true;
		} else if ($stm->fetchColumn(1) == "confirmed") {
			$res["email_confirmed"] = true;
		} else {}
		echo json_encode($res);
	}

	public function verificate() {
		if (!empty($_GET["id"]) && !empty($_GET["token"])) {
			$res = $this->db->column("SELECT token FROM users WHERE id = :id", ["id" => $_GET["id"]]);
			if ($res === $_GET["token"]) {
				$this->db->query("UPDATE users SET status = :status WHERE id = :id",
					["status" => "confirmed", "id" => $_GET["id"]]);
				echo "Done";
			} else {
				echo "Wrong Token";
			}
		} else {
			echo "Wrong Token";
		}
	}

	private function sendVerification($id, $email, $token) {
		$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
			. "://" . $_SERVER["HTTP_HOST"] . "?action=verificate&id=$id&token=$token";
		$subject = "Confirm your E-mail address";
		$message = "You should confirm your e-mail address to access some features<br>
					To confirm go via this link:<br>
					<a href=$link>$link</a>";
		$headers = "Content-Type: text/html; charset=ISO-8859-1\n";
		mail($email, $subject, $message, $headers);
	}

	private function generateToken($length = 10) {
		$chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$charsLen = strlen($chars);
		$res = "";
		for ($i = 0; $i < $length; $i++) {
			$res .= $chars[rand(0, $charsLen - 1)];
		}
		return $res;
	}

}
