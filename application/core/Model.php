<?php


namespace application\core;
use application\lib\Db;

abstract class Model {

    protected $db;
    protected $pdo;

    public function __construct() {
        $this->db = new Db;
        $this->pdo = $this->db->getConnection();
    }

}