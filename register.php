<?php
require_once 'connect.php';
if ($_POST["pass"] === $_POST["repass"]) {
	// $query = "SELECT username FROM users WHERE username = '" . $_POST["username"] . "'"
	$query = "INSERT INTO users(username, name, surname, email, password)
	VALUES(
		'" . $_POST["username"] . "',
		'" . $_POST["name"] . "',
		'" . $_POST["surname"] . "',
		'" . $_POST["email"] . "',
		'" . hash("whirlpool", $_POST["pass"]) . "')";
	mysqli_query($connection, $query);
	header("Location: index.html");
} else {
	echo "ERROR";
}
?>