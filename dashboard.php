<?php

require_once('config.php');
require_once('DB.php');
require_once('thirdparty/googleanalytics.class.php');
require_once('thirdparty/mailchimp.class.php');

$db = new DB();





/*



// acquisition: # of new visitors per day
// activation: # of new visitors who visit at least 2 pages
// retention: # of old visitors who visited per day (%?) ... # of people who visited homepage that week.
		
	alternatively, c/o http://andrewchenblog.com/2008/09/08/how-to-measure-if-users-love-your-product-using-cohorts-and-revisit-rates/ 
	
	time period		user count		percentage of users who have logged-in in the past week
	week 1			500				28%
	week 2			523				26%		
etc.	

select count(*) as user_count from users where registration_date>START_DATE and registration_date<START_DATE+interval 7 day
select count(*) as revisit_count from users where registration_date>START_DATE and registration_date<START_DATE+interval 7 day and (last_login>=now()-interval 7 day)
revisit_rate = revisit_count/user_count


// referral: # of referral sent per day? % of total user base? % of total daily logins?
// revenue

*/

echo '<h1>Traffic Data</h1>';

$date_data = array();

try {
	$ga = new GoogleAnalytics('zackster@gmail.com','happyd0nuts');

	// set the Google Analytics profile you want to access - format is 'ga:123456';
	$ga->setProfile('ga:35050130');
	// set the date range we want for the report - format is YYYY-MM-DD
	$ga->setDateRange('2010-07-26',date('Y-m-d'));

	$report = $ga->getReport(
		array('dimensions'=>urlencode('ga:date'),
			'metrics'=>urlencode('ga:pageviews,ga:visits'),
//			'filters'=>urlencode('ga:country=@Australia'),
			'sort'=>'-ga:pageviews'
			)
		);

	
	foreach($report as $day=>$data) {
		$day = date('F j Y',strtotime($day));
		$date_data[$day]['pageviews'] = $data['ga:pageviews'];
		$date_data[$day]['visits'] = $data['ga:visits'];
	}
	
} catch (Exception $e) { 
	print 'Error: ' . $e->getMessage(); 
}


$regcount = $db->dashboard_get_registration_count();
echo '<table border=1>';
echo '<thead><th>Date</th><th>Registration Count</th><th>Pageviews</th><th>Visits</th></thead>';
foreach($regcount as $day) {	
	echo "<tr>\n";
	echo "<td>{$day['registration_date']}</td>\n";
	echo "<td>{$day['registration_count']}</td>\n";
	echo "<td>" . @$date_data[$day['registration_date']]['pageviews'] . "</td>\n";
	echo "<td>" . @$date_data[$day['registration_date']]['visits'] . "</td>\n";
	echo "</tr>\n";
}
echo '</table>';


echo '<h1>Mailing List Data</h1>';

$api = new MCAPI($mailchimp_api_key);

$retval = $api->lists();

if ($api->errorCode){
	echo "Unable to load lists()!";
	echo "\n\tCode=".$api->errorCode;
	echo "\n\tMsg=".$api->errorMessage."\n";
} else {
	echo '<table border=1>';
	echo '<thead><th>List ID</th><th>List Name</th><th>List Web ID</th><th>List Member Count</th><th>List Unsubscribe Count</th><th>List Cleaned Count</th></thead>';

	foreach ($retval as $list){
		echo '<tr>';
		echo "<td>{$list['id']}</td>";
		echo "<td>{$list['name']}</td>";
		echo "<td>{$list['web_id']}</td>";				
		echo "<td>{$list['member_count']}</td>";
		echo "<td>{$list['unsubscribe_count']}</td>";
		echo "<td>{$list['cleaned_count']}</td>";
		echo '</tr>';
	}
	
	echo '</table>';
}

die;

?>