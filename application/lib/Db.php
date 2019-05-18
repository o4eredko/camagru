<?php

namespace application\lib;
use PDO;
use PDOException;

class Db {

    protected $pdo;

    public function __construct() {
        require "application/config/database.php";
		try {
			$this->pdo = new PDO("mysql:host=$DB_HOST;charset=$DB_CHARSET", $DB_USER, $DB_PASSWORD, $DB_OPT);
			$this->pdo->query("CREATE DATABASE IF NOT EXISTS $DB_NAME")
				or die(print_r($this->pdo->errorInfo(), true));
			$this->pdo->exec("use $DB_NAME");

		} catch (PDOException $e) {
			die("DB ERROR: ". $e->getMessage());
		}
	}

    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function rowsAll($sql, $params = []) {
        $result = $this->query($sql, $params);
        return $result->fetchAll();
    }

    public function row($sql, $params = []) {
        $result = $this->query($sql, $params);
        return $result->fetch(PDO::FETCH_LAZY);
    }

    public function column($sql, $params = [], $id = 0) {
        $result = $this->query($sql, $params);
        return $result->fetchColumn($id);
    }

	public function pdoSet($allowed, &$values, $source = array()) {
		$set = '';
		$values = array();
		if (!$source) $source = &$_POST;
		foreach ($allowed as $field) {
			if (isset($source[$field])) {
				$set.="`".str_replace("`","``",$field)."`". "=:$field, ";
				$values[$field] = $source[$field];
			}
		}
		return substr($set, 0, -2);
	}

    public function getConnection() {
    	return $this->pdo;
	}

}