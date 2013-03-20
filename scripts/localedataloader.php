<?
/* Source: http://www.webmasterworld.com/databases_sql_mysql/3268434.htm 
Note: an alternative script (recommended by InMotionHoting but not needed or tested by me) is available at: http://www.legend.ws/blog/tips-tricks/csv-php-mysql-import/ 
See http://dev.mysql.com/doc/refman/5.0/en/load-data.html for syntax details on info on reverse process, "SELECT * INTO OUTFILE ... */

/* In addition to uploading new "static" data (i.e. Locale, LocaleShort, StateStates, Population, and MaxLicenses) into locales_table, localedataloader.php also recalculates the "dynamic" data fields in locales_table (i.e. Exclusive, NofLicenses, and Full). */

// THESE ARE THE ONLY TWO LINES YOU'LL PROBABLY NEED TO CHANGE
//Configure file path and table name
$source_file = "/home/paulme6/public_html/nrmedlic/dbdata/Locale_Data_011610_Keyed.csv";
$destination_table = "locales_table";

//call to function
$luinfo = load_file_data($source_file, $destination_table);

// LOAD DATA INFILE
// EMPTY TABLE BEFORE LOAD
function load_file_data($source_file, $destable)
{ 
# first get a mysql connection as per the FAQ
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
or die('Could not connect: ' . mysql_error());
mysql_select_db('paulme6_newresolution') or die('Could not select database: ' . mysql_error());

// Empty table
$query = "DELETE FROM $destable";
$result = mysql_query($query);

// do the data import
//$query = "LOAD DATA LOCAL INFILE \"$source_file\" INTO TABLE $destable";
$query = "LOAD DATA LOCAL INFILE \"$source_file\" INTO TABLE $destable FIELDS TERMINATED BY ',' (`Locale`,`LocaleShort`,`LocaleStates`,`Population`,`MaxLicenses`)";

$result = mysql_query($query) or die("Query (The LOAD DATA INFILE operation failed: \nINPUT FILE: $source_file \nTABLE: $destable \nERROR: ".mysql_error());
if (!$result) {	echo 'No $result was achievable.'; };

// Recalculate the values of the Exclusive field in the locales_table by reference to the value of Exclusive in each row of mediators_table.
$query = "UPDATE $destable, mediators_table SET $destable.Exclusive = 1 WHERE $destable.Locale = mediators_table.Locale AND mediators_table.Exclusive = 1";
$result = mysql_query($query) or die("Attempt to recalculate the Exclusive field failed. The query is: ".$query." and the error is: ".mysql_error());

// Recalculate the values of the NofLicenses field in the locales_table by going through each row of mediators_table and incrementing NofLicenses in locales_table for each mediator row whose AdminFreeze is 0 OR whose (AdminFreeze = 1 AND PrevRslWhlFrzn = 1).
// Note that I tried (and failed) to do this with a single query statement using a join! Harder than it seems ... "UPDATE $destable, mediators_table SET $destable.NofLicenses = $destable.NofLicenses + 1 WHERE $destable.Locale = mediators_table.Locale AND (mediators_table.AdminFreeze = 0 || (mediators_table.AdminFreeze = 0 && mediators_table.PrevRslWhlFrzn = 1))" doesn't work! So I gave up and decided to loop through the rows of mediators_table instead.
$query = "SELECT Locale, AdminFreeze, PrevRslWhlFrzn FROM mediators_table";
$result = mysql_query($query) or die('Query (select Locale, AdminFreeze, and PrevRslWhlFrzn from mediators_table) failed: ' . mysql_error());
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['AdminFreeze'] = 0 || ($row['AdminFreeze'] = 1 && $row['PrevRslWhlFrzn'] = 1)) 
		{
		$query = "UPDATE $destable SET NofLicenses = NofLicenses + 1 WHERE Locale = '".$row['Locale']."'";
		mysql_query($query) or die('Query (UPDATE of locales_table to increment NofLicenses) failed: ' . mysql_error());
		}
	}

// Recalculate the values of the Full field in the locales_table. Note that if a particular row of locales_table has Exclusive = 1 then I've chosen the convention of saying this locale is not full (i.e. Full is true when a locale has the maximum complement of mediators, not when it has one exclusive mediator). Otherwise, presuming a row in locales_table has Exclusive = 0, then that row's Full will be 0 if NofLicenses < MaxLicenses, and Full will be 1 if NofLicense = MaxLicenses.
$query = "SELECT Exclusive, NofLicenses, MaxLicenses, Locale FROM $destable";
$result = mysql_query($query) or die('Query (select Exclusive, NofLicenses, MaxLicenses from $destable) failed: ' . mysql_error());
while ($row = mysql_fetch_assoc($result))
	{
	if ($row['Exclusive'] == 1 || $row['NofLicenses'] < $row['MaxLicenses'])
		{
		$query = "UPDATE $destable SET Full = 0 WHERE Locale = '".$row['Locale']."'";
		}
	else
		{
		$query = "UPDATE $destable SET Full = 1 WHERE Locale = '".$row['Locale']."'";
		}
	mysql_query($query) or die('Query (UPDATE of Full field in locales_table) failed: ' . mysql_error());
	}

echo 'Execution of loader script was successful. Dynamic data in the locales_table (i.e. Exclusive, NofLicenses, and Full) has also been recalculated. Examine the database table to check results.';
}

?> 