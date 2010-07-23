<?php
require_once('DB.php');

session_start();
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
	}
	
	$_SESSION['username'] = $_POST['reg_username'];
	$_SESSION['logged_in'] = true;

	header('Location: home.php');	
}


	


?>