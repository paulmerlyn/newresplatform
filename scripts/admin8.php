<?php
/*
admin7.php allows an administrator (user) to update platform parameters such as the license fee and license term value-pairs being offered to new mediators and the default APR at which a mediator's license fee will increase unless an administrator manually intercedes. These values are stored in the DB's parameters_table. admin8.php is a slave script to process updates when the user clicks the 'Update Parameters' button.
*/
// Start a session
session_start();
ob_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>New Resolution Mediation Launch Platform&trade; Administrator</title>
<link href="/nrcss.css" rel="stylesheet" type="text/css">
</head>

<body>
<h1 style="margin-left:100px; margin-top: 30px; font-size: 22px; padding: 0px; color: #425A8D;">New Resolution Mediation Launch Platform&trade; Administrator</h1>
<?php
// Create short variable names.
$Authentication = $_POST['Authentication'];
$DefaultAPR = $_POST['DefaultAPR'];
$MarginNow = $_POST['MarginNow'];

// Sanitize user-supplied field data.
$DefaultAPR = htmlspecialchars($DefaultAPR);
$MarginNow = htmlspecialchars($MarginNow);

//The tricky part is that I don't know in advance how many license fee-term term value-pairs were entered by the administrator on clicking the 'Update Parameters' submit button in admin7.php. But I can determine the number dynamically as follows. I use array_key_exists() function as the condition of a while loop to control the establishment of short variable names such as $LicFeeForTerm1, $LicFeeForTerm2, $LicFeeForTerm3, $TermNumber1, $TermNumber2, $TermNumber3, etc. for exactly the number $i items, where $i is incremented inside the while loop. 
$i = 1;
while (array_key_exists('LicFeeForTerm'.$i, $_POST)) // If the $_POST array contains, say, a 'LicFeeForTerm4' but no 'LicFeeForTerm5', the loop will end.
{
	$myLicFeeForTermString = 'LicFeeForTerm'.$i; // e.g. variously 'LicFeeForTerm1', 'LicFeeForTerm2', etc. as the loop iterates
	$myTermNumberString = 'TermNumber'.$i; // e.g. variously 'TermNumber1', 'TermNumber2', etc. as the loop iterates
	$myTermUnitString = 'TermUnit'.$i;  // e.g. variously 'TermUnit1', 'TermUnit2', etc. as the loop iterates
	$LicFeeForTerm[$i] = $_POST["$myLicFeeForTermString"];
	$LicFeeForTerm[$i] = htmlspecialchars($LicFeeForTerm[$i]); // Sanitize user-supplied field data. (Note: No need to sanitize the $TermNumber[$i] or $TermUnit[$i] b/c their values are controlled from a select menu, so user can't enter anything nonsensical/invalid.)
	$TermNumber[$i] = $_POST["$myTermNumberString"];
	$TermUnit[$i] = $_POST["$myTermUnitString"];
	$i++;
}
// On exiting the while loop, $i's value will be one greater than the number of license fee-term value pairs that have been defined. Assign this to the variable $NofPairs for simplicity/readability
$NofPairs = $i - 1;

if  (empty($Authentication) && $_SESSION['Authenticated'] != 'true')
	{
	// Visitor is not authenticated so exit the script (for a visitor who tried to go directly to this admin8.php).
	exit;
	}
else
	{
	// See if the password entered by the user in the POST array is correct. (Note that PHP comparisons are case-sensitive [unlike MySQL query matches] and sha1 returns a lower-case result.) If it is correct or if the $_SESSION['Authenticated'] session variable was set for a previously established authentication, proceed to show either the client vs demo selection form or proceed straight to the main screen.
	if ((sha1($Authentication) == 'dc6a59aab127063fd353585bf716c7f7c34d2aa0') || $_SESSION['Authenticated'] == 'true')
		{
		$_SESSION['Authenticated'] = 'true';
		// Script user is authenticated so now perform the slave function of updating the DB's parameters_table table with values entered in admin7.php forms.
		
		/* First do PHP form validation. */

		// Create a session variable for the PHP form validation flag, and initialize it to 'false' i.e. assume it's valid.
		$_SESSION['phpinvalidflag'] = false;

		// Create session variables to hold inline error messages, and initialize them to blank.
		$_SESSION['MsgMarginNow'] = null;
		$_SESSION['MsgDefaultAPR'] = null;
		$_SESSION['MsgLicFeeForTerm'] = null;
		$_SESSION['MsgTermNumber'] = null;
		$_SESSION['MsgTermUnit'] = null;

		// Seek to validate $MarginNow
		$illegalCharSet = '/[^0-9\.]+/'; // Reject everything that contains one or more characters that is neither a period nor a digit.
		$reqdCharSet = '/[0-9]+/';  // At least one numeric.
		if (preg_match($illegalCharSet, $MarginNow) || !preg_match($reqdCharSet, $MarginNow))
			{
				$_SESSION['MsgMarginNow'] = '<span class="errorphp">Please enter a numeric value here. You may use only numbers [0-9] and the decimal point (.) character.<br></span>';
				$_SESSION['phpinvalidflag'] = true; 
			}

		// Seek to validate $DefaultAPR
		$illegalCharSet = '/[^0-9\.]+/'; // Reject everything that contains one or more characters that is neither a period nor a digit.
		$reqdCharSet = '/[0-9]+/';  // At least one numeric.
		if (preg_match($illegalCharSet, $DefaultAPR) || !preg_match($reqdCharSet, $DefaultAPR))
			{
				$_SESSION['MsgDefaultAPR'] = '<span class="errorphp">Please enter a numeric value here. You may use only numbers [0-9] and the decimal point (.) character.<br></span>';
				$_SESSION['phpinvalidflag'] = true; 
			}

		// Seek to validate $LicFeeForTerm[$i] for all $i indices.
		$illegalCharSet = '/[^0-9\.]+/'; // Reject everything that contains one or more characters that is neither a period nor a digit.
		for ($i=1; $i <= $NofPairs;  $i++)
		{
			if (preg_match($illegalCharSet, $LicFeeForTerm[$i]))
			{
				$_SESSION['MsgLicFeeForTerm'] = '<span class="errorphp"><br>Please enter a numeric license fee. You may use only numbers [0-9] and the decimal point (.) character.<br></span>';
				$_SESSION['phpinvalidflag'] = true; 
			}
		}

		// Seek to validate $TermNumber[$i] for all $i indices.
		for ($i=1; $i <= $NofPairs;  $i++)
		{
			if ($LicFeeForTerm[$i] != "")
			{
				if ($TermNumber[$i] == "")
				{
				$_SESSION['MsgTermNumber'] = '<span class="errorphp"><br>Please select a value from the drop-down menu for the duration of the license term.<br></span>';
				$_SESSION['phpinvalidflag'] = true; 
				}
			}
		}

		// Seek to validate $TermUnit[$i] for all $i indices.
		for ($i=1; $i <= $NofPairs;  $i++)
		{
			if ($LicFeeForTerm[$i] != "")
			{
				if ($TermUnit[$i] == "")
				{
				$_SESSION['MsgTermUnit'] = '<span class="errorphp"><br>Please select a value from the drop-down menu for the units (months, years, etc.) of the license term.<br></span>';
				$_SESSION['phpinvalidflag'] = true; 
				}
			}
		}

		//Now go back to the previous page (updateprofile.php) and show any PHP inline validation error messages if the $_SESSION['phpinvalidflag'] has been set to true. ... otherwise, proceed to update the database with the user's form data.
//Note: Remember that output buffering (ob_start()) has been turned on, so only headers are sent until the code encounters either an ob_flush() or an ob_end_flush() statement, at which point the contents of the output buffer are output to the screen. Also note that ob_flush() differs from ob_end_flush in that the latter not only flushes but also turns off output buffering (i.e. cancels a prior ob_start() statement). Final note: the output buffer is always flushed at the end of the page (e.g. at the bottom of this processprofile.php script), even if there isn't an ob_flush() or ob_end_flush() statement.
		if ($_SESSION['phpinvalidflag'])
			{
			header("Location: admin7.php"); // Go back to admin7.php page.
			exit;	
			};

		// End of PHP form validation

		// Connect to mysql and select database
		$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
		or die('Could not connect: ' . mysql_error());
		mysql_select_db('paulme6_newresolution') or die('Could not select database');

/*
Combine $LicFeeForTerm[1], $LicFeeForTerm[2], $LicFeeForTerm[3], etc, and $TermNumber[1], $TermNumber[2], $TermNumber[3], etc. and $TermUnit[1], $TermUnit[2], TermUnit[3], etc. into the following string format prior to placement in the database: LicenseFeeForTerm[1], Term 1 in months, LicenseFeeForTerm[2], Term 2 in Months, LicenseFeeForTerm[3], Term 3 in Months, etc. For example, FeeTerm contains 220,1,595,3,2000,18,3175,24 i.e. a one-month term for $220, a 3-month term for $595, an 18-month term for $2000, and a 24-month term for $3175. 
*/

		// First perform necessary conversion of TermNumber on the basis of its associated TermUnit to achieve this string format.
		for ($i=1; $i <= $NofPairs; $i++)
		{
			switch ($TermUnit[$i])
				{
				case 'quarter':
					$TermNumber[$i] = (int)$TermNumber[$i] * 3;  // Multiply a type-cast version of $TermNumber[$i] by 3 to convert quarter to months.
					break;
				case 'years':
					$TermNumber[$i] = (int)$TermNumber[$i] * 12;  // Multiply a type-cast version of $TermNumber[$i] by 12 to convert yrs to mths.
					break;
				default:
					// No need for any action. The value of $TermNumber[$i] must be already denominated in 'months' via the drop-down units menu in admin7.php, so no conversion is necessary.
					break;
				};
		}
		
		// It's possible that user entered license fee values and terms in a non-ascending order on admin7.php (e.g. an entry of 1 year, one quarter, 2 years rather than the correct order of one quarter, 1 year, 2 years) . So sort the $TermNumber[$i] and the associated $LicFeeForTerm[$i] arrays into ascending order of $TermNumber[$i]. I use a bubble sort below.
		do
		{
		$swapreqdflag = false;
			for ($i=1; $i < $NofPairs; $i++)
			{
				if ($TermNumber[$i] > $TermNumber[$i+1]) // Test to see if a swap of the nth and (n+1)th values in the array is appropriate
				{
					$tempTermNumber = $TermNumber[$i];  // Temporary short-term storage to manage swap assignment
					$tempLicFeeForTerm = $LicFeeForTerm[$i];  // Temporary short-term storage to manage swap assignment
					$TermNumber[$i] = $TermNumber[$i+1];
					$LicFeeForTerm[$i] = $LicFeeForTerm[$i+1];
					$TermNumber[$i+1] = $tempTermNumber;
					$LicFeeForTerm[$i+1] = $tempLicFeeForTerm;
					$swapreqdflag = true; // A swap was required, so set the flag to true.
				}
			}
		}
		while ($swapreqdflag == true);
		
		// For all non-empty $LicFeeForTerm entries, build up a $FeeTerm string comprising all license fee-license term (in months) value-pairs, which were presorted into ascending order of TermNumber (in months) just above.
		$FeeTerm = ''; // This string will get built up in the following for loop and ultimately assigned to the FeeTerm column in the DB.
		for ($i=1; $i <= $NofPairs; $i++)
		{
		if (strstr($LicFeeForTerm[$i],'.') != false) // First, if LicFeeForTerm[$i] already contains a decimal point (because the Administrator included one when filling out form in admin7.php) then format to two decimal places (and no comma between thousands). If there is no decimal point, then leave formatting unchanged.
			{
			$LicFeeForTerm[$i] = number_format($LicFeeForTerm[$i],2,'.','');
			}
		if ($LicFeeForTerm[$i] != '') $FeeTerm .= $LicFeeForTerm[$i].','.$TermNumber[$i].',';
		}

		// Remove that last comma from $FeeTerm
		$FeeTerm = rtrim($FeeTerm, ',');

		// Formulate query to update the parameters_table.
		$query = "update parameters_table".
		" set MarginNow = '$MarginNow', DefaultAPR = '$DefaultAPR', FeeTerm = '$FeeTerm'";
		$result = mysql_query($query) or die('Unable to update the parameters_table. ' . mysql_error());

		echo '<p class=\'basictext\' style=\'margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px;\'>Mission accomplished! The database has now been updated with the specified parameter values.';
		?>		
		<form method="post" action="unwind.php">
		<table width="530" cellpadding="0" cellspacing="0" style="margin-top: 50px; margin-left: 100px;">
		<tr>
		<td style="text-align: right;">
		<input type='button' name='Continue' class='buttonstyle' value='Continue' onclick='javascript: window.location = "admin7.php";'> <!-- This is not a submit button and functions independenly of the action='unwind.php' form -->
		</td>
		<td width="50">&nbsp;</td>
		<td style="text-align: left;">
		<input type="submit" name="Logout" class="buttonstyle" value="Log Out">
		</td>
		</tr>
		</table>
		</form>
		<?php
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
// Free resultset
//mysql_free_result($result);  // Commented this out b/c it produced a PHP Warning. Inmotion tech support suggested it's b/c the $query resource is from an insert statement and is therefore not valid for mysql_free_result() memory freeing. If $query had been a select statement instead of an insert statement, mysql_free_result($result) would not produce a warning. 

// Closing connection
mysql_close($db);
ob_flush();
	}
	?>	
</body>
</html>