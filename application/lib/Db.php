<?php

namespace application\lib;
use PDO;

class Db {

    protected $db;

    public function __construct() {
        require "application/config/db.php";
        $this->db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, $DB_OPT);
        return $this->db;
    }

    public function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
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

}