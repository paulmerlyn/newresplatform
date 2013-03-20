<?php
/*
This script is used as a php include only. It isn't called directly. It contains the calculateOfferPrice($ID, $term) function for calculating the license renewal or "newnewal" price to be offered to an existing mediator of ID=$ID when seeking a renewal (or "newnewal") term=$term. It is used by ipn.php when seeking to confirm that a license purchase is legitimate by reconciling the amount tendered by the PayPal payer and the offer price for that license term (or, strictly, the lesser of the offer price and the public fee as defined by the public price list for new mediators signing up for a particular term).
This function isn't necessary for validating a purchase by new licensees (i.e. mediators who are signing up for the first time). For new licensees, ipn.php validates their payment amounts by straight comparison with the public price list. No calculation is necessary.
*/

require '/home/paulme6/public_html/nrmedlic/ssi/obtainparameters.php'; // Include the obtainparameters.php file, which retrieves values $DefaultAPR and $FeeTermArray array from the parameters_table table. The latter is used for price-checking by the confirmProduct() function in ipn.php. Note that offerpricecalculator.php itself includes obtainparameters.php because it provides easy access to the public price list, which calculateOfferPrice($ID, $term) uses below. Note also the use of an absolute file path to avoid high risk of file path confusion with relative path with use of nested require statements i.e. when an included file itself includes another file. The confusion arises b/c the relative path is relative to the top-level file, not the lower-level nested file.

// Scenario: Mediator with ID=398 wants to renew or "newnew". Her existing term is, say, 12 months. She wants to renew for another 12-month term (i.e. $term = 12). Or she wants to "newnew" for 18 months ($term = 18). What is the offer price? The methodology for this calculation was originally described and created for the Javascript calculateNewFee() function inside admin5A.php. It is further illustrated in Excel file = 'License Fee Pricing.xls'. Whereas use of Javascript was appropriate in admin5A.php, here inside ipn.php it makes more sense to use PHP. Either way, the principles of the calculation are the same, calculating the correct "offer price" for a given term using a simplelinear cost calculation that is then adjusted using a raw discount and then finally compared with the public fee at the time. */
function calculateOfferPrice($ID, $term, $publicfee)
{
// Connect to mysql DB.
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
or die('Could not connect: ' . mysql_error());
mysql_select_db('paulme6_newresolution') or die('Could not select database: '. mysql_error());

// Step 1: Formulate query to retrieve the renewal fee (RenewalFee) [which pertains to the existing license term, by definition, of course] and the (existing) license term (LicenseTerm) from mediators_table where ID=$ID.
$query = "select RenewalFee, LicenseTerm from mediators_table where ID='".$ID."'";
$result = mysql_query($query) or die('Query (select RenewalFee and LicenseTerm values from mediators_table for given ID) failed: '.$query . mysql_error());
if (!$result) {	echo 'No $result was achievable.'; };
$line = mysql_fetch_array($result);
$RenewalFee = $line['RenewalFee'];
$LicenseTerm = $line['LicenseTerm'];

// Step 2: Use $RenewalFee and $LicenseTerm to calculate the simple linear cost appropriate for the term ($term) that is sought.
$simplelinearcost = $RenewalFee/$LicenseTerm * $term;

// Step 3: Calculate the rawdiscount as the difference between the existing LicenseTerm and the term ($term) sought.
$rawdiscount = $term - $LicenseTerm;
$rawdiscount = min($rawdiscount, 50); // Cap the rawdiscount to no more than 50.

// Step 4: Use rawdiscount and simplelinearcost to calculate $newfee.
$newfee = (1 - $rawdiscount/100) * $simplelinearcost;
$newfee = min($newfee, $publicfee); // Take the lesser of either the calculated newfee or the current publicly offered license fee for $term. The current publicly offered license fee (an input parameter to the calculateOfferPrice() function) is calculated within ipn.php from the array $FeeForTermArray[$term], which is itself defined in the require'd file obtainparameters.php.
return $newfee;
}
?>