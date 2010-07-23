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

		});
			

		</script>
		<title>EndANTs | Home</title>
	</head>	
	<body>
		<ul class="navbar">
			<li id="li-activities"><a href="home.php">Activities</a></li>
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
			<li>(Anonymously) <a href="antreview.php">review someone else's Automatic Negative Thoughts</a>: <em>100 pts</em></li>
			<li><a href="#" id="invitation_link">Invite a friend to EndAnts</a>: <em>10 pts per friend</em></li>
		</p>




		<div id="invitation_field">
			<input type="text" class="highlight_onclick" size=50 value="<?php echo $curpath; ?>?r=<?php echo $_SESSION['uid']; ?>"  /><br />
			<label class="invitation">If your friend signs up for an account after using your URL, you will automatically be credited with 10 karma points.</label>
		</div>
		
<?php } // end:home_step is not leaderboard... ?>
	</body>
</html>