<?php
require_once('DB.php');

session_start();

if(isset($_GET['logout'])) {
	session_destroy();
	header('Location: index.php');
	return;
}

$db = new DB();
$credentials = $db->login_user($_POST['login_email'],$_POST['login_password']);
if(!$credentials) {
	header('Location: index.php?badlogin');
	return;
}
else {
	$_SESSION = array();
	$_SESSION['uid'] = $credentials['uid'];
	$_SESSION['username'] = $credentials['username'];
	$_SESSION['logged_in'] = true;
	header('Location: home.php');
	return;
}

?>