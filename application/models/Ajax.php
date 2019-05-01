<?php


namespace application\models;
use application\core\Model;

class Ajax extends Model {

	public function register($params) {
		$res = [];
		$res["user_exists"] = $this->usernameExists($params["username"]);
		$res['email_exists'] = $this->emailExists($params["email"]);

		if (!$res["user_exists"] && !$res["email_exists"]) {
			$params["token"] = $this->generateToken(10);
			$params["pass"] = hash("whirlpool", $params["pass"]);
			$allowed = array("username", "name", "surname", "email", "pass", "token");
			$sql = "INSERT INTO `users` SET " . $this->db->pdoSet($allowed, $values, $params);
			$this->db->query($sql, $values);
			$sql = "SELECT id FROM `users` WHERE username = ?";
			$id = $this->db->column($sql, [$params["username"]]);
//			$this->sendVerification($id, $params["email"], $params["token"]);
		}
		echo json_encode($res);
	}

	public function login($params) {
		$res = [];
		$res["wrong_user"] = !$this->usernameExists($params["username"]);
		$res["wrong_pass"] = !$this->passwordsEqual($params["username"], $params["pass"]);
		$res["email_confirmed"] = $this->emailConfirmed(NULL, $params["username"]);

		if (!$res["wrong_user"] && !$res["wrong_pass"] && $res["email_confirmed"]) {
			$_SESSION["user"] = $params["username"];
		}
		echo json_encode($res);
	}

	public function change($params) {
		$res = ["user_exists" => false, "email_exists" => false];
		$sql = "SELECT * FROM `users` WHERE username = ?";
		$user = $this->db->row($sql, [$_SESSION["user"]]);
		$res["wrong_oldpass"] = ($user["pass"] == hash("whirlpool", $params["oldpass"]));
		$res["email_confirmed"] = ($user["status"] == "confirmed");
		if ($params["newuser"] != $user["username"])
			$res["user_exists"] = $this->usernameExists($params["newuser"]);
		if ($params["newemail"] != $user["email"])
			$res["email_exists"] = $this->emailExists($params["newemail"]);
		if (!$res["wrong_oldpass"] && $res["email_confirmed"] &&
			!$res["user_exists"] && !$res["email_exists"]) {
			$sql = "UPDATE `users` SET username = ?, name = ?, surname = ?, email = ?, pass = ? WHERE id = ?";
			$this->db->query($sql, $params);
		}
		echo json_encode($res);
	}

	public function verificate() {
		if (!empty($_GET["id"]) && !empty($_GET["token"])) {
			$sql = "SELECT token FROM `users` WHERE id = ?";
			$res = $this->db->column($sql, [$_GET["id"]]);
			if ($res === $_GET["token"]) {
				$sql = "UPDATE `users` SET status = 'confirmed' WHERE id = ?";
				$this->db->query($sql, [$_GET["id"]]);
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

	private function usernameExists($username) {
		$sql = "SELECT id FROM `users` WHERE username = ?";
		$res = $this->db->row($sql, [$username]);
		if (!$res) return false;
		return (!$res ? false : true);
	}


	private function emailExists($email) {
		$sql = "SELECT id FROM `users` WHERE email = ?";
		$res = $this->db->row($sql, [$email]);
		if (!$res) return false;
		return (!$res ? false : true);
	}

	private function passwordsEqual($username, $newpass) {
		$sql = "SELECT pass FROM `users` WHERE username = ?";
		$res = $this->db->row($sql, [$username]);
		if (!$res) return false;
		return ($res["pass"] == hash("whirlpool", $newpass));
	}

	private function emailConfirmed($email, $username) {
		if ($email) {
			$sql = "SELECT status FROM `users` WHERE email = ?";
			$res = $this->db->row($sql, [$email]);
		} else {
			$sql = "SELECT status FROM `users` WHERE username = ?";
			$res = $this->db->row($sql, [$username]);
		}
		if (!$res) return false;
		return ($res["status"] == "confirmed");
	}

}
