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
	$db->update_ant($_POST['u_uid'], $_POST['ant'],$_POST['event'],$_POST['u_distortions'], $_POST['r_uid'], $_POST['r_distortions'], $_POST['comments']);	
	$db->award_exp($_POST['r_uid'], 'ant review', 40);
	$db->award_exp($_POST['u_uid'], 'beneficiary of ant review', 10);
	$db->insert_notification($_POST['u_uid'], 'One of your anonymous ANTs was anonymously reviewed. Event: <b>' . $_POST['event'] . '</b>. Your automatic thought: "<b>' . $_POST['ant'] . '</b>". Distortions you observed: <i>' . format_distortions(explode(',',$_POST['u_distortions']),true) . '</i>. Your reviewer (unaware of the distortions you indicated) the distortions observed these distortions: <i>' . format_distortions(explode(',', $_POST['r_distortions']),true) . '</i>, and had this to say: "<b>' . $_POST['comments']. '</b>".');


	if($db->are_email_notifications_enabled($_POST['u_uid'])) {
		
		$formatted_distortions = format_distortions(explode(',', $_POST['r_distortions']),true);
		$ant_noslash = stripslashes($_POST['ant']);
		$event_noslash = stripslashes($_POST['event']);
		$subject = 'Someone has reviewed one of your negative thoughts on EndAnts';
		$text_message = <<<TEXTMESSAGE
Hi,

One of your negative thoughts on EndAnts has been anonymously reviewed.

Your negative thought was "{$ant_noslash}". 
It was triggered by the event "{$event_noslash}". 

An anonymous reviewer found that your thought contained the distortions of {$formatted_distortions}. The anonymous reviewer also had this to say:

	>{$_POST['comments']}

NOTE: You can log into your account at http://www.endants.com to see your new leaderboard rank, correct more of your automatic negative thoughts, or even disable these emails.
TEXTMESSAGE;
		$bold_distortions = format_distortions(array_map("make_bold", explode(',', $_POST['r_distortions'])),true);
		$html_message = <<<HTMLMESSAGE
Hi,<br /><br />

One of your negative thoughts on EndAnts has been anonymously reviewed.<br /><br />

Your negative thought was "<b>{$ant_noslash}</b>".<br />
It was triggered by the event "<b>{$event_noslash}</b>".<br /><br />

An anonymous reviewer found that your thought contained the distortions of {$bold_distortions}. The anonymous reviewer also had this to say:<br /><br />

&nbsp;&nbsp;&nbsp;&nbsp;><b>{$_POST['comments']}</b><br /><br />

NOTE: You can log into your account at http://www.endants.com to see your new leaderboard rank, correct more of your automatic negative thoughts, or even disable these emails.		
HTMLMESSAGE;

		send_email_notification($_POST['u_uid'], $subject, $html_message, $text_message);
	}
	list($rank, $total) = $db->calculate_rank($user);
	echo $rank;
	return;
	
}
elseif($_POST['type'] == 'usersettings') {
	$db->update_email_settings($user, $_POST['disable_emails']);
	return;
}
?>