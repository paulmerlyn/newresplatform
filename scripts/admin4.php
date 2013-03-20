<?php
/* 
This script is the slave processor for admin3.php, which allws the administrator to add a new locale to the locales_table. 
*/
// Start a session
session_start();
ob_start(); // Used in conjunction with ob_flush() [see www.tutorialized.com/view/tutorial/PHP-redirect-page/27316], this allows me to postpone issuing output to the screen until after the header has been sent.
if ($_SESSION['AntiReloadFlag'] == 'true') exit; // First set to 'false' in admin3.php. Used to prevent a user-forced reload (i.e. user hits F5 key) of admin2.php, which would resubmit the form data each time if this anti-reload device were not employed.
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Processing Slave Script for admin3.php</title>
<link href="/nrcss.css" rel="stylesheet" type="text/css">
</head>

<body>
<h1 style="margin-left:100px; margin-top: 30px; font-size: 22px; padding: 0px; color: #425A8D;">New Resolution Mediation Launch Platform&trade; Administrator</h1>

<?php
// Create short variable names
$LocaleShort = $_POST['LocaleShort']; // Typed in text field by administrator in admin1.php.
$LocaleStates = $_POST['LocaleStates']; // Potentially multiple states selected by administrator from drop-down in admin1.php.
$Population = $_POST['Population']; 
$MaxLicenses = $_POST['MaxLicensesHidden']; // User may have overridden the default (calculated) maximum.
$OverrideMaxLic = $_POST['OverrideMaxLic'];
$AddNewLocale = $_POST['AddNewLocale'];

$WaitlistState = $_POST['WaitlistState'];
$WaitlistLocale = $_POST['WaitlistLocale'];
$WithPhoneNos = $_POST['WithPhoneNos'];
$WithNames = $_POST['WithNames'];
$LicExpPast = $_POST['LicExpPast'];
$LicExpFuture = $_POST['LicExpFuture'];
$RetrieveWaitlisters = $_POST['RetrieveWaitlisters'];

$DeleteCheckbox = $_POST['DeleteCheckbox']; // This is an array because the corresponding form element's name on admin3.php is 'DeleteCheckbox[]'.
$DeleteWaitlisters = $_POST['DeleteWaitlisters'];

// Connect to mysql and select database
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
or die('Could not connect: ' . mysql_error());
mysql_select_db('paulme6_newresolution') or die('Could not select database');

if (isset($AddNewLocale))
{

	if (!get_magic_quotes_gpc())
	{
		$LocaleShort = addslashes($LocaleShort);
	
		$Population = addslashes($Population);
		$MaxLicenses = addslashes($MaxLicenses);
	}
	
	/* Do PHP form validation. */

	// Create a session variable for the PHP form validation flag, and initialize it to 'false' i.e. assume it's valid.
	$_SESSION['phpinvalidflag'] = false;

	// Create session variables to hold inline error messages, and initialize them to blank.
	$_SESSION['MsgLocaleShort'] = null;
	$_SESSION['MsgPopulation'] = null;
	$_SESSION['MsgMaxLicenses'] = null;

	// Seek to validate $LocaleShort
	$illegalCharSet = '/[0-9,~!@#\$%\^&\*\(\)_\+`=\|\\:";<>\?]+/'; // Exclude everything except letters, hyphen, apostrophe, and space character. (Note that the caret ^ inside square brackets applies to exclude only the first character in PHP's implementation of regular expressions, whereas it applies to any character in Javascript's implementation of regular expressions. Hence my different approach in defining the regular expression for illegalCharSet in PHP.)
	$reqdCharSet = '/[a-zA-Z]{2}/';  // At least two letters.
	if (preg_match($illegalCharSet, $LocaleShort) || !preg_match($reqdCharSet, $LocaleShort))
		{
			$_SESSION['MsgLocaleShort'] = '<span class="errorphp">Enter alphabetic characters. Use only letters, apostrophes (\'), and hyphens (-).<br></span>';
			$_SESSION['phpinvalidflag'] = true; 
		}

	// Seek to validate $LocaleStates by simply checking that a selection has been made from the menu.
	if ($LocaleStates == '' || $LocaleStates == null)
		{
		$_SESSION['MsgLocaleStates'] = '<span class="errorphp">Please select one or more states that associate with your locale.<br></span>';
		$_SESSION['phpinvalidflag'] = true; 
		}

	// Seek to validate $Population
	$illegalCharSet = '/[^0-9]+/'; // Reject everything that contains one or more characters that is not a digit.
	$reqdCharSet = '/[0-9]{3}/';  // At least three digits.
	if (preg_match($illegalCharSet, $Population) || !preg_match($reqdCharSet, $Population))
		{
			$_SESSION['MsgPopulation'] = '<span class="errorphp">Enter a number here. Use only digits [0-9].<br></span>';
			$_SESSION['phpinvalidflag'] = true; 
		}

	// Seek to validate $MaxLicenses
	$illegalCharSet = '/[^0-9]+/'; // Reject everything that contains one or more characters that is not a digit.
	$reqdCharSet = '/[1-9]+/';  // At least one non-zero numeric.
	if ($OverrideMaxLic) // Don't bother to validate when the OverrideMaxLic check-box isn't checked.
		{
		if (preg_match($illegalCharSet, $MaxLicenses) || !preg_match($reqdCharSet, $MaxLicenses))
			{
				$_SESSION['MsgMaxLicenses'] = '<span class="errorphp"><br>Enter the maximum number of licenses here. (Must be greater than zero.)<br></span>';
				$_SESSION['phpinvalidflag'] = true; 
			}
		}
	
	//Now go back to the previous page (admin3.php) and show any PHP inline validation error messages if the $_SESSION['phpinvalidflag'] has been set to true. ... otherwise, proceed to update the database with the user's form data.
	if ($_SESSION['phpinvalidflag'])
		{
		header("Location: admin3.php"); // Go back to admin3.php page.
		exit;	
		};

	// End of PHP form validation

	if (!isset($AddNewLocale)) exit; else unset($AddNewLocale); // If user tries to run admin4.php directly rather than via admin3.php, terminate admin4.php.

	/* Manipulate the value of the new locale (entered by the user as $LocaleShort) that will be entered into the locales_table. */
	// First obtain the state(s) with which that locale associates then append them as a suffix e.g. "_CT-NJ-NY-PA"
$multistatesuffix = implode('-', $LocaleStates);
	// Append the suffix to the $LocaleShort to comprise $Locale
	$Locale = $LocaleShort.'_'.$multistatesuffix;
	$Locale = htmlspecialchars($Locale, ENT_QUOTES); // Prevent cross-site scripting. Note that the ENT_QUOTES parameter is necessary to handle locales with apostrophes e.g. Isle d'Eglise. 
	$LocaleShort = htmlspecialchars($LocaleShort, ENT_QUOTES); // Prevent cross-site scripting. Note that the ENT_QUOTES parameter is necessary to handle locales with apostrophes e.g. Isle d'Eglise. 

	// Redefine (i.e. convert) LocaleStates from its original array form to the string $multistatesuffix
	$LocaleStates = $multistatesuffix;

	// Prevent cross-site scripting on $Population and $MaxLicenses.
	$Population = htmlspecialchars($Population);
	$MaxLicenses = htmlspecialchars($MaxLicenses);

	// Initialize $Exclusive, $Full, and $NofLicenses to zero b/c they are zero when the new locale is first created.
	$Exclusive = 0;
	$Full = 0;
	$NofLicenses = 0;

	// Note that LocaleShort, MaxLicenses, Population are entered into the locales_table as $LocaleShort, $MaxLicenses, and $Population from their user-submitted form values.

	// Connect to mysql and select database
	$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
	or die('Could not connect: ' . mysql_error());
	mysql_select_db('paulme6_newresolution') or die('Could not select database');

	// Formulate query to insert into database the new locale's Locale,	LocaleShort, Exclusive,	LocaleStates, Population, MaxLicenses, and NofLicenses. 
	$query = "insert into locales_table".
	" set Locale = '$Locale', LocaleShort = '$LocaleShort', Exclusive = '$Exclusive', LocaleStates = '$LocaleStates', Population = '$Population', MaxLicenses = '$MaxLicenses', NofLicenses = '$NofLicenses'";

	$result = mysql_query($query) or die('Your attempt to insert new locale details into the locales_table table failed. ' . mysql_error());
	echo '<p class=\'basictext\' style=\'margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px;\'>Mission accomplished! A new locale has been created.</p>';
	?>

	<form method="post" action="unwind.php">
	<table width="530" cellpadding="0" cellspacing="0" style="margin-top: 50px; margin-left: 100px;">
	<tr>
	<td style="text-align: right;">
	<input type='button' name='Continue' class='buttonstyle' value='Continue' onclick='javascript: window.location = "admin3.php";'> <!-- This is not a submit button and functions independenly of the action='unwind.php' form -->
	</td>
	<td width="50">&nbsp;</td>
	<td style="text-align: left;">
	<input type="submit" name="Logout" class="buttonstyle" value="Log Out">
	</td>
	</tr>
	</table>
	</form>

	<?php 
	// Closing connection
	mysql_close($db);

	$_SESSION['AntiReloadFlag'] = 'true';

}

if (isset($WaitlistLocale) && !isset($RetrieveWaitlisters) && !isset($DeleteWaitlisters))
	{
	$_SESSION['WaitlistLocaleSelected'] = $WaitlistLocale;  // Store the selected state of the WaitlistLocale drop-down menu in session variable $_SESSION['WaitlistLocaleSelected'] for use in waitlist.php. Specifically, this session variable is used to ensure that the Locale drop-down menu resets to its previously selected value on returning to admin3.php.
	unset($WaitlistLocale);
	};

if (isset($WaitlistState) && !isset($RetrieveWaitlisters) && !isset($DeleteWaitlisters))
	{
	$_SESSION['WaitlistStateSelected'] = $WaitlistState;  // Store the selected state of the WaitlistState drop-down menu in session variable $_SESSION['WaitlistStateSelected'] for use in waitlist.php in populating the relevant locales for that state in the locales drop-down menu when a mediator wants to provide his/her name/phone/email for a locale that is already taken.
	if ($WaitlistState == '' || $WaitlistState == null) $_SESSION['WaitlistLocaleSelected'] = '';
	unset($WaitlistState);
	};
	
if (!isset($RetrieveWaitlisters) && !isset($DeleteWaitlisters)) // Now go back to admin3.php via either the HTTP_REFERER or via javascript history.back in situations where either the state or the locale drop-down menu got changed (but the 'Retrieve Mediators' submit button wasn't clicked).
	{
	?>
	<script language="javascript" type="text/javascript">
	<!--
	window.location = 'admin3.php#waitlistretrieval';
	// -->
	</script>
	<noscript>
	<?php
	ob_flush();
	if (isset($_SERVER['HTTP_REFERER'])) // Antivirus software sometimes blocks transmission of HTTP_REFERER.
		{
		header("Location: /admin3.php#waitlistretrieval"); // Go back to previous page. (Alternative to echoing the Javascript statement: history.go(-1) or history.back() in cases where user has Javascript disabled.
		}
	?>
	</noscript>
	<?php
	}

if (isset($RetrieveWaitlisters))
	{
	/* This code block defines the query to retireve all waitlisters who conform to the user's search critera as specified in admin3.php. Note there's no need for any data validation or anti-cross-site scripting countermeasures because the user input is via drop-down menus and check-boxes only. */
	
	// Connect to mysql and select database.
	$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
	or die('Could not connect: ' . mysql_error());
	mysql_select_db('paulme6_newresolution') or die('Could not select database');
	
	// Formulate DB query to retrieve details of all waitlisters of interest from the waitlist_table together with details of license expiration date for the locale of interest to each waitlister. In the case of a locale that is currently available (and that supports more than one licensee), the license expiration date will be the most recent date that a licensee decided not to renew his/her license. In the case of a locale that is currently full (i.e. unavailable) (and that supports more than one licensee), the license expiration date will be the earliest date at which one of the existing licensees' licenses is due for renewal (and hence possible expiration).
	$query = "SELECT ";
	if ($LicExpPast != 'anytime' && $LicExpFuture != 'anytime') $query .= "DISTINCT "; // There's a possibility that the result set would contain duplicate rows by virtue of the equi-join (i.e. the 'mediators_table.Locale = waitlist_table.Locale' clause below) established between waitlist_table and mediators_table when either the $LicExpPast drop-down menu or the $LicExpFuture drop-down menu are not in their neutral (anytime) positions.
	$query .= " waitlist_table.Locale, waitlist_table.WaitlisterName, waitlist_table.WaitlisterTelephone, waitlist_table.WaitlisterEmail ";
	$query .= "FROM waitlist_table";
	if ($LicExpPast != 'anytime' && $LicExpFuture != 'anytime') $query .= ", mediators_table"; // There's no need for a join to the mediators_table if the user isn't interested in limiting the retrieval by mediators' license Expiration Dates.
	$query .= " WHERE ";
	if (($WaitlistState == 'allstates') && ($WaitlistLocale == 'alllocales')) // WaitlistState and WaitlistLocale drop-down menus are in the neutral (i.e. all states, all locales) position so match the query to any locale i.e. * wild card .
		{
		$query .= "waitlist_table.Locale LIKE '%' "; // i.e. Essentially, no 'where' clause is necessary here, but I match to wildcard * to simplify the structure and logic of this multipart $query formulation.
		}
	else if ($WaitlistState != 'allstates' && $WaitlistLocale != 'alllocales') // Neither the WaitlistState nor the WaitlistLocale drop-down menus are in the neutral position, so the user has selected a particular locale.
		{
		$query .= "waitlist_table.Locale = '".$WaitlistLocale."' ";
		$_SESSION['WaitlistLocaleSelected'] = $WaitlistLocale; // Used for presetting the 'Locale of Interest' drop-down menu on admin3.php.
		}
	else // The WaitlistState is non-neutral (i.e. a particular state has been selected) but the WaitlistLocale is neutral (i.e. it's set at 'All Locales').
		{
		$query .= "(waitlist_table.Locale LIKE BINARY '%\_".$WaitlistState."%' || waitlist_table.Locale LIKE BINARY '%-".$WaitlistState."%') "; // Note the need to escape the literal underscore to distinguish it from a single-character wildcard (ref. http://www.htmlite.com/mysql011.php). Also note the use of 'LIKE BINARY' to effect case-sensitive comparisons (necessary to avoid confusion between the '_AK' of Alaska and the 'ak' in 'San Francisco-Oakland-Fremont_CA' (ref. http://www.delphifaq.com/faq/databases/mysql/f801.shtml).
		};

	if ($WithPhoneNos == 1)
		{
		$query .= "AND waitlist_table.WaitlisterTelephone != '' ";
		$_SESSION['WithPhoneNos'] = 1; // Set this session variable to true to preset the 'WithPhoneNos' check-box as checked when admin3.php is reloaded.
		}
	else
		{
		$query .= "AND waitlist_table.WaitlisterTelephone LIKE '%' "; // Essentially, no 'where' clause is necessary here, but I match to wildcard * to simplify the structure and logic of this multipart $query formulation.
		};
	
	if ($WithNames == 1)
		{
		$query .= "AND waitlist_table.WaitlisterName != '' ";
		$_SESSION['WithNames'] = 1; // Set this session variable to true to preset the 'WithNames' check-box as checked when admin3.php is reloaded.
		}
	else
		{
		$query .= "AND waitlist_table.WaitlisterName  LIKE '%' "; // Essentially, no 'where' clause is necessary here, but I match to wildcard * to simplify the structure and logic of this multipart $query formulation.
		};
		
	$_SESSION['LicExpPast'] = $LicExpPast; // Session variable is set to ensure LicExpPast drop-down menu resets to its previous value when admin3.php reloads.
	$_SESSION['LicExpFuture'] = $LicExpFuture; // Session variable is set to ensure LicExpFuture drop-down menu resets to its previous value when admin3.php reloads.
	if ($LicExpPast == 'anytime' && $LicExpFuture == 'anytime')
		{
		$query .= " "; // No 'where' clause is necessary here. I just include this pointless line of code for clarity.
		}
	else if ($LicExpPast != 'anytime' && $LicExpPast != '' && $LicExpPast != null) // Remember, $LicExpPast will post as null or '' if the LicExpPast drop-down menu is disabled in admin3.php.
		{
		// Extract $intervalnumber and $intervalunit from $LicExpPast using explode().
		$LicExpPast = explode('_', $LicExpPast);
		$intervalnumber = $LicExpPast[0]; // e.g. For a post array $LicExpPast of '3_MONTH', then $intervalnumber is '3'.
		$intervalunit = $LicExpPast[1]; // e.g. For a post array $LicExpPast of '3_MONTH', then $intervalunit is 'MONTH'.
		$query .= "AND mediators_table.Locale = waitlist_table.Locale AND mediators_table.AdminFreeze != 1 AND mediators_table.ExpirationDate < CURDATE() AND mediators_table.ExpirationDate > DATE_SUB(CURDATE(), INTERVAL $intervalnumber $intervalunit) "; // Note: there's only need to join the waitlist_table to the mediators_table if the Administrator sets one of the License Expiration drop-down menus in admin3.php to a non-neutral (i.e. not 'anytime') value. When they are both neutral, the query should simply retrieve rows from the waitlist_table without the dual condition that the waitlister's Locale appear in the mediators_table and the mediator's ExpirationDate conform to the value specified in the drop-down menu. (Also, only join with mediators in mediators_table who aren't decommissioned i.e. AdminFreeze isn't 1.)
		}
	else if ($LicExpFuture != 'anytime' && $LicExpFuture != '' && $LicExpFuture != null)
		{
		// Extract $intervalnumber and $intervalunit from $LicExpFuture using explode().
		$LicExpFuture = explode('_', $LicExpFuture);
		$intervalnumber = $LicExpFuture[0]; // e.g. For post array $LicExpFuture of '3_MONTH', $intervalnumber is '3'.
		$intervalunit = $LicExpFuture[1]; // e.g. For a post array $LicExpFuture of '3_MONTH', $intervalunit is 'MONTH'.
		$query .= "AND mediators_table.Locale = waitlist_table.Locale AND mediators_table.AdminFreeze != 1 AND mediators_table.ExpirationDate > CURDATE() AND mediators_table.ExpirationDate < DATE_ADD(CURDATE(), INTERVAL $intervalnumber $intervalunit) ";
		};
	unset($RetrieveWaitlisters);
	$_SESSION['RetrieveWaitlistersReturn'] = true; // This session variable is used on admin3.php to indicate that admin4.php has sent back a waitlisters query so admin3.php is able to (process the query then) display the waitlisters.
	$_SESSION['WaitlisterRetrievalQuery'] = $query; // Store the query inside session variable $_SESSION['WaitlisterRetrievalQuery'] for easy access on return to admin3.php.
	
	// Now go back to admin3.php where I actually execute the query and display the retrieved waitlisters.
	?>
	<script language="javascript" type="text/javascript">
	<!--
	window.location = 'admin3.php';
	// -->
	</script>
	<noscript>
	<?php
	if (isset($_SERVER['HTTP_REFERER'])) // Antivirus software sometimes blocks transmission of HTTP_REFERER.
		{
		header("Location: admin3.php"); // Go back to previous page. (Alternative to echoing the Javascript statement: history.go(-1) or history.back() in cases where user has Javascript disabled.
		}
	ob_flush();
	?>
	</noscript>
	<?php
	} // End of isset($RetrieveWaitlisters)

if (isset($DeleteWaitlisters))
	{
	unset($DeleteWaitlisters);
	// Loop through the $DeleteCheckbox (an array, each element of which is the email address of a waitlister whose checkbox on admin3.php was checked by the Administrator to indicate he/she wants to delete the waitlister who has that email address from the waitlist_table.
	foreach ($DeleteCheckbox as $item)
		{
		$query = "DELETE FROM waitlist_table WHERE WaitlisterEmail = '".$item."'";
		$result = mysql_query($query) or die('Your attempt to delete a waitlister from waitlist_table has failed. Here is the query: '.$query.mysql_error());
		}
	
		echo '<p class=\'basictext\' style=\'margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px;\'>Mission accomplished! The selected wait-lister(s) have been deleted from the database.</p>';
	?>

	<form method="post" action="unwind.php">
	<table width="530" cellpadding="0" cellspacing="0" style="margin-top: 50px; margin-left: 100px;">
	<tr>
	<td style="text-align: right;">
	<input type='button' name='Continue' class='buttonstyle' value='Continue' onclick='javascript: window.location = "admin3.php";'> <!-- This is not a submit button and functions independenly of the action='unwind.php' form -->
	</td>
	<td width="50">&nbsp;</td>
	<td style="text-align: left;">
	<input type="submit" name="Logout" class="buttonstyle" value="Log Out">
	</td>
	</tr>
	</table>
	</form>
	
	<?php
	} // End of isset ($DeleteWaitlister)
?>
</body>
</html>