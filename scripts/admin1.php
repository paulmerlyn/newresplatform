<?php
/*
admin1.php allows an administrator (user) to add a new mediator i.e. to set up the locale, date of admission, license fee, license term, expiration date, and renewal fee for a new mediator (all of which gets added to the mediators_table or mediators_table_demo table (depending on whether the user is adding a client mediator or a demo mediator). Whether or not this mediator has an exclusive locale is also entered into that table. Also, details are updated in the locales_table (i.e. the NofLicenses count is incremented and the Full and the Exclusive flags are updated in the locales_table. The processing of this script is performed by admin4.php.
Note that the mediator's Username and Password are assigned automatically inside admin2.php (drawing from userpass_table for client mediators and userpass_table_demo for demo mediators).
*/
// Start a session
session_start();
$_SESSION['AntiReloadFlag'] = 'false'; // Used to prevent a user-forced reload (i.e. user hits F5 key) of admin2.php, which would resubmit the form data each time if this anti-reload device were not employed.

if (isset($_POST['ClientDemoSelection']))
	{
	$_SESSION['ClientDemoSelected'] = $_POST['ClientDemoSelection']; // Since contents of the post array aren't maintained for very long, store the fact that the user made a client-demo selection in a session variable.
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>New Resolution Mediation Launch Platform&trade; Administrator</title>
<link href="/nrcss.css" rel="stylesheet" type="text/css">
<link href="tigra_calendar/calendar.css" rel="stylesheet" type="text/css">
<!-- For European date format, use calendar_eu.js instead of calendar_us.js below. -->
<style>
select#Locale { behavior: url(/scripts/IEoptionhack.htc); } /* Hack because IE 6 and IE 7 don't respect the 'disabled' attribute of an option element in a drop-down (i.e. select) menu. (This shortcoming seems to have been rectified in IE8 though.) Thanks to: http://apptaro.seesaa.net/article/21140090.html for this non-invasive workaround! */
option#Locale { behavior: url(/scripts/IEoptionhack.htc); } /* Hack because IE 6 and IE 7 don't respect the 'disabled' attribute of an option element in a drop-down (i.e. select) menu. (This shortcoming seems to have been rectified in IE8 though.) Thanks to: http://apptaro.seesaa.net/article/21140090.html for this non-invasive workaround! */
</style>
<script language="JavaScript" type="text/javascript" src="tigra_calendar/calendar_us.js"></script>
<script>
function FocusFirst()
{
	if (document.forms.length > 0 && document.forms[0].elements.length > 0)
		document.forms[0].elements[0].focus();
};

/* Begin JS form validation functions. */
function checkLocaleOnly()
{
// Validate that a Locale was selected.
var localeValue = document.getElementById("Locale").value;
if (localeValue == 'null')
	{
	document.getElementById("LocaleError").style.display = "inline";
	return false;
	} 
else
	{
	return true;
	}
}

function checkDofAdmissionOnly()
{
// Validate DofAdmission input field.
var dofAdmissionValue = document.getElementById("DofAdmission").value;
var illegalCharSet = /[^0-9\/]+/; // Reject everything that contains one or more characters that is neither a slash (/) nor a digit. Note the need to escape the slash.
var reqdCharSet = /\d{2}\/\d{2}\/\d{4}/;  // Required format is MM/DD/YYYY.
if (illegalCharSet.test(dofAdmissionValue)  || !reqdCharSet.test(dofAdmissionValue))
	{
	document.getElementById("DofAdmissionError").style.display = "inline";
	return false;
	} 
else
	{
	return true;
	}
}

function checkLicenseFeeOnly()
{
// Validate LicenseFee input field.
var licenseFeeValue = document.getElementById("LicenseFee").value;
var illegalCharSet = /[^0-9\.]+/; // Reject everything that contains one or more characters that is neither a period nor a digit.
var reqdCharSet = /[0-9]+/;  // At least one numeric.
if (illegalCharSet.test(licenseFeeValue)  || !reqdCharSet.test(licenseFeeValue))
	{
	document.getElementById("LicenseFeeError").style.display = "inline";
	return false;
	} 
else
	{
	return true;
	}
}

function checkLicenseFeeAPROnly()
{
// Validate LicenseFeeAPR input field.
var licenseFeeAPRValue = document.getElementById("LicenseFeeAPR").value;
var illegalCharSet = /[^0-9\.]+/; // Reject everything that contains one or more characters that is neither a period nor a digit.
var reqdCharSet = /[0-9]+/;  // At least one numeric.
if (illegalCharSet.test(licenseFeeAPRValue)  || !reqdCharSet.test(licenseFeeAPRValue))
	{
	document.getElementById("LicenseFeeAPRError").style.display = "inline";
	return false;
	} 
else
	{
	return true;
	}
}

function checkRenewalFeeOnly()
{
// Validate RenewalFee input field.
var renewalFeeValue = document.getElementById("RenewalFee").value;
var illegalCharSet = /[^0-9\.]+/; // Reject everything that contains one or more characters that is neither a period nor a digit.
var reqdCharSet = /[0-9]+/;  // At least one numeric.
if (illegalCharSet.test(renewalFeeValue)  || !reqdCharSet.test(renewalFeeValue))
	{
	document.getElementById("RenewalFeeError").style.display = "inline";
	return false;
	} 
else
	{
	return true;
	}
}

function checkForm() // To ease complexity, I include only checkLocaleOnly() within checkForm() (which gets called when the user clicks the submit button). I don't bother to validate other elements such as LicenseFee and LicenseFeeAPR inside checkForm() for simplicity because those other elements aren't even displayed when the demo (rather than client) database is chosen. Besides, other elements such as LicenseFee and LicenseFeeAPR are validated onblur, so it's really not necessary to javascript validate them on form submission as well. (And they are validated by PHP within admin2.php as a back up!).
{
hideAllErrors();
if (checkLocaleOnly()) // Check validity of this core element common to demo and client cases.
	{
	if (document.getElementById("DofAdmission") != null && document.getElementById("DofAdmission").value != '') // Only check DofAdmission (which is used in client case only) if it exists.
		{
		if (!checkDofAdmissionOnly()) return false; // Return a false if the checkDofAdmissionOnly() function is false (i.e. invalid).
		}
	if (document.getElementById("LicenseFee") != null && document.getElementById("LicenseFee").value != '') // Only check LicenseFee (which is used in client case only) if it exists.
		{
		if (!checkLicenseFeeOnly()) return false; // Return a false if the checkLicenseFeeOnly() function is false (i.e. invalid).
		};
	if (document.getElementById("LicenseFeeAPR") != null && document.getElementById("LicenseFeeAPR").value != '') // Only check LicenseFeeAPR (which is used in client case only) if it exists.
		{
		if (!checkLicenseFeeAPROnly()) return false; // Return a false if the checkLicenseFeeAPROnly() function is false (i.e. invalid).
		};
	if (document.getElementById("RenewalFee").value != null && document.getElementById("RenewalFee").value != '') // Only check RenewalFee (which is used in client case only) if it exists.
		{
		if (!checkRenewalFeeOnly()) return false; // Return a false if the checkRenewalFeeOnly() function is false (i.e. invalid).
		};
	return true; // All elements passed their validity checks, so return a true.
	}
else
	{
	return false; // The core element Locale failed its individual check function, so return a false;
	};
} // End of checkForm()

/* This function hideAllErrors() is called by checkForm() and by onblur event within non-personal form fields. */
function hideAllErrors()
{
document.getElementById("LocaleError").style.display = "none";
if (document.getElementById("DofAdmission") != null) document.getElementById("DofAdmissionError").style.display = "none"; // The DofAdmission element won't exist for demo cases. Only modify the display style to none if the element exists (i.e. for client cases).
if (document.getElementById("LicenseFee") != null) document.getElementById("LicenseFeeError").style.display = "none"; // The LicenseFee element won't exist for demo cases. Only modify the display style to none if the element exists (i.e. for client cases).
if (document.getElementById("LicenseFeeAPR") != null) document.getElementById("LicenseFeeAPRError").style.display = "none"; // The LicenseFeeAPR element won't exist for demo cases. Only modify the display style to none if the element exists (i.e. for client cases).
if (document.getElementById("RenewalFee") != null) document.getElementById("RenewalFeeError").style.display = "none"; // The LicenseFeeAPR element won't exist for demo cases. Only modify the display style to none if the element exists (i.e. for client cases).
return true;
}

function ClearCheckBoxElements()
{
document.getElementById('Exclusive').disabled = false;
document.getElementById('Exclusive').checked = false;
document.getElementById('OverrideDefaultAPR').checked = false;
document.getElementById('OverrideAPR').checked = false;

document.getElementById('RenewalFee').value = ''; // Clear any user-typed data from the Renewal Fee field.
};

/*
Functions checkLicenseFeeAPRForResetOnly() and checkRenewalFeeForResetOnly() are used within the Renewal APR sandbox. I haven't combined them with any sort of checkForm() function b/c that's not worthwhile. I simply check each respective function onkeyup. Note that since the sandbox doesn't entail any form submission, there's no need for PHP versions of these two functions within admin2.php.
*/
function checkLicenseFeeAPRForResetOnly()
{
// Validate LicenseFeeAPRForReset input field.
var licenseFeeAPRForResetValue = document.getElementById("LicenseFeeAPRForReset").value;
var illegalCharSet = /[^0-9\.]+/; // Reject everything that contains one or more characters that is neither a period nor a digit.
var reqdCharSet = /[0-9]+/;  // At least one numeric.
if (illegalCharSet.test(licenseFeeAPRForResetValue)  || !reqdCharSet.test(licenseFeeAPRForResetValue))
	{
	document.getElementById("LicenseFeeAPRForResetError").style.display = "inline";
	return false;
	} 
else
	{
	return true;
	}
}

function checkRenewalFeeForResetOnly()
{
// Validate RenewalFeeForReset input field.
var renewalFeeForResetValue = document.getElementById("RenewalFeeForReset").value;
var illegalCharSet = /[^0-9\.]+/; // Reject everything that contains one or more characters that is neither a period nor a digit.
var reqdCharSet = /[0-9]+/;  // At least one numeric.
if (illegalCharSet.test(renewalFeeForResetValue)  || !reqdCharSet.test(renewalFeeForResetValue))
	{
	document.getElementById("RenewalFeeForResetError").style.display = "inline";
	return false;
	} 
else
	{
	return true;
	}
}

/*
Function calcRenewalFeeForAPR is used by the ResetRenewalFee form to calculate the Renewal Fee for a given APR. It uses the  A = P(1 + r/100)^n formula where P = existing license fee (available as form element LicenseFee), r = the monthly equivalent of the APR, and n = the existing term in months (available as a hidden form element ExistingTerm).
*/
function calcRenewalFeeForAPR(apr)
{
	var apr, mthlyrate, renewalfee, existingterm, existingfee;
	
	existingterm = document.getElementById('ExistingTerm').value;
	existingfee = document.getElementById('LicenseFee').value;
	
	mthlyrate = (Math.pow(1 + apr/100, 1/12) - 1) * 100;  // Calculate the equivalent monthly % interest rate rM using rearranged formula A = P [1 + rM/100]^n where exponent is 1/12
	renewalfee = existingfee * Math.pow(1 + mthlyrate/100, existingterm); // Calculate renewalfee using A = P(1 + r/100)^n where r is mthlyrate, and n is the existing term in months.

	renewalfee = renewalfee.toFixed(2);
	return renewalfee;
}

/*
Function calcAPRForRenewalFee is used by the Renewal Fee APR sandbox to calculate the APR that equates to the renewal fee set by the Administrator for a mediator on term T (e.g. T=3 months). It uses the  A = P(1 + r/100)^n formula in two steps. First, calculate the equivalent monthly % interest rate rM using rM = [(A/P)^(1/m) - 1] * 100 where A = the renewal fee, P = existing license fee (available as form element LicenseFee), and m = the existing term in months (available from hidden form element ExistingTerm). Second, convert from the monthly rate rM to the annual percentage rate APR (rY) using rY = [(1 + rM/100)^12 - 1] * 100. (Note that a PHP version of this function (not used in this admin1.php and admin2.php module) has the same function name!)
*/
function calcAPRForRenewalFee(renfee)
{
	var apr, mthlyrate, renfee, existingterm, existingfee;
	
	existingterm = document.getElementById('ExistingTerm').value;
	existingfee = document.getElementById('LicenseFee').value;
	
	mthlyrate = (Math.pow(renfee/existingfee, 1/existingterm) - 1) * 100;  // First calculate the equivalent monthly % interest rate using rearranged formula A = P [1 + r/100]^n
	apr = (Math.pow(1 + mthlyrate/100, 12) - 1) * 100; // Second calculate the APR by converting the monthly rate. Create a conversion equation by equating the amount A after 12 months and 1 year as follows: A = P(1 + rM/100)^12 = P(1 + rY/100)^1 such that rM is the mthlyrate, rY is the annual APR rate. Rearranging the equality provides rY = [(1 + rM/100)^12 - 1] * 100

	apr = apr.toFixed(2);
	return apr;
}

// This function "extracts" the license fee for the relevant license term. Its only input argument is the ID attribute of a radio button (e.g. LicenseTerm4) that is selected by the Administrator/user to indicate the desired license term. The function uses string manipulation to obtain just the numeric suffix from that ID (e.g. 4). That suffix is then appended to the string "LicFeeForTerm" to make "LicFeeForTerm4", which will be the ID of the hidden field whose value is the associated license fee for the selected radio button license term.
function ExtractLicenseFee(theID)
{
var ElementID, licfee;
var myRegExp = /[0-9]/; // check this syntax!
var matchPos1 = theID.search(myRegExp);
// Use another JS string function to obtain the number in the name from matchPos1 onwards, perhaps by splitting it at (matchPos1-1) ...?
var NumberSuffix = theID.substring(matchPos1); // This JS method extracts from the first digit in theID to the end of the string i.e. it extracts the full number (probably a single digit).
ElementID = "LicFeeForTerm" + NumberSuffix;  // Create the element ID of the hidden field associated with the selected LicenseTerm radio button and whose value is the associated license fee for that term.
licfee = document.getElementById(ElementID).value;
return licfee;
}
</script>
</head>

<body>
<div style="margin: 10px auto; padding: 0px; text-align: center;">
<form method="post" action="unwind.php" style="display: inline;">
<input type="submit" name="ChangeDB" class="submitLinkSmall" value="Change Database">
</form>
&nbsp;&nbsp;&nbsp;&nbsp;
<form method="post" action="unwind.php" style="display: inline;">
<input type="submit" name="Logout" class="submitLinkSmall" value="Log Out">
</form>
</div>

<h1 style="text-align: center; font-size: 22px; padding: 0px; color: #425A8D;">New Resolution Mediation Launch Platform&trade; Administrator</h1>
<?php
require('../ssi/adminmenu.php'); // Include the navigation menu.
require('../ssi/obtainparameters.php'); // Include the obtainparameters.php file, which retrieves values $DefaultAPR and $FeeTermArray from the parameters_table table.

// Create short variable names
$Authentication = $_POST['Authentication'];
$Locale = $_POST['Locale']; // Selected by administrator from drop-down menu in admin1.php.

if  (empty($Authentication) && $_SESSION['Authenticated'] != 'true')
	{
	// Visitor needs to authenticate
	?>
	<div style="position: absolute; left: 240px; top: 100px; width: 250px; padding: 15px; border: 2px solid #444444; border-color: #425A8D;">
	<h3>Please authenticate yourself:</h3>
	<br>
	<script type="text/javascript">window.onload = FocusFirst;</script>
	<form method="post" action="/scripts/admin1.php">
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
		if (!isset($_SESSION['ClientDemoSelected']))
			{
			// Show visitor the form to select either client or demo database access.
			?>
			<div style="position: absolute; left: 240px; top: 100px; width: 250px; text-align:center; padding: 15px; border: 2px solid #444444; border-color: #425A8D;">
			<h3>I want to access the following database:</h3>
			<form method="post" action="/scripts/admin1.php">
			<input type="radio" name="ClientDemoSelection" id="ClientDemoSelection" value="client" checked>
			<label for="ClientDemoSelection">Client&nbsp;&nbsp;&nbsp;</label>
			<input type="radio" name="ClientDemoSelection" id="ClientDemoSelection" value="demo">
			<label for="ClientDemoSelection">Demo</label>
			<br><br>
			<input type="submit" name="ClientDemoButton" value="Continue" class="buttonstyle">
			</form>
			</div>
			<?php
			exit;
			}
		else // A client vs demo selection had previously been made. Show the main screen.
			{
			?>
			<div style="margin: 20px auto; width: 530px; padding: 15px; border: 2px solid #444444; border-color: #425A8D;">
			<h1 style="margin-left: 0px;">Add a mediator to the <?php if ($_SESSION['ClientDemoSelected'] == 'client') echo 'client'; else echo 'demo'; ?> database:</h1><br>
			
			<?php
			// Count the number of unallocated username-password pairs from either userpass_table (client mediators) or userpass_table_demo (demo mediators). If the number is less than 10, issue a warning in red ink. If the number is zero, disable the 'Create New Mediator' button.
			switch ($_SESSION['ClientDemoSelected'])
			{
			case 'client' :
				$userpasstablename = 'userpass_table';
				break;
			case 'demo' :
				$userpasstablename = 'userpass_table_demo';
				break;
			default :
				echo 'Error: Unable to determine the name of an appropriate username-password table (client or demo) in admin1.php.<br>';
				exit;
			}
			// Establish a mysql connection.
			$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
			or die('Could not connect: ' . mysql_error());
			mysql_select_db('paulme6_newresolution') or die('Could not select database: ' . mysql_error());

			$query = "SELECT count(*) FROM ".$userpasstablename." WHERE Available = 1"; // Count of only unallocated username-password pairs
			$result = mysql_query($query) or die('The SELECT query count of available username-password pairs failed i.e. '.$query.' failed: ' . mysql_error());
			$row = mysql_fetch_row($result); // $row array contains just one value i.e. the value of the count of all available UP pairs.
			$rowAvail = $row[0];
			
			if ($rowAvail < 10) echo '<div class="errorphp">&nbsp;&nbsp;Warning: You have less than 10 username-password pairs available. Add more immediately!</div><br>';

			?>
			
			<form method="post" name="AddMediator" action="/scripts/admin2.php">
			
			<table width="530">
			<tr>
			<td width="125"><label>Select State</label></td>
			<td>
			<select name="StateOfInterest" id="StateOfInterest" size="1" style="font-size: 12px; font-weight:normal; font-family:Arial,Helvetica,'Century Gothic', sans-serif" <?php if ($_SESSION['ClientDemoSelected'] == 'client') echo ' onChange="ClearCheckBoxElements(); this.form.submit();"'; else echo ' onChange="this.form.submit();"'; ?>> <!-- There are no Exclusive et al check boxes to clear when $_SESSION['ClientDemoSelected'] is 'demo'. -->
			<?php
			// Note: this code for generating a drop-down menu of states was first written for updateprofile.php.
			$statesArray = array(array('&lt;&nbsp;Select a State&nbsp;&gt;',null), array('Alabama','AL'),	array('Alaska','AK'), array('Arizona','AZ'), array('Arkansas','AR'),	array('California','CA'), array('Colorado','CO'), array('Connecticut','CT'), array('Delaware','DE'), array('District of Columbia (D.C.)','DC'), array('Florida','FL'), array('Georgia','GA'), array('Hawaii','HI'), array('Idaho','ID'), array('Illinois','IL'), array('Indiana','IN'), array('Iowa','IA'), array('Kansas','KS'), array('Kentucky','KY'), array('Louisiana','LA'), array('Maine','ME'), array('Maryland','MD'), array('Massachusetts','MA'), array('Michigan','MI'), array('Minnesota','MN'), array('Mississippi','MS'), array('Missouri','MO'), array('Montana','MT'), array('Nebraska','NE'), array('Nevada','NV'), array('New Hampshire','NH'), array('New Jersey','NJ'), array('New Mexico','NM'), array('New York','NY'), array('North Carolina','NC'), array('North Dakota','ND'), array('Ohio','OH'), array('Oklahoma','OK'), array('Oregon','OR'), array('Pennsylvania','PA'), array('Rhode Island','RI'), array('South Carolina','SC'), array('South Dakota','SD'), array('Tennessee','TN'), array('Texas','TX'), array('Utah','UT'), array('Vermont','VT'), array('Virginia','VA'), array('Washington','WA'), array('Washington, D.C.','DC'), array('West Virginia','WV'), array('Wisconsin','WI'), array('Wyoming','WY'), array('United States (Generic&ndash;US)','US'));
			for ($i=0; $i<54; $i++)
			{
				$optiontag = '<option value="'.$statesArray[$i][1].'" ';
				if (isset($_SESSION['StateSelected'])) // Preset to previously selected state if one was previously selected.
					{
					if ($_SESSION['StateSelected'] == $statesArray[$i][1]) { $optiontag = $optiontag.'selected'; }
					}
				$optiontag = $optiontag.'>'.$statesArray[$i][0]."</option>\n";
				echo $optiontag;
			}
			?>
			</select>
			<!-- This submit button is no longer necessary when JS is enabled b/c submission takes place via the onchange method of the 'StateOfInterest' select element. --> 
			<noscript><input type="submit" value="Select" name="StateOfInterestSelected" class="buttonstyle"></noscript>
			</td>
			</tr>
			</table>
			
			<table width="530" style="display: <?php if (isset($_SESSION['StateSelected']) && $_SESSION['StateSelected'] != null) { echo 'block'; } else { echo 'none'; }; ?>"> <!-- Only display this table if the 'StateSelected' session variable has been set (which happens inside admin2.php) and is a non-null value -->
			<tr>
			<td width="125"><label>Locale</label></td>
			<td>
			<select name="Locale" id="Locale" size="1" style="font-size: 12px; font-weight: normal; font-family: Arial, Helvetica, sans-serif;" onBlur="hideAllErrors(); return checkLocaleOnly();" onChange= "hideAllErrors(); <?php if ($_SESSION['ClientDemoSelected'] == 'client') echo 'ClearCheckBoxElements();'; ?>"> <!-- There are no Exclusive et al check boxes to clear when $_SESSION['ClientDemoSelected'] is 'demo'. -->
			<option value=null selected>&lt;&nbsp;Select a Locale&nbsp;&gt;</option>
			<?php

/* Obtain the locales that belong to the selected state of interest. We'll place these locales in an array, which we'll use to build a drop-down menu by which the user can select a particular locale. */

// If $_SESSION['StateSelected'] is neither null nor '' (i.e. if the State drop-down menu isn't still in its neutral position), formulate query to obtain the locales that belong to the selected state of interest.
if ($_SESSION['StateSelected'] != null && $_SESSION['StateSelected'] != '')
	{
	$query = "select Locale, LocaleShort, Exclusive, LocaleStates, MaxLicenses, NofLicenses, Full from locales_table WHERE LocaleStates LIKE '%".$_SESSION['StateSelected']."%'";
	$result = mysql_query($query) or die('Query (select all Locale values from table) failed: ' . mysql_error());
	if (!$result) {	echo 'No $result was achievable.'; };

	// Place the result of the query in an array $line via the mysql_fetch_assoc command. The associative values $line['Locale'], $line['LocaleShort'], etc. on moving down through the lines of this array will hold the Locale, LocaleShort, etc. values. Then push each of these value into array $localesArray[] and $localesShortArray respectively, where the contents can then be sorted alphabetically before use in the option tag of the locales drop-down menu.
	$localesArray = array(); // Declare and initialize array
	$localesShortArray = array(); // Declare and initialize array
	while ($line = mysql_fetch_assoc($result))
		{
		array_push($localesArray, $line['Locale']);
		if ($_SESSION['ClientDemoSelected'] == 'client') // Don't bother to append '[Excl.]' and [Full]' to $line['LocaleShort'] if 'demo' is selected.
			{
				if ($line['Exclusive'] == 1) // Append to the $localesShortString string a suffix '[Excl.]' (for exclusive) or '[Full]' (for full) locales
					{
					array_push($localesShortArray, $line['LocaleShort'].' [Excl.]'); // Append the word '[Excl.]' to the LocaleShort item for exclusive locales
					}
				elseif ($line['Full'] == 1)
					{
					array_push($localesShortArray, $line['LocaleShort'].' [Full]'); // Append the word '[Full]' to the LocaleShort item for full locales
					}
				else //  For client case when the locale is neither Exclusive nor Full
					{
					array_push($localesShortArray, $line['LocaleShort']);
					}
			}
		else // For demo case
			{
				array_push($localesShortArray, $line['LocaleShort']);
			}
		};
	sort($localesArray);
	sort($localesShortArray);

	$arrayLength = count($localesArray);
	for ($i=0; $i<$arrayLength; $i++)
		{
		$optiontag = '<option value="'.$localesArray[$i].'"';
		/* Note on option disablement: IE 6 and 7 don't implement the disabled attribute of the option element. I address that bug courtesy of http://apptaro.seesaa.net/article/21140090.html for a great non-invasive workaround! I use it with the necessary inclusion of "select, option { behavior: url(sample.htc); }" in my external stylesheet files nrcss.css and salescss.css. It's a necessary hack because IE doesn't respect the 'disabled' attribute of an option element in a drop-down (i.e. select) menu. Best of all, it's non-invasive, so you can just create standard HTML with the 'disabled' attribute. The standard code will work fine in Firefox, and this hack will make the disablement work in IE. Note that I disable option elements in drop-down menus such as the locale selector inside sales.php (and admin1.php) when a locale is full or exclusive and therefore not available to another mediator/licensee. */
		if (strpos($localesShortArray[$i], '[Excl.]') != false) $optiontag .= ' disabled'; // Disable option item if '[Excl.]' was appended to the array item in the short version of this locale.
		if (strpos($localesShortArray[$i], '[Full]') != false) $optiontag .= ' disabled'; // Disable option item if '[Full]' was appended to the array item in the short version of this locale.
		if (html_entity_decode($localesArray[$i], ENT_QUOTES, 'UTF-8') == $_SESSION['LocaleSelected']) $optiontag .= ' selected'; // Preset LocaleOfInterest drop-down menu to previously selected value. This is necessary for reload of sales.php on return from the form processor script admin2.php. Note use of html_entity_decode to handle use of special characters such as the ntilde in 'Canon City_CO' and the single quote in Coeur d'Alene_ID. See PHP manual http://us.php.net/manual/en/function.html-entity-decode.php, including Matt Robinson's contributed note for why I use ENT_QUOTES and UTF-8 parameters.
		$optiontag .= '>'.$localesShortArray[$i]."</option>\n";
		echo $optiontag;
		}
	}
	
// Closing connection
mysql_close($db);
			?>
			</select>
			<div class="error" id="LocaleError"><br>Please select a locale from the drop-down menu.<br></div>
			<?php if ($_SESSION['MsgLocale'] != null) { echo $_SESSION['MsgLocale']; $_SESSION['MsgLocale']=null; } ?>
			</td>
			</tr>
			<?php if ($_SESSION['ClientDemoSelected'] == 'client') // Don't show this HTML elements if 'demo' was chosen by the user.
			{
			?>
			<tr>
			<td><label>Exclusive Purchase?</label></td>
			<td>
			<input type="checkbox" name="Exclusive" id="Exclusive" value="1" style="position: relative; right: 4px;" onClick="if (this.checked) this.form.submit();">
			</td>
			</tr>
			<?php
			}
			if ($_SESSION['ClientDemoSelected'] == 'client') // Don't show these other HTML elements if 'demo' was chosen by the user.
			{
			?>
			<tr>
			<td><label>Date of Admission</label></td>
			<td>
			<input type="text" name="DofAdmission" id="DofAdmission" maxlength="10" size="10" value="<?=date('m/d/Y');?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkDofAdmissionOnly();">
			<script language="JavaScript">
			new tcal ({
			'formname': 'AddMediator',
			'controlname': 'DofAdmission'
			});
			</script>
			<div class="error" id="DofAdmissionError"><br>Date must have format MM/DD/YYYY. Use only numbers and slash (/) character.<br></div>
			<?php if ($_SESSION['MsgDofAdmission'] != null) { echo $_SESSION['MsgDofAdmission']; $_SESSION['MsgDofAdmission']=null; } ?></td>
			</tr>
			<tr>
			<td><label>License Fee $</label></td>
			<td>
			<input type="text" name="LicenseFee" id="LicenseFee" maxlength="8" size="8" value="<?=$FeeForTermArray[12]; ?>" onFocus="this.style.background='#FFFF99'" onBlur="this.value = this.value.replace(/,/g, ''); this.style.background='white'; hideAllErrors(); return checkLicenseFeeOnly();"> <!-- The initialization value of this input element is the dollar value for 12 months, as taken from the $FeeTermArray array, which is created in the require'd obtainparameters.php file. -->
			<div class="error" id="LicenseFeeError"><br>Enter a dollar value. Use only numbers [0-9] and the period (.) character.<br></div>
			<?php if ($_SESSION['MsgLicenseFee'] != null) { echo $_SESSION['MsgLicenseFee']; $_SESSION['MsgLicenseFee']=null; } ?>
			</td>
			</tr>
			<tr>
			<td><label>License Term</label></td>
			<td>
			<?php
			/* Now go through $FeeTermArray's term elements to identify which term lengths are being offered (as configured by the Administrator using admin7.php) and thus create a set of radio buttons with appropriate labels and values. For example, if we find that $FeeTermArray contains a term length element of '3' then we need a radio button with a label of 'Quarter' because the Administrator has defined one quarter as one of the term lengths being offered to mediators. Note that $FeeTermArray and $NofFeeTermPairs are obtained via the require(obtainparameters.php) statement. */
			$FeeIndexPointer = 0; // Keeps track of the index for successive fee elements in the $FeeTermArray fee-term value-pair array. (Note: it increments in steps of 2 to point to index 0, 2, 4, 6, etc.
			$TermIndexPointer = 1; // Keeps track of the index for successive term elements in the $FeeTermArray fee-term value-pair array. (Note: it increments in steps of 2 to point to index 1, 3, 5, 7, etc.
			for ($i=1; $i <= $NofFeeTermPairs; $i++) // When $i is 1, we generate the first row of the table; when $i is 2, we generate the second row, and so on until we've created one row for each fee-term value-pair in the $FeeTermArray (which is itself retrieved from the DB's parameters_table).
			// Note: the purpose of the "if (this.checked) document.getElementById(\'ExistingTerm\').value = this.value;" code in each of the case clauses below is to ensure that the hidden field id=ExistingTerm (which is used by the RenewalSandbox) gets assigned the value of the term selected via the radio button elements.
				{
				switch($FeeTermArray[$TermIndexPointer])
					{
					case '1':
						echo '<input type="radio" name="LicenseTerm" id="LicenseTerm'.$i.'" value="1" onchange="document.getElementById(\'LicenseFee\').value = ExtractLicenseFee(this.id); if (this.checked) document.getElementById(\'ExistingTerm\').value = this.value;" onclick="this.blur();">';
						// Note the inclusion of the onclick="this.blur(); event handler. This is a neat workaround (courtesy of http://bytes.com/topic/javascript/answers/538606-internet-explorer-onchange-issue) for fact that IE's event handler would require a second click before firing i.e. without inclusion of onclick="this.blur()", the License Fee field would only update inside admin1.php after the user had made a second click (anywhere on the screen) after clicking a license term radio button -- it wouldn't fire on the initial click. IE's implementation is silly (Firefox is more logical) but the workaround solves the issue. I've fixed the same issue in admin5A.php also.
echo '<label>Month&nbsp;&nbsp;&nbsp;</label>';
						echo '<input type="hidden" name="LicFeeForTerm'.$i.'" id="LicFeeForTerm'.$i.'" value="'.$FeeTermArray[$FeeIndexPointer].'">';
						break;
					case '2':
						echo '<input type="radio" name="LicenseTerm" id="LicenseTerm'.$i.'" value="2" onchange="document.getElementById(\'LicenseFee\').value = ExtractLicenseFee(this.id); if (this.checked) document.getElementById(\'ExistingTerm\').value = this.value;" onclick="this.blur();">';
						echo '<label>2 Months&nbsp;&nbsp;&nbsp;</label>';
						echo '<input type="hidden" name="LicFeeForTerm'.$i.'" id="LicFeeForTerm'.$i.'" value="'.$FeeTermArray[$FeeIndexPointer].'>">';
						break;
					case '3':
						echo '<input type="radio" name="LicenseTerm" id="LicenseTerm'.$i.'" value="3" onchange="document.getElementById(\'LicenseFee\').value = ExtractLicenseFee(this.id); if (this.checked) document.getElementById(\'ExistingTerm\').value = this.value;" onclick="this.blur();">';
						echo '<label>Quarter&nbsp;&nbsp;&nbsp;</label>';
						echo '<input type="hidden" name="LicFeeForTerm'.$i.'" id="LicFeeForTerm'.$i.'" value="'.$FeeTermArray[$FeeIndexPointer].'">';
						break;
					case '6':
						echo '<input type="radio" name="LicenseTerm" id="LicenseTerm'.$i.'" value="6" onchange="document.getElementById(\'LicenseFee\').value = ExtractLicenseFee(this.id); if (this.checked) document.getElementById(\'ExistingTerm\').value = this.value;" onclick="this.blur();">';
						echo '<label>6 Months&nbsp;&nbsp;&nbsp;</label>';
						echo '<input type="hidden" name="LicFeeForTerm" id="LicFeeForTerm'.$i.'" value="'.$FeeTermArray[$FeeIndexPointer].'">';
						break;
					case '12':
						echo '<input type="radio" name="LicenseTerm" id="LicenseTerm'.$i.'" value="12" checked  onchange="document.getElementById(\'LicenseFee\').value = ExtractLicenseFee(this.id); if (this.checked) document.getElementById(\'ExistingTerm\').value = this.value;" onclick="this.blur();">';
						echo '<label>Year&nbsp;&nbsp;&nbsp;</label>';
						echo '<input type="hidden" name="LicFeeForTerm'.$i.'" id="LicFeeForTerm'.$i.'" value="'.$FeeTermArray[$FeeIndexPointer].'">';
						break;
					case '18':
						echo '<input type="radio" name="LicenseTerm" id="LicenseTerm'.$i.'" value="18" onchange="document.getElementById(\'LicenseFee\').value = ExtractLicenseFee(this.id); if (this.checked) document.getElementById(\'ExistingTerm\').value = this.value;" onclick="this.blur();">';
						echo '<label>18 Months&nbsp;&nbsp;&nbsp;</label>';
						echo '<input type="hidden" name="LicFeeForTerm'.$i.'" id="LicFeeForTerm'.$i.'" value="'.$FeeTermArray[$FeeIndexPointer].'">';
						break;
					case '24':
						echo '<input type="radio" name="LicenseTerm" id="LicenseTerm'.$i.'" value="24" onchange="document.getElementById(\'LicenseFee\').value = ExtractLicenseFee(this.id); if (this.checked) document.getElementById(\'ExistingTerm\').value = this.value;" onclick="this.blur();">';
						echo '<label>24 Months&nbsp;&nbsp;&nbsp;</label>';
						echo '<input type="hidden" name="LicFeeForTerm'.$i.'" id="LicFeeForTerm'.$i.'" value="'.$FeeTermArray[$FeeIndexPointer].'">';
						break;
					case '36':
						echo '<input type="radio" name="LicenseTerm" id="LicenseTerm'.$i.'" value="36" onchange="document.getElementById(\'LicenseFee\').value = ExtractLicenseFee(this.id); if (this.checked) document.getElementById(\'ExistingTerm\').value = this.value;" onclick="this.blur();">';
						echo '<label>3 Years&nbsp;&nbsp;&nbsp;</label>';
						echo '<input type="hidden" name="LicFeeForTerm'.$i.'" id="LicFeeForTerm'.$i.'" value="'.$FeeTermArray[$FeeIndexPointer].'">';
						break;
					case '48':
						echo '<input type="radio" name="LicenseTerm" id="LicenseTerm'.$i.'" value="48" onchange="document.getElementById(\'LicenseFee\').value = ExtractLicenseFee(this.id); if (this.checked) document.getElementById(\'ExistingTerm\').value = this.value;" onclick="this.blur();">';
						echo '<label>4 Years&nbsp;&nbsp;&nbsp;</label>';
						echo '<input type="hidden" name="LicFeeForTerm'.$i.'" id="LicFeeForTerm'.$i.'" value="'.$FeeTermArray[$FeeIndexPointer].'">';
						break;
					case '60':
						echo '<input type="radio" name="LicenseTerm" id="LicenseTerm'.$i.'" value="60" onchange="document.getElementById(\'LicenseFee\').value = ExtractLicenseFee(this.id); if (this.checked) document.getElementById(\'ExistingTerm\').value = this.value;" onclick="this.blur();">';
						echo '<label>5 Years&nbsp;&nbsp;&nbsp;</label>';
						echo '<input type="hidden" name="LicFeeForTerm'.$i.'" id="LicFeeForTerm'.$i.'" value="'.$FeeTermArray[$FeeIndexPointer].'">';
						break;
					case '120':
						echo '<input type="radio" name="LicenseTerm" id="LicenseTerm'.$i.'" value="120" onchange="document.getElementById(\'LicenseFee\').value = ExtractLicenseFee(this.id); if (this.checked) document.getElementById(\'ExistingTerm\').value = this.value;" onclick="this.blur();">';
						echo '<label>10 Years&nbsp;&nbsp;&nbsp;</label>';
						echo '<input type="hidden" name="LicFeeForTerm'.$i.'" id="LicFeeForTerm'.$i.'" value="'.$FeeTermArray[$FeeIndexPointer].'">';
						break;
					default:
						echo '<input type="radio" name="LicenseTerm" id="LicenseTerm'.$i.'" value="'.$FeeTermArray[$TermIndexPointer].'" onchange="document.getElementById(\'LicenseFee\').value = ExtractLicenseFee(this.id); if (this.checked) document.getElementById(\'ExistingTerm\').value = this.value;" onclick="this.blur();">';
						echo '<label>'.$FeeTermArray[$TermIndexPointer].'&nbsp;Months&nbsp;&nbsp;&nbsp;</label>';
						echo '<input type="hidden" name="LicFeeForTerm'.$i.'" id="LicFeeForTerm'.$i.'" value="'.$FeeTermArray[$FeeIndexPointer].'">';
						break;
					}
				if ($i%5 == 0) echo '<br/>';  // Create a break after every five term radio buttons (by testing whether $i modulus 5 is zero) to avoid an ugly split over two lines.
				$TermIndexPointer = $TermIndexPointer + 2;
				$FeeIndexPointer = $FeeIndexPointer + 2;
				}
			?>
			</td>
			</tr>
			<tr>
			<td><label>APR (Fee Increase)</label></td>
			<td>
			<input type="text" name="LicenseFeeAPR" id="LicenseFeeAPR" maxlength="6" size="5" value="<?=$DefaultAPR; ?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; if (document.getElementById('OverrideDefaultAPR').checked) { hideAllErrors(); return checkLicenseFeeAPROnly(); };" disabled>&nbsp;<label>%</label>&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="OverrideDefaultAPR" id="OverrideDefaultAPR" value="yes" onClick="if (this.checked) { document.getElementById('LicenseFeeAPR').disabled = false; document.getElementById('LicenseFeeAPR').focus(); document.getElementById('OverrideAPR').disabled = true; document.getElementById('RenewalFee').disabled = true; } else { document.getElementById('LicenseFeeAPR').disabled = true; document.getElementById('OverrideAPR').disabled = false; };">&nbsp;<label class="small">[check box to override default APR]</label>
			<div class="error" id="LicenseFeeAPRError"><br>Enter a percentage (%). Use only numbers [0-9] and the period (.) character.<br></div>
			<?php if ($_SESSION['MsgLicenseFeeAPR'] != null) { echo $_SESSION['MsgLicenseFeeAPR']; $_SESSION['MsgLicenseFeeAPR']=null; } ?>
			</td>
			</tr>
			<tr>
			<td><label>Renewal Fee $</label></td>
			<td><input type="text" name="RenewalFee" id="RenewalFee" maxlength="8" size="8" onFocus="this.style.background='#FFFF99'" onBlur="this.value = this.value.replace(/,/g, ''); this.style.background='white'; if (document.getElementById('OverrideAPR').checked) { hideAllErrors(); return checkRenewalFeeOnly(); };" disabled>&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="OverrideAPR" id="OverrideAPR" value="yes" onClick="if (this.checked) { document.getElementById('RenewalFee').disabled = false; document.getElementById('RenewalFee').focus(); document.getElementById('LicenseFeeAPR').disabled = true; document.getElementById('OverrideDefaultAPR').disabled = true; } else { document.getElementById('RenewalFee').disabled = true; document.getElementById('OverrideDefaultAPR').disabled = false; };">&nbsp;<label class="small">[check box to override APR-based calculation]</label>
			<div class="error" id="RenewalFeeError"><br>Enter a dollar value. Use only numbers [0-9] and the period (.) character.<br></div>
			<?php if ($_SESSION['MsgRenewalFee'] != null) { echo $_SESSION['MsgRenewalFee']; $_SESSION['MsgRenewalFee']=null; } ?>
			</td>
			</tr>
			<?php
			}
			?>
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr>
			  <td colspan="2" align="left"><input type="submit" name="CreateNewMediator" <?php if ($rowAvail > 0) echo 'class="buttonstyle"'; ?> style="margin-left: 130px;" value="Create New Mediator" onClick="return checkForm();" <?php if ($rowAvail == 0) echo 'disabled'; ?>></td>
			</tr>
			</table>
			</form>
			</div>
			
			<!-- The following sandbox section is a simplified version of the "Reset renewal fee" section of admin5A.php. It's only relevant for client mediators, so don't show it for demo case. -->
			<?php
			if ($_SESSION['ClientDemoSelected'] == 'client' && $_SESSION['StateSelected'] != null)
			{
			?>
			<div id="RenewalSandbox" style="margin: <?= ($rowAvail >=10 ? '10px' : '43px'); ?> auto; width: 530px; padding: 15px 15px 10px 15px; border: 2px solid #444444; border-color: #425A8D; display: block;">
			<h1 style="margin-left: 0px;">Explore renewal APR (fee increase) in sandbox :</h1>
			<br>
			<form><!-- No method or action necessary here b/c all the form processing is JS client-side -->
			<table width="530" cellpadding="5">
			<tr> <!-- The client-side JS used with this ResetRenewalFee form uses one hidden field: ExistingTerm (i.e. the current LicenseTerm). The value assigned to this field is assigned when the radio buttons for license terms are constructed above. The default value of 12 (months) is the default for the radio button that is initially checked (i.e. the 12-month radio button). -->
			<td colspan="2">
			<input type="hidden" name="ExistingTerm" id="ExistingTerm" value="12">
			</td>
			</tr>
			<tr>
			<td colspan="2"><label>Margin Guarantee:&nbsp;</label>
			<span class="basictext"><?=$MarginNow.'%'; ?></span><!-- $MarginNow is obtained from obtainparameters.php -->
			</td>
			</tr>
			<tr height="40" valign="bottom">
			<td colspan="2" valign="bottom">
			<input type="checkbox" name="ResetRenewalFeeForAPR" id="ResetRenewalFeeForAPR" style="vertical-align: bottom;" value="yes" onClick="document.getElementById('LicenseFeeAPRForResetError').style.display = 'none'; document.getElementById('RenewalFeeForResetError').style.display = 'none'; if (!this.checked) { document.getElementById('LicenseFeeAPRForReset').value = ''; document.getElementById('RenewalFeeForAPR').innerHTML = '____'; document.getElementById('RenewalFeeForAPR').style.borderColor = '#FFFFFF'; }; if (this.checked) { document.getElementById('ResetRenewalFeeValue').checked = false; document.getElementById('RenewalFeeForReset').value = ''; document.getElementById('LicenseFeeAPRForRenewalFee').innerHTML = '____'; document.getElementById('LicenseFeeAPRForRenewalFee').style.borderColor = '#FFFFFF'; document.getElementById('LicenseFeeAPRForReset').disabled = false; document.getElementById('RenewalFeeForAPR').disabled = false; document.getElementById('RenewalFeeForReset').disabled = true; document.getElementById('LicenseFeeAPRForRenewalFee').disabled = true; document.getElementById('LicenseFeeAPRForReset').focus(); } else { document.getElementById('LicenseFeeAPRForReset').disabled = true; document.getElementById('RenewalFeeForAPR').disabled = true; };">
			&nbsp;<label class="small" style="vertical-align: middle;">Check to set Renewal Fee based on APR increase</label>
			</td>
			</tr>
			<tr>
			<td colspan="2">
			<input type="text" name="LicenseFeeAPRForReset" id="LicenseFeeAPRForReset" maxlength="5" size="2" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; checkLicenseFeeAPRForResetOnly();" onkeyup="document.getElementById('RenewalFeeForAPR').innerHTML = calcRenewalFeeForAPR(this.value);" onKeyDown="document.getElementById('RenewalFeeForAPR').style.borderColor = '#CCCCCC'; document.getElementById('LicenseFeeAPRForResetError').style.display = 'none';" disabled>
			&nbsp;<label>% APR produces a Renewal Fee of&nbsp;$</label>
			<span id="RenewalFeeForAPR" style="overflow: auto; height: 16px; border: 1px solid #FFFFFF; padding: 3px 2px 2px 2px;" class="basictext">____</span>
			<div class="error" id="LicenseFeeAPRForResetError"><br>Enter a percentage (%). Use only numbers [0-9] and the period (.) character.<br></div>
			</td>
			</tr>
			<tr height="40" valign="bottom">
			<td colspan="2" valign="bottom">
			<input type="checkbox" name="ResetRenewalFeeValue" id="ResetRenewalFeeValue" style="vertical-align: bottom;" value="yes" onClick="document.getElementById('LicenseFeeAPRForResetError').style.display = 'none'; document.getElementById('RenewalFeeForResetError').style.display = 'none'; if (!this.checked) { document.getElementById('RenewalFeeForReset').value = ''; document.getElementById('LicenseFeeAPRForRenewalFee').innerHTML = '____'; document.getElementById('LicenseFeeAPRForRenewalFee').style.borderColor = '#FFFFFF'; }; if (this.checked) { document.getElementById('ResetRenewalFeeForAPR').checked = false; document.getElementById('LicenseFeeAPRForReset').value = ''; document.getElementById('RenewalFeeForAPR').innerHTML = '____'; document.getElementById('RenewalFeeForAPR').style.borderColor = '#FFFFFF'; document.getElementById('RenewalFeeForReset').disabled = false; document.getElementById('LicenseFeeAPRForRenewalFee').disabled = false; document.getElementById('LicenseFeeAPRForReset').disabled = true; document.getElementById('RenewalFeeForAPR').disabled = true; document.getElementById('RenewalFeeForReset').focus(); } else { document.getElementById('RenewalFeeForReset').disabled = true; document.getElementById('LicenseFeeAPRForRenewalFee').disabled = true; };">
			&nbsp;<label class="small" style="vertical-align: middle;">Check to set Renewal Fee as a dollar value</label>
			</td>
			</tr>
			<tr>
			<td colspan="2">
			<label>$</label>&nbsp;<input type="text" name="RenewalFeeForReset" id="RenewalFeeForReset" maxlength="6" size="4" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; checkRenewalFeeForResetOnly();" onKeyUp="document.getElementById('LicenseFeeAPRForRenewalFee').innerHTML = calcAPRForRenewalFee(this.value);" onKeyDown="document.getElementById('LicenseFeeAPRForRenewalFee').style.borderColor = '#CCCCCC'; document.getElementById('RenewalFeeForResetError').style.display = 'none';" disabled>
			&nbsp;<label>Renewal Fee is equivalent to an APR increase of</label>&nbsp;
			<span id="LicenseFeeAPRForRenewalFee" style="overflow: auto; height: 16px; width: 20px; border: 1px solid #FFFFFF; padding: 3px 2px 2px 2px;" class="basictext">____</span>
			&nbsp;<label>%</label>
			<div class="error" id="RenewalFeeForResetError"><br>Enter a dollar value. Use only numbers [0-9] and the period (.) character.<br></div>
			</td>
			</tr>
			</table>
			</form>
			</div>
			<?php
			}

			if ($_SESSION['ClientDemoSelected'] == 'client') // By-pass if the user selected 'demo' rather than 'client'.
			{
			if (isset($_SESSION['IneligibleForExclusive'])) // This flag gets set in admin2.php if user is trying to check the 'Exclusive check-box for a locale that already has more than one licensee.
				{
				echo '<script type="text/javascript" language="javascript">document.getElementById(\'Exclusive\').checked = false; document.getElementById(\'Exclusive\').disabled = true;</script>';
				if ($_SESSION['IneligibleForExclusive'] == 1)
					{
					echo '<script type="text/javascript" language="javascript">alert("This locale already has at least one mediator.\nIt is not available for sale as an exclusive license."); document.getElementById(\'Exclusive\').checked = false; document.getElementById(\'Exclusive\').disabled = true;</script>';
					}
				else
					{
					echo '<script type="text/javascript" language="javascript">document.getElementById(\'Exclusive\').checked = true; document.getElementById(\'Exclusive\').disabled = false;</script>';
					};
				};
			};

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
exit;
?>	
</body>
</html>