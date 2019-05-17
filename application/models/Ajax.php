<?php


namespace application\models;
use application\core\Model;
use PDO;

class Ajax extends Model {

	public function register($params) {
		if (!isset($params["csrf"]) || !hash_equals($params["csrf"], $_SESSION["csrf"]))
			exit("Csrf attack!!!");
		$res = [];
		$res["user_exists"] = $this->usernameExists($params["username"]);
		$res['email_exists'] = $this->emailExists($params["email"]);

		if (!$res["user_exists"] && !$res["email_exists"]) {
			$params["token"] = $this->generateToken(10);
			$params["pass"] = hash("whirlpool", $params["pass"]);
			$sql = "INSERT INTO `users` SET username=:user, email=:email, pass=:pass, token=:token";
			$this->db->query($sql, [
			    "user" => $params["username"],
                "email" => $params["email"],
                "pass" => $params["pass"],
                "token" => $params["token"]
            ]);
			$sql = "SELECT * FROM `users` WHERE username = ?";
			$user = $this->db->row($sql, [$params["username"]]);
			$this->sendMail($user, "verify");
		}
		echo json_encode($res);
	}

	public function login($params) {
		if (!isset($params["csrf"]) || !hash_equals($params["csrf"], $_SESSION["csrf"]))
			exit("Csrf attack!!!");
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
		if (!isset($params["csrf"]) || !hash_equals($params["csrf"], $_SESSION["csrf"]))
			exit("Csrf attack!!!");
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
			$sql = "UPDATE `users` SET username=:user, email=:email, pass=:pass, status=:status, token=:token, notifications=:notif WHERE id=:id";
			$values = [
				"user" => $params["newuser"],
				"email" => $params["newemail"],
				"pass" => hash("whirlpool", $params["pass"]),
				"status" => $params["status"],
                "token" => $user["token"],
				"id" => $user["id"],
                "notif" => isset($params["notifications"]) ? 1 : 0
			];
			$this->db->query($sql, $values);
			$_SESSION["user"] = $params["newuser"];
			if ($values["status"] == "unconfirmed")
				$this->sendMail($values, "verify");
		}
		echo json_encode($res);
	}

	public function forget($params) {
		if (!isset($params["csrf"]) || !hash_equals($params["csrf"], $_SESSION["csrf"]))
			exit("Csrf attack!!!");
		$res = [
			"wrong_email" => false,
			"email_confirmed" => true
		];
		if (!empty($params["email"])) {
			$sql = "SELECT * FROM `users` WHERE email=?";
			$user = $this->db->row($sql, [$params["email"]]);
			$res["wrong_email"] = !$user;
			$res["email_confirmed"] = ($user && $user["status"] == "confirmed");

			if (!$res["wrong_email"] && $res["email_confirmed"]) {
				$this->sendMail($user, "change password");
			}

		} else if (!empty($params["pass"]) && !empty($params["id"])) {
			$sql = "UPDATE `users` SET pass=:pass WHERE id=:id";
			$this->db->query($sql, [
				"pass" => hash("whirlpool", $params["pass"]),
				"id" => $params["id"]
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

	public function addPost($params) {
		if (!isset($params["csrf"]) || !hash_equals($params["csrf"], $_SESSION["csrf"]))
			exit("Csrf attack!!!");
		$img = $_FILES["img"]["tmp_name"];
		$filename = $_FILES["img"]["name"];
		if (!preg_match("#^" . $_SESSION["user"] . "_.*$#", $filename)) {
			$filename = $_SESSION["user"] . "_" . $filename;
		}
		move_uploaded_file($img, "img/" . $filename);
		$params["img"] = "img/" . $filename;
		$params["owner"] = $_SESSION["user"];
		$sql = "INSERT INTO `posts` SET owner=:owner, title=:title, description=:description, img=:img";
		$this->db->query($sql, [
		    "owner" => $this->preventXss($params["owner"]),
		    "title" => $this->preventXss($params["title"]),
		    "description" => $this->preventXss($params["description"]),
		    "img" => $params["img"],
        ]);
	}

	public function delPost($params) {
		if (!isset($params["id"]) || !$_SESSION["user"])
			exit("Error");
		$post = $this->db->row("SELECT * FROM `posts` WHERE id=?", [$params["id"]]);
		if ($post["owner"] != $_SESSION["user"])
		    exit("You are a hacker ???? I'have bitten U!)");
		$this->db->query("DELETE FROM `posts` WHERE id=?", [$params["id"]]);
		$this->db->query("DELETE FROM `likes` WHERE post_id=?", [$params["id"]]);
		$this->db->query("DELETE FROM `comments` WHERE post_id=?", [$params["id"]]);
		unlink($post["img"]);
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
		if (!isset($params["csrf"]) || !hash_equals($params["csrf"], $_SESSION["csrf"]))
			exit("Csrf attack!!!");
		if (!isset($params["post_id"]) || !isset($_SESSION["user"]))
			exit("Error");
		$sql = "INSERT INTO `comments` SET owner=:owner, post_id=:post_id, text=:text";
		$values = [
			"owner" => $_SESSION["user"],
			"post_id" => $params["post_id"],
			"text" => $this->preventXss($params["comment"])
		];
		$this->db->query($sql, $values);

		$sql = "SELECT owner FROM `posts` WHERE id=?";
		$username = $this->db->column($sql, [$params["post_id"]]);
		$sql = "SELECT email, notifications FROM `users` WHERE username=?";
		$postOwner = $this->db->row($sql, [$username]);
		if ($_SESSION["user"] != $username && $postOwner["notifications"]) {
            $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
                . "://" . $_SERVER["HTTP_HOST"] . "/post?id=" . $params["post_id"];
            $headers = "Content-Type: text/html; charset=ISO-8859-1\n";
            $message = "
                Hello, " . $_SESSION["user"] . "<br>
                You recieved a new comment<br>
                To read it, go via this link:<br>
                <a href='$link'>Watch new comment</a>
            ";
            mail($postOwner["email"], "You recieved a comment on Camagru", $message, $headers);
        }
	}

	public function delElem($params) {
		if (!isset($params["id"]))
			exit("Error");
		if (!isset($params["csrf"]) || !hash_equals($params["csrf"], $_SESSION["csrf"]))
		    exit("Csrf attack");
		$table = $params["where"];
		$sql = "DELETE FROM $table WHERE id=?";
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
					<i class="fas fa-trash-alt post-comment__del"
                       data-comment-id="<?= $comment["id"] ?>"
                       data-csrf="<?= $_SESSION["csrf"] ?>">
                    </i>
				<?php endif; ?>
			</div>
		</div>
		<?php endforeach;
	}

	public function snapshot($params) {
	    if (!isset($_SESSION["user"]) || !isset($params["img"]))
	        exit("No image");
		$srcImg = $params["img"];
		$srcImg = str_replace('data:image/png;base64,', '', $srcImg);
		$srcImg = str_replace(' ', '+', $srcImg);
		$srcData = base64_decode($srcImg);
		$bg = imagecreatefromstring($srcData);
		list($width, $height) = getimagesizefromstring($srcData);
		$out = imagecreatetruecolor($width, $height);

		imagecopyresampled($out, $bg, 0, 0, 0, 0, $width, $height, $width, $height);
		$overlays = json_decode($params["overlays"]);
		foreach ($overlays as $overlay) {
			$destimg = imagecreatefrompng($overlay->src);
			$transColor = imagecolorallocatealpha($destimg, 255, 255, 255, 127);
			$rotatedImage = imagerotate($destimg, -$overlay->rotation, $transColor);
			imagesavealpha($rotatedImage, true);
			$rotatedImage = imagescale($rotatedImage, $overlay->width, $overlay->height);
			imagecopyresampled($out, $rotatedImage, $overlay->posX, $overlay->posY, 0, 0,
				$overlay->width, $overlay->height, $overlay->width, $overlay->height);
		}
		$imgName = "img/" . $_SESSION["user"] ."_" . $this->generateToken(8) . ".jpg";
		imagejpeg($out, $imgName, 100);
		$sql = "INSERT INTO `snapshots` SET owner=?, img=?";
		$this->db->query($sql, [$_SESSION["user"], $imgName]);
    }

    public function showSnapshots() {
	    if (!isset($_SESSION["user"]))
	        exit;
	    $sql = "SELECT id, img FROM `snapshots` WHERE owner=?";
	    $response = $this->db->query($sql, [$_SESSION["user"]]);
	    while (($snap = $response->fetch(PDO::FETCH_ASSOC))): ?>
        <div class="snapshot">
            <img src="<?= $snap["img"] ?>" alt="Camagru">
            <a href="#" class="upload button button-transparent"><i class="fas fa-cloud-upload-alt"></i>Upload snapshot</a>
            <a href="#" class="remove button button-transparent" data-remove-id="<?= $snap["id"] ?>" data-csrf="<?= $_SESSION["csrf"] ?>">
                <i class="fas fa-times"></i>Delete snapshot
            </a>
        </div>
        <?php endwhile;
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
		$res = $this->db->column($sql, [$username]);
		if (!$res) return false;
		return (!$res ? false : true);
	}


	private function emailExists($email) {
		$sql = "SELECT id FROM `users` WHERE email = ?";
		$res = $this->db->column($sql, [$email]);
		if (!$res) return false;
		return (!$res ? false : true);
	}

	private function passwordsEqual($username, $newpass) {
		$sql = "SELECT pass FROM `users` WHERE username = ?";
		$res = $this->db->column($sql, [$username]);
		if (!$res) return false;
		return ($res == hash("whirlpool", $newpass));
	}

	private function emailConfirmed($email, $username) {
		if ($email) {
			$sql = "SELECT status FROM `users` WHERE email = ?";
			$res = $this->db->column($sql, [$email]);
		} else {
			$sql = "SELECT status FROM `users` WHERE username = ?";
			$res = $this->db->column($sql, [$username]);
		}
		if (!$res) return false;
		return ($res == "confirmed");
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

	private function getComments($post_id) {
		$sql = "SELECT * FROM `comments` WHERE post_id=:post_id";
		$response = $this->db->query($sql, ["post_id" => $post_id]);
		if (!($res = $response->fetchAll(PDO::FETCH_ASSOC)))
			return [];
		return $res;
	}

	private function preventXss($input, $encoding = 'UTF-8') {
		return htmlentities($input, ENT_QUOTES | ENT_HTML5, $encoding);
	}

}
