<?php 
/* iplibrary.php is the gateway to New Resolution's IP Library. Much of the (complex) logic flow in iplibrary.php draws from workflow developed for updateprofile.php. Having (i) identified as a client mediator (cf demo mediator), (ii) logged in (either directly from iplibrary.php's log-in screen or indirectly via updateprofile.php's screen), and (iii) clicked "I Agree" to the IP acknowledgement notice, the user can then navigate to various pages (e.g. logo, business cards, Wires Crossed, postcard) of downloadable files via the Milonic tramline DHTML menu. */
// Start a session
session_start();
header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
header( "Cache-Control: no-store, no-cache, must-revalidate" );
header('Cache-Control: post-check=0, pre-check=0', FALSE); 
header( "Pragma: no-cache" );
ob_start(); // Used in conjunction with ob_flush() [see www.tutorialized.com/view/tutorial/PHP-redirect-page/27316], this allows me to postpone issuing output to the screen relating to $UploadFile until after the header has been sent.
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Pragma" content="no-cache">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="Expires" CONTENT="Mon, 26 Jul 1997 05:00:00 GMT">
<title>New Resolution Mediation Launch Platform&trade; | IP Library</title>
<link href="/nrcss.css" rel="stylesheet" type="text/css">
</head>
<?php
// The display of these JS alerts on load is controlled by session variables that are set in changeuserpassform_slave.php and resetuserpassform_slave.php after user clicked the 'Change Username/Password' link in renew.php's (or in updateprofile.php's or iplibrary.php's) log-in screen.
if ($_SESSION['DisplaySuccessAlert'] == true) echo '<body onload = "alert(\'Confirmation of your new username/password has been emailed to you. You may now log in using your new username ('.$_SESSION['NewUsername'].') and password ('.$_SESSION['NewPassword'].').\')">'; elseif ($_SESSION['DisplayUnchangedAlert'] == true) echo '<body onload = "alert(\'Your username/password have not been changed. You may log in using your existing Username ('.$_SESSION['ExistingUsername'].') and Password ('.$_SESSION['ExistingPassword'].').\n\nWe have also emailed you confirmation of your existing username and password.\')">'; elseif ($_SESSION['ResetSuccessAlert'] == true) echo '<body onload = "alert(\'Your username and password have been reset. Details have been emailed to your registered email address.\n\nFor further assistance, please contact our support desk:\nsupport@newresolutionmediation.com\')">'; elseif ($_SESSION['ResetDeniedAlert'] == true) echo '<body onload = "alert(\'For security reasons, your reset request is denied. The email address you entered is not recognized as registered under your Mediator Profile.\n\nFor further assistance in reseting your username and password, please contact our support desk:\nsupport@newresolutionmediation.com\')">'; else echo '<body>'; unset($_SESSION['DisplaySuccessAlert']); unset($_SESSION['DisplayUnchangedAlert']); unset($_SESSION['ResetSuccessAlert']); unset($_SESSION['ResetDeniedAlert']);
?>
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

<?php
// Create short variable names
$Username = $_POST['Username'];
$Password = $_POST['Password'];
$AccessLibraryLogIn = $_POST['AccessLibraryLogIn'];
$AgreeToIP = $_POST['AgreeToIP'];
$LoggedOut = $_POST['LoggedOut'];

// Note: values of these "path" session variables are defined by action script democlientpaths1.php. For situations in which the check-box screen isn't displayed (and hence action script democlientpaths1.php isn't run) because the user went straight to the log-in screen because he/she was referred to updateprofile.php or iplibrary.php by the sales.php page, the path session variables also need to be defined below according to the value determined for $_SESSION['ClientDemoSelected'] by action script democlientpaths1.php
$imagepathshort = $_SESSION['imagepathshort'];
$dbmediatorstablename = $_SESSION['dbmediatorstablename'];
$imagepathlong = $_SESSION['imagepathlong'];
$scriptpathshort = $_SESSION['scriptpathshort'];
$pagepathshort = $_SESSION['pagepathshort'];
$ssipathlong = $_SESSION['ssipathlong'];

if (isset($LoggedOut))
{
	unset($LoggedOut);
	$_SESSION['SessValidUserFlag'] = 'false';
	if (isset($_SERVER['HTTP_REFERER'])) // Antivirus software sometimes blocks transmission of HTTP_REFERER.
		{
		header("Location: /scripts/updateprofile.php"); // Go back to previous page. (Alternative to echoing the Javascript statement: history.go(-1) or history.back()
		}
	else
		{
		echo "<script type='text/javascript' language='javascript'>window.location = '/scripts/updateprofile.php';</script>";
		ob_flush();
		};
	exit;
}

if (!isset($_SESSION['ReferredFromSales']) && !isset($_SESSION['ClientDemoSelected'])) // By-pass this initial screen if referred from the 'Test Drive Now' button on sales.php or if the ClientDemoSelected session variable was set by democlientpaths1.php.
	{
?>
	<form method="post" action="/scripts/democlientpaths1.php">
	<table border="0" width="810" style="margin-left: 50px; margin-right: 50; margin-top:50px;">
	<tr>
	<td width="60px" align="center">
	<input type="checkbox" name="AccessLevel" value="client" onclick="if (this.checked) this.form.submit();">
	</td>
	<td>
	<label class="big">I am a New Resolution mediator. I want to access my profile and the proprietary content library.</label>
	</td>
	</tr>
	<tr><td height="12px" colspan="2">&nbsp;</td></tr>
	<tr>
	<td width="60px" align="center">
	<input type="checkbox" name="AccessLevel" value="demo" onclick="if (this.checked) this.form.submit();">
	</td>
	<td>
	<label class="big">I am a prospective mediator. I want to test drive the New Resolution Mediation Launch Platform&trade;.</label>
	</td>
	</tr>
	<tr>
	<td height="40px" colspan="2"><span class="basictextboldsmaller">[check one]</span></td>
	</tr>
	</table>
	</form>

<?php
	}

if ($_SESSION['SessValidUserFlag'] != 'true' && (isset($_SESSION['ClientDemoSelected']) || isset($_SESSION['ReferredFromSales']))) // Only show the log-in screen if the user hasn't already been validated from a previous successful log in AND (either the user has been through the previous "demo vs client screen (see above) such that democlientpaths1.php set the 'ClientDemoSelected' session variable OR the 'ReferredFromSales' session variable has been set because the user was referred here from the sales.php page.
{

unset($_SESSION['Guest']); // Default assumption is that user who will log in is not going to use guest/pass log in. Clear the session variable so a full account holder (i.e. a user with a username/password stored in the mediators_table) can gain full access (cf. greyed out buttons) to the IP library.

	if (empty($Username) || empty($Password))
	{
	echo '<h1 class="bannerhead" style="margin-left:148px; position:relative; top: 40px;">Mediator Log-In</h1>';
	$_SESSION['ClientDemoSelected'] == 'client'; // Only the client level is relevant to the iplibrary.
	echo '<h3 style="margin-left:180px; position:relative; top: 40px;">(Client Access)</h3>';
	// Set path session variables (used by updateprofile.php and processprofile.php) accordingly for client level.
	$_SESSION['imagepathshort'] = '../images/';
	$_SESSION['dbmediatorstablename'] = 'mediators_table';
	$_SESSION['imagepathlong'] = '/home/paulme6/public_html/nrmedlic/images/';
	$_SESSION['scriptpathshort'] = '../scripts/';
	$_SESSION['pagepathshort'] = '../';
	$_SESSION['ssipathlong'] = '/home/paulme6/public_html/nrmedlic/ssi/';

	// Visitor needs to enter a username and password
	$_SESSION['SessValidUserFlag'] = 'false';
	?>
	<div style="margin-left:100px;">
	<br><br><br>
	<h3>Please log in to access library content:</h3>
	<form method="post" action="iplibrary.php">
	<table border="0" width="230" style="margin-left:0px;">
	<?php
	if (isset($_SESSION['Guest']) || $_SESSION['ReferredFromSales'] == 'true'): ?>
	<tr>
	<td colspan='2' style='color:#FF0000; height: 65px;'>
	<div style="border-width: 1px; border-style: solid; border-color: red; padding: 10px; width: 210px;"><span class="basictext"><b>Just visiting? For a sneak preview&hellip;</b><br>
	    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Enter username:&nbsp;&nbsp;<kbd style="color: red;">guest</kbd><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Enter password:&nbsp;&nbsp;&nbsp;<kbd style="color: red;">pass</kbd></span></div><br>
	</td>
	</tr>
	<?php endif; ?>
	<tr>
	<td><label for="Username">Username:&nbsp;</label></td>
	<td><input type="text" name="Username" id="Username" style="width:125px" maxlength="20" size="20" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'"></td>
	<!-- The style attribute ensures IE/Firefox consistency -->
	</tr>
	<tr>
	<td><label for="Password">Password:&nbsp;</label></td>
	<td><input type="password" name="Password" id="Password" style="width:125px" maxlength="40" size="20" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'"></td>
	</tr>
	<tr>
	<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
	<td colspan="2" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="AccessLibraryLogIn" class="buttonstyle" value="Access Library"></td>
	</tr>
	</table>
	</form>
	<div style="position: absolute; left:186px; top: 10px;">
	<form method="post" action="/scripts/startover.php">
	<input type="submit" class="submitLinkSmall" value="Demo/Client">
	</form>
	</div>

	<!--  As with display of "test/drive" instructions above, don't display the 'Forgot Username/Password' and 'Change Username/Password' for test-drivers or for demo mediators (because it's excessive/superfluous). -->
	<div style="display: inline; position: absolute; left:50px; top: <?php if (isset($_SESSION['Guest']) || $_SESSION['ReferredFromSales'] == 'true') echo '400px;'; else echo '300px;'; ?>">
	<a class="submitLinkSmall" style="font-size: 10px;" href="/scripts/resetuserpassform.php" onclick="window.open('/scripts/resetuserpassform.php','','height=400,width=650,top=30,left=290,scrollbars=yes,menubar=no,toolbar=no,location=no,status=yes'); return false;">Reset Username/Password</a>
	</div>

	<div style="display: inline; position: absolute; left: 280px; top: <?php if (isset($_SESSION['Guest']) || $_SESSION['ReferredFromSales'] == 'true') echo '400px;'; else echo '300px;'; ?>">
	<a class="submitLinkSmall" style="font-size: 10px;" href="/scripts/changeuserpassform.php" onclick="window.open('/scripts/changeuserpassform.php','','height=615,width=750,top=30,left=290,scrollbars=yes,menubar=no,toolbar=no,location=no,status=yes'); return false;">Change Username/Password</a>
	</div>

	</div>
	<?php
	}
}

if (($_SESSION['SessValidUserFlag'] == 'true') || ((!empty($Username)) && (!empty($Password))))
{
// If the SessValidUserFlag is 'true', then the user has already been previously validated and his/her username and password would have been stored in session variables. In this case, we can set the $Username and $Password to those session variable values.
if ($_SESSION['SessValidUserFlag'] == 'true' && !isset($_SESSION['Guest'])) { $Username = $_SESSION['SessUsername']; $Password = $_SESSION['SessPassword']; };

// Connect to mysql, using either the username and password that the user just typed into the above form, or using stored values for username and password that are stored in their respective session variables.
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
or die('Could not connect: ' . mysql_error());
mysql_select_db('paulme6_newresolution') or die('Could not select database');

// Query the database to see if a record exists with a username-password that matches the values in the POST array. Since the password typed by the user live is different from the encoded password that's retrieved from the database and stored in a session variable, we need to create two different $query strings -- one for the live human path and one for the already-validated path.
if ($_SESSION['SessValidUserFlag'] == 'true')
{ $query = "select count(*) from ".$dbmediatorstablename." where
		Username = '$Username' and 
		Password = '$Password'"; }
else
{ $query = "select count(*) from ".$dbmediatorstablename." where
		Username = '$Username' and 
		Password = sha1('$Password')";};

$result = mysql_query($query) or die('Username-password query failed: ' . mysql_error());

$row = mysql_fetch_row($result); // $row array should have just one item, which holds either '0' or '1'
$count = $row[0];

if ($count == 1 || ($Username == 'guest' && $Password == 'pass') || isset($_SESSION['Guest']))
	{
	if ($Username == 'guest' && $Password == 'pass') $_SESSION['Guest'] = true;
	// The visitor's username and password were correct. (Remember also to query database for ID and store it in session variable so it's accessible later by processprofile.php.) Again, create two different $query strings, one for the live human path and the other for when the password was retrieved in encoded form from the database.
	if ($_SESSION['SessValidUserFlag'] == 'true')
		{
		$query = "select * from ".$dbmediatorstablename." where 
			Username = '$Username' and 
			Password = '$Password'"; 
		}
	else 
		{
		 $query = "select * from ".$dbmediatorstablename." where 
			Username = '$Username' and 
			Password = sha1('$Password')"; 
		};
			
	$result = mysql_query($query) or die ('Failed to retrieve ID for username-password in iplibrary.php: '.mysql_error());
	$line = mysql_fetch_array($result);
	
	// Store ID, Username, Password, Locale, and validation user flag as session variables, which are necessary because data in the $_POST[] array would get forgotten on leaving iplibrary.php.
	$_SESSION['SessID'] = $line['ID'];
	$_SESSION['SessUsername'] = $line['Username'];
	$_SESSION['SessPassword'] = $line['Password'];
	$_SESSION['SessLocale'] = $line['Locale'];
	$_SESSION['SessValidUserFlag'] = 'true'; // Set a 'valid user' flag so user doesn't have to log in again when the iplibrary.php or updateprofile.php page is reloaded.

	// Even though user has successfully logged in, eject user if the AdminFreeze column pertaining to his/her ID is '1' (i.e. true). Also set the SessValidUserFlag to false.
	if ($line['AdminFreeze'] == 1)
		{
		echo '<br><p class=\'basictext\' style=\'margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px;\'>Your account (username = &lsquo;'.$line['Username'].'&rsquo;) has been frozen. Please email Client Services (support@newresolutionmediation.com) immediately to reactivate your account. Thank you.</p>';
		$_SESSION['SessValidUserFlag'] = 'false'; // Allows user to try to log in again via iplibrary.php or updateprofile.php (though the attempt will result in ejection unless the account has been unfrozen by an Administrator).
		exit;
		}
?>


	<div style="position: absolute; top: 5px; left: 410px; padding: 0px;">
	<form name="LogOutForm" method="post" action="/iplibrary.php">
	<input type="hidden" name="LoggedOut" id="LoggedOut" value="true">
	<input type="submit" class="submitLinkSmall" value="Log Out">
	<!-- I've disguised the submit button as a link -->
	</form> 
	</div>

	<div id="verticalline">
	<h1 style="margin-left:300px; position:relative; top: 20px; font-size: 22px; color: #425A8D;">Intellectual Property Library</h1>
	<table border="0" width="800" style="margin-left: 0px;">
	<tr>
	<td colspan="5"><HR align="left" size="2px" noshade color="#FF9900" style="margin-top: 22px; size: 2px; height: 0px; position: relative; right: 0px;"></td>
	</tr>
	<tr>
	<td>
	<?php
	if (isset($AgreeToIP) && !$AlreadyAgreed)
		{
		unset($AgreeToIP);
		$AlreadyAgreed = false;
	?>
		<script language="javascript" type="text/javascript">
		<!--
		window.location = "/iplogo.php";
		// -->
		</script>
		<noscript>
	<?php
		if (isset($_SERVER['HTTP_REFERER'])) // Antivirus software sometimes blocks transmission of HTTP_REFERER.
			{
			header("Location: iplogo.php"); // Now that the user has clicked 'I Agree', send iplogo.php to the browser.
			}
			ob_flush();
	?>
		</noscript>
	<?php
		}
	else
		{
	?>
		<h2 style="margin-left: 410px;">Notice</h2>
		<p class="basictextbold" style="margin-top: 20px; margin-left: 150px; margin-right: 5px; margin-bottom: 30px;">Content in this library is the intellectual property (IP) of New Resolution LLC. Access to and use of the content herein is restricted to mediators who hold current licenses to the New Resolution Mediation Launch Platform&trade;. In proceeding, I acknowledge that I am authorized to access this content. As an authorized user, I will use any such content in the format provided without modification. I further acknowledge that I may be subject to civil and criminal penalties for unauthorized usage of proprietary content.</p>
		<form action="/iplibrary.php" method="post">
		<div style="margin-left: 360px;"><input type="submit" name="AgreeToIP" class="buttonstyle" value="I Agree">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="cancel" class="buttonstyle" value="Cancel" onClick="location.href='/scripts/updateprofile.php'"></div>
		</form>
	<?php
		}
	?>
	</td>
	</tr>
	</table>
	<br><br>
	</div>
	<?php
	}
else
	{
	// The visitor's username and password were not found in the database.
	echo "<br><p class='basictext' style='margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px; font-weight: bold;'>Incorrect username or password. Please use your browser&rsquo;s Back button or <a href='/iplibrary.php' style='font-size: 14px;'>click here</a> to try again.</p>";
	$Username = null;
	$Password = null;
	}

// Closing connection
mysql_close($db);

}
?>
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