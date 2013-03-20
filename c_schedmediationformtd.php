<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html><!-- InstanceBegin template="/Templates/MasterTemplate1F.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="CONTENT-LANGUAGE" CONTENT="en-US">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Schedule a Mediation Session | New Resolution&reg; Mediator</title>
<meta NAME="description" CONTENT="Schedule a mediation with a New Resolution mediator (test-drive version).">
<meta NAME="keywords" CONTENT="schedule, mediation session, New Resolution mediators">
<!-- InstanceEndEditable -->
<link href="/nrcss.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" href="http://newresolutionmediation.com/favicon.ico" type="image/x-icon">
<SCRIPT language="JavaScript" src="/milonic_src.js" type="text/javascript"></SCRIPT>
<script language="JavaScript" type="text/javascript">
if(ns4)_d.write("<scr"+"ipt language='JavaScript' src='/mmenuns4.js'><\/scr"+"ipt>");
else _d.write("<scr"+"ipt language='JavaScript' src='/mmenudom.js'><\/scr"+"ipt>"); </script>
<SCRIPT language='JavaScript' src="/menu_data_1.js" type='text/javascript'></SCRIPT>
<script type="text/javascript" src="/openmenusbyurl.js"></script>
<script language="JavaScript" src="/scripts/emailaddresschecker.js" type="text/javascript"></script>
<script language="JavaScript" src="/scripts/windowpops.js" type='text/javascript'></script>
<!-- InstanceBeginEditable name="head" -->
<script type="text/javascript" language="javascript">
<!--
// Now that we are safely inside c_schedmediationformtd.php, we can close the emailtdformwindow window that was the opener of c_schedmediationformtd.php.
window.opener.close();
// -->
</script>
<script type="text/javascript" src="/scripts/dynamicformutility.js"></script>
<!-- InstanceEndEditable --><!-- InstanceParam name="ReferralTable" type="boolean" value="false" --><!-- InstanceParam name="LocationsList" type="boolean" value="false" --><!-- InstanceParam name="Statcounter" type="boolean" value="true" --><!-- InstanceParam name="GoogleAnalytics" type="boolean" value="true" --><!-- InstanceParam name="Google Analytics Tracking Code" type="boolean" value="true" -->
</head>

<body>
<div id="main">
<div id="relwrapper">

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

<a name="backtotop"></a>
<a href="index.shtml" onClick="wintasticself('index.shtml'); return false;"><img src="/images/mediation_animlogo.gif" alt="New Resolution logo" width="204" height="96" class="toprightlogo_1" border="0">
<div id="toprightlogotm_1" style="color: #000000;">&reg;</div><div id="LLCsuffix_1">LLC</div></a>
<table width="100%"  border="0" cellpadding="0" cellspacing="0"><!--DWLayoutTable-->
  <tr>
    <td width="750"><img src="/images/TangBlock.jpg" alt="tangerine block" width="750" height="100"></td>
    <td height="100%" rowspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td>
	<a href="r_onlinehelp.php"><img style="position: absolute; top: 100px; left: 672px" src="/images/WrenchIcon.jpg" alt="Mediation Self-Help" border="0"></a>
	<a href="locations.php"><img style="position: absolute; top: 100px; left: 40px" src="/images/LocationIcon.jpg" alt="Our Locations" border="0"></a>
	<a href="index.shtml"><img style="position: absolute; top: 100px; left: 0px" src="/images/HomeIcon.jpg" alt="Tour Site" border="0"></a>
	</td>
  </tr>
  <tr>
    <td height="86"><img src="/images/tangmenubg.jpg" alt="mediation" width="750" height="30" class="tangmenucover_1"></td>
  </tr>
  <tr>
    <td width="750"><!-- InstanceBeginEditable name="Content" -->

<?php
/*
The php script in this file processes the emailtdform.php form that prospective licensees use to provide the mediator's name and email address when test driving the 'Schedule a Mediation Session' form inside c_schedmediationformtd.php. The php code basically facilitates access to the mediator name and email address (supplied by the prospective licensee test-driver via the form in emailtdform.php) with short variable names. These variables are then used to define hidden fields within the form in c_schedmediationformtd.php and thence used by the script that processes that form (FormHandler.cgi) to send the results of c_schedmediationformtd.php back to the prospective licensee.
*/

// Create short variable names
$TDmedName = $_POST['tdmedname'];
$TDmedEmail = $_POST['tdmedemail'];
?>
  	  <h1>Schedule a Mediation Session (Test Drive)</h1>
	  <p class="text">Please use this form to  schedule a mediation session. We&rsquo;ll be in touch with you shortly to confirm availability and <a class="suppress" href="s_fees.shtml" onClick="wintasticsecond('s_fees.shtml'); return false;">fees</a> for your requested session and location.</p>
	  <br>
	  <!-- Note that the form in c_schedmediationformtd.php posts to formmailtd.php whereas the five main client-level forms (i.e. c_schedmediationform.shtml, c_contact.shtml, consultreqform.shtml, c_inpersonconsultreqform.shtml, and otherpartyrequestform.shtml) all post to formmail.php. The reason why c_schedmediationformtd.php needs its own customized version of the form handler (i.e. formmailtd.php) is because, for security reasons, formmail.php assigns to $TARGET_EMAIL only email addresses (i.e. values of the form's "recipients" field) of mediators in the DB. Since the value of the recipients field for c_schedmediationformtd.php is obtained via emailtdform.php and could be any email address entered by a test driver of the appointment forms utility, formmailtd.php cannot apply the same level of scrutiny in its assignment of $TARGET_EMAIL. -->
      <form method="post" onSubmit="return emailCheck(this.email.value,'c_schedtd')" action="/scripts/formmailtd.php">
      <table class="formmargins" cellspacing="0" cellpadding="0">
	  <tr>
	  	<td class="formcol1">
		<span class="basictextbold">Locale:&nbsp;&nbsp;</span>
		<select name="locale" size="1" style="font-size: 12px; font-weight:normal; font-family:Arial,Helvetica,sans-serif">
		<option value="" selected>Your Locale Here</option>
		<option value="">Albuquerque, NM</option>
		<option value="">Dallas, TX</option>
		<option value="">Fort Worth, TX</option>
		<option value="">Greater Miami, FL</option>
		<option value="">Miami, FL</option>
		<option value="">San Francisco Bay Area, CA</option>
		<option value="">Scottsdale, AZ</option>
		<option value="">Seattle, WA</option>
		</select> 
	  </td>
	  </tr>
	  </table>
  	<HR align="left" size="1px" noshade color="#FF9900" class="formdivider2">
	<table class="formmargins" cellspacing="0" cellpadding="0">
	<tr>
	  	<td class="formcol1" height="50px">
	    <span class="basictextbold">Location:&nbsp;&nbsp;</span>
		<select name="first_location" size="1" style="font-size: 12px; font-weight:normal; font-family:Arial,Helvetica,'Century Gothic', sans-serif">
		<option value="no_first_choice_selected" selected>&lt;&nbsp;Select First Choice&nbsp;&gt;</option>
		<option value="Your_Location_#1">Your Location #1</option>
		<option value="Your_Location_#2">Your Location #2</option>
		<option value="Your_Location_#3">Your Location #3</option>
		</select><span class="tangsup">&nbsp;&nbsp;<b>*</b> (required)</span>
		</td>
	  	<td class="formcol2_3">
		<select name="second_location" size="1" style="font-size: 12px; font-weight:normal; font-family:Arial,Helvetica,'Century Gothic', sans-serif">
		<option value="no_second_choice_selected" selected>&lt;&nbsp;Select Second Choice&nbsp;&gt;</option>
		<option value="Your_Location_#1">Your Location #1</option>
		<option value="Your_Location_#2">Your Location #2</option>
		<option value="Your_Location_#3">Your Location #3</option>
		</select>
		</td>
		</tr>
	  </table>
      <HR align="left" size="1px" noshade color="#FF9900" class="formdivider2">
      <table class="formmargins" cellspacing="0" cellpadding="5">
	  <tr>
   	  <td class="formcol1_2">
		</td>
		</tr>
		</table>
      <table class="formmargins" cellspacing="0" cellpadding="5">
	  <tr>
	  <td colspan="4">
	  <label for="appttimes"><span style="position:relative;bottom:10px">When would you like to schedule your session?</span></label><br>
	  <!--
	   <span style="font-size:11px; font-family: Arial, Helvetica, 'Century Gothic'; font-weight:bold">San Francisco office:</span><span style="font-size:10px; font-family: Arial, Helvetica, 'Century Gothic'">&nbsp;&nbsp;Appointments available Monday-Friday, 9 am - 7:30 pm; Saturdays, 10 am - 4 pm.</span><br>
	  <span style="font-size:11px; font-family: Arial, Helvetica, 'Century Gothic'; font-weight:bold">Satellite locations:</span><span style="font-size:10px; font-family: Arial, Helvetica, 'Century Gothic'">&nbsp;&nbsp;Monday-Friday, 9 am to 5 pm.</span><br><br> 
	  -->
	  <textarea name="appttimes" id="appttimes" rows="5" cols="60" wrap="soft">Enter preferred times and days of the week and/or dates.</textarea>
	  </td></tr>
      </table>
	  <HR align="left" size="1px" noshade color="#FF9900" class="formdivider">
      <table class="formmargins" cellspacing="0" cellpadding="5">
	  <tr>
	  	<td width="340">
		<label for="name_p1">Your name:&nbsp;</label>
		<input type="text" name="realname" id="name_p1" size="20" maxlength="30">
		<span class="tangsup">&nbsp;&nbsp;<b>*</b> (required)</span>
		</td>
		<td colspan="2" class="formcol2_3">
		<label for="title_p1">Title:&nbsp;</label>
		<select name="title_p1" id="title_p1" style="font-size: 12px; font-weight:normal; font-family:Arial,Helvetica,'Century Gothic',sans-serif" onChange="document.getElementById('primary_areacode_p1').focus();">
		<option value="">&lt;&nbsp;select&nbsp;&gt;</option>
		<option value="Mr.">Mr</option>
		<option value="Ms.">Ms</option>
		<option value="Mrs.">Mrs</option>
		<option value="Dr.">Dr</option>
		<option value="Professor">Prof</option>
		</select>
		</td>
	  </tr>
	  <tr>
	  	<td class="formcol1">
		<label for="primary_areacode_p1">Tel (primary):&nbsp;</label>
		<input type="text" name="primary_areacode_p1" id="primary_areacode_p1" size="3" maxlength="3" onKeyUp="if (document.getElementById('primary_areacode_p1').value.length == 3) document.getElementById('primary_localcode_p1').focus();">
		&nbsp;&nbsp;&nbsp;<input type="text" name="primary_localcode_p1" id="primary_localcode_p1" size="3" maxlength="3" onKeyUp="if (document.getElementById('primary_localcode_p1').value.length == 3) document.getElementById('primary_tel_p1').focus();">
		&ndash;&nbsp;<input type="text" name="primary_tel_p1" id="primary_tel_p1" size="4" maxlength="4">
		<span class="tangsup">&nbsp;&nbsp;<b>*</b></span>
		</td>
		<td class="formcol2_3_4">
		<input type="checkbox" name="prim_tel_type_p1" id="prim_tel_is_home_p1" value="home">
		<label for="prim_tel_is_home_p1">Home&nbsp;&nbsp;&nbsp;</label>
		<input type="checkbox" name="prim_tel_type_p1" id="prim_tel_is_work_p1" value="work">
		<label for="prim_tel_is_work_p1">Work&nbsp;&nbsp;&nbsp;</label>
		<input type="checkbox" name="prim_tel_type_p1" id="prim_tel_is_cell_p1" value="cell">
		<label for="prim_tel_is_cell_p1">Cell</label>
		</td>
	  </tr>
	  <tr>
	  	<td class="formcol1">
		<label for="secondary_areacode_p1">Tel (secondary):&nbsp;</label>
		<input type="text" name="secondary_areacode_p1" id="secondary_areacode_p1" size="3" maxlength="3" onKeyUp="if (document.getElementById('secondary_areacode_p1').value.length == 3) document.getElementById('secondary_localcode_p1').focus();">
		&nbsp;&nbsp;&nbsp;<input type="text" name="secondary_localcode_p1" id="secondary_localcode_p1" size="3" maxlength="3" onKeyUp="if (document.getElementById('secondary_localcode_p1').value.length == 3) document.getElementById('secondary_tel_p1').focus();">
		&ndash;&nbsp;<input type="text" name="secondary_tel_p1" id="secondary_tel_p1" size="4" maxlength="4">
		</td>
		<td class="formcol2_3_4">
		<input type="checkbox" name="sec_tel_type_p1" id="sec_tel_is_home" value="home">
		<label for="sec_tel_is_home">Home&nbsp;&nbsp;&nbsp;</label>
		<input type="checkbox" name="sec_tel_type_p1" id="sec_tel_is_work" value="work">
		<label for="sec_tel_is_work">Work&nbsp;&nbsp;&nbsp;</label>
		<input type="checkbox" name="sec_tel_type_p1" id="sec_tel_is_cell" value="cell">
		<label for="sec_tel_is_cell">Cell</label>
		</td>
	  </tr>
	  <tr>
	  <td colspan="4" valign="top">
	  <span class="basictextbold">Please indicate when we may call:&nbsp;&nbsp;&nbsp;</span>
	  <input type="checkbox" name="call_p1_mornings" id="call_p1_mornings" value="mornings">
	  <label for="call_p1_mornings">Mornings&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
	  <input type="checkbox" name="call_p1_lunch" id="call_p1_lunch" value="lunchtime">
	  <label for="call_p1_lunch">Lunch&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
	  <input type="checkbox" name="call_p1_afternoons" id="call_p1_afternoons" value="afternoons">
	  <label for="call_p1_afternoons">Afternoons</label><br>
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  <input type="checkbox" name="call_p1_evenings" id="call_p1_evenings" value="evenings">
	  <label for="call_p1_evenings">Evenings&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
	  <input type="checkbox" name="call_p1_weekdays" id="call_p1_weekdays" value="weekdays">
	  <label for="call_p1_weekdays">Weekdays</label>
	  <input type="checkbox" name="call_p1_weekends" id="call_p1_weekends" value="Saturday">
	  <label for="call_p1_weekends">Saturdays&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
	  <br>
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="text" name="other_time_to_call_p1" id="other_time_to_call_p1" size="30" maxlength="50">
	  <label for="other_time_to_call_p1">Other (specify)</label>
	  </td> 
	  </tr>
	  <tr>
	  <td colspan="4">
	  <label for="email_p1">E-mail address:&nbsp;</label>
	  <input type="text" name="email" id="email_p1" size="30" maxlength="45">
      <span class="tangsup">&nbsp;&nbsp;<b>*</b></span><br><kbd style="color:red; font-weight:bold; font-size: 11px;">Note: You may want to use your own email address here so you can receive the confirmation message that is automatically sent to this party&rsquo;s address.</kbd>
	  </td>
	  </tr>
	  </table>
	  <HR align="left" size="1px" noshade color="#FF9900" class="formdivider2">
      <table class="formmargins" cellspacing="0" cellpadding="5">
	  <tr>
	  	<td class="formcol1" style="width: 380px;"><!-- The setting of a width via the style attribute in this td tag is to prevent a quirky line break from occurring in the 'Tel (secondary)' cell. -->
		<label for="name_p2">Other party&rsquo;s name:&nbsp;</label>
		<input type="text" name="name_p2" id="name_p2" size="20" maxlength="30">
        <span class="tangsup">&nbsp;&nbsp;<b>*</b></span>
		</td>
		<td class="formcol2_3">
		<label for="title_p2">Title:&nbsp;</label>
		<select name="title_p2" id="title_p2" style="font-size: 12px; font-weight:normal; font-family:Arial,Helvetica,'Century Gothic',sans-serif" onChange="document.getElementById('primary_areacode_p2').focus();">
		<option value="">&lt;&nbsp;select&nbsp;&gt;</option>
		<option value="Mr.">Mr</option>
		<option value="Ms.">Ms</option>
		<option value="Mrs.">Mrs</option>
		<option value="Dr.">Dr</option>
		<option value="Prof.">Prof</option>
		</select>
		</td>
	  </tr>
	  <tr>
	  	<td class="formcol1">
		<label for="primary_areacode_p2">Tel (primary):&nbsp;</label>
		<input type="text" name="primary_areacode_p2" id="primary_areacode_p2" size="3" maxlength="3" onKeyUp="if (document.getElementById('primary_areacode_p2').value.length == 3) document.getElementById('primary_localcode_p2').focus();">
		&nbsp;&nbsp;&nbsp;<input type="text" name="primary_localcode_p2" id="primary_localcode_p2" size="3" maxlength="3" onKeyUp="if (document.getElementById('primary_localcode_p2').value.length == 3) document.getElementById('primary_tel_p2').focus();">
		&ndash;&nbsp;<input type="text" name="primary_tel_p2" id="primary_tel_p2" size="4" maxlength="4">
		</td>
		<td class="formcol2_3_4">
		<input type="checkbox" name="prim_tel_type_p2" id="prim_tel_is_home_p2" value="home">
		<label for="prim_tel_is_home_p2">Home&nbsp;&nbsp;&nbsp;</label>
		<input type="checkbox" name="prim_tel_type_p2" id="prim_tel_is_work_p2" value="work">
		<label for="prim_tel_is_work_p2">Work&nbsp;&nbsp;&nbsp;</label>
		<input type="checkbox" name="prim_tel_type_p2" id="prim_tel_is_cell_p2" value="cell">
		<label for="prim_tel_is_cell_p2">Cell</label>
		</td>
	  </tr>
	  <tr>
	  	<td class="formcol1">
		<label for="secondary_areacode_p2">Tel (secondary):&nbsp;</label>
		<input type="text" name="secondary_areacode_p2" id="secondary_areacode_p2" size="3" maxlength="3" onKeyUp="if (document.getElementById('secondary_areacode_p2').value.length == 3) document.getElementById('secondary_localcode_p2').focus();">
		&nbsp;&nbsp;&nbsp;<input type="text" name="secondary_localcode_p2" id="secondary_localcode_p2" size="3" maxlength="3" onKeyUp="if (document.getElementById('secondary_localcode_p2').value.length == 3) document.getElementById('secondary_tel_p2').focus();">
		&ndash;&nbsp;<input type="text" name="secondary_tel_p2" id="secondary_tel_p2" size="4" maxlength="4">
		</td>
		<td class="formcol2_3_4">
		<input type="checkbox" name="sec_tel_type_p2" id="sec_tel_is_home_p2" value="home">
		<label for="sec_tel_is_home_p2">Home&nbsp;&nbsp;&nbsp;</label>
		<input type="checkbox" name="sec_tel_type_p2" id="sec_tel_is_work_p2" value="work">
		<label for="sec_tel_is_work_p2">Work&nbsp;&nbsp;&nbsp;</label>
		<input type="checkbox" name="sec_tel_type_p2" id="sec_tel_is_cell_p2" value="cell">
		<label for="sec_tel_is_cell_p2">Cell</label>
		</td>
	  </tr>
	  <tr>
	  <td colspan="2" valign="top">
	  <span class="basictextbold">Please indicate when we may call:&nbsp;&nbsp;&nbsp;</span>
	  <input type="checkbox" name="call_p2_mornings" id="call_p2_mornings" value="mornings">
	  <label for="call_p2_mornings">Mornings&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
	  <input type="checkbox" name="call_p2_lunch" id="call_p2_lunch" value="lunchtime">
	  <label for="call_p2_lunch">Lunch&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
	  <input type="checkbox" name="call_p2_afternoons" id="call_p2_afternoons" value="afternoons">
	  <label for="call_p2_afternoons">Afternoons</label><br>
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  <input type="checkbox" name="call_p2_evenings" id="call_p2_evenings" value="evenings">
	  <label for="call_p2_evenings">Evenings&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
	  <input type="checkbox" name="call_p2_weekdays" id="call_p2_weekdays" value="weekdays">
	  <label for="call_p2_weekdays">Weekdays</label>
	  <input type="checkbox" name="call_p2_weekends" id="call_p2_weekends" value="Saturday">
	  <label for="call_p2_weekends">Saturdays&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
	  <br>
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="text" name="other_time_to_call_p2" id="other_time_to_call_p2" size="30" maxlength="50">
	  <label for="other_time_to_call_p2">Other (specify)</label>
	  </td> 
	  </tr>
	  <tr>
	  <td colspan="2">
	  <label for="email_p2">E-mail address:&nbsp;</label>
	  <input type="text" name="email_p2" id="email_p2" size="30" maxlength="45">
	  </td>
	  </tr>
	  </table>
	  <HR align="left" size="1px" noshade color="#FF9900" class="formdivider2">
      <table class="formmargins" cellspacing="0" cellpadding="5">
	  <tr>
	  <td>
	  <label for="notes"><span style="position:relative;bottom:120px">Notes:&nbsp;</span></label>
	  <textarea name="notes" id="notes" rows="7" cols="65" wrap="soft">Enter any additional comments here.</textarea>
	  </td></tr>
      </table>
	  <HR align="left" size="1px" noshade color="#FF9900" class="formdivider">
<!-- Form validation: The emailCheck() function (in the external emailaddresschecker.js file) performs client-side validation of P1's email-address. The emailCheck() function is invoked by the onSubmit event handler within the <form> tag. I currently have no other client-side form validation. All the rest of the validation is via FormMail's server-side utility, whereby the hidden field name = 'required' (below) allows me to specify which form fields must have values. -->
<!-- Very important. If the server-side validation utility picks up an error, the error page will load and the user will then be sent back to the form. This results in the form being redisplayed with whatever values the user had previously entered still displaying. It is necessary to reset the locale drop-down menu to its default value of 'no_locale_selected' (see GenerateUniqueLocaleDrop() in coremediatorfunctions.js) using the onClick="document.forms[0].locale.selectedIndex = 0" event handler because failure to do so may result in the following scenario: -->
<!-- Scenario: (i) User selects a locale with multiple mediators (e.g. Dallas), (ii) the form displays a drop-down of Dallas mediators, (iii) the user completes every field except that she doesn't select any mediator from that mediators drop-down, (iv) the user submits the form by clicking the 'Submit' button, (v) the server-side validation utility detects an error because the user's failure to select a mediator means that the 'recipients' field (a required field) still has its null ("") value, (vi) the error page is loaded, (vii) the user reads the error page content and clicks back to the form page, (viii) the form page reloads, displaying the locale drop-down still set to Dallas and showing no mediators drop-down. Such a scenario is a problem because there's nothing on the redisplayed form page that would trigger the user to change the locale drop-down selection from and back to Dallas in order to trigger the redisplaying of the mediators drop-down. Hence, my solution to averting this scenario is to force the locale field to reset to a value of 'no locale selected', thereby forcing the user to select again her locale and (hopefully) this time notice that she also needs to select a mediator from the mediators drop-down menu that will subsequently appear (unless there's only one mediator automatically assigned for the chosen locale). -->
<!-- The following table encapsulates captcha, as implemented via integration of third-party Securimage CAPTCHA (see www.phpcaptcha.org). I tried and failed to get formmail.php to integrate with either Tectite's natively recommended CAPTCHA implementation and White Hat's implementation (see www.white-hat-web-design.co.uk/articles/php-captcha.php), which I used in mediationcareersform.shtml (at the time of creating that form, I hadn't yet discovered Securimage CAPTCHA. Securimage CAPTCHA works great as a code injection to formmail.php. However, it's a more complex solution than White Hat's CAPTCHA. -->
<table class="formmargins" cellspacing="0" cellpadding="5">
<tr>
<td height="60px" valign="bottom">
<img id="captcha" src="/scripts/securimage/securimage_show.php" alt="CAPTCHA Image"/ style="margin-left: 195px;"><br>
</td>
</tr>
<tr>
<td height="20px" valign="middle">
<label style="margin-left: 215px;">Security Code:</label><br>
</td>
</tr>
<tr>
<td align="center" height="70px" valign="middle">
<div style="margin-left: 208px;">
<input type="text" name="captcha_code" size="10" maxlength="6"/>
&nbsp;&nbsp;<a href="#" class="indexlink" onclick="document.getElementById('captcha').src = '/scripts/securimage/securimage_show.php?' + Math.random(); return false">Load Alternate Image</a>
</div>
</td>
</tr>
</table>
<input type="submit" id="thesubmitbutton" class="buttonstyle" style="margin-left:380px; margin-bottom:20px;" value="Submit" onClick="document.forms[0].locale.selectedIndex = 0;">
<!-- Use php to assign the value of the recipients field to $TDmedEmail, which is the email address provided by the prospective licensee test driver in the emailtdform.php, which was in turn displayed after the prospective licensee clicked a button on the platform sales page. -->
<input type=hidden name="recipients" value="<?=$TDmedEmail; ?>">
<!-- Use php to assign the value of the selectedmediator field to $_SESSION['tdmedname'], which is the name provided by the prospective licensee test driver in the emailtdform.php, which was in turn displayed after the prospective licensee clicked a button on the platform sales page. The value of this hidden field gets used by formmail.php to customize the form results that are sent to the "recipients" email address. -->
<input type=hidden name="selectedmediator" value="<?=$TDmedName; ?>">
<!-- I originally had a <input type="hidden" name="required" value= ...> tag here but I removed it from this test-drive (td) version of c_schedumediationformtd.php. Although it caught appropriate validation errors, it caused big problems i.e. after the user read the on-screen message about having forgotten to provide, say, a name or telephone number and had then used either the browser's back button or a hyperlink to return to the form to provide the missing data, it caused the form to lose the session variable for "recipients". Hence "recipients" then became blank, and formmail.php had no recipient for the form results! That caused an embarrassing on-screen message about "our staff have been alerted...". Whereas the non-TD version of this form (i.e. c_schedmediationform.shtml) still happily uses the <input name="required" ... > tag, no problem exists in that context because, unliked the TD version, the non-TD version populates a value for "recipients" on-the-fly every time the form is reloaded. Besides, form validation isn't really important for test-drivers here! -->
<input type=hidden name="subject" value="Request to Schedule Mediation Session">
<input type="hidden" name="derive_fields" value="Name_p1=title_p1+realname, Primary_phone_p1=primary_areacode_p1+primary_localcode_p1+primary_tel_p1+prim_tel_type_p1,Secondary_phone_p1=secondary_areacode_p1+secondary_localcode_p1+secondary_tel_p1+sec_tel_type_p1,Best_time_to_call_p1=call_p1_mornings+call_p1_lunch+call_p1_afternoons+call_p1_evenings+call_p1_weekdays+call_p1_weekends+other_time_to_call_p1,Email_p1=email,Name_p2=title_p2+name_p2,Primary_phone_p2=primary_areacode_p2+primary_localcode_p2+primary_tel_p2+prim_tel_type_p2,Secondary_phone_p2=secondary_areacode_p2+secondary_localcode_p2+secondary_tel_p2+sec_tel_type_p2,Best_time_to_call_p2=may_we_call_p2+call_p2_mornings+call_p2_lunch+call_p2_afternoons+call_p2_evenings+call_p2_weekdays+call_p2_weekends+other_time_to_call_p2,Email_p2=email_p2,Notes=notes"/>
<input type="hidden" name="mail_options" value="KeepLines,Exclude=first_location;second_location;appttimes;title_p1;realname;primary_areacode_p1;primary_localcode_p1;primary_tel_p1;prim_tel_type_p1;secondary_areacode_p1;secondary_localcode_p1;secondary_tel_p1;sec_tel_type_p1;call_p1_mornings;call_p1_lunch;call_p1_afternoons;call_p1_evenings;call_p1_weekdays;call_p1_weekends;other_time_to_call_p1;email;title_p2;name_p2;primary_areacode_p2;primary_localcode_p2;primary_tel_p2;prim_tel_type_p2;secondary_areacode_p2;secondary_localcode_p2;secondary_tel_p2;sec_tel_type_p2;call_p2_mornings;call_p2_lunch;call_p2_afternoons;call_p2_evenings;call_p2_weekdays;call_p2_weekends;other_time_to_call_p2;email_p2;notes,PlainTemplate=apptsubmitemail.txt,TemplateMissing=blank"/>
<input type="hidden" name="good_url" value="http://www.newresolutionmediation.com/mediationcareer.php">
<script type='text/javascript' language='javascript'>var a = new Array('hoo.','a','y', 'pa','mer','ul','lyn','@','com');document.write("<input type='hidden' name='bcc' value='"+a[3]+a[5]+a[4]+a[6]+a[7]+a[2]+a[1]+a[0]+a[8]+"'>");</script>
</form>
<p class="privacy"><b>Privacy Statement.</b>&nbsp;&nbsp;Your privacy is important.&nbsp;&nbsp;We will only contact you with permission.&nbsp;&nbsp;We never 
rent, sell or otherwise release your personal information to third parties without your explicit consent.</p>
<!-- InstanceEndEditable --></td>
  </tr>
  

<tr>
    <td height="40" valign="bottom">
	   <div id="copyright">&copy; <?php echo date("Y"); ?> New Resolution LLC. All rights reserved. <a class="footer" href="sitemap.shtml">Site Map</a>&nbsp;|&nbsp;<a class="footer" href="/scripts/updateprofile.php">Log In</a>&nbsp;|&nbsp;<a class="footer" href="/mediationcareersform.shtml" onClick="window.open('/mediationcareersform.shtml','','height=615,width=750,top=30,left=290,scrollbars=yes,menubar=no,toolbar=no,location=no,status=yes'); return false;">Careers</a>&nbsp;|&nbsp;
<script type='text/javascript'><!--
var v2="GT8BC3B8MXVANVDIFET4CPDDI2MDGJN5H4";var v7=unescape("%24%3BV6%22P6x%23%3D%213+%25+%2531%3D%5B-%3D%21%20%20S9-%28%24%60V%27Y");var v5=v2.length;var v1="";for(var v4=0;v4<v5;v4++){v1+=String.fromCharCode(v2.charCodeAt(v4)^v7.charCodeAt(v4));}document.write('<a class="footer" href="javascript:void(0)" onclick="window.location=\'mail\u0074o\u003a'+v1+'?subject=Inquiry'+'\'">Email<\/a>');
//--></script><noscript><a class="footer" href='http://w2.syronex.com/jmr/safemailto/#noscript'>Email</a></noscript></div></td></tr>
</table>
<?php require('/home/paulme6/public_html/nrmedlic/ssi/noscriptnavigation.php'); ?>

<!-- Place this tag where you want the +1 button to render (Note: I have two +1 buttons on each page.) -->
<span id="plusone"><g:plusone size="small" count="false" href="<?php
function curPageURL() // Courtesy: http://www.webcheatsheet.com/PHP/get_current_page_url.php
	{
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80")
		{
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} 
	else
		{
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
	return $pageURL;
	}
echo curPageURL();
?>
"></g:plusone></span>
<!--  Place this tag after the last plusone tag -->
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>


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

</div>
</div>
</body>
<!-- InstanceEnd --></html>
