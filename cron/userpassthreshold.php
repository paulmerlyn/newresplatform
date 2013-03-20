<?php
/*
userpassthreshold.php is a cron script that runs daily and sends an email to alert a platform administrator if the number of available username-password pairs in userpass_table (not userpass_table_demo) is less than a prescribed threshold. An administrator can then log into the Administrator Utility (admin9.php) to insert additional username-password pairs into the client database.
Note: the coding is drawn from admin9.php.
*/

// Define the threshold. (This is readily editable.)
$threshold = 20;

// Connect to my mysql database.
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
or die('Could not connect: ' . mysql_error());
mysql_select_db('paulme6_newresolution') or die('Could not select database');

// Count the number of available username-password pairs.
$query = "SELECT count(*) FROM userpass_table WHERE Available = 1"; // Count of only unallocated username-password pairs
$result = mysql_query($query) or die('The SELECT query count of the available username-password pairs in userpass_table failed i.e. '.$query.' failed: ' . mysql_error());
$row = mysql_fetch_row($result); // $row array contains just one value i.e. the value of the count of all available UP pairs.
$rowAvail = $row[0];

if ($rowAvail <= $threshold)
	{
	$subject = "Action Required: Critical Shortage of Username-Password Pairs";
	$address = "support@newresolutionmediation.com";
	$headers = 
	"From: support@newresolutionmediation.com\r\n" .
	"Reply-To: donotreply@newresolutionmediation.com\r\n" .
	"X-Mailer: PHP/" . phpversion();
	$body = "Hello Platform Administrator\n
	Re. Critical shortage of username-password pairs\n\n";
	$body .= "This is an automated message from script userpassthreshold.php, executed as a daily cron job. This script has detected that the number of available username-password pairs in the userpass_table has dropped below the critical threshold of ".$threshold.". An administrator must now insert additional username-password pairs into that table via the admin9.php module of the Administrator utility.";
	mail($address, $subject, $body, $headers);
	};
	
exit;
?>