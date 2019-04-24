<?php

$host = "localhost";
$dbname = "camagru";
$charset = "utf8";
$DB_DSN = "mysql:host=$host;dbname=$dbname;charset=$charset";
$DB_USER = "root";
$DB_PASSWORD = "36673667";
$DB_OPT = [
	PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC,
	PDO::ATTR_EMULATE_PREPARES		=> false
];
