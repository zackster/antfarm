<?php
require_once('utilities.php');
if(!isset($_COOKIE['source'])) {
	if(isset($_SERVER['HTTP_REFERER'])) {
		setcookie('source', $_SERVER['HTTP_REFERER'], time()+60*60*24*30);		
	}
}
if(isset($_REQUEST['r'])) {
	setcookie('user_referrer', $_REQUEST['r'], time()+60*60*24*30);
}

?><html>
<head>
	<meta name="title" content="EndAnts: Get in touch with reality" />
	<meta name="description" content="EndAnts is a fun app that makes you happier by destroying Automatic Negative Thoughts." />
	<link rel="image_src" href="http://www.endants.com/images/logo.jpg" / >		 
	<link rel="shortcut icon" href="http://www.endants.com/images/favicon.ico" />
	<title>Destroy Automatic Negative Thoughts</title>
	<link rel="stylesheet" href="style.css">
	<script type="text/javascript" src="utilities.js"></script>
	<script type="text/javascript" src="jquery.js"></script>	
	<script type="text/javascript" src="thirdparty/galleria/galleria.js"></script>
	<script type="text/javascript">
	Galleria.loadTheme('thirdparty/galleria/themes/dots/galleria.dots.js');
	Galleria.debug = true;
	</script>	
	<script>
	function showRegistration() {
		$("#content-shadow").remove();
		$("div#screenshot-preview").remove();
		$("#registration_field").show();
		$("a#questions").remove();
	}
	function showLogin() {
		$("#login_field").show();
		$("#registration_field").hide();
	}
	

	function registerUser() {
		var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
		if($("#reg_password").val().length < 3) {
			alert('Your password is too short. It must be at least 3 characters.');
			return false;
		}
		if($("#reg_username").val().length < 1) {
			alert('Please enter a username.');
			return false;
		}
//		if(filter.test($("#reg_email").val()) || $("#reg_email").val().indexOf('+')!=-1) {
//		if($("#reg_email").val().indexOf('@') !=-1 && $("#reg_email").val().indexOf('.')!=-1) { 
		if(validEmail($("#reg_email").val())) {
			$("#reg_form").submit();
		}
		else {
			alert('Enter a valid email, please.');
		}
	}
	
	$(document).ready(function() {
		$("#faq").hide();
		$("input#tryit-field").val("I forgot to buy airline tickets");
		
		$("input#tryit-field").click(function(){

			$(this).css('color','black');
			$(this).val('');
		});
		$("input#tryit-field").keydown(function(e) { 		
			if (e.keyCode === 13) { 
				
				showRegistration();
				
			} 
		});

		$('#galleria-images').galleria({
			height: '300',
			debug: true
		});
		
		$("button.login-button").click(function() {
			$("#login_form").submit();	
		});
		
//		$("#login_field").hide();
		$("#registration_field").hide();
		$("#cbt_source").toggle(function() {
			$("#cbt_review").show();
		}, function() {
			$("#cbt_review").hide();
		});


		$("a#questions").click(function() {
			$(this).hide();
			$("#faq").show();
		})



<?php if(isset($_GET['badlogin'])) { ?>
		$("#error_message").show();
<?php } elseif(isset($_GET['bademail'])) { ?>
		$("#error_message").show();
		$("#error_message").html('You entered an invalid email address');
<?php }	elseif(isset($_GET['regerr'])) { ?>
		$("#error_message").show();
		showRegistration();
		$("#error_message").html('Please enter a unique username and email address.');
<?php } elseif(isset($_GET['demo'])) { ?>
		$("#error_message").show();
		$("#error_message").html('You have already checked out the demo. Please make a free account to continue using the tool.');
<? } ?>
	
	
	});
	</script>
<?php add_google_analytics_tracking(); ?>	
</head>
<body>
	<div id="endants-header">
		<div id="endants-logo">
			<img src="images/endants-monotype.png">
		</div>
		<div id="endants-login-field">
			<div id="login_field">
				<form id="login_form" action="login.php" method="post">
					<fieldset class="login-field">
						<table>
							<tr>
								<td><input type="text" name="login_email" class="login-field" id="login_email"></td>
								<td><input type="password" name="login_password" class="login-field" id="login_password"></td>
								<td><button class="login-button">login</button></td>
							</tr>
							<tr>
								<td><label class="login-field">email / username</label></td>
								<td><label class="login-field">password</label></td>
								<td> </td>
							</tr>
						</table>



					</fieldset>
				</form>
			</div>	
		</div>
	</div>
	<div id="endants-content">
		
			
		<div id="error_message">
			Wrong username/password combination.
		</div>
		

		<div id="screenshot-preview">

			<div id="galleria-images">
				<img src="images/Picture 9-cropped-step.png">
				<img src="images/Picture 10-cropped-step.png">
				<img src="images/Picture 11-cropped-step.png">
				<img src="images/Picture 12-cropped-step.png">
				<img src="images/Picture 13-cropped-step.png">
				<img src="images/Picture 14-cropped-step.png">	
				<img src="images/Picture 15-cropped-step.png">
			</div>
			<label style="text-align: center">Screenshots of EndAnts</label>
			
		</div>
		<div id="endants-main-content">
			<div id="content-shadow">
				<h1 style="color:black">EndAnts makes you <span class="feel-better">feel better</span></span></h1> 
				<h3 style="color:#37599E">What's been troubling you lately?</h3>
				<input id="tryit-field" class="tryit" value="I forgot to book airline tickets">
				<button class="tryit-button" id="tryit-demo" onclick="showRegistration()">Get Started</button>											
				
				
			</div>
			
				
			<div id="registration_field" >
				<p style="margin-left: 30px;font-size:1.3em;background-color:#FAFAD2">We saved your grievance information. Registration takes 20 seconds and only requires filling out these 4 fields.</p>
				<form id="reg_form" action="registration.php" method="post">
					<fieldset>
						<p class="field">
							<label class="field">username</label>
							<input type="text" name="reg_username" class="field" id="reg_username">								
						</p>
						<p class="field">
							<label class="field">email</label>
							<input type="text" name="reg_email" class="field" id="reg_email">
						</p>
						<p class="field">
							<label class="field">password</label>
							<input type="password" name="reg_password" class="field" id="reg_password">								
						</p>


						<p class="field">
							<label class="field">notifications</label>
							<input type="checkbox" name="email_updates" checked> send me updates about major changes<br />
							<span style="font-size:.8em"><i>we will <u>never</u> spam you or sell your email</i></span>
						</p>

						<p class="field">
							<label class="field"></label>
							<a href="#" onclick="registerUser()"><span style="font-size:1.3em">Register</span></a>
						</p>



					</fieldset>
				</form>
			</div>
		
			
			
			<div id="content-explanation">
<br />		


			</div>
			
			<br /><br />
			
<center>			<a href="#" id="questions">Questions?</a></center>
		

			<div id="faq">
					<center> <h2>FAQ</h2>
				
						<question>Why is the site called EndAnts?</question>
						<answer>Because it is designed to reduce <b>A</b>utomatic <b>N</b>egative <b>T</b>houghts: ANTs.</answer>
				
						<question>What is an automatic negative thought?</question>
						<answer>Often, when something troubles us, we will automatically think up an explanation to justify the emotion. For example, we might think "I suck" after a presentation at work doesn't go well; that is a negative thought. Negative thoughts are the enemy of happiness. Since our life is very much determined by our mind, our thoughts can make or break our life. Negative thoughts will distract your focus from what's important and will drain your energy.</answer>
						
						<question>What are some benefits to reducing negative thoughts?</question>
						<answer>Increased optimism: thinking positive makes us happier. Optimists will live longer, be happier, demonstrate better survival rates for cancer, perform better in sports, especially after defeat, and advance more quickly in their careers. <label>Source: Martin Seligman, <a href="http://www.amazon.com/dp/1400078393/?tag=httpwwwhiph02-20" target="_blank">Learned Optimism</a></label></answer>
						
						<question>Can this help me with depression?</question>
						<answer>Yes! EndAnts uses a technique similar to Cognitive Behavioral Therapy, which has demonstrated to be more effective than anti-depressants in treating depression. </answer>
						
						<question>Is there any science to back this up?</question>
						<answer>Yes, there's a lot. You can start with <a href="http://www.ncbi.nlm.nih.gov/pubmed/16199119" target="_blank">this meta-review</a>: <br /><br /><span id="cbt_review">Butler, A.C., Chapman, J.E., Forman, E.M., &amp; Beck, A.T. (2006). The empirical status of cognitive-behavioral therapy: A review of meta-analyses. <i>Clinical Psychology Review, 26(1),</i> 17-31.</span></answer>
						
						<question>How do I contact you?</question>
						<answer>Please email zachary@endants.com</answer>
						
			
				
					</center>
			
			</div>
		</div>
		
		
	</div>
</body>
</html>

	
<?php 
/*


<button onclick="showRegistration()">Register</button>
<button class="login" onclick="showLogin()">Log In</button>
<?php // <button class="demo" onclick="javascript:window.location='dtr.php?demo_mode'">Demo</button> ?>
<br /><br />




<br /><br />
<br /><br />

<label>Disclaimer: By using EndAnts, you agree to indemnify and hold harmless it and its creators from any liability, damage or cost to you or any third party due to or arising out of access to the site. We are greatly indebted to many people for their work in Cognitive Behavioral Therapy &amp; this site is heavily inspired by <a href="http://www.amazon.com/dp/1572242523/?tag=httpwwwhiph02-20" target="_blank">The Self-Esteem Workbook</a> by Glenn Schiraldi. EndAnts is no replacement for a doctor. If you are feeling suicidal, please call  <big>1-800-273-8255</big>.</label>
<hr>

	
	<center>
	<label>
		Built by <a href="http://www.zacharyburt.com" target="_blank">Zachary Burt</a>. Contact me at zackster<b>@</b>gmail.<span>com</span>.
		<a href="http://www.endants.com/blog/" target="_blank">EndAnts blog</a>
	</label>
	</center>
	<hr>
	

	<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.endants.com&amp;layout=standard&amp;show_faces=false&amp;width=450&amp;action=like&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:80px;" allowTransparency="true" align="middle"></iframe>
	
-->
*/ ?>