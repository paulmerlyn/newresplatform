<?php
/*
admin3.php performs two locale-related management tasks: (i) It allows an administrator (user) to add a new locale to the locales_table, and (ii) it allows an administrator to retrieve from waitlist_table the names, phone numbers, and email addresses of all waitlisters of interest (e.g. waitlisters in the state of Pennyslvania whose locale of interest is due to expire (if not renewed by their current licensees) within a specified time period. The processing of this script is performed by admin4.php.
*/
// Start a session
session_start();
$_SESSION['AntiReloadFlag'] = 'false'; // Used to prevent a user-forced reload (i.e. user hits F5 key) of admin4.php, which would resubmit the form data each time if this anti-reload device were not employed.

// Connect to database
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
or die('Could not connect: ' . mysql_error());
mysql_select_db('paulme6_newresolution') or die('Could not select database: ' . mysql_error());
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>New Resolution Mediation Launch Platform&trade; Administrator</title>
<link href="/nrcss.css" rel="stylesheet" type="text/css">
<script>
function FocusFirst()
{
	if (document.forms.length > 0 && document.forms[0].elements.length > 0)
		document.forms[0].elements[0].focus();
}

/* Begin JS form validation functions. (Note to self: I did PHP validation that user selected a TermNumber and TermUnit from the drop-down menus, and I've decided not to bother with a Javascript version of that type of validation.) */
function checkLocaleShortOnly()
{
// Validate LocaleShort field.
var localeShortValue = document.getElementById("LocaleShort").value;
var illegalCharSet = /[0-9,~!@#\$%\^&\*\(\)_\+`=\|\\:";<>\?]+/; // Exclude everything except letters, hyphen, apostrophe, and space character.
var reqdCharSet = /[a-zA-Z]{2}/;  // At least two letters.
if (illegalCharSet.test(localeShortValue)  || !reqdCharSet.test(localeShortValue))
	{
	document.getElementById("LocaleShortError").style.display = "inline";
	return false;
	} 
else
	{
	return true;
	}
}

function checkLocaleStatesOnly()
{
// Validate that a LocaleStates value was selected.
var localeStatesValue = document.getElementById("LocaleStates").value;
if (localeStatesValue == 'null' || localeStatesValue == '')
	{
	document.getElementById("LocaleStatesError").style.display = "inline";
	return false;
	} 
else
	{
	return true;
	}
}

function checkPopulationOnly()
{
// Validate Population input field.
var populationValue = document.getElementById("Population").value;
var illegalCharSet = /[^0-9]+/; // Reject everything that contains one or more characters that is not a digit.
var reqdCharSet = /[0-9]{3}/;  // At least three digits.
if (illegalCharSet.test(populationValue)  || !reqdCharSet.test(populationValue))
	{
	document.getElementById("PopulationError").style.display = "inline";
	return false;
	} 
else
	{
	return true;
	}
}

function checkMaxLicensesOnly()
{
// Validate MaxLicenses input field.
var maxLicensesValue = document.getElementById("MaxLicenses").value;
var illegalCharSet = /[^0-9]+/; // Reject everything that contains one or more characters that is not a digit.
var reqdCharSet = /[0-9]+/;  // At least one numeric.
if (illegalCharSet.test(maxLicensesValue)  || !reqdCharSet.test(maxLicensesValue))
	{
	document.getElementById("MaxLicensesError").style.display = "inline";
	return false;
	} 
else
	{
	return true;
	}
}

function checkForm()
{
hideAllErrors();
if (checkLocaleShortOnly() && checkLocaleStatesOnly() && checkPopulationOnly() && checkMaxLicenses()) // Check validity of user inputs.
	{
	return true; // All elements passed their validity checks, so return a true.
	}
else
	{
	return false; // At least one of the elements (i.e. LocaleShort, LocaleStates, Population, or MaxLicenses) failed its individual check function, so return a false;
	};
} // End of checkForm()

/* This function hideAllErrors() is called by checkForm() and by onblur event within non-personal form fields. */
function hideAllErrors()
{
document.getElementById("LocaleShortError").style.display = "none";
document.getElementById("LocaleStatesError").style.display = "none";
document.getElementById("PopulationError").style.display = "none";
document.getElementById("MaxLicensesError").style.display = "none";
return true;
}

</script>
</head>

<body>
<div style="margin: 10px auto; padding: 0px; text-align: center;">
<form method="post" action="unwind.php" style="display: inline;">
<input type="submit" name="Logout" class="submitLinkSmall" value="Log Out">
</form>
</div>

<h1 style="text-align: center; font-size: 22px; padding: 0px; color: #425A8D;">New Resolution Mediation Launch Platform&trade; Administrator</h1>
<?php
require('../ssi/adminmenu.php'); // Include the navigation menu.

// Create short variable names
$Authentication = $_POST['Authentication'];
$Username = $_POST['Username'];
$Password = $_POST['Password'];

if  (empty($Authentication) && $_SESSION['Authenticated'] != 'true')
	{
	// Visitor needs to authenticate
	?>
	<div style="position: absolute; left: 240px; top: 100px; width: 250px; padding: 15px; border: 2px solid #444444; border-color: #425A8D;">
	<h3>Please authenticate yourself:</h3>
	<br>
	<script type="text/javascript">window.onload = FocusFirst;</script>
	<form method="post" action="/scripts/admin3.php">
	<table border="0" width="280">
	<tr>
	<td align="center"><input type="password" name="Authentication" maxlength="40" size="20"></td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
	<td align="center"><input type="submit" class="buttonstyle" value="Authenticate"></td>
	</tr>
	</table>
	</form>
	</div>
	<?php
	exit;
	}
else
	{
	// See if the password entered by the user in the POST array is correct. (Note that PHP comparisons are case-sensitive [unlike MySQL query matches] and sha1 returns a lower-case result.) If it is correct or if the $_SESSION['Authenticated'] session variable was set for a previously established authentication, proceed to show either the client vs demo selection form or proceed straight to the main screen.
	if ((sha1($Authentication) == 'dc6a59aab127063fd353585bf716c7f7c34d2aa0') || $_SESSION['Authenticated'] == 'true')
		{
		$_SESSION['Authenticated'] = 'true';
	?>
		<div style="margin: 20px auto; width: 530px; padding: 15px; border: 2px solid #444444; border-color: #425A8D;">
		<h1 style="margin-left: 0px;">Create a new locale:</h1>
		<br>
			
		<form method="post" name="AddLocale" action="/scripts/admin4.php">
			
		<table width="530">
		<tr>
		<td valign="top"><label>New Locale</label></td>
		<td>
		<input type="text" name="LocaleShort" id="LocaleShort" maxlength="40" size="30" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkLocaleShortOnly();">
		<div class="greytextsmall">Example: Los Angeles-Long Beach-Glendale</div>
		<div class="error" id="LocaleShortError">Enter alphabetic characters. Use only letters, apostrophes ('), and hyphens (-).<br></div>
		<?php if ($_SESSION['MsgLocaleShort'] != null) { echo $_SESSION['MsgLocaleShort']; $_SESSION['MsgLocaleShort']=null; } ?>
		</td>
		</tr>
		<tr>
		<td width="120" valign="top"><label>Associated State(s)</label></td>
		<td>
		<select name="LocaleStates[]" id="LocaleStates" MULTIPLE size="12" style="font-size:12px; font-weight:normal; font-family:Arial,Helvetica,sans-serif;" onBlur="hideAllErrors(); return checkLocaleStatesOnly();"> <!-- Note the need for square brackets [] in the name b/c $LocaleStates is to be an array. -->
		<?php
		// Note: this code for generating a drop-down menu of states was first written for updateprofile.php.
		$statesArray = array(array('Alabama','AL'),	array('Alaska','AK'), array('Arizona','AZ'), array('Arkansas','AR'),	array('California','CA'), array('Colorado','CO'), array('Connecticut','CT'), array('Delaware','DE'), array('District of Columbia (D.C.)','DC'), array('Florida','FL'), array('Georgia','GA'), array('Hawaii','HI'), array('Idaho','ID'), array('Illinois','IL'), array('Indiana','IN'), array('Iowa','IA'), array('Kansas','KS'), array('Kentucky','KY'), array('Louisiana','LA'), array('Maine','ME'), array('Maryland','MD'), array('Massachusetts','MA'), array('Michigan','MI'), array('Minnesota','MN'), array('Mississippi','MS'), array('Missouri','MO'), array('Montana','MT'), array('Nebraska','NE'), array('Nevada','NV'), array('New Hampshire','NH'), array('New Jersey','NJ'), array('New Mexico','NM'), array('New York','NY'), array('North Carolina','NC'), array('North Dakota','ND'), array('Ohio','OH'), array('Oklahoma','OK'), array('Oregon','OR'), array('Pennsylvania','PA'), array('Rhode Island','RI'), array('South Carolina','SC'), array('South Dakota','SD'), array('Tennessee','TN'), array('Texas','TX'), array('Utah','UT'), array('Vermont','VT'), array('Virginia','VA'), array('Washington','WA'), array('Washington, D.C.','DC'), array('West Virginia','WV'), array('Wisconsin','WI'), array('Wyoming','WY'), array('United States (Generic)','US'));
		for ($i=0; $i<53; $i++)
		{
			$optiontag = '<option value="'.$statesArray[$i][1].'">'.$statesArray[$i][0]."</option>\n";
			echo $optiontag;
		}
		?>
		</select>
		<div class="greytextsmall">Hold down &lsquo;Ctrl&rsquo; [Windows] or &lsquo;Command&rsquo; (Mac) keys to select multiple states.</div>
		<div class="error" id="LocaleStatesError">Please select one or more states that associate with your locale.<br></div>
		<?php if ($_SESSION['MsgLocaleStates'] != null) { echo $_SESSION['MsgLocaleStates']; $_SESSION['MsgLocaleStates']=null; } ?>
		</td>
		</tr>
		<tr>
		<td><label>Population</label></td>
		<td><input type="text" name="Population" id="Population" maxlength="10" size="10" onFocus="this.style.background='#FFFF99'" onBlur="this.value = this.value.replace(/,/g, ''); if (this.value != '') { if (!document.getElementById('OverrideMaxLic').checked) { var cap = Math.floor(parseInt(this.value)/1250000); if (cap > 5) { cap = 5; } else if (cap < 1) { cap = 1; }; document.getElementById('MaxLicenses').value = cap; document.getElementById('MaxLicensesHidden').value = cap; } else { document.getElementById('MaxLicensesHidden').value = document.getElementById('MaxLicenses').value; }; }; this.style.background='white'; hideAllErrors(); return checkPopulationOnly();"><div class="greytextsmall">Use numbers [0-9] only. (You may also include commas [e.g. 3,040,278] if you wish.)</div><div class="error" id="PopulationError">Enter a number here. Use only digits [0-9].<br></div>
<?php if ($_SESSION['MsgPopulation'] != null) { echo $_SESSION['MsgPopulation']; $_SESSION['MsgPopulation']=null; } ?></td> <!-- First strip commas from the population string. Then calculate MaxLicenses using formula of one license per 1.25 million population with a maximum of 5 licenses and a minimum of 1 license. -->
		<!-- I need to pass the value of MaxLicenses via the hidden form field 'MaxLicensesHidden' rather than directly through the MaxLicenses field b/c the MaxLicenses form element is sometimes disabled, and when a form element is disabled the browser (e.g. Firefox) doesn't post its value into the POST array. -->
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
		<td><label>Maximum Licenses</label></td>
		<td><input type="text" name="MaxLicenses" id="MaxLicenses" maxlength="8" size="8" onFocus="this.style.background='#FFFF99'" onBlur="document.getElementById('MaxLicensesHidden').value = this.value; this.style.background='white'; if (document.getElementById('OverrideMaxLic').checked) { hideAllErrors(); return checkMaxLicensesOnly(); };" disabled>&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="OverrideMaxLic" id="OverrideMaxLic" value="yes" onClick="if (this.checked) { document.getElementById('MaxLicenses').disabled = false; document.getElementById('MaxLicenses').focus(); document.getElementById('MaxLicensesHidden').value = document.getElementById('MaxLicenses').value; } else { var cap = Math.floor(parseInt(document.getElementById('Population').value)/1250000); if (cap > 5) { cap = 5; } else if (cap < 1) { cap = 1; }; document.getElementById('MaxLicenses').value = cap; document.getElementById('MaxLicensesHidden').value = cap; document.getElementById('MaxLicenses').disabled = true; };">&nbsp;<label class="small">[Check box to override default maximum  for this locale]</label>
			<input type="hidden" name="MaxLicensesHidden" id="MaxLicensesHidden"/>
		<div class="error" id="MaxLicensesError"><br>Enter the maximum number of licenses here. (Must be greater than zero.)<br></div>
		<?php if ($_SESSION['MsgMaxLicenses'] != null) { echo $_SESSION['MsgMaxLicenses']; $_SESSION['MsgMaxLicenses']=null; } ?>
		</td>
		</tr>
		<tr>
		<td colspan="2" align="center"><br><input type="submit" name="AddNewLocale" class="buttonstyle" value="Add New Locale" onClick="return checkForm();"></td>
		</tr>
		</table>
		</form>
		</div>

		<div style="margin: 20px auto; width: 530px; padding: 15px 15px 10px 15px; border: 2px solid #444444; border-color: #425A8D; display: block;">
		<a name="waitlistretrieval"><h1 style="margin-left: 0px;">Retrieve wait-listed mediators from database:</h1></a>
		<br>
		<form name="WaitlistForm" method="post" action="/scripts/admin4.php">
		<table width="530">
		<tr height="30px" valign="middle">
		<td valign="middle"><label>State(s) of Interest</label></td>
		<td>
		<select name='WaitlistState' id='WaitlistState' size='1' style='font-size: 14px; font-weight:normal; font-family:Geneva,Arial,Helvetica,sans-serif; width: 280px; color: #425A8D; background-color: #fafafa; border: 1px solid #425A8D;' onChange='this.form.submit();'>
		<?php
		// Note: this code for generating a drop-down menu of states was first written for updateprofile.php.
		$statesArray = array(array('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;&nbsp;All States&nbsp;&gt;','allstates'), array('Alabama','AL'),	array('Alaska','AK'), array('Arizona','AZ'), array('Arkansas','AR'),	array('California','CA'), array('Colorado','CO'), array('Connecticut','CT'), array('Delaware','DE'), array('District of Columbia (D.C.)','DC'), array('Florida','FL'), array('Georgia','GA'), array('Hawaii','HI'), array('Idaho','ID'), array('Illinois','IL'), array('Indiana','IN'), array('Iowa','IA'), array('Kansas','KS'), array('Kentucky','KY'), array('Louisiana','LA'), array('Maine','ME'), array('Maryland','MD'), array('Massachusetts','MA'), array('Michigan','MI'), array('Minnesota','MN'), array('Mississippi','MS'), array('Missouri','MO'), array('Montana','MT'), array('Nebraska','NE'), array('Nevada','NV'), array('New Hampshire','NH'), array('New Jersey','NJ'), array('New Mexico','NM'), array('New York','NY'), array('North Carolina','NC'), array('North Dakota','ND'), array('Ohio','OH'), array('Oklahoma','OK'), array('Oregon','OR'), array('Pennsylvania','PA'), array('Rhode Island','RI'), array('South Carolina','SC'), array('South Dakota','SD'), array('Tennessee','TN'), array('Texas','TX'), array('Utah','UT'), array('Vermont','VT'), array('Virginia','VA'), array('Washington','WA'), array('Washington, D.C.','DC'), array('West Virginia','WV'), array('Wisconsin','WI'), array('Wyoming','WY'));
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
		<tr height="30px" valign="middle">
		<td valign="middle"><label>Locale(s) of Interest</label></td>
		<td>
		<select name='WaitlistLocale' id='WaitlistLocale' size='1' style="font-size: 14px; font-weight: normal; font-family: Geneva, Arial, Helvetica, sans-serif; width: 280px; <?php if ($_SESSION['WaitlistStateSelected'] != null && $_SESSION['WaitlistStateSelected'] != '') echo 'color: #425A8D; background-color: #fafafa; border: 1px solid #425A8D;'; else echo 'color: #CCCCCC; '; ?>" <?php if ($_SESSION['WaitlistStateSelected'] == null || $_SESSION['WaitlistStateSelected'] == '') echo 'disabled'; ?>>
		<option value='alllocales' selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;&nbsp;All Locales&nbsp;&gt;</option>
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
				if ($localesArray[$i] == $_SESSION['WaitlistLocaleSelected']) $optiontag .= ' selected'; // Preset the drop-down menu to the value of the selected locale.
				$optiontag = $optiontag.'>'.$localesShortArray[$i].'</option>';
				/* Note: since the locales drop-down list inside the Trio Solutions Glossary on sales.php page (unlike the locales drop-down elsewhere on the page proper) doesn't ever need to disable "Full" option elements, I don't have to worry about the hack that addresses the fact that IE 6 and 7 don't implement the disabled attribute of the option element. So I don't need the http://apptaro.seesaa.net/article/21140090.html workaround!  */
				echo $optiontag;
				}
			}
		?>
		</select>
		</td>
		</tr>
		<tr height="30px" valign="middle">
		<td valign="middle"><label>With Telephone Numbers</label></td>
		<td>
		<input type="checkbox" name="WithPhoneNos" value="1" <?php if ($_SESSION['WithPhoneNos'] == 1) { unset($_SESSION['WithPhoneNos']); echo 'checked'; }; ?>><label class="small">&nbsp;&nbsp;[Check box to include only mediators who provided a phone number]</label>
		</td>
		</tr>
		<tr height="30px" valign="middle">
		<td valign="middle"><label>With Names</label></td>
		<td>
		<input type="checkbox" name="WithNames" value="1" <?php if ($_SESSION['WithNames'] == 1) { unset($_SESSION['WithNames']); echo 'checked'; }; ?>><label class="small">&nbsp;&nbsp;[Check box to include only mediators who provided a name]</label>
		</td>
		</tr>
		<tr height="30px" valign="middle">
		<td valign="middle"><label>License Expiration</label></td>
		<td>
		<select name="LicExpPast" id="LicExpPast" size="1" style="font-size: 14px; font-weight:normal; font-family:Geneva,Arial,Helvetica,sans-serif;  <?php if (isset($_SESSION['LicExpFuture']) && $_SESSION['LicExpFuture'] != 'anytime') echo 'color: #CCCCCC;'; else echo 'color: #425A8D;' ?> background-color: #fafafa; border: 1px solid #425A8D;" onChange="if (this.selectedIndex != 0) { document.getElementById('LicExpFuture').style.color = '#CCCCCC'; document.WaitlistForm.LicExpFuture.disabled = true; } else { document.WaitlistForm.LicExpFuture.disabled = false; document.getElementById('LicExpFuture').style.color = '#425A8D'; };" <?php if (isset($_SESSION['LicExpFuture']) && $_SESSION['LicExpFuture'] != 'anytime') echo 'disabled'; ?>>
		<option value="anytime" selected>Expired: Anytime</option>
		<option value="1_DAY" <?php if ($_SESSION['LicExpPast'] == '1_DAY') { echo 'selected'; }; ?>>In Past 24 Hours</option>
		<option value="1_WEEK" <?php if ($_SESSION['LicExpPast'] == '1_WEEK') { echo 'selected'; }; ?>>In Past Week</option>
		<option value="1_MONTH" <?php if ($_SESSION['LicExpPast'] == '1_MONTH') { echo 'selected'; }; ?>>In Past Month</option>
		<option value="3_MONTH" <?php if ($_SESSION['LicExpPast'] == '3_MONTH') { echo 'selected'; }; ?>>In Past 3 Months</option>
		<option value="6_MONTH" <?php if ($_SESSION['LicExpPast'] == '6_MONTH') { echo 'selected'; }; ?>>In Past 6 Months</option>
		<option value="1_YEAR" <?php if ($_SESSION['LicExpPast'] == '1_YEAR') { echo 'selected'; }; ?>>In Past 12 Months</option>
		<option value="3_YEAR" <?php if ($_SESSION['LicExpPast'] == '3_YEAR') { echo 'selected'; }; ?>>In Past 3 Years</option>
		</select>
		&nbsp;&nbsp;&nbsp;
		<select name="LicExpFuture" id="LicExpFuture" size="1" style="font-size: 14px; font-weight:normal; font-family:Geneva,Arial,Helvetica,sans-serif; <?php if (isset($_SESSION['LicExpPast']) && $_SESSION['LicExpPast'] != 'anytime') echo 'color: #CCCCCC;'; else echo 'color: #425A8D;' ?> background-color: #fafafa; border: 1px solid #425A8D; " onChange="if (this.selectedIndex != 0) { document.getElementById('LicExpPast').style.color = '#CCCCCC'; document.WaitlistForm.LicExpPast.disabled = true; } else { document.WaitlistForm.LicExpPast.disabled = false; document.getElementById('LicExpPast').style.color = '#425A8D'; };" <?php if (isset($_SESSION['LicExpPast']) && $_SESSION['LicExpPast'] != 'anytime') echo 'disabled'; ?>>
		<option value="anytime" selected>Will Expire: Anytime</option>
		<option value="1_DAY" <?php if ($_SESSION['LicExpFuture'] == '1_DAY') { echo 'selected'; }; ?>>In Next 24 Hours</option>
		<option value="1_WEEK" <?php if ($_SESSION['LicExpFuture'] == '1_WEEK') { echo 'selected'; }; ?>>In Next Week</option>
		<option value="1_MONTH" <?php if ($_SESSION['LicExpFuture'] == '1_MONTH') { echo 'selected'; }; ?>>In Next Month</option>
		<option value="3_MONTH" <?php if ($_SESSION['LicExpFuture'] == '3_MONTH') { echo 'selected'; }; ?>>In Next 3 Months</option>
		<option value="6_MONTH" <?php if ($_SESSION['LicExpFuture'] == '6_MONTH') { echo 'selected'; }; ?>>In Next 6 Months</option>
		<option value="1_YEAR" <?php if ($_SESSION['LicExpFuture'] == '1_YEAR') { echo 'selected'; }; ?>>In Next 12 Months</option>
		<option value="3_YEAR" <?php if ($_SESSION['LicExpFuture'] == '3_YEAR') { echo 'selected'; }; ?>>In Next 3 Years</option>
		</select>
		</td>
		</tr>
		<tr>
		<td colspan="2" align="center"><br><input type="submit" name="RetrieveWaitlisters" class="buttonstyle" value="Retrieve Wait-listers"></td>
		</tr>
		</table>
		</form>
		
		</div>
		
		<?php
		/* Process the waitlister-retrieval query (formulated in admin4.php) and display details of all retrieved waitlisters who passed the user's selection criteria. */
		if (isset($_SESSION['RetrieveWaitlistersReturn']))
			{
			unset($_SESSION['RetrieveWaitlistersReturn']);
			$result = mysql_query($_SESSION['WaitlisterRetrievalQuery']) or die('Your attempt to select from the waitlist_table and mediators_table has failed. Here is the query: '.$query.mysql_error());
		?>
			<form method="post" action="admin4.php">
			<div style="position: absolute; left: 125px; top: 929px; padding: 10px; border: 2px solid #444444; border-color: #425A8D;">
			<h1 style="margin-left: 0px;">Wait-listed mediators in the database:</h1><a id="waitlisterstable" name="waitlisterstable">&nbsp;</a>

		<?php
			if (mysql_num_rows($result) < 1) 
				{
				echo '<div class="basictext" style="width: 540px; margin-bottom: 20px; color: red">No wait-listers match your search criteria.</div>';
				}
		else
				{
		?>
				<table width="540" cellspacing="0" cellpadding="6" border="0" style="font-size: 10px; font-family: Arial, Helvetica, sans-serif; padding: 0px;">
				<thead>
				<tr>
				<th align="left">&nbsp;<img src="/images/DeleteIcon.jpg" height="12" width="12" alt="delete icon"></th>
				<th align="left">Locale</th>
				<th align="left">License Status</th>
				<th align="left">Name</th>
				<th align="left">Telephone</th>
				<th align="left">Email</th>
				</tr>
				</thead>
				<tbody>
				<?php
				// Use the result of the query formulated in admin4.php to fill out the body of the waitlisters table row by row.
				while ($row = mysql_fetch_assoc($result))
					{
					// In order to determine License Status (i.e. the latest date on which a license for the locale of interest expired [in the past] or the earliest date on which a license for the locale of interest is due to expire [in the future]), we need another DB query. Note that the query doesn't gather an ExpirationDate for a mediator whose license is frozen with a PrevRslWhlFrzn = 0 (i.e. available for resale while frozen).
					$query = "SELECT ExpirationDate FROM mediators_table WHERE Locale = '".$row['Locale']."' AND (AdminFreeze = 0 || (AdminFreeze = 1 && PrevRslWhlFrzn = 1))";
					$resultExtra1 = mysql_query($query) or die('Your attempt to select ExpirationDate from the mediators_table has failed. Here is the query: '.$query.mysql_error()); // I must use a different name (i.e. $resultExtra1) for this result set in the inner-loop to avoid confusion with $result in the outer-loop.
					$expdates = array();
					while ($line = mysql_fetch_assoc($resultExtra1)) // If there are, say, three mediators who share Locale = 'Los Angeles_CA' for a waitlister's particular locale of interest = 'Los Angeles_CA', then $line['ExpirationDate'] will take one of the three expiration dates as we progress through the while loop three times.
						{
						array_push($expdates, $line['ExpirationDate']);
						}
					sort($expdates); // Sort the expiration dates array from earliest to latest date (dates are in format 2009-10-21 so they are easily sortable numerically).
					$earliestdate = $expdates[0]; // The first element in the sorted array is the earliest date.
					$latestdate = $expdates[count($expdates)-1]; // The last element in the sorted array is the latest date.
					echo '<tr>';
					echo '<td><input type="checkbox" name="DeleteCheckbox[]" value="'.$row['WaitlisterEmail'].'" onclick="if (this.checked) { document.getElementById(\'DeleteWaitlisters\').disabled = false; document.getElementById(\'DeleteWaitlisters\').className = \'buttonstyle\'; };"></td>';
					echo '<td>'.$row['Locale'].'</td>';
					// Figure out the license status. First query locales_table to see if the locale of interest is still unavailable.
					$query = "SELECT Exclusive, Full FROM locales_table WHERE Locale = '".$row['Locale']."'";
					$resultExtra2 = mysql_query($query) or die('Your attempt to select Full, Exclusive from the lcoales_table has failed. Here is the query: '.$query.mysql_error());
					$vacancyinfo = mysql_fetch_assoc($resultExtra2);
					if ($vacancyinfo['Exclusive'] != 1 && $vacancyinfo['Full'] != 1)
						{
						echo '<td>License Now Available</td>';
						}
					else if ($earliestdate >= date("Y-m-d")) // $earliestdate is >= (i.e. on or after) current date (i.e. date("Y-m-d") via the PHP date function), then $licstatus is 'Expiration due: $earliestdate'...
						{
						echo '<td>Earliest expiration: '.$earliestdate.'</td>';
						}
					else if ($latestdate < date("Y-m-d")) // $latestdate is < (i.e. before) current date, then $licstatus is 'Expired: $latestdate'...
						{
						echo '<td>Expired ('.$earliestdate.')</td>';
						}
					else // Need to scroll through the (pre-sorted from earliest to latest) $expdates array to find the first element that is after the current date
						{
						foreach ($expdates as $item)
							{
							if ($item > date("Y-m-d"))
								{
								echo '<td>Earliest expiration: '.$item.'</td>';
								break;
								};
							}
						};
					echo '<td>'.$row['WaitlisterName'].'</td>';
					echo '<td>'.$row['WaitlisterTelephone'].'</td>';
					echo '<td>'.$row['WaitlisterEmail'].'</td>';
					echo '</tr>';
					}
				?>
				<tr><td colspan="6" align="center"><br><input type="submit" name="DeleteWaitlisters" id="DeleteWaitlisters" <?php echo 'disabled'; ?> value="Delete"></td></tr>
				</tbody>
				</table>
				<?php
				}
				?>
			</div>
			</form>
			<script type="text/javascript" language="javascript">
			location.hash = "waitlisterstable";
			document.getElementById('waitlisterstable').scrollIntoView(true); // This seems to be a nice (superior?) alternative to using location.hash (or location.href='mypage.html#anchorname').
			</script>
			<?php
			}

		}
	else
		{
		// Authentication is denied.
		echo "<p class='basictext' style='position: absolute; left: 150px; margin-right: 50px; margin-top: 180px; font-size: 14px;'>Authentication is denied. Use your browser&rsquo;s Back button or ";
		// Include a 'Back' button for redisplaying the Authentication form.
		if (isset($_SERVER['HTTP_REFERER'])) // Antivirus software sometimes blocks HTTP_REFERER.
			{
			echo "<a style='font-size: 14px;' href='".$_SERVER['HTTP_REFERER']."'>click here</a> to try again.</p>";
			}
		else
			{
			echo "<a style='font-size: 14px;' href='javascript:history.back()'>click here</a> to try again.</p>";
			}
		}
	}
// Closing connection
mysql_close($db);
?>	
</body>
</html>