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

 function format_distortions(&$array, $useOxfordComma = false) {
    $count = (is_array($array) ? count($array) : 0);
    if (3 <= $count) {
        $last = end($array);
        $list = prev($array) . ($useOxfordComma ? ', and ' : ' and ') . $last;
        while ($v = prev($array)) {
            $list = $v . ', ' . $list;
        }   
    } else if (2 == $count) {
        $last = end($array);
        $list = prev($array) . ' and ' . $last;
    } else if (1 == $count) {
        $list = end($array);
    } else {
        return '';
    }       
            
    reset($array);
    return $list;
}

?>