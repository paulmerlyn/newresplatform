<?php
/*
waiternotification.php is a cron script that runs daily and takes action on the day that a license is released for sale to a new mediator i.e. waiternotification.php runs a few hours or so after cron job expirationrelease.php, which itself runs the day after a mediator’s license expires.
	Each day, waiternotification.php (i) identifies any locales in mediators_table that have a license that expired yesterday (i.e. any license for which today’s date is 1 day after ExpirationDate), and (ii) sends an email to every mediator in waitlist_table who requested notification when that locale becomes available.
	waitlist_table gets data inserted into it every time a waitlister wants to provide his/her name (optional), telephone (optional), and email address (required) via the web form on the waitlist.php page, which is itself accessible as a link on mediationcareer.php.
*/

// Connect to my mysql database.
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
or die('Could not connect: ' . mysql_error());
mysql_select_db('paulme6_newresolution') or die('Could not select database');

// Use an inner join to query the mediators_table and waitlist_table, selecting WaitlisterName, WaitlisterEmail, and Locale for all mediators for whom curdate() is the day after ExpirationDate.
$query = "SELECT waitlist_table.WaitlisterName, waitlist_table.WaitlisterEmail, waitlist_table.Locale FROM waitlist_table, mediators_table WHERE TIMESTAMPDIFF(DAY, mediators_table.ExpirationDate, CURDATE()) = 1 AND mediators_table.Locale = waitlist_table.Locale";
$result = mysql_query($query) or die('Query (select data from mediators_table and waitlist_table for expired mediators) failed: ' . mysql_error());

while ($line = mysql_fetch_assoc($result))
	{
// Send email message to each email address in $line['WaitlisterEmail'], customizing the message sent using data from corresponding $line['WaitlisterName'] and $line['Locale'].
	$address = $line['WaitlisterEmail'];  // $line['WaitlisterEmail'] contains an email address for a mediator whose license expired yesterday.
	$thelocale = explode('_', $line['Locale']); // E.g. $thelocale[0] is Bakersfield, and $thelocale[1] is CA.
	$thelocale = $thelocale[0]; // Now $thelocale just holds the abbreviated form 'Bakersfield'
	$subject = $thelocale." Locale Now Available for Licensing";
	if ($line['WaitlisterName'] != '' && $line['WaitlisterName'] != null)
		{
		$body = "Hello ".html_entity_decode($line['WaitlisterName'], ENT_QUOTES)."\n\n"; // Personalize by appending the name if that field wasn't left blank when the waitlister completed the web form on waitlist.php. The html_entity_decode() converts apostrophes and other special characters (stored in coded form in the mediators_table) in names such as "Flannigan O'Driscoll".
		}
	else
		{
		$body = "Hello\n\n";
		};
		$body .= "Re. New Resolution Launch Platform License now available for ".html_entity_decode($thelocale, ENT_QUOTES)."\n\n";
		$body .= "Some time ago, you completed a form on our web site to request that we notify you when a license became available\nfor your chosen locale of ".$thelocale.". I'm delighted to inform you that this locale is now available.\n\n";
		$body .= "If you're still interested in securing a license for this locale in order to launch your mediation practice, I urge you to act\nquickly by visiting the following page (click on the link below):\n\n";
		$body .= "www.newresolutionmediation.com/mediationcareer.php\n\n";
		$body .= "There you'll find a list of license fees and license terms, starting as short as one month. We also offer our mediators a full refund\nany time within the first 30 days of your term. Simply select the term that best suits your needs and complete the online sign-up process.\n\n";
		$body .= "I look forward to welcoming you aboard the New Resolution Mediation Launch Platform. In the meantime, please don't hesitate to\ncontact me if you have any questions.\n\n";
		$body .= "Sincerely\n
Paul R. Merlyn
Chief Executive Officer
New Resolution LLC
paul@newresolutionmediation.com\n\n";
		$body .= "P.S. Please act now. Other mediators in your locale may also want to secure this license.";
		$headers = 
"From: paul@newresolutionmediation.com\r\n" .
"Reply-To: paul@newresolutionmediation.com\r\n" .
"Bcc: paulmerlyn@yahoo.com\r\n" .
"X-Mailer: PHP/" . phpversion();
		mail($address, $subject, $body, $headers);
	}
?>