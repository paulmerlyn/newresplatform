<?php
/* 
This script is the slave processor for the waitlist form page that gets opened via a hyperlink inside mediationcareer.php. It controls the proper function of the state and locale drop-down menus on waitlist.php, and it inserts the user-provided locale, email, name, and telephone data into DB table waitlist_table for a waitlister. It incorporates Captcha as described in documentation at http://www.white-hat-web-design.co.uk/articles/php-captcha.php.
*/

// Start a session
session_start(); 
ob_start(); // Used in conjunction with ob_flush() [see www.tutorialized.com/view/tutorial/PHP-redirect-page/27316], this allows me to postpone issuing output to the screen until after the header has been sent.
// Create short variable names
$WaitlistState = $_POST['WaitlistState']; // Used within waitlist.php for a mediator who wants to be informed when his/her locale is already taken
$WaitlistLocale = $_POST['WaitlistLocale']; // Used within waitlist.php for a mediator who wants to be informed when his/her locale is already taken

// Create short names for other posted variables:
$WaitlisterEmail = $_POST['waitlistemail'];
$WaitlisterName = $_POST['waitlistname'];
$WaitlisterTelephone = $_POST['waitlistphone'];
$NotifyMe = $_POST['NotifyMe']; // NotifyMe is the name of a submit button.
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Priority License Reservation - Part II</title>
<link href="/sales.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
if (isset($WaitlistLocale))
	{
	$_SESSION['WaitlistLocaleSelected'] = $WaitlistLocale;  // Store the selected state of the WaitlistLocale drop-down menu in session variable $_SESSION['WaitlistLocaleSelected'] for use in waitlist.php. Specifically, this session variable is used to ensure that the 'NotifyMe' submit button is disabled whenever the locales drop-down menu is in its neutral position.
	unset($WaitlistLocale);
	};

if (isset($WaitlistState))
	{
	$_SESSION['WaitlistStateSelected'] = $WaitlistState;  // Store the selected state of the WaitlistState drop-down menu in session variable $_SESSION['WaitlistStateSelected'] for use in waitlist.php in populating the relevant locales for that state in the locales drop-down menu when a mediator wants to provide his/her name/phone/email for a locale that is already taken.
	if ($WaitlistState == '' || $WaitlistState == null) $_SESSION['WaitlistLocaleSelected'] = '';
	unset($WaitlistState);
	};
	
if (!isset($NotifyMe)) // Now go back to waitlist.php via either the HTTP_REFERER or via javascript history.back in situations where either the state or the locale drop-down menu got changed (but the 'Notify Me' submit button wasn't clicked).
	{
?>
	<script language="javascript" type="text/javascript">
	<!--
	window.location = '/waitlist.php';
	// -->
	</script>
	<noscript>
	<?php
	if (isset($_SERVER['HTTP_REFERER'])) // Antivirus software sometimes blocks transmission of HTTP_REFERER.
		{
		header("Location: /waitlist.php"); // Go back to previous page. (Alternative to echoing the Javascript statement: history.go(-1) or history.back() in cases where user has Javascript disabled.
		}
	ob_flush();
	?>
	</noscript>
	<?php
	}
	
if (isset($NotifyMe)) // The 'Notify Me' button was clicked
	{
	/* Start of Captcha code */
	if(($_SESSION['security_code'] == $_POST['security_code']) && (!empty($_SESSION['security_code'])) ) 
		{
		// Insert you code for processing the form here, e.g emailing the submission, entering it into a database. 

		/* Begin PHP form validation. */

		// Create a session variable for the PHP form validation flag, and initialize it to 'false' i.e. assume it's valid.
		$_SESSION['phpinvalidflag'] = false;

		// Create a session variable to hold inline error messages, and initialize them to blank.
		$_SESSION['MsgEmail'] = null;
	
		// Seek to validate $Email
		$reqdCharSet = '^[A-Za-z0-9_\-\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$';  // Simple validation from Welling/Thomson book, p125.
		if (!ereg($reqdCharSet, $WaitlisterEmail))
			{
			$_SESSION['MsgEmail'] = '<span class="errorphp"><br>Your email address is invalid.<br>Please try again. Example: <i>myname@gmail.com</i><br></span>';
			$_SESSION['phpinvalidflag'] = true; 
			};

		//Now go back to the previous page (waitlist.php) and show any PHP inline validation error messages if the $_SESSION['phpinvalidflag'] has been set to true. ... otherwise, proceed to update the database with the waitlister's form data.
		if ($_SESSION['phpinvalidflag'])
			{
			//echo 'The phpinvalidflag session variable got set to true for an invalid user input.'; exit;
			?>
			<script type='text/javascript' language='javascript'><!-- history.back(); //--></script>
			<noscript>
			<?php
			if (isset($_SERVER['HTTP_REFERER']))
				header("Location: ".$_SERVER['HTTP_REFERER']); // Go back to previous page. (Similar to echoing the Javascript statement: history.go(-1) or history.back() except I think $_SERVER['HTTP_REFERER'] reloads the page, which causes freshly entered values in the updateprofile.php form to get overwritten by values retrieved from the DB. So the javascript 'history.back()' method is more suitable. However, if Javascript is enabled, php form validation is moot. And if Javascript is disabled, then the javascript 'history.back()' method won't work anyway.
			?>
			</noscript>
			<?php
			exit;	
			};

		// Having passed the error validation stage, next prevent cross-site scripting via htmlspecialchars on user-entry form fields
		$WaitlisterName = htmlspecialchars($WaitlisterName, ENT_QUOTES); // The ENT_QUOTES parameter is necessary to handle names with apostrophes e.g. Jean D'Tocqueville.
		$WaitlisterTelephone = htmlspecialchars($WaitlisterTelephone, ENT_QUOTES);
		$WaitlisterEmail = htmlspecialchars($WaitlisterEmail, ENT_QUOTES);
		
		// I also need to manipulate Locale (note: even though this is provided in a controlled way via a drop-down menu, I still need to manipulate it a little (using the more comprehensive htmlentities() rather than htmlspecialchars()) b/c Locale values posted by waitlist.php may contain single quotation marks as in "Coeur d'Alene_ID" or n-tilde as in "Canon City_CO". (Note the UTF-8 character set specification is important for chars such as n-tilde in Canon City_CO.)
		$WaitlistLocale = htmlentities($_SESSION['WaitlistLocaleSelected'], ENT_QUOTES, 'UTF-8');

		// Escape quotes if necessary
		if (!get_magic_quotes_gpc())
			{
			$WaitlisterName = addslashes($WaitlisterName);
			$WaitlisterTelephone = addslashes($WaitlisterTelephone);
			$WaitlisterEmail = addslashes($WaitlisterEmail);
			};
	
		// Connect to mysql and select database.
		$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
		or die('Could not connect: ' . mysql_error());
		mysql_select_db('paulme6_newresolution') or die('Could not select database');
	
		// Formulate DB query and insert locale, email, name, and telephone into waitlist_table
		$query = "INSERT INTO waitlist_table SET Locale = '".$WaitlistLocale."', WaitlisterName = '".$WaitlisterName."', WaitlisterTelephone = '".$WaitlisterTelephone."', WaitlisterEmail = '".$WaitlisterEmail."'";
		$result = mysql_query($query) or die('Your attempt to insert into the waitlist_table has failed. Here is the query: '.$query.mysql_error());
		$_SESSION['OKtoClose'] = 'true'; // Set this session variable, which is used by waitlist.php so that that pop-up window can close itself now that the waitlister's data has been safely inserted into waitlist_table.
		unset($NotifyMe);

		// Now go back to waitlist.php via either the HTTP_REFERER or via javascript history.back. Note that the waitlist.php window will immediately self-close if $_SESSION['OKtoClose'] has been set to 'true', which it will have been if the user clicked the 'Notify Me' button and the user input was accepted i.e no data validation errors were detected.
		echo '<div style="margin-left: 20px; margin-top: 40px; font-family: Geneva, Arial, Helvetica, sans-serif; font-size: 14px;">Thank you. You&rsquo;re now on the wait-list for your chosen locale. We&rsquo;ll notify you when this locale becomes available. <a href=\'index.shtml\' style=\'color: #FF9900; font-weight: bold; text-decoration: none\' onclick=\'javascript: window.location = "/waitlist.php"; return false;\'>Click here</a> to continue.</div>';  
		unset($_SESSION['security_code']);
		}
	else
		{
		// Insert your code for showing an error message here
		echo '<div style="margin-left: 20px; margin-top: 20px; font-family: Geneva, Arial, Helvetica, sans-serif; font-size: 14px;">To help fight spam, please enter the security code on the previous page before resubmitting your request. Click <a href=\'index.shtml\' onclick=\'javascript: window.location = "/waitlist.php"; return false;\'>here</a> to continue. Thank you.</div>';  
		unset($_SESSION['security_code']);
		exit;
		}
		/* End of Captcha code block */
		?>
	</body>
	</html>
	<?php
	}
?>