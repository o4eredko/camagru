<?php

namespace application\lib;
use PDO;

class Db {

    protected $pdo;

    public function __construct() {
        require "application/config/db.php";
        $this->pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, $DB_OPT);
    }

    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        if (!empty($params)) {
            foreach ($params as $key => $val) {
                $stmt->bindValue(":$key", $val);
            }
        }
        $stmt->execute();
        return $stmt;
    }

    public function rowsAll($sql, $params = []) {
        $result = $this->query($sql, $params);
        return $result->fetchAll();
    }

    public function row($sql, $params = []) {
        $result = $this->query($sql, $params);
        return $result->fetch();
    }

    public function column($sql, $params = []) {
        $result = $this->query($sql, $params);
        return $result->fetchColumn();
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