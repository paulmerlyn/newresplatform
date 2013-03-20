<?php
/*
This script is called by admin1.php. It is called as an action when the user clicks the 'Change Database' or 'Log Out' submit buttons (disguised as links). It unsets various session variables so that the log-in screen (i.e. where the user offers an authentication password) or the client/demo selector screen (which allows the user to decide between access to either the client database or the demo database) will display again once this script returns control back to admin1.php (via either the php header statement or the javascript history.back() method. Note the former necessitates use of the ob_start() and ob_flush() so nothing is written to the screen prior to a header being sent.
*/

// Start a session
session_start();
ob_start(); // Used in conjunction with ob_flush() [see www.tutorialized.com/view/tutorial/PHP-redirect-page/27316], this allows me to postpone issuing output to the screen relating to $UploadFile until after the header has been sent.

// Create short variable names
$ChangeDB = $_POST['ChangeDB'];
$Logout = $_POST['Logout'];
if (isset($ChangeDB))
	{
	unset($_SESSION['ClientDemoSelected']);
	}
if (isset($Logout))
	{
	unset($_SESSION['Authenticated']);
	unset($_SESSION['ClientDemoSelected']);
	}

// Go back to admin1.php (which includes ready access to admin3.php, admin5.php, admin7.php etc. via an require()'ed navigation menu).
if (isset($_SERVER['HTTP_REFERER'])) // Antivirus software sometimes blocks transmission of HTTP_REFERER.
	{
	$HTTP_REFERER = $_SERVER['HTTP_REFERER'];
	header("Location: admin1.php"); // (Formerly header("Location: $HTTP_REFERER");) where $HTTP_REFERER = $_SERVER['HTTP_REFERER'];. (Alternative to echoing the Javascript statement: history.go(-1) or history.back()
	}
else
	{
	echo "<script type='text/javascript' language='javascript'>document.location = 'admin1.php';</script>";
	ob_flush();
	}
exit;
?>