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
	<title>Destroy Automatic Negative Thoughts</title>
	<link rel="stylesheet" href="style.css">
	<script type="text/javascript" src="utilities.js"></script>
	<script type="text/javascript" src="jquery.js"></script>	
	<script>
	function showRegistration() {
		$("#login_field").hide();
		$("#registration_field").show();
	}
	function showLogin() {
		$("#login_field").show();
		$("#registration_field").hide();
	}
	
	function loginUser() {
		$("#login_form").submit();
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
		$("#login_field").hide();
		$("#registration_field").hide();

<?php if(isset($_GET['badlogin'])) { ?>
		$("#error_message").show();
<?php } elseif(isset($_GET['bademail'])) { ?>
		$("#error_message").show();
		$("#error_message").html('You entered an invalid email address');
<?php }	elseif(isset($_GET['regerr'])) { ?>
		$("#error_message").show();
		$("#error_message").html('Please enter a unique username and email address.');
<?php } elseif(isset($_GET['demo'])) { ?>
		$("#error_message").show();
		$("#error_message").html('You have already checked out the demo. Please make a free account to continue using the tool.');
<? } ?>
	
	});
	</script>
<?php add_google_analytics_tracking(); ?>	
</head>
<img src="images/logo.jpg" align="right">
<div id="error_message">
	Wrong username/password combination.
</div>
<h1> What are negative thoughts?</h1>
<p>Negative thoughts are the enemy of happiness. Since our life is very much determined by our mind, our thoughts can make or break our life. Negative thoughts will distract your focus from what's important and will drain your energy.<br />Most of the time, they happen automatically.</p>
<h1>Why should I care about eliminating them?</h1>
<h2>Thinking Realistic &amp; Positive Makes Us Happier</h2>
<p>Optimists will...</p>
<ul id="optimism-benefits">
	<li>Live longer</li>
	<li>Be happier</li>
	<li>Demonstrate better survival rates for cancer</li>
	<li>Perform better in sports, especially after defeat</li>
	<li>Advance more quickly in their careers</li>
	<li class="source"><label>Source: Martin Seligman, <a href="http://www.amazon.com/dp/1400078393/?tag=httpwwwhiph02-20" target="_blank">Learned Optimism</a></label></li>
</ul>

</ul>

<p>So welcome to EndAnts: the <b>A</b>utomatic <b>N</b>egative <b>T</b>hought destruction clinic</p>
<button onclick="showRegistration()">Register</button>
<button class="login" onclick="showLogin()">Log In</button>
<button class="demo" onclick="javascript:window.location='dtr.php?demo_mode'">Demo</button>
<br /><br />
<div id="login_field" class="field">
	<form id="login_form" action="login.php" method="post">
		<fieldset>
			<p class="field">
				<label class="field">email</label>
				<input type="text" name="login_email" class="field" id="login_email">
			</p>
			<p class="field">
				<label class="field">password</label>
				<input type="password" name="login_password" class="field" id="login_password">								
			</p>
			<p class="field">
				<label class="field"><a href="#" onclick="loginUser()">Log In</a></label>
			</p>
			


		</fieldset>
	</form>
</div>
<div id="registration_field" class="field">
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
				<label class="field"><a href="#" onclick="registerUser()">Register</a></label>
			</p>
			
		</fieldset>
	</form>
</div>



<br /><br />
<br /><br />

<label>Disclaimer: By using EndAnts, you agree to indemnify and hold harmless it and its creators from any liability, damage or cost to you or any third party due to or arising out of access to the site. We are greatly indebted to many people for their work in Cognitive Behavioral Therapy &amp; this site is heavily inspired by <a href="http://www.amazon.com/dp/1572242523/?tag=httpwwwhiph02-20" target="_blank">The Self-Esteem Workbook</a> by Glenn Schiraldi. EndAnts is no replacement for a doctor. If you are feeling suicidal, please call  <big>1-800-273-8255</big>.</label>
<hr>

	
	<center>
	<label>
			Built by <a href="http://www.zacharyburt.com" target="_blank">Zachary Burt</a>. Contact me at zackster<b>@</b>gmail.<a />com.
	</label>
	</center>

</html>