<?php
/* 
This script is the slave processor for renew.php. It simply processes the clicking of a "log out" form button that's disguised as a link. 
*/
// Start a session
session_start();

// Create short variable names
$LoggedOut = $_POST['LoggedOut'];
$Abort = $_POST['Abort'];

/*
If the user clicked the 'Abort' button or the 'Log out' hyperlink in the Profile Entry form inside updateprofile.php, then set the $_SESSION['SessValidUserFlag'] to 'false' and reload renew.php. Now that once this flag is set to false, the page will show the Log-In form inside renew.php.
*/
if (isset($Abort) || isset($LoggedOut))
{
	unset($Abort);
	unset($LoggedOut);
	$_SESSION['SessValidUserFlag'] = 'false';
	if (isset($_SERVER['HTTP_REFERER'])) // Antivirus software sometimes blocks transmission of HTTP_REFERER.
		{
		header("Location: /renew.php"); // Go back to previous page. (Alternative to echoing the Javascript statement: history.go(-1) or history.back()
		}
	else
		{
		echo "<script type='text/javascript' language='javascript'>history.back();</script>";
		};
	exit;
}
?>