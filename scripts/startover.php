<?php
/*
This script is called by updateprofile.php. It is called as an action when the user clicks the 'start over' submit button (disguised as a link). It unsets various session variables so that the first screen (i.e. the check-box selector screen that allows the user to decide between access to either his/her client mediator profile or to the demo profiles [e.g. for test drivers]) will display again rather than be perpetually skipped over because, say, the 'ReferredFromSales' or the 'ClientDemoSelected' session variables had previously been set.
*/

// Unset key session variables
session_start();
ob_start(); // Used in conjunction with ob_flush() [see www.tutorialized.com/view/tutorial/PHP-redirect-page/27316], this allows me to postpone issuing output to the screen relating to $UploadFile until after the header has been sent.

unset($_SESSION['SessValidUserFlag']); // set inside updateprofile.php
unset($_SESSION['ReferredFromSales']); // set inside sales.php
unset($_SESSION['ClientDemoSelected']); // set inside democlientpaths1.php

// Go back to page that called this script (i.e. updateprofile.php)
if (isset($_SERVER['HTTP_REFERER'])) // Antivirus software sometimes blocks transmission of HTTP_REFERER.
	{
	header("Location: updateprofile.php"); // Go back to previous page. (Alternative to echoing the Javascript statement: history.go(-1) or history.back()
	}
else
	{
	echo "<script type='text/javascript' language='javascript'>history.back();</script>";
	ob_flush();
	}
exit;

