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
?>
<html>
	<head>
		<link rel="stylesheet" href="style.css" />
		<script type="text/javascript" src="jquery.js"></script>
		<script>
		$(document).ready(function() {
			
			$("#li-<?php echo $home_step; ?>").addClass('selected');

		});
		</script>
		<title>EndANTs | Home</title>
	</head>	
	<body>
		<ul class="navbar">
			<li id="li-activities"><a href="home.php">Activities</a></li>
			<li id="li-notifications">Notifications (0)</li>
			<li id="li-leaderboard"><a href="home.php?page=leaderboard">Leaderboard</a></li>
			<li><a href="login.php?logout">Logout</a></li>
		</ul>	

<?php if($home_step == 'leaderboard') { ?>	

<div style="margin:10px; padding: 10px">
<?php draw_leaderboard(); ?>
</div>
<?php } else { ?>		
		
		<div class="home-hud">
			<span class="profile-thumb"><img src="images/alert-overlay.png" height="50" width="50"></span>
			<div class="headline" id="stats_headline"><?php echo $_SESSION['username']; ?></div>
			<br /><br />
<?php
		list($rank, $scoreboard_size) = $db->calculate_rank($_SESSION['uid']);
		if($rank == NULL) {			
?>
		You are currently unranked, but collecting karma will put you on the ladder.
<?php } else { ?>			
			Your AntRank is currently <b>#<?php echo $rank; ?></b> out of <?php echo $scoreboard_size; ?>, but collecting karma will improve your rank. 
<?php } ?>


		</div>
		<p>
		Karma Quests:
		<ul>
			<li><a href="dtr.php">Correct some of your own Automatic Negative Thoughts</a>: <em>200 pts</em></li>
			<li>(Anonymously) <a href="antreview.php">review someone else's Automatic Negative Thoughts</a>: <em>100 pts</em></li>
			<li>Invite a friend to EndAnts: <em>10 pts per friend</em></li>
		</p>
<?php } // home_step is not leaderboard... ?>
	</body>
</html>