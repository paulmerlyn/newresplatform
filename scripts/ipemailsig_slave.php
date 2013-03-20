<?php
/*
ipemailsig_slave.php is the slave script to ipemailsig.php, used by the user to create his/her email signature HTML (via a link from the Milonic tramline menu, after user logged in via iplibrary.php). The slave combines the user's form input from ipemailsig.php with HTML formatting data to effect two email signatures. The two are identical except one has a static New Resolution LLC logo, and the other has an animated logo.
Also of great note: For (non-techie) licensees, I have implemented a method of providing licensees with a single file as their (customized) email signature. Ordinarily, the signature (if it contained an image) would comprise one html file and a second (jpg or gif) image file that is hreferenced via the img tag. I achieve a single file by using a base64-encoded image that is actually embedded into the HTML file (hence no need for a separate image file). I got the code (which I had to modify to make it work) from here: http://www.sweeting.org/mark/blog/2005/07/12/base64-encoded-images-embedded-in-html.
I'm allowing licensees to choose between a static NR logo and an animated GIF logo.
    ipemailsig_slave.php doesn't actually create the downloadable html signature files on the server. That function is done by emailsigfilecreatordownloader.php, which is an action script called by the user on clicking one of the two 'Download' buttons in ipemailsig_slave.php.
 */
// Start a session
session_start();
if ($_SESSION['SessValidUserFlag'] != 'true')
	{
	echo "<br><p style='font-family: Arial, Helvetica, sans-serif; margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px; font-weight: bold;'>Access to this page is restricted to New Resolution LLC clients and validated users.</p>";
	exit;
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Create an Email Signature | Slave Script</title>
<link href="/nrcss.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/milonic_tramline/milonic_src.js"></script>
<noscript>This DHTML Menu requires JavaScript. Please adjust your browser settings to turn on JavaScript</noscript>
<script type="text/javascript" src="/milonic_tramline/mmenudom.js"></script> 
<script type="text/javascript" src="/milonic_tramline/menu_data.js"></script> 
</head>
<body>
<div style="position: absolute; top: 5px; left: 480px; padding: 0px;">
<form name="LogOutForm" method="post" action="/iplibrary.php">
<input type="hidden" name="LoggedOut" id="LoggedOut" value="true">
<input type="submit" class="submitLinkSmall" value="Log Out">
<!-- I've disguised the submit button as a link -->
</form> 
</div>
<h1 style="margin-left:370px; position:relative; top: 10px; font-size: 22px; color: #425A8D;">Intellectual Property Library</h1>
<HR align="left" size="2px" noshade color="#FF9900" style="margin-top: 22px; size: 2px; height: 0px; position: relative; right: 0px;">
<img src="/images/VerticalLine.bmp" style="position: absolute; top: 0px; left: 122px;">
<?php
// Create short variable names
$Name = $_POST['name'];
$Credentials = $_POST['credentials'];
$FunctionDescriptor = $_POST['functiondescriptor'];
$EntityName = $_POST['entityname'];
$StreetAddress = $_POST['streetaddress'];
$City = $_POST['city'];
$State = $_POST['state'];
$Zip = $_POST['zip'];
$Email = $_POST['email'];
$UseProfessionalEmail = $_POST['UseProfessionalEmail'];
$Tel1 = $_POST['tel1'];   // tel1,2,3 are area code, local code, and four-digit number
$Tel2 = $_POST['tel2'];
$Tel3 = $_POST['tel3'];
$Ext = $_POST['ext'];
$Fax1 = $_POST['fax1'];   // Fax1,2,3 are pending manipulation before insertion to DB as Fax
$Fax2 = $_POST['fax2'];
$Fax3 = $_POST['fax3'];

// Prevent cross-site scripting
$Name = htmlspecialchars($Name, ENT_QUOTES);
$Credentials = htmlspecialchars($Credentials, ENT_QUOTES);
$FunctionDescriptor = htmlspecialchars($FunctionDescriptor, ENT_QUOTES);
$EntityName = htmlspecialchars($EntityName, ENT_QUOTES);
$StreetAddress = htmlspecialchars($StreetAddress, ENT_QUOTES);
$City = htmlspecialchars($City, ENT_QUOTES);
$Zip = htmlspecialchars($Zip, ENT_QUOTES);
$Email = htmlspecialchars($Email, ENT_QUOTES);
$Tel1 = htmlspecialchars($Tel1, ENT_QUOTES);
$Tel2 = htmlspecialchars($Tel2, ENT_QUOTES);
$Tel3 = htmlspecialchars($Tel3, ENT_QUOTES);
$Ext = htmlspecialchars($Ext, ENT_QUOTES);
$Fax1 = htmlspecialchars($Fax1, ENT_QUOTES);
$Fax2 = htmlspecialchars($Fax2, ENT_QUOTES);
$Fax3 = htmlspecialchars($Fax3, ENT_QUOTES);

// Examine the value of the UseProfessionalEmail check-box in ipemailsig.php. If it was checked (i.e. value is 1) then user is using his/her ProfessionalEmail and assign $ProfessionalEmail to $Email. Otherwise, let $Email retain its posted value.
if ($UseProfessionalEmail == 1) $Email = $_SESSION['ProfessionalEmail'];

$signatureHTML = '<div style="font-size: 11px; font-weight:bold; font-family:Arial, Helvetica, sans-serif;">
<table>
<tr>
<td colspan="2" height="20" valign="top"><span style="text-align:left; font-size: 11px; font-weight:bold;">'.$Name;

if ($Credentials != '' && !is_null($Credentials)) $signatureHTML .= ', '.$Credentials; // Include this optional item iff the user specified credentials when completing the form in ipemailsig.

$signatureHTML .= '&nbsp;&#8226;&nbsp;'.$FunctionDescriptor.'</span></td></tr>';

if ($EntityName != '' && !is_null($EntityName)) $signatureHTML .= '<tr><td colspan="2" height="20" valign="top"><span style="font-size: 11px; font-weight:bold;">'.$EntityName.'</span></td></tr>'; // Create a new row to contain the EntityName if it's non-blank.

$signatureHTML .= '<tr>
<td colspan="2" height="20" valign="top"><span style="font-size: 11px; font-weight:bold;">'.$StreetAddress.'&nbsp;&#8226;&nbsp;'.$City.'&nbsp;&#8226;&nbsp;'.$State.'&nbsp;'.$Zip.'</span></td>
</tr>
<tr>
<td colspan="2" height="20" valign="top"><span style="font-size: 11px; font-weight:bold; font-family:Arial, Helvetica, sans-serif; text-decoration: none; color: #000000;">'.$Email.'&nbsp;&#8226;&nbsp;'.$Tel1.'.'.$Tel2.'.'.$Tel3;
if ($Ext != '' && !is_null($Ext)) $signatureHTML .= '&nbsp;Ext&nbsp;'.$Ext;
$signatureHTML .= '</span></td>
</tr>
<tr>
<td colspan="2" height="20" valign="top"><span style="font-size: 11px; font-weight:bold; font-family:Arial, Helvetica, sans-serif">www.newresolutionmediation.com';

if ($Fax1 != '' && !is_null($Fax1)) $signatureHTML .= '&nbsp;&#8226;&nbsp;'.$Fax1.'.'.$Fax2.'.'.$Fax3.'&nbsp;fax';
$signatureHTML .= '</span></td>
</tr>
<tr>
<td colspan="2" height="20" valign="bottom">
<span style="font-size: 10px"><font size="1">Member of the New Resolution Network of Independent Mediators</font></span>
</td>
</tr>
';

// The only difference between the two email signatures is in the (base64encoded) images -- one is a static logo, and the other is an animated GIF.

// The base64-encoded image data below was copy-and-pasted from my browser's source code after running base64imageencoder.php, which I created specifically to obtain the base64-encoding of an image. See that file for more details (it should be in the /scripts folder).
$signatureHTMLstatic = $signatureHTML . '<tr><td colspan="2" valign="top"><img border="0" src="data:image/jpg;base64,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" width="211" height="58" alt="New Resolution Logo"/></td>
</tr>
</table>
</div>';

// The base64-encoded image data below was copy-and-pasted from my browser's source code after running base64imageencoder.php, which I created specifically to obtain the base64-encoding of an image. See that file for more details (it should be in the /scripts folder). For the animated logo for licensees, I encoded fole = "anim_logo.gif", which has no LLC or reg TM.
$signatureHTMLanim = $signatureHTML . '<tr><td colspan="2" valign="top"><a href="http://www.newresolutionmediation.com"><img align="middle" border="0" src="http://www.newresolutionmediation.com/images/anim_logo.gif" width="204" height="96" alt="New Resolution Logo"/></a>&nbsp;&reg;</td>
</tr>
</table>
</div>';
?>

<h2 style="margin-left: 150px;">EMAIL SIGNATURE INSTRUCTIONS</h2>
<div style="margin-left: 0px; margin-top: 0px; width: 850px; font-family: Arial, Helvetica, sans-serif; font-size: 10px; font-size: 12px;">
<p class="text">Your HTML email signature will display in most modern email applications.</p>
<p class="texthangindent" style="margin-right: 0px;">1. Download either the static or animated version of the signature. (Alternatively, you can revise your contact information by clicking the &lsquo;Email Signature&rsquo; menu item in the left sidebar again.)</p>
<p class="texthangindent" style="margin-right: 0px;">2. Save the signature file (mysignature.html) in a folder on your computer &mdash; for example, in your &lsquo;Documents&rsquo; or &lsquo;My Documents&rsquo; folder.</p>
<p class="texthangindent" style="margin-right: 0px;">3. Open your email client software (e.g. Thunderbird, Outlook, or Apple Mail). Navigate to the <b>Signatures</b> settings screen &mdash; usually under the Tools/Options or Account Settings item.</p>
<p class="texthangindent" style="margin-right: 0px;">4. Click through the set-up screens, pointing your email application to the mysignature.html file saved on your computer. For detailed assistance, click the <b>Help</b> menu item in your email application and search for &lsquo;signature&rsquo;.</p>

<!-- I could have simply shown $signatureHTMLstatic and $signatureHTMLanim in the following table, just above their respective 'Download' buttons. However, the base64-encoded version of the animated logo flashes annoyingly in IE (not in any other browser) even though the signature seems to integrate fine in any email client. To avoid the annoying IE flashing on screen, I instead show the table with non-base64-encoded versions of the two logos (static and animated). -->
<div style="margin-left: 140px; margin-top: 50px;">
<table cellspacing="0" cellpadding="0">
<tr>
<td valign="top"><?php echo $signatureHTML.'<tr><td colspan="2"><img align="middle" border="0" src="/images/NewResolutionLogoBasic.jpg"></td></tr></table>'; ?></td>
<td rowspan="2" width="40">&nbsp;</td>
<td valign="top"><?php echo $signatureHTML.'<tr><td colspan="2"><img align="middle" border="0" src="/images/anim_logo.gif">&nbsp;&reg;</td></tr></table>'; ?></td>
</tr>
<tr>
<td align="center">
<form action="/scripts/emailsigfilecreatordownloader.php" method="post">
<input type="hidden" value="/home/paulme6/public_html/nrmedlic/downloadables/mysignature.html" name="file" id="file">
<input type="submit" name="download_static" style="margin-top: 8px; width: 120px; position: relative; right: 25px;" value="Download" <?php if (isset($_SESSION['Guest'])) { echo 'disabled'; } else { echo 'class="buttonstyle"'; }; ?>>
</form>
</td>
<td align="center">
<form action="/scripts/emailsigfilecreatordownloader.php" method="post">
<input type="hidden" value="/home/paulme6/public_html/nrmedlic/downloadables/mysignature.html" name="file" id="file">
<input type="submit" name="download_anim" style="margin-top: 8px; width: 120px; position: relative; right: 45px;" value="Download" <?php if (isset($_SESSION['Guest'])) { echo 'disabled'; } else { echo 'class="buttonstyle"'; }; ?>>
</form>
</td>
</tr>
</table>
</div>
<?php
// Store signature code for static and animated signatures in session variables so the code can be passed to emailsigfilecreatordownloader.php, which writes this code to downloadable file mysignature.html.
$_SESSION['signatureHTMLstatic'] = $signatureHTMLstatic;
$_SESSION['signatureHTMLanim'] = $signatureHTMLanim;
?>
<br><br><br>
<div id="copyright" style="margin-left: 150px;">&copy; <?php echo date('Y'); ?> New Resolution LLC. All rights reserved.</div>
</div>
</body>
</html>