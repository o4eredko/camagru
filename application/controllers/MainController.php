<?php


namespace application\controllers;
use application\core\Controller;
use application\lib\Db;

class MainController extends Controller {

    public function indexAction() {
        $db = new Db;
        $this->view->render("Main page Title");
    }

}