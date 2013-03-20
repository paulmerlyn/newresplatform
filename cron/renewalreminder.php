<?php
/*
renewalreminder.php is a cron script. Its only purpose is to send an email reminder to every mediator (i) whose license will expire within the next month i.e. to every mediator for whom ExpirationDate is less than one month from the current date, and (ii) whose AdminFreeze flag isn't set to 1 (i.e. true). [I figure that if a licensee has done something egregious enough to be frozen out via an AdminFreeze, I don't necessarily want that mediator to be receiving renewal reminder notices. However, I may later change my mind about this policy.]
*/

/*
Calculate $value to $sigFigs significant figures. (Used in screen formatting of $MarginNow.)
Source: http://tamlyn.org/2008/12/formatting-bytes-with-significant-figures-in-php/comment-page-1/#comment-668
*/
function sigFig($value, $sigFigs) 
{
	if ($value == 0) return 0; // My addition of this line seems to help prevent a "Warning: division by zero"
	//Convert to scientific notation e.g. 12345 -> 1.2345x10^4 where $significand is 1.2345 and $exponent is 4
	$exponent = floor(log10(abs($value))+1);
	$significand = round(($value/pow(10, $exponent)) * pow(10, $sigFigs)) / pow(10, $sigFigs);
	return $significand * pow(10, $exponent);
}

// Connect to my mysql database.
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
or die('Could not connect: ' . mysql_error());
mysql_select_db('paulme6_newresolution') or die('Could not select database');

// Query the mediators_table table to select ID, UseProfessionalEmail, Email, ProfessionalEmail, and EmailPersonal for mediators where ExpirationDate is within one month of today's date AND ExpirationDate is either (30 OR 25 OR 15 OR 5 OR 2 OR 1 OR 0 DAYS) from today's date, (ii) the mediator's AdminFreeze is not equal to 1, and (iii) the mediator has at least one email address that is neither null nor blank. (Note: because demo mediators don't have license fees and renewal fees associated with them, we only need to query mediators_table, not mediators_table_demo.) So, basically, if the current month has 30 or 31 days, the reminder gets sent out 30, 25, 15, 5, 2, 1, and 0 days before expiration; if the current month has less than 30 days, the reminder gets sent out 25, 15, 5, 2, 1, and 0 days before expiration.
$query = "SELECT ID, UseProfessionalEmail, Email, ProfessionalEmail, EmailPersonal FROM mediators_table WHERE TIMESTAMPDIFF(MONTH, CURDATE(), ExpirationDate) < 1 AND (TIMESTAMPDIFF(DAY, CURDATE(), ExpirationDate) = 30 OR TIMESTAMPDIFF(DAY, CURDATE(), ExpirationDate) = 25 OR TIMESTAMPDIFF(DAY, CURDATE(), ExpirationDate) = 15 OR TIMESTAMPDIFF(DAY, CURDATE(), ExpirationDate) = 5 OR TIMESTAMPDIFF(DAY, CURDATE(), ExpirationDate) = 2 OR TIMESTAMPDIFF(DAY, CURDATE(), ExpirationDate) = 1 OR TIMESTAMPDIFF(DAY, CURDATE(), ExpirationDate) = 0) AND AdminFreeze != 1 AND ((ProfessionalEmail IS NOT NULL AND ProfessionalEmail != '') OR (Email IS NOT NULL AND Email != '') OR (EmailPersonal IS NOT NULL AND EmailPersonal != ''))";
$result = mysql_query($query) or die('Query (select those mediators who should receive a renewal reminder) failed: ' . mysql_error());

// Populate 2-D array $EmailTargets[][] to hold all email addresses to which I should send a reminder message. When a mediator has elected to use a professional email address (i.e. UseProfessionalEmail == 1) rather than his/her regular email address (Email), use ProfessionalEmail. Also, if the mediator has provided a value for EmailPersonal that differs from his/her other email address, include that into the array as well.
// Note: If the mediator hasn't yet entered any email address(es) in his/her mediator profile (via updateprofile.php), then don't bother storing anything in $EmailTargets for such a mediator.
// Structure of 2-D array $EmailTargets[row][col]: the first element in each row (i.e. col=0) shall contain the ID of a mediator who qualifies for receiving a renewal reminder message; the other columns in each row (i.e. col=1, col=2, etc.) shall contain the email address(es) pertaining to each mediator ID. Example: if mediator Jill Smith has an ID of 43, Email = jillsmith@sbcglobal.net, and EmailPersonal = jillygrl608@hotmail.com, then $EmailTargets[$i][0] will be 43, $EmailTargets[$i][1] will be 'jillsmith@sbcglobal.net', and $EmailTargets[$i][2] will be 'jillygrl608@hotmail.com'.
$EmailTargets = array(); // I don't think this array definition step is strictly necessary.
$i = 0;
while ($line = mysql_fetch_assoc($result))
	{
	$EmailTargets[$i] = array(); // I don't think this array definition step is strictly necessary.
	if (($line['ProfessionalEmail'] != '' AND $line['ProfessionalEmail'] != null) OR ($line['Email'] != '' AND $line['Email'] != null) OR ($line['EmailPersonal'] != '' AND $line['EmailPersonal'] != null))
		{
		array_push($EmailTargets[$i], $line['ID']); // If there's at least one non-blank ProfessionalEmail or Email or EmailPersonal in the $line results set, the assign the mediator's ID to $EmailTargets[$i][0] and proceed to push those email address(es) into $EmailTargets[$i][1], $EmailTargets[$i][2], etc.
		if ($line['UseProfessionalEmail'] == 1 AND $line['ProfessionalEmail'] != '' AND $line['ProfessionalEmail'] != null) 
			{
			array_push($EmailTargets[$i], $line['ProfessionalEmail']); // Only assign a ProfessionalEmail address as a column of the $EmailTargets 2-D array for row  $i in the 2D array $EmailTargets if the UseProfessionalEmail flag is set AND the ProfessionalEmail address is neither blank nor null.
			}
		if ($line['UseProfessionalEmail'] == 0 AND $line['Email'] != '' AND $line['Email'] != null) 
			{
			array_push($EmailTargets[$i], $line['Email']); // Only assign Email address as a column of the $EmailTargets 2-D array for row  $i in the 2D array $EmailTargets if the UseProfessionalEmail flag is not set AND the Email address is neither blank nor null.
			}
		if ($line['EmailPersonal'] != '' AND $line['EmailPersonal'] != null) 
			{
			array_push($EmailTargets[$i], $line['EmailPersonal']); // Only assign EmailPersonal address as a column of the $EmailTargets 2-D array for row  $i in the 2D array $EmailTargets if the EmailPersonal address is neither blank nor null.
			}
		$i += 1; // Increment the row counter $i and proceed to next row in the $line resultset.
		}
	} 
// echo 'Here is $EmailTargets: ';print_r($EmailTargets); echo '<br><br>';

/*
Step 1: Loop through the rows of the $EmailTargets 2-D array, composing and dispatching an email reminder to every email address in the columns of each row
*/
foreach ($EmailTargets as $item)
	{
	$id = array_shift($item); // Assign to $id the value of the col=0 element in each row of $EmailTargets. Note that the array_shift() function also removes this element from the $item array row.
	$query = "SELECT ID, Name, DATE_FORMAT(ExpirationDate, '%M %e, %Y') as ExpDateFormatted, TIMESTAMPDIFF(DAY, CURDATE(), ExpirationDate) AS DaysRemaining, Locale, Margin FROM mediators_table WHERE ID = '$id'";
	$result = mysql_query($query) or die('Query (select Name, ExpirationDate, and Locale) failed: ' . mysql_error());
	$row = mysql_fetch_assoc($result);
	$address = join(',', $item);  // Having lopped off the ID element (i.e. $EmailTargets[][0] from $item, the $item array for each mediator now just contains the email address(es) for that mediator, which I can combine into a comma-separated string via the join() function.
	if ($row['DaysRemaining'] == 0) $subject = "Final Notice of License Expiration";
	else $subject = "Renewal Reminder";
$body = "Hello ".html_entity_decode($row['Name'], ENT_QUOTES)."\n\n";
$body .= "Re. Expiration of your license for ".html_entity_decode($row['Locale'], ENT_QUOTES)."\n\n";
if ($row['DaysRemaining'] == 0) $body .= "Your license expires today. You must now click on the following link today to avoid loss of your license:\nwww.newresolutionmediation.com/renew.php\n\nUpon expiration, your license will automatically be released for sale to other mediators in your locale.\n\n";
else $body .= "Your license will expire on ".$row['ExpDateFormatted'].". Please click on the following link today in order to protect your license:\nwww.newresolutionmediation.com/renew.php\n\n To avoid loss of your license, I urge you to act now before it soon expires. Upon expiration, your license will automatically be released for sale to other mediators in your locale.\n\n";
if ($row['DaysRemaining'] > 0) $body .= "If you prefer to renew via paper check or telephone, please contact our support desk at support@newresolutionmediation.com.\n\n";
$body .= "Sincerely\n
Paul R. Merlyn
Chief Executive Officer
New Resolution LLC
paul@newresolutionmediation.com\n\n";
$body .= "P.S. Please consider these compelling reasons to renew today:\n
1. Don't surrender the recognition you've built through your association with the New Resolution brand.
2. If you ever want to rejoin after your license has lapsed, a license may no longer be available in your locale.
3. We reward loyalty by generally offering lower fees to existing mediators than we offer to new mediators. 
4. Don't lose the protection of your guaranteed maximum fee increase (just ".sigFig($row['Margin'], 4)."% + inflation) by letting your license expire.";
$headers = 
"From: paul@newresolutionmediation.com\r\n" .
"Reply-To: paul@newresolutionmediation.com\r\n" .
"Bcc: paulmerlyn@yahoo.com\r\n" .
"X-Mailer: PHP/" . phpversion();
mail($address, $subject, $body, $headers);
	}
?>