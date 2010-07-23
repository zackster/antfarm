<?php
session_start();
if(isset($_GET['debug'])) { $_SESSION['emotions']=array(0=>array('emotion'=>'annoyed', 'intensity'=>6)); }
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
?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<link rel="stylesheet" type="text/css" href="dragdealer/dragdealer.css" />
	<script type="text/javascript" src="dragdealer/dragdealer.js"></script>
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript" src="jquery.progressbar.min.js"></script>
	<script type="text/javascript">
	/* Function for removing array duplicates - http://www.martienus.com/code/javascript-remove-duplicates-from-array.html */
	Array.prototype.unique = function () {
		var r = new Array();
		o:for(var i = 0, n = this.length; i < n; i++)
		{
			for(var x = 0, y = r.length; x < y; x++)
			{
				if(r[x]==this[i])
				{
					continue o;
				}
			}
			r[r.length] = this[i];
		}
		return r;
	}
	String.prototype.capitalize = function() {
	    return this.charAt(0).toUpperCase() + this.slice(1);
	}
	
	
	function distortionPopup() {
		window.open("assuming-popup.html","Distortion Explanations","height=500,width=530,scrollbars=1");
	}

	$(document).ready(function() {

		/* Responses from section 1 - dtr.php */				
		session_data = <?=json_encode($_SESSION)?>;		
		emotion_data = session_data['emotions'];		
		<?php if($demo_mode) { ?>
			username = session_data['username'];
		<?php } else { ?>
			username = '<?php echo htmlentities($_SESSION['username']); ?>';
		<? } ?>
		
		/* Page Initialization */

		//Don't hide the first one! $("#analysis-automatic-thoughts").hide();
		$("#analysis-initial-believability").hide();
		$("#analysis-initial-accuracy").hide();
		$("#analysis-reasonable-responses").hide();
		$("#analysis-reasonable-response-believability").hide();
		$("#analysis-subsequent-believability").hide();
		$("#analysis-rerate-emotional-intensity").hide();

		/* Next Button Functionality */
		
		$("#analysis-automatic-thoughts-next").click(function() {
			$("#analysis-automatic-thoughts").hide();						
			$("#analysis-initial-believability").show();	
			$("#pb2").progressBar((1/7)*100);
			$("#stepnumber").text(2);
			initializeSliders([1,2]);	
			$("#show-ant1").html($("#ant1").val());  //believability
			$("#show-ant1a").html($("#ant1").val()); //accuracy	
 			$("#show-ant1b").html($("#ant1").val()); //re-rating believability
			$("#show-ant2").html($("#ant2").val());
			$("#show-ant2a").html($("#ant2").val());
 			$("#show-ant2b").html($("#ant2").val()); 

 			// if they only listed one thought, then we don't need to have them evaluate thought #2
			if($("#ant2").val() == '') {
				$("#ant2-believability-wrapper").hide();
				$("#ant2-distortion-wrapper").hide();
				$("#ant2-reasonableretort-wrapper").hide();
				$("#ant2-reratebelievability-wrapper").hide();
				$("#ant2-retortbelievability-wrapper").hide();
			}
		});
		
		$("#analysis-initial-believability-next").click(function() {
			$("#analysis-initial-believability").hide();
			$("#analysis-initial-accuracy").show();
			$("#pb2").progressBar((2/7)*100);			
			$("#stepnumber").text(3);
			$("#tip-area").show();
			$("#tip-text").html('Click <a href="#" onclick="javascript:distortionPopup();">here</a> for a pop-up explanation of the distortions, in case you forgot!');
		});
		
		$("#analysis-initial-accuracy-next").click(function() {
			$("#tip-area").hide();
			$("#analysis-initial-accuracy").hide();
			$("#analysis-reasonable-responses").show();			
			$("#pb2").progressBar((3/7)*100);			
			$("#stepnumber").text(4);

			distortion1 = [];
			distortion1.push('<i>'+$("#distortion-1a :selected").text()+'</i>', '<i>'+$("#distortion-1b :selected").text()+'</i>', '<i>'+$("#distortion-1c :selected").text()+'</i>');								
			distortion1.push('<i>---</i>'); //hackish. when we remove --- two lines later, this ensure it is in the array.			
			distortion1 = distortion1.unique();
			distortion1.splice(distortion1.indexOf('<i>---</i>'), 1);
			distortion2 = [];
			distortion2.push('<i>'+$("#distortion-2a :selected").text()+'</i>', '<i>'+$("#distortion-2b :selected").text()+'</i>', '<i>'+$("#distortion-2c :selected").text()+'</i>');								
			distortion2.push('<i>---</i>'); //hackish. when we remove --- two lines later, this ensure it is in the array.
			distortion2 = distortion2.unique();
			distortion2.splice(distortion2.indexOf('<i>---</i>'), 1);
						
			if (distortion1.length <= 1) {
			    distortionMessage1 = distortion1.join();
			} else {
			    distortionMessage1 = distortion1.slice(0, -1).join(", ") + " and " + distortion1[distortion1.length-1];
			}
			if (distortion2.length <= 1) {
			    distortionMessage2 = distortion2.join();
			} else {
			    distortionMessage2 = distortion2.slice(0, -1).join(", ") + " and " + distortion2[distortion2.length-1];
			}
			$("#distortion-serial-1").html(distortionMessage1);
			$("#distortion-serial-2").html(distortionMessage2);
			
			$("#automatic-thought-1").html($("#ant1").val()); // populating "your automatic negative thought 1 was ...."
			$("#automatic-thought-2").html($("#ant2").val());											

		});
		
		$("#analysis-reasonable-responses-next").click(function() {
			$("#analysis-reasonable-responses").hide();
			$("#analysis-reasonable-response-believability").show();
			$("#pb2").progressBar((4/7)*100);			
			$("#stepnumber").text(5);
			initializeSliders([3,4]);		
			$("#reasonable-thought-1-show").html($("#reasonable-thought-1").val());  
			$("#reasonable-thought-2-show").html($("#reasonable-thought-2").val()); 							
		});
		
		$("#analysis-reasonable-response-believability-next").click(function() {
			$("#analysis-reasonable-response-believability").hide();
			$("#analysis-subsequent-believability").show();
			$("#pb2").progressBar((5/7)*100);			
			$("#stepnumber").text(6);
			initializeSliders([5,6]);									
		});
		
		$("#analysis-subsequent-believability-next").click(function() {
			$("#analysis-subsequent-believability").hide();
			$("#analysis-rerate-emotional-intensity").show();
			$("#pb2").progressBar(95);			
			$("#stepnumber").text(7);		
												
			var rerate = true;	
			var sliderlist = range(1,emotion_data.length,1);		
			for(var i=0; i<emotion_data.length;i++) {
				j = i+1; // array begins with 0 index but id/label names begin start at 1				
				$("#emotion"+j+"-label-rerate").html(emotion_data[i]['emotion']);
				$("#emotion"+j+"-row-rerate").show();
			}
			initializeSliders(sliderlist, rerate);	 // have to initialize the sliders AFTER they are visible	(css Display)
										
			
		});
		
		$("#analysis-rerate-emotional-intensity-next").click(function() {
		<?php if($demo_mode) { ?>
										
			alert('Thanks for checking out the demo of the core functionality. The full site is more fun and feature-filled.');
			window.location = 'index.php';
			
		<?php } else { ?>
			
			
			save_data = new Object();
			save_data['type'] = 'ants';
			save_data['ant1'] = $("#ant1").val();
			save_data['ant2'] = $("#ant2").val();
			save_data['distortion1'] = distortion1;
			save_data['distortion2'] = distortion2;					
			$.post('save.php', save_data, function(new_rank) {
				alert('Congratulations! Your EndAnts rank has increased to ' + new_rank + '. This is a jump of ' + rank_differential + ' spots!');
				window.location = 'home.php';
			});				
		<?php } ?>
		
		});
		
		/*subsequent
		rerate*/
		
		function initializeSliders(sliderList, rerate) {	
			$.each(sliderList, function(k) {				
				var isname = 'believability-slider-' + sliderList[k];
				var sv = '#slider' + sliderList[k] + '-value';				
				if(rerate == true) {
					var isname = 'intensity-slider-' + (sliderList[k]) + '-rerate';
					var sv    = '#intensity-slider-' + (sliderList[k]) + '-rerate-value';								
				}
				var dd = new Dragdealer(isname, {
					steps: 10,
					snap: true,
					animationCallback: (function(x) {						
						intensity_score = (x * 9) + 1; //Dragdealer has a weird callback value for x, indicating its horizontal position
						$(sv).html(intensity_score);
					})
				});	
				dd.setValue((4/9));			
			});		
		}
		
		function range ( low, high, step ) {
		    // http://kevin.vanzonneveld.net
		    // +   original by: Waldo Malqui Silva
		    // *     example 1: range ( 0, 12 );
		    // *     returns 1: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
		    // *     example 2: range( 0, 100, 10 );
		    // *     returns 2: [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100]
		    // *     example 3: range( 'a', 'i' );
		    // *     returns 3: ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i']
		    // *     example 4: range( 'c', 'a' );
		    // *     returns 4: ['c', 'b', 'a']

		    var matrix = [];
		    var inival, endval, plus;
		    var walker = step || 1;
		    var chars  = false;

		    if ( !isNaN( low ) && !isNaN( high ) ) {
		        inival = low;
		        endval = high;
		    } else if ( isNaN( low ) && isNaN( high ) ) {
		        chars = true;
		        inival = low.charCodeAt( 0 );
		        endval = high.charCodeAt( 0 );
		    } else {
		        inival = ( isNaN( low ) ? 0 : low );
		        endval = ( isNaN( high ) ? 0 : high );
		    }

		    plus = ( ( inival > endval ) ? false : true );
		    if ( plus ) {
		        while ( inival <= endval ) {
		            matrix.push( ( ( chars ) ? String.fromCharCode( inival ) : inival ) );
		            inival += walker;
		        }
		    } else {
		        while ( inival >= endval ) {
		            matrix.push( ( ( chars ) ? String.fromCharCode( inival ) : inival ) );
		            inival -= walker;
		        }
		    }

		    return matrix;
		}
		
		$("#stats_headline").html(username.capitalize() + "'s Stats");
		
	});
	
	</script>
	<title>Analysis Section</title>
</head>
<h1>Analysis of Your Thoughts</h1>
<div class="hud">
	<span class="profile-thumb"><img src="images/alert-overlay.png" height="50" width="50"></span>
	<div class="headline" id="stats_headline">* * *</div>
	<br /><br />
	You are currently <b>analyzing some Automatic Negative Thought responses</b>
	<div class="task-details">
		- You are on Step <span id="stepnumber">1</span> of 7<br />
		- Progress Completion: <span class="progressBar" id="pb2">0%</span>
	</div><br />

	Your AntRank is currently <b>#1154</b>, but finishing all seven steps will bump you up to <b>#1023</b>. 
	
	<br /><br />
	<div id="tip-area"><span style="color:blue">Tip:</span> <span id="tip-text"></span></div>
</div>
<div id="analysis-automatic-thoughts">
	<h2>Initial Response</h2>
	<label>Describe up to two Automatic Thoughts or Self-Talk</label>
	
	<br /><br /><h5>Thought one</h5>
	<img src="images/quotes1.jpg" id="quote1">
	<textarea class="automatic-thought" id="ant1" rows="7" cols="30"></textarea>
	<img src="images/quotes2.jpg" id="quote2">
	
		
	<br /><br /><h5>Thought two</h5>
	<img src="images/quotes1.jpg" id="quote1">
	<textarea class="automatic-thought" id="ant2" rows="7" cols="30"></textarea>
	<img src="images/quotes2.jpg" id="quote2">
	
	<br /><button type="submit" id="analysis-automatic-thoughts-next">Now, how much do you believe these thoughts?</button>		
</div>

<div id="analysis-initial-believability">
	<h2>Believability</h2>
	<label>Rate the believability of your thoughts, from 1-10</label>
	<br /><br />
				<div id="ant1-believability-wrapper">
					&quot;<span class="ant" id="show-ant1"></span>&quot;
					<div class="slider-believability">
						<div id="believability-slider-1" class="dragdealer">			
							<div class="red-bar handle" id="slider1-value">intensity-o-meter</div>
						</div>
					</div>
				</div>
				
				<br />
				<div id="ant2-believability-wrapper">
					&quot;<span class="ant" id="show-ant2"></span>&quot;
					<div class="slider-believability">
						<div id="believability-slider-2" class="dragdealer">			
							<div class="red-bar handle" id="slider2-value">intensity-o-meter</div>
						</div>
					</div>
				</div>

	<h3>Done adjusting?</h3>
	<button type="submit" id="analysis-initial-believability-next">Is it possible that some of these thoughts are irrational?</button>
</div>

<div id="analysis-initial-accuracy">
	<h2>Accuracy</h2>
	<label>Evaluate the accuracy of each thought. <br />Choose up to 3 distortions that might apply.<br />
		Click <a href="#" onclick="javascript:distortionPopup();">here</a> for a pop-up explanation of the distortions, in case you forgot!</label>
	<br /><br />
				&quot;<span class="ant" id="show-ant1a"></span>&quot;
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
						
				<br />
				
				<div id="ant2-distortion-wrapper">
					&quot;<span class="ant" id="show-ant2a"></span>&quot;
					<div class="distortion-list">(1)
						<select id="distortion-2a">
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
						<select id="distortion-2b">
							<option value="---">---</option>
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
						<select id="distortion-2c">
							<option value="---">---</option>
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
				</div>


	<h3>Figured out the distortions?</h3><button type="submit" id="analysis-initial-accuracy-next">Talk back with reasonable responses</button>
</div>
<div id="analysis-reasonable-responses">
	<h2>Reasonable Responses</h2>
	<label>Talk back! Change the distortions to more reasonable thoughts.</label>
	
	<br /><br /><h5>Reasonable Thought one</h5>
	<br />Your original thought was &quot;<i><span id="automatic-thought-1"></span></i>&quot;
	<br />How can you reply with a thought that is not <span id="distortion-serial-1" class="distortion-serial">&nbsp;</span>?
	<br />
	<img src="images/quotes1.jpg" id="quote1">
	<textarea class="automatic-thought" id="reasonable-thought-1" rows="7" cols="30"></textarea>
	<img src="images/quotes2.jpg" id="quote2">
	
		
	<br /><br />
	
	<div id="ant2-reasonableretort-wrapper">
		<h5>Reasonable Thought two</h5>
		<br />Your original thought was &quot;<i><span id="automatic-thought-2"></span></i>&quot;
		<br />How can you reply with a thought that is not <span id="distortion-serial-2" class="distortion-serial">&nbsp;</span>?	
		<br/>
		<img src="images/quotes1.jpg" id="quote1">
		<textarea class="automatic-thought" id="reasonable-thought-2" rows="7" cols="30"></textarea>
		<img src="images/quotes2.jpg" id="quote2">
	</div>
	
	<br /><button type="submit" id="analysis-reasonable-responses-next">Cool. Do you believe this?</button>		
</div>

<div id="analysis-reasonable-response-believability">
	<h2>Believability</h2>
	<label>Rate the believability of your new reasonable thoughts, from 1-10</label>
	<br /><br />
				&quot;<span class="rt" id="reasonable-thought-1-show"></span>&quot;
				<div class="slider-believability">
					<div id="believability-slider-3" class="dragdealer">			
						<div class="red-bar handle" id="slider3-value">intensity-o-meter</div>
					</div>
				</div>
				
				<br />
				<div id="ant2-retortbelievability-wrapper">
					&quot;<span class="rt" id="reasonable-thought-2-show"></span>&quot;
					<div class="slider-believability">
						<div id="believability-slider-4" class="dragdealer">			
							<div class="red-bar handle" id="slider4-value">intensity-o-meter</div>
						</div>
					</div>
				</div>

	<h3>Done adjusting?</h3><button type="submit" id="analysis-reasonable-response-believability-next">Now let's go back to your original thoughts</button>
</div>


<div id="analysis-subsequent-believability">
	<h2>RE-Rating Believability</h2>
	<label>RE-Rate the believability of your original responses, from 1-10</label>
	<br /><br />
				&quot;<span class="ant" id="show-ant1b"></span>&quot;
				<div class="slider-believability">
					<div id="believability-slider-5" class="dragdealer">			
						<div class="red-bar handle" id="slider5-value">intensity-o-meter</div>
					</div>
				</div>
				
				<br />
				<div id="ant2-reratebelievability-wrapper">
					&quot;<span class="ant" id="show-ant2b"></span>&quot;
					<div class="slider-believability">
						<div id="believability-slider-6" class="dragdealer">			
							<div class="red-bar handle" id="slider6-value">intensity-o-meter</div>
						</div>
					</div>
				</div>

	<h3>Done adjusting?</h3><button type="submit" id="analysis-subsequent-believability-next">Good! Now let's re-evalute your emotions</button>
</div>
<div id="analysis-rerate-emotional-intensity">
	<h2>Adjust Emotional Intensity</h2>
	<label>Finally, Re-Rate the intensity of each emotion from 1-10</label>

	<table>		
		<tr id="emotion1-row-rerate" class="emotion-rerate">
			<td id="emotion1-label-rerate" class="emotion-label">azsdff</td>
			<td>
				<div class="slider">
					<div id="intensity-slider-1-rerate" class="dragdealer">			
						<div class="red-bar handle" id="intensity-slider-1-rerate-value">intensity-o-meter</div>
					</div>
				</div>
			</td>
		</tr>
		<tr id="emotion2-row-rerate" class="emotion-rerate">
			<td id="emotion2-label-rerate"  class="emotion-label">asdfdsf</td>
			<td>
				<div class="slider">
					<div id="intensity-slider-2-rerate" class="dragdealer">			
						<div class="red-bar handle" id="intensity-slider-2-rerate-value">intensity-o-meter</div>
					</div>
				</div>
			</td>
		</tr>
		<tr id="emotion3-row-rerate" class="emotion-rerate">
			<td id="emotion3-label-rerate"  class="emotion-label">asdf</td>
			<td>
				<div class="slider">
					<div id="intensity-slider-3-rerate" class="dragdealer">			
						<div class="red-bar handle" id="intensity-slider-3-rerate-value">intensity-o-meter</div>
					</div>
				</div>
			</td>
		</tr>
	</table>
	<h3>Done adjusting?</h3><button type="submit" id="analysis-rerate-emotional-intensity-next">I'm done!</button>
	
</div>


</html>