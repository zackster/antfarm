<?php
require_once('DB.php');
session_start();

if(strlen($_POST['reg_email']) == 0 || strlen($_POST['reg_username']) == 0) {
	header('Location: index.php?regerr');
	return;	
}




isset($_COOKIE['source']) ? $source = $_COOKIE['source'] : $source = 'none';
if(isset($_COOKIE['user_referrer'])) { 
	$source .= ',' . $_COOKIE['user_referrer'];
}
$db = new DB();
$_SESSION['uid'] = $db->create_user($_POST['reg_email'], $_POST['reg_password'], $_POST['reg_username'], $source);
if($_SESSION['uid'] == false) {
	header('Location: index.php?regerr');
	return;
}
else {
	if(isset($_COOKIE['user_referrer'])) {
		$db->award_exp($_COOKIE['user_referrer'],'accepted_invitation',10);		
		$db->insert_notification($_COOKIE['user_referrer'], $_POST['reg_username'] . ' has accepted your invitation! You have been credited with 10 karma points.');
	}
	
	$_SESSION['username'] = $_POST['reg_username'];
	$_SESSION['logged_in'] = true;

	header('Location: home.php');	
}


	


?>