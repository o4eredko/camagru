<?php


namespace application\models;
use application\core\Model;
use PDO;

class Ajax extends Model {

	public function register($params) {
		$res = [];
		$res["user_exists"] = $this->usernameExists($params["username"]);
		$res['email_exists'] = $this->emailExists($params["email"]);

		if (!$res["user_exists"] && !$res["email_exists"]) {
			$params["token"] = $this->generateToken(10);
			$params["pass"] = hash("whirlpool", $params["pass"]);
			$allowed = array("username", "email", "pass", "token");
			$sql = "INSERT INTO `users` SET " . $this->db->pdoSet($allowed, $values, $params);
			$this->db->query($sql, $values);
			$sql = "SELECT * FROM `users` WHERE username = ?";
			$user = $this->db->row($sql, [$params["username"]]);
			$this->sendMail($user, "verify");
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
		$params["status"] = $user["status"];

		if (empty($params["pass"]))
			$params["pass"] = $params["oldpass"];
		$res["wrong_oldpass"] = ($user["pass"] != hash("whirlpool", $params["oldpass"]));
		$res["email_confirmed"] = ($user["status"] == "confirmed");
		if ($params["newuser"] != $user["username"])
			$res["user_exists"] = $this->usernameExists($params["newuser"]);
		if ($params["newemail"] != $user["email"]) {
			$res["email_exists"] = $this->emailExists($params["newemail"]);
			$params["status"] = "unconfirmed";
		}

		if (!$res["wrong_oldpass"] && $res["email_confirmed"] &&
			!$res["user_exists"] && !$res["email_exists"])
		{
			$sql = "UPDATE `users` SET username=:user, email=:email, pass=:pass, status=:status, token=:token WHERE id=:id";
			$values = [
				"user" => $params["newuser"],
				"email" => $params["newemail"],
				"pass" => hash("whirlpool", $params["pass"]),
				"status" => $params["status"],
				"id" => $user["id"]
			];
			$values["token"] = $user["token"];
			$this->db->query($sql, $values);
			$_SESSION["user"] = $params["newuser"];
			if ($values["status"] == "unconfirmed")
				$this->sendMail($values, "verify");
		}
		echo json_encode($res);
	}

	public function forget($params) {
		$res = [
			"wrong_email" => false,
			"email_confirmed" => true
		];
		if (!empty($params["email"])) {
			$sql = "SELECT * FROM `users` WHERE email=:email";
			$user = $this->db->row($sql, $params);
			$res["wrong_email"] = !$user;
			$res["email_confirmed"] = ($user && $user["status"] == "confirmed");

			if (!$res["wrong_email"] && $res["email_confirmed"]) {
				$this->sendMail($user, "change password");
			}

		} else if (!empty($params["pass"]) && !empty($params["id"])) {
			$sql = "UPDATE `users` SET pass=:pass WHERE id=:id";
			$this->db->query($sql, [
				"pass" => hash("whirlpool", $params["pass"]),
				"id" => $params["id"],
			]);
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

	public function addPhoto($params) {
		$img = $_FILES["img"]["tmp_name"];
		move_uploaded_file($img, "img/" . $_FILES["img"]["name"]);
		$params["img"] = "img/" . $_FILES["img"]["name"];
		$params["owner"] = $_SESSION["user"];
		$sql = "INSERT INTO `posts` SET owner=:owner, title=:title, description=:description, img=:img";
		$this->db->query($sql, $params);
		header("Location: /" . BASE_DIR);
	}

	private function sendMail($user, $opt) {
		$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"];
		if ($opt == "verify") {
			$link .= "?action=verificate&id=" . $user["id"] . "&token=" . $user["token"];
			$subject = "Confirm your E-mail address";
			$message = "Hello, " . $user["username"] . "! <br>
					Thank's for signing up to Camagru!<br>
					To get started, click the link below to confirm your account.<br>
					<a href=$link>Confirm your account</a>";
		} else if ($opt == "change password") {
			$link .= "?action=forgot&id=" . $user["id"] . "&token=" . $user["token"];
			$subject = "Change your password";
			$message = "Hello, " . $user["username"] . "! <br>
						It seems you have forgotten your password.<br>
						To create new password, go to this link: <br>
						<a href=$link>Change your password</a>";
		}
		$headers = "Content-Type: text/html; charset=ISO-8859-1\n";
		mail($user["email"], $subject, $message, $headers);
	}

	public function like($params) {
		if (!isset($params["post_id"]) || !isset($_SESSION["user"]) || !isset($params["liked"])) {
			echo "Error";
			return ;
		}
		if ($params["liked"] == "true") {
			$sql = "DELETE FROM `likes` WHERE owner=? AND post_id=?";
			$this->db->query($sql, [$_SESSION["user"], $params["post_id"]]);
		} else {
			$sql = "INSERT INTO `likes` SET owner=?, post_id=?";
			$this->db->query($sql, [$_SESSION["user"], $params["post_id"]]);
		}
	}

	public function comment($params) {
		if (!isset($params["post_id"]) || !isset($_SESSION["user"])) {
			echo "Error";
			return ;
		}
		$sql = "INSERT INTO `comments` SET owner=:owner, post_id=:post_id, text=:text";
		$values = [
			"owner" => $_SESSION["user"],
			"post_id" => $params["post_id"],
			"text" => $params["comment"]
		];
		$this->db->query($sql, $values);
	}

	public function delComment($params) {
		if (!isset($params["id"]))
			exit("Error");
		$sql = "DELETE FROM `comments` WHERE id=?";
		$this->db->query($sql, [$params["id"]]);
	}

	public function showComments($params) {
		$comments = $this->getComments($params["post_id"]);
		foreach ($comments as $comment): ?>
		<div class="post-comment__item">
			<img class="img-round" src="img/profile.png" alt="Camagru image">
			<div class="d-flex flex-column post-comment__block">
				<h4 class="post-comment__owner"><?= $comment["owner"] ?></h4>
				<p class="post-comment__text"><?= $comment["text"] ?></p>
				<?php if ($comment["owner"] == $_SESSION["user"]): ?>
					<i class="fas fa-trash-alt post-comment__del" data-comment-id="<?= $comment["id"] ?>"></i>
				<?php endif; ?>
			</div>
		</div>
		<?php endforeach;
	}

	public function snapshot($params) {
	    if (!isset($params["img"]))
	        exit("No image");
		$img = $params["img"];
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$fileName = "img/snapshot_tmp.png";
		$fileData = base64_decode($img);
		file_put_contents($fileName, $fileData);
		$bg = imagecreatefrompng($fileName);

		$overlays = json_decode($params["overlays"]);
		list($width, $height) = getimagesize($fileName);
		$out = imagecreatetruecolor($width, $height);
		imagecopyresampled($out, $bg, 0, 0, 0, 0, $width, $height, $width, $height);
		foreach ($overlays as $overlay) {
			$img = imagecreatefrompng($overlay->src);
			$posX = $overlay->posX;
			$posY = $overlay->posY;
			$overlayWidth = $overlay->width;
			$overlayHeight = $overlay->height;
			list($pngWidth, $pngHeight) = getimagesize($fileName);
			imagecopyresized($out, $img, $posX, $posY, 0, 0, $overlayWidth, $overlayHeight, $pngWidth, $pngHeight);
		}
		imagejpeg($out, 'img/out.jpg', 100);
		echo 'img/out.jpg';
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

	private function getComments($post_id) {
		$sql = "SELECT * FROM `comments` WHERE post_id=:post_id";
		$response = $this->db->query($sql, ["post_id" => $post_id]);
		if (!($res = $response->fetchAll(PDO::FETCH_ASSOC)))
			return [];
		return $res;
	}

}
