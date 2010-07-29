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

function add_google_analytics_tracking() {
echo <<<GOOG
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-17638356-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
GOOG;
}

function send_email_notification($user, $subject, $message) {
	$db = new DB();
	$to = $db->get_email_address($user);	
	$headers = 'From: notifications@endants.com' . "\r\n" .
	    'Reply-To: zachary@endants.com' . "\r\n" .
	    'X-Mailer: PHP/' . phpversion();

	mail($to, $subject, $message, $headers);
	
}

?>