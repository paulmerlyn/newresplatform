<?
/*
This php-based page shows prospective licensees a form by which they can submit their name (optional), phone number (optional), and email address (required) to be alerted to the future availability of any locale that may currently be taken (i.e. full or exclusive). The form in waitlist.php is processed by slave file waitlist_slave.php. Also note that I use a cron script to automatically send an email message to anyone who submitted his/her email address to the waitlist for a particular locale the very next day that that locale becomes available.
*/
session_start();

// Connect to mysql and select database.
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
	or die('Could not connect: ' . mysql_error());

mysql_select_db('paulme6_newresolution') or die('Could not select database');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Priority License Reservation</title>
<link href="salescss.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="javascript" src="/scripts/emailaddresschecker.js"></script>
<script type="text/javascript">
function checkEmailOnly()
{
// Validate WaitlisterEmail field. (There's no need to validate the WaitlisterName or WaitlisterTelephone fields)
var emailValue = document.getElementById("waitlistemail").value;
var emailLength = emailValue.length;
if (emailLength > 60 || !(emailCheck(emailValue,'noalert'))) // emailCheck() is function in emailaddresscheker.js. This field is reqd i.e. blank causes a rejection as invalid.
	{
	return false;
	}
else
	{
	return true;
	}
} 
</script>
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
<?php
// If the $_SESSION['OKtoClose'] session variable got set to 'true' by waitlist_slave.php, then the waitlister's data has now been safely inserted into waitlist_table by waitlist_slave.php and we can therefore simply close this waitlist.php window. We must also, in that case, reset $_SESSION['OKtoClose'] to false so that the user can subsequently put him/herself on the waitlist for another locale.
if ($_SESSION['OKtoClose'] == 'true') 
	{
	$_SESSION['OKtoClose'] = 'false';
	echo '<script type="text/javascript">window.close();</script>';
	}
?>
<div class='gloss' style='margin-left: 20px; margin-top: 20px;'>If your locale was marked &ldquo;[Full]&rdquo; on the previous page, it&rsquo;s <br>
  already taken. Select the locale below and register your <br>
  interest. You&rsquo;ll be the first to know when it becomes available:

    <form name='WaitlistForm' id='WaitlistForm' action='/scripts/waitlist_slave.php' method='post'>
<table style='margin-left: 20px; margin-top: 20px; width: 260px; cellspacing: 0px; cellpadding: 6px;'>

<tr height='30'>
<td colspan='2'>
<select name='WaitlistState' id='WaitlistState' size='1' style='font-size: 14px; font-weight:normal; font-family:Geneva,Arial,Helvetica,sans-serif; width: 280px; color: #425A8D; background-color: #fafafa; border: 1px solid #425A8D;' onChange='this.form.submit();'>
<?php
// Note: this code for generating a drop-down menu of states was first written for updateprofile.php.
$statesArray = array(array('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;&nbsp;Select Your State&nbsp;&gt;',null), array('Alabama','AL'),	array('Alaska','AK'), array('Arizona','AZ'), array('Arkansas','AR'),	array('California','CA'), array('Colorado','CO'), array('Connecticut','CT'), array('Delaware','DE'), array('District of Columbia (D.C.)','DC'), array('Florida','FL'), array('Georgia','GA'), array('Hawaii','HI'), array('Idaho','ID'), array('Illinois','IL'), array('Indiana','IN'), array('Iowa','IA'), array('Kansas','KS'), array('Kentucky','KY'), array('Louisiana','LA'), array('Maine','ME'), array('Maryland','MD'), array('Massachusetts','MA'), array('Michigan','MI'), array('Minnesota','MN'), array('Mississippi','MS'), array('Missouri','MO'), array('Montana','MT'), array('Nebraska','NE'), array('Nevada','NV'), array('New Hampshire','NH'), array('New Jersey','NJ'), array('New Mexico','NM'), array('New York','NY'), array('North Carolina','NC'), array('North Dakota','ND'), array('Ohio','OH'), array('Oklahoma','OK'), array('Oregon','OR'), array('Pennsylvania','PA'), array('Rhode Island','RI'), array('South Carolina','SC'), array('South Dakota','SD'), array('Tennessee','TN'), array('Texas','TX'), array('Utah','UT'), array('Vermont','VT'), array('Virginia','VA'), array('Washington','WA'), array('Washington, D.C.','DC'), array('West Virginia','WV'), array('Wisconsin','WI'), array('Wyoming','WY'));
for ($i=0; $i<53; $i++)
	{
	$optiontag = "<option value='".$statesArray[$i][1]."'";
	if ($statesArray[$i][1] == $_SESSION['WaitlistStateSelected']) $optiontag .= ' selected'; // Preset the drop-down menu to the value of the selected state.
	$optiontag = $optiontag.'>'.$statesArray[$i][0].'</option>';
	echo $optiontag;
	}
?>
</select>
</td>
</tr>

<tr height='30'>
<td colspan='2'>
<select name='WaitlistLocale' id='WaitlistLocale' size='1' style="font-size: 14px; font-weight: normal; font-family: Geneva, Arial, Helvetica, sans-serif; width: 280px; <?php if ($_SESSION['WaitlistStateSelected'] != null && $_SESSION['WaitlistStateSelected'] != '') echo 'color: #425A8D; background-color: #fafafa; border: 1px solid #425A8D;'; else echo 'color: #CCCCCC; '; ?>" onChange="this.form.submit();" <?php if ($_SESSION['WaitlistStateSelected'] == null || $_SESSION['WaitlistStateSelected'] == '') echo 'disabled'; ?>>
<option value=null selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;&nbsp;Select Your Locale&nbsp;&gt;</option>
<?php
// If $_SESSION['WaitlistStateSelected'] is neither null nor '' (i.e. if the State drop-down menu isn't still in its neutral position), formulate query to obtain the locales that belong to the selected state of interest. We'll place these locales in an array, which we'll use to build a drop-down menu by which the user can select a particular locale.
if ($_SESSION['WaitlistStateSelected'] != null && $_SESSION['WaitlistStateSelected'] != '')
	{
	$query = "SELECT Locale, LocaleShort from locales_table WHERE LocaleStates LIKE '%".$_SESSION['WaitlistStateSelected']."%'";
	$result = mysql_query($query) or die('Query (select all Locale values from table inside Trio Solutions Glossary) failed: ' . mysql_error());
	if (!$result) {	echo 'No $result was achievable.'; };

	// Place the result of the query in an array $line via the mysql_fetch_assoc command. The associative values $line['Locale'], $line['LocaleShort'], etc. on moving down through the lines of this array will hold the Locale, LocaleShort, etc. values. Then push each of these value into array $localesArray[] and $localesShortArray respectively, where the contents can then be sorted alphabetically before use in the option tag of the locales drop-down menu.
	$localesArray = array(); // Declare and initialize array
	$localesShortArray = array(); // Declare and initialize array
	while ($line = mysql_fetch_assoc($result))
		{
		array_push($localesArray, $line['Locale']);
		array_push($localesShortArray, $line['LocaleShort']);
		};
	sort($localesArray);
	sort($localesShortArray);
	$arrayLength = count($localesArray);
	for ($i=0; $i<$arrayLength; $i++)
		{
		$optiontag = '<option value="'.$localesArray[$i].'"';
		if (html_entity_decode($localesArray[$i], ENT_QUOTES, 'UTF-8') == $_SESSION['WaitlistLocaleSelected']) $optiontag .= ' selected'; // Preset the drop-down menu to the value of the selected locale. Note use of html_entity_decode to handle use of special characters such as the ntilde in 'Canon City_CO' and the single quote in Coeur d'Alene_ID. See PHP manual http://us.php.net/manual/en/function.html-entity-decode.php, including Matt Robinson's contributed note for why I use ENT_QUOTES and UTF-8 parameters.
		$optiontag = $optiontag.'>'.$localesShortArray[$i].'</option>';
		/* Note: since the locales drop-down list inside the Trio Solutions Glossary on sales.php page (unlike the locales drop-down elsewhere on the page proper) doesn't ever need to disable "Full" option elements, I don't have to worry about the hack that addresses the fact that IE 6 and 7 don't implement the disabled attribute of the option element. So I don't need the http://apptaro.seesaa.net/article/21140090.html workaround!  */
		echo $optiontag;
		}
	}
// Closing connection
mysql_close($db);
?>
</select>
</td>
</tr>

<tr height='30'>
<td width='100' valign="bottom">
<label for='waitlistname'>Name</label>&nbsp;&nbsp;<span style='font-size: 9px'>(optional)</span>
</td>
<td width='150' valign="bottom">&nbsp;&nbsp;<input type='text' name='waitlistname' id='waitlistname' style='width:160px; color: #425A8D; border: 1px solid #425A8D;' size='20' maxlength='30'></td>
</tr>

<tr height='30'>
<td width='100' valign="bottom">
<label for='waitlistphone'>Telephone</label>&nbsp;&nbsp;<span style='font-size: 9px'>(optional)</span>
</td>
<td width='160' valign="bottom">&nbsp;&nbsp;<input type='text' name='waitlistphone' id='waitlistphone' style='width:160px; color: #425A8D; border: 1px solid #425A8D;' size='20' maxlength='30'></td>
</tr>

<tr height='30'>
<td valign="middle">
<span style="position: relative; top: <?php if ($_SESSION['MsgEmail'] == null) echo '8px;'; else echo '-12px;'; ?>"><label for='waitlistemail'>Email</label>&nbsp;&nbsp;<span style='font-size: 9px'>(required)</span></span></td>
<td valign="bottom">&nbsp;
<input type='text' name='waitlistemail' id='waitlistemail' style='width:160px; color: #425A8D; border: 1px solid #425A8D;' size='20' maxlength='40' onBlur="checkEmailOnly();">
<?php if ($_SESSION['MsgEmail'] != null) { echo $_SESSION['MsgEmail']; $_SESSION['MsgEmail']=null; } ?>
</td>
</tr>

<!-- Excellent documentation on Captcha at: http://www.white-hat-web-design.co.uk/articles/php-captcha.php -->
<tr height="60">
<td colspan="2" align="center">
<span style="position: relative; left: 30px;"><img src="/scripts/captcha/CaptchaSecurityImages.php?width=100&height=40&characters=5" /></span>
</td>
</tr>

<tr>
<td colspan="2" align="center" height="40px" valign="middle">
<span style="position: relative; left: 28px;"><label>Security Code:</label><br>
<input id="security_code" name="security_code" type="text" size="5" /></span>
</td></tr>

<tr height="60">
<td colspan="2">
<input type="submit" name="NotifyMe" id="NotifyMe" style="margin-left: 130px;" value="Notify Me" onClick="return checkEmailOnly();" <?php if ($_SESSION['WaitlistLocaleSelected'] != null && $_SESSION['WaitlistLocaleSelected'] != '') { echo 'class="buttonstyle"'; } else { echo 'class=""'; }; ?>>
</td>
</tr>

</table>
</form>
</div>

<!-- This JS disables the 'NotifyMe' submit button when the Locale drop-down menu is at its neutral position. I've placed it here at the end of the file rather than within an onchange event of the Locale drop-down menu because putting it in an onchange event causes an error in IE (it works fine either way in Firefox). -->
<script type="text/javascript">if (document.getElementById('WaitlistLocale').selectedIndex == 0) { document.getElementById('NotifyMe').removeAttribute("class"); document.getElementById('NotifyMe').disabled = true; } else { document.getElementById('NotifyMe').disabled = false;document.getElementById('NotifyMe').classname = 'buttonstyle'; };</script>
 
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
