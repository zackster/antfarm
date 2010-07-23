<?php
require_once('DB.php');

session_start();
isset($_COOKIE['source']) ? $source = $_COOKIE['source'] : $source = 'none';
$db = new DB();
$_SESSION['uid'] = $db->create_user($_POST['reg_email'], $_POST['reg_password'], $_POST['reg_username'], $source);
if($_SESSION['uid'] == false) {
	header('Location: index.php?regerr');
	return;
}

$_SESSION['username'] = $_POST['reg_username'];
$_SESSION['logged_in'] = true;


header('Location: home.php');	


	


?>