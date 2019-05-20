<?php
/**
 * Created by PhpStorm.
 * User: Scur
 * Date: 01.05.2019
 * Time: 16:51
 */

namespace application\models;

use application\core\Model;
use PDO;

class Main extends Model {

	public function getUserData() {
		if (!empty($_SESSION["user"])) {
			$sql = "SELECT * FROM `users` WHERE username=?";
			$response = $this->db->query($sql, [$_SESSION["user"]]);
			$res = $response->fetch(PDO::FETCH_ASSOC);
			$res["username"] = $this->preventXss($res["username"]);
			$res["info"] = $this->preventXss($res["info"]);
			$res["about"] = $this->preventXss($res["about"]);
			$res["email"] = $this->preventXss($res["email"]);
			return $res;
		}
		return [];
	}

	public function getPosts($username = null) {
		if ($username) {
			$response = $this->db->query("SELECT * FROM `posts` WHERE owner=? ORDER BY creation_date DESC", [$username]);
		} else {
			$response = $this->pdo->query("SELECT * FROM `posts` ORDER BY creation_date DESC");
		}
		$posts = $response->fetchAll(PDO::FETCH_ASSOC);
		for ($i = 0; $i < count($posts); $i++) {
			$posts[$i]["likes"] = $this->getLikesNum($posts[$i]["id"]);
			$posts[$i]["comments"] = $this->getCommentsNum($posts[$i]["id"]);
		}
		return $posts;
	}

	public function checkToken($params) {
		if (empty($params["id"]) || empty($params["token"]))
			return false;
		$sql = "SELECT token FROM `users` WHERE id=?";
		$res = $this->db->row($sql, [$params["id"]]);
		if (!$res || $res["token"] != $params["token"])
			return false;
		return true;
	}

	public function getLikedPosts() {
		if (!isset($_SESSION["user"]))
			return [];
		$sql = "SELECT post_id FROM `likes` WHERE owner=?";
		$response = $this->db->query($sql, [$_SESSION["user"]]);
		$res = $response->fetchAll(PDO::FETCH_COLUMN);
		return $res;
	}

	private function getLikesNum($post_id) {
		$sql = "SELECT * FROM `likes` WHERE post_id=:post_id";
		$response = $this->db->query($sql, ["post_id" => $post_id]);
		return $response->rowCount();
	}

	private function getCommentsNum($post_id) {
		$sql = "SELECT * FROM `comments` WHERE post_id=:post_id";
		$response = $this->db->query($sql, ["post_id" => $post_id]);
		return $response->rowCount();
	}

	public function getPost($post_id) {
		$sql = "SELECT * FROM `posts` WHERE id=:post_id";
		$response = $this->db->query($sql, ["post_id" => $post_id]);
		$post = $response->fetch(PDO::FETCH_ASSOC);
		$post["likes"] = $this->getLikesNum($post["id"]);
		$post["comments"] = $this->getCommentsNum($post["id"]);
		return $post;
	}

	public function setupDatabase() {
		$tmpline = "";
		$lines = file("application/config/camagru.sql");
		foreach ($lines as $line) {
			if (substr($line, 0, 2) == '--' || $line == '')
				continue;
			$tmpline .= $line;
			if (substr(trim($line), -1, 1) == ';') {
				$this->pdo->exec($tmpline);
				$tmpline = "";
			}
		}
		echo "Tables imported successfully";
		header("Location: /");
	}

	private function preventXss($input, $encoding = 'UTF-8') {
		return htmlentities($input, ENT_QUOTES | ENT_HTML5, $encoding);
	}

}