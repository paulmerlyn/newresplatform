<?php
/*
expirationrelease.php is a cron script that runs daily and takes action on the day after a mediator's license has expired. Specifically, for each mediator for whom the current date is the day after his/her license expiration date (ExpirationDate in the mediators_table), the script (i) updates the locales_table's Exclusive, NofLicenses, and Full columns for that mediator's Locale; (ii) updates the mediators_table by decommissioning expired mediators e.g. by setting their AdminFreeze=1; and (iii) sends to all email addresses associated with that mediator a "sorry to see you leaving ... and don't use our intellectual property" message.
Note: MAKE SURE YOU DON"T RUN THIS CRON SCRIPT MORE THAN ONCE PER DAY b/c if it is run n times on the same day it will decrement NofLicenses n times for the same expiring license when in fact only a single decrement is intended (for each expiring license).
*/

// Connect to my mysql database.
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
or die('Could not connect: ' . mysql_error());
mysql_select_db('paulme6_newresolution') or die('Could not select database');

// Use an inner join to query the mediators_table and locales_table, selecting email addresses and other useful data from both tables for all mediators for whom curdate() is the day after ExpirationDate.
$query = "SELECT mediators_table.ID, mediators_table.Name, mediators_table.UseProfessionalEmail, mediators_table.ProfessionalEmail, mediators_table.Email, mediators_table.EmailPersonal, mediators_table.AdminFreeze, mediators_table.PrevRslWhlFrzn, mediators_table.Locale, DATE_FORMAT(mediators_table.DofAdmission, '%M %e, %Y') as AdmitDateFormatted, locales_table.Exclusive, locales_table.NofLicenses, locales_table.Full, locales_table.LocaleShort FROM mediators_table, locales_table WHERE TIMESTAMPDIFF(DAY, mediators_table.ExpirationDate, CURDATE()) = 1 AND mediators_table.Locale = locales_table.Locale";
$result = mysql_query($query) or die('Query (select data from mediators_table and locales_table for expired mediators) failed: ' . mysql_error());

while ($line = mysql_fetch_assoc($result))
	{
/* Update locales_table for all mediator licenses whose locales should now be released because the mediator's license has expired. */
	switch ($line['AdminFreeze'])
		{
		case 0 : // Expired mediator did not have a frozen account. Proceed to define query that decrements NofLicenses, sets Exclusive to 0, and sets Full to 0 in locales_table for Locale = $line['Locale'].
			$query = "UPDATE locales_table SET NofLicenses = NofLicenses - 1, Exclusive = 0, Full = 0 WHERE Locale = '".$line['Locale']."'";
			break;
		case 1 : // Expired mediator has a frozen account. Look at value of PrevRslWhlFrzn in order to determine the appropriate values to set for NofLicenses in locales_table.
			if ($line['PrevRslWhlFrzn'] == 1)
				{
				$query = "UPDATE locales_table SET NofLicenses = NofLicenses - 1, Exclusive = 0, Full = 0 WHERE Locale = '".$line['Locale']."'"; // Since the mediator, although frozen, had been flagged to prevent resale of his/her license, NofLicenses in locales_table hadn't been previously decremented, so we now need to decrement NofLicenses given that his/her license has expired.
				}
			else // The license had been previously released for resale.
				{
				$query = "UPDATE locales_table SET Exclusive = 0, Full = 0 WHERE Locale = '".$line['Locale']."'"; // The mediator has a frozen account and has previously had his/her license released for resale, so NofLicenses in locales_table has already been decremented (effected when the AdminFreeze got applied, probably via administrator module admin5A.php) and thus there's no need to decrement NofLicenses now that his/her license has expired.
				};
				break;
		default :
			echo 'Error: Unable to determine an appropriate value for AdminFreeze in expirationrelease.php.<br>';
			exit;
		}
	$resultA = mysql_query($query) or die('The attempt to update NofLicenses, Exclusive, and Full in the locales_table has failed. ' . mysql_error()); // Note that I label this result resource as '$resultA' rather than the usual label of '$result'. That's because '$result' is already being used for looping through in the while statement above.

/* Update mediators_table for this "decommissioned" mediator (set AdminFreeze = 1, set PrevRslWhlFrzn = 0, set Suspend = 1, Exclusive = 0, UseProfessionalEmail = 0, ProfessionalEmail = '') */
	$query = "UPDATE mediators_table SET AdminFreeze = 1, PrevRslWhlFrzn = 0, Suspend = 1, Exclusive = 0, UseProfessionalEmail = 0, ProfessionalEmail = '' WHERE ID = ".$line['ID'];
	$resultA = mysql_query($query) or die('The attempt to update the mediators_table for a decommissioned mediator has failed. ' . mysql_error()); // Note that I label this result resource as '$resultA' rather than the usual label of '$result'. That's because '$result' is already being used for looping through in the while statement above.

	
/* Create an array of email addresses (culling ProfessionalEmail if available, Email if available, and EmailPersonal if available) to which a message should be sent saying "sorry to see you leave" and "don't use our intellectual property now that your license has expired".  */
	$EmailTargets = array(); // Each time we go through the while loop, reinitialize $EmailTargets as an empty array.
	if (($line['ProfessionalEmail'] != '' AND $line['ProfessionalEmail'] != null) OR ($line['Email'] != '' AND $line['Email'] != null) OR ($line['EmailPersonal'] != '' AND $line['EmailPersonal'] != null))
		{
		if ($line['UseProfessionalEmail'] == 1 AND $line['ProfessionalEmail'] != '' AND $line['ProfessionalEmail'] != null) 
			{
			array_push($EmailTargets, $line['ProfessionalEmail']); // Only push ProfessionalEmail address as an element of the $EmailTargets array if the UseProfessionalEmail flag is set AND the ProfessionalEmail address is neither blank nor null.
			}
		if ($line['UseProfessionalEmail'] == 0 AND $line['Email'] != '' AND $line['Email'] != null) 
			{
			array_push($EmailTargets, $line['Email']); // Only push Email address as an element of the $EmailTargets array if the UseProfessionalEmail flag is not set AND the Email address is neither blank nor null.
			}
		if ($line['EmailPersonal'] != '' AND $line['EmailPersonal'] != null) 
			{
			array_push($EmailTargets, $line['EmailPersonal']); // Only push EmailPersonal address as an element of the $EmailTargets array if the EmailPersonal address is neither blank nor null.
			}
		}

// Send email message to each email address for the expired licensee if he/she has at least one address i.e. if the size of $EmailTargets is greater than 0.
	if (sizeof($EmailTargets) > 0)
		{
		$address = implode(',', $EmailTargets);  // The $item array just contains an email address for a mediator whose license expired yesterday. I combine all such addresses for this mediator (there may be, say, two addresses - e.g. ProfessionalEmail and EmailPersonal) into a comma-separated string via the implode() function.
		$subject = "Expired License";
		$body = "Hello ".html_entity_decode($line['Name'], ENT_QUOTES)."\n\n";
		$body .= "Re. Expired License\n\n";
		$body .= "Your license for ".$line['LocaleShort']." has now expired. We're sorry to see you go, but thank you for being a valued client since ".$line['AdmitDateFormatted'].". We hope we've enhanced your journey into professional mediation in many ways.\n\nYour license is now available for resale to other mediators in your locale. Please let me know of any friends or colleagues who may be interested in purchasing a license in this locale or in any other of our 940 locales nationwide. We reward such referrals with a generous referral commission. Simply contact me for details.\n\n";
		$body .= "Finally, please remember that only our licensed mediators may use New Resolution's intellectual property, including the New Resolution name, logo, email address, and copyrighted materials. In order to protect the New Resolution brand and our licensed mediators, we actively monitor all locales for any unauthorized usage. We also seek compensatory damages through civil action in accordance with California Code of Civil Procedure Section 638. Thank you for respecting our property rights.\n\n";
		$body .= "Sincerely\n
Paul R. Merlyn
Chief Executive Officer
New Resolution, LLC
paul@newresolutionmediation.com\n\n";
		$headers = 
"From: paul@newresolutionmediation.com\r\n" .
"Reply-To: paul@newresolutionmediation.com\r\n" .
"Bcc: paulmerlyn@yahoo.com\r\n" .
"X-Mailer: PHP/" . phpversion();
		mail($address, $subject, $body, $headers);
		}

	// Finally, we must call createutilitymediatordata.php, which will rewrite utilitymediatordata.js on the server, removing, for example, any mediators that are now expired. But before running that script, we need to provide two variable values:
	//	(i) The variable $dbmediatorstablename is used by createutilitymediator.php. It needs to be set to 'mediators_table'. (ii) The variable $_SESSION['ClientDemoSelected'] is used by democlientpaths2.php, which is in include'd file within createutilitymediator.php. It needs to be set to 'client'.
	$dbmediatorstablename = 'mediators_table';
	$_SESSION['ClientDemoSelected'] = 'client';
	require('../ssi/createutilitymediatordata.php'); // Include the createutilitymediatordata.php file, which in this instance will be run at the client level (i.e. utilitymediatordata.js stored in /scripts/). I use an SSI include here for modularity because the same code block is used elsewhere in processprofile.php and admin6.php.

	} // End of while loop

echo 'expirationrelease.php has been run.';
?>