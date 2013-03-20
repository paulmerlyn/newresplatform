<?php
/*
This script is used as a php include only. It isn't called directly. It retrieves fundamental parameters from the DB's parameters_table table such as the default APR for license fee increases and the license fees and associated license terms 
*/
//session_start();

// Connect to mysql and select database
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
	or die('Could not connect: ' . mysql_error());

mysql_select_db('paulme6_newresolution') or die('Could not select database');

// Formulate query to retrieve all parameters from parameters_table. 
$query = "select * from parameters_table";

$result = mysql_query($query) or die('The SELECT query of parameters_table i.e. '.$query.' failed: ' . mysql_error());
$row = mysql_fetch_assoc($result);

$DefaultAPR = $row['DefaultAPR'];

$MarginNow = $row['MarginNow'];

$feetermstring = $row['FeeTerm'];
//$feetermstring = substr($feetermstring, 0, strcspn($feetermstring, '"')-1); // Omit anything from the first " character onwards.
$FeeTermArray = explode(',', $feetermstring); // Example: $FeeTermArray[0] = 220, $FeeTermArray[1] = 1, $FeeTermArray[2] = 625, $FeeTermArray[3] = 3, etc. when we have a $220 fee for a 1-month term, a $625 fee for a 3-month term, etc.

//Create associative array $FeeForTermArray whereby $FeeForTermArray[1] has value 220 when the fee for 1 month is $220; $FeeForTermArray[6] has value 1200 when the fee for a 6-mth term is $1200; $FeeForTermArray[12] has value 2000 when the fee for a 1-year (i.e. 12 mth) term is $2000, etc.
$SizeOfFeeTermArray = count($FeeTermArray);
$NofFeeTermPairs = $SizeOfFeeTermArray/2; // The number of Fee-Term value pairs will be one-half the number of items in the $FeeTermArray array.
for ($i=1; $i <= $SizeOfFeeTermArray; $i=$i+2)
{
$FeeForTermArray[$FeeTermArray[$i]] = $FeeTermArray[$i-1];
}
?>