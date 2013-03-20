<?php
/*
Process the IPN. The bulk of this code draws from Chapter 10 of Paul Reinheimer's excellent book 'Professional Web APIs with PHP'
*/

require('../ssi/obtainparameters.php'); // Include the obtainparameters.php file, which retrieves values $DefaultAPR and $FeeTermArray array from the parameters_table table. The latter is used for price-checking by the confirmProduct() function below. (Note that require'ing obtainparameters.php here isn't strictly necessary b/c obtainparameters.php is require'd by offerpricecalculator.php anyway.)
require('../ssi/offerpricecalculator.php'); // Include the offerpricecalculator.php file, which contains the calculateOfferPrice($ID, $term, $publicfee) function for calculating the license renewal price (or the "newnewal" price) to be offered to mediator of ID=$ID when seeking a renewal (or "newnewal") term=$term.

//Step 0. Log the server superglobal and any posted transaction details in alltxlog.txt, which is dump for logging all transaction details.
ob_start();
echo date("D M j G:i:s T Y") . "\n"; 
print_r($_POST);
print_r($_SERVER);
$body = ob_get_clean();
$datadump = $body; // Store all this $_SERVER and $_POST data in a variable for later reuse.
file_put_contents("/home/paulme6/public_html/nrmedlic/logs/IPNlogs/alltxlog.txt", $body, FILE_APPEND);

//Step 1. Verify IPN With PayPal. (Only proceed to Step 1.5 if () returns a true result.) 
$result=verifyIPN($_POST); 
if ($result == 0) // Test whether a 'false' (i.e. 0) is returned by 
	{
	$subject = "FAKE IPN RECEIVED";
	$address = "paulmerlyn@yahoo.com";
	$headers = 
	"From: ipn_processor@newresolutionmediation.com\r\n" .
	"Reply-To: donotreply@newresolutionmediation.com\r\n" .
	"X-Mailer: PHP/" . phpversion();
	mail($address, $subject, $body, $headers);
	exit; // Exit the script. No point in continuing without having a valid IPN.
	}
else if($result != 1) // I don't really see this clause getting executed. I think $result will be either 0 (above) or 1, and nothing else.
	{
	$subject = "Unable to Verify IPN";
	$body = "ipn.php was unable to contact PayPal in order to validate the IPN. If the incoming payment notification from PayPal pertains to a valid payment, the issuance of a license to the mediator will need to be manually processed\n $result\n $body";
	$address = "paulmerlyn@yahoo.com";
	$headers = 
	"From: ipn_processor@newresolutionmediation.com\r\n" .
	"Reply-To: donotreply@newresolutionmediation.com\r\n" .
       "X-Mailer: PHP/" . phpversion();
	mail($address, $subject, $body, $headers);
	exit; // Exit the script. No point in continuing without having a valid IPN.
	}
  
//Step 1.5 The IPN was valid (i.e. it came from PayPal) though it could still have been sourced from someone seeking a fraudulent transaction. Now check payment_status as "Completed", "Pending", "Failed", or default, and proceed accordingly.
switch ($_POST['payment_status']) 
	{
   	case "Completed":
		if ($_POST['test_ipn'] == 1) // The posted information came from a sandbox account/purchaser.
			{	
			paymentCompletedThankYou('Paul', 'paulmerlyn@yahoo.com', $_POST['custom']); // Send the 'Thank you' acknowledgement to myself b/c Sandbox accounts can't accept external email.
			}
		else // The posted information came from the live PayPal system, not from a sandbox PayPal account.
			{
			paymentCompletedThankYou($_POST['first_name'], $_POST['payer_email'], $_POST['custom']); // Send the purchaser a message to say his/her payment is completed.
			};
		break; // Proceed to confirm product information.
	case "Pending":
		if ($_POST['test_ipn'] == 1) // The posted information came from a sandbox account/purchaser.
			{	
			paymentPendingThankYou('Paul', 'paulmerlyn@yahoo.com', $_POST['custom']); // Send the 'Thank you' acknowledgement to myself b/c Sandbox accounts can't accept external email.
			}
		else // The posted information came from the live PayPal system, not from a sandbox PayPal account.
			{
			paymentPendingThankYou($_POST['first_name'], $_POST['payer_email'], $_POST['custom']); // Send the purchaser a message to say his/her payment is pending.
			};
		break; // Proceed to confirm product information.
	case "Failed":
		if ($_POST['test_ipn'] == 1) // The posted information came from a sandbox account/purchaser.
			{	
			paymentFailed('Paul', 'paulmerlyn@yahoo.com'); // Send the 'Thank you' acknowledgement to myself b/c Sandbox accounts can't accept external email.
			}
		else // The posted information came from the live PayPal system, not from a sandbox PayPal account.
			{
			paymentFailed($_POST['first_name'], $_POST['payer_email']); // Send the purchaser a message to say his/her payment failed.
			};
		$body = "Hello New Resolution Support\n
An IPN from PayPal was posted to ipn.php (which generated this email message). You can view full details of the IPN post both below and in /logs/IPNlogs/alltxlog.txt.\n
The IPN had a payment_status of Failed - presumably because the payer either cancelled a Pending transaction him/herself before it could attain a payment_status of Completed, or because the payer's funding source (e.g. eCheck) had insufficient funds such that PayPal was unable to obtain the funds to clear the transaction.\n
The item name is ".$_POST['item_name'].". The name on the PayPal account is ".$_POST['address_name'].". The email address is ".$_POST['payer_email'].". The phone number is ".$_POST['contact_phone'].". The transaction ID (which relates to the original transaction) is ".$_POST['txn_id'].".\n\n";
		$body .= $datadump;  // Append all the $_SERVER and $_POST data
		$subject = "Valid IPN Received (Failed)";
		$address = "support@newresolutionmediation.com";
		$headers = 
		"From: ipn_processor@newresolutionmediation.com\r\n" .
		"Reply-To: donotreply@newresolutionmediation.com\r\n" .
		"X-Mailer: PHP/" . phpversion();
		mail($address, $subject, $body, $headers);
		exit; // Exit script here b/c there's no point in confirming product information when the payment_status is Failed.
	case "Refunded":
		if ($_POST['test_ipn'] == 1) // The posted information came from a sandbox account/purchaser.
			{	
			paymentRefunded('Paul', 'paulmerlyn@yahoo.com'); // Send the 'Thank you' acknowledgement to myself b/c Sandbox accounts can't accept external email.
			}
		else // The posted information came from the live PayPal system, not from a sandbox PayPal account.
			{
			paymentRefunded($_POST['first_name'], $_POST['payer_email']); // Send the purchaser a message to say his/her payment failed.
			};
		$body = "Hello New Resolution Support\n
An IPN from PayPal was posted to ipn.php (which generated this email message). You can view full details of the IPN post both below and in /logs/IPNlogs/alltxlog.txt.\n
The IPN had a payment_status of Refunded. The item name is ".$_POST['item_name'].". The refunded amount is ".$_POST['mc_gross'].". The name on the PayPal account is ".$_POST['address_name'].". The email address is ".$_POST['payer_email'].". The phone number is ".$_POST['contact_phone'].". The transaction ID (which relates to the original transaction) is ".$_POST['txn_id'].".\n\n";
		$body .= $datadump;  // Append all the $_SERVER and $_POST data
		$subject = "Refund Issued";
		$address = "support@newresolutionmediation.com";
		$headers = 
		"From: ipn_processor@newresolutionmediation.com\r\n" .
		"Reply-To: donotreply@newresolutionmediation.com\r\n" .
		"X-Mailer: PHP/" . phpversion();
		mail($address, $subject, $body, $headers);
		exit; // Exit script here b/c there's no point in confirming product information when the payment_status is Refunded.
	default:
		$body = "Hello New Resolution Support\nAn IPN from PayPal was received by ipn.php (which generated this email message). The IPN had a payment_status of neither Completed nor Pending nor Failed nor Refunded. You should confirm this transaction against your records.";
		$body .= $post;
		$subject = "Neither Completed nor Pending nor Failed nor Refunded IPN Received";
		$address = "paulmerlyn@yahoo.com";
		$headers = 
		"From: ipn_processor@newresolutionmediation.com\r\n" .
		"Reply-To: donotreply@newresolutionmediation.com\r\n" .
		"X-Mailer: PHP/" . phpversion();
		mail($address, $subject, $body, $headers);
		exit; // Exit script here b/c there's no point in confirming product information with this indeterminate payment_status.
	}

/* 
Step 2. Confirm Product Information for both Completed and Pending payment_status
When dealing with new mediator purchases and renewals (or "newnewals") by existing licenesees, we will extract the license term from the item_number (e.g. 6mLPL or 24mLPL for 6-month and 2-year respectively license term purchases) specified in hidden fields of the particular PayPal 'Buy Now' button code that was clicked. We will then determine (by look up of the public price list via FeeForTermArray[] in obtainparameters.php for a new licensee, or by calculation using calculateOfferPrice() in offerpricecalculator.php for an existing mediator) the amount actually due from the payer. If the merchant receives from a new licensee a gross amount ($_POST['mc_gross'] of $10 for, say, a 1-month term, and the price list says that a 1-month term costs $220, then the purchaser is trying to defraud us. The confirmProduct() function should only return 'true' if the mc_gross matches (within 1%) the correct amount due. Note that a 'Buy Now' button will have a 'custom' field of value = "newlicensee" for new licensees, and a value equal to the mediator's ID for an existing mediator.
*/

// Before calling the confirmProduct() function, we need to calculate some variables. $feefortermsought is used by confirmProduct() in cases of a new mediator, whereas $offerprice is used by confirmProduct() in cases of an existing (i.e. renewing or newnewing) mediator. Note that we precalculate these variables rather than calculate them inside confirmProduct() in order to avoid a problem with variable scope. Note, by the same token, the use of the "global $feefortermsought, $offerprice;" statement in confirmProduct().

// First, derive the term sought (in months) by the mediator.
$termsought = explode("m", $_POST['item_number']); // $_POST['itemnumber'] is 3mLPL for a one-quarter (3-month) term, 24mLPL for a 24-month term, etc. So derive the term (i.e. the number before the letter 'm') by exploding $itemnumber on its 'm'. The first element in the resulting array will be the number (e.g. 3 or 24).
$termsought = (int)$termsought[0]; // Convert from string to integer.

// Second, obtain the (public price list) fee for this term. Note that I can't obtain this value inside the confirmProduct() function because I would run into a global variable scope problem (see http://us.php.net/manual/en/language.variables.scope.php).
$feefortermsought = $FeeForTermArray[$termsought];
if ($feefortermsought == null) $feefortermsought = 1000000000; // Note also that if it's a renewing mediator, then $FeeForTermArray[$termsought] may no longer exist if, say, the mediator was on, say, an 18-month term that he/she is renewing, but I no longer offer an 18-mth term to new mediators on the public price list. In that instance, I set $feefortermsought to a very large value so that the comparison performed in Step 4 in function calculateOfferPrice() (which takes the lesser of the public price list fee and the calculated fee) will always return the calculated fee. See offerpricecalculator.php for the specification of the calculateOfferPrice() function.)

// Third, obtain the offer price for this term if not a new licensee (not needed by confirmProduct() otherwise). The offer price is the price of license that we are offering to a renewing mediator or a newnewing mediator. Note that $feefortermsought is an input parameter b/c the offer price should always be the lesser of the calculated offer price and the public price for the term sought.
if ($_POST['custom'] != "newlicensee") 
	{
	$offerprice = calculateOfferPrice($_POST['custom'], $termsought, $feefortermsought); // This function is accessed via the require(offerpricecalculator.php) statement above.
	}

// Fourth, call the confirmProduct() function with the term sought and the amount tendered as input data.
$confirmationresult = confirmProduct($termsought, $_POST['mc_gross']);
if ($confirmationresult != 1)
	{
	$subject = "Fraud Alert: Product/Price Mismatch Detected by ipn.php";
	$address = "support@newresolutionmediation.com";
	$headers = 
	"From: ipn_processor@newresolutionmediation.com\r\n" .
	"Reply-To: donotreply@newresolutionmediation.com\r\n" .
	"X-Mailer: PHP/" . phpversion();
	mail($address, $subject, $body, $headers);
	
	// Send prospective licensee a note to say the amount tendered doesn't match the correct price for the license sought
	if ($_POST['test_ipn'] == 1) // The posted information came from a sandbox account/purchaser.
		{	
		priceProductMismatch('Paul', 'paulmerlyn@yahoo.com'); //  Send email to myself b/c Sandbox accounts can't accept external email.
		}
	else // The posted information came from the live PayPal system, not from a sandbox PayPal account.
		{
		priceProductMismatch($_POST['first_name'], $_POST['payer_email']); // Send message to the prospective licensee.
		};

	exit; // Exit the script.
	}
 
// Step 3. Proceed with the order
// Send a notification email that a valid IPN has been received (and one that has passed the confirmProduct() function as a legitimate newlicensee purchase, existing licensee renewal, or existing licensee "newnewal").
$subject = "Valid IPN Received (".$_POST['payment_status'].")";
$address = "paul@newresolutionmediation.com";
$headers = 
"From: ipn_processor@newresolutionmediation.com\r\n" .
"Reply-To: donotreply@newresolutionmediation.com\r\n" .
"X-Mailer: PHP/" . phpversion();
mail($address, $subject, $body, $headers); // If the parser gets to this line, $body will still have the value given to it at the beginning of this script i.e. a print_r of the $_SERVER[] and $_POST[] array contents.
// Log essential details in an easy-to-read validipnlog.txt file.
file_put_contents("/home/paulme6/public_html/nrmedlic/logs/IPNlogs/validipnlog.txt", "Valid IPN received at: ".date(r)." with payment_status = ".$_POST['payment_status'].", address_name = ".$_POST['address_name'].", payer_id = ".$_POST['payer_id'].", payer_email = ".$_POST['payer_email'].", contact_phone = ".$_POST['contact_phone'].", mc_gross = ".$_POST['mc_gross'].", txn_id = ".$_POST['txn_id'].", txn_type = ".$_POST['txn_type'].", and item_name = ".$_POST['item_name']."\n\n", FILE_APPEND);

exit;


function verifyIPN($data) 
{ 
$postdata = "";
$response = array();
foreach($data as $i=>$v) 
	{ 
	$postdata .= $i . "=" . urlencode($v) . "&"; 
	}
$postdata.="cmd=_notify-validate"; 
$fp=@fsockopen("ssl://www.paypal.com" ,"443",$errnum,$errstr,30); // Change from www.paypal to www.sandbox.paypal.com if sandbox rather than production version.
if(!$fp) 
	{ 
	return "$errnum: $errstr";
	}
else 
	{ 
	fputs($fp, "POST /cgi-bin/webscr HTTP/1.1\r\n"); 
	fputs($fp, "Host: www.paypal.com\r\n");  // Change from www.paypal to www.sandbox.paypal.com if sandbox rather than production version.
	fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n"); 
	fputs($fp, "Content-length: ".strlen($postdata)."\r\n"); 
	fputs($fp, "Connection: close\r\n\r\n"); 
	fputs($fp, $postdata . "\r\n\r\n"); 
	while(!feof($fp)) { $response[]=@fgets($fp, 1024); }  
	fclose($fp); 
	}
	$response = implode("\n", $response);
	if(eregi("VERIFIED",$response)) 
	{
	return true;
	}
	else
	{
	// Save details of all IPNs that were not verified in log file = nonverifiedIPNs.txt for investigating potential fraud attempts.
	file_put_contents("/home/paulme6/public_html/nrmedlic/logs/IPNlogs/nonverifiedIPNs.txt", "Failed, $response", FILE_APPEND); 
	return false;
	}
}

/*
Verify that the payment amount tendered is correct for the term sought. This helps prevent fraud. Perform this verification using the confirmProduct($term, $grosspayment) function, which I also use in licenseonramp.php. Note that the function code processing differs according to the value of $_POST['custom'], which is supplied by the 'Pay Now' button clicked by the client. In the case of a new license purchase, $_POST['custom'] = 'newlicensee'. In the case of a purchase by an existing licensee (i.e. either a renewal for the same term or a "newnewal" for a different term), $_POST['custom'] is the ID of a mediator from the mediators_table table.
*/
function confirmProduct($itemnumber, $grosspayment)
{
//$confirmed = true; // Delete this line for full implementation!
//return $confirmed; // Delete this line for full implementation!
global $feefortermsought, $offerprice; // I need to declare this variable as a global b/c it's calculated outside the confirmProduct function. (See variable scope info at http://us.php.net/manual/en/language.variables.scope.php.)

switch($_POST['custom'])
	{ 
   	case "newlicensee": // If the 'custom' variable (defined in the 'Buy Now' button) takes value 'newlicensee' then the posted IPN transaction pertains to the purchase of a new license (i.e. not a renewing mediator).
		if ($grosspayment < (0.99 * $feefortermsought) || $grosspayment > (1.01 * $feefortermsought)) // I see if the received amount is within 1% of the price-listed amount for $term. (Note that $FeeForTermArray[] is obtained through the require(obtainparameters.php) statement above.)
			{
			$confirmed = 0; // Suspected case of fraud by the buyer (trying to sneak a small payment past me). Set $confirmed to 0 i.e. false.
			}
		else
			{
			$confirmed = 1; // Set $confirmed to 1 i.e. true
			};
		break;
	default: // The default case is when the 'custom' variable isn't 'newlicensee'. Its value will instead be the ID of the renewing mediator as stored in the mediators_table table. The custom variable gets this value via the 'Buy Now' button on that mediator's self-renewal web page.
		$ID = $_POST['custom'];
		$ID = (int)$ID;
		// Connect to mysql to examine whether $ID is a legitimate ID i.e. whether the 'ID' column of the mediators_table contains an ID equal to $ID.
		$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
		or die('Could not connect: ' . mysql_error());
		mysql_select_db('paulme6_newresolution') or die('Could not select database: ' . mysql_error());

		// Query the database to see if a record exists with an ID matches the value of $ID (i.e. the value of $_POST['custom']).
		$query = "select count(*) from mediators_table where ID = '".$ID."'";
		$result = mysql_query($query) or die('ID look-up query failed: ' . mysql_error());
		$row = mysql_fetch_row($result); // $row array should have just one item, which holds either '0' or '1'
		$count = $row[0];

		if ($count != 1) // The $ID is not a legitimate mediator's ID.
			{
			$confirmed = 0; // Suspected case of fraud by the buyer (trying to sneak a small payment past me).
			}
		else // The $ID is legitimate, found among the ID values in the mediators_table.
			{
			if ($grosspayment < (0.99 * $offerprice) || $grosspayment > (1.01 * $offerprice)) // See if the received payment amount is within 1% of the price being offered to this mediator whose ID=$ID for a term=$term.
				{
				$confirmed = 0; // Suspected case of fraud by the buyer (trying to sneak a small payment past me).
				}
			else
				{
				$confirmed = 1;
				};
			};
		break;
	}; // End of switch statement
	
return $confirmed; // $confirmed will be true if the $ amount tendered is within 1% of the expected amount for the license term.
}


/*
The processOrder function is currently empty and unused because my "order processing" is actually driven by the workflow that flows as follows: sales.php --> PayPal --> licenseonramp.php --> econtract --> username/password issuance. In other words, ipn.php isn't actually in the order-processing path. I actually use data provided by Payment Data Transfer (PDT) within licenseonramp.php to support verification in the order processing workflow.
*/
function processOrder($data)
{
}

function paymentCompletedThankYou($fname, $address, $custom)
{
$body = "Hello ".$fname."\n
Re. ".$_POST['item_name']."\n\n";
if ($custom == "newlicensee") 
	{
	$subject = "License Request - Payment Completed";
	$body .= "It is my pleasure to welcome you aboard the New Resolution Mediation Launch Platform!\n
We've now received confirmation from PayPal, our payment processor, that your payment has cleared. If you
haven't already done so, please complete the online registration process by signing your Launch Platform
License Agreement. You'll then be issued a username and password with immediate access to the platform.\n
If you have any questions or are unable to complete the registration process, please contact our support 
desk at:\n
support@newresolutionmediation.com.\n\n";
	}
else // It's a renewal, not a new licensee.
	{
	$subject = "License Renewal - Payment Completed";
	$body .= "It is my pleasure to welcome you for another term aboard the New Resolution Mediation Platform!\n
We've now received confirmation from PayPal, our payment processor, that your payment has cleared.
If you haven't already done so, please complete the online renewal process by signing your License Agreement 
for your new term.\n
If you have any questions or are unable to complete the renewal process, please contact our support desk at:\n
support@newresolutionmediation.com.\n\n";
	}
$body .= "Sincerely\n
Paul R. Merlyn
Chief Executive Officer
New Resolution LLC
paul@newresolutionmediation.com\n";
$headers = 
"From: paul@newresolutionmediation.com\r\n" .
"Reply-To: support@newresolutionmediation.com\r\n" .
"X-Mailer: PHP/" . phpversion();
mail($address, $subject, $body, $headers);
return;
}

function paymentPendingThankYou($fname, $address, $custom)
{
$body = "Hello ".$fname."\n
Re. ".$_POST['item_name']."\n\n";
if ($custom == "newlicensee") 
	{
	$subject = "License Request - Payment Pending";
	$body .= "It is my pleasure to welcome you aboard the New Resolution Mediation Launch Platform!\n
PayPal, our payment processor, has notified us that your payment is expected to clear in the next 1-5
business days. You will receive another email from us when the funds have cleared. We'll also 
contact you if we encounter a problem with your payment.\n
In the meantime, if you haven't already done so, please complete the online registration process by 
signing your Launch Platform License Agreement. You'll then be issued a username and password with 
immediate access to the platform.\n
If you have any questions or are unable to complete the registration process, please contact our 
support desk at:\n
support@newresolutionmediation.com.\n\n";
	}
else // It's a renewal, not a new licensee.
	{
	$subject = "License Renewal - Payment Pending";
	$body .= "It is my pleasure to welcome you for another term aboard the New Resolution Mediation Platform!\n
PayPal, our payment processor, has notified us that your payment is expected to clear in the next 1-5
business days. You will receive another email from us when the funds have cleared. We'll also contact
you if we encounter a problem with your payment.\n
In the meantime, if you haven't already done so, please complete the online renewal process by signing
your Launch Platform License Agreement for your new term.\n
If you have any questions or are unable to complete the renewal process, please contact our support desk at:\n
support@newresolutionmediation.com.\n\n";
	}
$body .= "Sincerely\n
Paul R. Merlyn
Chief Executive Officer
New Resolution LLC
paul@newresolutionmediation.com\n";
$headers = 
"From: paul@newresolutionmediation.com\r\n" .
"Reply-To: support@newresolutionmediation.com\r\n" .
"X-Mailer: PHP/" . phpversion();
mail($address, $subject, $body, $headers);
return;
}

function paymentFailed($fname, $address)
{
$subject = "Failed Payment";
$body = "Hello ".$fname."\n
Re. ".$_POST['item_name']."\n
This is a courtesy note to let you know that unfortunately your recent payment to New Resolution
has failed. We've received notification from PayPal that PayPal was unable to draw sufficient 
funds for the transaction. The problem may be an out-of-date credit or debit card or insufficient
funds in your bank account.\n
A New Resolution customer service agent will contact you shortly to help you resolve this problem.
In the meantime, access to your New Resolution Mediation Launch Platform account may be limited
until the problem is resolved.\n
If you have any questions, please don't hesitate to contact our support desk at:\n
support@newresolutionmediation.com.\n
Sincerely\n
Paul R. Merlyn
Chief Executive Officer
New Resolution LLC
paul@newresolutionmediation.com\n";
$headers = 
"From: paul@newresolutionmediation.com\r\n" .
"Reply-To: support@newresolutionmediation.com\r\n" .
"X-Mailer: PHP/" . phpversion();
mail($address, $subject, $body, $headers);
return;
}

function paymentRefunded($fname, $address)
{
$refundamount = substr($_POST['mc_gross'],1);  // This string manipulation omits the minus sign E.g. if $_POST['mc_gross'] is -220.00, then $refundamount will be '220.00'.
$subject = "Payment Refunded";
$body = "Hello ".$fname."\n
Re. ".$_POST['item_name']."\n
This is a courtesy note to let you know that we have issued a refund of your payment. 
Depending on the original method of payment, the refund ($".$refundamount.") may take 0-5 
business days before the transaction is complete and the funds are restored to your account.\n
If you have any questions, please don't hesitate to contact our support desk at:\n
support@newresolutionmediation.com.\n
Please let us know if we can be of further service to you in the future.\n
Sincerely\n
Paul R. Merlyn
Chief Executive Officer
New Resolution LLC
paul@newresolutionmediation.com\n";
$headers = 
"From: paul@newresolutionmediation.com\r\n" .
"Reply-To: support@newresolutionmediation.com\r\n" .
"X-Mailer: PHP/" . phpversion();
mail($address, $subject, $body, $headers);
return;
}

function priceProductMismatch($fname, $address)
{
$subject = "Price Mismatch";
$body = "Hello ".$fname."\n
Re. ".$_POST['item_name']."\n
This is a courtesy note to let you know that, after further processing your recent attempt to 
purchase the above license, we've detected a price discrepancy in the payment transaction. Our 
customer service desk will contact you shortly to resolve this problem.\n
If you have any questions in the meantime, please don't hesitate to contact our support desk at:\n
support@newresolutionmediation.com.\n
Sincerely\n
Paul R. Merlyn
Chief Executive Officer
New Resolution LLC
paul@newresolutionmediation.com\n";
$headers = 
"From: paul@newresolutionmediation.com\r\n" .
"Reply-To: support@newresolutionmediation.com\r\n" .
"X-Mailer: PHP/" . phpversion();
mail($address, $subject, $body, $headers);
return;
}

?>