<?php

// in the future we'll award the XP
require_once('DB.php');
$db = new DB();

session_start();
$user = $_SESSION['uid'];

if($_POST['type'] == 'dtr') {
	$_SESSION['emotions'] = $_POST['emotions'];		
	if(isset($_POST['demo_mode'])) {
		$_SESSION['username'] = $_POST['username'];		
	}
	return;
}
elseif($_POST['type'] == 'ants') {
	if(strlen($_POST['ant1'])>0) {
		$distortion1 = explode(',',$_POST['distortion1']);
		$db->save_ant($user,$_POST['ant1'],$distortion1);		
	}
	if(strlen($_POST['ant2'])>0) {
		$distortion2 = explode(',',$_POST['distortion2']);			
		$db->save_ant($user,$_POST['ant2'],$distortion2);		
	}
	$db->award_exp($user,'distortions',200);
	echo json_encode($db->calculate_rank($user));
	return;
}


?>