<?php
require_once('DB.php');


function make_bold($input) {
	return '<b>' . $input . '</b>';
}


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

function send_email_notification($user, $subject, $html_message, $text_message) {
	
	require_once('thirdparty/swiftmailer.lib/swift_required.php');
	include('config.php');
	
	
	$db = new DB();
	$to_email = $db->get_email_address($user);	
	$to_username = $db->get_username($user);
	
	$from = array('zachary@endants.com' => 'EndAnts Notifications');
	$to = array($to_email=>$to_username);



	// Setup Swift mailer parameters
	$transport = Swift_SmtpTransport::newInstance('smtp.sendgrid.net', 25);
	$transport->setUsername($sendgrid_username);
	$transport->setPassword($sendgrid_password);
	$swift = Swift_Mailer::newInstance($transport);

	// Create a message (subject)
	$message = new Swift_Message($subject);

	// attach the body of the email
	$message->setFrom($from);
	$message->setBody($html_message, 'text/html');
	$message->setTo($to);
	$message->addPart($text_message, 'text/plain');

	// send message 

	try {


		if ($recipients = $swift->send($message, $failures))
		{
		  // This will let us know how many users received this message
		  // echo 'Message sent out to '.$recipients.' users';
		}
		// something went wrong =(
		else
		{
		  // echo "Something went wrong - ";
		  // print_r($failures);
		}
	} catch (Exception $e) {
		//echo 'Something went wrong..';
		// so let's mail it manually . . . 
		$headers = 'From: zachary@endants.com' . "\r\n" .
		    'Reply-To: zachary@endants.com' . "\r\n" .
		    'X-Mailer: PHP/' . phpversion();

		mail($to_email, $subject, $text_message, $headers);
		
		
	}
	
	
	
}


 

 




?>
