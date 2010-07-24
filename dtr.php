<?php
session_start();
$demo_mode = false;
if(isset($_GET['demo_mode'])) {
	$demo_mode = true;
} 
else {
	// since it's not demo mode, we gotta bounce them if they aren't logged in 
	if(!isset($_SESSION['logged_in'])) {
		header('Location: index.php');
		return;
	}
}
require_once('DB.php');
$db = new DB();
list($rank, $scoreboard_size) = $db->calculate_rank($_SESSION['uid']);
$_SESSION['initial_rank'] = $rank;

function render_emotion_options() {
	$emotions = array('afraid', 'annoyed', 'angry', 'aversion', 'confused', 'disconnected', 'embarrassed', 'pain', 'sad', 'tense', 'vulnerable', 'despair', 'shame', 'lonely', 'anxious', 'bored', 'tired', 'overwhelmed', 'enraged', 'frightened', 'disgusted', 'frustrated', 'hysterical', 'guilty', 'suspicious', 'helpless', 'hopeless', 'panic', 'nervous');
	asort($emotions);
	foreach($emotions as $feeling) {
		echo '<option value="' . $feeling . '">' . $feeling . '</option>';
		echo "\n";
	}
	return;  
}

?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<link rel="stylesheet" type="text/css" href="dragdealer/dragdealer.css" />
	<script type="text/javascript" src="dragdealer/dragdealer.js"></script>
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript" src="jquery.progressbar.min.js"></script>
	<script type="text/javascript" src="utilities.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		

			
		$("#fact-event-impact").hide();
		$("#fact-intensity").hide();

<?php if($rank == NULL) { ?>
		$("#antrank").html('You are currently unranked on the leaderboard, but finishing this task will put you up the ladder.');
<?php } else { ?>		
		rank = <?php echo $rank; ?>;
		scoreboard_size = <?php echo $scoreboard_size; ?>;
		$("#antrank").html('Your AntRank is currently <b>'+rank+'</b> out of <b>'+scoreboard_size+'</b>, but finishing this task will bump you up the ladder. ');
<?php } ?>
		

		
		/* button on step 1, to advance to step 2 */
		$("#step1-next").click(function() {
			$("#fact-event").hide();
			$("#fact-event-impact").show();
			$("#pb1").progressBar(33);
			$("#pbo").progressBar(10);
		});
		
		/* button on step 2, to advance to step 3 */
		$("#step2-next").click(function() {
			$("#fact-event-impact").hide();
			$("#fact-intensity").show();

			
			emotion1 = $("#emotion-1 :selected").text();	
			emotion2 = $("#emotion-2 :selected").text();	
			emotion3 = $("#emotion-3 :selected").text();	
			emotions = new Array();
			emotions.push(emotion1);
			emotions.push(emotion2);
			emotions.push(emotion3);
			emotions.push('---'); //hackish. when we remove --- two lines later, this ensure it is in the array.
			emotions = emotions.unique();
			emotions.splice(emotions.indexOf('---'), 1);
			emotion_ctr = 1;
			sliders = new Array();
			slider_values = new Array();
			for(var i=0; i<emotions.length;i++) {
				j = i+1; // array begins with 0 index but id/label names begin start at 1				
				$("#emotion"+j+"-label").html(emotions[i]);
				$("#emotion"+j+"-row").show();
				sliders.push(j);
			}
			$.each(sliders, function(k) {
				slider_values[k] = new Array();
				var isname = 'intensity-slider-' + sliders[k];
				var sv = '#slider'+sliders[k]+'-value';
				var dd = new Dragdealer(isname, {
					steps: 10,
					snap: true,
					animationCallback: (function(x) {
						intensity_score = (x * 9) + 1; //Dragdealer has a weird callback value for x, indicating its horizontal position
						$(sv).html(intensity_score);
						slider_values[k] = intensity_score;
					})
				});	
				dd.setValue((4/9));			
			});
			
			$("#pb1").progressBar(66);
			$("#pbo").progressBar(25);
			
		});			
		
		$("#step3-next").click(function() {
			save_data = new Object();
			save_data['type'] = 'dtr';
			save_data['negative_event'] = $("#negative-event-description").val();
			save_data['emotions'] = new Array();
			for(var i=0;i<emotions.length;i++){
				var t = new Object();
				t['emotion'] = emotions[i];
				t['intensity'] = slider_values[i];
				save_data['emotions'].push(t);
			}
			<?php
			//if demo mode we want to save their username & redirect to analysis.php?demo=yes
			if($demo_mode) { ?>
				save_data['demo_mode'] = true;
				save_data['username'] = username; 
				$.post('save.php', save_data);
				window.location = 'analysis.php?demo_mode=true';
			<?php } else { ?>
				$.post('save.php', save_data);
				window.location = 'analysis.php';
			<?php } ?>
		});
		
		<?php if($demo_mode) { ?>
		username = prompt("What is your name?");		
		<?php } else { ?>
		username = '<?php echo htmlentities($_SESSION['username']); ?>';
		<?php	
		}
		?>
		$("#stats_headline").html(username.capitalize() + "'s Stats");
	
	});
	</script>
	<title>Anti-Distortion Fun</title>
</head>
<body>
<div class="hud">
	<span class="profile-thumb"><img src="images/alert-overlay.png" height="50" width="50"></span>
	<div class="headline" id="stats_headline">* * *</div>
	<br /><br />
	You are currently <b>describing some Negative Emotions and their Circumstances</b><br />
	<div class="task-details">
		- You are on Step 1 of 3<br />
		- Progress Completion: <span class="progressBar" id="pb1">0%</span>
	</div><br />

	<span id="antrank"></span>

</div>




<h1>The Facts</h1>
<div id="fact-event">
	<h2>Event</h2>
	<label>Describe the event that "made you" feel bad/unpleasant</label>
	<textarea class="event" id="negative-event-description" rows="7" cols="40"></textarea>
	<button type="submit" id="step1-next">on to step two</button>		
</div>
<div id="fact-event-impact">
	<h2>Impact of Event</h2>
	<label>Choose up to three emotions to describe the emotional impact</label>
	<div class="emotion-list">(1)
		<select id="emotion-1">
			<?render_emotion_options();?>
		</select>	
		<img src="images/red-arrow-left-2.png" class="red-arrow-left" style="display: none;"/></div>
	<div class="emotion-list">(2)
		<select id="emotion-2">
			<option selected value="---">---</option>
			<?render_emotion_options();?>
		</select>
	
	</div>
	<div class="emotion-list">(3)
		<select id="emotion-3">
			<option selected value="---">---</option>
			<?render_emotion_options();?>
		</select>
	</div>
	<button type="submit" id="step2-next">on to step three</button>
</div>
<div id="fact-intensity">
	<h2>Intensity</h2>
	<label>Rate the intensity of each emotion from 1-10</label>

	<table>		
		<tr id="emotion1-row" class="emotion">
			<td id="emotion1-label" class="emotion-label">azsdff</td>
			<td>
				<div class="slider">
					<div id="intensity-slider-1" class="dragdealer">			
						<div class="red-bar handle" id="slider1-value">intensity-o-meter</div>
					</div>
				</div>
			</td>
		</tr>
		<tr id="emotion2-row" class="emotion">
			<td id="emotion2-label"  class="emotion-label">asdfdsf</td>
			<td>
				<div class="slider">
					<div id="intensity-slider-2" class="dragdealer">			
						<div class="red-bar handle" id="slider2-value">intensity-o-meter</div>
					</div>
				</div>
			</td>
		</tr>
		<tr id="emotion3-row" class="emotion">
			<td id="emotion3-label"  class="emotion-label">asdf</td>
			<td>
				<div class="slider">
					<div id="intensity-slider-3" class="dragdealer">			
						<div class="red-bar handle" id="slider3-value">intensity-o-meter</div>
					</div>
				</div>
			</td>
		</tr>
	</table>
	<h3>Done adjusting?</h3><button type="submit" id="step3-next">Time for Analysis</button>
	
</div>
</body>
</html>