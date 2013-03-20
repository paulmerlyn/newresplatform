<?php
// Reference: www.white-hat-web-design.co.uk/articles/php-captcha.php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<!-- This form is accessed via links on updateprofile.php and renew.php and iplibrary.php. Only client mediators will have access to this page. The links will not be displayed for demo mediators. -->
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta NAME="description" CONTENT="New Resolution licensed mediators can reset their username/password using this form.">
<meta NAME="keywords" CONTENT="mediation, username, password, reset">
<title>Reset Username/Password</title>
<link href="/nrcss.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" href="http://newresolutionmediation.com/favicon.ico" type="image/x-icon">
<script language="JavaScript" src="/scripts/emailaddresschecker.js" type="text/javascript"></script>
</head>

<body>
<h1 style="margin-left: 200px; margin-top: 24px;">Reset Username/Password</h1>
<p class="text" style="font-size: 14px; margin-left:40px; margin-right:40px; margin-top: 20px;">Please identify yourself with a <i>personal</i> email address. Use any personal  address (e.g. karaoke_queen99@yahoo.com) registered in your Mediator Profile. (Don&rsquo;t use your professional email address e.g. jane@newresolutionmediation.com.) Your username and password will be reset and sent to the registered  address provided.</p>
<br>
<form method="post" onSubmit="if (!emailCheck(this.registeredemail.value,'noalert')) return false; else { return true; }" action="resetuserpassform_slave.php">
<table style="margin-left: 140px; width: 420px; cellspacing: 0px; cellpadding: 0px;">
<tr>
<td width="50" height="30px" valign="bottom"><label style="position: relative; bottom: 20px;">Email</label></td>
<td width="370" valign="bottom">
<input type="text" name="registeredemail" id="registeredemail" style="width:170px" size="45" maxlength="45" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white';">
<div class="greytextsmall">Enter any personal email address registered in your Mediator Profile</div>
<?php if ($_SESSION['MsgRegisteredPersonalEmail'] != null) { echo $_SESSION['MsgRegisteredPersonalEmail']; $_SESSION['MsgRegisteredPersonalEmail']=null; } ?></td>
</tr>
<tr>
<td colspan="2" height="80px" valign="bottom">
<img src="/scripts/captcha/CaptchaSecurityImages.php?width=100&height=40&characters=5" style="margin-left: 100px;"><br>
</td>
</tr>
<tr>
<td colspan="2" height="70px" valign="middle">
<label style="margin-left: 110px;">Security Code:</label><br>
<!-- For this form, I've used the following implementation of CAPTCHA: www.white-hat-web-design.co.uk/articles/php-captcha.php. Note this implementation is different from Securimage CAPTCHA that I used in the public site's contact forms (e.g. c_schedmediationform.shtml). I hadn't yet discovered Securimage CAPCHA at the time of building mediationcareersform.shtml - it presumably would work though b/c it works with behemoth formmail.php. -->
<input id="security_code" name="security_code" type="text" size="5" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; document.getElementById('submitbutton').focus();"  style="margin-left: 120px;">
</td></tr>
<tr><td colspan="2"><input type="submit" id="submitbutton" class="buttonstyle" style="margin-left:50px; margin-top:0px;" value="Reset My Username/Password"></td></tr>
</table>
</form>
<br>
</body>
</html>
