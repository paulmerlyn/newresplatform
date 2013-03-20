<?php
/*
This script is conventionally executed via a cron job. (It can also be executed manually by simply calling this file in my browser.) Its only purpose is to send an initial email invitation to a number (no more than 50 at the present time -- a limit imposed by my host, InMotion Hosting) of unique Mediation Platform prospects in the prospects_table, unless that prospect has unsubscribed him/herself via unsubscriber.php (in which case his/her record in prospects_table will have the Unsubscribed column set to 1 (true)). After sending the message to each appropriate prospect, it must also increment the NofMsgs field and record today's date/time in the DofMail field.
*/

// Start a session
session_start();
ob_start();

/* Determine the parameters to identify which prospects in the prospects_table should receive a marketing solicitation when this script is next run (either manually or via a cron job). (These user-configured settings effect two $query statements below.) */

$theState1 = ''; // If you want to send to prospects in any/all states, set all five $theState values to '' (empty)
$theState2 = '';
$theState3 = '';
$theState4 = '';
$theState5 = '';

/* Determine the ProspectID's of prospects in the prospects_table who should receive a marketing solicitation when this script is next run (either manually or via a cron job). Note that we actually calculate the low and high values of the ProspectID range by using an integer multiplier that is obtained from the script's filename (strictly speaking, filepath). So, if the script, for example, were called prospectinviter6.php, then the multiplier ($RangeMultiplier) would be 6. This technique allows me to create one batch of 50 unique ProspectID's in each script file. If I needed to send emails to, say, 350 propsects, I might have seven files: prospectinviter1.php, prospectinviter2.php, prospectinviter3.php, ... , and prospectinviter7.php. */
$theFilepath = __FILE__; // Obtain this script's own file path (and thence filename - e.g. prospectinviterFL6.php). Note that my previous attempt (where I'd used $_SERVER['REQUEST_URI']) failed because it didn't return the file path when called by a cron script (only when called by typing the URL into a browser).
echo 'The filepath obtained via __FILE__ is: '.$theFilepath."\n";
$theFilename = strrchr($theFilepath, '/');
echo 'The filename obtained via strrchr is: '.$theFilename."\n";
$RangeMultiplier = preg_replace( '/[^\d]/', '', $theFilename); // Strip anything that isn't a digit from the REQUEST_URI file path

$ProspectIDLow = '1'; // Baseline
$ProspectIDLow = (int)$ProspectIDLow + ((int)$RangeMultiplier - 1) * 50; // Calculate actual $ProspectIDLow for this particular script. Note use of integer type when performing arithmetic on a string.

$ProspectIDHigh = (int)$ProspectIDLow + 49; // Similarly, calculate actual $ProspectIDHigh for this particular script.
$ProspectIDHigh = (string)$ProspectIDHigh; // Convert back to a string type after performing arithmetic to find upper limit of range

// Formalize the states clause for use in the two $query statements below. (Note: this $theStateClause determination is largely moot unless the administrator manually specifies targeted states above.
if ($theState1 == '' AND $theState2 == '' AND $theState3 == '' AND $theState4 == '' AND $theState5 == '')
	{
	$theStateClause = '';
	}
else
	{
	$theStateClause = " AND (State = '".$theState1."' OR State = '".$theState2."' OR State = '".$theState3."' OR State = '".$theState4."' OR State = '".$theState5."')";
	} 
	
// Include these PEAR mail utilities.
require_once('Mail.php');
require_once('Mail/mime.php');

// Connect to my mysql database.
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
or die('Could not connect: ' . mysql_error());
mysql_select_db('paulme6_newresolution') or die('Could not select database');

// Query the prospects_table table to select various prospect data where NofMsgs is not 99 and Unsubscribed != 1.
$query = "SELECT UseFormalTitle, Title, FirstName, LastName, FormMailCode, ProspectEmail, AreaLabel FROM prospects_table WHERE ProspectID >= ".$ProspectIDLow." AND ProspectID <= ".$ProspectIDHigh." AND NofMsgs != 99 ".$theStateClause." AND Unsubscribed != 1";

echo '$query is: '.$query."\n\n";

$result = mysql_query($query) or die('Query (select prospects who have not yet been sent any messages) failed: ' . mysql_error());

while ($row = mysql_fetch_assoc($result))
	{
	echo 'Message sent to: '.$row['FirstName'].' '.$row['LastName'].', '.$row['ProspectEmail'].', '.$row['FormMailCode'].', '.$row['AreaLabel']."\n"; // This will either be echoed to the screen (if prospectinviter.php is invoked manually via the browser) or to an email that is sent to me through a setting on the cron jobs section of cpanel (if prospectinviter.php is invoked via a cron job).

	// Formulate appropriate form of address based on value of $row['UseFormalTitle'], etc., for the "dear line" in the message.
	if ($row['UseFormalTitle'] == 1 && $row['Title'] != '' && $row['LastName'] != '') $dearline = 'Dear '.$row['Title'].' '.$row['LastName']; // Use a formal title (e.g. Dr. Thomas) if applicable.
	else if ($row['FirstName'] != '') $dearline = $row['FirstName'].',';
	else $dearline = 'Dear Fellow Practitioner'; // Very unlikely to see use.
	$_SESSION['dearline'] = $dearline; // for use in SSI file emailsolicitationcontent.php

	// Assign a default value to $AreaLabel if it didn't have a user-specified value, and create a session variable for $AreaLabel because it needs to get passed to the SSI file emailsolicitationcontent.php in order to insert its value there.
	if (empty($row['AreaLabel'])) $AreaLabel = 'your area'; else $AreaLabel = $row['AreaLabel'];
	$_SESSION['AreaLabel'] = $AreaLabel; // for use in SSI file emailsolicitationcontent.php
	
	// Assign the correct ProspectEmail to $_SESSION['ProspectEmail'], a session variable that is used by emailsolicitationcontent.php
	$_SESSION['ProspectEmail'] = $row['ProspectEmail'];
			
	require('/home/paulme6/public_html/nrmedlic/ssi/emailsolicitationcontent.php'); // Important to place this SSI AFTER the definition of the $_SESSION['dearline'], $_SESSION['AreaLabel'], and $_SESSION['ProspectEmail'] b/c the included file incorporates these session variables. NOTE: I originally used a require_once() rather than a require() statement, but it causes a problem. If I use require(), the values of the session variables inside emailsolicitationcontent.php remain stuck at their values for the first row of results.
			
	switch($row['FormMailCode'])
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
			$subject = $subject11;
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

	$sendto = $row['FirstName'].' '.$row['LastName'].' <'.$row['ProspectEmail'].'>'; /* Comment out this line via // when testing this script, replacing it with the next line instead */
	// $sendto = 'paulmerlyn@yahoo.com';   /* Uncomment this line for test purposes */
	$crlf = "\n";
	$hdrs = array(
	              'From'    => 'New Resolution Mediation LLC <paul@newresolutionmediation.com>',
	              'To'    => $row['FirstName'].' '.$row['LastName'].' <'.$sendto.'>',
				  'Subject' => $subject
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

// Finally, update prospects_table, incrementing NofMsgs and DofMail in each case where NofMsgs was previously 0 and the prospect hadn't previously unsubscribed him/herself.

// Disconnect then reconnect to my mysql database (I have found that the cron job issues a "MySQL server has gone away" error message otherwise. Reestablishing a DB connection is my attempt to prevent that.
mysql_close($db);
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik') or die('Could not connect: ' . mysql_error());
$db_selected = mysql_select_db('paulme6_newresolution', $db);
if (!$db_selected) { die ('Cannot use this particular connection to the paulme6_newresolution database: ' . mysql_error());
}


$query = "UPDATE prospects_table SET NofMsgs = NofMsgs + 1, DofMail = NOW() WHERE ProspectID >= ".$ProspectIDLow." AND ProspectID <= ".$ProspectIDHigh." AND NofMsgs != 99 ".$theStateClause." AND Unsubscribed != 1";

$result = mysql_query($query) or die('Update to prospects_table for NofMsgs and DofMail failed: ' . mysql_error());

echo "\n\n".'The NofMsgs counter and DofMail field in the prospects_table have been updated for each prospect to whom an email was sent<br>'; // This will either be echoed to the screen (if prospectinviter.php is invoked manually via the browser) or to an email that is sent to me through a setting on the cron jobs section of cpanel (if prospectinviter.php is invoked via a cron job).

ob_flush();
exit;
?>