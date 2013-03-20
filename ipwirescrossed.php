<?php
/* 
ipwirescrossed.php is one of the files in the Intellectual Property Library. Components of the library are: iplogo.php, ipbusinesscard.php, ipletterhead.php, ipemailsig.php, ipwirescrossed.php, and ippostcard.php. These files are accessible via the Milonic tramline menu after a user has successfully logged in (i.e. gotten his/her $_SESSION['ValidUserFlag'] set to 'true') via the log-in screen in either updateprofile.php or iplibrary.php. */
// Start a session
session_start();
if ($_SESSION['SessValidUserFlag'] != 'true')
	{
	echo "<br><p style='font-family: Arial, Helvetica, sans-serif; margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px; font-weight: bold;'>Access to this page is restricted to New Resolution LLC clients and validated users.</p>";
	exit;
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<!-- The ipwirescrossed.php file is opened when a user clicks on the 'Wires Crossed' menu item in Milonic tramline menu item (see /milonic_tramline/menu_data.js) inside iplibrary.php. It calls action script ipwirescrossed_slave.php upon click of the 'Create' button -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>IP Library | Wires Crossed&trade;</title>
<link href="/nrcss.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/milonic_tramline/milonic_src.js"></script>
<noscript>This DHTML Menu requires JavaScript. Please adjust your browser settings to turn on JavaScript</noscript>
<script type="text/javascript" src="/milonic_tramline/mmenudom.js"></script> 
<script type="text/javascript" src="/milonic_tramline/menu_data.js"></script> 
<script type="text/javascript" language="javascript" src="/scripts/boxover.js"></script>
<script language="JavaScript" src="/scripts/emailaddresschecker.js" type="text/javascript"></script>
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
<div style="position: absolute; top: 5px; left: 480px; padding: 0px;">
<form name="LogOutForm" method="post" action="/iplibrary.php">
<input type="hidden" name="LoggedOut" id="LoggedOut" value="true">
<input type="submit" class="submitLinkSmall" value="Log Out">
<!-- I've disguised the submit button as a link -->
</form> 
</div>
<h1 style="margin-left:370px; position:relative; top: 10px; font-size: 22px; color: #425A8D;">Intellectual Property Library</h1>
<HR align="left" size="2px" noshade color="#FF9900" style="margin-top: 22px; size: 2px; height: 0px; position: relative; right: 0px;">
<img src="/images/VerticalLine.bmp" style="position: absolute; top: 0px; left: 122px;">
<?php
// Don't even bother to connect to the DB, let alone query it, if the user is visiting via a guest pass (session variable set in iplibrary.php)
if (!isset($_SESSION['Guest']))
	{
	// Connect to mysql, using either the username and password that the user just typed into the above form, or using stored values for username and password that are stored in their respective session variables.
	$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
	or die('Could not connect: ' . mysql_error());
	mysql_select_db('paulme6_newresolution') or die('Could not select database');

	// Retrieve data from client DB (mediators_table) using session variable $_SESSION['SessID'] (which is set in iplibrary.php and in updateprofile.php, one of which pages will be visited by the user before user navigates to ipwirescrossed.php) in order to prepopulate the form fields.
	$query = "select Name, EntityName, PrincipalStreet, PrincipalAddressOther, City, State, Zip, ProfessionalEmail, UseProfessionalEmail, Email, Telephone from mediators_table where ID = ".$_SESSION['SessID'];
	$result = mysql_query($query) or die ('Failed to retrieve ID for username-password in ipemailsig.php: '.mysql_error());
	$line = mysql_fetch_array($result);
	}
?>

<h2 style="margin-left: 150px;">WIRES CROSSED&trade; E-ZINE</h2>
<div style="margin-left: 150px; margin-top: 0px; width: 800px; font-family: Arial, Helvetica, sans-serif; font-size: 10px; font-size: 12px;">
<p class="text" style="margin-right: 0px; margin-bottom: 30px; margin-left: 0px;">
<img HSPACE="0" vspace="0" style="position: absolute; top: 85px; left: 700px; float: right" src="/images/wires_crossed_logotype.jpg" alt="Wires Crossed Logotype">
A professional email marketing campaign will keep your name front and center for referrals from<br>past (and prospective) clients. To that end, your Launch Platform&trade; license includes rights to the design template and syndicated content of the Wires Crossed&trade; email newsletter (or e-zine). Begin by customizing Wires Crossed&trade; with your personal information below:</p>
<!-- Note that the only form validation I do below is the email address using emailCheck() (a function defined in emailaddresschecker.js). I conclude there's no rationale for JS or PHP validation. However, to avoid cross-site scripting, I use html_specialchars() in the form processor, ipwirescrossed_slave.php. -->
<form method="post" onSubmit="if (document.getElementById('email').value != '') return emailCheck(this.email.value,'noalert')" action="/scripts/ipwirescrossed_slave.php">
<table>
<tr>
<td valign="top" height="30"><label for="name" style="position: relative; top: 5px;">Name</label></td>
<td valign="top">
<div style="position: relative; top: 0px;"><input type="text" name="name" id="name" maxlength="50" style="width:170px" size="30" value="<?php echo $line['Name']; ?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; document.getElementById('entityname').focus();"><span class="tangsup" style="font-size: 10px;">&nbsp;<b>(required)</b></span>
</div>
</td>
</tr>
<tr>
<td valign="top" height="30"><label for="entityname" style="position: relative; top: 9px;">Entity name&nbsp;</label></td>
<td valign="top">
<div style="position: relative; top: 0px;"><input type="text" name="entityname" id="entityname" size="45" value="<?php echo $line['EntityName']; ?>" maxlength="50" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; document.getElementById('streetaddress').focus();">
<span class="tangsup" style="font-size: 10px;">&nbsp;<b>(optional)</b></span><span title="header=[Help: Entity name] body=[If you wish to use an entity name (e.g. &ldquo;Law and Mediation Office&nbsp;<br>of Jane Doe&rdquo;), include it here.] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span>
</div>
</td>
</tr>
<tr>
<td valign="top" height="40"><label for="streetaddress" style="position: relative; top: 9px;">Street address</label>&nbsp;</td>
<td valign="top">
<div style="position: relative; top: 0px;"><input type="text" name="streetaddress" id="streetaddress" maxlength="100" size="64" value="<?php echo $line['PrincipalStreet']; if ($line['PrincipalAddressOther'] != '' && !is_null($line['PrincipalAddressOther'])) echo ', '.$line['PrincipalAddressOther']; ?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; document.getElementById('city').focus();"><span class="tangsup" style="font-size: 10px;">&nbsp;<b>(required)</b></span><span title="header=[Help: Street Address] body=[This is the address of your principal (main) office location. Example: &ldquo;448 Lincoln Way&rdquo;. If your address includes a suite number (e.g. Suite 200), include it here.] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span>
</div>
</td>
</tr>
<tr>
<td valign="top" height="30"><label for="city" style="position: relative; top: 5px;">City</label></td>
<td valign="top">
<div style="position: relative; top: 0px;"><input type="text" name="city" id="city" maxlength="40" size="40" value="<?php echo $line['City']?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white';"><span class="tangsup" style="font-size: 10px;">&nbsp;<b>(required)</b></span>
</div>
</td>
</tr>
<tr>
<td valign="top" height="40"><label for="state" style="position: relative; top: 9px;">State</label></td>
<td valign="top">
<div style="position: relative; top: 0px;"><select name="state" id="state" size="1">
<?php
// Note: the same code gets used in admin1.php for generating a drop-down menu of states.
$statesArray = array(array('&lt;&nbsp; Select a State &nbsp;&gt;',null), array('Alabama','AL'),	array('Alaska','AK'), array('Arizona','AZ'), array('Arkansas','AR'),	array('California','CA'), array('Colorado','CO'), array('Connecticut','CT'), array('Delaware','DE'), array('District of Columbia (D.C.)','DC'), array('Florida','FL'), array('Georgia','GA'), array('Hawaii','HI'), array('Idaho','ID'), array('Illinois','IL'), array('Indiana','IN'), array('Iowa','IA'), array('Kansas','KS'), array('Kentucky','KY'), array('Louisiana','LA'), array('Maine','ME'), array('Maryland','MD'), array('Massachusetts','MA'), array('Michigan','MI'), array('Minnesota','MN'), array('Mississippi','MS'), array('Missouri','MO'), array('Montana','MT'), array('Nebraska','NE'), array('Nevada','NV'), array('New Hampshire','NH'), array('New Jersey','NJ'), array('New Mexico','NM'), array('New York','NY'), array('North Carolina','NC'), array('North Dakota','ND'), array('Ohio','OH'), array('Oklahoma','OK'), array('Oregon','OR'), array('Pennsylvania','PA'), array('Rhode Island','RI'), array('South Carolina','SC'), array('South Dakota','SD'), array('Tennessee','TN'), array('Texas','TX'), array('Utah','UT'), array('Vermont','VT'), array('Virginia','VA'), array('Washington','WA'), array('Washington, D.C.','DC'), array('West Virginia','WV'), array('Wisconsin','WI'), array('Wyoming','WY'));
for ($i=0; $i<53; $i++)
	{
	$optiontag = '<option value="'.$statesArray[$i][1].'" ';
	if ($line['State'] == $statesArray[$i][1]) $optiontag = $optiontag.' selected';
	$optiontag = $optiontag.'>'.$statesArray[$i][0]."</option>\n";
	echo $optiontag;
	}
?>
</select><span class="tangsup" style="font-size: 10px;">&nbsp;<b>(required)</b></span>
&nbsp;&nbsp;&nbsp;
<label for="zip" style="position: relative; top: 0px;">Zip</label>&nbsp;&nbsp;<input type="text" name="zip" id="zip" maxlength="10" size="10" value="<?php echo $line['Zip']?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white';  document.getElementById('email').focus();"><span class="tangsup" style="font-size: 10px;">&nbsp;<b>(required)</b></span><span title="header=[Help: Zip Code] body=[Enter either a five digit zip code (e.g. 94040) or a five-plus-four code separated by a hyphen (e.g. 9040-4321).] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span>
</div>
</td>
</tr>
<tr>
<td valign="top" height="60"><label for="Email" style="position: relative; top: 10px;">Email</label></td>
<td>
<input type="text" name="email" id="email" maxlength="50" size="45" value="<?php if ($line['UseProfessionalEmail'] != 1) echo $line['Email']?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; document.getElementById('tel1').focus();" <?php if ($line['UseProfessionalEmail'] == 1) echo ' disabled';?>><span class="tangsup" style="font-size: 10px;">&nbsp;<b>(required)</b></span><br>
<input type="checkbox" name="UseProfessionalEmail" id="UseProfessionalEmail" value="1" onclick="if (document.getElementById('UseProfessionalEmail').checked) { document.getElementById('email').disabled = true; document.getElementById('Email').value = ''; } else { document.getElementById('email').disabled = false; document.getElementById('email').focus(); };" <?php if ($line['ProfessionalEmail'] == null || $line['ProfessionalEmail'] == '') { echo ' disabled'; $_SESSION['ProfessionalEmail'] = ''; }  else if ($line['UseProfessionalEmail'] == 1) { echo ' checked'; $_SESSION['ProfessionalEmail'] = $line['ProfessionalEmail'];}; ?>>&nbsp;<label>Use my assigned professional email address instead:&nbsp;&nbsp;</label><span class="basictext"><?php if ($line['ProfessionalEmail'] == null || $line['ProfessionalEmail'] == '') echo '[available on request]'; else echo $line['ProfessionalEmail']; ?></span>
<span title="header=[Help: Professional Email Address] body=[Send an email to support@newresolutionmediation.com to request a <br>custom email account of type prefix@newresolutionmediation.com.<br>Let us know your preferred prefix (e.g. your first name or your initials). We&rsquo;ll set up the account on our server and notify you with instructions (usually in 1-2 business days) when your new address is ready for use.] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span>
</td>
</tr>
<tr>
<td height="45"><label for="tel1">Telephone</label></td>
<td>
<input type="text" name="tel1" id="tel1" maxlength="3" size="3" value="<?php echo substr($line['Telephone'],0,3);?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; document.getElementById('tel2').focus();">
&nbsp;&nbsp;&nbsp;
<input type="text" name="tel2" id="tel2" maxlength="3" size="3" value="<?php echo substr($line['Telephone'],4,3); ?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white';">&nbsp;&ndash;&nbsp;<input type="text" name="tel3" id="tel3" maxlength="4" size="4" value="<?php echo substr($line['Telephone'],8,4); ?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; document.getElementById('ext').focus();"><span class="tangsup" style="font-size: 10px;">&nbsp;<b>(required)</b></span>
<label for="ext">&nbsp;&nbsp;&nbsp;Ext.&nbsp;</label>
<input type="text" name="ext" id="ext" maxlength="5" size="3" value="<?php if (strlen($line['Telephone']) > 12) echo substr($line['Telephone'],19); ?>" onFocus="this.style.background='#FFFF99'">
</td>
</tr>
<tr>
<td colspan="2" align="center" height="50" valign="bottom">
<input type="submit" name="GenerateWiresCrossedHTML" value="Generate" class="buttonstyle">
</td>
</tr>
</table>
</form>
<br><br>
<div id="copyright">&copy; <?php echo date('Y'); ?> New Resolution LLC. All rights reserved.</div>
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
