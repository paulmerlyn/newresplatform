<?php
/*
signaturefailure.php is the page to which a mediator (either a new licensee or a renewing/newnewing mediator) will be redirected (via licenseofframp.php) if code near the top of licenseofframp.php is unable to determine that the mediator successfully e-signed the Licensing Agreement on the AlphaTrust web site. The purpose of signaturefailure.php is to inform the mediator of this outcome, let him/her know that customer support has been informed and will refund any payment already submitted (i.e. an earlier, pre-esign step in the sign-up process, while the user was on the PayPal web site). It also allows the user to try to e-sign again if he/she wishes (perhaps the user, say, accidentally hit the 'Cancel' button while on the AlphaTrust site).
*/
session_start(); // For reuse of $_SESSION['LocaleSelected'] (which is set in sales_slave.php and renew.php), and $_SESSION['custom'] (which is set in sales.php and renew.php).
ob_start(); // Used in conjunction with ob_flush() [see www.tutorialized.com/view/tutorial/PHP-redirect-page/27316], this allows me to postpone issuing output to the screen until after the header has been sent.

// Connect to mysql and select database
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
	or die('Could not connect: ' . mysql_error());
mysql_select_db('paulme6_newresolution') or die('Could not select database');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>New Resolution Mediation Launch Platform™ | Failure to Sign Licensing Agreement</title>
<link href="/salescss.css" rel="stylesheet" type="text/css">
</head>
<?php
$Locale = $_SESSION['LocaleSelected']; // Assign the LocaleSelected session variable into variable $Locale and ...
$Locale = htmlentities($Locale, ENT_QUOTES, 'utf-8'); // Sanitize $Locale. Even though this session variable is filled in a controlled way as the value of a drop-down menu, I still need to manipulate it a little (using htmlentities() b/c  values may contain single quotation marks as in "Coeur d'Alene_ID". (Note the UTF-8 character set specification is important for chars such as n-tilde in Canon City_CO.)
?>

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
<h2 style="margin-top: 40px;">Oops! We didn&rsquo;t receive your signature on your Licensing Agreement</h2>
<p class='sales' style='width: 750px; margin-top:30px;'>Our system detected a problem with your electronic signature. Perhaps you accidentally clicked the &lsquo;Cancel&rsquo; button on the previous page. Or maybe you deliberately clicked it because you don't want to complete the <?php if ($_SESSION['custom'] == 'newlicensee') echo 'sign-up'; else echo 'renewal'; ?>
   process. The server might have encountered a technical issue. Or the document might have expired. </p>
<p class='sales' style='width: 750px; margin-top: 20px;'>If you don&rsquo;t wish to complete the renewal process, there&rsquo;s no need to take any action. You&rsquo;ll automatically receive a refund of any payment already submitted within three business days. We&rsquo;ll send notification of your refund to this address: <?=$_SESSION['payer_email']; ?>. You may also contact our support desk any time at 
  <script type='text/javascript'> var a = new Array('o','.','@new','ort','supp','resolution','mediation','c','m');document.write('<a href="mailto:' + a[4]+a[3]+a[2]+a[5]+a[6]+a[1]+a[7]+a[0]+a[8] + '">' + a[4]+a[3]+a[2]+a[5]+a[6]+a[1]+a[7]+a[0]+a[8] + '</a>');</script>
  . (Please reference transaction ID: <?=$_SESSION['txn_id']; ?>.)</p>
<p class='sales' style='width: 750px; margin-top: 20px;'>If you do wish to complete the renewal process, please click the button below. You&rsquo;ll be redirected  through the e-signing process where you can try again to sign your Licensing Agreement for the <?=$Locale; ?> locale.</p>
</td>
</tr>
</table>

<form method="post" action="/scripts/createcontract.php">
<div style="text-align: center"> <!-- This div provides centering for older browsers incl. NS4 and IE5. (See http://theodorakis.net/tablecentertest.html#intro.) Use of margin-left: auto and margin-right: auto in the style of the table itself (see below) takes care of centering in newer browsers. -->
<table border="0" cellpadding="20" cellspacing="0" style="margin-left: auto; margin-right: auto;"><tr><td width="750" align="center">&nbsp;
<input type="hidden" name="locale" value="<?php echo $_SESSION['LocaleSelected']; ?>"> <!-- Session variable set in sales_slave.php and renew.php -->
<input type="hidden" name="licfee" value="<?php echo $_SESSION['mc_gross']; ?>"> <!-- Session variable set in licenseonramp.php -->
<input type="hidden" name="licterm" value="<?php echo $_SESSION['termsought']; ?>"> <!-- Session variable set in licenseonramp.php -->
<input type="submit" name="ProceedToContract" value="Review/Sign Agreement" class="buttonstyle" style="position: relative; left: 0px;">
</td></tr></table>
</div>
</form>


<?php
/* Send the support desk an email to notify that a refund may be due of a payment transaction for a mediator whose e-signature was invalid. No refund, however, will be due if the mediator subsequently revisited the AlphaTrust e-sign process and then successfully signed the Licensing Agreement. */
	$address = 'support@newresolutionmediation.com'; 
	$subject = "Potential Refund Due for Failure to Sign Licensing Agreement";
	$body = "Hello New Resolution Support\n\n";
	$body .= "Re. PayPal Transaction ID: ".$_SESSION['txn_id']."\n
A mediator (either a new mediator or a renewing/newnewing mediator) made a payment via PayPal (verify) then induced a signature failure at the AlphaTrust site while attempting to e-sign the Licensing Agreement.\n
Please investigate whether the mediator subsequently revisited the e-sign process and successfully signed the Agreement. If he/she did, there is no refund due. If he/she did not, then refund the amount pertaining to the above transaction ID.\n
Item name: ".$_SESSION['item_name']."
Existing mediator (ID) or new licensee: ".$_SESSION['custom']."
Payer name: ".$_SESSION['first_name']." ".$_SESSION['last_name']."
Payer email: ".$_SESSION['payer_email']."
Payment status: ".$_SESSION['payment_status']."
Gross amount: ".$_SESSION['mc_gross']."
Payment date: ".$_SESSION['payment_date']."
Payer city & state: ".$_SESSION['address_city'].", ".$_SESSION['address_state']."
Payer telephone: ".$_SESSION['contact_phone']."\n
[This message was autogenerated by signaturefailure.php.]";
$headers = 
"From: donotreply@newresolutionmediation.com\r\n" .
"X-Mailer: PHP/" . phpversion();
mail($address, $subject, $body, $headers);
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