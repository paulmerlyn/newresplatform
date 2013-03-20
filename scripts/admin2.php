<?php
/* 
This script is the slave processor for admin1.php, which allws the administrator to add a new mediator. It effects changes to the mediators_table (or mediators_table_demo) and to the locales_table. 
*/
// Start a session
session_start();
ob_start(); // Used in conjunction with ob_flush() [see www.tutorialized.com/view/tutorial/PHP-redirect-page/27316], this allows me to postpone issuing output to the screen until after the header has been sent.
if ($_SESSION['AntiReloadFlag'] == 'true') exit; // First set to 'false' in admin1.php. Used to prevent a user-forced reload (i.e. user hits F5 key) of admin2.php, which would resubmit the form data each time if this anti-reload device were not employed.
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Processing Slave Script for admin1.php</title>
<link href="/nrcss.css" rel="stylesheet" type="text/css">
</head>

<body>
<h1 style="margin-left:100px; margin-top: 30px; font-size: 22px; padding: 0px; color: #425A8D;">New Resolution Mediation Launch Platform&trade; Administrator</h1>
<?php
require('../ssi/obtainparameters.php'); // Include the obtainparameters.php file, which retrieves values $DefaultAPR (and $FeeTermArray) from the parameters_table table.

// Create short variable names
$Locale = $_POST['Locale'];
$Exclusive = $_POST['Exclusive']; // Check-box is checked by administrator if client has purchased an exclusive license.
$DofAdmission = $_POST['DofAdmission']; // Selected by administrator with aid of tigra_calendar JS function.
$LicenseFee = $_POST['LicenseFee'];
$LicenseTerm = $_POST['LicenseTerm'];
$LicenseFeeAPR = $_POST['LicenseFeeAPR']; // A user- (i.e. admininstrator-) supplied value of the Annual Percentage Rate that may assume its default value from admin1.php.
$OverrideDefaultAPR = $_POST['OverrideDefaultAPR'];
$OverrideAPR = $_POST['OverrideAPR'];
$RenewalFee = $_POST['RenewalFee'];
$StateOfInterest = $_POST['StateOfInterest'];
$CreateNewMediator = $_POST['CreateNewMediator'];

if (!get_magic_quotes_gpc())
{
	$DofAdmission = addslashes($DofAdmission);
	$LicenseFee = addslashes($LicenseFee);
	$LicenseFeeAPR = addslashes($LicenseFeeAPR);
	$RenewalFee = addslashes($RenewalFee);
}	

// Sanitize user-supplied field data.
$DofAdmission = htmlspecialchars($DofAdmission);
$LicenseFee = htmlspecialchars($LicenseFee);
$LicenseFeeAPR = htmlspecialchars($LicenseFeeAPR);
$RenewalFee = htmlspecialchars($RenewalFee);

unset($_SESSION['IneligibleForExclusive']); // Unset so javascript alert code for a locale that is ineligible for an exclusive license isn't processed in admin1.php inappropriately.

$_SESSION['LocaleSelected'] = $Locale; // Store currently selected value of the Locale drop-down on admin1.php inside session variable for use in presetting that drop-down on returning to admin1.php after user checked the 'Exclusive' check-box on admin1.php.


if (!isset($CreateNewMediator)) // By-pass if the 'CreateNewMediator' button was clicked
{
	if (isset($StateOfInterest))
	{
	$_SESSION['StateSelected'] = $StateOfInterest;  // Store the selected state in a session variable for safe-keeping.
	unset($StateOfInterest);
	};
	
	if (isset($Exclusive)) // The user checked the 'Exclusive' check-box in admin1.php, which effected a form submit action.
	{
	$_SESSION['IneligibleForExclusive'] = false; // Initialize this flag to false.

	$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
	or die('Could not connect: ' . mysql_error());
	mysql_select_db('paulme6_newresolution') or die('Could not select database: ' . mysql_error());
	
	// Formulate query to see whether the locale selected in admin1.php's Locale drop-down menu already has one or more licenses issued.
	$Locale = htmlentities($Locale, ENT_QUOTES, 'utf-8'); // Sanitize $Locale. Even though this session variable is filled in a controlled way as as the value of a drop-down menu, I still need to manipulate it a little (using htmlentities() b/c  values may contain single quotation marks as in "Coeur d'Alene_ID". (Note the UTF-8 character set specification is important for chars such as n-tilde in Canon City_CO.) When $Locale is used below in DB queries, we want $Locale to be in entity-coded format (e.g. &ntilde; instead of n-tilde, and &#039; instead of an apostrophe) because the DB stores special characters in their coded format.
	$query = "select NofLicenses from locales_table WHERE Locale = '$Locale'";
	$result = mysql_query($query) or die('Query of NofLicenses i.e. '.$query.' failed: ' . mysql_error());
	$row = mysql_fetch_row($result); // $row array should have just one item, which holds the number of licenses for the currently selected locale.
	$number = $row[0];
	if ((int)$number != 0) // Remember that HTML forms generate strings, so use type casting to temporarily convert to an integer for this comparison.
		{
		$_SESSION['IneligibleForExclusive'] = 1; // The NofLicenses isn't zero, so set the ineligibility flag to true.
		}
	else
		{
		$_SESSION['IneligibleForExclusive'] = 0;  // The NofLicenses is zero, so set the ineligibility flag to false.
		}
	};
// Now go back to admin1.php via either the HTTP_REFERER or	via javascript history.back
?>
<script language="javascript" type="text/javascript">
<!--
history.back();
// -->
</script>
<noscript>
<?php
if (isset($_SERVER['HTTP_REFERER'])) // Antivirus software sometimes blocks transmission of HTTP_REFERER.
	{
	header("Location: admin1.php"); // Go back to previous page. (Alternative to echoing the Javascript statement: history.go(-1) or history.back() in cases where user has Javascript disabled.
	}
	ob_flush();
?>
</noscript>
<?php
exit;
}

unset($CreateNewMediator);
$_SESSION['LocaleSelected'] = null; // Set to null in order to force the locale drop-down menu to reset on return to admin1.php after the user had clicked the 'Create New Mediator' button.

/* Do PHP form validation. */

// Create a session variable for the PHP form validation flag, and initialize it to 'false' i.e. assume it's valid.
$_SESSION['phpinvalidflag'] = false;

// Create session variables to hold inline error messages, and initialize them to blank.
$_SESSION['MsgLocale'] = null;
$_SESSION['MsgLicenseFee'] = null;
$_SESSION['MsgLicenseFeeAPR'] = null;
$_SESSION['MsgRenewalFee'] = null;

// Seek to validate $Locale by simply checking that the drop-down menu is not still in the neutral (null) position
if ($Locale == 'null') // Note the need for the quotes because form values are automatically posted as strings. ($Locale == null won't work.)
	{
	$_SESSION['MsgLocale'] = '<span class="errorphp"><br>Please select a locale from the drop-down menu.<br></span>';
	$_SESSION['phpinvalidflag'] = true; 
	}

// Seek to validate $DofAdmission (but don't bother when $_SESSION['ClientDemoSelected'] == 'demo' because DofAdmission isn't displayed when creating demo mediators).
if ($_SESSION['ClientDemoSelected'] == 'client')
{
$illegalCharSet = '/[^0-9\/]+/'; // Reject everything that contains one or more characters that is neither a slash (/) nor a digit. Note the need to escape the slash. 
$reqdCharSet = '[0-1][0-9]\/[0-3][0-9]\/20[0-9]{2}';  // Required format is MM/DD/YYYY. (Note my choice to use ereg for reqdCharSet (less confusing re slashes than using preg_match.)
if (preg_match($illegalCharSet, $DofAdmission) || !ereg($reqdCharSet, $DofAdmission))
	{
		$_SESSION['MsgDofAdmission'] = '<span class="errorphp"><br>Date must have format MM/DD/YYYY. Use only numbers and slash (/) character.<br></span>';
		$_SESSION['phpinvalidflag'] = true; 
	}
}

// Seek to validate $LicenseFee (but don't bother when $_SESSION['ClientDemoSelected'] == 'demo' because LicenseFee isn't displayed when creating demo mediators).
if ($_SESSION['ClientDemoSelected'] == 'client')
{
$illegalCharSet = '/[^0-9\.]+/'; // Reject everything that contains one or more characters that is neither a period nor a digit.
$reqdCharSet = '/[0-9]+/';  // At least one numeric.
if (preg_match($illegalCharSet, $LicenseFee) || !preg_match($reqdCharSet, $LicenseFee))
	{
		$_SESSION['MsgLicenseFee'] = '<span class="errorphp"><br>Enter a dollar value. Use only numbers [0-9] and the period (.) character.<br></span>';
		$_SESSION['phpinvalidflag'] = true; 
	}
}

// Seek to validate $LicenseFeeAPR (but don't bother when $_SESSION['ClientDemoSelected'] == 'demo' because LicenseFeeAPR isn't displayed when creating demo mediators).
if ($_SESSION['ClientDemoSelected'] == 'client')
{
$illegalCharSet = '/[^0-9\.]+/'; // Reject everything that contains one or more characters that is neither a period nor a digit.
$reqdCharSet = '/[0-9]+/';  // At least one numeric.
if ($OverrideDefaultAPR) // Don't bother to validate when the OverrideDefaultAPR check-box isn't checked.
	{
	if (preg_match($illegalCharSet, $LicenseFeeAPR) || !preg_match($reqdCharSet, $LicenseFeeAPR))
		{
			$_SESSION['MsgLicenseFeeAPR'] = '<span class="errorphp"><br>Enter a percentage (%). Use only numbers [0-9] and the period (.) character.<br></span>';
			$_SESSION['phpinvalidflag'] = true; 
		}
	}
}	

// Seek to validate $RenewalFee (but don't bother when $_SESSION['ClientDemoSelected'] == 'demo' because RenewalFee isn't displayed when creating demo mediators).
if ($_SESSION['ClientDemoSelected'] == 'client')
{
$illegalCharSet = '/[^0-9\.]+/'; // Reject everything that contains one or more characters that is neither a period nor a digit.
$reqdCharSet = '/[0-9]+/';  // At least one numeric.
if ($OverrideAPR) // Don't bother to validate when the OverrideAPR check-box isn't checked.
	{
	if (preg_match($illegalCharSet, $RenewalFee) || !preg_match($reqdCharSet, $RenewalFee))
		{
			$_SESSION['MsgRenewalFee'] = '<span class="errorphp"><br>Enter a dollar value. Use only numbers [0-9] and the period (.) character.<br></span>';
			$_SESSION['phpinvalidflag'] = true; 
		}
	}
}
	
//Now go back to the previous page (admin1.php) and show any PHP inline validation error messages if the $_SESSION['phpinvalidflag'] has been set to true. ... otherwise, proceed to update the database with the user's form data.
if ($_SESSION['phpinvalidflag'])
	{
	header("Location: admin1.php"); // Go back to admin1.php page.
	exit;	
	};

// End of PHP form validation

// Connect to mysql and select database
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
	or die('Could not connect: ' . mysql_error());
mysql_select_db('paulme6_newresolution') or die('Could not select database');

/* Draw an available username-password pair from either userpass_table or userpass_table_demo (i.e. a Username and a Password whose Available column is 1).  (Note: the following code pertaining to locales_table and mediators_table updates upon sale of a new license was reused in userpass.php.) */
// Decide on which username-password table name from which to draw a username-password pair.
switch ($_SESSION['ClientDemoSelected'])
{
	case 'client' :
		$userpasstablename = 'userpass_table';
		break;
	case 'demo' :
		$userpasstablename = 'userpass_table_demo';
		break;
	default :
		echo 'Error: Unable to determine the name of an appropriate username-password table (client or demo) in admin2.php.<br>';
}
// Draw the first "available" username and password pair
$query = "SELECT Username, Password FROM ".$userpasstablename." WHERE Available = 1 ORDER BY Username LIMIT 1";
$result = mysql_query($query) or die('The SELECT Username, Password (where Available = 1) failed i.e. '.$query.' failed: ' . mysql_error());
$line = mysql_fetch_assoc($result);
$Username = $line['Username']; // Assign the username
$Password = $line['Password']; // Assign the password

// Update the Available value (setting it to 0 i.e. unavailable) for the username-password pair that we've now assigned to a mediator.
$query = "UPDATE ".$userpasstablename." SET Available = 0 WHERE Username = '".$Username."' AND Password = '".$Password."'";
$result = mysql_query($query) or die('The attempt to update the userpass_table (or userpass_table_demo) has failed. ' . mysql_error());

/* Perform necessary manipulations prior to data insertion into DB: */

// Manipulate Locale (note: even though this is provided in a controlled way via a drop-down menu, I still need to manipulate it a little (using htmlentities b/c Locale values may contain single quotation marks as in "Coeur d'Alene_ID" or n-tilde as in "Canon City_CO".
$Locale = htmlentities($Locale, ENT_QUOTES, 'UTF-8');

// Manipulate the Exclusive check-box value. (Default value in DB is 0 i.e. false.)
if ($Exclusive != 1) $Exclusive = 0;

// Manipulate the Date of Admission from HTML form format into the 'YYYY-MM-DD' MySQL format.
$datearray = explode('/', $DofAdmission);
$DofAdmission = $datearray[2].'-'.$datearray[0].'-'.$datearray[1];
$DofAdmission = htmlspecialchars($DofAdmission);


// Calculate RenewalFee, either from the LicenseFeeAPR or from the value entered by the user for RenewalFee.
if ($OverrideAPR != 'yes')  // User didn't override the APR on admin1.php form, so use LicenseFeeAPR to calculate a RenewalFee value. (If user did override the APR, then RenewalFee will be the value entered by the user.)
	{
	if ($OverrideDefaultAPR != 'yes') $LicenseFeeAPR = $DefaultAPR; // If administrator left OverrideDefaultAPR unchecked on admin1.php form, LicenseFeeAPR will take the default value, which is retrieved from the parameters_table via the require('ssi/obtainparameters.php') statement.
	$mthlyrate = (pow((1 + $LicenseFeeAPR/100), 1/12) -1)*100; // Calculate the equivalent monthly % interest rate using rearranged formula A = P [1 + r/100]^n where exponent is 1/12
	$RenewalFee = (float)$LicenseFee*(pow(1 + $mthlyrate/100, (int)$LicenseTerm));  // Uses formula A= P [1 + r/100]^n where n is the number of months in the $LicenseTerm. (Note use of type casting to integers.)
	$RenewalFee = round($RenewalFee, 2);
	$RenewalFee = htmlspecialchars($RenewalFee);
	};

// Use MySQL functions below within the $query string formulation to calculate ExpirationDate (easier than using PHP's limited date functions).

// Select appropriate database table (client vs demo) name. Also formulate query to insert into database the new mediator's username and password from the POST array, and also set the Locale. Include other details in the query (i.e. DofAdmission, Exclusive, LicenseFee, LicenseTerm, ExpirationDate, and RenewalFee) when adding clients.
// Note that mediators_table (and mediators_table_demo) store the password in its encrypted form via sha1, whereas userpass_table (and userpass_table_demo) store the password in its non-encrypted form.
switch ($_SESSION['ClientDemoSelected'])
	{
	case 'client' :
		$dbmediatorstablename = 'mediators_table';
		$query = "insert into ".$dbmediatorstablename.
			" set Username = '$Username', Password = sha1('$Password'), Locale = '$Locale', Exclusive = '$Exclusive', DofAdmission = '$DofAdmission', LicenseFee = '$LicenseFee', LicenseTerm = '$LicenseTerm', ExpirationDate = DATE_ADD('$DofAdmission', INTERVAL '$LicenseTerm' MONTH), RenewalFee = '$RenewalFee', Margin = '$MarginNow'";
		break;
	case 'demo' :
		$dbmediatorstablename = 'mediators_table_demo';
		$query = "insert into ".$dbmediatorstablename.
			" set Username = '$Username', Password = sha1('$Password'), Locale = '$Locale'";
		break;
	default :
		echo 'Error: Unable to determine the name of an appropriate database table for data insertion.<br>';
		exit;
	}


$result = mysql_query($query) or die('Either you are not authenticated to insert a username, password, and locale, or username/password/locale and license details insertion into database failed. ' . mysql_error());
echo '<p class=\'basictext\' style=\'width: 630px; margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px;\'>Mission accomplished! A '.$_SESSION['ClientDemoSelected'].' mediator has now been added to the database.</p>';
echo '<p class=\'basictext\' style=\'width: 630px; margin-left: 300px; margin-right: 50px; margin-top: 20px; font-size: 14px;\'>Username = '.$Username.'<br>Password = '.$Password.'</p>';

// Next, in order to determine whether the Full flag should now be set to true in the locales_table, obtain values of NofLicenses and MaxLicenses from locales_table and compare them. Note: this query and the subsequent update to the locales_table table are only appropriate when $_SESSION['ClientDemoSelected'] is 'client'. I don't need to make any updates to the locales_table for 'demo'.
if ($_SESSION['ClientDemoSelected'] == 'client')
{
	$query = "SELECT NofLicenses, MaxLicenses FROM locales_table WHERE Locale = '".$Locale."'";
	$result = mysql_query($query) or die('Unable to retrieve the value of NofLicenses for this locale value. ' . mysql_error());
	$row = mysql_fetch_row($result); 
	$NofLicenses = $row[0];
	$NofLicenses = $NofLicenses + 1; // Increment $NofLicenses b/c we are now adding a new license.
	$MaxLicenses = $row[1];
	$Full = 0; // Default value
	if (($NofLicenses >= $MaxLicenses) && ($Exclusive != 1)) $Full = 1;  // Full should be assigned the value 1 (i.e. true) if now the $NofLicenses has increased to the level of the $MaxLicenses AND the mediator didn't make an exclusive purchase.

// Now define another query, this time to update the locales_table. The NofLicenses column will increment every time a mediator is added (i.e. a license is issued) to the mediators_table. Also update the Full column, which may now be 1 or may still be zero (calculated above). And, if the mediator chose to purchase an exclusive license, the Exclusive column will go from 0 to 1 (and Full will remain at 0).
	$query = "UPDATE locales_table SET Exclusive = '$Exclusive', NofLicenses = '$NofLicenses', Full = '$Full' WHERE Locale = '$Locale'";
	$result = mysql_query($query) or die('Either you are not authenticated to update the locales_table, or Exclusive/NofLicenses/Full update into database failed. ' . mysql_error());
};
?>
<form method="post" action="unwind.php">
<table width="530" cellpadding="0" cellspacing="0" style="margin-top: 50px; margin-left: 100px;">
<tr>
<td style="text-align: right;">
<input type='button' name='Continue' class='buttonstyle' value='Continue' onclick='javascript: window.location = "admin1.php";'> <!-- This is not a submit button and functions independenly of the action='unwind.php' form -->
</td>
<td width="50">&nbsp;</td>
<td style="text-align: left;">
<input type="submit" name="Logout" class="buttonstyle" value="Log Out">
</td>
</tr>
</table>
</form>

<?php 
// Free resultset
//mysql_free_result($result);  // Commented this out b/c it produced a PHP Warning. Inmotion tech support suggested it's b/c the $query resource is from an insert statement and is therefore not valid for mysql_free_result() memory freeing. If $query had been a select statement instead of an insert statement, mysql_free_result($result) would not produce a warning. 

// Closing connection
mysql_close($db);

$_SESSION['AntiReloadFlag'] = 'true';
?>
</body>
</html>