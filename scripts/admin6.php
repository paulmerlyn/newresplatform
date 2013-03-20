<?php
/* 
This script is the slave processor for admin5.php, which allws the administrator to manage certain attributes of an existing mediator in either the demo or client database. It effects changes to the mediators_table (or mediators_table_demo). 
*/
// Start a session
session_start();
ob_start(); // Used in conjunction with ob_flush() [see www.tutorialized.com/view/tutorial/PHP-redirect-page/27316], this allows me to postpone issuing output to the screen until after the header has been sent.
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Processing Slave Script for admin5.php and admin5A.php</title>
<link href="/nrcss.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
require('../ssi/obtainparameters.php'); // Include the obtainparameters.php file, which retrieves values $DefaultAPR from the parameters_table table for use in calculating a renewal fee below.

require('../ssi/democlientpaths2.php'); // Include the democlientpaths2.php file, which provides the page path for either the client case (i.e. '../') or the demo case (i.e. '../demo/') as appropriate to which the custom mediators .shtml files are stored on the server. (Note that the ssi file democlientpaths2.php is similar to but different from the script file democlientpaths1.php.) This page path is used when deleting from the server a custom mediator .shtml file, which may be an appropriate housekeeping task as one of the several steps that happens when the administrator trashes a mediator.
$pagepathlong = $_SESSION['pagepathlong'];

// Create short variable names
$Name = $_POST['Name'];
$Username = $_POST['Username'];
$Password = $_POST['Password'];
$State = $_POST['State'];
$Locale = $_POST['Locale'];
$LocaleLabel = $_POST['LocaleLabel'];
$ID = $_POST['ID'];
$Email = $_POST['Email'];
$Zip = $_POST['Zip'];
$ZipPersonal = $_POST['ZipPersonal'];
$ShowAllMeds = $_POST['ShowAllMeds'];
$FindMediator = $_POST['FindMediator'];

$NoteText = $_POST['NoteText'];
$RecordNote = $_POST['RecordNote'];

$Exclusive = $_POST['Exclusive']; // Check-box is checked by administrator if client has purchased an exclusive license.
$DofRenewal = $_POST['DofRenewal']; // Selected by administrator with aid of tigra_calendar JS function.
$LicenseFee = $_POST['LicenseFee'];
$LicenseTerm = $_POST['LicenseTerm'];
$LicenseFeeAPR = $_POST['LicenseFeeAPR']; // A user- (i.e. admininstrator-) supplied value of the Annual Percentage Rate that may assume its default value from admin5.php.
$OverrideDefaultAPR = $_POST['OverrideDefaultAPR'];
$OverrideAPR = $_POST['OverrideAPR'];
$RenewalFee = $_POST['RenewalFee']; 
$RenewMediator = $_POST['RenewMediator'];

$ResetRenewalFeeForAPR = $_POST['ResetRenewalFeeForAPR'];
$LicenseFeeAPRForReset = $_POST['LicenseFeeAPRForReset'];
$HiddenRenewalFeeForAPR = $_POST['HiddenRenewalFeeForAPR'];
$ResetRenewalFeeValue = $_POST['ResetRenewalFeeValue'];
$RenewalFeeForReset = $_POST['RenewalFeeForReset'];
$ResetRenewalFee = $_POST['ResetRenewalFee']; // Reset Renewal Fee button

$Suspend = $_POST['Suspend'];
$AdminFreeze = $_POST['AdminFreeze'];
$FreezeAndSuspend = $_POST['FreezeAndSuspend'];
$PreventResale = $_POST['PreventResale'];
$TrashMediator = $_POST['TrashMediator'];

$NewUsername = $_POST['NewUsername'];
$NewPassword = $_POST['NewPassword'];
$ChangeUsernamePassword = $_POST['ChangeUsernamePassword'];

$ProfEmailName = $_POST['profemailname'];
$ProfEmailDomain = $_POST['profemaildomain'];
$ProfEmailPassword = $_POST['profemailpassword'];
$IssueEmailInstructions = $_POST['IssueEmailInstructions'];

$SEOlocations = $_POST['SEOlocations'];
$AssignSEOlocations = $_POST['AssignSEOlocations'];

unset($_SESSION['IneligibleForExclusive']); // Unset so javascript alert code for a locale that is ineligible for an exclusive license isn't processed in admin1.php inappropriately.

$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
or die('Could not connect: ' . mysql_error());
mysql_select_db('paulme6_newresolution') or die('Could not select database: ' . mysql_error());

switch ($_SESSION['ClientDemoSelected']) // Ascertain whether search should be of the mediators_table or the mediators_table_demo. (Actually, this is kinda redundant b/c I could get it via the $_SESSION['dbmediatorstablename'] variable within the above include'd file democlientpaths2.php.) Also set the $userpasstablename, which allows me to access the appropriate table when an Administrator attempts to change the mediator's username/password.
	{
	case 'client' :
		$dbmediatorstablename = 'mediators_table';
		$userpasstablename = 'userpass_table';
		break;
	case 'demo' :
		$dbmediatorstablename = 'mediators_table_demo';
		$userpasstablename = 'userpass_table_demo';break;
	default :
		echo 'Error: Unable to determine the name of an appropriate database table for data insertion.<br>';
	}

if (!isset($RenewMediator) && !isset($Exclusive) && !isset($Suspend) && !isset($AdminFreeze) && !isset($FreezeAndSuspend) && !isset($PreventResale) && !isset($TrashMediator) && !isset($IssueEmailInstructions)  && !isset($ChangeUsernamePassword) && !isset($ResetRenewalFee) && !isset($AssignSEOlocations)) // By-pass if the 'RenewMediator' button was clicked, the 'Exclusive' check-box was clicked, the 'Suspend', 'AdminFreeze', 'FreezeAndSuspend', and PreventResale radio buttons were changed, the 'TrashMediator' graphical submit button was clicked, or the 'ChangeUsernamePassword', 'IssueEmailInstructions', or 'ResetRenewalFee', or the 'Assign SEO Locations' buttons were clicked.
{
	if (isset($State))
	{
	$_SESSION['StateSelected'] = $State;  // Store the selected state in a session variable for safe-keeping.
	};
	
	if (isset($FindMediator) || isset($ShowAllMeds)) // User clicked the 'Find Mediator' button in admin5.php or the 'Show All Mediators' check box in admin5.php. This code should return with details of all mediator(s) who match the user-supplied search criteria.
	{
	// Retrieve all rows from locales_table that match the search criteria and place them in resource $row.
	$_SESSION['NoWhereClause'] = 1; // Initialize to true. Its value will remain true iff the user left all search parameters blank in admin5.php, in which case this session variable can trigger a notification alert when control passes back to admin5.php.
	if ($ShowAllMeds == 1) $query = "select ID from ".$dbmediatorstablename; // If 'Show All' check-box was checked in admin5.php the query is to retrieve every ID ... 
	else 
		{
		$query = "select ID from ".$dbmediatorstablename." where"; // ... else just retrieve IDs that match the user-specified criteria.
		if ($Name != '') { $query .= " AND Name like '%$Name%'"; $_SESSION['NoWhereClause'] = 0; }
		if ($Username != '') { $query .= " AND Username like '$Username%'"; $_SESSION['NoWhereClause'] = 0; }
		if ($Password != '') { $query .= " AND Password = sha1('$Password')"; $_SESSION['NoWhereClause'] = 0; }
		// Manipulate Locale (note: even though this is provided in a controlled way via a drop-down menu, I still need to manipulate it a little (using htmlentities b/c Locale values may contain single quotation marks as in "Coeur d'Alene_ID" or n-tilde as in "Canon City_CO".
		$Locale = htmlentities($Locale, ENT_QUOTES, 'UTF-8');
		if ($Locale != '') { $query .= " AND Locale = '$Locale'"; $_SESSION['NoWhereClause'] = 0; } // Locale_State match combined
		else if ($State != null) { $query .= " AND State = '$State'"; $_SESSION['NoWhereClause'] = 0; } // State-only match
		if ($LocaleLabel != '') { $query .= " AND LocaleLabel like '%$LocaleLabel%'"; $_SESSION['NoWhereClause'] = 0; }
		if ($ID != '') { $query .= " AND ID = '$ID'"; $_SESSION['NoWhereClause'] = 0; }
		if ($Email != '') { $query .= " AND (Email like '%$Email%' OR ProfessionalEmail like '%$Email%' OR EmailPersonal like '%$Email%')"; $_SESSION['NoWhereClause'] = 0; }
		if ($Zip != '') { $query .= " AND Zip like '$Zip%'"; $_SESSION['NoWhereClause'] = 0; }
		if ($ZipPersonal != '') { $query .= " AND ZipPersonal like '$ZipPersonal%'"; $_SESSION['NoWhereClause'] = 0; }
		$query = preg_replace('/\sAND\s/',' ',$query, 1); // Remove the first instance of ' AND ' b/c it screws up the query syntax.
		}
	$_SESSION['IDmatchesArray'] = null; // Initialize this to empty.
	if (!$_SESSION['NoWhereClause'] || $ShowAllMeds == 1) // Only perform the mysql query if there is at least one 'where clause' (i.e. if the user entered at least one non-blank item in admin5.php) or the 'ShowAllMeds' checkbox was checked.
		{
		$result = mysql_query($query) or die('Query (select of mediators who match search criteria) failed: ' . mysql_error());
		$i = 0;
		while ($row = mysql_fetch_assoc($result))
			{
			$IDmatchesArray[$i] = $row['ID'];
			$i += 1;
			}
		$_SESSION['IDmatchesArray'] = $IDmatchesArray; // Store the array of IDs that match the search criteria in a session variable so they can be available for use when admin6.php loads admin5.php.
		}
	$_SESSION['FindMediatorReturn'] = 'true'; // Use to control execution on return of control back to admin5.php.
	unset($FindMediator);
	unset($ShowAllMeds);
	};
	
	if (isset($RecordNote)) // User clicked the 'Record' button to record a note in admin5A.php.
	{
	// First retrieve whatever is the existing content in the Notes column of the mediator (demo or client) DB table...
	$query = "select Notes from ".$dbmediatorstablename." where ID = '".$_SESSION['selectedID']."'"; 
	$result = mysql_query($query) or die('Query (retrieve existing contents of Notes column) failed: ' . mysql_error());
	$row = mysql_fetch_assoc($result);

	// ... Then prepend the new note to the existing content and update the Notes column in the DB table.
	$newnote = $NoteText; // This is the new note text to be recorded as entered by the user in admin5A.php.
	$datestamp = date('<b>D, M d, Y, g:i A</b>'); // Create a date/timestamp.
	$newnote = $datestamp.'&nbsp;&ndash;&nbsp;'.$newnote; // Prepend the date/timestamp.
	$newnote = htmlspecialchars($newnote, ENT_QUOTES); // Security measure against malicious script code injection.
	$oldcontentplusnewnote = $newnote.'&lt;hr style=&quot;margin-top: 10px; margin-bottom: 10px; width: 90%; height: 1px; border: 0px;  background-color: #FF9900; color: #FF9900; align: center; size: 1px;&quot;&gt;'.$row['Notes'];
	if (!get_magic_quotes_gpc())
		{
		$oldcontentplusnewnote = addslashes($oldcontentplusnewnote);
		}
	$query = "UPDATE ".$dbmediatorstablename." SET Notes = '".$oldcontentplusnewnote."' WHERE ID = '".$_SESSION['selectedID']."'";

	$result = mysql_query($query) or die('Query (update Notes column) failed: ' . mysql_error());
	if (!$result) {	echo 'The new note could not be recorded into the database.'; };
	unset($RecordNote);
	};
	
// Now go back to admin5.php via either the HTTP_REFERER or	via javascript history.back
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
	header("Location: admin5.php"); // Go back to previous page. (Alternative to echoing the Javascript statement: history.go(-1) or history.back() in cases where user has Javascript disabled.
	}
	ob_flush();
?>
</noscript>
<?php
exit;
}

if (isset($Exclusive) && !isset($RenewMediator)) // The user checked the 'Exclusive' check-box in admin5A.php (and the user didn't click the 'RenewMediator' button), which effected a form submit action.
{
unset($Exclusive);
$_SESSION['IneligibleForExclusive'] = 0; // Initialize this flag to false.

// Formulate query to see whether the locale of the chosen mediator already has more than one licenses issued. (We know it has one license -- namely, the license of the mediator who is being managed! However, if it has more than one license then this mediator should not be allowed to switch to an exclusive license.) Note that $_SESSION['LocaleSelected'] gets assigned in admin5A.php after the mediator was identified by the Administrator in admin5.php.
$query = "select NofLicenses from locales_table WHERE Locale = '".$_SESSION['LocaleSelected']."'";
$result = mysql_query($query) or die('Query of NofLicenses i.e. '.$query.' failed: ' . mysql_error());
$row = mysql_fetch_row($result); // $row array should have just one item, which holds the number of licenses for the currently selected locale.
$number = $row[0];
if ((int)$number != 1) // Remember that HTML forms generate strings, so use type casting to temporarily convert to an integer for this comparison.
	{
	$_SESSION['IneligibleForExclusive'] = 1; // The NofLicenses isn't one, so set the ineligibility flag to true.
	}
else
	{
	$_SESSION['IneligibleForExclusive'] = 0;  // The NofLicenses is zero, so set the ineligibility flag to false.
	}

// Now go back to admin5A.php via either the HTTP_REFERER or via javascript history.back
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
	header("Location: admin5A.php"); // Go back to previous page. (Alternative to echoing the Javascript statement: history.go(-1) or history.back() in cases where user has Javascript disabled.
	}
	ob_flush();
?>
</noscript>
<?php
exit;
}

if (isset($RenewMediator))
{
	unset($RenewMediator);

	// Sanitize user-entered data.
	$DofRenewal = htmlspecialchars($DofRenewal);
	$LicenseFee = htmlspecialchars($LicenseFee);
	$LicenseFeeAPR = htmlspecialchars($LicenseFeeAPR);
	$RenewalFee = htmlspecialchars($RenewalFee);
	
	if (!get_magic_quotes_gpc())
	{
		$DofRenewal = addslashes($DofRenewal);
		$LicenseFee = addslashes($LicenseFee);
		$LicenseFeeAPR = addslashes($LicenseFeeAPR);
		$RenewalFee = addslashes($RenewalFee);
	}

	/* Do PHP form validation. */

	// Create a session variable for the PHP form validation flag, and initialize it to 'false' i.e. assume it's valid.
	$_SESSION['phpinvalidflag'] = false;

	// Create session variables to hold inline error messages, and initialize them to blank.
	$_SESSION['MsgDofRenewal'] = null;
	$_SESSION['MsgLicenseFee'] = null;
	$_SESSION['MsgLicenseFeeAPR'] = null;
	$_SESSION['MsgRenewalFee'] = null;

	// Seek to validate $DofRenewal
	$illegalCharSet = '/[^0-9\/]+/'; // Reject everything that contains one or more characters that is neither a slash (/) nor a digit. (Note the need to escape the slash.)
	$reqdCharSet = '[0-1][0-9]\/[0-3][0-9]\/20[0-9]{2}';  // Required format is MM/DD/YYYY. (Note my choice to use ereg for reqdCharSet (less confusing re slashes than using preg_match.)
	if (preg_match($illegalCharSet, $DofRenewal) || !ereg($reqdCharSet, $DofRenewal))
		{
			$_SESSION['MsgDofRenewal'] = '<span class="errorphp"><br>Date must have format MM/DD/YYYY. Use only numbers and slash (/) character.<br></span>';
			$_SESSION['phpinvalidflag'] = true; 
		}

	// Seek to validate $LicenseFee
	$illegalCharSet = '/[^0-9\.]+/'; // Reject everything that contains one or more characters that is neither a period nor a digit.
	$reqdCharSet = '/[0-9]+/';  // At least one numeric.
	if (preg_match($illegalCharSet, $LicenseFee) || !preg_match($reqdCharSet, $LicenseFee))
		{
			$_SESSION['MsgLicenseFee'] = '<span class="errorphp"><br>Enter a dollar value. Use only numbers [0-9] and the period (.) character.<br></span>';
			$_SESSION['phpinvalidflag'] = true; 
		}
	
	// Seek to validate $LicenseFeeAPR
	$illegalCharSet = '/[^0-9\.]+/'; // Reject everything that contains one or more characters that is neither a period nor a digit.
	$reqdCharSet = '/[0-9]+/';  // At least one numeric.
	if ($OverrideDefaultAPR) // Don't bother to validate when the OverrideDefaultAPR check-box isn't checked.
		{
		if (preg_match($illegalCharSet, $LicenseFeeAPR) || !preg_match($reqdCharSet, $LicenseFeeAPR))
			{
				$_SESSION['MsgLicenseFeeAPR'] = '<span class="errorphp"><br>Enter a percentage (%). Use only numbers [0-9] and the period (.) character.<br></span>';
				$_SESSION['phpinvalidflag'] = true; 
			}
		else if ($LicenseFeeAPR > $_SESSION['Margin']) // Max allowable fee APR = inflation + Margin. So, unless we are in a deflationary period st inflation is negative, $LicenseFeeAPR should always be less than the mediator's margin.
			{
				$_SESSION['MsgLicenseFeeAPR'] = '<span class="errorphp"><br>Warning: The APR is greater than the margin guaranteed to this mediator.<br></span>';
				$_SESSION['phpinvalidflag'] = true; 
			}
		}
	
	// Seek to validate $RenewalFee
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
	
	// Now go back to the previous page (admin5A.php) and show any PHP inline validation error messages if the $_SESSION['phpinvalidflag'] has been set to true. ... otherwise, proceed to update the database with the user's form data.
	if ($_SESSION['phpinvalidflag'])
		{
		header("Location: admin5A.php"); // Go back to admin5A.php page.
		exit;	
		};

	// End of PHP form validation

	// Perform necessary manipulations prior to data insertion into DB:

	// Manipulate the Date of Renewal from HTML form format into the 'YYYY-MM-DD' MySQL format.
	$datearray = explode('/', $DofRenewal);
	$DofRenewal = $datearray[2].'-'.$datearray[0].'-'.$datearray[1];

	// Manipulate the Exclusive check-box value. (Default value in DB is 0 i.e. false.)
	if ($Exclusive != 1) $Exclusive = 0;

	// Calculate The Re-renewal Fee (i.e. column RenewalFee in the DB), either from the LicenseFeeAPR or from the flat $ value entered by the user for RenewalFee.
	if ($OverrideAPR != 'yes')  // User didn't override the APR on admin5A.php form, so use LicenseFeeAPR to calculate a RenewalFee value. (If user did override the APR, then RenewalFee will be the value entered by the user.)
	{
		if ($OverrideDefaultAPR != 'yes') $LicenseFeeAPR = $DefaultAPR; // If administrator left OverrideDefaultAPR unchecked on admin5A.php form, LicenseFeeAPR will take the default value assigned there via the require'd file obtainparameters.php. (Otherwise, it just retains the administrator-supplied value that it has.)
			$mthlyrate = (pow((1 + $LicenseFeeAPR/100), 1/12) -1)*100; // Calculate the equivalent monthly % interest rate using rearranged formula A = P [1 + r/100]^n where exponent is 1/12
	$RenewalFee = (float)$LicenseFee*(pow(1 + $mthlyrate/100, (int)$LicenseTerm));  // Uses formula A= P [1 + r/100]^n where n is the number of months in the $LicenseTerm. (Note use of type casting to integers.)
	$RenewalFee = round($RenewalFee, 2);
	};

// Use MySQL functions below within the $query string formulation to calculate ExpirationDate (easier than using PHP's limited date functions). Note that if DofRenewal is before ExpirationDate then ExpirationDate = ExpirationDate + LicenseTerm. If DofRenewal is on or after ExpirationDate then ExpirationDate = DofRenewal + LicenseTerm.

// Since the ManageMediator form with its RenewMediator button will only display inside admin5A.php when the client database has been selected, we only ever need to update this renewal (or newnewal) data into the mediators_table (never into the mediators_table_demo). Now formulate query to update database values for the following columns: Exclusive, DofRenewal, LicenseFee, LicenseTerm, ExpirationDate, and RenewalFee. Also note that I set AdminFreeze to 0 (i.e. false) because I would always want to unfreeze a mediator who had previously been frozen now that this mediator has been renewed.
	$query = "UPDATE mediators_table".
			" set Exclusive = '$Exclusive', DofRenewal = '$DofRenewal', LicenseFee = '$LicenseFee', LicenseTerm = '$LicenseTerm', AdminFreeze = 0, ";
	if (strcmp($DofRenewal, $_SESSION['ExpirationDate']) < 0) $query .= "ExpirationDate = DATE_ADD('".$_SESSION['ExpirationDate']."', INTERVAL '$LicenseTerm' MONTH), ";
	else $query .= "ExpirationDate = DATE_ADD('$DofRenewal', INTERVAL '$LicenseTerm' MONTH), ";
	$query .= "RenewalFee = '$RenewalFee' ";
	$query .= "WHERE ID='".$_SESSION['selectedID']."'";
			
	$result = mysql_query($query) or die('Either you are not authenticated to insert renewal data into the database, or the insertion failed for other reasons. ' . mysql_error());
	echo '<p class=\'basictext\' style=\'margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px;\'>Mission accomplished! The mediators_table has been updated to reflect this renewal.'; // The text in this paragraph continues below with an echo of ' The locales table has also been updated.' if DemoClientSelected is 'client'.

	// Now define another query, this time to update the Exclusive column in the locales_table. If, upon renewing, the mediator chose to "upgrade" by making his/her license exclusive, the Exclusive column will go from 0 to 1.
	$query = "UPDATE locales_table SET Exclusive = '$Exclusive' WHERE Locale = '".$_SESSION['LocaleSelected']."'";
	$result = mysql_query($query) or die('Either you are not authenticated to update the locales_table, or Exclusive/NofLicenses/Full update into database failed. ' . mysql_error());
	
	// I also need to potentially update the Full column of the locales_table. If, for example, the Exclusive column went from 1 to 0 for a locale that has MaxLicenses of 1, then Full will need to change from 0 to 1 because now the locale has gone from being Exclusive=1/Full=0 to Exclusive=0/Full=1.
	$query = "SELECT NofLicenses, MaxLicenses FROM locales_table WHERE Locale = '".$_SESSION['LocaleSelected']."'";
	$result = mysql_query($query) or die('Unable to retrieve the value of NofLicenses and MaxLicenses for this locale. ' . mysql_error());
	$row = mysql_fetch_row($result); 
	$NofLicenses = $row[0];
	$MaxLicenses = $row[1];
	$Full = 0; // Default value
	if (($NofLicenses >= $MaxLicenses) && ($Exclusive != 1)) $Full = 1;  // Full should be assigned the value 1 (i.e. true) if now the $NofLicenses has increased to the level of the $MaxLicenses AND the mediator didn't make an exclusive purchase.
	
	// Finally update the locales_table with the value of Full, which may now be 1 or may still be zero (assigned above).
	$query = "UPDATE locales_table SET Full = '$Full' WHERE Locale = '".$_SESSION['LocaleSelected']."'";
	$result = mysql_query($query) or die('Either you are not authenticated to update the locales_table, or the Full update into the database failed. ' . mysql_error());

	echo ' The locales table has also been updated to reflect any changes to the Exclusive status of the locale.'; // Continuation sentence from 'Mission accomplished' paragraph started above.
	echo '</p>'; // End paragraph for both client and demo cases.

	require('../ssi/createutilitymediatordata.php'); // Include the createutilitymediatordata.php file, which creates, for either the client level (stored in /scripts/) or the demo level (stored in /demo/scripts/), the javascript mediator data file, utilitymediatordata.js. I use an SSI include here for modularity because the same code block is used a few times in admin6.php as well as in processprofile.php and in cron/expirationrelease.php. I need to recreate this javascript data file because the RenewMediator process has automatically unfrozen (by setting AdminFreeze = 0 above) a mediator who might previously have been frozen. This mediator will only be incorporated into the utilitymediatordata.js file if his/her Suspend value is 0, which it might very well be.
	
	require('../ssi/createcustompages.php'); // Include the createcustompages.php file, which creates, for either the client level (stored in /) or the demo level (stored in /demo/), the .shtml mediator files (e.g. file = "mediatorSanFrancisco-Oakland-Fremont.shtml") and fees pages (e.g. file = "mediationcostSanFrancisco-Oakland-Fremont.shtml" for each locale for which a (non-suspended) mediator exists in mediators_table or mediators_table_demo respectively.

	require('../ssi/createlocalelocationincludes.php'); // Include the createlocalelocationincludes.php file, which creates, for either the client level (stored in /scripts/) or the demo level (stored in /demo/scripts/), the three .php files (i.e. localeslistable.php, localeslistphrase.php, and labellocations.php), which are currently used to incorporate locale- and location-specific data on the newresolution home page. I need to recreate these three .html files because the RenewMediator process has automatically unfrozen (by setting AdminFreeze = 0 above) a mediator who might previously have been frozen. This mediator's locale label/locations data will only be incorporated into the three .html files if his/her Suspend value is 0, which it might very well be. Specifically, labellocations.html provides the table of locale labels and locations at the bottom of the page (and, also, indirectly it provides content for manipulation by marqueescript.js for use in the horizontally scrolling list of labels and parenthesized locations at the top of the screen); localeslistphrase.html provides locale labels content for the server-side include within the paragraph text after the words "... satellite locations in ..."; and localeslisttable.html provides the table of rows of format "locale label, state" (e.g. "Tacoma, WA") that appears on the right-hand edge of the home page. I use a PHP require statement for modularity b/c this same creation code is used a few times in admin6.php as well as in processprofile.php.

	// Closing connection
	mysql_close($db);
	
}

/*
This logic pertains to the 'Reset Renewal Fee' section of admin5A.php, which allows the Administrator to reset the mediator's renewal fee without actually processing a mediator renewal.
*/
if (isset($ResetRenewalFee))
{
	unset($ResetRenewalFee);

	// Sanitize user-entered data.
	$LicenseFeeAPRForReset = htmlspecialchars($LicenseFeeAPRForReset);
	$RenewalFeeForReset = htmlspecialchars($RenewalFeeForReset);

	if (!get_magic_quotes_gpc())
	{
		$LicenseFeeAPRForReset = addslashes($LicenseFeeAPRForReset);
		$RenewalFeeForReset = addslashes($RenewalFeeForReset);
	}

	/* Do PHP form validation. */

	// Create a session variable for the PHP form validation flag, and initialize it to 'false' i.e. assume it's valid.
	$_SESSION['phpinvalidflag'] = false;

	// Create session variables to hold inline error messages, and initialize them to blank.
	$_SESSION['MsgLicenseFeeAPRForReset'] = null;
	$_SESSION['MsgRenewalFeeForReset'] = null;

	// Seek to validate $LicenseFeeAPRForReset if the $ResetRenewalFeeForAPR checkbox was checked.
	if ($ResetRenewalFeeForAPR == 'yes')
	{
		$illegalCharSet = '/[^0-9\.]+/'; // Reject everything that contains one or more characters that is neither a period nor a digit.
		$reqdCharSet = '/[0-9]+/';  // At least one numeric.
		if (preg_match($illegalCharSet, $LicenseFeeAPRForReset) || !preg_match($reqdCharSet, $LicenseFeeAPRForReset))
			{
				$_SESSION['MsgLicenseFeeAPRForReset'] = '<span class="errorphp"><br>Enter a percentage (%). Use only numbers [0-9] and the period (.) character.<br></span>';
				$_SESSION['phpinvalidflag'] = true; 
			}
		else if ($LicenseFeeAPRForReset > $_SESSION['Margin']) // Max allowable fee APR = inflation + Margin. So, unless we are in a deflationary period st inflation is negative, $LicenseFeeAPRForReset should always be less than the mediator's margin.
			{
				$_SESSION['MsgLicenseFeeAPRForReset'] = '<span class="errorphp"><br>Warning: The APR is greater than the margin guaranteed to this mediator.<br></span>';
				$_SESSION['phpinvalidflag'] = true; 
			}
	}

	// Seek to validate $RenewalFeeForReset if the $ResetRenewalFeeValue checkbox was checked.
	if ($ResetRenewalFeeValue == 'yes') // Don't bother to validate when the OverrideDefaultAPR check-box isn't checked.
		{
		$illegalCharSet = '/[^0-9\.]+/'; // Reject everything that contains one or more characters that is neither a period nor a digit.
		$reqdCharSet = '/[0-9]+/';  // At least one numeric.
		if (preg_match($illegalCharSet, $RenewalFeeForReset) || !preg_match($reqdCharSet, $RenewalFeeForReset))
			{
				$_SESSION['MsgRenewalFeeForReset'] = '<span class="errorphp"><br>Enter a dollar value. Use only numbers [0-9] and the period (.) character.<br></span>';
				$_SESSION['phpinvalidflag'] = true; 
			}
		}

	// Now go back to the previous page (admin5A.php) and show any PHP inline validation error messages if the $_SESSION['phpinvalidflag'] has been set to true. ... otherwise, proceed to update the database with the user's form data.
	if ($_SESSION['phpinvalidflag'])
		{
		header("Location: admin5A.php#resetrenewalfee"); // Go back to admin5A.php page.
		exit;	
		};

	// End of PHP form validation

	// Perform necessary manipulations prior to data insertion into DB:

	// If the $ResetRenewalFeeForAPR checkbox was checked, update the value of the RenewalFee column of mediators_table for this mediator with the value of the hidden field $HiddenRenewalFeeForAPR supplied by admin5A.php. (Note that since demo mediators don't even display the ResetRenewalFee section of admin5A.php, we would never want to insert RenewalFee into mediators_table_demo. We would only want to insert into the mediators_table table.)
	if ($ResetRenewalFeeForAPR == 'yes') $query = "UPDATE ".$dbmediatorstablename." SET RenewalFee = '".$HiddenRenewalFeeForAPR."' WHERE ID = '".$_SESSION['selectedID']."'";
	
	// If the $ResetRenewalFeeValue checkbox was checked, update the value of the RenewalFee column of mediators_table for this mediator with the value of the field $RenewalFeeForReset supplied by admin5A.php. (Note that since demo mediators don't even display the ResetRenewalFee section of admin5A.php, we would never want to insert RenewalFee into mediators_table_demo. We would only want to insert into the mediators_table table.)
	if ($ResetRenewalFeeValue == 'yes') $query = "UPDATE ".$dbmediatorstablename." SET RenewalFee = '".$RenewalFeeForReset."' WHERE ID = '".$_SESSION['selectedID']."'";

	$result = mysql_query($query) or die('Either you are not authenticated to update the RenewalFee, or the update failed for other reasons. ' . mysql_error());
	echo '<p class=\'basictext\' style=\'margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px;\'>Mission accomplished! The mediators_table has been updated with a Renewal Fee of $';
	if ($ResetRenewalFeeForAPR == 'yes') echo $HiddenRenewalFeeForAPR.' for the mediator with ID='.$_SESSION['selectedID'].'.</p>';
	if ($ResetRenewalFeeValue == 'yes') echo $RenewalFeeForReset.' for the mediator with ID='.$_SESSION['selectedID'].'.</p>';

	// Closing connection
	mysql_close($db);
}

/*
This logic pertains to certain controls in the 'Manage mediator status' section of admin5A.php (viz, Suspend, AdminFreeze, FreezeAndSspend, and PreventResale controls).
*/
if (isset($Suspend) || isset($AdminFreeze) || isset($FreezeAndSuspend) || isset($PreventResale))
{
	if (isset($Suspend))
	{
		// Processing for the Suspend radio button
		$query = "UPDATE ".$dbmediatorstablename." SET Suspend = '".$Suspend."' WHERE ID = '".$_SESSION['selectedID']."'";
		$result = mysql_query($query) or die('Query (update Suspend column) failed: ' . mysql_error());
		if (!$result) {	echo 'The Suspend column could not be updated in the database.'; };
	}

/*
State Table documentation:

$AdminFreeze		$PreventResale			Action
------------		--------------			-------
1					no						locales table: decrement NofLicenses, modify Exclusive and Full (n/a for demo mediators)
											mediators table: AdminFreeze=1, PrevRslWhlFrzn=0
											
1					yes						locales table: increment NofLicenses, modify Exclusive and Full (n/a for demo mediators)
											mediators table: AdminFreeze=1, PrevRslWhlFrzn=1
											
0					disabled				locales table: increment NofLicenses, modify Exclusive and Full (n/a for demo mediators)
					(prior value = 0)		mediators table: AdminFreeze=0, PrevRslWhlFrzn=0

0					disabled				locales table: no action necessary
					(prior value = 1)		mediators table: AdminFreeze=0, PrevRslWhlFrzn=0
					
*/

// Ensure that $FreezeAndSuspend only retains its value if one of the two FreezeAndSuspend radio controls was clicked (i.e. if the form was submitted by means of an onchange event's getting invoked when the user clicked a FreezeAndSuspend radio button.

	// Retrieve current state (i.e. state recorded in DB prior to any potential change) of PrevRslWhlFrzn from mediators_table (or mediators_table_demo) so it can be used below as a condition in if statements to decide the appropriate changes to the mediators_table (or mediators_table_demo) and the locales_table.
	// Also retrieve AdminFreeze's current state for use in preventing repeated decrements of NofLicenses if the user repeatedly clicks, say, the AdminFreeze (Yes) radio button in admin5A.php. Note that I had to use the onclick event rather than the onchange event for the Suspend/AdminFreeze/FreezeAndSuspend/PreventResale radio buttons in admin5A.php. The onchange event is more appropriate, except IE doesn't register the onchange event for radio buttons or check-boxes until after a user clicks elsewhere on the page (see www.opensourcery.com/blog/chad-granum/ie-onchange-events). So I use onclick instead and then need extra code in admin6.php to detect whether the user has CHANGED the AdminFreeze radio button or has simply repeatedly clicked an already-registered value/button.
	$query = "SELECT PrevRslWhlFrzn, AdminFreeze FROM ".$dbmediatorstablename." WHERE ID = '".$_SESSION['selectedID']."'";
	$result = mysql_query($query) or die('Your attempt to select the PrevRslWhlFrzn column has failed. Here is the query: '.$query.mysql_error());
	$line = mysql_fetch_assoc($result);
	$currentstateofPrevRslWhlFrzn = $line['PrevRslWhlFrzn'];

	// Processing for when admin5A.php undergoes a change in the AdminFreeze radio button (either a direct change of AdminFreeze or an indirect change as a result of the user changing the FreezeAndSuspend radio button) or the PreventResale radio button.
	// Whenever AdminFreeze assumes a state of '1', the PreventResale radio buttons become enabled in admin5A.php. (If AdminFreeze is not applied, then PreventResale will be disabled and it won't contribute a value into the $_POST array when the form is submitted.)
	// When the Administrator first applies an AdminFreeze in admin5A.php, script admin6.php is called and the 'PreventResale' radio buttons in admin5A.php can't possibly be changed from their initial default selection of 'no' (because admin6.php is called immediately onsubmit of the AdminFreeze radio button's being clicked). During this initial state (and potentially very temporary state, before the Administrator clicks the PreventResale 'yes' button), PreventResale is 'no'...
	// ... While the PreventResale radio buttons are 'no', we should release this mediator's license/locale for resale to someone else. Do this by decrementing NofLicenses in the locales_table. Also amend the Full and Exclusive columns of the locales_table if appropriate, which will ensure that they don't show as either Full or Exclusive on the locales drop-down menus in sales.php and admin1.php (and thereby unavailable for purchase by a new licensee).
	if ($AdminFreeze == 1 && $PreventResale == 'no' && ($line['AdminFreeze'] == 0 || $currentstateofPrevRslWhlFrzn == 1)) // PreventResale radio control set to 'no', so resale of this license/locale can take place. (Note that the $line['AdminFreeze'] == 0 condition ensures that we only bother to process this code if the user invoked a true change to the AdminFreeze button (either by clicking AdminFreeze (Yes) or by clicking FreezeAndSuspend).
		{
		
		if ($_SESSION['ClientDemoSelected'] == 'client') // Don't bother modifying the locales_table for freeze operations of demo mediators.
			{
			// Decrement NofLicenses and set Exclusive and Full columns to zero (because these will always revert to zero if they weren't already zero once a mediator's license has been released for resale) in locales_table for the record whose Locale column is $_SESSION['LocaleSelected'].
			$query = "SELECT NofLicenses FROM locales_table WHERE Locale = '".$_SESSION['LocaleSelected']."'";
			$result = mysql_query($query) or die('Your attempt to select the NofLicenses column (relating to the PreventResale radio control being no) has failed. Here is the query: '.$query.mysql_error());
			$line = mysql_fetch_row($result); // $line array should have just one item i.e. the NofLicenses value.
			$decrementedNofLicenses = $line[0] - 1; // Calculate the new decremented amount for NofLicenses in readiness to update the locales_table.
			$query = "UPDATE locales_table SET NofLicenses = '".$decrementedNofLicenses."', Exclusive = '0', Full = '0' WHERE Locale = '".$_SESSION['LocaleSelected']."'";
			$result = mysql_query($query) or die('The attempt to update the locales_table (relating to the PreventResale radio control being no) has failed. ' . mysql_error());
			}

		// Update AdminFreeze and PrevRslWhlFrzn in the mediators_table (or mediators_table_demo) in all situations.
		$query = "UPDATE ".$dbmediatorstablename." SET AdminFreeze = 1, PrevRslWhlFrzn = 0 WHERE ID = '".$_SESSION['selectedID']."'";
		$result = mysql_query($query) or die('Query (update AdminFreeze and PrevRslWhlFrzn columns) failed: ' . mysql_error());
		if (!$result) {	echo 'The AdminFreeze and PrevRslWhlFrzn columns could not be updated in the database.'; };

		}

	// ... When the PreventResale radio buttons are changed to 'yes', this mediator's license/locale should be "reclaimed" and prevented from being resold. Do this by incrementing NofLicenses in the locales_table. Also amend the Full and Exclusive columns of the locales_table if appropriate, which will ensure that the locale shows appropriately on the locales drop-down menus in sales.php and admin1.php (e.g. shows up as unavailable, perhaps, because it's full or exclusive). Restore the Exclusive, NofLicenses, and Full states in the locales_table to their former values (i.e. to the values they had before the PreventResale radio button was set to 'yes', which caused them to change via the above DB Update statement). What are these former values? We can figure them out as follows. 
	else if ($AdminFreeze == 1 && $PreventResale == 'yes' && ($line['AdminFreeze'] == 0 || $currentstateofPrevRslWhlFrzn == 0)) // PreventResale radio control set to 'yes', so resale of this license/locale cannot take place. (Note that the $line['AdminFreeze'] == 0 condition ensures that we only bother to process this code if the user invoked a true change to the AdminFreeze button (either by clicking AdminFreeze (Yes) or by clicking FreezeAndSuspend).
		{

		if ($_SESSION['ClientDemoSelected'] == 'client') // Don't bother modifying the locales_table for freeze operations of demo mediators.
			{
			// The former value of Exclusive in the locales_table will be the value of Exclusive in the mediator's mediators_table (or mediators_table_demo).
			$query = "SELECT Exclusive from ".$dbmediatorstablename." where ID = '".$_SESSION['selectedID']."'"; 
			$result = mysql_query($query) or die('Query (retrieval of Exclusive column) failed: ' . mysql_error());
			$row = mysql_fetch_assoc($result);
			$restoredExclusive = (int)$row['Exclusive'];

			// The former value of NofLicenses will be incremented by one.
			$query = "SELECT NofLicenses, MaxLicenses FROM locales_table WHERE Locale = '".$_SESSION['LocaleSelected']."'";
			$result = mysql_query($query) or die('Your attempt to select the NofLicenses column (relating to the PreventResale check-box being checked) has failed. Here is the query: '.$query.mysql_error());
			$row = mysql_fetch_assoc($result);
			$incrementedNofLicenses = (int)$row['NofLicenses'] + 1; // Calculate the new incremented amount for NofLicenses in readiness to update the locales_table.

			// The former value of Full in the locales_table will be 0 if Exclusive == 1 (that's always the case by definition/design), or it will be 0/1 depending on whether (the restored) NofLicenses is less than/equal to MaxLicenses.
			if ($restoredExclusive == 1) 
				{ 
				$restoredFull = 0; 
				}
			else
				{
				if ($incrementedNofLicenses < $row['MaxLicenses']) $restoredFull = 0; else $restoredFull = 1;
				};

			// Update the locales_table with the restored values
			$query = "UPDATE locales_table SET Exclusive = '".$restoredExclusive."', NofLicenses = '".$incrementedNofLicenses."', Full = '".$restoredFull."' WHERE Locale = '".$_SESSION['LocaleSelected']."'";
			$result = mysql_query($query) or die('The attempt to update the locales_table (relating to the PreventResale check-box being checked) has failed. ' . mysql_error());
			}
			
		// Update AdminFreeze and PrevRslWhlFrzn in the mediators_table (or mediators_table_demo) in all situations.
		$query = "UPDATE ".$dbmediatorstablename." SET AdminFreeze = 1, PrevRslWhlFrzn = 1 WHERE ID = '".$_SESSION['selectedID']."'";
		$result = mysql_query($query) or die('Query (update AdminFreeze and PrevRslWhlFrzn columns) failed: ' . mysql_error());
		if (!$result) {	echo 'The AdminFreeze and PrevRslWhlFrzn columns could not be updated in the database.'; };

		}

	else if ($AdminFreeze == 0 && $currentstateofPrevRslWhlFrzn == 0 && ($line['AdminFreeze'] == 1 || $currentstateofPrevRslWhlFrzn == 1)) // AdminFreeze is returned to 0, while current (i.e. prior) state of PrevRslWhlFrzn is 0. In this case, we need to increment NofLicenses in locales_table in order to restore it to its former value because it was decremented when AdminFreeze was originally set to 1 (because $PreventResale was 'yes'). We also need to set new values for AdminFreeze and PrevRslWhlFrzn in the mediators_table (or mediators_table_demo). (Note that the $line['AdminFreeze'] == 1 condition ensures that we only bother to process this code if the user invoked a true change to the AdminFreeze button (either by clicking AdminFreeze (No) or by clicking FreezeAndSuspend).
		{
		if ($_SESSION['ClientDemoSelected'] == 'client') // Don't bother modifying the locales_table for freeze operations of demo mediators.
			{
			// The former value of Exclusive in the locales_table will be the value of Exclusive in the mediator's mediators_table (or mediators_table_demo).
			$query = "SELECT Exclusive from ".$dbmediatorstablename." where ID = '".$_SESSION['selectedID']."'"; 
			$result = mysql_query($query) or die('Query (retrieval of Exclusive column) failed: ' . mysql_error());
			$row = mysql_fetch_assoc($result);
			$restoredExclusive = (int)$row['Exclusive'];

			// The former value of NofLicenses will be incremented by one.
			$query = "SELECT NofLicenses, MaxLicenses FROM locales_table WHERE Locale = '".$_SESSION['LocaleSelected']."'";
			$result = mysql_query($query) or die('Your attempt to select the NofLicenses column (relating to the PreventResale check-box being checked) has failed. Here is the query: '.$query.mysql_error());
			$row = mysql_fetch_assoc($result);
			$incrementedNofLicenses = (int)$row['NofLicenses'] + 1; // Calculate the new incremented amount for NofLicenses in readiness to update the locales_table.

			// The former value of Full in the locales_table will be 0 if Exclusive == 1 (that's always the case by definition/design), or it will be 0/1 depending on whether (the restored) NofLicenses is less than/equal to MaxLicenses.
			if ($restoredExclusive == 1) 
				{ 
				$restoredFull = 0; 
				}
			else
				{
				if ($incrementedNofLicenses < $row['MaxLicenses']) $restoredFull = 0; else $restoredFull = 1;
				};

			// Update the locales_table with the restored values
			$query = "UPDATE locales_table SET Exclusive = '".$restoredExclusive."', NofLicenses = '".$incrementedNofLicenses."', Full = '".$restoredFull."' WHERE Locale = '".$_SESSION['LocaleSelected']."'";
			$result = mysql_query($query) or die('The attempt to update the locales_table (relating to the PreventResale check-box being checked) has failed. ' . mysql_error());
			}
			
		// Update AdminFreeze and PrevRslWhlFrzn in the mediators_table (or mediators_table_demo) in all situations.
		$query = "UPDATE ".$dbmediatorstablename." SET AdminFreeze = 0, PrevRslWhlFrzn = 0 WHERE ID = '".$_SESSION['selectedID']."'";
		$result = mysql_query($query) or die('Query (update AdminFreeze and PrevRslWhlFrzn columns) failed: ' . mysql_error());
		if (!$result) {	echo 'The AdminFreeze and PrevRslWhlFrzn columns could not be updated in the database.'; };

		}
		
	else if ($AdminFreeze == 0 && $currentstateofPrevRslWhlFrzn == 1 && ($line['AdminFreeze'] == 1 || $currentstateofPrevRslWhlFrzn == 0)) // AdminFreeze is returned to 0, while current (i.e. prior) state of PrevRslWhlFrzn is 1. In this case, we don't need to increment NofLicenses in locales_table in order to restore it to its former value because it wasn't previously decremented when AdminFreeze was originally set to 1 (because $PreventResale was 'no'). We do, however, need to set new values for AdminFreeze and PrevRslWhlFrzn in the mediators_table (or mediators_table_demo). (Note that the $line['AdminFreeze'] == 1 condition ensures that we only bother to process this code if the user invoked a true change to the AdminFreeze button (either by clicking AdminFreeze (No) or by clicking FreezeAndSuspend).
		{
		// Update AdminFreeze and PrevRslWhlFrzn in the mediators_table (or mediators_table_demo) in all situations.
		$query = "UPDATE ".$dbmediatorstablename." SET AdminFreeze = 0, PrevRslWhlFrzn = 0 WHERE ID = '".$_SESSION['selectedID']."'";
		$result = mysql_query($query) or die('Query (update AdminFreeze and PrevRslWhlFrzn columns) failed: ' . mysql_error());
		if (!$result) {	echo 'The AdminFreeze and PrevRslWhlFrzn columns could not be updated in the database.'; };
		};
	
	if ($AdminFreeze == 1 && $PreventResale == 'no') // PreventResale radio control set to 'no', so resale of this license/locale can take place.
		{
		// Update AdminFreeze and PrevRslWhlFrzn in the mediators_table (or mediators_table_demo).
		$query = "UPDATE ".$dbmediatorstablename." SET AdminFreeze = 1, PrevRslWhlFrzn = 0 WHERE ID = '".$_SESSION['selectedID']."'";
		$result = mysql_query($query) or die('Query (update AdminFreeze and PrevRslWhlFrzn columns) failed: ' . mysql_error());
		if (!$result) {	echo 'The AdminFreeze and PrevRslWhlFrzn columns could not be updated in the database.'; };
		}

	else if ($AdminFreeze == 1 && $PreventResale == 'yes') // PreventResale radio control set to 'yes', so resale of this license/locale cannot take place.
		{
		// Update AdminFreeze and PrevRslWhlFrzn in the mediators_table (or mediators_table_demo) in all situations.
		$query = "UPDATE ".$dbmediatorstablename." SET AdminFreeze = 1, PrevRslWhlFrzn = 1 WHERE ID = '".$_SESSION['selectedID']."'";
		$result = mysql_query($query) or die('Query (update AdminFreeze and PrevRslWhlFrzn columns) failed: ' . mysql_error());
		if (!$result) {	echo 'The AdminFreeze and PrevRslWhlFrzn columns could not be updated in the database.'; };
		}

	else if ($AdminFreeze == 0 && $currentstateofPrevRslWhlFrzn == 0) // AdminFreeze is returned to 0, while current (i.e. prior) state of PrevRslWhlFrzn is 0. 
		{
		// Update AdminFreeze and PrevRslWhlFrzn in the mediators_table (or mediators_table_demo) in all situations.
		$query = "UPDATE ".$dbmediatorstablename." SET AdminFreeze = 0, PrevRslWhlFrzn = 0 WHERE ID = '".$_SESSION['selectedID']."'";
		$result = mysql_query($query) or die('Query (update AdminFreeze and PrevRslWhlFrzn columns) failed: ' . mysql_error());
		if (!$result) {	echo 'The AdminFreeze and PrevRslWhlFrzn columns could not be updated in the database.'; };
		}
		
	else if ($AdminFreeze == 0 && $currentstateofPrevRslWhlFrzn == 1) // AdminFreeze is returned to 0, while current (i.e. prior) state of PrevRslWhlFrzn is 1. 
		{
		// Update AdminFreeze and PrevRslWhlFrzn in the mediators_table (or mediators_table_demo) in all situations.
		$query = "UPDATE ".$dbmediatorstablename." SET AdminFreeze = 0, PrevRslWhlFrzn = 0 WHERE ID = '".$_SESSION['selectedID']."'";
		$result = mysql_query($query) or die('Query (update AdminFreeze and PrevRslWhlFrzn columns) failed: ' . mysql_error());
		if (!$result) {	echo 'The AdminFreeze and PrevRslWhlFrzn columns could not be updated in the database.'; };
		};

	unset($Suspend);
	unset($AdminFreeze);
	unset($PreventResale);
	unset($FreezeAndSuspend);

	require('../ssi/createutilitymediatordata.php'); // Include the createutilitymediatordata.php file, which creates, for either the client level (stored in /scripts/) or the demo level (stored in /demo/scripts/), the javascript mediator data file, utilitymediatordata.js. I use an SSI include here for modularity because the same code block is used a few times in admin6.php as well as in processprofile.php and in cron/expirationrelease.php. I need to recreate utilitymediatordata.js here because changes have likely been made to the mediator's Suspend value. createutilitymediatordata.php will only incorporate a mediator into utilitymediatordata.js if Suspend==0.
	
	require('../ssi/createcustompages.php'); // Include the createcustompages.php file, which creates, for either the client level (stored in /) or the demo level (stored in /demo/), the .shtml mediator files (e.g. file = "mediatorSanFrancisco-Oakland-Fremont.shtml") and fees pages (e.g. file = "mediationcostSanFrancisco-Oakland-Fremont.shtml" for each locale for which a (non-suspended) mediator exists in mediators_table or mediators_table_demo respectively.

	require('../ssi/createlocalelocationincludes.php'); // Include the createlocalelocationincludes.php file, which creates, for either the client level (stored in /scripts/) or the demo level (stored in /demo/scripts/), the three .html files (i.e. localeslistable.html, localeslistphrase.html, and labellocations.html), which are currently used to incorporate locale- and location-specific data on the newresolution home page. I need to recreate these three .html files because the RenewMediator process has automatically unfrozen (by setting AdminFreeze = 0 above) a mediator who might previously have been frozen. This mediator's locale label/locations data will only be incorporated into the three .html files if his/her Suspend value is 0, which it might very well be. Specifically, labellocations.html provides the table of locale labels and locations at the bottom of the page (and, also, indirectly it provides content for manipulation by marqueescript.js for use in the horizontally scrolling list of labels and parenthesized locations at the top of the screen); localeslistphrase.html provides locale labels content for the server-side include within the paragraph text after the words "... satellite locations in ..."; and localeslisttable.html provides the table of rows of format "locale label, state" (e.g. "Tacoma, WA") that appears on the right-hand edge of the home page. I use a PHP require statement for modularity b/c this same creation code is used a few times in admin6.php as well as in processprofile.php.

// Now go back to admin5A.php via either the HTTP_REFERER or via javascript history.back
?>
<script language="javascript" type="text/javascript">
<!--
window.location = "http://www.admin5A.php#mediatorstatus";
// -->
</script>
<noscript>
<?php
if (isset($_SERVER['HTTP_REFERER'])) // Antivirus software sometimes blocks transmission of HTTP_REFERER.
	{
	header("Location: admin5A.php#mediatorstatus"); // Go back to previous page. (Alternative to echoing the Javascript statement: history.go(-1) or history.back() in cases where user has Javascript disabled.
	}
ob_flush();
?>
</noscript>
<?php
exit;
}

/*
This logic pertains to an Administrator's attempt to change a mediator's username and/or password. Usernames and passwords (non-encrypted) are stored in pairs for client and demo mediators in the DB userpass_table and userpass_table_demo respectively. (Note that the mediators_table and mediators_table_demo also contain the Username and Password for a mediator, but in those tables the password is stored in its encrypted form.
*/
if (isset($ChangeUsernamePassword))
{
unset($ChangeUsernamePassword);

// Step 1: Perform PHP validation on the submitted $NewUsername and $NewPassword

// Create a session variable for the PHP form validation flag, and initialize it to 'false' i.e. assume it's valid.
$_SESSION['phpinvalidflag'] = false;

// Create session variables to hold inline error messages, and initialize them to blank.
$_SESSION['MsgNewUsername'] = null;
$_SESSION['MsgNewPassword'] = null;

// Seek to validate $NewUsername.
$illegalCharSet = '/[^\w]+/'; // Exclude everything except word character i.e one or more alphanumeric characters or underscore.
$reqdCharSet = '/\w+/';  // Names of form word character i.e one or more alphanumeric characters or underscore.
if (preg_match($illegalCharSet, $NewUsername) || !preg_match($reqdCharSet, $NewUsername))
	{
		$_SESSION['MsgNewUsername'] = '<span class="errorphp"><br>Enter alphanumerics here. Use only letters [A-Z, a-z] and numbers [0-9].<br></span>';
		$_SESSION['phpinvalidflag'] = true; 
	}

// Seek to validate $NewPassword.
$illegalCharSet = '/[^\w]+/'; // Exclude everything except word character i.e one or more alphanumeric characters or underscore.
$reqdCharSet = '/\w+/';  // Names of form word character i.e one or more alphanumeric characters or underscore.
if (preg_match($illegalCharSet, $NewPassword) || !preg_match($reqdCharSet, $NewPassword))
	{
		$_SESSION['MsgNewPassword'] = '<span class="errorphp"><br>Enter alphanumerics here. Use only letters [A-Z, a-z] and numbers [0-9].<br></span>';
		$_SESSION['phpinvalidflag'] = true; 
	}

// Now go back to the previous page (admin5A.php) and show any PHP inline validation error messages if the $_SESSION['phpinvalidflag'] has been set to true. ... otherwise, proceed to determine whether the NewUsername or NewPassword are duplicates of existing Usernames/Passwords in the userpass_table (or userpass_table_demo).
if ($_SESSION['phpinvalidflag'])
	{
	header("Location: admin5A.php#changeUP"); // Go back to admin5A.php page.
	exit;	
	};

// End of PHP form validation

// Step 2: Presuming Step 1 passes okay, determine whether $NewUsername or $NewPassword is a duplicate of any username or any password in userpass_table (client mediators) or userpass_table_demo (demo mediators).
$UsernamesAlreadyInDB = array();
$PasswordsAlreadyInDB = array();
$query = "SELECT Username, Password FROM ".$userpasstablename; // Select all usernames and passwords from the table
$result = mysql_query($query) or die('The SELECT query to obtain all usernames and passwords failed i.e. '.$query.' failed: ' . mysql_error());
while ($row = mysql_fetch_assoc($result))
	{
	array_push($UsernamesAlreadyInDB, $row['Username']); // $UsernamesAlreadyInDB gets built up st $UsernamesAlreadyInDB[0] = the first Username fetched from the DB, $UsernamesAlreadyInDB = the second Username fetched from the DB, etc.
	array_push($PasswordsAlreadyInDB, $row['Password']); // $PasswordsAlreadyInDB gets built up st $PasswordsAlreadyInDB[0] = the first Password fetched from the DB, $PasswordsAlreadyInDB = the second Password fetched from the DB, etc.
	}

$_SESSION['DuplicateUsernameFlag'] = false;
$_SESSION['DuplicatePasswordFlag'] = false;

foreach ($UsernamesAlreadyInDB as $item)
	{
	if ($NewUsername == $item)
		{
		$_SESSION['MsgDuplicateUsername'] = '<span class="errorphp"><br>This username is already assigned. Please choose another.<br></span>';
		$_SESSION['DuplicateUsernameFlag'] = true;
		break;
		}
	}

foreach ($PasswordsAlreadyInDB as $item)
	{
	if ($NewUsername == $item)
		{
		$_SESSION['MsgDuplicateUsername'] = '<span class="errorphp"><br>This username is already assigned. Please choose another.<br></span>';
		$_SESSION['DuplicateUsernameFlag'] = true;
		break;
		}
	}

foreach ($PasswordsAlreadyInDB as $item)
	{
	if ($NewPassword == $item)
		{
		$_SESSION['MsgDuplicatePassword'] = '<span class="errorphp"><br>This password is already assigned. Please choose another.<br></span>';
		$_SESSION['DuplicatePasswordFlag'] = true;
		break;
		}
	}
	
foreach ($UsernamesAlreadyInDB as $item)
	{
	if ($NewPassword == $item)
		{
		$_SESSION['MsgDuplicatePassword'] = '<span class="errorphp"><br>This password is already assigned. Please choose another.<br></span>';
		$_SESSION['DuplicatePasswordFlag'] = true;
		break;
		}
	}
	
// Now go back to the previous page (admin5A.php) and show any PHP inline duplication error messages if the $_SESSION['DuplicateUsernameFlag'] or $_SESSION['DuplicatePasswordFlag'] has been set to true. ... otherwise, proceed to update the userpass_table (or userpass_table_demo) and mediators_table (or mediators_table_demo) with the newly chosen username/password.
if ($_SESSION['DuplicateUsernameFlag'] || $_SESSION['DuplicatePasswordFlag'])
	{
	header("Location: admin5A.php#changeUP"); // Go back to admin5A.php page.
	exit;	
	};

// Note that I have decided not to insert $NewUsername and $NewPassword into the userpass_table (or userpass_table_demo). That's because a customer service rep might be using this utility (within admin5A.php) to manually change a mediator's username-password pair from, say, it's initial sanitary values drawn from userpass_table (which would have been assigned when the mediator first signed up) to new custom (and potentially private/weird/naughty) values. If I were to insert these into userpass_table, they would end up being recycled i.e. available for allocation to another mediator when the first mediator was later removed via the 'TrashMediator' button in admin5A.php. And then my new mediator could be assigned, say, a naughty or weird username-password pair like "big/willy" or "Fur/Elyse".

// Reset the Available flag to 1 (true i.e. available) in the userpass_table (or userpass_table_demo) because the username-password pair associated with the mediator's existing username/password (i.e. before it was changed by the Administrator) is now available for allocation to another mediator. Note I use an inner join here.
$query = "UPDATE ".$userpasstablename.", ".$dbmediatorstablename." SET ".$userpasstablename.".Available = 1 WHERE ".$dbmediatorstablename.".ID = '".$_SESSION['selectedID']."' AND ".$userpasstablename.".Username = ".$dbmediatorstablename.".Username";
$result = mysql_query($query) or die('The attempt to update the userpass_table (or userpass_table_demo) to reset Available to 0 via an inner join with the mediators_table (or mediators_table_demo) has failed. ' .$query.mysql_error());

// Also update the mediators_table (or mediators_table_demo) with $NewUsername and $NewPassword for the mediator in question on admin5A.php.
$query = "UPDATE ".$dbmediatorstablename." SET Username = '".$NewUsername."', Password = '".sha1($NewPassword)."' WHERE ID = '".$_SESSION['selectedID']."'";
$result = mysql_query($query) or die('The attempt to update the mediators_table (or mediators_table_demo) relating to a change of username-password has failed. ' . mysql_error());

echo '<p class=\'basictext\' style=\'margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px;\'>You have successfully changed this '.$_SESSION['ClientDemoSelected'].' mediator&rsquo;s username and password.</p><br><br>';
?>
<div style="text-align: center"> <!-- This div provides centering for older browsers incl. NS4 and IE5. (See http://theodorakis.net/tablecentertest.html#intro.) Use of margin-left: auto and margin-right: auto in the style of the table itself (see below) takes care of centering in newer browsers. -->
<table class="basictext" style="margin-left: auto; margin-right: auto;">
<tr>
<th width="120px;" align="left">Username</th>
<th width="120px;" align="left">Password</th>
</tr>
<tr>
<td align="left"><span style="font-family: Courier, Times, serif;"><?=$NewUsername; ?></span></td>
<td align="left"><span style="font-family: Courier, Times, serif;"><?=$NewPassword; ?></span></td>
</tr>
</table>
<br><br>
</div>

<?php
}

/*
This logic pertains to when the Administrator clicks the 'TrashMediator' button in admin5A.php. Note that demo mediators don't need Steps 2 or 3. The archiving operation of Step 2 and the amendments to the locales_table of Step 3 apply only to client mediators. Demo mediators are not stored in an archive table prior to being deleted from the mediators_table_demo.
*/
if (isset($TrashMediator))
{
// Step 1: Select all the columns from the mediator_table (or mediators_table_demo) for the selected ID. (This is a precursor to Step 2.)
$query = "SELECT * FROM ".$dbmediatorstablename. " WHERE ID='".$_SESSION['selectedID']."'";
$result = mysql_query($query) or die('Your attempt to select items from the table in readiness to delete a mediator has failed. '.mysql_error());
$row = mysql_fetch_assoc($result);
$trashedLocale = $row['Locale']; // Although this value isn't needed until much later, in Step 8, I assign it here b/c other DB queries via include'd files such as the SSI file = createutilitymediatordata.php introduce other DB $row-type queries that would otherwise mask the assignment.

if ($_SESSION['ClientDemoSelected'] == 'client')
	{
// Step 2: Insert all the columns for this record into the medarchive_table.
	$query = "INSERT INTO medarchive_table SET ID = '".$row['ID']."', Username = '".$row['Username']."', Password = '".$row['Password']."', Suspend = '".$row['Suspend']."', Name = '".$row['Name']."', Credentials = '".$row['Credentials']."', Locale = '".$row['Locale']."', Exclusive = '".$row['Exclusive']."', LocaleLabel = '".$row['LocaleLabel']."', Locations = '".$row['Locations']."', Email = '".$row['Email']."', UseProfessionalEmail = '".$row['UseProfessionalEmail']."', ProfessionalEmail = '".$row['ProfessionalEmail']."', ProfEmailPassword = '".$row['ProfEmailPassword']."', EntityName = '".$row['EntityName']."', PrincipalStreet = '".$row['PrincipalStreet']."', PrincipalAddressOther = '".$row['PrincipalAddressOther']."', City = '".$row['City']."', State = '".$row['State']."', Zip = '".$row['Zip']."', Telephone = '".$row['Telephone']."', Fax = '".$row['Fax']."', Profile = '".$row['Profile']."', ImageFileName = '".$row['ImageFileName']."', HourlyRate = '".$row['HourlyRate']."', AdminCharge = '".$row['AdminCharge']."', AdminChargeDetails = '".$row['AdminChargeDetails']."', LocationCharge = '".$row['LocationCharge']."', Packages = '".$row['Packages']."', SlidingScale = '".$row['SlidingScale']."', Increment = '".$row['Increment']."', ConsultationPolicy = '".$row['ConsultationPolicy']."', CancellationPolicy = '".$row['CancellationPolicy']."', TelephoneMediations = '".$row['TelephoneMediations']."', VideoConf = '".$row['VideoConf']."', CardPolicy = '".$row['CardPolicy']."', StreetPersonal = '".$row['StreetPersonal']."', CityPersonal = '".$row['CityPersonal']."', StatePersonal = '".$row['StatePersonal']."', ZipPersonal = '".$row['ZipPersonal']."', TelephonePersonal = '".$row['TelephonePersonal']."', EmailPersonal = '".$row['EmailPersonal']."', ServiceLevel = '".$row['ServiceLevel']."', AdminFreeze = '".$row['AdminFreeze']."', PrevRslWhlFrzn = '".$row['PrevRslWhlFrzn']."', DofAdmission = '".$row['DofAdmission']."', LicenseFee = '".$row['LicenseFee']."', LicenseTerm = '".$row['LicenseTerm']."', ExpirationDate = '".$row['ExpirationDate']."', DofRenewal = '".$row['DofRenewal']."', RenewalFee = '".$row['RenewalFee']."', Notes = '".$row['Notes']."', Margin= '".$row['Margin']."'";
	$result = mysql_query($query) or die('Your attempt to insert items into the medarchive_table has failed. Here is the query: '.$query.mysql_error());

// Step 3: Decrement NofLicenses and set Exclusive and Full columns to zero (because these will always revert to zero if they weren't already zero once a mediator is deleted) in locales_table for the record whose Locale column is $_SESSION['LocaleSelected'].
	$query = "SELECT NofLicenses FROM locales_table WHERE Locale = '".$_SESSION['LocaleSelected']."'";
	//echo 'Query for selecting NofLicenses is: '.$query.'<br>';
	$result = mysql_query($query) or die('Your attempt to select the NofLicenses column (relating to the TrashMediator button) has failed. Here is the query: '.$query.mysql_error());
	$line = mysql_fetch_row($result); // $line array should have just one item i.e. the NofLicenses value.
	//echo 'NofLicenses before decrement is: '.$line[0].'<br>';
	$decrementedNofLicenses = $line[0] - 1; // Calculate the new decremented amount for NofLicenses in readiness to update the locales_table.
	//echo 'Decremented NofLicenses is: '.$decrementedNofLicenses.'<br>';
	
	$query = "UPDATE locales_table SET NofLicenses = '".$decrementedNofLicenses."', Exclusive = '0', Full = '0' WHERE Locale = '".$_SESSION['LocaleSelected']."'";
	//echo 'Query to update locales_table is: '.$query.'<br>';
	$result = mysql_query($query) or die('The attempt to update the locales_table (relating to the TrashMediator button) has failed. ' . mysql_error());
	}
	
// Step 4: Delete the record pertaining to the selectedID from its appropriate table (either mediators_table or mediators_table_demo).
$query = "DELETE FROM ".$dbmediatorstablename." WHERE ID='".$_SESSION['selectedID']."'";
$result = mysql_query($query);

// Step 5: Set Available=1 in the userpass_table (client mediators) or userpass_table_demo (demo mediators) for the Username-Password pair that the mediator was using, which can now be made available for use by other mediators. Note that the Username column in mediators_table (and in mediators_table_demo) [obtained from associative array $row via Step 1 above] is also used in userpass_table (and in userpass_table_demo) so this column can be used as a WHERE condition. Note also that Password is encrypted in mediators_table (and in mediators_table_demo) but non-encrypted in userpass_table (and in userpass_table_demo) so Password could not be used as a WHERE condition. However, there's no need to use a WHERE condition around Password as well as Username because all Username entries are unique from each other (and also, as it happens, unique from any Password entries).
// Also note that in situations where the (client) mediator has since chosen to change his/her Username/Password (via the "Change Username/Password" link on the updateprofile.php or renew.php log-in screens), that custom Username-Password pair won't exist in userpass_table. The implications is that the UPDATE $query of userpass_table will simply produce zero affected rows i.e. there will be no username-password to make Available now.

// First decide on which username-password table name to access.
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
$query = "UPDATE ".$userpasstablename." SET Available = 1 WHERE Username = '".$row['Username']."'";
$result = mysql_query($query) or die('The attempt to restore Available to 1 in the username-password table has failed. ' . mysql_error());

// Step 6: Recreate utilitymediatordata.js because a mediator who was previously incorporated in that file will now need to be excluded from it now that he/she has been trashed.
require('../ssi/createutilitymediatordata.php'); // Include the createutilitymediatordata.php file, which creates, for either the client level (stored in /scripts/) or the demo level (stored in /demo/scripts/), the javascript mediator data file, utilitymediatordata.js. After a Trash operation, a mediator will no longer exist in either the mediators_table or the mediators_table_demo, so he/she will be omitted from utilitymediatordata.js when it is thus recreated. I use an SSI include here for modularity because the same code block is used a few times in admin6.php as well as in processprofile.php and in expirationrelease.php.

// Step 7: Recreate three .php files (i.e. localeslistable.php, localeslistphrase.php, and labellocations.php), which are currently used to incorporate locale- and location-specific data on the newresolution home page.
require('../ssi/createlocalelocationincludes.php'); // Include the createlocalelocationincludes.php file, which creates, for either the client level (stored in /scripts/) or the demo level (stored in /demo/scripts/), the three .php files. I need to recreate these three .php files. A mediator's locale label/locations data will only be incorporated into the three .php files if the mediator still exists in either the mediators_table or the mediators_table_demo. Of course, after a Trash operation, the mediator will not exist in that table.

// Step 8: If no other non-suspended mediator exists in mediators_table (or mediators_table_demo) with the same locale as the trashed mediator, then delete (technically, unlink, in PHP parlance) the custom mediator .shtml file (e.g. mediatorSanFrancisco-Oakland-Fremont.shtml) and the custom fees .shtml file (e.g. mediationcostSanFrancisco-Oakland-Fremont.shtml) from the server. This is nice housekeeping (not vital) to keep the server from getting cluttered with unnecessary .shtml files. (Note: A comparable process (slightly different methodology) is used in createcustompages.php.)

// Having deleted the desired mediator from mediators_table (or mediators_table_demo) in Step 4 above, count number of other mediators who share the trashed mediator's locale and who have a Suspend = 0.
$query1 = "SELECT COUNT(*) FROM ".$dbmediatorstablename." where Locale='".$trashedLocale."' AND Suspend = 0"; 
$result1 = mysql_query($query1) or die('Query (select of Suspend) failed: ' . mysql_error());
$line = mysql_fetch_row($result1); // $line array should have just one item, which holds the count
$countNonSuspMeds = $line[0];

if ($countNonSuspMeds == 0) // Only delete a custom mediator file if this count is zero.
	{
	// Create the name of the the custom mediator file from $row['Locale'] using string functions. Here we are creating the file name for deletion rather than in order to write or rewrite to the file. (Note also: we performed a comparable set of manipulations to build the uniqueLocaleLabelAssocStub[] array using Javascript within utilitymediatorcode.js, but now we need to work with PHP, and it's sensible to use $row['Locale'] as our starting point.)
	$cleanstub = $trashedLocale;
	$cleanstub = str_replace(' ', '', $cleanstub); // Remove spaces
	$cleanstub = str_replace('&ntilde;', 'n', $cleanstub); // Replace n-tilde with a plain n (in Canon City_CO only)
	$cleanstub = str_replace('&#039;', '', $cleanstub); // Remove apostrophe special character (in Coeur d'Alene_ID only)
	$cleanstubArray = explode('_', $cleanstub); $cleanstub = $cleanstubArray[0];  // Take just the part of the string up to (excluding) the underscore.

	unlink($pagepathlong."mediator".$cleanstub.".shtml"); // Unlinking custom versions of the s_mediators.shtml files.
	unlink($pagepathlong."mediationcost".$cleanstub.".shtml"); // Unlinking custom versions of the s_fees.shtml files. 
	}

unset($TrashMediator);

echo '<p class=\'basictext\' style=\'margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px;\'>The deletion was successful. The '.$_SESSION['ClientDemoSelected'].' mediator of ID '.$_SESSION['selectedID'].' has been removed from the database.</p>';

}

/*
This section gets processed when the Administrator clicks the 'IssueEmailInstructions' button in admin5A.php. It assigns a new professional email address (ProfessionalEmail) and EmailPassword to the mediators_table for the selected mediator and sends an email to the mediator to tell him/her how to set up their email client software for receipt and sending of messages using their newly assigned professional email address. (The administrator will still need to manually create the actual email account (e.g. using cpanel) on the email server using the same email addres prefix ([e.g. jdoe] and Email Password as assigned in the admin5A.php administrator module.)
*/
if (isset($IssueEmailInstructions))
{

// Sanitize user-supplied field data.
if (!get_magic_quotes_gpc())
	{
	$ProfEmailPassword = addslashes($ProfEmailPassword);
	$ProfEmailName = addslashes($ProfEmailName);
	}

$ProfEmailName = htmlspecialchars($ProfEmailName);
$ProfEmailPassword = htmlspecialchars($ProfEmailPassword);

/* Do PHP form validation on ProfEmailName and ProfEmailPassword. */

// Create session variables to hold inline error messages, and initialize them to blank.
$_SESSION['MsgProfEmailName'] = null;
$_SESSION['MsgProfEmailPassword'] = null;

// Seek to validate $ProfEmailName.
$illegalCharSet = '/[,~\s!@#\$%\^&\*\(\)_\+`=\|\\:";<>\?]+/'; // Exclude everything except alphanumerics. (Note that the \s refers to space characters, including tabs.)
$reqdCharSet = '/\w/';  // Match any character in the range 0-9, A-Z and a-z (eqivalent to '/[0-9a-zA-Z]+/').
if (preg_match($illegalCharSet, $ProfEmailName) || !preg_match($reqdCharSet, $ProfEmailName))
	{
		$_SESSION['MsgProfEmailName'] = '<span class="errorphp"><br>Enter alphanumerics here. Use only letters [A-Z, a-z] and numbers [0-9].<br></span>';
	}
 
// Seek to validate that $ProfEmailName is unique. (No two mediators can have the same professional email address i.e. we can't have two johnsmith@newresolutionmediation.com. Do this by counting the number of rows in mediators_table (and mediators_table_demo) where SUBSTRING_INDEX(ProfessionalEmail, '@', 1) [a MySQL string function] equals $ProfEmailName. Note: if ProfessionalEmail is jdoe@newresolutionmediation.com then the prefix is "jdoe". Note that, despite my best efforts, I couldn't find a way to effect this count as a UNION across both the mediators_table and mediators_table_demo; consequently, I perform two separate queries and sum their results.
$query = "SELECT COUNT(*) FROM mediators_table WHERE SUBSTRING_INDEX(ProfessionalEmail, '@', 1) = '".$ProfEmailName."'";
$result = mysql_query($query) or die('Query (select count of professional email prefixes) failed: ' . mysql_error());
$row = mysql_fetch_row($result); // $row[0] array should be zero if the $ProfEmailName is unique within mediators_table i.e. no client mediator already has $ProfEmailName assigned to him/her.
$countClientTable = $row[0];

$query = "SELECT COUNT(*) FROM mediators_table_demo WHERE SUBSTRING_INDEX(ProfessionalEmail, '@', 1) = '".$ProfEmailName."'";
$result = mysql_query($query) or die('Query (select count of professional email prefixes) failed: ' . mysql_error());
$row = mysql_fetch_row($result); // $row[0] array should be zero if the $ProfEmailName is unique within meddiators_table_demo i.e. no demo mediator already has $ProfEmailName assigned to him/her.
$countDemoTable = $row[0];

$count = $countClientTable + $countDemoTable;

if ($count != 0) $_SESSION['MsgProfEmailName'] = '<span class="errorphp"><br>The email prefix <i>'.$ProfEmailName.'</i> is already assigned. Please enter a different address.<br></span>'; // Note: This error message, if invoked, will overwrite the validation error pertaining to non-alphanumerics within $ProfEmailName.

// Seek to validate $ProfEmailPassword.
$illegalCharSet = '/[,~\s!@#\$%\^&\*\(\)_\+`=\|\\:";<>\?]+/'; // Exclude everything except alphanumerics. (Note that the \s refers to space characters, including tabs.)
$reqdCharSet = '/\w/';  // Match any character in the range 0-9, A-Z and a-z (eqivalent to '/[0-9a-zA-Z]+/').
if (preg_match($illegalCharSet, $ProfEmailPassword) || !preg_match($reqdCharSet, $ProfEmailPassword))
	{
		$_SESSION['MsgProfEmailPassword'] = '<span class="errorphp"><br>Enter alphanumerics here. Use only letters [A-Z, a-z] and numbers [0-9].<br></span>';
	}

//Now go back to the previous page (admin5A.php) and show any PHP inline validation error messages if the $_SESSION['phpinvalidflag'] has been set to true. ... otherwise, proceed to update the database with the user's form data.
if (!is_null($_SESSION['MsgProfEmailName']) || !is_null($_SESSION['MsgProfEmailPassword']))
	{
	header("Location: admin5A.php#profemail"); // Go back to admin5A.php page.
	exit;	
	};

// End of PHP form validation

// Formulate the professional email address to be inserted into the mediators_table (clients) or mediators_table_demo (demos).
$ProfessionalEmail = $ProfEmailName.'@'.$ProfEmailDomain;

// Update the mediators_table or mediators_table_demo with the newly defined $ProfessionalEmail and the $ProfEmailPassword;
$query = "UPDATE ".$dbmediatorstablename." SET ProfessionalEmail = '".$ProfessionalEmail."', ProfEmailPassword = '".$ProfEmailPassword."' WHERE ID = '".$_SESSION['selectedID']."'";
$result = mysql_query($query) or die('Your attempt to update the ProfessionalEmail column has failed. Here is the query: '.$query.mysql_error());

// Issue an email to be sent to the mediator with instructions on how to configure his/her email client software in order to send and receive messages via this newly assigned professional email address...

// ... First, select the mediator's name, existing (i.e. non-professional) email, and new professional email address from the table.
$query = "SELECT Name, Email, ProfessionalEmail FROM ".$dbmediatorstablename." WHERE ID = '".$_SESSION['selectedID']."'";
$result = mysql_query($query) or die('Your attempt to select the Name and Email columns has failed. Here is the query: '.$query.mysql_error());
$row = mysql_fetch_assoc($result);

// ... Second, create the parts of the instructional email and send via PHP's PEAR extension library. Specifically, I'm using a Mail package that readily handles MIME and email attachments. In order to run it, I needed to first install Mail (see http://pear.php.net/manual/en/package.mail.mail.php) and Mail_mime (see http://pear.php.net/manual/en/package.mail.mail-mime.example.php) via cPanel's PEAR gateway, and then include() them (see below). 
require_once('Mail.php');
require_once('Mail/mime.php');

$message = "Hello ".html_entity_decode($row['Name'], ENT_QUOTES)."\n\nCongratulations on deciding to adopt a professional email address. It's a decision that will bring you an immediate credibility boost. Your new email address, which is protected by industry-strength SSL security, has been loaded onto our servers. Now you must configure your own email client so you can send and receive messages using your professional address.\n\n"; // The html_entity_decode() converts apostrophes and other special characters (stored in coded form [e.g. Flannigan O&#039; Driscoll] in the mediators_table) in names such as "Flannigan O'Driscoll".
$message .= "Your professional email address is: ".$row['ProfessionalEmail']."\n\n";
$message .= "In order to use this address for sending and receiving messages via your own email client software (e.g. Outlook Express, Windows Mail, Windows Live Mail, Apple Mail, iPhone Mail, Eudora, etc.), you first need to configure some settings. Follow these three steps:\n\n";
$message .= "Step 1: Configure your email client software according to the instructions in the PDF document attached to this message. In particular, you'll need to enter these settings:\n\n";
$message .= "        Email Address = ".$ProfessionalEmail."\n";
$message .= "        Email Username = ".$ProfEmailName."+".$ProfEmailDomain."\n";
$message .= "        Email Password = ".$ProfEmailPassword."\n";
$message .= "        Incoming Mail Server (POP3) = secure54.inmotionhosting.com\n";
$message .= "        Outgoing Mail Server (SMTP) = secure54.inmotionhosting.com\n";
$message .= "        Outgoing Mail Server Requires Authentication = yes\n";
$message .= "        Incoming Mail Server Port = 995 (or 993)\n";
$message .= "        Outgoing Mail Server Port = 465\n\n";
$message .= "Step 2: Having completed the configuration of Step 1, check that you can receive messages in your new email account by sending a test message from another account to your professional email address (or ask a friend to send you a message). Also test to ensure you can compose and send messages from your New Resolution Mediation email account.\n\n";
$message .= "Step 3: After testing your new email account in Step 2, don't forget to log into your mediator profile on the New Resolution Mediation Launch Platform. Once inside your profile, simply check the box labeled \"Use my assigned professional email address instead\" and click 'Submit Now'. Your professional email address will then appear as your email address on the New Resolution web site, and form submissions from prospective clients (for example, requests to schedule consultations) will be emailed to you using that address too.\n\n";
$message .= "If you have any difficulty, please contact our support desk:
support@newresolutionmediation.com\n\n";
$message .= "Sincerely\n
Paul R. Merlyn
President
New Resolution LLC";
$sendto = $row['Email'];
$file = '/home/paulme6/public_html/nrmedlic/attachments/Professional_Email_Instructions.pdf';
$crlf = "\n";
$hdrs = array(
              'From'    => 'support@newresolutionmediation.com',
              'Subject' => 'Professional Email Configuration Instructions',
			  'Bcc' => 'paul@newresolutionmediation.com'
              );

$mime = new Mail_mime($crlf);

$mime->setTXTBody($message);
//$mime->setHTMLBody($html);
$mime->addAttachment($file, $c_type='application/pdf');

//do not ever try to call these lines in reverse order
$body = $mime->get();
$hdrs = $mime->headers($hdrs);

if ($sendto != '' && $sendto != null) 
	{ 
	$mail =& Mail::factory('mail');
	$mail->send("$sendto", $hdrs, $body);
	echo '<p class=\'basictext\' style=\'margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px;\'>An email message has been sent to '.$row['Email'].' with instructions on setting up the mediator&rsquo;s client software for his/her new professional address:<br><br>'.$row['ProfessionalEmail'].'.</p>';
	}
else
	{
	echo '<p class=\'basictext\' style=\'margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px;\'>This mediator has not yet entered an email address in his/her profile. No instructions could therefore be sent via email to this mediator.</p>';
	}

unset($IssueEmailInstructions);
}

if (isset($AssignSEOlocations)) // User clicked the 'Assign SEO Locations' button to insert a value into the SEOlocations field in mediators_table.
	{
	// Update the value of $SEOlocations in the mediators_table table.
	if (!get_magic_quotes_gpc())
		{
		$SEOlocations = addslashes($SEOlocations);
		}
	$query = "UPDATE ".$dbmediatorstablename." SET SEOlocations = '".$SEOlocations."' WHERE ID = '".$_SESSION['selectedID']."'";

	$result = mysql_query($query) or die('Query (update SEOlocations column) failed: ' . mysql_error());
	if (!$result) {	echo 'The new SEOlocations value could not be assigned in the database.'; };
	unset($AssignSEOlocations);
	};
?>

<div style="text-align: center"> <!-- This div provides centering for older browsers incl. NS4 and IE5. (See http://theodorakis.net/tablecentertest.html#intro.) Use of margin-left: auto and margin-right: auto in the style of the table itself (see below) takes care of centering in newer browsers. -->
<form method="post" action="unwind.php">
<table cellpadding="0" cellspacing="0" style="margin-top: 50px; margin-left: auto; margin-right: auto; position: relative; left: -7px;">
<tr>
<td style="text-align: left;">
<input type='button' name='Continue' class='buttonstyle' style="text-align: center;" value='Continue' onclick='javascript: window.location = "admin5.php";'> <!-- This is not a submit button and functions independenly of the action='unwind.php' form -->
</td>
<td width="50">&nbsp;</td>
<td width="120" style="text-align: left;">
<input type="submit" name="Logout" class="buttonstyle" style="text-align: center;" value="Log Out">
</td>
</tr>
</table>
</form>
</div>

</body>
</html>