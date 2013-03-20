<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>New Resolution Mediation Launch Platform&trade; Administrator</title>
<link href="/nrcss.css" rel="stylesheet" type="text/css">
<script> 
function FocusFirst()
{
	if (document.forms.length > 0 && document.forms[0].elements.length > 0)
		document.forms[0].elements[0].focus();
}
 
/* Begin JS form validation functions. (Note to self: I did PHP validation that user selected a TermNumber and TermUnit from the drop-down menus, and I've decided not to bother with a Javascript version of that type of validation.) */
function checkLocaleShortOnly()
{
// Validate LocaleShort field.
var localeShortValue = document.getElementById("LocaleShort").value;
var illegalCharSet = /[0-9,~!@#\$%\^&\*\(\)_\+`=\|\\:";<>\?]+/; // Exclude everything except letters, hyphen, apostrophe, and space character.
var reqdCharSet = /[a-zA-Z]{2}/;  // At least two letters.
if (illegalCharSet.test(localeShortValue)  || !reqdCharSet.test(localeShortValue))
	{
	document.getElementById("LocaleShortError").style.display = "inline";
	return false;
	} 
else
	{
	return true;
	}
}
 
function checkLocaleStatesOnly()
{
// Validate that a LocaleStates value was selected.
var localeStatesValue = document.getElementById("LocaleStates").value;
if (localeStatesValue == 'null' || localeStatesValue == '')
	{
	document.getElementById("LocaleStatesError").style.display = "inline";
	return false;
	} 
else
	{
	return true;
	}
}
 
function checkPopulationOnly()
{
// Validate Population input field.
var populationValue = document.getElementById("Population").value;
var illegalCharSet = /[^0-9]+/; // Reject everything that contains one or more characters that is not a digit.
var reqdCharSet = /[0-9]{3}/;  // At least three digits.
if (illegalCharSet.test(populationValue)  || !reqdCharSet.test(populationValue))
	{
	document.getElementById("PopulationError").style.display = "inline";
	return false;
	} 
else
	{
	return true;
	}
}
 
function checkMaxLicensesOnly()
{
// Validate MaxLicenses input field.
var maxLicensesValue = document.getElementById("MaxLicenses").value;
var illegalCharSet = /[^0-9]+/; // Reject everything that contains one or more characters that is not a digit.
var reqdCharSet = /[0-9]+/;  // At least one numeric.
if (illegalCharSet.test(maxLicensesValue)  || !reqdCharSet.test(maxLicensesValue))
	{
	document.getElementById("MaxLicensesError").style.display = "inline";
	return false;
	} 
else
	{
	return true;
	}
}
 
function checkForm()
{
hideAllErrors();
if (checkLocaleShortOnly() && checkLocaleStatesOnly() && checkPopulationOnly() && checkMaxLicenses()) // Check validity of user inputs.
	{
	return true; // All elements passed their validity checks, so return a true.
	}
else
	{
	return false; // At least one of the elements (i.e. LocaleShort, LocaleStates, Population, or MaxLicenses) failed its individual check function, so return a false;
	};
} // End of checkForm()
 
/* This function hideAllErrors() is called by checkForm() and by onblur event within non-personal form fields. */
function hideAllErrors()
{
document.getElementById("LocaleShortError").style.display = "none";
document.getElementById("LocaleStatesError").style.display = "none";
document.getElementById("PopulationError").style.display = "none";
document.getElementById("MaxLicensesError").style.display = "none";
return true;
}
 
</script>
</head>
 
<body>
<h1 style="margin-left:100px; margin-top: 30px; font-size: 22px; padding: 0px; color: #425A8D;">New Resolution Mediation Launch Platform&trade; Administrator</h1>
<!-- Menu for the New Resolution Mediation Launch Pad Administrator UI -->
<div style="margin-left:125px; width: 550px; text-align: center; position:absolute; top: 60px; font-size: 22px; padding: 0px;">
<table cellpadding="0" cellspacing="0" align="center">
<tr>
<td>
<a href="/scripts/admin1.php">Add Mediator</a>
</td>
<td align="center" width="20">|</td>
<td>
<a href="/scripts/admin3.php">Manage Locales</a>
</td>
<td align="center" width="20">|</td>
<td>
<a href="/scripts/admin5.php">Manage Mediator</a>
</td>
<td align="center" width="20">|</td>
<td>
<a href="/scripts/admin7.php">Set Parameters</a>
</td>
<td align="center" width="20">|</td>
<td>
<a href="/scripts/admin9.php">Manage Passwords</a>
</td>
</tr>
</table>
</div>		<div style="position: absolute; left: 125px; top: 100px; width: 530px; padding: 15px; border: 2px solid #444444; border-color: #425A8D;">
		<h1 style="margin-left: 0px;">Create a new locale:</h1>
		<br>
			
		<form method="post" name="AddLocale" action="/scripts/admin4.php">
			
		<table width="530">
		<tr>
		<td valign="top"><label>New Locale</label></td>
		<td>
		<input type="text" name="LocaleShort" id="LocaleShort" maxlength="40" size="30" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkLocaleShortOnly();">
		<div class="greytextsmall">Example: Los Angeles-Long Beach-Glendale</div>
		<div class="error" id="LocaleShortError">Enter alphabetic characters. Use only letters, apostrophes ('), and hyphens (-).<br></div>
				</td>
		</tr>
		<tr>
		<td width="120" valign="top"><label>Associated State(s)</label></td>
		<td>
		<select name="LocaleStates[]" id="LocaleStates" MULTIPLE size="12" style="font-size:12px; font-weight:normal; font-family:Arial,Helvetica,sans-serif;" onBlur="hideAllErrors(); return checkLocaleStatesOnly();"> <!-- Note the need for square brackets [] in the name b/c $LocaleStates is to be an array. -->
		<option value="AL">Alabama</option>
<option value="AK">Alaska</option>
<option value="AZ">Arizona</option>
<option value="AR">Arkansas</option>
<option value="CA">California</option>
<option value="CO">Colorado</option>
<option value="CT">Connecticut</option>
<option value="DE">Delaware</option>
<option value="DC">District of Columbia (D.C.)</option>
<option value="FL">Florida</option>
<option value="GA">Georgia</option>
<option value="HI">Hawaii</option>
<option value="ID">Idaho</option>
<option value="IL">Illinois</option>
<option value="IN">Indiana</option>
<option value="IA">Iowa</option>
<option value="KS">Kansas</option>
<option value="KY">Kentucky</option>
<option value="LA">Louisiana</option>
<option value="ME">Maine</option>
<option value="MD">Maryland</option>
<option value="MA">Massachusetts</option>
<option value="MI">Michigan</option>
<option value="MN">Minnesota</option>
<option value="MS">Mississippi</option>
<option value="MO">Missouri</option>
<option value="MT">Montana</option>
<option value="NE">Nebraska</option>
<option value="NV">Nevada</option>
<option value="NH">New Hampshire</option>
<option value="NJ">New Jersey</option>
<option value="NM">New Mexico</option>
<option value="NY">New York</option>
<option value="NC">North Carolina</option>
<option value="ND">North Dakota</option>
<option value="OH">Ohio</option>
<option value="OK">Oklahoma</option>
<option value="OR">Oregon</option>
<option value="PA">Pennsylvania</option>
<option value="RI">Rhode Island</option>
<option value="SC">South Carolina</option>
<option value="SD">South Dakota</option>
<option value="TN">Tennessee</option>
<option value="TX">Texas</option>
<option value="UT">Utah</option>
<option value="VT">Vermont</option>
<option value="VA">Virginia</option>
<option value="WA">Washington</option>
<option value="DC">Washington, D.C.</option>
<option value="WV">West Virginia</option>
<option value="WI">Wisconsin</option>
<option value="WY">Wyoming</option>
<option value="US">United States (Generic)</option>
		</select>
		<div class="greytextsmall">Hold down &lsquo;Ctrl&rsquo; [Windows] or &lsquo;Command&rsquo; (Mac) keys to select multiple states.</div>
		<div class="error" id="LocaleStatesError">Please select one or more states that associate with your locale.<br></div>
				</td>
		</tr>
		<tr>
		<td><label>Population</label></td>
		<td><input type="text" name="Population" id="Population" maxlength="10" size="10" onFocus="this.style.background='#FFFF99'" onBlur="this.value = this.value.replace(/,/g, ''); if (this.value != '') { if (!document.getElementById('OverrideMaxLic').checked) { var cap = Math.floor(parseInt(this.value)/1250000); if (cap > 5) { cap = 5; } else if (cap < 1) { cap = 1; }; document.getElementById('MaxLicenses').value = cap; document.getElementById('MaxLicensesHidden').value = cap; } else { document.getElementById('MaxLicensesHidden').value = document.getElementById('MaxLicenses').value; }; }; this.style.background='white'; hideAllErrors(); return checkPopulationOnly();"><div class="greytextsmall">Use numbers [0-9] only. (You may also include commas [e.g. 3,040,278] if you wish.)</div><div class="error" id="PopulationError">Enter a number here. Use only digits [0-9].<br></div>
</td> <!-- First strip commas from the population string. Then calculate MaxLicenses using formula of one license per 1.25 million population with a maximum of 5 licenses and a minimum of 1 license. -->
		<!-- I need to pass the value of MaxLicenses via the hidden form field 'MaxLicensesHidden' rather than directly through the MaxLicenses field b/c the MaxLicenses form element is sometimes disabled, and when a form element is disabled the browser (e.g. Firefox) doesn't post its value into the POST array. -->
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
		<td><label>Maximum Licenses</label></td>
		<td><input type="text" name="MaxLicenses" id="MaxLicenses" maxlength="8" size="8" onFocus="this.style.background='#FFFF99'" onBlur="document.getElementById('MaxLicensesHidden').value = this.value; this.style.background='white'; if (document.getElementById('OverrideMaxLic').checked) { hideAllErrors(); return checkMaxLicensesOnly(); };" disabled>&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="OverrideMaxLic" id="OverrideMaxLic" value="yes" onClick="if (this.checked) { document.getElementById('MaxLicenses').disabled = false; document.getElementById('MaxLicenses').focus(); document.getElementById('MaxLicensesHidden').value = document.getElementById('MaxLicenses').value; } else { var cap = Math.floor(parseInt(document.getElementById('Population').value)/1250000); if (cap > 5) { cap = 5; } else if (cap < 1) { cap = 1; }; document.getElementById('MaxLicenses').value = cap; document.getElementById('MaxLicensesHidden').value = cap; document.getElementById('MaxLicenses').disabled = true; };">&nbsp;<label class="small">[check box to override default maximum  for this locale]</label><input type="hidden" name="MaxLicensesHidden" id="MaxLicensesHidden"/>
		<div class="error" id="MaxLicensesError"><br>Enter the maximum number of licenses here. (Must be greater than zero.)<br></div>
				</td>
		</tr>
		<tr>
		<td colspan="2" align="center"><br><input type="submit" name="AddNewLocale" class="buttonstyle" value="Add New Locale" onClick="return checkForm();"></td>
		</tr>
		</table>
		</form>
		</div>
 
		<div style="position: absolute; left: 125px; top: 620px; width: 530px; padding: 15px 15px 10px 15px; border: 2px solid #444444; border-color: #425A8D; display: block;">
		<a name="waitlistretrieval"><h1 style="margin-left: 0px;">Retrieve wait-listed mediators from database:</h1></a>
		<br>
		<form method="post" action="admin4.php">
		<table width="530">
		<tr height="30px" valign="middle">
		<td valign="middle"><label>State(s) of Interest</label></td>
		<td>
		<select name='WaitlistState' id='WaitlistState' size='1' style='font-size: 14px; font-weight:normal; font-family:Geneva,Arial,Helvetica,sans-serif; width: 280px; color: #425A8D; background-color: #fafafa; border: 1px solid #425A8D;' onChange='this.form.submit();'>
		<option value=''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;&nbsp;All States&nbsp;&gt;</option><option value='AL'>Alabama</option><option value='AK'>Alaska</option><option value='AZ'>Arizona</option><option value='AR'>Arkansas</option><option value='CA'>California</option><option value='CO'>Colorado</option><option value='CT'>Connecticut</option><option value='DE'>Delaware</option><option value='DC'>District of Columbia (D.C.)</option><option value='FL'>Florida</option><option value='GA'>Georgia</option><option value='HI'>Hawaii</option><option value='ID'>Idaho</option><option value='IL'>Illinois</option><option value='IN'>Indiana</option><option value='IA'>Iowa</option><option value='KS'>Kansas</option><option value='KY'>Kentucky</option><option value='LA'>Louisiana</option><option value='ME'>Maine</option><option value='MD'>Maryland</option><option value='MA'>Massachusetts</option><option value='MI'>Michigan</option><option value='MN'>Minnesota</option><option value='MS'>Mississippi</option><option value='MO'>Missouri</option><option value='MT'>Montana</option><option value='NE'>Nebraska</option><option value='NV'>Nevada</option><option value='NH'>New Hampshire</option><option value='NJ'>New Jersey</option><option value='NM'>New Mexico</option><option value='NY'>New York</option><option value='NC'>North Carolina</option><option value='ND'>North Dakota</option><option value='OH'>Ohio</option><option value='OK'>Oklahoma</option><option value='OR'>Oregon</option><option value='PA'>Pennsylvania</option><option value='RI'>Rhode Island</option><option value='SC'>South Carolina</option><option value='SD'>South Dakota</option><option value='TN'>Tennessee</option><option value='TX'>Texas</option><option value='UT' selected>Utah</option><option value='VT'>Vermont</option><option value='VA'>Virginia</option><option value='WA'>Washington</option><option value='DC'>Washington, D.C.</option><option value='WV'>West Virginia</option><option value='WI'>Wisconsin</option><option value='WY'>Wyoming</option>		</select>
		</td>
		</tr>
		<tr height="30px" valign="middle">
		<td valign="middle"><label>Locale(s) of Interest</label></td>
		<td>
		<select name='WaitlistLocale' id='WaitlistLocale' size='1' style="font-size: 14px; font-weight: normal; font-family: Geneva, Arial, Helvetica, sans-serif; width: 280px; color: #425A8D; background-color: #fafafa; border: 1px solid #425A8D;" >
		<option value=null selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;&nbsp;All Locales&nbsp;&gt;</option>
		<option value="Brigham City_UT">Brigham City</option><option value="Cedar City_UT">Cedar City</option><option value="Heber_UT">Heber</option><option value="Logan_UT-ID">Logan</option><option value="Ogden-Clearfield_UT">Ogden-Clearfield</option><option value="Price_UT">Price</option><option value="Provo-Orem_UT">Provo-Orem</option><option value="Salt Lake City_UT">Salt Lake City</option><option value="St. George_UT">St. George</option><option value="Vernal_UT">Vernal</option>		</select>
		</td>
		</tr>
		<tr height="30px" valign="middle">
		<td valign="middle"><label>With Telephone Numbers</label></td>
		<td>
		<input type="checkbox" name="WithPhoneNos" value="1"><span class="greytextsmall">&nbsp;&nbsp;Check box to include only mediators who provided a phone number</span>
		</td>
		</tr>
		<tr height="30px" valign="middle">
		<td valign="middle"><label>With Names</label></td>
		<td>
		<input type="checkbox" name="WithNames" value="1"><span class="greytextsmall">&nbsp;&nbsp;Check box to include only mediators who provided a name</span>
		</td>
		</tr>
		<tr height="30px" valign="middle">
		<td valign="middle"><label>License Expiration</label></td>
		<td>
		<select name="LicExpPast" id="LicExpPast" size="1" style="font-size: 14px; font-weight:normal; font-family:Geneva,Arial,Helvetica,sans-serif; color: #425A8D; background-color: #fafafa; border: 1px solid #425A8D;" onChange="if (this.selectedIndex != 0) { document.getElementById('LicExpFuture').disabled = true; } else { document.getElementById('LicExpFuture').disabled = false; };">
		<option value="anytime" selected>Expired: Anytime</option>
		<option value="TBD">In Past 24 Hours</option>
		<option value="TBD">In Past Week</option>
		<option value="TBD">In Past Month</option>
		<option value="TBD">In Past 3 Months</option>
		<option value="TBD">In Past 6 Months</option>
		<option value="TBD">In Past 12 Months</option>
		<option value="TBD">In Past 3 Years</option>
		</select>
		&nbsp;&nbsp;&nbsp;
		<select name="LicExpFuture" id="LicExpFuture" size="1" style="font-size: 14px; font-weight:normal; font-family:Geneva,Arial,Helvetica,sans-serif; color: #425A8D; background-color: #fafafa; border: 1px solid #425A8D;" onChange="if (this.selectedIndex != 0) { document.getElementById('LicExpPast').disabled = true; } else { document.getElementById('LicExpPast').disabled = false; };">
		<option value="anytime" selected>Will Expire: Anytime</option>
		<option value="TBD">In Next 24 Hours</option>
		<option value="TBD">In Next Week</option>
		<option value="TBD">In Next Month</option>
		<option value="TBD">In Next 3 Months</option>
		<option value="TBD">In Next 6 Months</option>
		<option value="TBD">In Next 12 Months</option>
		<option value="TBD">In Next 3 Years</option>
		</select>
		</td>
		</tr>
		<tr>
		<td colspan="2" align="center"><br><input type="submit" name="RetrieveWaitlisters" class="buttonstyle" value="Retrieve Mediators"></td>
		</tr>
		</table>
		</form>
		
		</div>
		
		<div style="position: absolute; top: 7px; left: 360px; padding: 0px;">
		<form method="post" action="unwind.php">
		<input type="submit" name="Logout" class="submitLinkSmall" value="Log Out">
		</form>
		<br>
		</div>
 
			
</body>
</html>
