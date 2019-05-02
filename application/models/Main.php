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
			$sql = "SELECT username, name, surname, email, pass FROM `users` WHERE username = ?";
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

}