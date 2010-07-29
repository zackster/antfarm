<?php

require_once('utilities.php');
require_once('DB.php');
$db = new DB();

session_start();
$user = $_SESSION['uid'];

if($_POST['type'] == 'dtr') {
	$_SESSION['negative_event'] = $_POST['negative_event'];
	$_SESSION['emotions'] = $_POST['emotions'];		
	if(isset($_POST['demo_mode'])) {
		$_SESSION['username'] = $_POST['username'];		
	}
	return;
}
elseif($_POST['type'] == 'ants') {
	if(strlen($_POST['ant1'])>0) {
		$db->save_ant($user,$_POST['ant1'],$_POST['negative_event'],$_POST['distortion1']);		
	}
	if(strlen($_POST['ant2'])>0) {		
		$db->save_ant($user,$_POST['ant2'],$_POST['negative_event'],$_POST['distortion2']);		
	}
	$db->award_exp($user,'distortions',200);
	list($rank, $total) = $db->calculate_rank($user);
	echo $rank;
	return;
}
elseif($_POST['type'] == 'antreview') {
	//TODO: implement a format_distortions function
	$db->update_ant($_POST['u_uid'], $_POST['ant'],$_POST['event'],$_POST['u_distortions'], $_POST['r_uid'], $_POST['r_distortions'], $_POST['comments']);	
	$db->award_exp($_POST['r_uid'], 'ant review', 40);
	$db->award_exp($_POST['u_uid'], 'beneficiary of ant review', 10);
	$db->insert_notification($_POST['u_uid'], 'One of your anonymous ANTs was anonymously reviewed. Event: <b>' . $_POST['event'] . '</b>. Your automatic thought: "<b>' . $_POST['ant'] . '</b>". Distortions you observed: <i>' . format_distortions(explode(',',$_POST['u_distortions']),true) . '</i>. Your reviewer (unaware of the distortions you indicated) the distortions observed these distortions: <i>' . format_distortions(explode(',', $_POST['r_distortions']),true) . '</i>, and had this to say: "<b>' . $_POST['comments']. '</b>".');
	list($rank, $total) = $db->calculate_rank($user);
	echo $rank;
	return;
	
}
elseif($_POST['type'] == 'usersettings') {
	$db->update_email_settings($user, $_POST['disable_emails']);
	return;
}
?>