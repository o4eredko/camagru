<?php

$DB_HOST = "localhost";
$DB_NAME = "camagru";
$DB_CHARSET = "utf8";
$DB_DSN = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=$DB_CHARSET";
$DB_USER = "root";
$DB_PASSWORD = "";
$DB_OPT = [
    PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES		=> false
];
