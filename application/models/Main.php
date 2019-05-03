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
			$sql = "SELECT username, email, pass FROM `users` WHERE username=?";
			$response = $this->db->query($sql, [$_SESSION["user"]]);
			$res = $response->fetch(PDO::FETCH_ASSOC);
			return $res;
		}
		return [];
	}

	public function getPosts() {
		$response = $this->pdo->query("SELECT * FROM `posts`");
		return $response->fetchAll(PDO::FETCH_ASSOC);
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

}