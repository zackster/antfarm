<?php
session_start();
if(!isset($_SESSION['logged_in'])) {
	header('Location: index.php');
	return;
}
require_once('DB.php');
require_once('utilities.php');
$db = new DB();
$home_step = 'activities';
if(isset($_GET['page'])) {
	$home_step = $_GET['page'];
}


$parts = explode('/', $_SERVER['REQUEST_URI']);
if(count($parts) == 3) { // are we developing on localhost? or...
	$curpath = 'http://'. $_SERVER['HTTP_HOST'] . '/' . $parts[1] . '/';
}
else {
	$curpath = 'http://' . $_SERVER['HTTP_HOST'] . '/';
}


?>
<html>
	<head>
		<link rel="stylesheet" href="style.css" />
		<script type="text/javascript" src="jquery.js"></script>
		<script>
		$(document).ready(function() {
			
			
			$("#li-<?php echo $home_step; ?>").addClass('selected');

			$(".highlight_onclick").bind("click focus", function(e) {
				this.select();
			});
			
			$("#invitation_link").toggle(function() {
				$("#invitation_field").show();
			}, function() {
				$("#invitation_field").hide();
			});
			
			$("#settings-saved-confirmation").hide();
			var disable_emails = 0;
			$("#save-settings").click(function() {
				if($("#enable-emails").is(':checked')) {
					disable_emails = 0;
				} else {
					disable_emails = 1;
				}
				
				save_data = new Object();
				save_data['type'] = 'usersettings';
				save_data['disable_emails'] = disable_emails;
				$.post('save.php', save_data, function(complete) {
					$("#settings-saved-confirmation").show();
					$("#settings-saved-confirmation").fadeOut(5000, function() {
						$(this).hide();
					});
				});
			});

		});
			

		</script>
		<title>EndANTs | Home</title>
		<?php add_google_analytics_tracking(); ?>	
	</head>	
	<body>
		<ul class="navbar">
			<li id="li-activities"><a href="home.php">Activities</a></li>
			<li id="li-notifications"><a href="home.php?page=notifications">Notifications (<?php echo $db->get_notification_count($_SESSION['uid']); ?>)</a></li>
			<li id="li-leaderboard"><a href="home.php?page=leaderboard">Leaderboard</a></li>
			<li id="li-settings"><a href="home.php?page=settings">Settings</a></li>
			<li><a href="login.php?logout">Logout</a></li>
		</ul>	

<?php if($home_step == 'leaderboard') { ?>	

<div style="margin:10px; padding: 10px">
<?php draw_leaderboard(); ?>
</div>
<?php } elseif($home_step == 'notifications') { 
	
	// table? span?
	// make sure to mark all notifications read, or at least change the contents of the <li>
?>
<script type="text/javascript">
$("#li-notifications").html('<a href="home.php?page=notifications">Notifications (0)</a>');
</script>
<div id="notification-box">
<?php
	$notifications = $db->get_notifications($_SESSION['uid']);
	if(count($notifications) == 0) {
		echo 'We have no messages for you right now!';
	}
	else {
		
	
		foreach($notifications as $notification) {
			echo '<span class="notification">';
			$date_text = strtotime($notification['date']);		
			$nice_date = date('M j', $date_text);
			echo stripslashes($notification['message']) . ' ('. $nice_date .')';
			echo '</span>';
		}
	}
?>
</div>
	
<?php } elseif($home_step == 'settings') {
	
	$enable_emails_checked = '';
	if($db->are_email_notifications_enabled($_SESSION['uid'])) {
		$enable_emails_checked = 'checked';
	}

?>

<div id="notification-box">
	<input type="checkbox" id="enable-emails" <?php echo $disable_emails_checked; ?> /> 
	<label>receive an email notification when someone anonymously reviews one of your distortions</label>
	<br />
	<button id="save-settings">save settings</button>
	<span id="settings-saved-confirmation">settings saved</span>
</div>	
	
	
<?	} else { ?>		
		
		<div class="home-hud">
			<span class="profile-thumb"><img src="images/alert-overlay.png" height="50" width="50"></span>
			<div class="headline" id="stats_headline"><?php echo $_SESSION['username']; ?></div>
			<br /><br />
<?php
		list($rank, $scoreboard_size) = $db->calculate_rank($_SESSION['uid']);
		$karma_points = $db->calculate_score($_SESSION['uid']);
		if($rank == NULL) {			
?>
		You are currently unranked, but collecting karma will put you on the ladder.
<?php } else { ?>			
			Your AntRank is currently <b>#<?php echo $rank; ?></b> out of <?php echo $scoreboard_size; ?>, but collecting karma will improve your rank. You have <b><?php echo $karma_points; ?></b> karma points.
<?php } ?>


		</div>
		<p>
		Karma Quests:
		<ul>
			<li><a href="dtr.php">Correct some of your own Automatic Negative Thoughts</a>: <em>200 pts</em></li>
			<li>(Anonymously) <a href="antreview.php">review someone else's Automatic Negative Thoughts</a>: <em>40 pts for you, 10 pts for them</em></li>
			<li><a href="#" id="invitation_link">Invite a friend to EndAnts</a>: <em>10 pts per friend</em></li>
		</p>




		<div id="invitation_field">
			<label class="invitation">Copy and paste this URL to a friend:</label>
			<input type="text" class="highlight_onclick" size=50 value="<?php echo $curpath; ?>?r=<?php echo $_SESSION['uid']; ?>"  /><br />
			<label class="invitation">If your friend signs up for an account after using your URL, you will automatically be credited with 10 karma points.</label>
			<br /><br />
			<a name="fb_share" type="button" share_url="http://www.endants.com/index.php?r=<?php echo $_SESSION['uid']; ?>" href="http://www.facebook.com/sharer.php">Share</a><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
		</div>
		
<?php } // end:home_step is not leaderboard... ?>
	</body>
</html>