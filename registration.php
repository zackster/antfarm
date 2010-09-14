<?php
require_once('DB.php');
require_once('thirdparty/mailchimp.class.php');
require_once('config.php');


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
		$db->award_exp($_COOKIE['user_referrer'],'accepted_invitation',50);		
		$db->insert_notification($_COOKIE['user_referrer'], $_POST['reg_username'] . ' has accepted your invitation! You have been credited with 10 karma points.');
	}
	
	$_SESSION['username'] = $_POST['reg_username'];
	$_SESSION['logged_in'] = true;
	
	
	if(isset($_POST['email_updates'])) {
	
		// mailchimp stuff: they've opted into email updates
		
		$api = new MCAPI($mailchimp_api_key);
		$listId = '02aeb1161b'; // this is the "EndAnts Alpha Users" list.
		$merge_vars = array('USERNAME' => $_POST['reg_username']); 
		$subscriber_email = $_POST['reg_email'];
		$retval = $api->listSubscribe( $listId, $subscriber_email, $merge_vars );

	}	

	header('Location: home.php');	
}


	


?>