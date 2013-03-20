<?php
/*
admin12.php is the slave script for admin11.php. The latter allows an administrator (user) to enter details of a prospect into the DB's prospects_table, including the designation of an appropriate category (e.g. "Experienced Attorney", "Newbie Mediator with Minimal Site", etc.) for use in sending an email message targeted appropriately. The slave script inserts the prospect data into the prospects_table and, when desired, actually sends a marketing email to the prospect via PHP's mail utility.
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
$Title = $_POST['Title'];
$FirstName = $_POST['FirstName'];
$LastName = $_POST['LastName'];
$UseFormalTitle = $_POST['UseFormalTitle'];
$ProspectEmail = $_POST['ProspectEmail'];
$AreaLabel = $_POST['AreaLabel'];
$State = $_POST['State'];
$SendNow = $_POST['SendNow'];
$FormMailCode= $_POST['FormMailCode'];
$SourceNotes = $_POST['SourceNotes'];

// Sanitize user-supplied field data.
$Authentication = htmlspecialchars($Authentication);
$Title = htmlspecialchars($Title, ENT_COMPAT);
$FirstName = htmlspecialchars($FirstName, ENT_NOQUOTES);
$LastName = htmlspecialchars($LastName, ENT_NOQUOTES);
$ProspectEmail = htmlspecialchars($ProspectEmail);
$AreaLabel = htmlspecialchars($AreaLabel);
$SourceNotes = htmlspecialchars($SourceNotes);

/* Before going any further, I'm going to check that the prospects_table doesn't already have a prospect whose ProspectEmail column is equal to $ProspectEmail. If it does, terminate the script. */

// Connect to mysql and select database
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
or die('Could not connect: ' . mysql_error());
mysql_select_db('paulme6_newresolution') or die('Could not select database');
$query = "select count(*) from prospects_table where ProspectEmail = '".$ProspectEmail."'";
$result = mysql_query($query) or die('Check for prospect email duplication failed: ' . mysql_error());

$row = mysql_fetch_row($result); // $row array should have just one item, which holds either '0' or '1'
$count = $row[0];

if ($count != 0) // A duplicate is detected
	{
	echo "<p class='basictext' style='position: absolute; left: 150px; margin-right: 50px; margin-top: 180px; font-size: 14px;'>The addition of this propsect has been prevented because the email address (i.e. ".$ProspectEmail.") of the prospect that you attempted to add already exists in prospects_table. No email message will have been sent in this case. Use your browser&rsquo;s Back button or ";
	// Include a 'Back' button for redisplaying the Authentication form.
	if (isset($_SERVER['HTTP_REFERER'])) // Antivirus software sometimes blocks HTTP_REFERER.
		{
		echo "<a style='font-size: 14px;' href='".$_SERVER['HTTP_REFERER']."'>click here</a> to try again.</p>";
		}
	else
		{
		echo "<a style='font-size: 14px;' href='javascript:history.back()'>click here</a> to try again.</p>";
		}
	exit; // Kill the script here otherwise the rest will be processed (and an email potentially sent, even though the browser was redirected back to admin11.php.)
	}

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
		$_SESSION['MsgTitle'] = null;
		$_SESSION['MsgFirstName'] = null;
		$_SESSION['MsgLastName'] = null;
		$_SESSION['MsgProspectEmail'] = null;
		$_SESSION['MsgState'] = null;
		$_SESSION['MsgAreaLabel'] = null;

		// Seek to validate $Title
		$illegalCharSet = '[0-9~%\^\*_@\+`\|\$:";<>\?#!=]+'; // Exclude everything except A-Z, a-z, period, hyphen, apostrophe, &, slash, space, comma, and parentheses.
		$reqdCharSet = "[A-Za-z]{2,}";  // At least two letters
		if ($Title != '') // Since it's an optional field, only bother to validate if it's not empty.
			{
			if (ereg($illegalCharSet, $Title) || !ereg($reqdCharSet, $Title))
				{
				$_SESSION['MsgTitle'] = "<span class='errorphp'>Please enter a valid title (e.g. Dr. or Ms.) or leave blank.<br></span>";
				$_SESSION['phpinvalidflag'] = true; 
				};
			}

		// Seek to validate $FirstName
		$illegalCharSet = '[0-9~%@\^\*_\+`\|\$:";<>\?#&+=!,\(\)]+'; // Exclude everything except letters and dash and period and apostrophe.
		$reqdCharSet = "[A-Za-z]{2,}";  // At least two letters
		if ($FirstName != '') // Since it's an optional field, only bother to validate if it's not empty.
			{
			if (ereg($illegalCharSet, $FirstName) || !ereg($reqdCharSet, $FirstName))
				{
				$_SESSION['MsgFirstName'] = "<span class='errorphp'>Please enter a valid first name or leave blank.<br></span>";
				$_SESSION['phpinvalidflag'] = true; 
				};
			}

		// Seek to validate $LastName
		$illegalCharSet = '[0-9~%@\^\*_\+`\|\$:";<>\?#&+=!,\(\)]+'; // Exclude everything except letters and dash and period and apostrophe.
		$reqdCharSet = "[A-Za-z]{2,}";  // At least two letters
		if ($LastName != '') // Since it's an optional field, only bother to validate if it's not empty.
			{
			if (ereg($illegalCharSet, $LastName) || !ereg($reqdCharSet, $LastName))
				{
				$_SESSION['MsgLastName'] = "<span class='errorphp'>Please enter a valid surname or leave blank.<br></span>";
				$_SESSION['phpinvalidflag'] = true; 
				};
			}

		// Seek to validate $ProspectEmail
		$reqdCharSet = '^[A-Za-z0-9_\-\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$';  // Simple validation from Welling/Thomson book, p125.
		if (!ereg($reqdCharSet, $ProspectEmail)) // This is a required field.
			{
			$_SESSION['MsgProspectEmail'] = '<span class="errorphp"><br>Please check format. Use only alphanumerics (A-Z, a-z, 0-9), dash (-), period (.), @, and underscore (_) characters.<br></span>';
			$_SESSION['phpinvalidflag'] = true; 
			};

		// Seek to validate $AreaLabel
		$illegalCharSet = '[0-9~%@\^\*_\+`\|\$:";<>\?#+=!\(\)]+'; // Exclude everything except letters, dash, period, comma, ampersand, slash, and apostrophe.
		$reqdCharSet = "[A-Za-z]{2,}";  // At least two letters
		if ($AreaLabel != '') // Since it's an optional field, only bother to validate if it's not empty.
			{
			if (ereg($illegalCharSet, $AreaLabel) || !ereg($reqdCharSet, $AreaLabel))
				{
				$_SESSION['MsgAreaLabel'] = "<span class='errorphp'>Please enter a valid description of this prospect&rsquo;s geographic area.<br></span>";
				$_SESSION['phpinvalidflag'] = true; 
				};
			}

		// Seek to validate $State.
		if (is_null($State) || $State == '') // Test for either null or '' (blank).
			{
			$_SESSION['MsgState'] = '<span class="errorphp">Please select a state from the drop-down menu.<br></span>';
			$_SESSION['phpinvalidflag'] = true; 
			};
	
		//Now go back to the previous page (admin11.php) and show any PHP inline validation error messages if the $_SESSION['phpinvalidflag'] has been set to true. ... otherwise, proceed to update the database with the user's form data.
//Note: Remember that output buffering (ob_start()) has been turned on, so only headers are sent until the code encounters either an ob_flush() or an ob_end_flush() statement, at which point the contents of the output buffer are output to the screen. Also note that ob_flush() differs from ob_end_flush in that the latter not only flushes but also turns off output buffering (i.e. cancels a prior ob_start() statement). Final note: the output buffer is always flushed at the end of the page (e.g. at the bottom of this processprofile.php script), even if there isn't an ob_flush() or ob_end_flush() statement.
		if ($_SESSION['phpinvalidflag'])
		{
		?>
		<script language="javascript" type="text/javascript">
		<!--
		history.back();
		// -->
		</script>
		<noscript>
		<?php
		header("Location: admin11.php"); // Go back to admin11.php page.
		?>
		</noscript>
		<?php
		exit;	
		ob_flush();
		};

		// End of PHP form validation

		// Escape any data that may be inserted into the DB if necessary
		if (!get_magic_quotes_gpc())
		{
			$Title = addslashes($Title);
			$FirstName = addslashes($FirstName);
			$LastName = addslashes($LastName);
			$ProspectEmail = addslashes($ProspectEmail);
			$AreaLabel = addslashes($AreaLabel);
			$SourceNotes = addslashes($SourceNotes);
		}	

		/* Check to see that this prospect's email address doesn't already exist in prospects_table. */
		
		/* Having now gotten past form validation and having established that the prospect wasn't previously added to the DB, we now have two remaining tasks (A) send a custom email (according to the selected FormMailCode) to the prospect if the Administrator checked the "Send Now" check-box, and (B) insert the prospect's data into the prospects_table. */

		/* Task A: Send marketing email to prospect if desired. The precise text of the message will vary according to the selected FormMailCode. */
		
		if ($SendNow == 1)
			{
			require_once('Mail.php');
			require_once('Mail/mime.php');

			// Assign a default value to $AreaLabel if it didn't have a user-specified value, and create a session variable for $AreaLabel because it needs to get passed to the SSI file emailsolicitationcontent.php in order to insert its value there.
			if (empty($AreaLabel)) $AreaLabel = 'your area';
			$_SESSION['AreaLabel'] = $AreaLabel;

			// Assign $ProspectEmail to a session variable for use within emailsolicitationcontent.php, which creates a SHA-1 hashed and encoded unsubscribe link in the email text sent to each propsect.
			$_SESSION['ProspectEmail'] = $ProspectEmail;

			// Formulate appropriate form of address based on value of $UseFormalTitle, etc., for the "dear line" in the message.
			if ($UseFormalTitle == 1 && $Title != '' && $LastName != '') $dearline = 'Dear '.$Title.' '.$LastName; // Use a formal title (e.g. Dr. Thomas) if applicable.
			else if ($FirstName != '') $dearline = $FirstName.',';
			else $dearline = 'Dear Fellow Practitioner'; // Very unlikely to see use.
			$_SESSION['dearline'] = $dearline; // for use in SSI file emailsolicitationcontent.php
			
			require_once('../ssi/emailsolicitationcontent.php'); // Important to place this SSI AFTER the definition of $dearline b/c it incorporates the $dearline variable.
			
			switch($FormMailCode)
				{
				case 'EarlyMedNo' :
					$messageHTML = $message1HTML;
					$messageText = $message1Text;
					$subject = $subject1;
					break;
				case 'EarlyMedMin' :
					$messageHTML = $message2HTML;
					$messageText = $message2Text;
					$subject = $subject2;
					break;
				case 'EstdMedMin' :
					$messageHTML = $message3HTML;
					$messageText = $message3Text;
					$subject = $subject3;
					break;
				case 'EstdMed' :
					$messageHTML = $message4HTML;
					$messageText = $message4Text;
					$subject = $subject4;
					break;
				case 'ExpAttyMed' :
					$messageHTML = $message5HTML;
					$messageText = $message5Text;
					$subject = $subject5;
					break;
				case 'AttyNonMed' :
					$messageHTML = $message6HTML;
					$messageText = $message6Text;
					$subject = $subject6;
					break;
				case 'TherapMed' :
					$messageHTML = $message7HTML;
					$messageText = $message7Text;
					$subject = $subject7;
					break;
				case 'TherapExt' :
					$messageHTML = $message8HTML;
					$messageText = $message8Text;
					$subject = $subject8;
					break;
				case 'FinProMed' :
					$messageHTML = $message9HTML;
					$messageText = $message9Text;
					$subject = $subject9;
					break;
				case 'FinProExt' :
					$messageHTML = $message10HTML;
					$messageText = $message10Text;
					$subject = $subject10;
					break;
				case 'CoachMed' :
					$messageHTML = $message11HTML;
					$messageText = $message11Text;
					$subject = $subject10;
					break;
				case 'CoachExt' :
					$messageHTML = $message12HTML;
					$messageText = $message12Text;
					$subject = $subject12;
					break;
				case 'LawStud' :
					$messageHTML = $message13HTML;
					$messageText = $message13Text;
					$subject = $subject13;
					break;
				case 'TherapStud' :
					$messageHTML = $message14HTML;
					$messageText = $message14Text;
					$subject = $subject14;
					break;
				default :
					$messageHTML = $message3HTML;
					$messageText = $message3Text;
					$subject = $subject3;
				}

			$sendto = $ProspectEmail; /* Comment out this line via // when testing this script, replacing it with the next line instead */
			// $sendto = 'paulmerlyn@yahoo.com';   /* Uncomment this line for test purposes */
			$crlf = "\n";
			$hdrs = array(
	              'From'    => 'New Resolution Mediation LLC <paul@newresolutionmediation.com>',
    	          'Subject' => $subject,
				  'Bcc' => 'paulmerlyn@yahoo.com'
	              );

$mime = new Mail_mime($crlf);
$mime->setTXTBody($messageText);
$mime->setHTMLBody($messageHTML);

//do not ever try to call these lines in reverse order
$body = $mime->get();
$hdrs = $mime->headers($hdrs);

$mail =& Mail::factory('mail');
$mail->send("$sendto", $hdrs, $body);
			
			}

		/* Task B: Insert the prospect's data into the prospects_table, including date information about when this prospect was originally added to the DB (DofAdd). Also, (if an email was sent via the Administrator's checking the "Send Now" box in admin11.php), the date on which it was sent (DofMail), and increment the count of messages sent from zero to 1 (NofMsgs). */

		/* Manipulate user data prior to insertion into DB */
		if (empty($Title) OR empty($LastName)) $UseFormalTitle = 0;
		
		if ($UseFormalTitle != 1) $UseFormalTitle = 0;

		if ($SendNow != 1) $SendNow = 0;
		
		if ($SendNow == 1) $NofMsgs = 1; else $NofMsgs = 0;
		
		// Formulate query to insert new data into the prospects_table.
		$query = "INSERT INTO prospects_table set ProspectEmail = '".$ProspectEmail."', Title = '".$Title."', FirstName = '".$FirstName."', LastName = '".$LastName."', AreaLabel = '".$AreaLabel."', UseFormalTitle = '".$UseFormalTitle."', NofMsgs = '".$NofMsgs."', State = '".$State."', FormMailCode = '".$FormMailCode."', SourceNotes = '".$SourceNotes."', DofAdd = CURDATE()"; 
		if ($SendNow == 1) $query .= ", DofMail = NOW()"; // Append an insertion of today as the date-and-time of the last mailing to this prospect if the Administrator checked the "Send Now" box in admin11.php.
		$result = mysql_query($query) or die('Query (insert into prospects_table) failed: ' . mysql_error().' and the query string was: '.$query);

		echo '<p class=\'basictext\' style=\'margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px;\'>Mission accomplished! The new prospect '.$FirstName.' '.$LastName.' has now been added to the prospects table in the database.';
		if ($SendNow == 1) echo ' An email was sent to prospect '.$FirstName.' '.$LastName.'.'; else echo ' No email was sent at this time.';
		echo '</p>';
		?>		
		<form method="post" action="unwind.php">
		<table width="530" cellpadding="0" cellspacing="0" style="margin-top: 50px; margin-left: 100px;">
		<tr>
		<td style="text-align: right;">
		<input type='button' name='Continue' class='buttonstyle' value='Continue' onclick='javascript: window.location = "admin11.php";'> <!-- This is not a submit button and functions independenly of the action='unwind.php' form -->
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