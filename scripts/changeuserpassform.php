<?php
// Reference: www.white-hat-web-design.co.uk/articles/php-captcha.php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<!-- This form is accessed via links on updateprofile.php, renew.php, and iplibrary.php. Only client mediators will have access to this page. The links will not be displayed for demo mediators. -->
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta NAME="description" CONTENT="New Resolution licensed mediators can change their username/password using this form.">
<meta NAME="keywords" CONTENT="mediation, username, password, change">
<title>Change Username/Password</title>
<link href="/nrcss.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" href="http://newresolutionmediation.com/favicon.ico" type="image/x-icon">
<script type="text/javascript">
/*
These javascript functions get used for inline form validation (ref. http://www.interspire.com/content/articles/23/1/Using-Inline-Form-Validation#postedcomment -->
*/
function checkNewUsernameOnly()
{
// Validate Name field.
var newUsernameV1Value = document.getElementById("newusernamev1").value;
var newUsernameV1Length = newUsernameV1Value.length;
var illegalCharSet = /[^A-Za-z0-9]+/; // Exclude everything except A-Z, a-z, 0-9.
if ((newUsernameV1Length < 3 && newUsernameV1Length != 0) || (illegalCharSet.test(newUsernameV1Value)))
	{
	document.getElementById("NewUsernameError").style.display = "inline";
	return false;
	}
else
	{
	return true;
	}
}

function checkNewPasswordOnly()
{
// Validate Name field.
var newPasswordV1Value = document.getElementById("newpasswordv1").value;
var newPasswordV1Length = newPasswordV1Value.length;
var illegalCharSet = /[^A-Za-z0-9]+/; // Exclude everything except A-Z, a-z, 0-9.
if ((newPasswordV1Length < 3 && newPasswordV1Length != 0) || (illegalCharSet.test(newPasswordV1Value)))
	{
	document.getElementById("NewPasswordError").style.display = "inline";
	return false;
	}
else
	{
	return true;
	}
}

/* This function checks that user typed same entry into newusernamev1 as into newusernamev2 */
function checkUsernameConfirmed()
{
var newUsernameV1Value = document.getElementById("newusernamev1").value;
var newUsernameV2Value = document.getElementById("newusernamev2").value;
if (newUsernameV1Value == newUsernameV2Value)
	{
	return true;
	}
else
	{
	document.getElementById("UsernameConfirmationError").style.display = "inline";
	return false;
	};
}

/* This function checks that user typed same entry into newpasswordv1 as into newpasswordv2 */
function checkPasswordConfirmed()
{
var newPasswordV1Value = document.getElementById("newpasswordv1").value;
var newPasswordV2Value = document.getElementById("newpasswordv2").value;
if (newPasswordV1Value == newPasswordV2Value)
	{
	return true;
	}
else
	{
	document.getElementById("PasswordConfirmationError").style.display = "inline";
	return false;
	}
}

/*
This function gets called when the user clicks the 'Submit Now' button. It causes a mass validation of all form fields so the user can correct them in one fell swoop rather than having to see them piecemeal with each submission of the form (i.e. see another validation error each time the user clicks the submit button).
*/
function checkForm() 
{
hideAllErrors();
if (!checkNewUsernameOnly() | !checkNewPasswordOnly() | !checkUsernameConfirmed() | !checkPasswordConfirmed())
	{
	return false; // return false if any one of the individual field validation functions returned a false ...
	} 
else 
	{
	return true; // ... otherwise, all individual field validations must have returned a true, so let checkForm() return true.
	}
} // End of checkForm()

/* This function hideAllErrors() is called by checkForm() and by onblur event within non-personal form fields. */
function hideAllErrors()
{
document.getElementById("NewUsernameError").style.display = "none";
document.getElementById("NewPasswordError").style.display = "none";
document.getElementById("UsernameConfirmationError").style.display = "none";
document.getElementById("PasswordConfirmationError").style.display = "none";
return true;
}
 
</script>
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
<h1 style="margin-left: 200px; margin-top: 24px;">Change Username/Password</h1>
<p class="text" style="font-size: 14px; margin-left:40px; margin-right:20px; margin-top: 20px;">To change your username and/or password, please complete all fields:</p>
<?php if ($_SESSION['MsgPHPvalidation'] != null) { echo $_SESSION['MsgPHPvalidation']; $_SESSION['MsgPHPvalidation']=null; } ?>
<form method="post" action="changeuserpassform_slave.php">
<table style="margin-left: 40px; width: 520px; cellspacing: 0px; cellpadding: 0px;">
<tr>
<td height="80px" valign="bottom"><label for="existingusername" style="position: relative; bottom: 24px;">Existing username</label></td>
<td width="350" valign="top">
<div style="position: relative; top: 35px;"><input type="text" name="existingusername" id="existingusername" style="width:170px" size="30" maxlength="20" onFocus="hideAllErrors(); this.style.background='#FFFF99'" onBlur="this.style.background='white'; document.getElementById('newusernamev1').focus();">
<span class="tangsup" style="font-size: 10px;">&nbsp;<b>* (required)</b></span>
</div>
<br>
</td>
</tr>
<tr>
<td height="30px" valign="bottom"><label for="newusernamev1" style="position: relative; bottom: 20px;">New username</label></td>
<td width="350" valign="bottom">
<input type="text" name="newusernamev1" id="newusernamev1" style="width:170px" size="30" maxlength="20" onFocus="this.style.background='#FFFF99'" onBlur="hideAllErrors(); checkNewUsernameOnly(); this.style.background='white'; document.getElementById('newusernamev2').focus();">
<span class="tangsup" style="font-size: 10px;"></span><div class="greytextsmall">Enter new username, or leave blank to retain existing username</div><div class="error" id="NewUsernameError">Use letters [A-Z, a-z] and numbers [0-9] only. Minimum of 3 characters.<br></div><div class="error" id="UsernameConfirmationError">Error: The username that you retyped does not match.<br></div>
</td>
</tr>
<tr>
<td height="30px" valign="bottom"><label for="newusernamev2" style="position: relative; bottom: 20px;">New username (confirm)</label></td>
<td width="350" valign="bottom">
<input type="text" name="newusernamev2" id="newusernamev2" style="width:170px" size="30" maxlength="20" onFocus="this.style.background='#FFFF99'" onBlur="hideAllErrors(); checkUsernameConfirmed(); this.style.background='white'; document.getElementById('existingpassword').focus();">
<div class="greytextsmall">Retype new username, or leave blank to retain existing username</div>
</td>
</tr>
<tr>
<td height="80px" valign="bottom"><label for="existingpassword" style="position: relative; bottom: 24px;">Existing password</label></td>
<td width="350" valign="top">
<div style="position: relative; top: 35px;"><input type="text" name="existingpassword" id="existingpassword" style="width:170px" size="30" maxlength="40" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; document.getElementById('newpasswordv1').focus();">
<span class="tangsup" style="font-size: 10px;">&nbsp;<b>* (required)</b></span>
</div>
<br>
</td>
</tr>
<tr>
<td height="30px" valign="bottom"><label for="newpasswordv1" style="position: relative; bottom: 20px;">New password</label></td>
<td width="350" valign="bottom">
<input type="text" name="newpasswordv1" id="newpasswordv1" style="width:170px" size="30" maxlength="40" onFocus="this.style.background='#FFFF99'" onBlur="hideAllErrors(); checkNewPasswordOnly(); this.style.background='white'; document.getElementById('newpasswordv2').focus();">
<div class="greytextsmall">Enter new password, or leave blank to retain existing password</div>
<div class="error" id="NewPasswordError">Use letters [A-Z, a-z] and numbers [0-9] only. Minimum of 3 characters.<br></div><div class="error" id="PasswordConfirmationError">Error: The password that you retyped does not match.<br></div><?php if ($_SESSION['MsgNewPassword'] != null) { echo $_SESSION['MsgNewPassword']; $_SESSION['MsgNewPassword']=null; } ?>
</div>
</td>
</tr>
<tr>
<td height="30px" valign="bottom"><label for="newpasswordv2" style="position: relative; bottom: 20px;">New password (confirm) </label></td>
<td width="350" valign="bottom">
<input type="text" name="newpasswordv2" id="newpasswordv2" style="width:170px" size="30" maxlength="40" onFocus="this.style.background='#FFFF99'" onBlur="hideAllErrors(); checkPasswordConfirmed(); this.style.background='white'; document.getElementById('security_code').focus();">
<div class="greytextsmall">Retype new password, or leave blank to retain existing password</div>
</td>
</tr>
<tr>
<td colspan="2" align="center" height="80px" valign="bottom">
<img src="/scripts/captcha/CaptchaSecurityImages.php?width=100&height=40&characters=5" /><br>
</td>
</tr>
<tr>
<td colspan="2" align="center" height="70px" valign="middle">
<label>Security Code:</label><br>
<!-- For this form, I've used the following implementation of CAPTCHA: www.white-hat-web-design.co.uk/articles/php-captcha.php. Note this implementation is different from Securimage CAPTCHA that I used in the public site's contact forms (e.g. c_schedmediationform.shtml). I hadn't yet discovered Securimage CAPCHA at the time of building mediationcareersform.shtml - it presumably would work though b/c it works with behemoth formmail.php. -->
<input id="security_code" name="security_code" type="text" size="5" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; document.getElementById('submitbutton').focus();" />
</td></tr>
<tr><td colspan="2"><input type="submit" id="submitbutton" class="buttonstyle" style="margin-left:190px; margin-top:0px;" onclick="return checkForm();" value="Submit Change(s)"></td></tr>
</table>
</form>
<br>
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
