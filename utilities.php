<?php
require_once('DB.php');
function draw_leaderboard() {
	$db = new DB();
	$res = $db->calculate_leaderboard();
	echo '<table class="leaderboard"><thead><th>Rank</th><th>Username</th><th>Score</th></thead>';
	foreach($res as $row) {
		echo '<tr>';
		echo '<td>'.$row['rank'].'</td>';
		echo '<td>'.$row['username'].'</td>';
		echo '<td>'.$row['score'].'</td>';
		echo '</tr>';
	}
	echo '</table>';	
}

?>