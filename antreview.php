<?php
session_start();
if(!isset($_SESSION['logged_in'])) {
	header('Location: index.php');
	return;
}
require_once('DB.php');
require_once('utilities.php');
$db = new DB();

//ant event u_distortions
$ant = $db->get_ant_for_review($_SESSION['uid']);
if($ant==false) {
	echo <<<NOANTS
	
<script type="text/javascript">
alert('Sorry - there are no ANTs available for review at this time.');
window.location = 'home.php';
</script>

NOANTS;
}
//TODO: what happens when there are no available ants for review?
?>
<html>
<head>
	<title>Automatic Negative Thought review</title>
	<link rel="stylesheet" href="style.css">
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript" src="utilities.js"></script>
	<script type="text/javascript">	
	$(document).ready(function() {
		$('button').click(function() {
			save_data = new Object();
			save_data['type'] = 'antreview';			
			save_data['ant'] = <?php echo json_encode($ant['ant']); ?>;
			save_data['event'] = <?php echo json_encode($ant['event']); ?>;
			save_data['u_uid'] = '<?php echo $ant['u_uid']; ?>';
			save_data['u_distortions'] = '<?php echo $ant['u_distortions']; ?>';
			save_data['comments'] = $("#anonymous-comments").val();
			
			distortion1 = [];
			distortion1.push('<i>'+$("#distortion-1a :selected").text()+'</i>', '<i>'+$("#distortion-1b :selected").text()+'</i>', '<i>'+$("#distortion-1c :selected").text()+'</i>');								
			distortion1.push('<i>---</i>'); //hackish. when we remove --- two lines later, this ensure it is in the array.			
			distortion1 = distortion1.unique();
			distortion1.splice(distortion1.indexOf('<i>---</i>'), 1);

			distortion1string = '';
			for(var i=0;i<distortion1.length;i++) {
				distortion1string += distortion1[i].substr(3,distortion1[i].length-7) + ','; 
				// eliminating the <i> and </i> ... 
				// TODO: refactor so that the container applies the formatting and we don't have to string manipulate					
			}
			distortion1string = distortion1string.slice(0,-1);			
			save_data['r_distortions'] = distortion1string;
			save_data['r_uid'] = <?php echo $_SESSION['uid']; ?>;
			
			$.post('save.php', save_data, function(new_rank) {
				initial_rank = <?php echo $db->calculate_rank($_SESSION['uid']); ?>;
				rank_differential = initial_rank-new_rank;
				alertmsg = 'Thanks. The anonymous reviewee will receive a notification with your comments. You have received 200 karma and are now ranked #' + new_rank+'.';
				if(rank_differential>0) {
					alertmsg += ' This is a jump of ' + rank_differential + ' spots.';
				}				
				alert(alertmsg);
				window.location = 'home.php';
			});

		});
	});
	
	</script>
	<?php add_google_analytics_tracking(); ?>	
</head>
<body>
	<h2>Anonymous Event &amp; Negative Thought</h2>
	<span class="anonymous-desc">
		<label>Event</label>: &quot;<b><?php echo stripslashes(htmlentities($ant['event'])); ?></b>&quot;<br />
	</span>
	<span class="anonymous-desc">
		<label>Automatic Thought</label>: &quot;<em><?php echo stripslashes(htmlentities($ant['ant'])); ?></em>&quot;
	</span>
	<h2>What distortions do you detect?</h2>
	
	<div class="distortion-list">(1)
		<select id="distortion-1a">
			<option value="assuming">assuming</option>
			<option value="shoulds (musts/oughts)">shoulds (musts/oughts)</option>
			<option value="the fairy-tale fantasy">the fairy-tale fantasy</option>
			<option value="all or nothing thinking">all or nothing thinking</option>
			<option value="overgeneralizing">overgeneralizing</option>
			<option value="labeling">labeling</option>
			<option value="dwelling on the negative">dwelling on the negative</option>
			<option value="rejecting the positive">rejecting the positive</option>
			<option value="unfavorable comparisons">unfavorable comparisons</option>
			<option value="catastrophizing">catastrophizing</option>
			<option value="personalizing">personalizing</option> 
			<option value="blaming">blaming</option>				
			<option value="making feelings facts">making feelings facts</option>						
		</select>	
		<img src="images/red-arrow-left-2.png" class="red-arrow-left" style="display: none;"/></div>
	<div class="distortion-list">(2)
		<select id="distortion-1b">
			<option selected value="---">---</option>
			<option value="assuming">assuming</option>
			<option value="shoulds (musts/oughts)">shoulds (musts/oughts)</option>
			<option value="the fairy-tale fantasy">the fairy-tale fantasy</option>
			<option value="all or nothing thinking">all or nothing thinking</option>
			<option value="overgeneralizing">overgeneralizing</option>
			<option value="labeling">labeling</option>
			<option value="dwelling on the negative">dwelling on the negative</option>
			<option value="rejecting the positive">rejecting the positive</option>
			<option value="unfavorable comparisons">unfavorable comparisons</option>
			<option value="catastrophizing">catastrophizing</option>
			<option value="personalizing">personalizing</option> 
			<option value="blaming">blaming</option>				
			<option value="making feelings facts">making feelings facts</option>						
		</select>

	</div>
	<div class="distortion-list">(3)
		<select id="distortion-1c">
			<option selected value="---">---</option>
			<option value="assuming">assuming</option>
			<option value="shoulds (musts/oughts)">shoulds (musts/oughts)</option>
			<option value="the fairy-tale fantasy">the fairy-tale fantasy</option>
			<option value="all or nothing thinking">all or nothing thinking</option>
			<option value="overgeneralizing">overgeneralizing</option>
			<option value="labeling">labeling</option>
			<option value="dwelling on the negative">dwelling on the negative</option>
			<option value="rejecting the positive">rejecting the positive</option>
			<option value="unfavorable comparisons">unfavorable comparisons</option>
			<option value="catastrophizing">catastrophizing</option>
			<option value="personalizing">personalizing</option> 
			<option value="blaming">blaming</option>				
			<option value="making feelings facts">making feelings facts</option>						
		</select>
	</div>		
	<div class="distortion-list">
		<a href="#" onclick="javascript:distortionPopup();">distortion explanations</a>
	</div>
	<h2>Any (anonymous) comments?</h2>
	<textarea class="event" id="anonymous-comments" rows="7" cols="49"></textarea>
	<button>Submit your thoughts</button>
</body>
</html>