<?php
/*
seolocationslist.php generates a list of mediator locations from the administrator-only-controlled SEOlocations field in mediators_table. Note that the Administrator can populate the SEOlocations field of the table either directly into the DB via cPanel or via the admin5.php 'Manage Mediators' administrator module.
*/

// Establish a mysql connection.
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
or die('Could not connect: ' . mysql_error());
mysql_select_db('paulme6_newresolution') or die('Could not select database: ' . mysql_error());

$query = "SELECT SEOlocations, State FROM mediators_table WHERE (Suspend != 1 AND AdminFreeze != 1 AND ID != 100 AND ID != 101 AND ID != 102 AND ID != 103 AND ID != 104 AND ID != 105 AND ID != 106 AND ID != 107 AND ID != 108 AND ID != 109 AND ID != 110)"; 
// When retrieving the SEOlocations value for each mediator, don't bother to retrieve (or maybe just retrieve one of) my several own profiles (don't retrieve duplicates across my multiple locales) and any other non-suspeneded or frozen mediator's profiles
$result = mysql_query($query) or die('The SELECT query of SEOlocations values has failed i.e. '.$query.' failed: ' . mysql_error());

// Formulate the string of SEOlocations values from the query result.
$thestring = '';
while ($row = mysql_fetch_assoc($result))
	{
	$thestring .= $row['SEOlocations'].' ('.$row['State'].'), ';
	}
// Delete last two characters - superfluous ", " using string functions, extracting the substring from 0 (i.e. beginning) to two characters less than the full string length (to lop off the final ', '.
$thestring = substr($thestring, 0, strlen($thestring) - 2);

// Echo the string
echo $thestring;
?>