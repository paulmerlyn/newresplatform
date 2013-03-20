<?php
/*
admin5A.php is Part 2 of admin5.php. Together, these allow an administrator (user) to manage certain attributes of a mediator's account. admin5.php retrieves and displays mediator(s) that match selection criteria supplied by the user. The user then selects/confirms which mediator he/she wants to manage by clicking a check-box. On submit of this check-box form element, control passes to admin5A.php, which provides the management user-interface for tasks sa freezing the mediator's account or performing a renewal. The back-end slave processing of admin5.php and admin5A.php is performed by admin6.php.
*/
// Start a session
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>New Resolution Mediation Launch Platform&trade; Administrator</title>
<link href="/nrcss.css" rel="stylesheet" type="text/css">
<link href="tigra_calendar/calendar.css" rel="stylesheet" type="text/css">
<!-- For European date format, use calendar_eu.js instead of calendar_us.js below. -->
<?php			
if ($_SESSION['ClientDemoSelected'] == 'client') // We don't need the calendar external JS file if the user selected 'demo' rather than 'client'. (And its inclusion in the demo case produces a JS warning error in IE on scrolling after a demo mediator logs into admin5A.php.)
	{
?>
	<script language="JavaScript" type="text/javascript" src="tigra_calendar/calendar_us.js"></script>
<?php
	}
?>
<script type="text/javascript">
/* Begin JS form validation functions. */

function checkDofRenewalOnly()
{
// Validate DofRenewal input field.
var dofRenewalValue = document.getElementById("DofRenewal").value;
var illegalCharSet = /[^0-9\/]+/; // Reject everything that contains one or more characters that is neither a slash (/) nor a digit. Note the need to escape the slash.
var reqdCharSet = /\d{2}\/\d{2}\/\d{4}/;  // Required format is MM/DD/YYYY.
if (illegalCharSet.test(dofRenewalValue)  || !reqdCharSet.test(dofRenewalValue))
	{
	document.getElementById("DofRenewalError").style.display = "inline";
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

function checkForm()
{
hideAllErrors();
if (checkDofRenewalOnly() && checkLicenseFeeOnly()) // Note: For simplicity, I don't bother to call/test the checkLicenseFeeAPROnly() and checkRenewalFeeOnly() functions here b/c they are checked on blur of their respective elements and they are only appropriately invoked when their respective check-boxes are checked. Also, remember, JS form validation is just a first-pass prior to PHP form validation.
	{
	return true; // All elements passed their validity checks, so return a true.
	}
else
	{
	return false; // At least one of the elements (DofRenewal, etc.) failed its individual check function, so return a false;
	};
} // End of checkForm()

/* This function hideAllErrors() is called by checkForm() and by onblur event within non-personal form fields. */
function hideAllErrors()
{
document.getElementById("DofRenewalError").style.display = "none";
document.getElementById("LicenseFeeError").style.display = "none";
document.getElementById("LicenseFeeAPRError").style.display = "none";
document.getElementById("RenewalFeeError").style.display = "none";
document.getElementById("LicenseFeeAPRForResetError").style.display = "none";
document.getElementById("RenewalFeeForResetError").style.display = "none";
return true;
}

/*
Function calculateNewFee() returns the fee for the term selected by the Administrator via the radio buttons in the LicenseTerm form field when the Administrator wants to renew a mediator manually. Note that I've created a PHP implementation of this methodology as PHP function calculateOfferPrice() within file offerpricecalculator.php (an SSI 'require' file). The JS implementation of calculateNewFee() is nice and fast for use in the Administrator utility suite. However, the PHP implementation is a good choice for price calculations in ipn.php and renew.php, where use of client-side Javascript isn't appropriate.
*/
function calculateNewFee()
{
	var simplelinearcost, rawdiscount;
	var existingterm, existingrenewalfee, publicrenewalfee, publicrenewalfeeid, renewalfee, newterm, newfee, publicfeeforterm;
	var i, radioObj;
	
	existingterm = document.getElementById('ExistingTerm').value;
	existingrenewalfee = document.getElementById('ExistingRenewalFee').value;
	
	// Step 1: Determine renewalfee by, if necessary, paring down the existingrenewalfee by taking the lesser of existingrenewalfee (which is the mortgage rate-style capped number sourced from the mediators_table above via a hidden form field) and the publicly offered license fee (publicrenewalfee) for the existing term (pulled from the parameters_table, also via a hidden form field). Note that if the mediator's existing term is no longer being offered to the general public for new licensees, we don't need to compare the existingrenewalfee with a public fee -- simply use the existingrenewalfee.
	publicrenewalfeeid = 'publicfeeforterm' + existingterm; // Construct the id string for the relevant hidden field (e.g. the id string will be 'publicfeeforterm12' if the existingterm is 12 months).
	if (document.getElementById(publicrenewalfeeid)) // If this element exists, the mediator's existingterm is still being offered as one of the terms available on the public price list. Obtain the public fee for that term and compare it to the existingrenewalfee, taking the smaller.
		{
		publicrenewalfee = document.getElementById(publicrenewalfeeid).value; // The publicrenewalfeeid's value will be the dollar public fee currently being asked for some new mediator to join the platform for a term equal to this particular mediator's existingterm as indicated by the associated hidden form element's id.
		renewalfee = Math.min(existingrenewalfee, publicrenewalfee);
		}
	else // The document.getElementById(publicrenewalfeeid) doesn't exist, so the mediator's existingterm is no longer being offered as one of the terms available on the public price list. Simply assign existingrenewalfee to renewalfee.
		{
		renewalfee = existingrenewalfee;
		};

	// Get the value of the selected LicenseTerm radio button and assign it to newterm
	radioObj = document.getElementsByName('LicenseTerm');
	for (i = 0; i < radioObj.length; i++)
	{
	if (radioObj[i].checked)
		{
		newterm = radioObj[i].value;
		break;
		}
	}

// If the LicenseTerm radio button selector is set at the same term T as the mediator had previously been licensed under, this is a classic renewal (i.e. the renewal fee will be the amount pulled from the hidden ExistingRenewalFee [which is in turn stored in the mediators_table of the DB as column RenewalFee], unless, of course, the administrator wishes to type in a different value into the LicenseFee form field in admin5A.php.
if (newterm == existingterm) newfee = renewalfee;	

// Otherwise, the LicenseTerm radio button selector will be set at a different term Td than the mediator had previously been licensed under. I call this not a renewal but a "newnewal". Here is the method for calculating the newnewal license fee amount. Also see Excel file "License Fee Pricing.xls" for more detail and worked examples.
else  
	{
	// Step 2: Calculate the simplelinearcost for the selected term. Calculate this by dividing the existingrenewalfee (NOT the potentially pared down renewalfee) value by the ExistingTerm value and multiplying the result by the new term.
	simplelinearcost = existingrenewalfee/existingterm * newterm;
	
	// Step 3: Calculate the rawdiscount by subtracting the existing term from the newterm (i.e. rawdiscount = Td - T). (Note: To avoid situation where rawdiscount could reach a value of 100 or greater [whereby the newnewal fee would be zero or less!], I cap the rawdiscount at 50.)
	rawdiscount = newterm - existingterm;
	rawdiscount = Math.min(rawdiscount,50);

	// Step 4: Use simplelinearcost and rawdiscount to calculate the newfee using formula newfee = (1 - rawdiscount/100) * simplelinearcost.
	newfee = (1 - rawdiscount/100) * simplelinearcost;
	
	// Step 5: Calculate an adjusted newfee by taking the lesser of either the calculated newfee in Step 4 above or the current publicly offered license fee to new mediators for Td (i.e. newterm). The current publicly offered license fee is accessed using getElementById from one of the hidden form elements.
	publicfeefortermid = 'publicfeeforterm' + newterm; // Construct the id string for the relevant hidden field (e.g. the id string will be 'publicfeeforterm18' if the newterm is 18 months).
	publicfeeforterm = document.getElementById(publicfeefortermid).value; // The publicfeefortermid's value will be the dollar public fee currently being asked for a mediator to join the platform for the term as indicated by the associated hidden form element's id.
	newfee = Math.min(newfee, publicfeeforterm);
	newfee = newfee.toFixed(2); // Round to two decimal places.
	}

	return newfee;	
};

/*
Function calcRenewalFeeForAPR is used by the ResetRenewalFee form to calculate the Renewal Fee for a given APR. It uses the  A = P(1 + r/100)^n formula where P = existing license fee (available as a hidden form element ExistingFee), r = the monthly equivalent of the APR, and n = the existing term in months (available as a hidden form element ExistingTerm).
*/
function calcRenewalFeeForAPR(apr)
{
	var apr, mthlyrate, renewalfee, existingterm, existingfee;
	
	existingterm = document.getElementById('ExistingTerm').value;
	existingfee = document.getElementById('ExistingFee').value;
	
	mthlyrate = (Math.pow(1 + apr/100, 1/12) - 1) * 100;  // Calculate the equivalent monthly % interest rate rM using rearranged formula A = P [1 + rM/100]^n where exponent is 1/12
	renewalfee = existingfee * Math.pow(1 + mthlyrate/100, existingterm); // Calculate renewalfee using A = P(1 + r/100)^n where r is mthlyrate, and n is the existing term in months.

	renewalfee = renewalfee.toFixed(2);
	return renewalfee;
}

/*
Function calcAPRForRenewalFee is used by the ResetRenewalFee form to calculate the APR that equates to the renewal fee set by the Administrator for a mediator on term T (e.g. T=3 months). It uses the  A = P(1 + r/100)^n formula in two steps. First, calculate the equivalent monthly % interest rate rM using rM = [(A/P)^(1/m) - 1] * 100 where A = the renewal fee, P = existing license fee (available as a hidden form element ExistingFee), and m = the existing term in months (available from hidden form element ExistingTerm). Second, convert from the monthly rate rM to the annual percentage rate APR (rY) using rY = [(1 + rM/100)^12 - 1] * 100. Note that a PHP version of this function has the same function name!
*/
function calcAPRForRenewalFee(renfee)
{
	var apr, mthlyrate, renfee, existingterm, existingfee;
	
	existingterm = document.getElementById('ExistingTerm').value;
	existingfee = document.getElementById('ExistingFee').value;
	
	// First calculate the equivalent monthly % interest rate using rearranged formula A = P [1 + r/100]^n
	if (existingfee != 0) 
		{ 
		mthlyrate = (Math.pow(renfee/existingfee, 1/existingterm) - 1) * 100;  
		}
	else // This else clause avoid a JavaScript NaN error.
		{
		mthlyrate = 0;
		};
	apr = (Math.pow(1 + mthlyrate/100, 12) - 1) * 100; // Second calculate the APR by converting the monthly rate. Create a conversion equation by equating the amount A after 12 months and 1 year as follows: A = P(1 + rM/100)^12 = P(1 + rY/100)^1 such that rM is the mthlyrate, rY is the annual APR rate. Rearranging the equality provides rY = [(1 + rM/100)^12 - 1] * 100

	apr = apr.toFixed(2);
	return apr;
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
require('../ssi/obtainparameters.php'); // Include the obtainparameters.php file, which retrieves values $DefaultAPR and $FeeTermArray array from the parameters_table table.
// Create short variable names
$selectedID = $_POST['selectedID'];
if (!isset($_SESSION['selectedID'])) $_SESSION['selectedID'] = $selectedID; // Save the selected ID in a session variable for reuse by admin6.php when recording a note ... but don't overwrite the session variable if the $_POST array item is null or empty, which it may be when control passes from admin6.php back to admin5A.php after the user clicked the Exclusive check-box. In that case, leave the session variable unchanged.

// Define a PHP version of the JS function calcAPRForRenewalFee(), which calculates the APR that equates to the renewal fee set by the Administrator for a mediator on term T (e.g. T=3 months). Note that a JS version of this function has the same function name!
function calcAPRForRenewalFee($renfee, $licfee, $licterm)
{
	// First calculate the equivalent monthly % interest rate using rearranged formula A = P [1 + r/100]^n
	if ($licfee != 0) 
		{
		$mthlyrate = (pow($renfee/$licfee, 1/$licterm) - 1) * 100; 
		}
	else // This else clause avoids a PHP 'Division by zero' warning.
		{
		$mthlyrate = 0;
		};
	$apr = (pow(1 + $mthlyrate/100, 12) - 1) * 100; // Second calculate the APR by converting the monthly rate. Create a conversion equation by equating the amount A after 12 months and 1 year as follows: A = P(1 + rM/100)^12 = P(1 + rY/100)^1 such that rM is the mthlyrate, rY is the annual APR rate. Rearranging the equality provides rY = [(1 + rM/100)^12 - 1] * 100

	return $apr;
}

if  (empty($Authentication) && $_SESSION['Authenticated'] != 'true')
	{
	// Visitor needs to authenticate
	?>
	<div style="position: absolute; left: 240px; top: 100px; width: 250px; padding: 15px; border: 2px solid #444444; border-color: #425A8D;">
	<h3>Please authenticate yourself:</h3>
	<br>
	<form method="post" action="admin5.php">
	<table border="0" width="280">
	<tr>
	<td align="center"><input type="password" name="Authentication" maxlength="40" size="20"></td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
	<td align="center"><input type="submit" value="Authenticate"></td>
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
			<div style="position: absolute; left: 240px; top: 100px; width: 250px; text-align:center; padding: 15px; border: 1px solid #444444;">
			<h3>I want to access the following database:</h3>
			<form method="post" action="admin5.php">
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
		else // A client vs demo selection had previously been made. Set the DB table name, formulate queries, and show the main screen.
			{
			switch ($_SESSION['ClientDemoSelected'])
				{
				case 'client' :
					$dbmediatorstablename = 'mediators_table';
					// Formulate query to retrieve all information from (client) mediators table for the mediator ID selected in admin5.php.
					$query = "select *, DATE_FORMAT(DofAdmission, '%M %e, %Y') as DofAdmission, DATE_FORMAT(ExpirationDate, '%M %e, %Y') as ExpirationDate, DATE_FORMAT(DofRenewal, '%M %e, %Y') as DofRenewal FROM ".$dbmediatorstablename." WHERE ID = '".$_SESSION['selectedID']."'";
					// Formulate one more query, this time to grab the unreformatted (i.e. native mysql format) of the ExpirationDate colum for this mediator. Store it in a session variable. I later use the session variable in admin6.php when processing a mediator renewal.
					$queryExp =  "SELECT ExpirationDate FROM ".$dbmediatorstablename." WHERE ID = '".$_SESSION['selectedID']."'";
					break;
				case 'demo' :
					$dbmediatorstablename = 'mediators_table_demo';
					// Formulate query to retrieve all information from (demo) mediators table for the mediator ID selected in admin5.php.
					$query = "select * FROM ".$dbmediatorstablename." WHERE ID = '".$_SESSION['selectedID']."'";
					break;
				default :
					echo 'Error: Unable to determine the name of an appropriate database table for data insertion.<br>';
					exit;
				}
			?>
			<input type="button" value="Hide/Show Data" class="buttonstylesimple" style="position: absolute; left: 635px; <?php include_once('/home/paulme6/public_html/nrmedlic/scripts/browser_detection.php'); if ( browser_detection('browser') == 'saf' ) { echo 'top: 110px;'; } else { echo 'top: 120px;'; }; ?> width: 100px;" onClick="if (document.getElementById('MedData').style.display == 'block') document.getElementById('MedData').style.display = 'none'; else document.getElementById('MedData').style.display = 'block';">
			<div id="MedData" style="position: relative; left: 125px; top: 51px; width: 530px; padding: 15px; border: 2px solid #444444; border-color: #425A8D; display: block;">
			<h1 style="margin-left: 0px;">Mediator account data in the <?php if ($_SESSION['ClientDemoSelected'] == 'client') echo 'client'; else echo 'demo'; ?> database table:</h1>
			<br>
			<?php
			$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
			or die('Could not connect: ' . mysql_error());
			mysql_select_db('paulme6_newresolution') or die('Could not select database: ' . mysql_error());

			$result = mysql_query($query) or die('The SELECT query of mediator information i.e. '.$query.' failed: ' . mysql_error());
			$row = mysql_fetch_assoc($result); // $row associative array contains details of the selected mediator from mediators_table or mediators_table_demo.

			if ($_SESSION['ClientDemoSelected'] == 'client') // Only run this extra query for client situation. (Mediators in the demo table don't have an ExpirationDate.)
				{
				$result = mysql_query($queryExp);
				$line = mysql_fetch_row($result); // $line array should have just one item i.e. the ExpirationDate.
				$_SESSION['ExpirationDate'] = $line[0];
				}
			?>
			
			<table width="530" cellpadding="5">
			<tr>
			<td width="150" valign="top"><label>ID</label></td>
			<td valign="top" class="basictext"><?php echo $row['ID']; ?></td>
			<td rowspan="6" valign="top" class="basictext"><?php if ($row['ImageFileName'] != null) if ($_SESSION['ClientDemoSelected'] == 'client') echo '<img style="float: right" src="../images/Mediator'.$row['ID'].'.jpg">'; else echo '<img style="float: right" src="../demo/images/Mediator'.$row['ID'].'.jpg">';?></td>
			</tr>
			<tr>
			<td><label>Username</label></td>
			<td class="basictext"><?=$row['Username'];?></td>
			</tr>
			<tr>
			<td><label>Name</label></td>
			<td class="basictext"><?=$row['Name'];?></td>
			</tr>
			<tr>
			<td><label>Locale</label></td>
			<td class="basictext"><?php echo $row['Locale']; $_SESSION['LocaleSelected'] = $row['Locale']; ?></td>
			</tr>
			<tr>
			<td><label>Admin Freeze</label></td>
			<td valign="top" class="basictext"><?php if ($row['AdminFreeze'] == 0) echo 'account not frozen'; else echo 'account frozen'; ?></td>
			</tr>
			<tr>
			<td><label>Suspend</label></td>
			<td valign="top" class="basictext"><?php if ($row['Suspend'] == 0) echo 'profile is live'; else echo 'profile is <i>not</i> live'; ?></td>
			</tr>
			<?php if ($_SESSION['ClientDemoSelected'] == 'client') // Omit for mediators in demo table.
			{
			?>
			<tr>
			<td><label>Date of Admission</label></td>
			<td valign="top" class="basictext"><?=$row['DofAdmission'];?></td>
			</tr>
			<tr>
			<td><label>Current Fee & Term</label></td>
			<td valign="top" class="basictext"><?php echo '$'.$row['LicenseFee'].' ('; 
			switch ($row['LicenseTerm'])
				{
				case 1:
					echo '1 month)';
					break;
				case 3:
					echo 'quarter)';
					break;
				case ((int)$row['LicenseTerm'] < 12):
					echo $row['LicenseTerm'].' months)';
					break;
				case 12:
					echo '1 year)';
					break;
				case 24:
					echo '2 years)';
					break;
				default:
					echo $row['LicenseTerm'].' months)';
					break;
				};
			 ?>
			</td>
			</tr>
			<tr>
			<td><label>Expiration Date</label></td>
			<!-- Note: I store the expiration date in a session variable for convient use by admin6.php when processing a renewal. -->
			<td valign="top" class="basictext"><?=$row['ExpirationDate']; ?></td>
			</tr>
			<tr>
			<td><label>Date of Last Renewal</label></td>
			<td valign="top" class="basictext"><?=$row['DofRenewal'];?></td>
			</tr>
			<tr>
			<td><label>Renewal Fee</label></td>
			<td valign="top" class="basictext"><?php echo '$'.$row['RenewalFee']; ?>
			</td>
			</tr>
			<tr>
			<td><label>Margin Guarantee</label></td>
			<td valign="top" class="basictext"><?php $_SESSION['Margin'] = $row['Margin']; echo $row['Margin'].'%'; ?>
			</td>
			</tr>
			<?php
			}
			?>
			<tr>
			<td><label>Credentials</label></td>
			<td class="basictext"><?=$row['Credentials']; ?></td>
			</tr>
			<tr>
			<td><label>Exclusive</label></td>
			<td class="basictext"><?php if ($row['Exclusive'] == 0) echo 'no'; else echo 'yes'; ?></td>
			</tr>
			<tr>
			<td><label>Locale Label</label></td>
			<td class="basictext"><?=$row['LocaleLabel']; ?></td>
			</tr>
			<tr>
			<td valign="top"><label>Locations</label></td>
			<td colspan="2" valign="top" class="basictext"><?php $locs = str_replace('"','',$row['Locations']); $locs = str_replace(',',', ',$locs); echo $locs; ?></td>
			</tr>
			<tr>
			<td valign="top"><label>Email</label></td>
			<td colspan="2" valign="top" class="basictext"><?=$row['Email']; ?></td>
			</tr>
			<tr>
			<td valign="top"><label>Use Professional Email</label></td>
			<td valign="top" class="basictext"><?php if ($row['UseProfessionalEmail'] == 0) echo 'no'; else echo 'yes'; ?></td>
			</tr>
			<tr>
			<td valign="top"><label>Professional Email</label></td>
			<td colspan="2" valign="top" class="basictext"><?=$row['ProfessionalEmail']; ?></td>
			</tr>
			<tr>
			<td valign="top"><label>Prof Email Password</label></td>
			<td colspan="2" valign="top" class="basictext"><?=$row['ProfEmailPassword']; ?></td>
			</tr>
			<tr>
			<td valign="top"><label>Entity Name</label></td>
			<td colspan="2" valign="top" class="basictext"><?=$row['EntityName']; ?></td>
			</tr>
			<tr>
			<td valign="top"><label>Principal Street</label></td>
			<td colspan="2" valign="top" class="basictext"><?=$row['PrincipalStreet']; ?></td>
			</tr>
			<tr>
			<td valign="top"><label>Principal Address (Other)</label></td>
			<td colspan="2" valign="top" class="basictext"<?=$row['PrincipalAddressOther']; ?>></td>
			</tr>
			<tr>
			<td valign="top"><label>City</label></td>
			<td valign="top" class="basictext"><?=$row['City']; ?></td>
			</tr>
			<tr>
			<td><label>State</label></td>
			<td class="basictext"><?=$row['State']; ?></td>
			</tr>
			<tr>
			<td><label>Zip</label></td>
			<td class="basictext"><?=$row['Zip']; ?></td>
			</tr>
			<tr>
			<td><label>Telephone</label></td>
			<td class="basictext"><?=$row['Telephone']; ?></td>
			</tr>
			<tr>
			<td><label>Fax</label></td>
			<td class="basictext"><?=$row['Fax']; ?></td>
			</tr>
			<tr>
			<td valign="top"><label>Profile</label></td>
			<td colspan="2" class="basictext"><?=$row['Profile']; ?></td>
			</tr>
			<tr>
			<td valign="top"><label>Hourly Rate</label></td>
			<td valign="top" class="basictext"><?php if ($row['HourlyRate'] != null) { $hrate = strstr($row['HourlyRate'], 'true,'); if ($hrate != false) { $hrate = str_replace('true,','',$hrate); echo '$'.$hrate. ' per hour'; } else echo 'not published'; }  ?></td>
			</tr>
			<tr>
			<td><label>Admin Charge</label></td>
			<td colspan="2" class="basictext">
			<?php
			switch ($row['AdminCharge'])
			{
			case null:
				break;
			case 0:
				echo 'no case admin charge';
				break;
			case 1:
				switch ($row['AdminChargeDetails'])
				{
				case (strstr($row['AdminChargeDetails'], 'true,')):
					echo 'published as $'.str_replace('true,','',$row['AdminChargeDetails']).' per hour';
					break;
				default:
					echo 'charge levied but amount not published';
					break;
				}
			}
			?>
			</td>
			</tr>
			<tr>
			<td valign="top"><label>Location Charge</label></td>
			<td valign="top" class="basictext">
			<?php
			$loc = explode(',',$row['LocationCharge']);
			if ($loc[0] == 'false') echo 'no location charge';
			else if ($loc[1] == $loc[2]) echo '$'.$loc[1];
			else echo '$'.$loc[1].' to $'.$loc[2];
			?>
			</td>
			</tr>
			<tr>
			<td valign="top"><label>Packages</label></td>
			<td valign="top" class="basictext">
			<?php
			$loc = explode(',',$row['Packages']);
			if ($loc[0] == 'false') echo 'no packages';
			else
				{
				if ($loc[1] != '""') echo $loc[1].'-session package for $'.$loc[2];
				if ($loc[3] != '""') echo '<br>'.$loc[3].'-session package for $'.$loc[4];
				if ($loc[5] != '""') echo '<br>'.$loc[5].'-session package for $'.$loc[6];
				echo '<br>(each session is '.$loc[7];
				if ((int)$loc[7] < 2) echo ' hour'; else echo ' hours';
				if ($loc[8] != '""') echo ' '.$loc[8].' mins)'; else echo ')';
				};
			?>
			</td>
			</tr>
			<tr>
			<td valign="top"><label>Sliding Scale</label></td>
			<td valign="top" class="basictext"><?php if ($row['SlidingScale'] == 0) echo 'no'; else echo 'yes'; ?></td>
			</tr>
			<tr>
			<td valign="top"><label>Fee Increment</label></td>
			<td valign="top" class="basictext"><?php if (is_numeric($row['Increment'])) echo $row['Increment'].' minutes'; ?></td>
			</tr>
			<tr>
			<td valign="top"><label>Consultation Policy</label></td>
			<td colspan="2" valign="top" class="basictext">
			<?php
			$consultpol = explode(',',$row['ConsultationPolicy']);
			if ($consultpol[0] == 'false') echo 'telephone consultations are not free'; else echo 'free telephone consultations';
			if ($consultpol[1] == 'false') echo '<br>in-person consultations are not free'; else echo '<br>free in-person consultations are available';
			if ($consultpol[2] == 'false') echo '<br>no location charge for in-person consultations'; else echo '<br>location charge may apply for in-person consultations';
			if ($consultpol[3] == 'false') echo '<br>no credit offered for paid consultations'; else echo '<br>'.$consultpol[4].'% credit for paid consultations';
			?>
			</td>
			</tr>
			<tr>
			<td valign="top"><label>Cancellation Policy</label></td>
			<td colspan="2" valign="top" class="basictext">
			<?php
			$cancpol = str_replace('"','',$row['CancellationPolicy']); // Strip out the double quotes (") from the string.
			$cancpol = explode(',',$cancpol); // Create an array
			if ($cancpol[0] != 'null') echo '$'.$cancpol[2].' fee for cancellation inside '.$cancpol[0].' '.$cancpol[1];
			?>
			</td>
			</tr>
			<tr>
			<td valign="top"><label>Telephone Mediations</label></td>
			<td valign="top" class="basictext"><?php if ($row['TelephoneMediations'] == 0) echo 'no'; else echo 'yes'; ?></td>
			</tr>
			<tr>
			<td valign="top"><label>Video Conferencing</label></td>
			<td valign="top" class="basictext"><?php if ($row['VideoConf'] == 0) echo 'no'; else echo 'yes'; ?></td>
			</tr>
			<tr>
			<td valign="top"><label>Credit Card Policy</label></td>
			<td colspan="2" valign="top" class="basictext">
			<?php
			$cardpol = str_replace('"','',$row['CardPolicy']); // Strip out the double quotes (") from the string.
			$cardpol = explode(',',$cardpol); // Create an array
			if ($cardpol[0] == 'false') echo 'not accepted'; 
			else 
				{
				echo 'credit cards accepted';
				if ($cardpol[1] == 'true') echo '<br>(convenience charge of '.$cardpol[2].'%)'; else echo '<br>(no convenience charge)';
				};
			?>
			</td>
			</tr>
			<tr>
			<td valign="top"><label>Service Level</label></td>
			<td valign="top" class="basictext"><?php if ($row['ServiceLevel'] != null) if ($row['ServiceLevel'] == 0) echo 'self-help and referrals'; else echo 'full service (preparation/filing of court forms)'; ?></td>
			</tr>
			<tr>
			<td valign="top"><label>Street (Personal)</label></td>
			<td colspan="2" valign="top" class="basictext"><?php if ($row['StreetPersonal'] != null) echo $row['StreetPersonal']; ?></td>
			</tr>
			<tr>
			<td valign="top"><label>City (Personal)</label></td>
			<td valign="top" class="basictext"><?php if ($row['CityPersonal'] != null) echo $row['CityPersonal']; ?></td>
			</tr>
			<tr>
			<td valign="top"><label>State (Personal)</label></td>
			<td valign="top" class="basictext"><?php if ($row['StatePersonal'] != null) echo $row['StatePersonal']; ?></td>
			</tr>
			<tr>
			<td valign="top"><label>Zip (Personal)</label></td>
			<td valign="top" class="basictext"><?php if ($row['ZipPersonal'] != null) echo $row['ZipPersonal']; ?></td>
			</tr>
			<tr>
			<td valign="top"><label>Telephone (Personal)</label></td>
			<td valign="top" class="basictext"><?php if ($row['TelephonePersonal'] != null) echo $row['TelephonePersonal']; ?></td>
			</tr>
			<tr>
			<td valign="top"><label>Email (Personal)</label></td>
			<td valign="top" class="basictext"><?php if ($row['EmailPersonal'] != null) echo $row['EmailPersonal']; ?></td>
			</tr>
			</table>

			</div>

			<div style="position: relative; left: 125px; top: 100px; width: 530px; padding: 15px 15px 10px 15px; border: 2px solid #444444; border-color: #425A8D; display: block;">
			<h1 style="margin-left: 0px;">Notes archive for mediator ID <?php echo $row['ID']; if ($row['Name'] != null && $row['Name'] != '') echo ' ('.$row['Name'].')'; ?>:</h1>
			<div style="overflow: auto; height: 180px; border: 1px solid #CCCCCC; padding: 10px 10px 10px 10px;" class="basictext">
			<?php $archivednotes = $row['Notes']; $archivednotes = htmlspecialchars_decode($archivednotes); echo $archivednotes; ?> <!-- Note that htmlspecialchars_decode has the (desirable) side-effect of removing extra spaces and line breaks before presenting the archived notes that are retrieved from the database table's 'Notes' column. -->
			</div>
			</div>

			<div id="NoteEntry" style="position: relative; left: 125px; top: 150px; width: 530px; padding: 15px 15px 0px 15px; border: 2px solid #444444; border-color: #425A8D; display: block;">
			<h1 style="margin-left: 0px;">Record a note for mediator ID <?php echo $row['ID']; if ($row['Name'] != null && $row['Name'] != '') echo ' ('.$row['Name'].')'; ?>:</h1>
			<form method="post" action="admin6.php">
			<table width="530" cellpadding="5">
			<tr>
			<td valign="top" class="basictext">
			<textarea name="NoteText" id="NoteText" rows="10" cols="65" wrap="soft" style="overflow:auto; height: 180px; width: 510px;" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white';"></textarea>
			</td>
			</tr>
			<tr>
			<td align="center">
			<input type="submit" name="RecordNote" value="Record" class="buttonstyle">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" value="Clear" class="buttonstyle" onClick="document.getElementById('NoteText').focus();">
			</td>
			</tr>
			</table>
			</form>
			</div>

			<?php			
			if ($_SESSION['ClientDemoSelected'] == 'client') // By-pass if the user selected 'demo' rather than 'client'.
			{
			?>
			<div style="position: absolute; left: 725px; top: 163px; width: 530px; padding: 15px; border: 2px solid #444444; border-color: #425A8D; display: block;">
			<h1 style="margin-left: 0px;">Renew mediator and set next renewal fee:</h1>
			<br>
			<form name="ManageMediator" method="post" action="admin6.php">
			<table width="530" cellpadding="5">
			<tr>
			<td colspan="2">
			<input type="hidden" name="ExistingTerm" id="ExistingTerm" value="<?=$row['LicenseTerm']; ?>"><input type="hidden" name="ExistingRenewalFee" id="ExistingRenewalFee" value="<?=$row['RenewalFee']; ?>">
			<?php
			$FeeIndexPointer = 0; // Keeps track of the index for successive fee elements in the $FeeTermArray fee-term value-pair array. (Note: it increments in steps of 2 to point to index 0, 2, 4, 6, etc.
			$TermIndexPointer = 1; // Keeps track of the index for successive term elements in the $FeeTermArray fee-term value-pair array. (Note: it increments in steps of 2 to point to index 1, 3, 5, 7, etc.
			for ($i=1; $i <= $NofFeeTermPairs; $i++) // Use a loop to generate a (hidden) field for each fee term-fee value pair. These hidden fields are referenced by Javascript function calculateNewFee(). They provide a technique the bridges between the separate worlds of Javascript and PHP. Note that $NofFeeTermPairs is obtained from the require'd file obtainparameters.php.
				{
			?>
				<input type="hidden" name="<?='publicfeeforterm'.$FeeTermArray[$TermIndexPointer]; ?>" id="<?='publicfeeforterm'.$FeeTermArray[$TermIndexPointer]; ?>" value="<?=$FeeTermArray[$FeeIndexPointer]; ?>">
			<?php
				$FeeIndexPointer = $FeeIndexPointer + 2;
				$TermIndexPointer = $TermIndexPointer + 2;
				}
			?>
			</td>
			</tr>
			<tr>
			<td width="120"><label>Date of Renewal</label></td>
			<td>
              <input type="text" name="DofRenewal" id="DofRenewal" maxlength="10" size="10" value="<?=date('m/d/Y');?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkDofRenewalOnly();">			
              <script language="JavaScript">
			new tcal ({
			'formname': 'ManageMediator',
			'controlname': 'DofRenewal'
			});
			</script>
			<div class="error" id="DofRenewalError"><br>Date must have format MM/DD/YYYY. Use only numbers and slash (/) character.<br></div>
			<?php if ($_SESSION['MsgDofRenewal'] != null) { echo $_SESSION['MsgDofRenewal']; $_SESSION['MsgDofRenewal']=null; } ?>
			</td>
			</tr>
			<tr>
			<td><label>New Term</label></td>
			<td>

			<?php
			/* Now go through $FeeTermArray's term elements to identify which term lengths are being offered (as configured by the Administrator using admin7.php) and thus create a set of radio buttons with appropriate labels and values. For example, if we find that $FeeTermArray contains a term length element of '3' then we need a radio button with a label of 'Quarter' because the Administrator has defined one quarter as one of the term lengths being offered to mediators. Note that $FeeTermArray and $NofFeeTermPairs are obtained via the require(obtainparameters.php) statement. */
			$TermIndexPointer = 1; // Keeps track of the index for successive term elements in the $FeeTermArray fee-term value-pair array. (Note: it increments in steps of 2 to point to index 1, 3, 5, 7, etc.
			for ($i=1; $i <= $NofFeeTermPairs; $i++) // When $i is 1, we generate the first row of the table; when $i is 2, we generate the second row, and so on until we've created one row for each fee-term value-pair in the $FeeTermArray (which is itself retrieved from the DB's parameters_table).
				{
				switch($FeeTermArray[$TermIndexPointer])
					{
					case '1':
						echo '<input type="radio" name="LicenseTerm" id="LicenseTerm" value="1" onchange="document.getElementById(\'LicenseFee\').value = calculateNewFee(this.value);" onclick="this.blur();"';
						// Note the inclusion of the onclick="this.blur(); event handler. This is a neat workaround (courtesy of http://bytes.com/topic/javascript/answers/538606-internet-explorer-onchange-issue) for fact that IE's event handler would require a second click before firing i.e. without inclusion of onclick="this.blur()", the Renewal Fee field would only update inside admin5A.php after the user had made a second click (anywhere on the screen) after clicking a license term radio button -- it wouldn't fire on the initial click. IE's implementation is silly (Firefox is more logical) but the workaround solves the issue. I've fixed the same issue in admin1.php also.
						if ($row['LicenseTerm'] == 1) echo ' checked';
						echo '><label for="LicenseTerm">Month&nbsp;&nbsp;&nbsp;</label>';
						break;
					case '2':
						echo '<input type="radio" name="LicenseTerm" id="LicenseTerm" value="2" onchange="document.getElementById(\'LicenseFee\').value = calculateNewFee(this.value);" onclick="this.blur();"';
						if ($row['LicenseTerm'] == 2) echo ' checked';
						echo '><label for="LicenseTerm">2 Months&nbsp;&nbsp;&nbsp;</label>';
						break;
					case '3':
						echo '<input type="radio" name="LicenseTerm" id="LicenseTerm" value="3" onchange="document.getElementById(\'LicenseFee\').value = calculateNewFee(this.value);" onclick="this.blur();"';
						if ($row['LicenseTerm'] == 3) echo ' checked';
						echo '><label for="LicenseTerm">Quarter&nbsp;&nbsp;&nbsp;</label>';
						break;
					case '6':
						echo '<input type="radio" name="LicenseTerm" id="LicenseTerm" value="6" onchange="document.getElementById(\'LicenseFee\').value = calculateNewFee(this.value);" onclick="this.blur();"';
						if ($row['LicenseTerm'] == 6) echo ' checked';
						echo '><label for="LicenseTerm">6 Months&nbsp;&nbsp;&nbsp;</label>';
						break;
					case '12':
						echo '<input type="radio" name="LicenseTerm" id="LicenseTerm" value="12" onchange="document.getElementById(\'LicenseFee\').value = calculateNewFee(this.value);" onclick="this.blur();"';
						if ($row['LicenseTerm'] == 12) echo ' checked';
						echo '><label for="LicenseTerm">Year&nbsp;&nbsp;&nbsp;</label>';
						break;
					case '18':
						echo '<input type="radio" name="LicenseTerm" id="LicenseTerm" value="18" onchange="document.getElementById(\'LicenseFee\').value = calculateNewFee(this.value);" onclick="this.blur();"';
						if ($row['LicenseTerm'] == 18) echo ' checked';
						echo '><label for="LicenseTerm">18 Months&nbsp;&nbsp;&nbsp;</label>';
						break;
					case '24':
						echo '<input type="radio" name="LicenseTerm" id="LicenseTerm" value="24" onchange="document.getElementById(\'LicenseFee\').value = calculateNewFee(this.value);" onclick="this.blur();"';
						if ($row['LicenseTerm'] == 24) echo ' checked';
						echo '><label for="LicenseTerm">24 Months&nbsp;&nbsp;&nbsp;</label>';
						break;
					case '36':
						echo '<input type="radio" name="LicenseTerm" id="LicenseTerm" value="36" onchange="document.getElementById(\'LicenseFee\').value = calculateNewFee(this.value);" onclick="this.blur();"';
						if ($row['LicenseTerm'] == 36) echo ' checked';
						echo '><label for="LicenseTerm">3 Years&nbsp;&nbsp;&nbsp;</label>';
						break;
					case '48':
						echo '<input type="radio" name="LicenseTerm" id="LicenseTerm" value="48" onchange="document.getElementById(\'LicenseFee\').value = calculateNewFee(this.value);" onclick="this.blur();"';
						if ($row['LicenseTerm'] == 48) echo ' checked';
						echo '><label for="LicenseTerm">4 Years&nbsp;&nbsp;&nbsp;</label>';
						break;
					case '60':
						echo '<input type="radio" name="LicenseTerm" id="LicenseTerm" value="60" onchange="document.getElementById(\'LicenseFee\').value = calculateNewFee(this.value);" onclick="this.blur();"';
						if ($row['LicenseTerm'] == 60) echo ' checked';
						echo '><label for="LicenseTerm">5 Years&nbsp;&nbsp;&nbsp;</label>';
						break;
					case '120':
						echo '<input type="radio" name="LicenseTerm" id="LicenseTerm" value="120" onchange="document.getElementById(\'LicenseFee\').value = calculateNewFee(this.value);" onclick="this.blur();"';
						if ($row['LicenseTerm'] == 120) echo ' checked';
						echo '><label for="LicenseTerm">10 Years&nbsp;&nbsp;&nbsp;</label>';
						break;
					default:
						echo '<input type="radio" name="LicenseTerm" id="LicenseTerm" value="'.$FeeTermArray[$TermIndexPointer].'" onchange="document.getElementById(\'LicenseFee\').value = calculateNewFee(this.value);" onclick="this.blur();"><label for="LicenseTerm">';
						echo $FeeTermArray[$TermIndexPointer];
						echo ' Months&nbsp;&nbsp;&nbsp;</label>';
						break;
					}
				if ($i%5 == 0) echo '<br/>';  // Create a break after every five term radio buttons (by testing whether $i modulus 5 is zero) to avoid an ugly split over two lines.
				$TermIndexPointer = $TermIndexPointer + 2;
				}

			/* If the mediator's LicenseTerm no longer exists among the terms currently being offered to the public as stored in $FeeTermArray, we need to create one additional radio button that represents the mediator's LicenseTerm. Without such a button, the mediator won't be able to renew (because no selection will be available to continue with the same term) and my code will crash. I want mediators to be able to renew with term T, even if I no longer offer term T to new mediators. */
			// Loop through $FeeTermArray’s Term elements to see whether any matches LicenseTerm.
			$existingtermfound = false; // Initialize this flag to false.
			$TermIndexPointer = 1; // Keeps track of the index for successive term elements in the $FeeTermArray fee-term value-pair array. (Note: it increments in steps of 2 to point to index 1, 3, 5, 7, etc.
			for ($i=1; $i <= $NofFeeTermPairs; $i++)
			{
				if ($FeeTermArray[$TermIndexPointer] == $row['LicenseTerm']) $existingtermfound = true; // Set flag to true if a match is found
				$TermIndexPointer = $TermIndexPointer + 2;
			}
			
			// If no match is found, echo another radio button for the mediator's existing LicenseTerm
			if (!$existingtermfound)
			{
				echo '<input type="radio" name="LicenseTerm" id="LicenseTerm" value="'.$row['LicenseTerm'].'" onchange="document.getElementById(\'LicenseFee\').value = calculateNewFee(this.value);" onclick="this.blur();" checked><label for="LicenseTerm">';
				echo $row['LicenseTerm'];
				echo ' Months&nbsp;&nbsp;&nbsp;</label>';
			}
			?>
			</td>
			</tr>
			<tr>
			<td><label>Exclusive Purchase?</label></td>
			<td>
			<input type="checkbox" name="Exclusive" id="Exclusive" value="1" style="position: relative; right: 4px;" onClick="if (this.checked) this.form.submit();" <?php if ($row['Exclusive'] == 1) echo 'checked';?>>
			</td>
			</tr>
			<tr>
			<td><label>Renewal Fee $</label></td>
			<td><input type="text" name="LicenseFee" id="LicenseFee"  value="<?=$row['RenewalFee'];?>" maxlength="8" size="8" onFocus="this.style.background='#FFFF99'" onBlur="this.value = this.value.replace(/,/g, ''); this.style.background='white'; hideAllErrors(); return checkLicenseFeeOnly();"><!-- The following bit of javascript ensures that the renewal fee that is shown by default in the LicenseFee field is calculated properly (i.e. it is the lesser of RenewalFee from the mediators_table and the public renewal amount for the existing term [stored in parameters_table]). If, say, RenewalFee were 2490 and the public amount was only 2200, then the renewal fee field should show 2200. --><script type="text/javascript">document.getElementById('LicenseFee').value = calculateNewFee(document.getElementById('LicenseTerm').value);</script><div class="error" id="LicenseFeeError"><br>Enter a dollar value. Use only numbers [0-9] and the period (.) character.<br></div><?php if ($_SESSION['MsgLicenseFee'] != null) { echo $_SESSION['MsgLicenseFee']; $_SESSION['MsgLicenseFee']=null; } ?></td>
			</tr>
			<tr>
			<td><label>APR (Fee Increase)</label></td>
			<td>
			<!-- Note that the default APR fee increase $DefaultAPR is accessible via the require(ssi/obtainparameters.php) statement. -->
			<input type="text" name="LicenseFeeAPR" id="LicenseFeeAPR" maxlength="6" size="5" value="<?=$DefaultAPR; ?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; if (document.getElementById('OverrideDefaultAPR').checked) { hideAllErrors(); return checkLicenseFeeAPROnly(); };" disabled>&nbsp;<label>%</label>&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="OverrideDefaultAPR" id="OverrideDefaultAPR" value="yes" onClick="if (this.checked) { document.getElementById('LicenseFeeAPR').disabled = false; document.getElementById('LicenseFeeAPR').focus(); document.getElementById('OverrideAPR').disabled = true; document.getElementById('RenewalFee').disabled = true; } else { document.getElementById('LicenseFeeAPR').disabled = true; document.getElementById('OverrideAPR').disabled = false; };">&nbsp;<label class="small">[check box to override default APR]</label>
			<div class="error" id="LicenseFeeAPRError"><br>Enter a percentage (%). Use only numbers [0-9] and the period (.) character.<br></div>
			<?php if ($_SESSION['MsgLicenseFeeAPR'] != null) { echo $_SESSION['MsgLicenseFeeAPR']; $_SESSION['MsgLicenseFeeAPR']=null; } ?>
			</td>
			</tr>
			<tr>
			<td><label>Next Renewal Fee $</label></td>
			<td><input type="text" name="RenewalFee" id="RenewalFee" maxlength="8" size="8" onFocus="this.style.background='#FFFF99'" onBlur="this.value = this.value.replace(/,/g, ''); this.style.background='white'; if (document.getElementById('OverrideAPR').checked) { hideAllErrors(); return checkRenewalFeeOnly(); };" disabled>&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="OverrideAPR" id="OverrideAPR" value="yes" onClick="if (this.checked) { document.getElementById('RenewalFee').disabled = false; document.getElementById('RenewalFee').focus(); document.getElementById('LicenseFeeAPR').disabled = true; document.getElementById('OverrideDefaultAPR').disabled = true; } else { document.getElementById('RenewalFee').disabled = true; document.getElementById('OverrideDefaultAPR').disabled = false; };">&nbsp;<label class="small">[check box to override APR-based calculation]</label>
			<div class="error" id="RenewalFeeError"><br>Enter a dollar value. Use only numbers [0-9] and the period (.) character.<br></div>
			<?php if ($_SESSION['MsgRenewalFee'] != null) { echo $_SESSION['MsgRenewalFee']; $_SESSION['MsgRenewalFee']=null; } ?>
			</td>
			</tr>
			<tr height="40px" valign="middle">
			<td colspan="2" align="center">
			<input type="submit" name="RenewMediator" value="Renew" onClick="return checkForm();" <?php if ($row['AdminFreeze']) { echo 'disabled'; } else { echo 'class="buttonstyle"'; }; ?>> <!-- Disable an attempt to renew a mediator whose account is frozen. Only show button in its stylized class form when not disabled. -->
			</td>
			</tr>
			</table>
			</form>
			</div>
			<?php
			if (isset($_SESSION['IneligibleForExclusive'])) // This flag gets set in admin2.php if user is trying to check the 'Exclusive check-box for a locale that already has more than one licensee.
				{
				echo '<script type="text/javascript" language="javascript">document.getElementById(\'Exclusive\').checked = false; document.getElementById(\'Exclusive\').disabled = true;</script>';
				if ($_SESSION['IneligibleForExclusive'] == 1)
					{
					echo '<script type="text/javascript" language="javascript">alert("Another mediator has already purchased a license for this locale.\nThe locale is therefore no longer available for exclusive licensing."); document.getElementById(\'Exclusive\').checked = false; document.getElementById(\'Exclusive\').disabled = true;</script>';
					}
				else
					{
					echo '<script type="text/javascript" language="javascript">document.getElementById(\'Exclusive\').checked = true; document.getElementById(\'Exclusive\').disabled = false;</script>';
					};
				unset($_SESSION['IneligibleForExclusive']);	
				};
			?>

			<div style="position: absolute; left: 725px; top: 514px; width: 530px; padding: 15px; border: 2px solid #444444; border-color: #425A8D; display: block;">
			<a name="resetrenewalfee">
			<h1 style="margin-left: 0px;">Reset renewal fee:</h1>
			</a>
			<br>
			<form name="ResetRenewalFee" method="post" action="admin6.php">
			<table width="530" cellpadding="5">
			<tr> <!-- The client-side JS used with this ResetRenewalFee form uses three hidden fields: ExistingFee below (i.e. the current LicenseFee), ExistingTerm (which was already integrated above for the RenewMediator form as a hidden field, so no need to repeat it here), and HiddenRenewalFeeForAPR (into which is fed the contents of the span id="RenewalFeeForAPR". Of these hidden fields, only HiddenRenewalFeeForAPR is used by the form processor admin6.php, but the other two are used the JS client-side functions calcRenewalFeeForAPR and calcAPRForRenewalFee -->
			<td colspan="2">
			<input type="hidden" name="ExistingFee" id="ExistingFee" value="<?=$row['LicenseFee']; ?>">
			</td>
			</tr>
			<tr>
			<td><label>Current Fee & Term</label></td>
			<td valign="top" class="basictext"><?php echo '$'.$row['LicenseFee'].' ('; 
			switch ($row['LicenseTerm'])
				{
				case 1:
					echo '1 month)';
					break;
				case 3:
					echo 'quarter)';
					break;
				case ((int)$row['LicenseTerm'] < 12):
					echo $row['LicenseTerm'].' months)';
					break;
				case 12:
					echo '1 year)';
					break;
				case 24:
					echo '2 years)';
					break;
				default:
					echo $row['LicenseTerm'].' months)';
					break;
				};
			 ?>
			</td>
			</tr>
			<tr>
			<td><label>Renewal Fee</label></td>
			<td valign="top" class="basictext"><?php echo '$'.$row['RenewalFee']; ?>
			</td>
			</tr>
			<tr>
			<td><label>APR (Fee Increase)</label></td>
			<td valign="top" class="basictext"><?php $apr=calcAPRForRenewalFee($row['RenewalFee'], $row['LicenseFee'], $row['LicenseTerm']); echo round($apr,3).'%'; ?><!-- Note that we use a PHP version of calcAPRForRenewalFee here, but there's also a very similar Javascript version of that function used for client-side "real-time" calculation of the APR below when the Administrator wants to explore reseting the renewal fee. -->
			</td>
			</tr>
			<tr>
			<td><label>Public Fee</label></td>
			<td valign="top" class="basictext"><?php echo '$'.$FeeForTermArray[$row["LicenseTerm"]]; ?>
			</td>
			</tr>
			<tr>
			<td><label>Margin Guarantee</label></td>
			<td valign="top" class="basictext"><?php $_SESSION['Margin'] = $row['Margin']; echo $row['Margin'].'%'; ?>
			</td>
			</tr>
			<tr height="50" valign="bottom">
			<td colspan="2" valign="bottom">
			<input type="checkbox" name="ResetRenewalFeeForAPR" id="ResetRenewalFeeForAPR" style="vertical-align: bottom;" value="yes" onClick="hideAllErrors(); if (!this.checked) { document.getElementById('LicenseFeeAPRForReset').value = ''; document.getElementById('RenewalFeeForAPR').innerHTML = '____'; document.getElementById('RenewalFeeForAPR').style.borderColor = '#FFFFFF'; }; if (this.checked) { document.getElementById('ResetRenewalFeeValue').checked = false; document.getElementById('RenewalFeeForReset').value = ''; document.getElementById('LicenseFeeAPRForRenewalFee').innerHTML = '____'; document.getElementById('LicenseFeeAPRForRenewalFee').style.borderColor = '#FFFFFF'; document.getElementById('LicenseFeeAPRForReset').disabled = false; document.getElementById('RenewalFeeForAPR').disabled = false; document.getElementById('RenewalFeeForReset').disabled = true; document.getElementById('LicenseFeeAPRForRenewalFee').disabled = true; document.getElementById('LicenseFeeAPRForReset').focus(); } else { document.getElementById('LicenseFeeAPRForReset').disabled = true; document.getElementById('RenewalFeeForAPR').disabled = true; };">
			&nbsp;<label class="small" style="vertical-align: middle;">Check to set Renewal Fee based on APR increase</label>
			</td>
			</tr>
			<tr>
			<td colspan="2">
			<input type="text" name="LicenseFeeAPRForReset" id="LicenseFeeAPRForReset" maxlength="5" size="2" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkLicenseFeeAPRForResetOnly();" onkeyup="document.getElementById('RenewalFeeForAPR').innerHTML = calcRenewalFeeForAPR(this.value); document.getElementById('HiddenRenewalFeeForAPR').value = calcRenewalFeeForAPR(this.value);" onKeyDown="document.getElementById('RenewalFeeForAPR').style.borderColor = '#CCCCCC';" disabled><!-- Dump the calculated value returned by calcRenewalFeeForAPR() into the hidden field HiddenRenewalFeeForAPR for posting into admin6.php. Note that the user interface displays the calculated result as a span, and contents of span elements are not posted with form results. Hence my need to use a hidden input form element to relay the calculated value to admin6.php. -->
			&nbsp;<label>% APR produces a Renewal Fee of&nbsp;$</label>
			<span id="RenewalFeeForAPR" style="overflow: auto; height: 16px; border: 1px solid #FFFFFF; padding: 3px 2px 2px 2px;" class="basictext">____</span>
			<div class="error" id="LicenseFeeAPRForResetError"><br>Enter a percentage (%). Use only numbers [0-9] and the period (.) character.<br></div>
			<?php if ($_SESSION['MsgLicenseFeeAPRForReset'] != null) { echo $_SESSION['MsgLicenseFeeAPRForReset']; $_SESSION['MsgLicenseFeeAPRForReset']=null; } ?>
			</td>
			</tr>
			<tr> <!-- The client-side JS used with this ResetRenewalFee form uses three hidden fields: ExistingFee below (i.e. the current LicenseFee), ExistingTerm (which was already integrated above for the RenewMediator form as a hidden field, so no need to repeat it here), and HiddenRenewalFeeForAPR (into which is fed the contents of the span id="RenewalFeeForAPR". Of these hidden fields, only HiddenRenewalFeeForAPR is used by the form processor admin6.php, but the other two are used the JS client-side functions calcRenewalFeeForAPR and calcAPRForRenewalFee -->
			<td colspan="2">
			<input type="hidden" name="HiddenRenewalFeeForAPR" id="HiddenRenewalFeeForAPR">
			</td>
			</tr>
			<tr height="50" valign="bottom">
			<td colspan="2" valign="bottom">
			<input type="checkbox" name="ResetRenewalFeeValue" id="ResetRenewalFeeValue" style="vertical-align: bottom;" value="yes" onClick="hideAllErrors(); if (!this.checked) { document.getElementById('RenewalFeeForReset').value = ''; document.getElementById('LicenseFeeAPRForRenewalFee').innerHTML = '____'; document.getElementById('LicenseFeeAPRForRenewalFee').style.borderColor = '#FFFFFF'; }; if (this.checked) { document.getElementById('ResetRenewalFeeForAPR').checked = false; document.getElementById('LicenseFeeAPRForReset').value = ''; document.getElementById('RenewalFeeForAPR').innerHTML = '____'; document.getElementById('RenewalFeeForAPR').style.borderColor = '#FFFFFF'; document.getElementById('RenewalFeeForReset').disabled = false; document.getElementById('LicenseFeeAPRForRenewalFee').disabled = false; document.getElementById('LicenseFeeAPRForReset').disabled = true; document.getElementById('RenewalFeeForAPR').disabled = true; document.getElementById('RenewalFeeForReset').focus(); } else { document.getElementById('RenewalFeeForReset').disabled = true; document.getElementById('LicenseFeeAPRForRenewalFee').disabled = true; };">
			&nbsp;<label class="small" style="vertical-align: middle;">Check to set Renewal Fee as a dollar value</label>
			</td>
			</tr>
			<tr>
			<td colspan="2">
			<label>$</label>&nbsp;<input type="text" name="RenewalFeeForReset" id="RenewalFeeForReset" maxlength="6" size="4" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkRenewalFeeForResetOnly();" onKeyUp="document.getElementById('LicenseFeeAPRForRenewalFee').innerHTML = calcAPRForRenewalFee(this.value);" onKeyDown="document.getElementById('LicenseFeeAPRForRenewalFee').style.borderColor = '#CCCCCC';" disabled>
			&nbsp;<label>Renewal Fee is equivalent to an APR increase of</label>&nbsp;
			<span id="LicenseFeeAPRForRenewalFee" style="overflow: auto; height: 16px; width: 20px; border: 1px solid #FFFFFF; padding: 3px 2px 2px 2px;" class="basictext">____</span>
			&nbsp;<label>%</label>
			<div class="error" id="RenewalFeeForResetError"><br>Enter a dollar value. Use only numbers [0-9] and the period (.) character.<br></div>
			<?php if ($_SESSION['MsgRenewalFeeForReset'] != null) { echo $_SESSION['MsgRenewalFeeForReset']; $_SESSION['MsgRenewalFeeForReset']=null; } ?>
			</td>
			</tr>
			<tr height="40px" valign="middle">
			<td colspan="2" align="center">
			<input type="submit" class="buttonstyle" name="ResetRenewalFee" value="Reset" onClick="hideAllErrors(); if (document.getElementById('ResetRenewalFeeForAPR').checked) return checkLicenseFeeAPRForResetOnly(); else if (document.getElementById('ResetRenewalFeeValue').checked) return checkRenewalFeeForResetOnly();">
			</td>
			</tr>
			</table>
			</form>
			</div>
			<?php
			}
			?>

			<div style="position: absolute; left: 725px; top: <?php if ($_SESSION['ClientDemoSelected'] == 'client') echo '1006px;'; else echo '124px;'; ?> width: 530px; padding: 15px 15px 10px 15px; border: 2px solid #444444; border-color: #425A8D; display: block;">
			<a name="mediatorstatus">
			<h1 style="margin-left: 0px;">Manage mediator&rsquo;s status:</h1>
			</a>
			<br>
			<form method="post" action="admin6.php">
			<table width="530" cellpadding="0" cellspacing="0">
			<tr height="20" valign="bottom">
			<td width="120" height="10" valign="middle"><label>Suspend Profile?</label></td>
			<td width="410">
			<input type="radio" name="Suspend" id="SuspendNo" value="0" onclick="this.form.submit();" <?php if ($row['Suspend'] == 0) echo 'checked' ?>><label for="Suspend">No&nbsp;&nbsp;&nbsp;</label>
			<input type="radio" name="Suspend" id="SuspendYes" value="1" onclick="this.form.submit();" <?php if ($row['Suspend'] == 1) echo 'checked'; ?>><label for="Suspend">Yes</label></td>
			</tr>
			<tr valign="top" height="30"><td colspan="2"><div class="greytextsmall">Mediator&rsquo;s profile won&rsquo;t appear on the live site until the Suspend status is removed.</div></td></tr>
			<tr height="20" valign="bottom">
			<td width="120" height="10" valign="middle"><label>Freeze Account?</label></td>
			<td width="410">
			<input type="radio" name="AdminFreeze" id="AdminFreezeNo" value="0" onclick="document.getElementById('PreventResaleNo').disabled = true; document.getElementById('PreventResaleYes').disabled = true; this.form.submit();" <?php if ($row['AdminFreeze'] == 0) echo 'checked' ?>><label for="AdminFreeze">No&nbsp;&nbsp;&nbsp;</label>
			<input type="radio" name="AdminFreeze" id="AdminFreezeYes" value="1" onclick="document.getElementById('PreventResaleNo').disabled = false; document.getElementById('PreventResaleYes').disabled = false; this.form.submit();" <?php if ($row['AdminFreeze'] == 1) echo 'checked'; ?>><label for="AdminFreeze">Yes</label>
			</td>
			</tr>
			<tr valign="top" height="30"><td colspan="2"><div class="greytextsmall">Mediator won&rsquo;t be able to log in to his/her account profile until the Freeze status is lifted.</div></td></tr>
			<tr height="20" valign="bottom">
			<td width="120" height="10" valign="middle"><label>Freeze &amp; Suspend?</label></td>
			<td width="410"><input type="radio" name="FreezeAndSuspend" id="FreezeAndSuspendNo" value="0" onclick="document.getElementById('SuspendYes').checked = false; document.getElementById('SuspendNo').checked = true; document.getElementById('AdminFreezeYes').checked = false; document.getElementById('AdminFreezeNo').checked = true; document.getElementById('PreventResaleNo').disabled = true; document.getElementById('PreventResaleYes').disabled = true; this.form.submit();" <?php if ($row['Suspend'] == 0 || $row['AdminFreeze'] == 0) echo 'checked'; ?>><label for="FreezeAndSuspend">No&nbsp;&nbsp;&nbsp;</label>
			<input type="radio" name="FreezeAndSuspend" id="FreezeAndSuspendYes" value="1" onclick="document.getElementById('SuspendYes').checked = true; document.getElementById('SuspendNo').checked = false; document.getElementById('AdminFreezeYes').checked = true; document.getElementById('AdminFreezeNo').checked = false; document.getElementById('PreventResaleNo').disabled = false; document.getElementById('PreventResaleYes').disabled = false; this.form.submit();" <?php if ($row['AdminFreeze'] == 1 && $row['Suspend'] == 1) echo 'checked' ?>><label for="FreezeAndSuspend">Yes</label></td>
			</tr>
			<tr valign="top" height="30"><td colspan="2"><div class="greytextsmall">Mediator won&rsquo;t be able to log into his/her account profile. Mediator won&rsquo;t appear on the live site.</div></td></tr>
			<tr height="20" valign="bottom">
			<td width="120" height="10" valign="middle"><label>Prevent Resale?</label></td>
			<td width="410">
			<input type="radio" name="PreventResale" id="PreventResaleNo" value="no" onclick="this.form.submit();" <?php if ($row['AdminFreeze'] == 0) echo 'disabled '; if ($row['PrevRslWhlFrzn'] == 0) echo 'checked'; ?>><label for="PreventResale">No&nbsp;&nbsp;&nbsp;</label>
			<input type="radio" name="PreventResale" id="PreventResaleYes" value="yes" onclick="this.form.submit();" <?php if ($row['AdminFreeze'] == 0) echo 'disabled '; if ($row['PrevRslWhlFrzn'] == 1) echo 'checked'; ?>><label for="PreventResale">Yes&nbsp;&nbsp;&nbsp;</label>
			</td>
			</tr>
			<tr valign="top" height="30"><td colspan="2"><div class="greytextsmall" style="position: relative; bottom: 0px;">A license (locale) associated with a frozen account is ordinarily available for resale. Select &lsquo;Yes&rsquo; to prevent resale.</div></td></tr>
			</table>
			</form>
			<form method="post" action="admin6.php">
			<table width="530" cellpadding="0" cellspacing="0">
			<tr height="20" valign="bottom">
			<td width="50">
			<button name="TrashMediator" type="submit" style="background-color: #FFFFFF; border: 0px; width: 53px; position: relative; right: 8px; top: 10px;" onClick="return confirm('You are about to delete a mediator from the database. Click \'OK\' to confirm, or click \'Cancel\' to cancel.');"><img src="/images/trash.jpg" alt=""></button>
			</td>
			<td width="500" height="10" valign="middle"><label>Delete Mediator from Database</label></td>
			</tr>
			<tr valign="top" height="30"><td>&nbsp;</td><td><div class="greytextsmall" style="position: relative; bottom: 13px;">Click the trash icon to remove a mediator. This operation is irreversible.</div></td></tr>
			</table>
			</form>
			</div>

			<div style="position: absolute; left: 725px; top: <?php if ($_SESSION['ClientDemoSelected'] == 'client') echo '1405px;'; else echo '522px;'; ?> width: 530px; padding: 15px 15px 10px 15px; border: 2px solid #444444; border-color: #425A8D; display: block;">
			<a name="changeUP">
			<h1 style="margin-left: 0px;">Change username and password:</h1>
			</a>
			<br>
			<form method="post" action="admin6.php">
			<table width="530" cellpadding="0" cellspacing="0">
			<tr height="34">
			<td width="134" height="10" valign="middle"><label>New Username</label></td>
			<td width="396">
			<input type="text" style="width: 194px;" name="NewUsername" id="NewUsername" maxlength="20" size="26" >
			<?php if ($_SESSION['MsgNewUsername'] != null) { echo $_SESSION['MsgNewUsername']; $_SESSION['MsgNewUsername']=null; }; if ($_SESSION['MsgDuplicateUsername'] != null) { echo $_SESSION['MsgDuplicateUsername']; $_SESSION['MsgDuplicateUsername']=null; } ?>
			</td>
			</tr>
			<tr height="34">
			<td width="134" height="10" valign="middle"><label>New Password</label></td>
			<td width="396">
			<input type="text" style="width: 194px;" name="NewPassword" id="NewPassword" maxlength="20" size="26">
			<?php if ($_SESSION['MsgNewPassword'] != null) { echo $_SESSION['MsgNewPassword']; $_SESSION['MsgNewPassword']=null; }; if ($_SESSION['MsgDuplicatePassword'] != null) { echo $_SESSION['MsgDuplicatePassword']; $_SESSION['MsgDuplicatePassword']=null; } ?>
			</td>
			</tr>
			<tr height="40px" valign="bottom">
			<td>&nbsp;</td>
			<td>
			<input type="submit" name="ChangeUsernamePassword" value="Change Username/Password" <?php if ($row['AdminFreeze']) { echo 'disabled'; } else { echo 'class="buttonstyle"'; }; ?> style="width: 200px;"> <!-- Disable an attempt to change username/password for a mediator whose account is frozen. Only show button in its stylized class form when not disabled. -->
			</td>
			</tr>
			<tr height="5px"><td colspan="2">&nbsp;</td></tr>
			</table>
			</form>
			</div>

			<div style="position: absolute; left: 725px; top: <?php if ($_SESSION['ClientDemoSelected'] == 'client') echo '1646px;'; else echo '764px;'; ?> width: 530px; padding: 15px 15px 10px 15px; border: 2px solid #444444; border-color: #425A8D; display: block;">
			<a name="profemail">
			<h1 style="margin-left: 0px;">Issue configuration instructions for professional email account:</h1>
			</a>
			<br>
			<form method="post" action="admin6.php">
			<table width="530" cellpadding="0" cellspacing="0">
			<tr height="20" valign="bottom">
			<td width="134" height="10" valign="middle"><label>Email Address</label></td>
			<td width="396">
			<input type="text" name="profemailname" id="profemailname" maxlength="28" size="16" value="<?php $ProfEmail = $row['ProfessionalEmail']; $ProfEmail = explode('@', $ProfEmail); echo $ProfEmail[0]; ?>">
			<label>&nbsp;@&nbsp;</label>
			<select name="profemaildomain" id="emaildomain" size="1">
			<option value="newresolutionmediation.com">newresolutionmediation.com</option>
			<option value="divorce-mediators.org">divorce-mediators.org</option>
			<option value="maritalmediators.com">maritalmediators.com</option>
			</select>
			<?php if ($_SESSION['MsgProfEmailName'] != null) { echo $_SESSION['MsgProfEmailName']; $_SESSION['MsgProfEmailName']=null; } ?>
			</td>
			</tr>
			<tr height="38" valign="bottom">
			<td width="134" height="10" valign="bottom"><label>Email Password</label></td>
			<td width="396">
			<input type="text" name="profemailpassword" id="profemailpassword" maxlength="16" size="16" value="<?php echo $row['ProfEmailPassword']; ?>"><span style="position: relative; left: 60px; top: 2px;"><a style="font-size: 11px;" target="_blank" href="https://secure54.inmotionhosting.com:2083/frontend/x3/mail/pops.html">Link to Email Server</a></span>
			</td>
			</tr>
			<tr height="10" valign="bottom">
			<td width="134" height="10" valign="middle">&nbsp;</td>
			<td width="396" style="position: relative; bottom: 10px;"><?php if ($_SESSION['MsgProfEmailPassword'] != null) { echo $_SESSION['MsgProfEmailPassword']; $_SESSION['MsgProfEmailPassword']=null; } else { echo '&nbsp;'; }; ?>
			</td>
			</tr>
			<tr height="50px" valign="bottom">
			<td colspan="2" align="center">
			<input type="submit" name="IssueEmailInstructions" value="Issue Instructions" onClick="return confirm('An email account for this address needs to be created before issuing set-up instructions to the mediator. Click \'OK\' to proceed, or click \'Cancel\' to cancel.');" <?php if ($row['AdminFreeze']) { echo 'disabled'; } else { echo 'class="buttonstyle"'; }; ?>> <!-- Disable an attempt to issue instructions to a mediator whose account is frozen. Only show button in its stylized class form when not disabled. -->
			</td>
			</tr>
			<tr height="5px"><td colspan="2">&nbsp;</td></tr>
			</table>
			</form>
			</div>

			<?php			
			if ($_SESSION['ClientDemoSelected'] == 'client') // By-pass if the user selected 'demo' rather than 'client' b/c there is no SEOlocations field in the mediators_table_demo.
			{
			?>
				<div style="position: absolute; left: 725px; top: <?php if ($_SESSION['ClientDemoSelected'] == 'client') echo '1914px;'; else echo '1032px;'; ?> width: 530px; padding: 15px 15px 10px 15px; border: 2px solid #444444; border-color: #425A8D; display: block;">
				<h1 style="margin-left: 0px;">Assign a value to SEO locations (administrator use only):</h1>
				<br>
				<form method="post" action="admin6.php">
				<table width="530" cellpadding="0" cellspacing="0">
				<tr height="34">
				<td width="530">
				<textarea name="SEOlocations" id="SEOlocations" rows="2" cols="65" wrap="soft" style="overflow:auto; height: 36px; width: 510px;" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white';"><?php if ($row['SEOlocations'] != '') echo $row['SEOlocations']; ?></textarea>
				</td>
				</tr>
				<tr height="50">
				<td align="center">
				<input type="submit" name="AssignSEOlocations" value="Assign" class="buttonstyle">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" value="Clear" class="buttonstyle" onClick="document.getElementById('SEOlocations').focus();">
				</td>
				</tr>
				</table>
				</form>
				</div>
			<?php
			}
			?>

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
?>	
</body>
</html>