<?php
/* congratulations.php is the final page/screen that a new licensee or an existing mediator will see as they go through the sign-up/renewal/newnewal process. It represents Step 4 in a four-step process for new mediators, and Step 3 in a three-step process for existing mediators. */
session_start(); // For reuse of $_SESSION['custom'], $_SESSION['Username'], $_SESSION['Password'], etc.
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>New Resolution Mediation Launch Platform&trade; | Profile Management</title>
<link href="salescss.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type='text/javascript' src="/scripts/windowpops.js"></script>
</head>

<body>
<!-- Start of Google Analytics -->
<script type="text/javascript"> 
var _gaq = _gaq || []; 
_gaq.push(['_setAccount', 'UA-1100858-3']); 
_gaq.push(['_trackPageview']); 
(function() { 
var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true; 
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js'; 
(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga); 
})(); 
</script>
<!-- End of Google Analytics -->
<div style="text-align: center;">
<table width="750" align="center" cellpadding="0" cellspacing="0" border="0">
<tr>
<td width="750"><img class="graphic" src="/images/platform.jpg" alt="Mediation Launch Platform Image" width="750" height="170" border="0"></td>
</tr>
<tr>
<td><img style="margin-top: 20px;" <?php if ($_SESSION['custom'] == 'newlicensee') echo 'src="/images/ProgressBarStep4new.jpg"'; else echo 'src="/images/ProgressBarStep3existing.jpg"'; ?> alt="Mediation Progress" width="750" height="108" border="0"></td>
</tr>
<tr>
<td align="left">
<?php
$thelocale = $_SESSION['LocaleSelected']; $thelocale = explode('_', $thelocale);
if ($_SESSION['custom'] == 'newlicensee') // Content for a new mediator
	{
?>
	<h2 style="margin-top: 40px;">Welcome!</h2>
	<p class='sales' style='margin-top:20px;'>Welcome aboard the New Resolution Mediation Launch Platform&trade;&nbsp;&hellip;&nbsp;and congratulations on joining the New Resolution network of independent professional mediators in the <?=$thelocale[0]; ?> locale!</p>
	<p class='sales' style='margin-top:20px;'>Now that you have a username (<i><?=$_SESSION['Username']; ?></i>) and password (<i><?=$_SESSION['Password']; ?></i>), you can create and publish your Mediator Profile live on the New Resolution site. Build your profile thoughtfully, and don&rsquo;t forget to check for typos, grammar, and spelling. The quality of your presentation may significantly influence a client&rsquo;s choice of mediator.</p>
	<p class='sales' style='margin-top:20px;'>To create and publish your profile on the New Resolution Mediation web site and to access licensed content in the Intellectual Property Library, you can either:</p>
	<p class='sales' style='margin-top:20px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Click the &ldquo;<a style="font-weight: bold" href="/index.shtml#footer" onClick="wintasticsecond('/index.shtml#footer'); return false;">Log In</a>&rdquo; link at the bottom of any page on the <a style="font-weight: bold" href="/index.shtml" onClick="wintasticsecond('/index.shtml'); return false;">New Resolution Mediation</a> web site</p>
	<p class='sales' style='margin-top:20px;'>or:</p>
	<div class='sales' style='margin-top:20px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Copy and paste the following into your browser:</div>
	<div class='sales' style='margin-top:6px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style="font-weight: bold" href="/scripts/updateprofile.php" onClick="wintasticsecond('/scripts/updateprofile.php'); return false;">www.newresolutionmediation.com/scripts/updateprofile.php</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!-- AddThis Button BEGIN via source http://www.addthis.com/?utm_source=hm&utm_medium=img&utm_content=ATLogo_orig&utm_campaign=AT_main -->
<a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4b58f6f37b2ee47d"><span style="position: relative; top: 2px;"><img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share" style="border:0"/></span></a><script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4b58f6f37b2ee47d"></script>
<!-- AddThis Button END -->
&nbsp;<span style="font-size: 10px; font-weight: bold">[bookmark]</span></div>
	<p class='sales' style='margin-top:30px;'>Good luck on this exciting venture to further your career in professional mediation! I wish you great success in fulfillment of your vision. In the meantime, please contact our support desk anytime for  assistance and service:</p>
	<p class='sales' style='margin-top:20px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="mailto:support@newresolutionmediation.com">support@newresolutionmediation.com</a></p>
	<p class='sales' style='margin-top:20px;'>Sincerely<br>
	<img src="/images/PaulRMerlynSignatureBlack.jpg" border="0" width="230" height="59" style="position: relative; left: -15px; top: 10px;"><br><br>Paul R. Merlyn<br>Chief Executive Officer<br>New Resolution LLC</p>
<?php
	}
else // Content for an existing (renewing or newnewing) mediator
	{
?>
	<h2 style="margin-top: 40px;">Welcome aboard the New Resolution Platform for a new term!</h2>
	<p class='sales' style='margin-top:20px;'>As a renewing member, you&rsquo;ve protected your  rights to a professional presence in the <?=$thelocale[0]; ?> locale at the lowest possible price. You&rsquo;ve also chosen the best way to promote your professional mediation practice. Congratulations on a smart investment!</p>
	<p class='sales' style='margin-top:20px;'>I hope you update your Mediator Profile from time to time. If you haven&rsquo;t recently, now is a great time for a tune up. As always, think about the needs of prospective clients, and don&rsquo;t forget to check for typos, grammar, and spelling. The quality of your presentation may significantly influence a client&rsquo;s choice of mediator.</p>
	<p class='sales' style='margin-top:20px;'>To log in and update your profile, you can either:</p>
	<p class='sales' style='margin-top:20px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Click the &ldquo;<a style="font-weight: bold" href="/index.shtml#footer" onClick="wintasticsecond('/index.shtml#footer'); return false;">Log In</a>&rdquo; link at the bottom of any page on the <a style="font-weight: bold" href="/index.shtml" onClick="wintasticsecond('/index.shtml'); return false;">New Resolution Mediation</a> web site</p>
	<p class='sales' style='margin-top:20px;'>or:</p>
	<div class='sales' style='margin-top:20px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Copy and paste the following into your browser:</div>
	<div class='sales' style='margin-top:6px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style="font-weight: bold" href="/scripts/updateprofile.php" onClick="wintasticsecond('/scripts/updateprofile.php'); return false;">www.newresolutionmediation.com/scripts/updateprofile.php</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!-- AddThis Button BEGIN via source http://www.addthis.com/?utm_source=hm&utm_medium=img&utm_content=ATLogo_orig&utm_campaign=AT_main -->
<a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4b58f6f37b2ee47d"><span style="position: relative; top: 2px;"><img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share" style="border:0"/></span></a><script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4b58f6f37b2ee47d"></script>
<!-- AddThis Button END -->&nbsp;<span style="font-size: 10px; font-weight: bold">[bookmark]</span></div>
<?php
	/* Retrieve this existing mediator's Username and Password from the DB */
	$db = mysql_connect('localhost', '', '')
		or die('Could not connect: ' . mysql_error());
	mysql_select_db('') or die('Could not select database');

	$query = "SELECT mediators_table.Username, userpass_table.Password FROM mediators_table, userpass_table WHERE mediators_table.ID=".$_SESSION['custom']." AND mediators_table.Username = userpass_table.Username";
	$result = mysql_query($query) or die('The SELECT Username/Password for an existing mediator failed i.e. '.$query.' failed: ' . mysql_error());
	$line = mysql_fetch_assoc($result);
	$Username = $line['Username'];
	$Password = $line['Password'];
?>
	<p class='sales' style='margin-top:30px;'>(As a reminder, your username is <i><?=$Username; ?></i> and your password is <i><?=$Password; ?></i>.)</p>
	<p class='sales' style='margin-top:30px;'>Good luck as you extend your journey into the very special world of professional mediation! I wish you continued fulfillment of your vision. In the meantime, please contact our support desk for any service we can provide: <a href="mailto:support@newresolutionmediation.com">support@newresolutionmediation.com</a></p>
	<p class='sales' style='margin-top:20px;'>Sincerely<br>
	<img src="/images/PaulRMerlynSignatureBlack.jpg" border="0" width="230" height="59" style="position: relative; left: -15px; top: 10px;"><br><br>Paul R. Merlyn<br>Chief Executive Officer<br>New Resolution LLC</p>
<?php

	/* Send notification email to Support that this mediator has just renewed/newnewed. (Note: There's no need to send an email for new licensees b/c Support receives one via licenseofframp.php in that case.) */
	$address = "support@newresolutionmediation.com";
	$subject = "An Existing Mediator Just Renewed";
	$body = "Hello New Resolution Support\n\n";
	$body .= "An existing mediator just renewed (or newnewed). Here are the details:\n
Item name: ".$_SESSION['item_name']."
Existing mediator ID: ".$_SESSION['custom']."
Payer name: ".$_SESSION['first_name']." ".$_SESSION['last_name']."
Payer email: ".$_SESSION['payer_email']."
Payment status: ".$_SESSION['payment_status']."
Gross amount: ".$_SESSION['mc_gross']."
Payment date: ".$_SESSION['payment_date']."
Payer city & state: ".$_SESSION['address_city'].", ".$_SESSION['address_state']."
Payer telephone: ".$_SESSION['contact_phone']."\n
[This message was autogenerated by congratulations.php.]";
	$headers = 
	"From: donotreply@newresolutionmediation.com\r\n" .
	"Bcc: paul@newresolutionmediation.com\r\n" .
	"X-Mailer: PHP/" . phpversion();
	mail($address, $subject, $body, $headers);
	}
?>
</td>
</tr></table>
</div>
<!-- Start of StatCounter Code -->
<script type="text/javascript">
var sc_project=5651836; 
var sc_invisible=1; 
var sc_partition=60; 
var sc_click_stat=1; 
var sc_security="97c14d16"; 
</script>

<script type="text/javascript"
src="http://www.statcounter.com/counter/counter.js"></script><noscript><div
class="statcounter"><a title="joomla counter"
href="http://www.statcounter.com/joomla/"
target="_blank"><img class="statcounter"
src="http://c.statcounter.com/5651836/0/97c14d16/1/"
alt="joomla counter" ></a></div></noscript>
<!-- End of StatCounter Code -->
</body>
</html>
