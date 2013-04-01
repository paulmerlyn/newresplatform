<?php
session_start(); // For reuse of $_SESSION['LocaleSelected'], which is originally set in sales_slave.php and renew.php (though, for safe measure, I reset/recreate it here in licenseonramp.php after incurring session variable persistence issues), and $_SESSION['custom'], which is assigned here in licenseonramp.php.
/*
The Payment Data Transfer (PDT) script code is based on PayPal's sample code for PDT at https://www.paypal.com/us/cgi-bin/webscr?cmd=p/pdn/pdt-codesamples-pop-outside#php
*/

require('ssi/obtainparameters.php'); // Include the obtainparameters.php file, which retrieves values $DefaultAPR and $FeeTermArray array from the parameters_table table. The latter is used for price-checking by the confirmProduct() function below. (Note that require'ing obtainparameters.php here isn't strictly necessary b/c obtainparameters.php is require'd by offerpricecalculator.php anyway.)
require('ssi/offerpricecalculator.php'); // Include the offerpricecalculator.php file, which contains the calculateOfferPrice($ID, $term) function for calculating the license renewal price (or the "newnewal" price) to be offered to mediator of ID=$ID when seeking a renewal (or "newnewal") term=$term. Note that this file also itself includes the obtainparameters.php file, which retrieves values $DefaultAPR and $FeeTermArray array from the parameters_table table. The latter is used for price-checking by the confirmProduct() function below.

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-synch';

$tx_token = $_GET['tx'];
$auth_token = ""; // REPLACE with the token (obtained from Profile/Web Payments Preferences) for Sandbox implementation.
$req = "tx=$tx_token&at=$auth_token&cmd=_notify-synch";

// post back to PayPal system to validate
$header = "POST /cgi-bin/webscr HTTP/1.0\r\n"; // REMOVE THE PERIOD
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
// If PHP server isn't SSL enabled, instead use: $fp = fsockopen('www.paypal.com', 80, $errno, $errstr, 30);
 $fp = fsockopen('ssl://www.paypal.com', 443, $errno, $errstr, 30); // Change to 'www.sandbox.paypal.com' for Sandbox implementation!

if (!$fp) 
{
// HTTP ERROR
} 
else 
{
$outputstring = $header.$req;
fputs($fp, $outputstring);
// read the body data response from PayPal
$res = '';
$headerdone = false;
while (!feof($fp)) 
	{
	$line = fgets($fp, 1024);
	// echo $line.'<br>'; // Uncomment this if I'm debugging.
	if (strcmp($line, "\r\n") == 0) 
		{
		// read the header
		$headerdone = true;
		}
	else if ($headerdone)
		{
		// header has been read. now read the contents
		$res .= $line;
		}
	}

// parse the data
$lines = explode("\n", $res);
$keyarray = array();
if (strcmp ($lines[0], "SUCCESS") == 0)
	{
	for ($i=1; $i<count($lines);$i++)
		{
		list($key,$val) = explode("=", $lines[$i]);
		$keyarray[urldecode($key)] = urldecode($val);
		}
	/* Check that txn_id has not been previously processed */
	// I haven't yet implemented this. Hopefullly it's not very useful.

	/* Check that receiver_email is your Primary PayPal email */

	/* Check that payment_amount/payment_currency are correct */

	/* Process payment */
	$first_name = $keyarray['first_name'];
	$last_name = $keyarray['last_name']; $_SESSION['last_name'] = $last_name; // For use by signaturefailure.php
	$item_name = $keyarray['item_name']; $_SESSION['item_name'] = $item_name; // For use by signaturefailure.php
	$item_number = $keyarray['item_number'];
	$payment_gross = $keyarray['payment_gross']; // Deprecated. Better to use mc_gross instead, which will be the same value.
	$mc_gross = $keyarray['mc_gross']; // For use by signaturefailure.php
	$payment_status = $keyarray['payment_status']; $_SESSION['payment_status'] = $payment_status; // Completed or Pending
	$payer_status = $keyarray['payer_status']; // Verified or Unverified
	$payer_id = $keyarray['payer_id'];
	$txn_id = $keyarray['txn_id']; $_SESSION['txn_id'] = $txn_id; // For use by signaturefailure.php
	$payment_date = $keyarray['payment_date']; $_SESSION['payment_date'] = $payment_date; // For use by signaturefailure.php
	$payer_email = $keyarray['payer_email'];
	$mc_currency = $keyarray['mc_currency'];
	$address_street = $keyarray['address_street'];
	$address_city = $keyarray['address_city']; $_SESSION['address_city'] = $address_city; // For use by signaturefailure.php
	$address_state = $keyarray['address_state']; $_SESSION['address_state'] = $address_state; // For use by signaturefailure.php
	$address_zip = $keyarray['address_zip'];
	$address_country = $keyarray['address_country'];
	$contact_phone = $keyarray['contact_phone']; $_SESSION['contact_phone'] = $contact_phone;
	$custom = $keyarray['custom']; // The custom field (defined in the code for the 'Pay Now' button) is either "newlicensee" or a mediator ID.
	$_SESSION['custom'] = $custom; // For use by licenseofframp.php in distinguishing between content relevant for an existing mediator vs content to be displayed for a new licensee.
	$_SESSION['payer_email'] = $payer_email; // For use by licenseofframp.php in sending the new mediator's username/password to this email address.
	$_SESSION['first_name'] = $first_name; // For use by licenseofframp.php to personalize the email that contains the new mediator's username/password.
	$_SESSION['LicenseFee'] = $mc_gross; // For use by licenseofframp.php
	}
else if (strcmp ($lines[0], "FAIL") == 0) 
	{
	// If this clause is invoked I should really (but haven't yet done so) log for manual investigation
//echo 'You are seeing this message because of either an unauthorized access attempt or a processing error in our Payment Data Transfer script. Please notify our Customer Support desk at support@newresolutionmediation.com so we can investigate this problem and issue you a refund if applicable. Thank you!'; exit;
	}

}

fclose ($fp);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>New Resolution Mediation Launch Platform&trade; License On-Ramp</title>
<link href="salescss.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="default.css" />
</head>

<body>
<!-- Start of Google Analytics -->
<script type="text/javascript"> 
var _gaq = _gaq || []; 
_gaq.push(['_setAccount', 'UA-1100858-3']); 
_gaq.push(['_trackPageview']); 
(function() { 
var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true; 
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js'; 
(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga); 
})(); 
</script>
<!-- End of Google Analytics -->
<table width="750" align="center" cellpadding="0" cellspacing="0" border="0">
<tr>
<td width="750"><img class="graphic" src="/images/platform.jpg" alt="Mediation Launch Platform Image" width="750" height="170" border="0"></td> <!-- The id of 'noprint' is used by the print-friendly stylesheet salescss_print.css to hide display of this graphic when the print-friendly link is clicked. See http://www.maratz.com/blog/archives/2004/09/21/10-minutes-to-printer-friendly-page -->
</tr>
<tr>
<td><img style="margin-top: 20px;" <?php if ($_SESSION['custom'] == 'newlicensee') echo 'src="/images/ProgressBarStep2new.jpg"'; else echo 'src="/images/ProgressBarStep2existing.jpg"'; ?> alt="Mediation Progress" width="750" height="108" border="0"></td>
</tr>
<tr>
<td align="left">
<?php
/* On testing, I didn't get cast-iron persistence of a PHP session within licenseonramp.php, causing me to lose the $_SESSION['LocaleSelected'] session variable that I'd set in sales_slave.php and renew.php. (I had originally set $thelocale to $_SESSION['LocaleSelected'].) As a work-around, I now obtain the locale by parsing the $item_name variable returned by PayPal's Payment Data Transfer after the user made a payment. Note, however, that licenseofframp.php and congratulations.php (both of which are downstream of licenseonramp.php) both seemed to use $_SESSION['LocaleSelected'] perfectly well -- at least, I thought so until my first client sign up encountered an issue causes apparently by the failed persistence of $_SESSION['LocaleSelected'].)
	The bottom line of all this is that I've decided to recreate $_SESSION['LocaleSelected'] from the Payment Data Transfer's $item_name variable for safe measure! */
// Find the position in $item_name where the needle " license for " begins.
$posn = strpos($item_name, ' license for ');
// Increment that position by 13 characters in order to get to the position where the locale begins.
$posn = $posn + 13;
// Obtain the locale portion using substr() and that starting point.
$thelocale = substr($item_name, $posn);
$_SESSION['LocaleSelected'] = $thelocale;

/*
Before thanking the user for his/her payment and providing an opportunity to proceed to signing the econtract, verify that the payment amount tendered is correct. This helps prevent fraud. Perform this verification using the confirmProduct($itemnumber, $grosspayment) function, which I originally defined for use in ipn.php. Note that the function code processing differs according to the value of $custom, which is supplied by the 'Pay Now' button clicked by the client. In the case of a new license purchase, $custom = 'newlicensee'. In the case of a purchase by an existing licensee (i.e. either a renewal for the same term or a "newnewal" for a different term), $custom is the ID of a mediator from the mediators_table table.
*/
function confirmProduct($itemnumber, $grosspayment)
{
//$confirmed = true; // Delete this line for full implementation!
//return $confirmed; // Delete this line for full implementation!
global $feefortermsought, $offerprice, $custom; // I need to declare these variables as global b/c they're calculated outside the confirmProduct function. (See variable scope info at http://us.php.net/manual/en/language.variables.scope.php.)

switch($custom)
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
		//echo 'The default switch clause has been invoked within the confirmProduct() function.<br>';
		$ID = $custom;
		$ID = (int)$ID;
		// Connect to mysql to examine whether $ID is a legitimate ID i.e. whether the 'ID' column of the mediators_table contains an ID equal to $ID.
		$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
		or die('Could not connect: ' . mysql_error());
		mysql_select_db('paulme6_newresolution') or die('Could not select database: ' . mysql_error());

		// Query the database to see if a record exists with an ID matches the value of $ID (i.e. the value of $custom).
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
Verify whether the amount tendered is correct using the confirmProduct() function. 
When dealing with new mediator purchases and renewals (or "newnewals") by existing licenesees, we will extract the license term from the item_number (e.g. 6mLPL or 24mLPL for 6-month and 2-year respectively license term purchases) specified in hidden fields of the particular PayPal 'Buy Now' button code that was clicked. We will then determine (by look up of the public price list via FeeForTermArray[] in obtainparameters.php for a new licensee, or by calculation using calculateOfferPrice() in offerpricecalculator.php for an existing mediator) the amount actually due from the payer. If the merchant receives from a new licensee a gross amount ($mc_gross] of $10 for, say, a 1-month term, and the price list says that a 1-month term costs $220, then the purchaser is trying to defraud us. The confirmProduct() function should only return 'true' if the mc_gross matches (within 1%) the correct amount due. Note that a 'Buy Now' button will have a 'custom' field of value = "newlicensee" for new licensees, and a value equal to the mediator's ID for an existing mediator.
*/

// Before calling the confirmProduct() function, we need to calculate some variables. $feefortermsought is used by confirmProduct() in cases of a new mediator, whereas $offerprice is used by confirmProduct() in cases of an existing (i.e. renewing or newnewing) mediator. Note that we precalculate these variables rather than calculate them inside confirmProduct() in order to avoid a problem with variable scope. Note, by the same token, the use of the "global $feefortermsought, $offerprice;" statement in confirmProduct().

// First, derive the term sought (in months) by the mediator.
$termsought = explode("m", $item_number); // $item_number is 3mLPL for a one-quarter (3-month) term, 24mLPL for a 24-month term, etc. So derive the term (i.e. the number before the letter 'm') by exploding $itemnumber on its 'm'. The first element in the resulting array will be the number (e.g. 3 or 24).
$termsought = (int)$termsought[0]; // Convert from string to integer.
$_SESSION['termsought'] = $termsought; // I create this as a session variable for later use by licenseofframp.php (the page on which new mediators land after signing the Licensing Agreement) and by signaturefailure.php (where mediators land after a failed e-signature).

// Second, obtain the (public price list) fee for this term. Note that I can't obtain this value inside the confirmProduct() function because I would run into a global variable scope problem (see http://us.php.net/manual/en/language.variables.scope.php).
$feefortermsought = $FeeForTermArray[$termsought];
if ($feefortermsought == null) $feefortermsought = 1000000000; // Note also that if it's a renewing mediator, then $FeeForTermArray[$termsought] may no longer exist if, say, the mediator was on, say, an 18-month term that he/she is renewing, but I no longer offer an 18-mth term to new mediators on the public price list. In that instance, I set $feefortermsought to a very large value so that the comparison performed in Step 4 in function calculateOfferPrice() (which takes the lesser of the public price list fee and the calculated fee) will always return the calculated fee. See offerpricecalculator.php for the specification of the calculateOfferPrice() function.)

// Third, obtain the offer price for this term if not a new licensee (not needed by confirmProduct() otherwise). The offer price is the price of license that we are offering to a renewing mediator or a newnewing mediator. Note that $feefortermsought is an input parameter b/c the offer price should always be the lesser of the calculated offer price and the public price for the term sought.
if ($custom != "newlicensee") 
	{
	$offerprice = calculateOfferPrice($custom, $termsought, $feefortermsought); // This function is accessed via the require(offerpricecalculator.php) statement above.
	}

// Fourth, call the confirmProduct() function with the term sought and the amount tendered as input data.

$confirmationresult = confirmProduct($termsought, $mc_gross);
//echo '$confirmationresult is: '.$confirmationresult.'<br>';
if ($confirmationresult != 1) // i.e. a potentially fraudulent effort to purchase a license cheaply
	{
?>
	<h2 style="margin-top: 40px;">Thank you for your payment</h2>
	<p class="sales" style="margin-top:30px;">We&rsquo;ve sent you a receipt for your payment to: 
	  <?=$payer_email; ?>.</p>
	<p class="sales" style="margin-top:30px;">Please note that our system has detected a pricing discrepancy with your payment. For that reason, we cannot complete your transaction at this time. Our customer support desk has been informed of the discrepancy and will contact you shortly to resolve the issue. In the event that we&rsquo;re unable to resolve it, you will receive a full refund of your payment.</p>
	<p class="sales" style="margin-top:30px;">You may also contact our support desk at any time if you have questions: 
	  <script type='text/javascript'> var a = new Array('o','.','@new','port','sup','resolution','mediation','c','m');document.write("<a href='mailto:"+a[4]+a[3]+a[2]+a[5]+a[6]+a[1]+a[7]+a[0]+a[8]+"'>"+a[4]+a[3]+a[2]+a[5]+a[6]+a[1]+a[7]+a[0]+a[8]+"</a>");</script>.</p>
<?php
	}
else
	{
?>
	<h2 style="margin-top: 40px;">Thank you for your payment!</h2>
	<p class="sales" style="margin-top:30px;">Details of your transaction are below. We&rsquo;ve also sent a receipt to you at <?=$payer_email; ?>.</p>
<?php
	if ($payment_status == 'Pending')
		{
?>
		<p class="sales" style="margin-top:24px;">If your payment status is <em>pending,</em> the funds should clear in 1&ndash;5 business days. (We&rsquo;ll contact you again when they clear or if there&rsquo;s a problem with your payment.)</p>
<?php
		}
	if ($custom == 'newlicensee')
		{
?>
		<p class="sales" style="margin-top:24px;">You are now just moments from securing a license to the New Resolution Mediation Launch Platform&trade; in your chosen locale of <?=$thelocale; ?>.</p>
		<p class="sales" style="margin-top:24px;">In order to receive a username and password to log into the platform, we first need you to accept the terms of your License Agreement. Click the button to review and sign the agreement online.</p>
<?php
		}
	else // It's a renewing or newnewing mediator, not a new licensee.
		{
?>
		<p class="sales" style="margin-top:24px;">In order to complete the renewal process, we now need you to accept the terms of your new License Agreement. Click the button to review and sign the agreement online.</p>
<?php
		}
?>

	<form method="post" action="/scripts/createcontract.php">
	<div style="text-align: center"> <!-- This div provides centering for older browsers incl. NS4 and IE5. (See http://theodorakis.net/tablecentertest.html#intro.) Use of margin-left: auto and margin-right: auto in the style of the table itself (see below) takes care of centering in newer browsers. -->
	<table border="0" cellpadding="20" cellspacing="0" style="margin-left: auto; margin-right: auto;"><tr><td width="750" align="center">&nbsp;
	<input type="hidden" name="locale" value="<?php echo $thelocale; ?>"> <!-- $thelocale was parsed above from $item_name -->
	<input type="hidden" name="licfee" value="<?php echo $mc_gross; ?>">
	<input type="hidden" name="licterm" value="<?php echo $termsought; ?>">
	<input type="submit" name="ProceedToContract" value="Review/Sign Agreement" class="buttonstyle" style="position: relative; left: 0px;">
	</td></tr></table>
	</div>
	</form>
	<hr size="1px" noshade color="#FF9900" class="divider" style="margin-top: 20px;">
	<br>
	<table cellpadding="0" cellspacing="2" border="0" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
	<tr>
	<td width="120">
	Payment by:
	</td>
	<td>
	<?php echo $first_name.' '.$last_name; ?>
	</td>
	</tr>
	<tr>
	<td>
	Payment amount:
	</td>
	<td>
	<?php echo $payment_gross.' '.$mc_currency; ?>
	</td>
	</tr>
	<tr>
	<td>
	Item:</td>
	<td>
	<?=$item_name; ?>
	</td>
	</tr>
	<tr>
	<td>
	Item code:
	</td>
	<td>
	<?=$item_number; ?>
	</td>
	</tr>
	<tr>
	<td valign="top">
	Payer address:
	</td>
	<td>
	<?php echo $address_street.', '.$address_city.', '.$address_state.' '.$address_zip.', '.$address_country ;?>
	</td>
	</tr>
	<tr>
	<td>
	Payment status:</td>
	<td>
	<?=$payment_status ;?>
	</td>
	</tr>
	<tr>
	<td>
	Payer ID:
	</td>
	<td>
	<?=$payer_id ;?>
	</td>
	</tr>
	<tr>
	<td>
	Transaction ID:</td>
	<td>
	<?=$txn_id ;?>
	</td>
	</tr>
	<tr>
	<td>
	Payment date:</td>
	<td>
	<?=$payment_date ;?>
	</td>
	</tr>
	</table>
	</td>
	</tr>
	<tr>
	<td>
	<br>
	<hr size="1px" noshade color="#FF9900" class="divider">
	</td>
	</tr>
	<tr>
	<td height="40" align="center" valign="bottom">
	<div id="copyright">&copy; <?php echo date("Y"); ?> New Resolution LLC. All rights reserved.</div>
	</td>
	</tr>
	</table>

	<?php
	/* Send the user an email (transaction receipt) with details of the PayPal transaction if $payment_status == 'Pending' or 'Completed'. (Note: I don't want to issue a transaction receipt for other $payment_status values such as 'Refunded' or 'Failed'. */
	if ($payment_status == 'Pending' || $payment_status == 'Completed')
		{
		$address = $payer_email;  // When testing, I commented out this line, and uncommented the abertawe address below.
		// $address = "abertawe@sbcglobal.net";  // For testing purposes, I uncommented this abertawe address
		$subject = "Transaction Receipt";
$body = "Hello ".$first_name."\n\n";
$body .= "Thank you for your request to license the New Resolution Mediation Launch Platform in your chosen
locale of ".$thelocale.". Please find below a record of your recent transaction.\n
Payment by: $first_name $last_name
Payment amount: $payment_gross $mc_currency
Item: $item_name
Item code: $item_number
Payer address: $address_street, $address_city, $address_state $address_zip, $address_country
Payment status: $payment_status
Payer ID: $payer_id
Transaction ID: $txn_id
Payment date: $payment_date\n
If you have any questions, please don't hesitate to contact our support desk:
support@newresolutionmediation.com.\n
Sincerely\n
Paul R. Merlyn
Chief Executive Officer
New Resolution LLC
paul@newresolutionmediation.com\n";
$headers = 
"From: paul@newresolutionmediation.com\r\n" .
"Reply-To: paul@newresolutionmediation.com\r\n" .
"Bcc: paulmerlyn@yahoo.com\r\n" .
"X-Mailer: PHP/" . phpversion();
mail($address, $subject, $body, $headers);
		}
	}
?>

<!-- Start of StatCounter Code -->
<script type="text/javascript">
var sc_project=5651836; 
var sc_invisible=1; 
var sc_partition=60; 
var sc_click_stat=1; 
var sc_security="97c14d16"; 
</script>

<script type="text/javascript"
src="http://www.statcounter.com/counter/counter.js"></script><noscript><div
class="statcounter"><a title="joomla counter"
href="http://www.statcounter.com/joomla/"
target="_blank"><img class="statcounter"
src="http://c.statcounter.com/5651836/0/97c14d16/1/"
alt="joomla counter" ></a></div></noscript>
<!-- End of StatCounter Code -->
</body>
</html>
