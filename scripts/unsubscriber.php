<?php
/* 
This script gets called when a prospect clicks the "Remove Me" link in an email solication. The emails are either sent piecemeal via admin11.php or in bulk via prospectinviter.php (run usually as cron job). The hyperlink will contain a hashed version of the prospect's email address. The hashing uses SHA-1 on a secret key (= white bear, yow, lower case) prepended to the email address. The result is then truncated to just the first 12 characters.
	unsubscriber.php simply goes through all the email addresses in the ProspectEmail column of prospects_table to find a match. It then updates the Unsubscribed field for that particular prospect, changing its value from the default of 0 (false) to 1 (true). It also sends me an email alerting me to the prospect's decision to unsubscribe.
*/

// Start a session
ob_start(); // Used in conjunction with ob_flush() [see www.tutorialized.com/view/tutorial/PHP-redirect-page/27316], this allows me to postpone issuing output to the screen until after the header has been sent.
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>New Resolution Mediation Platform | Removal of Prospect Data</title>
<link href="/sales.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
// Connect to mysql and select database.
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
or die('Could not connect: ' . mysql_error());
mysql_select_db('paulme6_newresolution') or die('Could not select database');
	
// First obtain the query string from the URL sought by the prospect who clicked the "Remove Me" hyperlink in his/her email solicitation message.
$queryString = $_SERVER['QUERY_STRING']; // courtesy http://www.webmasterworld.com/forum88/221.htm
$queryStringCode = $_GET['code']; // where the query string created in emailsolicitationcontent.php will have a format such as this: "code=af3uy678jg50"

// Formulate DB query to retrieve all prospects from prospects_table.
$query = "SELECT ProspectID, ProspectEmail, FirstName, LastName, FormMailCode, NofMsgs from prospects_table";
$result = mysql_query($query) or die('Your attempt to select ProspectID and ProspectEmail has failed. Here is the query: '.$query.mysql_error());
// Now loop through the resultset to find the ProspectID (the key of the prospects_table) of the prospect whose hash/encoded email address matches the value ($queryStringCode) extracted from the query string above.
 while ($row = mysql_fetch_assoc($result))
	{
	$hashcode = 'polo'; // This secret key gets used to hash the prospect's email address. If you change the value of this key, make sure you change it also in emailsolicitationcontent.php.
	$hashcode = $hashcode.$row['ProspectEmail'];
	$hashcode = sha1($hashcode);
	$hashcode = substr($hashcode, 0, 12); // Truncate, allowing only first 12 characters.

	if ($hashcode == $queryStringCode)
		{
		// A match has been found. Now proceed to set the Unsubscribed field to 1 (true) for the prospect of corresponding ProspectID.
		$query = "UPDATE prospects_table SET Unsubscribed = 1 WHERE ProspectID = ".$row['ProspectID'];
		$result = mysql_query($query) or die('Update to prospects_table for Unsubscribed field failed: ' . mysql_error());
		
		// Display a confirmation message on the prospect's screen.
		echo '<div style="margin-left: 100px; margin-right: 100px; margin-top: 140px; font-family: Geneva, Arial, Helvetica, sans-serif; font-size: 14px;">Thank you. Your request has been received, and sorry to have troubled you! We won&rsquo;t send any additional information to '.$row['ProspectEmail'].' about the New Resolution Platform&trade; and our network of independent mediators.</div>';  
		echo '<div style="margin-left: 100px; margin-right: 100px; margin-top: 40px; font-family: Geneva, Arial, Helvetica, sans-serif; font-size: 14px;">Please feel free to contact us in the future when you&rsquo;re ready to expand your mediation practice.<a href=\'index.shtml\' style=\'color: #FF9900; font-weight: bold; text-decoration: none\' onclick=\'javascript: window.location = "/index.shtml"; return false;\'> Click here</a> to continue.</div>';  
		echo '<div style="margin-left: 100px; margin-right: 100px; margin-top: 60px; font-family: Geneva, Arial, Helvetica, sans-serif; font-size: 14px; text-align: center;"><img src="../images/NewResolutionLogo.jpg"></div>';  

		// Break out of the while loop (there's no reason to keep looking for other matches b/c the ProspectEmail field is unique in the prospects_table.
		break; 
		}
	};
	
	// Send myself an email using PEAR Mail to alert me to this prospect's decision to unsubscribe.
	require_once('Mail.php');
	require_once('Mail/mime.php');
	
	$messageHTML .= "<html><body><table cellspacing='16'><tr><td style='font-family: Arial, Helvetica, sans-serif'><tr><td style='font-family: Arial, Helvetica, sans-serif'>Hello: This is an auto-generated message issued by unsubscriber.php to let you know that the following prospect received an email solicitation and has clicked a link in that message in order not to receive further communications from New Resolution LLC:</td></tr>";
	$messageHTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>".$row['FirstName']." ".$row['LastName']."  |  ".$row['ProspectEmail']."  |  FormMailCode = ".$row['FormMailCode']."  |  NofMsgs = ".$row['NofMsgs']."</td></tr>";
	if ($row['SourceNotes'] != '') $messageHTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Source notes for this prospect: ".$row['SourceNotes']."</td></tr>";
	$messageHTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Sincerely</td></tr>";
	$messageHTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>New Resolution LLC Autoresponder</td></tr></body></html>";
	$sendto = 'paulmerlyn@yahoo.com';
	$crlf = "\n";
	$hdrs = array(
	              'From'    => 'New Resolution Mediation LLC <donotreply@newresolutionmediation.com>',
    	          'Subject' => 'Prospect '.$row['FirstName'].' '.$row['LastName'].' Has Unsubscribed',
				  'Bcc' => 'paulmerlyn@yahoo.com'
	              );

	$mime = new Mail_mime($crlf);
	$mime->setHTMLBody($messageHTML);

	//do not ever try to call these lines in reverse order
	$body = $mime->get();
	$hdrs = $mime->headers($hdrs);

	$mail =& Mail::factory('mail');
	$mail->send("$sendto", $hdrs, $body);

	ob_flush();
?>
</body>
</html>
