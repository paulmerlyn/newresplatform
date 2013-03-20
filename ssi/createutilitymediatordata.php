<?php
/*
This code creates the javascript mediator data file, utilitymediatordata.js, for either the client level (stored in /scripts/) or the demo level (stored in /demo/scripts/). It is incorporated into processprofile.php and admin6.php via a require() statement.
*/
// Retrieve all rows from database in which the 'Suspend' column is 0 (i.e. false), and place them in resource $row.
$query = "select * from ".$dbmediatorstablename." where Suspend = 0";
$result = mysql_query($query) or die('Query (select) failed: ' . mysql_error());

// Begin to construct string that will be placed in file utilitymediatordata.js
$myString = "var mediatorID = new Array();\n";
$i = 0;
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		$myString = $myString.'mediatorID['.$i.'] = '.$row["ID"].";\n";
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorName = new Array(mediatorID.length);\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		$myString = $myString.'mediatorName['.$i.'] = "'.$row["Name"].'";'."\n";
		$i += 1;
		}
	}
	
$myString = $myString."\nvar mediatorCred = new Array(mediatorID.length);\n";
$i = 0;
mysql_data_seek($result, 0); // Move the internal pointer back to the first row (i.e. row 0) in $result.
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		$myString = $myString.'mediatorCred['.$i.'] = "'.$row["Credentials"].'";'."\n";
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorLocale = new Array(mediatorID.length);\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		$myString = $myString.'mediatorLocale['.$i.'] = "'.$row["Locale"].'";'."\n";
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorLocaleLabel = new Array(mediatorID.length);\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		$myString = $myString.'mediatorLocaleLabel['.$i.'] = "'.$row["LocaleLabel"].'";'."\n";
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorLocations = new Array(mediatorID.length);\n";
$myString = $myString."var index;\n";
$myString = $myString."for (index in mediatorID)\n";
$myString = $myString."{\n";
$myString = $myString."mediatorLocations[index] = new Array()\n";
$myString = $myString."}\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		$myString = $myString.'mediatorLocations['.$i.'] = new Array('.$row["Locations"].');'."\n";
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorEmail = new Array(mediatorID.length);\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		if ($row['UseProfessionalEmail'] == 1) // Assign to the Javascript mediatorEmail[] array either the DB's ProfessionalEmail value or the DB's Email value depending on whether the UseProfessionalEmail checkbox was checked.
			{ $myString = $myString.'mediatorEmail['.$i.'] = "'.$row["ProfessionalEmail"].'";'."\n"; }
		else
			{ $myString = $myString.'mediatorEmail['.$i.'] = "'.$row["Email"].'";'."\n"; };
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorEntityName = new Array(mediatorID.length);\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		$myString = $myString.'mediatorEntityName['.$i.'] = "'.$row["EntityName"].'";'."\n";
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorPrincipalStreet = new Array(mediatorID.length);\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		$myString = $myString.'mediatorPrincipalStreet['.$i.'] = "'.$row["PrincipalStreet"].'";'."\n";
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorPrincipalAddressOther = new Array(mediatorID.length);\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		$myString = $myString.'mediatorPrincipalAddressOther['.$i.'] = "'.$row["PrincipalAddressOther"].'";'."\n";
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorCity = new Array(mediatorID.length);\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		$myString = $myString.'mediatorCity['.$i.'] = "'.$row["City"].'";'."\n";
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorState = new Array(mediatorID.length);\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		$myString = $myString.'mediatorState['.$i.'] = "'.$row["State"].'";'."\n";
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorZip = new Array(mediatorID.length);\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		$myString = $myString.'mediatorZip['.$i.'] = "'.$row["Zip"].'";'."\n";
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorTelephone = new Array(mediatorID.length);\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		$myString = $myString.'mediatorTelephone['.$i.'] = "'.$row["Telephone"].'";'."\n";
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorFax = new Array(mediatorID.length);\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		$myString = $myString.'mediatorFax['.$i.'] = "'.$row["Fax"].'";'."\n";
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorProfile = new Array(mediatorID.length);\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		$myString = $myString.'mediatorProfile['.$i.'] = "'.$row["Profile"].'";'."\n";
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorHourlyRate = new Array(mediatorID.length);\n";
$myString = $myString."var index;\n";
$myString = $myString."for (index in mediatorID)\n";
$myString = $myString."{\n";
$myString = $myString."mediatorHourlyRate[index] = new Array(2)\n";
$myString = $myString."}\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		$myString = $myString.'mediatorHourlyRate['.$i.'] = new Array('.$row["HourlyRate"].');'."\n";
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorAdminCharge = new Array(mediatorID.length);\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		switch($row["AdminCharge"])
			{
			case 0:
				$myString = $myString.'mediatorAdminCharge['.$i.'] = false;'."\n";
				break;
			case 1:
				$myString = $myString.'mediatorAdminCharge['.$i.'] = true;'."\n";
				break;
			}			
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorAdminChargeDetails = new Array(mediatorID.length);\n";
$myString = $myString."var index;\n";
$myString = $myString."for (index in mediatorID)\n";
$myString = $myString."{\n";
$myString = $myString."mediatorAdminChargeDetails[index] = new Array(2)\n";
$myString = $myString."}\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		$myString = $myString.'mediatorAdminChargeDetails['.$i.'] = new Array('.$row["AdminChargeDetails"].');'."\n";
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorLocationCharge = new Array(mediatorID.length);\n";
$myString = $myString."var index;\n";
$myString = $myString."for (index in mediatorID)\n";
$myString = $myString."{\n";
$myString = $myString."mediatorLocationCharge[index] = new Array(3)\n";
$myString = $myString."}\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		$myString = $myString.'mediatorLocationCharge['.$i.'] = new Array('.$row["LocationCharge"].');'."\n";
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorPackages = new Array(mediatorID.length);\n";
$myString = $myString."var index;\n";
$myString = $myString."for (index in mediatorID)\n";
$myString = $myString."{\n";
$myString = $myString."mediatorPackages[index] = new Array(9)\n";
$myString = $myString."}\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		$myString = $myString.'mediatorPackages['.$i.'] = new Array('.$row["Packages"].');'."\n";
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorSlidingScale = new Array(mediatorID.length);\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		switch($row["SlidingScale"])
			{
			case 0:
				$myString = $myString.'mediatorSlidingScale['.$i.'] = false;'."\n";
				break;
			case 1:
				$myString = $myString.'mediatorSlidingScale['.$i.'] = true;'."\n";
				break;
			}			
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorIncrement = new Array(mediatorID.length);\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		$myString = $myString.'mediatorIncrement['.$i.'] = "'.$row["Increment"].'";'."\n";
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorConsultationPolicy = new Array(mediatorID.length);\n";
$myString = $myString."var index;\n";
$myString = $myString."for (index in mediatorID)\n";
$myString = $myString."{\n";
$myString = $myString."mediatorConsultationPolicy[index] = new Array(5)\n";
$myString = $myString."}\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		$myString = $myString.'mediatorConsultationPolicy['.$i.'] = new Array('.$row["ConsultationPolicy"].');'."\n";
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorCancellationPolicy = new Array(mediatorID.length);\n";
$myString = $myString."var index;\n";
$myString = $myString."for (index in mediatorID)\n";
$myString = $myString."{\n";
$myString = $myString."mediatorCancellationPolicy[index] = new Array(3)\n";
$myString = $myString."}\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		$myString = $myString.'mediatorCancellationPolicy['.$i.'] = new Array('.$row["CancellationPolicy"].');'."\n";
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorTelephoneMediations = new Array(mediatorID.length);\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		switch($row["TelephoneMediations"])
			{
			case 0:
				$myString = $myString.'mediatorTelephoneMediations['.$i.'] = false;'."\n";
				break;
			case 1:
				$myString = $myString.'mediatorTelephoneMediations['.$i.'] = true;'."\n";
				break;
			}			
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorVideoConf = new Array(mediatorID.length);\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		switch($row["VideoConf"])
			{
			case 0:
				$myString = $myString.'mediatorVideoConf['.$i.'] = false;'."\n";
				break;
			case 1:
				$myString = $myString.'mediatorVideoConf['.$i.'] = true;'."\n";
				break;
			}			
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorCardPolicy = new Array(mediatorID.length);\n";
$myString = $myString."var index;\n";
$myString = $myString."for (index in mediatorID)\n";
$myString = $myString."{\n";
$myString = $myString."mediatorCardPolicy[index] = new Array(3)\n";
$myString = $myString."}\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		$myString = $myString.'mediatorCardPolicy['.$i.'] = new Array('.$row["CardPolicy"].');'."\n";
		$i += 1;
		}
	}

$myString = $myString."\nvar mediatorServiceLevel = new Array(mediatorID.length);\n";
$i = 0;
mysql_data_seek($result, 0);
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Name'] != '') // Adding this condition prevents a row from the DB getting placed inside  utilitymediatordata.js file for "just-created" mediator profiles such as test-drive accounts where the mediator hasn't yet logged in and completed the full details (LocaleLabel, Telephone, Locations, etc.) of the profile. 'Empty' profiles  in the javascript file will cause the site to crash and/or display blank content.
		{
		switch($row["ServiceLevel"])
			{
			case 0:
				$myString = $myString.'mediatorServiceLevel['.$i.'] = false;'."\n";
				break;
			case 1:
				$myString = $myString.'mediatorServiceLevel['.$i.'] = true;'."\n";
				break;
			}			
		$i += 1;
		}
	}

require('democlientpaths2.php'); // Include the democlientpaths2.php file, which provides the ssi path (i.e. $ssipathlong) for either the client case or the demo case as appropriate. Note that the ssi file democlientpaths2.php is similar to but different from the script file democlientpaths1.php.
$scriptpathshort = $_SESSION['scriptpathshort'];

// Opening file
$fp = fopen($scriptpathshort."utilitymediatordata.js","w");

// Attempt to apply an exclusive lock
$lk = flock($fp, LOCK_EX);
if (!$lk) echo "Error locking the utilitymediatordata.js file!";

// Write to file
fwrite($fp, $myString, strlen($myString));

// Unlock the file (this would get done by the o/s automatically on exit of the script, but this is a good safe thing to do in case the script served by Apache doesn't end.
flock($fp, LOCK_UN);

// Closing file
fclose($fp);

// Free resultset
mysql_free_result($result);
?>