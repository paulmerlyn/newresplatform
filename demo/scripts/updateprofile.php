<?php 
// Start a session
session_start();
header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
header( "Cache-Control: no-store, no-cache, must-revalidate" );
header('Cache-Control: post-check=0, pre-check=0', FALSE); 
header( "Pragma: no-cache" );
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Pragma" content="no-cache">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="Expires" CONTENT="Mon, 26 Jul 1997 05:00:00 GMT">
<title>Mediator Profile</title>
<link href="/nrcss.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="javascript" src="/scripts/emailaddresschecker.js"></script>
<script type="text/javascript" language="javascript" src="/scripts/boxover.js"></script>
<script type="text/javascript" src="/scripts/characterCounter.js"></script>
<style>
.error { font-family: Tahoma; font-size: 8pt; color: red; margin-left: 0px; display:none; }
.errorphp { font-family: Tahoma; font-size: 8pt; color: red; margin-left: 0px; }
.submitLink { font-faily: Arial, sans-serif; color: #FF9900; background-color: transparent; text-decoration: none; font-weight: bold; font-size: 14px; border: none; cursor: pointer; cursor: hand; } /* Not currently used as the class of a submit button */
.submitLink:hover { font-faily: Arial, sans-serif; color: #FF9900; background-color: transparent; text-decoration: underline; font-weight: bold; font-size: 14px; border: none; cursor: pointer; cursor: hand; position: relative; left: 700px; top: 50px; } /* Not currently used as the class of a submit button */
</style>

<script type="text/javascript">
/*
These javascript functions get used for inline form validation (ref. http://www.interspire.com/content/articles/23/1/Using-Inline-Form-Validation#postedcomment -->
*/
var anchordestn = "#"; // Initialize this global variable.

function checkNameOnly()
{
// Validate Name field.
var nameValue = document.getElementById("Name").value;
var nameLength = nameValue.length;
var illegalCharSet = /[^A-Za-z,-\.\& ]+/; // Exclude everything except A-Z, a-z, comma, period, hyphen, &, and space.
var reqdCharSet = /^[A-Z][a-z\.]+(-| )[A-Za-z,-\. ]+/;  // Names of form J. Edgar Hoover, Lou-Ann Kay, Jr., etc.
if (nameLength < 5 || (illegalCharSet.test(nameValue)) || !(reqdCharSet.test(nameValue)))
	{
	document.getElementById("NameError").style.display = "inline";
	anchordestn = "#NameAnchor";
	return false;
	}
else
	{
	return true;
	}
} 

function checkCredentialsOnly()
{
// Validate Credentials field.
var credentialsValue = document.getElementById("Credentials").value;
illegalCharSet = /[^A-Za-z,-\. ]+/; // Exclude everything except A-Z, a-z, comma, period, hyphen, space.
if (illegalCharSet.test(credentialsValue))
	{
	document.getElementById("CredentialsError").style.display = "inline";
	anchordestn = "#CredentialsAnchor";
	return false;
	} 
else
	{
	return true;
	}
}

function checkLocaleLabelOnly()
{
// Validate LocaleLabel field.
var localeLabelValue = document.getElementById("LocaleLabel").value;
var localeLabelLength = localeLabelValue.length;
illegalCharSet = /[^A-Za-z-\.\/\(\) ]+/; // Exclude everything except A-Z, a-z, period, hyphen, slash, space, or parentheses.
reqdCharSet = /^[A-Z][a-z\.]+[(-|\/| |)[A-Za-z-\.\/\(\) ]+/;  // Names of form initial capital (e.g. San Jose-Gilroy) followed by potentially a period (e.g. N. Chicago) or lower case. May include dashes, slashes, or spaces. Also may have ( and ) for "(Test)" appendage.
if (localeLabelLength > 28 || illegalCharSet.test(localeLabelValue) || !(reqdCharSet.test(localeLabelValue)))
	{
	document.getElementById("LocaleLabelError").style.display = "inline";
	anchordestn = "#LocaleLabelAnchor";
	return false;
	}
else
	{
	return true;
	}
}

function checkEmailOnly()
{
// Validate Email field.
var emailValue = document.getElementById("Email").value;
var emailLength = emailValue.length;
if (!document.getElementById('UseProfessionalEmail').checked)  // If mediator checks the UseProfessionalEmail checkbox to select ProfessionalEmail, emailValue will be set to empty string '', so don't test it for being a valid email address.
	{
	if (emailLength > 60 || !(emailCheck(emailValue,'noalert'))) // emailCheck() is function in emailaddresscheker.js. This field is reqd i.e. blank causes a rejection as invalid.
		{
		document.getElementById("EmailError").style.display = "inline";
		anchordestn = "#EmailAnchor";
		return false;
		}
	else
		{
		return true;
		}
	}
} 

function checkLocationsOnly()
{
// Validate Locations field.
var locationsValue = document.getElementById("Locations").value;
illegalCharSet = /[^A-Za-z0-9,-\/\.\(\) ]+/; // Exclude everything except A-Z, a-z, period, hyphen, slash, space, comma, and parentheses. Note: I also need to exclude numbers from the illegal list b/c I use numbers in the demo database for the Locale = 'Your Locale Here', where I have locations = 'Main Location', 'Satellite Location 1', 'Satellite Location 2', etc.
reqdCharSet = /^[A-Z][a-z\.]+(-|\/| |,|)[A-Za-z,-\.\/\(\) ]+/;  // Names of form initial capital (e.g. San Jose) followed by potentially a period (e.g. N. Chicago) or lower case. May include dashes, slashes, commas, or spaces.
if (illegalCharSet.test(locationsValue) || !(reqdCharSet.test(locationsValue)))
	{
	document.getElementById("LocationsError").style.display = "inline";
	//document.getElementById("Locations").select();
	//document.getElementById("Locations").focus();
	anchordestn = "#LocationsAnchor";
	return false;
	} 
else
	{
	return true;
	}
}

function checkEntityNameOnly()
{
// Validate EntityName field.
var entityNameValue = document.getElementById("EntityName").value;
illegalCharSet = /[^A-Za-z0-9,-\.\& ]+/; // Exclude everything except A-Z, a-z, 0-9, comma, period, hyphen, space.
if (entityNameValue != null && entityNameValue != '') // Since EntityName is not a required field, only look for illegal characters if entityNameValue is neither null nor empty.
	{
	if (illegalCharSet.test(entityNameValue))
		{
		document.getElementById("EntityNameError").style.display = "inline";
		anchordestn = "#EntityNameAnchor";
		return false;
		} 
	else
		{
		return true;
		}
	}
else
	{
	return true;
	}
}

function checkPrincipalStreetOnly()
{
// Validate PrincipalStreet field.
var principalStreetValue = document.getElementById("PrincipalStreet").value;
illegalCharSet = /[^A-Za-z0-9,-\.\# ]+/; // Exclude everything except A-Z, a-z, 0-9, #, comma, period, hyphen, space.
if (illegalCharSet.test(principalStreetValue))
	{
	document.getElementById("PrincipalStreetError").style.display = "inline";
	anchordestn = "#PrincipalStreetAnchor";
	return false;
	} 
else
	{
	return true;
	}
}

function checkPrincipalAddressOtherOnly()
{
// Validate PrincipalAddressOther field.
var principalAddressOtherValue = document.getElementById("PrincipalAddressOther").value;
illegalCharSet = /[^A-Za-z0-9,-\.\#\& ]+/; // Exclude everything except A-Z, a-z, 0-9, #, comma, period, hyphen, space.
if (illegalCharSet.test(principalAddressOtherValue))
	{
	document.getElementById("PrincipalAddressOtherError").style.display = "inline";
	anchordestn = "#PrincipalAddressOtherAnchor";
	return false;
	} 
else
	{
	return true;
	}
}

function checkCityOnly()
{
// Validate City field.
var cityValue = document.getElementById("City").value;
var cityLength = cityValue.length;
//illegalCharSet = /[^A-Z]+[^a-z- ]+/; // Exclude everything except A-Z, a-z, hyphen, space.
illegalCharSet = /[^A-Za-z-\.\(\) ]+/; // Exclude everything except A-Z, a-z, period, hyphen, space, or parentheses.
reqdCharSet = /^[A-Z][a-z\.]+[(-|\/| |)[A-Za-z-\.\/\(\) ]+/;  // Names of form initial capital (e.g. San Jose-Gilroy) followed by potentially a period (e.g. N. Chicago) or lower case. May include dashes, slashes, or spaces. Also may have ( and ) for "(Test)" appendage.
if (cityLength < 3 || illegalCharSet.test(cityValue) || !(reqdCharSet.test(cityValue)))
	{
	document.getElementById("CityError").style.display = "inline";
	//document.getElementById("City").select();
	//document.getElementById("City").focus();
	anchordestn = "#CityAnchor";
	return false;
	} 
else
	{
	return true;
	}
}

function checkStateOnly()
{
// Validate State selection.
if (document.getElementById('State').selectedIndex == 0)
	{
	document.getElementById("StateError").style.display = "inline";
	return false;
	} 
else
	{
	return true;
	}
}

function checkZipOnly()
{
// Validate Zip field.
var zipValue = document.getElementById("Zip").value;
reqdCharSet = /^(\d{5})$|(\d{5}-\d{4})$/;  
if (!reqdCharSet.test(zipValue))
	{
	document.getElementById("ZipError").style.display = "inline";
	anchordestn = "#ZipAnchor";
	return false;
	} 
else
	{
	return true;
	}
}

function checkTelephoneOnly()
{
// Validate Telephone (Tel1, Tel2, Tel3, and Ext fields).
var tel1Value = document.getElementById("Tel1").value;
var tel2Value = document.getElementById("Tel2").value;
var tel3Value = document.getElementById("Tel3").value;
var extValue = document.getElementById("Ext").value;
var tel1Length = tel1Value.length;
var tel2Length = tel2Value.length;
var tel3Length = tel3Value.length;
illegalCharSet = /[^0-9]+/; // Reject everything that contains one or more non-digits.
if (illegalCharSet.test(tel1Value) || illegalCharSet.test(tel2Value) || illegalCharSet.test(tel3Value) || illegalCharSet.test(extValue) || !(tel1Length==0 || tel1Length==3) || !(tel2Length==0 || tel2Length==3) || !(tel3Length==0 || tel3Length==4))
	{
	document.getElementById("TelephoneError").style.display = "inline";
	anchordestn = "#TelephoneAnchor";
	return false;
	} 
else
	{
	return true;
	}
}

function checkFaxOnly()
{
// Validate Fax (Fax1, Fax2, and Fax3 fields).
var fax1Value = document.getElementById("Fax1").value;
var fax2Value = document.getElementById("Fax2").value;
var fax3Value = document.getElementById("Fax3").value;
var fax1Length = fax1Value.length;
var fax2Length = fax2Value.length;
var fax3Length = fax3Value.length;
illegalCharSet = /[^0-9]+/; // Reject everything that contains one or more non-digits.
if (illegalCharSet.test(fax1Value) || illegalCharSet.test(fax2Value) || illegalCharSet.test(fax3Value) || !(fax1Length==0 || fax1Length==3) || !(fax2Length==0 || fax2Length==3) || !(fax3Length==0 || fax3Length==4))
	{
	document.getElementById("FaxError").style.display = "inline";
	anchordestn = "#FaxAnchor";
	return false;
	} 
else
	{
	return true;
	}
}

function checkProfileOnly()
{
// Validate Profile field.
var profileValue = document.getElementById("Profile").value;
profileValue = profileValue.replace(/^\s*|\s*$/g,''); // PHP inside processprofile.php will trim white space from beginning and end of the string, so there's no need to "catch" any such white space during Javascript validation. Better to avoid annoying user by just trimming it here inside updateprofile.php then JS-validating what remains of the profileValue string. (Note: JS doesn't have a trim() function analogous to PHP, so I build one using JS's replace() method.
var profileLength = profileValue.length;
illegalCharSet1 = /[\r\n\t\f\v]+/; // Reject special characters return, newline, tab, form feed, vertical tab.
illegalCharSet2 = /( I | i | am )+/; // Reject if we find an " I " or an " i " (lower-case people) or an " am " in the text.
reqdCharSet = /(he| she | He | She | they | They )/; // I use "he" instead of " he " b/c Lorem ipsum has a "he" in it, but no " he ".
if (illegalCharSet1.test(profileValue) || (profileLength>900) || (profileLength<300)) // First, check the validity of the user's profile text.
	{
	document.getElementById("ProfileError1").style.display = "inline";
	anchordestn = "#ProfileAnchor";
	return false;
	} 
else if (illegalCharSet2.test(profileValue) || !reqdCharSet.test(profileValue)) // Second, check for evidence of third-person.
	{
	document.getElementById("ProfileError2").style.display = "inline";
	anchordestn = "#ProfileAnchor";
	return false;
	} 
else
	{
	return true;
	}
}

function checkHrlyRateOnly()
{
// Validate HrlyRate fields.
var hrlyRateValue = document.getElementById("HrlyRate").value;
var hrlyRateLength = hrlyRateValue.length;
illegalCharSet = /[^0-9]+/; // Reject everything that contains one or more non-digits..
reqdCharSet = /[0-9]{2,}/;  // At least two numerics.
if (document.getElementById('ShowHrlyRate').checked) // Only validate HrlyRate field if the ShowHrlyRate box is checked.
	{
	if (illegalCharSet.test(hrlyRateValue) || !reqdCharSet.test(hrlyRateValue))
		{
		document.getElementById("HrlyRateError").style.display = "inline";
		anchordestn = "#HrlyRateAnchor";
		return false;
		} 
	else
		{
		return true;
		}
	}
else
	{
	return true;
	}
}

function checkAdminChgAmtOnly()
{
// Validate AdminChgAmt field.
var adminChgAmtValue = document.getElementById("AdminChgAmt").value;
var adminChgAmtLength = adminChgAmtValue.length;
illegalCharSet = /[^0-9]+/; // Reject everything that contains one or more non-digits..
reqdCharSet = /[0-9]{2,}/;  // At least two numerics.
if (document.getElementById('ShowAdminCharge').checked) // Only validate AdminChgAmt field if the ShowAdminCharge box is checked.
	{
	if (illegalCharSet.test(adminChgAmtValue) || !reqdCharSet.test(adminChgAmtValue))
		{
		document.getElementById("AdminChgAmtError").style.display = "inline";
		anchordestn = "#AdminChargeDetailsAnchor";
		return false;
		} 
	else
		{
		return true;
		}
	}
else
	{
	return true;
	}
}

function checkLocationChargeOnly()
{
// Validate LocChgFrom and LocChgTo location charge fields.
var locChgFromValue = document.getElementById("LocChgFrom").value;
var locChgToValue = document.getElementById("LocChgTo").value;
var locChgFromLength = locChgFromValue.length;
var locChgToLength = locChgToValue.length;
illegalCharSet = /[^0-9]+/; // Reject everything that contains one or more non-digits.
if (document.getElementById('ShowLocChg').checked) // Only validate LocChgFrom and LocChgTo fields if the ShowLocChg box is checked.
	{
	if (illegalCharSet.test(locChgFromValue) || illegalCharSet.test(locChgToValue) || locChgFromLength==1 || locChgToLength==1) // No need to trouble user who mistakenly makes LocChgFrom value bigger than LocChgTo value b/c that's remedied on the server-side.
		{
		document.getElementById("LocChgError").style.display = "inline";
		//document.getElementById("LocChgFrom").select();
		//document.getElementById("LocChgFrom").focus();
		anchordestn = "#LocationChargeAnchor";
		return false;
		} 
	else
		{
		return true;
		}
	}
else
	{
	return true;
	}
}

function checkPackagesOnly()
{
// Validate NumSessPkg1, PricePkg1, NumSessPkg2, PricePkg2, NumSessPkg3, PricePkg3, SessLengthHrs, and SessLengthMins package fields.
var numSessPkg1Value = document.getElementById("NumSessPkg1").value;
var numSessPkg2Value = document.getElementById("NumSessPkg2").value;
var numSessPkg3Value = document.getElementById("NumSessPkg3").value;
var pricePkg1Value = document.getElementById("PricePkg1").value;
var pricePkg2Value = document.getElementById("PricePkg2").value;
var pricePkg3Value = document.getElementById("PricePkg3").value;
var sessLengthHrsValue = document.getElementById("SessLengthHrs").value; 
var sessLengthMinsValue = document.getElementById("SessLengthMins").value; 
var numSessPkg1Length = numSessPkg1Value.length;
var numSessPkg2Length = numSessPkg2Value.length;
var numSessPkg3Length = numSessPkg3Value.length;
var pricePkg1Length = pricePkg1Value.length;
var pricePkg2Length = pricePkg2Value.length;
var pricePkg3Length = pricePkg3Value.length;
var sessLengthHrsLength = sessLengthHrsValue.length;
var sessLengthMinsLength = sessLengthMinsValue.length;
illegalCharSet = /[^0-9]+/; // Reject everything that contains one or more non-digits.
if (document.getElementById('OfferPkgs').checked) // Only validate NumSessPkg1,2,3 and PricePkg1,2,3 and the SessLengthHrs and SessLengthMins fields if the OfferPkgs box is checked.
	{
	if (illegalCharSet.test(numSessPkg1Value) || illegalCharSet.test(numSessPkg2Value) || illegalCharSet.test(numSessPkg3Value) || illegalCharSet.test(pricePkg1Value) || illegalCharSet.test(pricePkg2Value) || illegalCharSet.test(pricePkg3Value) || illegalCharSet.test(sessLengthHrsValue) || illegalCharSet.test(sessLengthMinsValue) || (numSessPkg1Length > 0 && pricePkg1Length==0)  || (numSessPkg1Length==0 && pricePkg1Length > 0) || (numSessPkg2Length > 0 && pricePkg2Length==0)  || (numSessPkg2Length==0 && pricePkg2Length > 0) || (numSessPkg3Length > 0 && pricePkg3Length==0)  || (numSessPkg3Length==0 && pricePkg3Length > 0) || pricePkg1Length==1 || pricePkg2Length==1 || pricePkg3Length==1 || sessLengthHrsLength==0) // Make sure no illegal characters in any of the seven text fields. Also make sure that three price fields don't contain a single-digit number (silly). Also make sure that if, say, NumSessPkg2 has a number in it then PricePkg2 doesn't have zero length, and vice versa. Finally, make sure that SessLengthHrs has at least one digit. (SessLengthMins can be legitimately blank.)
		{
		document.getElementById("OfferPkgsError").style.display = "inline";
		anchordestn = "#PackagesAnchor";
		return false;
		} 
	else
		{
		return true;
		}
	}
else
	{
	return true;
	}
}

function checkIncrementOnly()
{
// Validate Increment field.
var incrementValue = document.getElementById("Increment").value;
illegalCharSet = /[^0-9]+/; // Reject everything that contains one or more non-digits.
reqdCharSet = /[0-9]+/; // Require at least one digit
if (illegalCharSet.test(incrementValue) || !reqdCharSet.test(incrementValue) || incrementValue > 60 || incrementValue < 1) // Contains an illegal char or does not contain at least one digit or is greater than 60 minutes or is less than 1 minute.
	{
	document.getElementById("IncrementError").style.display = "inline";
	//document.getElementById("Increment").select();
	//document.getElementById("Increment").focus();
	anchordestn = "#IncrementAnchor";
	return false;
	} 
else
	{
	return true;
	}
}

function checkPercentConsltnFeeCreditOnly()
{
// Validate credit for consultation text field, PercentConsltnFeeCredit.
var percentConsltnFeeCreditValue = document.getElementById("PercentConsltnFeeCredit").value;
illegalCharSet = /[^0-9]+/; // Reject everything that contains one or more non-digits.
reqdCharSet = /[0-9]+/; // Require at least one digit if validating
if (document.ProfileForm.OfferConsultFeeCredit[1].checked) // Only validate PercentConsltnFeeCredit field if the 'Yes' OfferConsultFeeCredit radio button is checked.
	{
	if (illegalCharSet.test(percentConsltnFeeCreditValue) || !reqdCharSet.test(percentConsltnFeeCreditValue) || percentConsltnFeeCreditValue > 100 || percentConsltnFeeCreditValue < 1) // Make sure no illegal characters in the text field and that the field conforms to the reqdCharSet. Also make sure that field's value is not greater than 100.)
		{
		document.getElementById("PercentConsltnFeeCreditError").style.display = "inline";
		anchordestn = "#PercentConsltnFeeCreditAnchor";
		return false;
		} 
	else
		{
		return true;
		}
	}
else
	{
	return true;
	}
}

function checkCancellationPolicyOnly()
{
// Validate CancNumber and LateCancFee fields.
var cancNumberValue = document.getElementById("CancNumber").value;
var lateCancFeeValue = document.getElementById("LateCancFee").value;
illegalCharSet = /[^0-9]+/; // Reject everything that contains one or more non-digits.
reqdCharSet = /[0-9]+/; // Require at least one digit
if (illegalCharSet.test(cancNumberValue) || !reqdCharSet.test(cancNumberValue) || illegalCharSet.test(lateCancFeeValue) || !reqdCharSet.test(lateCancFeeValue)) // Contains an illegal char or does not contain at least one digit.
	{
	document.getElementById("CancellationPolicyError").style.display = "inline";
	anchordestn = "#CancellationPolicyAnchor";
	return false;
	} 
else
	{
	return true;
	}
}

function checkCreditCardPolicyOnly()
{
// Validate CCFeePercent text field if LevyFee check box is checked.
var ccFeePercentValue = document.getElementById("CCFeePercent").value;
illegalCharSet = /[^0-9\.]+/; // Reject everything that contains one or more that is neither a period nor a digit.
reqdCharSet = /[0-9]+/; // Require at least one digit if validating
if (document.getElementById('LevyFee').checked) // Only validate CCFeePercent field if the 'LevyFee' check box is checked.
	{
	if (illegalCharSet.test(ccFeePercentValue) || !reqdCharSet.test(ccFeePercentValue) || ccFeePercentValue < 0 || ccFeePercentValue > 20) // Make sure no illegal characters in the text field and that the field conforms to the reqdCharSet. (Note: I've capped the CC fee percent at 20%, presuming no mediator would intentionally enter a percent as large as 20%.) Also make sure that field's value is not less than 0. (A value of $CCFeePercent==0 is handled by processprofile.php prior to updating the database.)
		{
		document.getElementById("CCFeePercentError").style.display = "inline";
		anchordestn = "#CCFeePercentAnchor";
		return false;
		} 
	else
		{
		return true;
		}
	}
else
	{
	return true;
	}
}

function checkStreetPersonalOnly()
{
// Validate StreetPersonal field.
var streetPersonalValue = document.getElementById("StreetPersonal").value;
illegalCharSet = /[^A-Za-z0-9,-\.\# ]+/; // Exclude everything except A-Z, a-z, 0-9, #, comma, period, hyphen, space.
reqdCharSet = /[A-Z]+[a-z]+/; // Require at least one capital letter followed by at least one lower-case letter
if (illegalCharSet.test(streetPersonalValue) || !reqdCharSet.test(streetPersonalValue))
	{
	document.getElementById("StreetPersonalError").style.display = "inline";
	anchordestn = "#StreetPersonalAnchor";
	return false;
	} 
else
	{
	return true;
	}
}

function checkCityPersonalOnly()
{
// Validate CityPersonal field.
var cityPersonalValue = document.getElementById("CityPersonal").value;
var cityPersonalLength = cityPersonalValue.length;
illegalCharSet = /[^A-Za-z- ]+/; // Exclude everything except A-Z, a-z, hyphen, space.
if (cityPersonalLength < 3 || illegalCharSet.test(cityPersonalValue))
	{
	document.getElementById("CityPersonalError").style.display = "inline";
	anchordestn = "#CityPersonalAnchor";
	return false;
	} 
else
	{
	return true;
	}
}

function checkStatePersonalOnly()
{
// Validate StatePersonal selection.
if (document.getElementById('StatePersonal').selectedIndex == 0)
	{
	document.getElementById("StatePersonalError").style.display = "inline";
	return false;
	} 
else
	{
	return true;
	}
}

function checkZipPersonalOnly()
{
// Validate ZipPersonal field.
var zipPersonalValue = document.getElementById("ZipPersonal").value;
reqdCharSet = /^(\d{5})$|(\d{5}-\d{4})$/;  
if (!reqdCharSet.test(zipPersonalValue))
	{
	document.getElementById("ZipPersonalError").style.display = "inline";
	anchordestn = "#ZipPersonalAnchor";
	return false;
	} 
else
	{
	return true;
	}
}

function checkTelephonePersonalOnly()
{
// Validate Telephone (Tel1Personal, Tel2Personal, Tel3Personal fields).
var tel1PersonalValue = document.getElementById("Tel1Personal").value;
var tel2PersonalValue = document.getElementById("Tel2Personal").value;
var tel3PersonalValue = document.getElementById("Tel3Personal").value;
var tel1PersonalLength = tel1PersonalValue.length;
var tel2PersonalLength = tel2PersonalValue.length;
var tel3PersonalLength = tel3PersonalValue.length;
illegalCharSet = /[^0-9]+/; // Reject everything that contains one or more non-digits.
if (illegalCharSet.test(tel1PersonalValue) || illegalCharSet.test(tel2PersonalValue) || illegalCharSet.test(tel3PersonalValue) || tel1PersonalLength!=3 || tel2PersonalLength!=3 || tel3PersonalLength!=4)
	{
	document.getElementById("TelephonePersonalError").style.display = "inline";
	anchordestn = "#TelephonePersonalAnchor";
	return false;
	} 
else
	{
	return true;
	}
}

function checkEmailPersonalOnly()
{
// Validate EmailPersonal field.
var emailPersonalValue = document.getElementById("EmailPersonal").value;
var emailPersonalLength = emailPersonalValue.length;
if (emailPersonalLength > 60 || !(emailCheck(emailPersonalValue,'noalert'))) // emailCheck() is function in emailaddresscheker.js. This field is reqd i.e. blank causes a rejection as invalid.
	{
	document.getElementById("EmailPersonalError").style.display = "inline";
	anchordestn = "#EmailPersonalAnchor";
	return false;
	}
else
	{
	return true;
	}
}; 

/*
This function gets called when the user clicks the 'Submit Now' button. It causes a mass validation of all form fields so the user can correct them in one fell swoop rather than having to see them piecemeal with each submission of the form (i.e. see another validation error each time the user clicks the submit button).
*/
function checkForm() 
{
hideAllErrors();
if (document.getElementById('Locale').value != 'Test Drive') // Only javascript validate all fields (including the personal fields) if the Locale isn't a test driver. 
	{
	hideAllPersonalErrors();
	if (!checkNameOnly() | !checkCredentialsOnly() | !checkLocaleLabelOnly() | !checkLocationsOnly() | !checkEntityNameOnly() | !checkPrincipalStreetOnly() | !checkPrincipalAddressOtherOnly() | !checkCityOnly() | !checkStateOnly() | !checkZipOnly() | !checkTelephoneOnly() | !checkFaxOnly() | !checkProfileOnly() | !checkHrlyRateOnly() | !checkAdminChgAmtOnly() | !checkLocationChargeOnly() | !checkPackagesOnly() | !checkIncrementOnly() | !checkPercentConsltnFeeCreditOnly() | !checkCancellationPolicyOnly() | !checkCreditCardPolicyOnly() | !checkStreetPersonalOnly() | !checkCityPersonalOnly() | !checkStatePersonalOnly() | !checkZipPersonalOnly() | !checkTelephonePersonalOnly() | !checkEmailPersonalOnly())
		{
		return false; // return false if any one of the individual field validation functions returned a false ...
		} 
	else if (!document.getElementById('UseProfessionalEmail').checked) // Only test Email if the UseProfessionalEmail check box was unchecked.
		{
		return checkEmailOnly();
		}	 
	else 
		{
		return true; // ... otherwise, all individual field validations must have returned a true, so let checkForm() return true.
		}
	}
else // No need to examine the values returned by the personal fields for this test-driver case.
	{
	if (!checkNameOnly() | !checkCredentialsOnly() | !checkLocaleLabelOnly() | !checkLocationsOnly() | !checkEntityNameOnly() | !checkPrincipalStreetOnly() | !checkPrincipalAddressOtherOnly() | !checkCityOnly() | !checkStateOnly() | !checkZipOnly() | !checkTelephoneOnly() | !checkFaxOnly() | !checkProfileOnly() | !checkHrlyRateOnly() | !checkAdminChgAmtOnly() | !checkLocationChargeOnly() | !checkPackagesOnly() | !checkIncrementOnly() | !checkPercentConsltnFeeCreditOnly() | !checkCancellationPolicyOnly() | !checkCreditCardPolicyOnly())
		{
		return false; // return false if any one of the individual field validation functions returned a false ...
		}
	else if (!document.getElementById('UseProfessionalEmail').checked) // Only test Email if the UseProfessionalEmail check box was unchecked.
		{
		return checkEmailOnly();
		}	 
	else 
		{
		return true; // ... otherwise, all individual field validations must have returned a true, so let checkForm() return true.
		}
	}
} // End of checkForm()


/* This function hideAllErrors() is called by checkForm() and by onblur event within non-personal form fields. */
function hideAllErrors()
{
document.getElementById("NameError").style.display = "none";
document.getElementById("CredentialsError").style.display = "none";
document.getElementById("LocaleLabelError").style.display = "none";
document.getElementById("LocationsError").style.display = "none";
document.getElementById("EmailError").style.display = "none";
document.getElementById("EntityNameError").style.display = "none";
document.getElementById("PrincipalStreetError").style.display = "none";
document.getElementById("PrincipalAddressOtherError").style.display = "none";
document.getElementById("CityError").style.display = "none";
document.getElementById("StateError").style.display = "none";
document.getElementById("ZipError").style.display = "none";
document.getElementById("TelephoneError").style.display = "none";
document.getElementById("FaxError").style.display = "none";
document.getElementById("ProfileError1").style.display = "none";
document.getElementById("ProfileError2").style.display = "none";
document.getElementById("HrlyRateError").style.display = "none";
document.getElementById("AdminChgAmtError").style.display = "none";
document.getElementById("LocChgError").style.display = "none";
document.getElementById("OfferPkgsError").style.display = "none";
document.getElementById("IncrementError").style.display = "none";
document.getElementById("PercentConsltnFeeCreditError").style.display = "none";
document.getElementById("CancellationPolicyError").style.display = "none";
document.getElementById("CCFeePercentError").style.display = "none";
return true;
}

/* This function hideAllPersonalErrors() is called by checkForm() and by onblur event within personal form fields. */
function hideAllPersonalErrors()
{
document.getElementById("StreetPersonalError").style.display = "none";
document.getElementById("CityPersonalError").style.display = "none";
document.getElementById("StatePersonalError").style.display = "none";
document.getElementById("ZipPersonalError").style.display = "none";
document.getElementById("TelephonePersonalError").style.display = "none";
document.getElementById("EmailPersonalError").style.display = "none";
return true;
}


/*
Function testFileType() is used for javascript validation that the file that the user is about to upload is of an appropriate file type. The function is called onclick of the 'Upload File' button. Source: http://www.codestore.net/store.nsf/cmnts/92EB6662D4D026048625697C00413F63?OpenDocument 
*/
function testFileType(fileName, fileTypes)
{
var fileName, fileTypes;
if (!fileName) return false;
var dots = fileName.split(".")
//get the part AFTER the LAST period.
fileType = "." + dots[dots.length-1];
if (fileTypes.join(".").indexOf(fileType) != -1) // The fileType of the fileName was among the allowable fileTypes
	{
	return true;
	}
	else
	{
	alert("Your file is of type " + fileType + ". Please only upload files that end in types: \n\n" + (fileTypes.join(" or ")) + "\n\nThis is the most common format for photo images. Please select a new file and try again.");
	return false;
	}
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

<?php
// Create short variable names
$Username = $_POST['Username'];
$Password = $_POST['Password'];
$first_time_test_driver = $_COOKIE['first_time_test_driver']; // cookie that's initially set in sales.php. It's used to control whether the profile form fields should be prepopulated from the DB for a non-first-time test-driver, or should be blank for a first-time test driver.

if ($_SESSION['SessValidUserFlag'] != 'true')
{
	if (empty($Username) || empty($Password))
	{
	echo '<h1 class="bannerhead" style="margin-left:120px; position:relative; top: 20px;">Mediator Log-In Page</h1>';
	echo '<h3 style="margin-left:170px; position:relative; top: 10px;">(Demo Database)</h3>';

	// Visitor needs to enter a username and password
	
	?>
	<div style="margin-left:100px;">
	<br><br><br>
	<h3>Welcome! Please log in to access your profile:</h3>
	<form method="post" action="updateprofile.php">
	<table border="0" width="230" style="margin-left:0px;">

<?php
	// Be sure to provide "test driver" instructions regarding the username ('test') and password ('drive') for test drivers who access updateprofile.php from sales.php. Note: The alternative approaches of using PHP's $_SERVER['HTTP_REFERER'] variable has a limitation b/c this referer variable is sometimes blocked by antivirus software. Also, the approach of using Javascript's document.referrer is not supported in some versions of IE. Hence my decision to use a session variable instead for tracking the referral source. (Yet another approach, which I could use, is to submit a hidden form field in sales.php, which would then be available to updateprofile.php via the _POST array.)
if ($_SESSION['ReferredFromSales'] == 'true'): ?>
	<tr>
	<td colspan='2' style='color:#FF0000; height: 65px;'>
	<div style="border-width: 1px; border-style: solid; border-color: red; padding: 10px;"><span class="basictext">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Enter username: <kbd style="color: red;">test</kbd><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Enter password: <kbd style="color: red;">drive</kbd></span></div>
	</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
<?php endif; ?>

	<tr>
	<td><label for="Username">Username:&nbsp;</label></td>
	<td><input type="text" name="Username" id="Username" style="width:125px" maxlength="20" size="20" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'"></td>
	<!-- The style attribute ensures IE/Firefox consistency -->
	</tr>
	<tr>
	<td><label for="Password">Password:&nbsp;</label></td>
	<td><input type="password" name="Password" id="Password" style="width:125px" maxlength="40" size="20" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'"></td>
	</tr>
	<tr>
	<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
	<td colspan="2" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Log In" class="buttonstyle"></td>
	</tr>
	</table>
	</form>
	</div>
	<?php
	}
}

if (($_SESSION['SessValidUserFlag'] == 'true') || ((!empty($Username)) && (!empty($Password))))
{
// If the SessValidUserFlag is 'true', then the user has already been previously validated and his/her username and password would have been stored in session variables. In this case, we can set the $Username and $Password to those session variable values.
if ($_SESSION['SessValidUserFlag'] == 'true') { $Username = $_SESSION['SessUsername']; $Password = $_SESSION['SessPassword']; };

// Connect to mysql, using either the username and password that the user just typed into the above form, or using stored values for username and password that are stored in their respective session variables.
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
or die('Could not connect: ' . mysql_error());
mysql_select_db('paulme6_newresolution') or die('Could not select database');

// Query the database to see if a record exists with a username-password that matches the values in the POST array. Since the password typed by the user live is different from the encoded password that's retrieved from the database and stored in a session variable, we need to create two different $query strings -- one for the live human path and one for the already-validated path.
if ($_SESSION['SessValidUserFlag'] == 'true')
{ $query = "select count(*) from mediators_table_demo where
		Username = '$Username' and 
		Password = '$Password'"; }
else
{ $query = "select count(*) from mediators_table_demo where
		Username = '$Username' and 
		Password = sha1('$Password')";};

$result = mysql_query($query) or die('Username-password query failed: ' . mysql_error());

$row = mysql_fetch_row($result); // $row array should have just one item, which holds either '0' or '1'
$count = $row[0];

if (($count == 1) || ($_SESSION['SessValidUserFlag'] == 'true'))
	{
	// The visitor's username and password were correct. (Remember also to query database for ID and store it in session variable so it's accessible later by processprofile.php.) Again, create two different $query strings, one for the live human path and the other for when the password was retrieved in encoded form from the database.
	if ($_SESSION['SessValidUserFlag'] == 'true')
		{ $query = "select * from mediators_table_demo where 
			Username = '$Username' and 
			Password = '$Password'"; }
		else { $query = "select * from mediators_table_demo where 
			Username = '$Username' and 
			Password = sha1('$Password')"; };
			
	$result = mysql_query($query) or die ('Failed to retrieve ID for username-password: '.mysql_error());
	$line = mysql_fetch_array($result);
	
	// Store ID, Username, Password, Locale, and validation user flag as session variables, which are necessary because data in the $_POST[] array would get forgotten on leaving updateprofile.php and visiting processprofile.php and on reloading updateprofile.php after processing the photo file.
	$_SESSION['SessID'] = $line['ID'];
	$_SESSION['SessUsername'] = $line['Username'];
	$_SESSION['SessPassword'] = $line['Password'];
	$_SESSION['SessLocale'] = $line['Locale'];
	$_SESSION['SessValidUserFlag'] = 'true'; // Set a 'valid user' flag so user doesn't have to log in again when this updateprofile.php page is reloaded e.g. after clicking the photo file submit button.

	// Even though user has successfully logged in, eject user (and Suspend his/her profile) if the AdminFreeze column pertaining to his/her ID is '1' (i.e. true). Also set the SessValidUserFlag to false.
	if ($line['AdminFreeze'] == 1)
		{
		echo '<br><p class=\'basictext\' style=\'margin-left: 50px; margin-right: 50px; font-size: 14px;\'>Your account (username = &lsquo;'.$line['Username'].'&rsquo;) has been frozen. Please contact Client Services to reactivate your account. Thank you.</p>';
		$_SESSION['SessValidUserFlag'] = 'false'; // Allows user to try to log in again via updateprofile.php (though the attempt will result in ejection unless the account has been unfrozen by an Administrator).
		exit;
		}
?>

	<form name="LogOutForm" method="post" action="processprofile.php">
	<input type="hidden" name="LoggedOut" id="LoggedOut" value="true">
	<input type="submit" class="submitLink" value="Log Out" style="position: relative; left: 728px; top: 70px;">
	</form> <!-- I've disguised the submit button as a link -->
	<h1 style="margin-left:250px; position:relative; bottom: 10px; font-size: 22px;">Mediator Profile Entry Form</h1>
	<!--<a style="margin-left:720px" href="javascript: document.LogOutForm.submit();">Log out</a>-->
	<form enctype="multipart/form-data" name="ProfileForm" action="processprofile.php" method="post">
	<table border="0" width="800">
	<tr>
	<td colspan="5"><HR align="left" size="1px" noshade color="#FF9900" style="margin-top: 12px;"><h2 style="margin-left:20px; margin-top:6px; margin-bottom:10px;">PROFESSIONAL INFORMATION</h2></td>
	</tr>
	<tr>
	<td rowspan="18" width="20">&nbsp;</td>
	<td valign="top"><label for="Name">Name</label></td>
	<td rowspan="43">&nbsp;&nbsp;</td>
	<td colspan="2"><a name="NameAnchor" class="plain"><input type="text" name="Name" id="Name" maxlength="50" size="30" value="<?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') echo $line['Name']; ?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkNameOnly();"></a><span class="redsup">&nbsp;&nbsp;<b>*</b> (required)</span><div class="greytextsmall">Examples: Jane Doe or John Doe, Jr.</div>
	<div class="error" id="NameError">Please enter your name here. Use initial capital (upper-case) letters. Examples: <i>Jane Doe</i> or <i>John Doe, Jr.</i><br></div><?php if ($_SESSION['MsgName'] != null) { echo $_SESSION['MsgName']; $_SESSION['MsgName']=null; } ?></td>
	</tr>
	<tr>
	<td valign="top"><label for="Credentials">Credentials</label></td>
	<td colspan="2"><a name="CredentialsAnchor" class="plain"><input type="text" name="Credentials" id="Credentials" maxlength="50" size="20" value="<?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') echo $line['Credentials']?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkCredentialsOnly();"></a><span title="header=[Help: Credentials] body=[Enter here your academic and professional credentials, separated by commas i.e. any so-called &ldquo;letters after your name.&rdquo; Example: M.F.T., Ph.D., J.D.] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span><div class="greytextsmall">Examples: M.F.T., Ph.D., or J.D.</div><div class="error" id="CredentialsError">If included, credentials must be in the appropriate format. Examples: <i>M.F.T., Esq., Psy.D.,</i> or <i>M.A.</i><br></div><?php if ($_SESSION['MsgCredentials'] != null) { echo $_SESSION['MsgCredentials']; $_SESSION['MsgCredentials']=null; } ?></td>
	</tr>
	<tr>
	<!-- Note: I foreshorten the Locale text field when Locale is 'Test Drive' for no other reason than aesthetic symmetry with the Locale Label field. The important reason for shortening the Locale Label field is formatting, as explained below. -->
	<td><label for="Locale">Locale</label></td>
	<td colspan="2"><input type="text" name="Locale" id="Locale" maxlength="40" size="<?php if ($line['Locale'] != 'Test Drive') echo '40'; else echo '22' ?>" value="<?=$line['Locale']?>" disabled></td>
	</tr>
	<tr>
	<td><label for="LocaleLabel">Locale Label</label></td>
	<!-- Strip out the ' (Test) appendage from the LocaleLabel if one exists before prepopulating this text field. Also shorten length of field for test drivers because the appendage will be added, potentially causing formatting overrun problems in localeslisttable.html if the field were not foreshortened. -->
	<td colspan="2"><a name="LocaleLabelAnchor" class="plain"><input type="text" name="LocaleLabel" id="LocaleLabel" maxlength="<?php if ($line['Locale'] != 'Test Drive') echo '28'; else echo '22' ?>" size="<?php if ($line['Locale'] != 'Test Drive') echo '28'; else echo '22' ?>" value="<?php if ($line['Locale'] != 'Test Drive') echo $line['LocaleLabel']; else if ($first_time_test_driver != 'true') echo str_replace(' (Test)','',$line['LocaleLabel']); ?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkLocaleLabelOnly();"></a><span class="redsup">&nbsp;&nbsp;<b>*</b></span><span title="header=[Help: Locale Label] body=[The Locale Label is the user-friendly way by which you describe your locale. It appears in the right margin on many pages of the New Resolution web site, and it&rsquo;s used by search engines to help clients find you. Choose an appropriate label with this in mind. Examples: &lsquo;San Francisco&rsquo;, &lsquo;San Francisco - Oakland&rsquo;, or &lsquo;San Francisco Bay Area&rsquo;.] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span><div class="error" id="LocaleLabelError"><br>Use only letters (A-Z, a-z), dash (-), slash (/), period (.) and space characters here. Also, use initial capital<br>(upper-case) letters. Examples: <i>San Francisco</i> or <i>San Francisco Bay Area</i> or <i>San Francisco-Oakland-Berkeley</i><br></div><?php if ($_SESSION['MsgLocaleLabel'] != null) { echo $_SESSION['MsgLocaleLabel']; $_SESSION['MsgLocaleLabel']=null; } ?></td>
	</tr>
	<tr>
	<td valign="top"><label for="Email">Email</label></td>
	<td colspan="2">
	<a name="EmailAnchor" class="plain"><input type="text" name="Email" id="Email" maxlength="50" size="45" value="<?php if (($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') && $line['UseProfessionalEmail'] != 1) echo $line['Email']?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkEmailOnly();" <?php if ($line['UseProfessionalEmail'] == 1) echo ' disabled';?>></a><br>
	<div class="greytextsmall">Example: jdoe@earthlink.net</div>
	<div class="error" id="EmailError">The format of your email address is invalid. Please provide a valid address.<br></div><?php if ($_SESSION['MsgEmail'] != null) { echo $_SESSION['MsgEmail']; $_SESSION['MsgEmail']=null; } ?>
	<input type="checkbox" name="UseProfessionalEmail" id="UseProfessionalEmail" value="1" onclick="hideAllErrors(); if (document.getElementById('UseProfessionalEmail').checked) { document.getElementById('Email').disabled = true; document.getElementById('Email').value = ''; } else { document.getElementById('Email').disabled = false; document.getElementById('Email').focus(); };" <?php if ($line['Locale'] == 'Test Drive' || $line['ProfessionalEmail'] == null || $line['ProfessionalEmail'] == '') { echo ' disabled';}  else if ($line['UseProfessionalEmail'] == 1) { echo ' checked';}; ?>>&nbsp;<label>Use my assigned professional email address instead:&nbsp;&nbsp;</label><span class="basictext"><?php if ($line['ProfessionalEmail'] == null || $line['ProfessionalEmail'] == '') echo '[available on request]'; else echo $line['ProfessionalEmail']; ?></span>
<span title="header=[Help: Professional Email Address] body=[Please contact support@newresolutionmediation.com to request a <br>custom email account of type prefix@newresolutionmediation.com.<br>Let us know your preferred prefix (e.g. your first name or your initials). We&rsquo;ll set up the account on our server and notify you with instructions (usually in 1-2 business days) when your new address is ready for use.] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span></td>
	</tr>
	<tr>
	<td valign="top"><label for="Locations">Location(s)</label></td>
	<td colspan="2"><a name="LocationsAnchor" class="plain"><textarea name="Locations" id="Locations" rows="4" cols="60" wrap="soft" style="overflow:auto; height: 80px; width: 405px;" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkLocationsOnly();"><?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') echo str_replace('"','',$line['Locations']); ?></textarea></a><span class="redsup" style="position: relative; top: -60px;">&nbsp;&nbsp;<b>*</b></span><span title="header=[Help: Locations] body=[List here &mdash; separated by commas if more than one &mdash; the location(s) where you conduct mediations. Each location will typically be the name of a city or the name of a neighborhood. Example: Downtown, South Central, Palmdale, Lake District&nbsp;] singleclickstop=[on] requireclick=[on]" style="position:relative; bottom: 60px;"><img src="/images/QuestionMarkIcon.jpg"></span><div class="greytextsmall">Example: Downtown, South Central, Palmdale, Lake District</div><div class="error" id="LocationsError">Please enter valid location(s). Separate each location with a comma (,). Use only letters (A-Z, a-z),<br>dash (-), slash (/), period (.), comma, and space characters. The initial letter of each location should<br>be a capital (upper-case) letter. Example: <i>San Francisco, Midtown, Upper East Side</i><br></div><?php if ($_SESSION['MsgLocations'] != null) { echo $_SESSION['MsgLocations']; $_SESSION['MsgLocations']=null; } ?></td>
	</tr>
	<tr>
	<td valign="top"><label for="EntityName">Entity Name</label></td>
	<td colspan="2"><a name="EntityNameAnchor" class="plain"><input type="text" name="EntityName" id="EntityName" maxlength="200" size="64" value="<?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') echo $line['EntityName']?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkEntityNameOnly();"></a><span title="header=[Help: Entity Name] body=[This will be blank for most mediators. However, you can enter here an entity name if you wish. Example: &lsquo;Mediation and Law Office of Jane Doe, Esq.&rsquo;] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span><div class="error" id="EntityNameError"><br>Please check your format. Use only alphanumerics (A-Z, a-z, 0-9), dash (-), slash (/), period (.),<br>&, and space characters. Example: <i>Law and Mediation Office of Jane Doe & Associates</i><br></div><?php if ($_SESSION['MsgEntityName'] != null) { echo $_SESSION['MsgEntityName']; $_SESSION['MsgEntityName']=null; } ?></td>
	</tr>
	<tr>
	<td valign="top"><label for="PrincipalStreet">Principal Street</label></td>
	<td colspan="2"><a name="PrincipalStreetAnchor" class="plain"><input type="text" name="PrincipalStreet" id="PrincipalStreet" maxlength="100" size="64" value="<?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') echo $line['PrincipalStreet']?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkPrincipalStreetOnly();"></a><span title="header=[Help: Principal Street] body=[This is the address of your principal (main) office location. Example: &ldquo;448 Lincoln Way&rdquo;. Note: If your address includes a suite number (e.g. Suite 200) or building name (e.g. The Towers), you may either include&nbsp;<br>it here or in the &lsquo;Principal Address (Other)&rsquo; field below.] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span><div class="error" id="PrincipalStreetError"><br>Please enter a valid street address. Use only alphanumerics (A-Z, a-z, 0-9), dash (-), pound (#),<br>period (.), and space characters. Examples: <i>925 N. Washington Ave.</i> or </i><i>8708 Oak Street, #3</i><br></div><?php if ($_SESSION['MsgPrincipalStreet'] != null) { echo $_SESSION['MsgPrincipalStreet']; $_SESSION['MsgPrincipalStreet']=null; } ?></td>
	</tr>
	<tr>
	<td valign="top"><label for="PrincipalAddressOther">Principal Address (Other)</label></td>
	<td colspan="2"><a name="PrincipalAddressOtherAnchor" class="plain"><input type="text" name="PrincipalAddressOther" id="PrincipalAddressOther" maxlength="100" size="64" value="<?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') echo $line['PrincipalAddressOther']?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkPrincipalAddressOtherOnly();"></a><span title="header=[Help: Principal Address (Other)] body=[You may use this field to include a suite number, floor number, etc.] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span><div class="error" id="PrincipalAddressOtherError"><br>Please check your format. Use only alphanumerics (A-Z, a-z, 0-9), dash (-), pound (#),<br>&, period (.), and space characters. Examples: <i>Suite #202</i><br></div><?php if ($_SESSION['MsgPrincipalAddressOther'] != null) { echo $_SESSION['MsgPrincipalAddressOther']; $_SESSION['MsgPrincipalAddressOther']=null; } ?></td>
	</tr>
	<tr>
	<td><label for="City">City</label></td>
	<td colspan="2"><a name="CityAnchor" class="plain"><input type="text" name="City" id="City" maxlength="40" size="40" value="<?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') echo $line['City']?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkCityOnly();"></a><span class="redsup">&nbsp;&nbsp;<b>*</b></span><div class="error" id="CityError"><br>Please enter a valid city. Use only letters (A-Z, a-z), dash (-), and space characters here.<br>Use initial capital (upper-case) letters. Examples: <i>Springfield</i> or <i>South Bend</i><br></div><?php if ($_SESSION['MsgCity'] != null) { echo $_SESSION['MsgCity']; $_SESSION['MsgCity']=null; } ?></td>
	</tr>
	<tr>
	<td><label for="State">State</label></td>
	<td colspan="2">
	<select name="State" id="State" size="1" <?php if ($line['Locale'] == 'Test Drive') echo 'disabled'; ?> onchange="hideAllErrors(); return checkStateOnly();">
	<?php
	$statesArray = array(array('&lt;&nbsp; Select a State &nbsp;&gt;',null), array('Alabama','AL'),	array('Alaska','AK'), array('Arizona','AZ'), array('Arkansas','AR'),	array('California','CA'), array('Colorado','CO'), array('Connecticut','CT'), array('Delaware','DE'), array('District of Columbia (D.C.)','D.C.'), array('Florida','FL'), array('Georgia','GA'), array('Hawaii','HI'), array('Idaho','ID'), array('Illinois','IL'), array('Indiana','IN'), array('Iowa','IA'), array('Kansas','KS'), array('Kentucky','KY'), array('Louisiana','LA'), array('Maine','ME'), array('Maryland','MD'), array('Massachusetts','MA'), array('Michigan','MI'), array('Minnesota','MN'), array('Mississippi','MI'), array('Missouri','MO'), array('Montana','MT'), array('Nebraska','NE'), array('Nevada','NV'), array('New Hampshire','NH'), array('New Jersey','NJ'), array('New Mexico','NM'), array('New York','NY'), array('North Carolina','NC'), array('North Dakota','ND'), array('Ohio','OH'), array('Oklahoma','OK'), array('Oregon','OR'), array('Pennsylvania','PA'), array('Rhode Island','RI'), array('South Carolina','SC'), array('South Dakota','SD'), array('Tennessee','TN'), array('Texas','TX'), array('Utah','UT'), array('Vermont','VT'), array('Virginia','VA'), array('Washington','WA'), array('Washington, D.C.','D.C.'), array('West Virginia','WV'), array('Wisconsin','WI'), array('Wyoming','WY'));
	for ($i=0; $i<53; $i++)
	{
	$optiontag = '<option value="'.$statesArray[$i][1].'" ';
	if ($line['State'] == $statesArray[$i][1]) $optiontag = $optiontag.'selected';
	$optiontag = $optiontag.'>'.$statesArray[$i][0]."</option>\n";
	echo $optiontag;
	}
	if ($line['State'] == 'US' && $line['Locale'] != 'Test Drive') echo '<option value="US" selected>US - Special Case</option>'; // Only when logged in for the special case of the 'Your Locale Here' mediator, include one extra option in the drop-down menu i.e. selection of a state called 'US'.
	if ($line['Locale'] == 'Test Drive') echo '<option value="US" selected>US</option>'; // Show this option only when a "test driver" is logged in.
	?>
	</select><span class="redsup" <?php if ($line['Locale'] == 'Test Drive') echo ' style="display: none;"'; ?>>&nbsp;&nbsp;<b>*</b></span><div class="error" id="StateError"><br>Please select a state from the drop-down menu<br></div>
	</td>
	</tr>
	<tr>
	<td><label for="">Zip</label></td>
	<td colspan="2"><a name="ZipAnchor" class="plain"><input type="text" name="Zip" id="Zip" maxlength="10" size="10" value="<?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') echo $line['Zip']?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkZipOnly();"></a><span class="redsup">&nbsp;&nbsp;<b>*</b></span><span title="header=[Help: Zip Code] body=[Enter either a five digit zip code (e.g. 94040) or a five-plus-four code separated by a hyphen (e.g. 9040-4321).] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span><div class="greytextsmall">Examples: 94040 or 94040-1738</div><div class="error" id="ZipError">Please enter a valid zip code. Use either a five-digit format or a five-plus-four format.<br></div><?php if ($_SESSION['MsgZip'] != null) { echo $_SESSION['MsgZip']; $_SESSION['MsgZip']=null; } ?></td>
	</tr>
	<tr>
	<td><label for="Tel1">Telephone</label></td>
	<td colspan="2"><a name="TelephoneAnchor" class="plain">
	  <input type="text" name="Tel1" id="Tel1" maxlength="3" size="3" value="<?php if (($line['Name'] != 'Your Name') && ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true')) echo substr($line['Telephone'],0,3);?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white';">
	</a>&nbsp;&nbsp;&nbsp;
	  <input type="text" name="Tel2" id="Tel2" maxlength="3" size="3" value="<?php if (($line['Name'] != 'Your Name') && ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true')) echo substr($line['Telephone'],4,3); ?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white';">&nbsp;&ndash;&nbsp;<input type="text" name="Tel3" id="Tel3" maxlength="4" size="4" value="<?php if (($line['Name'] != 'Your Name') && ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true')) echo substr($line['Telephone'],8,4); ?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkTelephoneOnly();"><span class="redsup">&nbsp;&nbsp;<b>*</b></span>
<label for="Ext">&nbsp;&nbsp;&nbsp;Ext.&nbsp;</label>
	<input type="text" name="Ext" id="Ext" maxlength="5" size="3" value="<?php if (($line['Name'] != 'Your Name') && ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true')) { if (strlen($line['Telephone']) > 12) echo substr($line['Telephone'],19); } ?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkTelephoneOnly();"><div class="error" id="TelephoneError"><br>Please enter a valid telephone number. Use only numbers (0-9). Leave the extension field blank if not applicable.<br></div><?php if ($_SESSION['MsgTelephone'] != null) { echo $_SESSION['MsgTelephone']; $_SESSION['MsgTelephone']=null; } ?></td>
	</tr>
	<tr>
	<td><label for="Fax1">Fax</label></td>
	<td colspan="2"><a name="FaxAnchor" class="plain"><input type="text" name="Fax1" id="Fax1" maxlength="3" size="3" value="<?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') echo substr($line['Fax'],0,3) ?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white';"></a>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="Fax2" id="Fax2" maxlength="3" size="3" value="<?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') echo substr($line['Fax'],4,3) ?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white';">&nbsp;&ndash;&nbsp;<input type="text" name="Fax3" id="Fax3" maxlength="4" size="4" value="<?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') echo substr($line['Fax'],8,4) ?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkFaxOnly();"><div class="error" id="FaxError"><br>Please enter a valid fax number (or leave this item blank). Use only numbers (0-9).<br></div><?php if ($_SESSION['MsgFax'] != null) { echo $_SESSION['MsgFax']; $_SESSION['MsgFax']=null; } ?></td>
	</tr>
	<tr>
	<td valign="top"><label for="Profile">Profile</label></td>
	<td colspan="2" valign="top"><a name="ProfileAnchor" class="plain"><textarea name="Profile" id="Profile" rows="10" cols="80" wrap="soft" style="overflow:auto; height: 180px; width: 540px;" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkProfileOnly();" onKeyUp="charCount('Profile','sBann1','{CHAR} chars'); toCount('Profile','sBann2','{CHAR} chars',900);"><?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') echo $line['Profile']; ?></textarea></a><span class="redsup" style="position:relative; bottom: 156px; <?php include('/home/paulme6/public_html/newresolutionmediation/scripts/browser_detection.php'); if ( browser_detection('browser') == 'saf' ) { echo 'left: 600px;'; } ?>
">&nbsp;&nbsp;<b>*</b></span><span title="header=[Help: Profile] body=[Please describe yourself as a mediator in a single paragraph. Mention your background, experience, approach, etc. Use the third-person i.e. use words like &lsquo;he&rsquo; or &lsquo;she&rsquo; and your name (e.g. Jane Doe) rather than &lsquo;I&rsquo; to describe yourself.<p>Be concise. The paragraph should be at least 300 characters (approx. 50 words) but no more than 900 characters (125 words).</p>] singleclickstop=[on] requireclick=[on]" style="position:relative; bottom: 156px; <?php  if (browser_detection('browser') == 'saf') echo 'left: 600px;'; else echo 'left: 10px;'; ?>"><img src="/images/QuestionMarkIcon.jpg"></span><div class="greytextsmall">Hints: Single paragraph. Use third-person (i.e. he/she instead of I). Minimum = 300 chars, maximum = 900 chars.<br>Character Count: [<span id="sBann1" class="greytextsmall" style="font-weight:bold;"><?=strlen($line['Profile']).' chars';?></span>]&nbsp;&nbsp;Remaining: [<span id="sBann2" class="greytextsmall" style="font-weight:bold;"><?php $RemainingChars = 900 - strlen($line['Profile']); echo $RemainingChars.' chars';?></span>]</div><div class="error" id="ProfileError1">Please remove any newline characters ('return' or 'enter'). To remove them, use the [delete] or [backspace] keys.<br>Also, please ensure your text is between 300 and 900 characters (approx. 50&ndash;125 words) in length.<br></div><div class="error" id="ProfileError2">Please describe yourself in the third person. Use words like &lsquo;he&rsquo; or &lsquo;she&rsquo; and your name (e.g. Jane Doe) rather than &lsquo;I&rsquo;.<br>Example of correct form: <i><u>Jane Doe</u> earned <u>her</u> degree from Princeton, where <u>she</u> ...</i><br> Incorrect: <i><u>I</u> earned <u>my</u> degree from Princeton, where <u>I</u> ...</i></div><?php if ($_SESSION['MsgProfile1'] != null) { echo $_SESSION['MsgProfile1']; $_SESSION['MsgProfile1']=null; }; if ($_SESSION['MsgProfile2'] != null) { echo $_SESSION['MsgProfile2']; $_SESSION['MsgProfile2']=null; }; ?></td>
	</tr>
	<tr>
	<td valign="top"><label for="MediatorPic">Photo Selection</label></td>
	<td colspan="2">
	<input type="hidden" name="MAX_FILE_SIZE" value="2000000"> <!-- Max file size is 2 MB -->
	<input name="MediatorPic" id="MediatorPic" type="file" size="39" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="submit" name="UploadFile" value="Select/Upload this Photo" onclick="return testFileType(this.form.MediatorPic.value, ['.jpg', '.jpeg', '.JPG', '.JPEG']);">
	<div class="greytextsmall">Hint: Select a .jpg portrait photo of height/width similar to  below (i.e. 100 units wide x 120 units high). Max file size = 2 MB</div></td> 
	</tr>
	<tr>
	<td valign="top"><label>Photo</label></td>
	<td>
	<?php if ($line['ImageFileName'] == null || ($line['Locale'] == 'Test Drive' && $first_time_test_driver == 'true')) echo '<img src="/images/NoPhotoSilhouette.jpg" width="100" height="120" border="0">'; else echo '<img src="../images/'.$line['ImageFileName'].'" width="100" height="120" border="0">' ?>
	</td>
	<td valign="top" style="padding-left: 7px; padding-top: 20px;">
	<div style="font-family:Geneva, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: bold; background-color:#CCCCCC; padding: 5px 3px 10px 7px;">You may need to &ldquo;refresh&rdquo; your screen to see the image you just uploaded. Refresh by pressing the [F5] key (Windows) or [Command+R] (Mac).<br><br>
	If your photo looks distorted, it&rsquo;s because the width and height need to be in the 
	ratio of 5&thinsp;:&thinsp;6. In other words, if your photo is 500 units wide, it should be about 600 units high. Similarly, if the width is 100 units, the height should be 120 units. The width/height doesn&rsquo;t have to be <i>exactly</i> 5/6, but it should be close.</div> 
	<span title="header=[Help: Photo] body=[To avoid distortion, your photo should be similar in shape (height vs width) to the image on the left. Every modern PC includes a basic image editor application that will enable you to create a suitable photo file. Save the photo as a .jpg file. The maximum file size is 2 MB,&nbsp;<br>but your file should be less than 500 kB for best results.<p>If you have difficulty creating the photo file, we&rsquo;ll be happy to create it&nbsp;<br>for you. Just send an email to help@newresolutionmediation.com and attach the photo you wish to use.</p>] singleclickstop=[on] requireclick=[on]" style="position:relative; bottom: 120px; left: 554px;"><img src="/images/QuestionMarkIcon.jpg"></span></td>
	</tr>
	<tr>
	<td><label for="DeletePhoto">Delete Photo?</label></td>
	<td colspan="2"><input type="checkbox" name="DeletePhoto" id="DeletePhoto" value="1"><span title="header=[Help: Delete Photo] body=[Check this box to delete the existing photo from your profile. If&nbsp;<br>the photo has been deleted, the web site will not display a picture next&nbsp;<br>to your name on the &lsquo;Our Mediators&rsquo; page. If you are happy with the existing photo, leave this box unchecked.<p>To replace the existing photo with a new photo, (i) leave the box unchecked, and (ii) browse and upload a new photo via the &lsquo;Photo&nbsp;<br>Selection&rsquo; area of the form.</p>] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span></td>
	</tr>
	<tr>
	<td colspan="5"><HR align="left" size="1px" noshade color="#FF9900" style="margin-top: 12px;"><h2 style="margin-left:20px; margin-top:6px; margin-bottom:10px;">PROFESSIONAL FEES & SERVICES</h2></td>
	</tr>
	<tr>
	<td rowspan="16">&nbsp;</td>
	<td valign="top"><label for="ShowHrlyRate">Publish Hourly Rate?</label></td>
	<td colspan="2"><input type="checkbox" name="ShowHrlyRate" id="ShowHrlyRate" value="true" <?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') { if (substr($line['HourlyRate'],0,4) == 'true') echo 'checked '; };?> onclick="if (document.getElementById('ShowHrlyRate').checked) { document.getElementById('HrlyRate').disabled = false; document.getElementById('HrlyRate').focus(); } else { document.getElementById('HrlyRate').disabled = true; };"><label for="HrlyRate">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hourly Rate $&nbsp;</label><a name="HrlyRateAnchor" class="plain"><input type="text" name="HrlyRate" id="HrlyRate" maxlength="3" size="3" value="<?php $myArray = explode(',',$line['HourlyRate']); if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') { if ($myArray[1] != '\'\'') echo $myArray[1]; }; ?>"  onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkHrlyRateOnly();"  <?php if ((substr($line['HourlyRate'],0,5) == 'false') || ($line['Locale'] == 'Test Drive' && $first_time_test_driver == 'true')) echo ' disabled';?>></a><span title="header=[Help: Hourly Rate] body=[Check (uncheck) the box to show (hide) your hourly rate on the Fees page of the web site. When the check-box is checked, you must also enter a number for your $ hourly rate. Specify your total hourly rate.&nbsp;<br>(If the parties are sharing the fee equally, they will each pay one-half&nbsp;<br>of this total.)] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span><div class="error" id="HrlyRateError"><br>To publish your hourly rate, enter a $ amount. Use numbers (0-9) only. Alternatively, uncheck the check-box.<br></div><?php if ($_SESSION['MsgHrlyRate'] != null) { echo $_SESSION['MsgHrlyRate']; $_SESSION['MsgHrlyRate']=null; } ?></td>
	</tr>
	<tr>
	<td><label for="AdminCharge">Case Admin Charge?</label></td>
	<td colspan="2"><input type="radio" name="AdminCharge" id="AdminCharge" value="0" onclick="document.getElementById('ShowAdminCharge').checked = false; document.getElementById('ShowAdminCharge').disabled = true; document.getElementById('AdminChgAmt').disabled = true;" <?php if ($line['AdminCharge'] == 0) echo 'checked' ?>><label for="AdminCharge">No&nbsp;&nbsp;&nbsp;</label><input type="radio" name="AdminCharge" id="AdminCharge" value="1" onclick="document.getElementById('ShowAdminCharge').disabled = false;" <?php if ($line['AdminCharge'] == 1) echo 'checked' ?>><label for="AdminCharge">Yes</label><span title="header=[Help: Case Administration Charge] body=[A Case Administration Charge is usually a flat fee charged to administer a case (i.e. schedule appointments, write up notes, etc.). Select &lsquo;Yes&rsquo; if you sometimes (or always) charge for case administration.] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span></td>
	</tr>
	<tr>
	<td valign="top"><label for="ShowAdminCharge">Publish Admin Charge?</label></td>
	<td colspan="2"><input type="checkbox" name="ShowAdminCharge" id="ShowAdminCharge" value="true" <?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') { if (substr($line['AdminChargeDetails'],0,4) == 'true') echo 'checked '; };?> onclick="if (document.getElementById('ShowAdminCharge').checked) { document.getElementById('AdminChgAmt').disabled = false; document.getElementById('AdminChgAmt').focus(); } else { document.getElementById('AdminChgAmt').disabled = true; };"><label for="AdminChgAmt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Flat Charge $&nbsp;</label><a name="AdminChargeDetailsAnchor" class="plain"><input type="text" name="AdminChgAmt" id="AdminChgAmt" maxlength="3" size="3" value="<?php $myArray = explode(',',$line['AdminChargeDetails']); if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') { if ($myArray[1] != '\'\'') echo $myArray[1]; }; ?>"  onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkAdminChgAmtOnly();"  <?php if ((substr($line['AdminChargeDetails'],0,5) == 'false') || ($line['Locale'] == 'Test Drive' && $first_time_test_driver == 'true')) echo ' disabled';?>></a><span title="header=[Help: Admin Charge Details] body=[If you wish, you can publish the amount of your Case Administration Charge on the Fees page of the site. Check (uncheck) the box to show (hide) your Admin Charge. When the box is checked, you must enter the amount of the charge as a one-time flat fee.] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span><div class="greytextsmall">Hint: Check the box and set the Case Admin Charge radio button to &lsquo;Yes&rsquo; in order to publish the amount of your admin charge.</div><div class="error" id="AdminChgAmtError">To publish your Case Admin Charge, enter a $ amount. Use numbers (0-9) only. Alternatively, uncheck the check-box.<br></div><?php if ($_SESSION['MsgAdminChgAmt'] != null) { echo $_SESSION['MsgAdminChgAmt']; $_SESSION['MsgAdminChgAmt']=null; } ?></td>
	</tr>
	</tr>
	<tr>
	<td valign="top"><label for="ShowLocChg">Location Charge?</label></td>
	<td colspan="2"><input type="checkbox" name="ShowLocChg" id="ShowLocChg" value="true" <?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') { if (substr($line['LocationCharge'],0,4) == 'true') echo 'checked '; }; ?> onclick="if (document.getElementById('ShowLocChg').checked) { document.getElementById('LocChgFrom').disabled = false; document.getElementById('LocChgTo').disabled = false; document.getElementById('LocChgFrom').focus(); } else { document.getElementById('LocChgFrom').disabled = true; document.getElementById('LocChgTo').disabled = true; };"><label for="LocChgFrom">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;From $&nbsp;</label><a name="LocationChargeAnchor" class="plain"><input type="text" name="LocChgFrom" id="LocChgFrom" maxlength="3" size="3" value="<?php $myArray = explode(',',$line['LocationCharge']); if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') { echo $myArray[1]; }; ?>"  onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkLocationChargeOnly();"  <?php if ((substr($line['LocationCharge'],0,5) == 'false') || ($line['Locale'] == 'Test Drive' && $first_time_test_driver == 'true')) echo ' disabled';?>></a><label for="LocChgTo">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To $&nbsp;</label><input type="text" name="LocChgTo" id="LocChgTo" maxlength="3" size="3" value="<?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') { echo $myArray[2]; }; ?>"  onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkLocationChargeOnly();" <?php if ((substr($line['LocationCharge'],0,5) == 'false') || ($line['Locale'] == 'Test Drive' && $first_time_test_driver == 'true')) echo 'disabled';?>><span title="header=[Help: Location Charge] body=[A Location Charge is a flat fee levied for use of a particular office location, separate from any fee for the actual mediation session. Check the check-box if you wish to charge a Location Charge.<br><br>If the box is checked, use the &lsquo;From&rsquo; and &lsquo;To&rsquo; fields to specify the location charge range (i.e. your minimum and maximum location charge). You may leave the &lsquo;To&rsquo; field blank if you charge a single flat&nbsp;<br>Location Charge for all locations.] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span><div class="greytextsmall">Hint: Leave the &lsquo;To&rsquo; field blank if your location charge is a flat fee for every location rather than a range of $ values.</div><div class="error" id="LocChgError">Please check these values. Use only numbers (0-9).<br></div><?php if ($_SESSION['MsgLocationCharge'] != null) { echo $_SESSION['MsgLocationCharge']; $_SESSION['MsgLocationCharge']=null; } ?></td>
	</tr>
	<tr>
	<td valign="top"><label for="OfferPkgs">Offer Packages?</label></td>
	<td valign="top"><input type="checkbox" name="OfferPkgs" id="OfferPkgs" value="true" <?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') { if (substr($line['Packages'],0,4) == 'true') echo 'checked '; };?> onclick="if (document.getElementById('OfferPkgs').checked) { document.getElementById('NumSessPkg1').disabled = false; document.getElementById('PricePkg1').disabled = false; document.getElementById('NumSessPkg2').disabled = false; document.getElementById('PricePkg2').disabled = false; document.getElementById('NumSessPkg3').disabled = false; document.getElementById('PricePkg3').disabled = false; document.getElementById('SessLengthHrs').disabled = false; document.getElementById('SessLengthMins').disabled = false; document.getElementById('NumSessPkg1').focus(); } else { document.getElementById('NumSessPkg1').disabled = true; document.getElementById('PricePkg1').disabled = true; document.getElementById('NumSessPkg2').disabled = true; document.getElementById('PricePkg2').disabled = true; document.getElementById('NumSessPkg3').disabled = true; document.getElementById('PricePkg3').disabled = true; document.getElementById('SessLengthHrs').disabled = true; document.getElementById('SessLengthMins').disabled = true;};"></td>
	<td><label for="NumSessPkg1">
Number of Sessions in Package #1&nbsp;&nbsp;</label><a name="PackagesAnchor" class="plain"><input type="text" name="NumSessPkg1" id="NumSessPkg1" maxlength="4" size="4" value="<?php $myArray = explode(',',$line['Packages']); if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') echo $myArray[1]; ?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkPackagesOnly();" <?php if ((substr($line['Packages'],0,5) == 'false') || ($line['Locale'] == 'Test Drive' && $first_time_test_driver == 'true')) echo 'disabled';?>></a><label for="PricePkg1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Price of Package #1&nbsp;&nbsp;$&nbsp;&nbsp;</label><input type="text" name="PricePkg1" id="PricePkg1" maxlength="4" size="4" value="<?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') echo $myArray[2]; ?>" <?php if ((substr($line['Packages'],0,5) == 'false') || ($line['Locale'] == 'Test Drive' && $first_time_test_driver == 'true')) echo 'disabled';?> onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkPackagesOnly();"><br>
<label for="NumSessPkg2">Number of Sessions in Package #2&nbsp;&nbsp;</label><input type="text" name="NumSessPkg2" id="NumSessPkg2" maxlength="4" size="4" value="<?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') echo $myArray[3]; ?>" <?php if ((substr($line['Packages'],0,5) == 'false') || ($line['Locale'] == 'Test Drive' && $first_time_test_driver == 'true')) echo 'disabled';?> onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkPackagesOnly();"><label for="PricePkg2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Price of Package #2&nbsp;&nbsp;$&nbsp;&nbsp;</label><input type="text" name="PricePkg2" id="PricePkg2" maxlength="4" size="4" value="<?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') echo $myArray[4]; ?>" <?php if ((substr($line['Packages'],0,5) == 'false') || ($line['Locale'] == 'Test Drive' && $first_time_test_driver == 'true')) echo 'disabled';?> onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkPackagesOnly();"><br>
<label for="NumSessPkg3">Number of Sessions in Package #3&nbsp;&nbsp;</label><input type="text" name="NumSessPkg3" id="NumSessPkg3" maxlength="4" size="4" value="<?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') echo $myArray[5]; ?>" <?php if ((substr($line['Packages'],0,5) == 'false') || ($line['Locale'] == 'Test Drive' && $first_time_test_driver == 'true')) echo 'disabled';?> onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkPackagesOnly();"><label for="PricePkg3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Price of Package #3&nbsp;&nbsp;$&nbsp;&nbsp;</label><input type="text" name="PricePkg3" id="PricePkg3" maxlength="4" size="4" value="<?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') echo $myArray[6]; ?>" <?php if ((substr($line['Packages'],0,5) == 'false') || ($line['Locale'] == 'Test Drive' && $first_time_test_driver == 'true')) echo 'disabled';?> onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkPackagesOnly();"><br>
<label for="SessLengthHrs">Length of Each Session:&nbsp;&nbsp;</label>
<input type="text" name="SessLengthHrs" id="SessLengthHrs" maxlength="1" size="1" value="<?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') echo $myArray[7]; ?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkPackagesOnly();" <?php if ((substr($line['Packages'],0,5) == 'false') || ($line['Locale'] == 'Test Drive' && $first_time_test_driver == 'true')) echo 'disabled';?>><label for="SessLengthMins">&nbsp;&nbsp;hours&nbsp;&nbsp;&nbsp;&nbsp;</label>
<input type="text" name="SessLengthMins" id="SessLengthMins" maxlength="2" size="2" value="<?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') echo $myArray[8]; ?>"  <?php if ((substr($line['Packages'],0,5) == 'false') || ($line['Locale'] == 'Test Drive' && $first_time_test_driver == 'true')) echo 'disabled';?> onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkPackagesOnly();">
<label>&nbsp;&nbsp;minutes</label>
<span title="header=[Help: Packages] body=[Check the check-box if you wish to offer packages. These are prepaid packages of mediation sessions &mdash; for example, a package of three 2-hour mediation sessions.<p>Parties may prefer to purchase a packages of mediation sessions rather than pay for time in mediation according to the mediator&rsquo;s hourly rate. Usually, the price of a package is less than the same amount of&nbsp;<br>time charged at the hourly rate.</p><p>You may offer up to three packages. Specify the number of sessions and the price for each package in the spaces provided. Also, be sure to specify the length of each session (e.g. &ldquo;2 hours&rdquo; or &ldquo;2 hours 20 mins&rdquo;).</p>] singleclickstop=[on] requireclick=[on]" style="position:relative; bottom: 70px; left: 156px;"><img src="/images/QuestionMarkIcon.jpg"></span>
<div class="error" id="OfferPkgsError"><br>Please check your values. Use only numbers (0-9). If you offer packages, you must provide a<br>duration (hours and minutes) for the length of each session. Example: <i>hours = 2; minutes = 30</i><br></div><?php if ($_SESSION['MsgPackages'] != null) { echo $_SESSION['MsgPackages']; $_SESSION['MsgPackages']=null; } ?></td>
	</tr>
	<tr>
	<td><label for="SlidingScale">Sliding Scale?</label></td>
	<td colspan="2"><input type="radio" name="SlidingScale" id="SlidingScale" value="0" <?php if ($line['SlidingScale'] == 0 || ($line['Locale'] == 'Test Drive' && $first_time_test_driver == 'true')) echo 'checked' ?>><label for="SlidingScale">No&nbsp;&nbsp;&nbsp;</label><input type="radio" name="SlidingScale" id="SlidingScale" value="1" <?php if ($line['SlidingScale'] == 1 && ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true')) echo 'checked' ?>><label for="SlidingScale">Yes</label><span title="header=[Help: Sliding Scale] body=[Some mediators wish to serve lower-income parties who may be unable to pay the mediator&raquo;s standard rate. Click the &lsquo;Yes&rsquo; button if you wish to charge parties different rates according to their ability to pay.] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span></td>
	</tr>
	<tr>
	<td valign="top"><label for="Increment" style="position:relative; top: 10px;">Fee Interval</label></td>
	<td colspan="2"><a name="IncrementAnchor" class="plain"><input type="text" name="Increment" id="Increment" maxlength="3" size="2" value="<?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') echo $line['Increment']; ?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkIncrementOnly();"></a><label for="Increment">&nbsp;&nbsp;minutes</label><span class="redsup">&nbsp;&nbsp;<b>*</b></span><span title="header=[Help: Fee Interval] body=[The Fee Interval relates to mediation charged at your hourly rate. It is the smallest block of time for which you charge. Suppose, for example, a session lasts 1 hours and 35 minutes, and the hourly rate is $200 per hour. Would you round up to the next hour and charge for 2 hours of&nbsp;<br>time (in which case the interval would be 60 minutes and the fee would be 2 x $200 = $400)? Or would you round up to the next 15 minutes and charge for 1 hour 45 minutes of time (in which case the interval would be 15 minutes and the fee would be 1 &frac34; x $200 = $350)?<p>You may choose any interval from 0 to 60 minutes. Mediators typically choose intervals of 5, 6, 10, 15, or 30 minutes. Clients prefer a smaller interval because it is less likely to require &ldquo;overpayment&rdquo; for time.</p>] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span><div class="greytextsmall">Hint: Enter &lsquo;6&rsquo; if you charge in 6-minute blocks of time; enter &lsquo;15&rsquo; if you charge in 15-minute blocks of time; etc.</div><div class="error" id="IncrementError">Please check that your value is between 0 and 60 minutes. Use only numbers (0-9). Examples: <i>5, 6, 10, 15, 20,</i> or <i>30</i><br></div><?php if ($_SESSION['MsgIncrement'] != null) { echo $_SESSION['MsgIncrement']; $_SESSION['MsgIncrement']=null; } ?></td>
	</tr>
	<tr>
	<td><label for="OfferFreeTelConsltn">Free Telephone Consultations?</label></td>
	<td colspan="2"><input type="radio" name="OfferFreeTelConsltn" id="OfferFreeTelConsltn" value="false" <?php $myArray = explode(',',$line['ConsultationPolicy']); if ($myArray[0] == 'false' || ($line['Locale'] == 'Test Drive' && $first_time_test_driver == 'true')) echo 'checked' ?>><label for="OfferFreeTelConsltn">No&nbsp;&nbsp;&nbsp;</label><input type="radio" name="OfferFreeTelConsltn" id="OfferFreeTelConsltn" value="true" <?php if ($myArray[0] == 'true' && ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true')) echo 'checked' ?>><label for="OfferFreeTelConsltn">Yes</label></td>
	</tr>
	<tr>
	<td><label for="OfferFreeInPersConsltn">Free In-Person Consultations?</label></td>
	<td colspan="2"><input type="radio" name="OfferFreeInPersConsltn" id="OfferFreeInPersConsltn" value="false" <?php if ($myArray[1] == 'false' || ($line['Locale'] == 'Test Drive' && $first_time_test_driver == 'true')) echo 'checked' ?>><label for=OfferFreeInPersConsltn"">No&nbsp;&nbsp;&nbsp;</label><input type="radio" name="OfferFreeInPersConsltn" id="OfferFreeInPersConsltn" value="true" <?php if ($myArray[1] == 'true' && ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true')) echo 'checked' ?>><label for="OfferFreeInPersConsltn">Yes</label><span title="header=[Help: Free In-Person Consultations] body=[If you offer free in-person consultations from at least one of your locations, select the &lsquo;Yes&rsquo; button.] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span></td>
	</tr>
	<tr>
	<td><label for="LocChgForInPersConsltn">Location Charge for In-Person Consultations?</label></td>
	<td colspan="2"><input type="radio" name="LocChgForInPersConsltn" id="LocChgForInPersConsltn" value="false" <?php if ($myArray[2] == 'false' || ($line['Locale'] == 'Test Drive' && $first_time_test_driver == 'true')) echo 'checked' ?>><label for="LocChgForInPersConsltn">No&nbsp;&nbsp;&nbsp;</label><input type="radio" name="LocChgForInPersConsltn" id="LocChgForInPersConsltn" value="true" <?php if ($myArray[2] == 'true' && ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true')) echo 'checked' ?>><label for="LocChgForInPersConsltn">Yes</label><span title="header=[Help: Location Charge for In-Person Consultations] body=[If you charge a location charge for consultations at any of your locations, select the &lsquo;Yes&rsquo; button.] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span></td>
	</tr>
	<tr>
	<td><label>Credit for Paid Consultations?</label></td>
	<td colspan="2"><input type="radio" name="OfferConsultFeeCredit" id="OfferConsultFeeCredit" value="false" <?php if ($myArray[3] == 'false' || ($line['Locale'] == 'Test Drive' && $first_time_test_driver == 'true')) echo 'checked'; ?> onClick="document.getElementById('PercentConsltnFeeCredit').disabled = true;"><label for="OfferConsultFeeCredit">No&nbsp;&nbsp;&nbsp;</label><input type="radio" name="OfferConsultFeeCredit" id="OfferConsultFeeCredit" value="true" <?php if ($myArray[3] == 'true' && ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true')) echo 'checked ' ?> onclick="document.getElementById('PercentConsltnFeeCredit').disabled = false; document.getElementById('PercentConsltnFeeCredit').focus();"><label for="OfferConsultFeeCredit">Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Consultation Fee Credit&nbsp;&nbsp;</label><a name="PercentConsltnFeeCreditAnchor" class="plain"><input type="text" name="PercentConsltnFeeCredit" id="PercentConsltnFeeCredit" maxlength="4" size="3" value="<?php if ($myArray[4] != '""' && ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true')) echo $myArray[4]; ?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkPercentConsltnFeeCreditOnly();" <?php if ($myArray[3] == 'false') echo 'disabled'; ?>></a><label>&nbsp;&nbsp;%</label><span title="header=[Help: Credit for Paid Consultations] body=[If parties pay for a consultation and then decide to hire you as their mediator, you may want to offer them a full or partial credit of the consultation fee.<p>If you wish to offer a consultation fee credit, select the &lsquo;Yes&rsquo; button and enter a percentage (0 to 100) in the adjacent field. For example, if the consultation fee is $150 and you offer a 50% credit, the parties will&nbsp;<br>receive a $75 credit towards any fees for mediation.</p>] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span><div class="error" id="PercentConsltnFeeCreditError"><br>Please provide a percentage using values from 1 to 100. Uncheck the check-box for zero percent. Examples: <i>50</i> or <i>100.</i><br></div><?php if ($_SESSION['MsgPercentConsltnFeeCredit'] != null) { echo $_SESSION['MsgPercentConsltnFeeCredit']; $_SESSION['MsgPercentConsltnFeeCredit']=null; } ?></td>
	</tr>
	<tr>
	<td><label for="">Cancellation Policy</label></td>
	<td colspan="2">
	<a name="CancellationPolicyAnchor" class="plain"><input type="text" name="CancNumber" id="CancNumber" maxlength="2" size="2" <?php $myArray = explode(',', $line['CancellationPolicy']); if ($myArray[0] != 'null' && ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true')) echo 'value="'.str_replace('"','',$myArray[0]).'"'; ?> onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; if (this.value=='') document.getElementById('LateCancFee').disabled = true; hideAllErrors(); return checkCancellationPolicyOnly();"></a><span class="redsup">&nbsp;&nbsp;<b>*</b></span>
&nbsp;&nbsp;<select name="CancUnits" id="CancUnits" size="1" onChange="if (!this.selectedIndex == 0) { document.getElementById('LateCancFee').disabled = false; document.getElementById('LateCancFee').focus(); } else document.getElementById('LateCancFee').disabled = true;">
			<option value="" <?php if (substr($myArray[1],1,4) == "null" || substr($myArray[1],1,4) == "" || ($line['Locale'] == 'Test Drive' && $first_time_test_driver == 'true')) echo 'selected'; ?>>&lt;&nbsp;Select Units of Time&nbsp;&gt;</option>
			<option value="hours" <?php if ((substr($myArray[1],1,5) == "hours") && ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true')) echo ' selected'; ?>>Hours</option>
			<option value="days" <?php if ((substr($myArray[1],1,4) == "days") && ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true')) echo ' selected'; ?>>Days</option>
			<option value="business days" <?php if ((substr($myArray[1],1,13) == "business days") && ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true')) echo ' selected'; ?>>Business days</option>
			<option value="week(s)" <?php if ((substr($myArray[1],1,7) == "week(s)") && ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true')) echo ' selected'; ?>>Week(s)</option>
			</select><span class="redsup">&nbsp;&nbsp;<b>*</b></span>
<label for="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Late Cancellation Fee $&nbsp;</label>
<input type="text" name="LateCancFee" id="LateCancFee" maxlength="4" size="3" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkCancellationPolicyOnly();" <?php if ($myArray[1] == "\x22\x22" || $myArray[0] == '' || $myArray[0] == 'null' || ($line['Locale'] == 'Test Drive' && $first_time_test_driver == 'true')) echo 'disabled'; elseif ($myArray[2] != 'null' && ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true')) echo 'value='.$myArray[2]; ?>> <!-- Note that \x22 works as an escape for ", whereas \" doesn't work. -->
	<span class="redsup">&nbsp;&nbsp;<b>*</b></span><span title="header=[Help: Cancellation Policy] body=[Use the text field and drop-down menu to specify the number of hours, days, etc. for which a cancellation is deemed late (example: 48 hours or 3 days). Then use the other text field to specify a $ fee for a late cancellation.] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span><div class="error" id="CancellationPolicyError"><br>Make a selection from the drop-down menu and use only numbers in the two text fields to indicate the period (number of<br>hours, days, etc.) and fee for late cancellations.<br></div><?php if ($_SESSION['MsgCancPolicy'] != null) { echo $_SESSION['MsgCancPolicy']; $_SESSION['MsgCancPolicy']=null; } ?></td>
	</tr>
	<tr>
	<td><label for="">Telephone Mediations?</label></td>
	<td colspan="2"><input type="checkbox" name="TelephoneMediations" value="1" <?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') { if ($line['TelephoneMediations'] == 1) echo 'checked'; }; ?>><span title="header=[Help: Telephone Mediations] body=[Some mediators conduct telephone mediations via three-way calls or teleconference when one or both parties are remote (e.g. out of state). Check this box if you wish to offer telephone mediations.] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span></td>
	</tr>
	<tr>
	<td><label for="">Accept Credit Cards?</label></td>
	<td colspan="2">
	<input type="checkbox" name="AcceptCC" id="AcceptCC" value="true" onclick="if (!this.checked) { document.getElementById('LevyFee').disabled = true; document.getElementById('CCFeePercent').disabled = true; }; if (this.checked) { document.getElementById('LevyFee').disabled = false; if (document.getElementById('LevyFee').checked) document.getElementById('CCFeePercent').disabled = false; else document.getElementById('CCFeePercent').disabled = true; }" <?php $myArray = explode(',', $line['CardPolicy']); if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') { if ($myArray[0] == 'true') echo ' checked'; }; ?>><label for="">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Levy Convenience Charge?&nbsp;&nbsp;</label>
	<input type="checkbox" name="LevyFee" id="LevyFee" value="true" onclick="if (!this.checked) document.getElementById('CCFeePercent').disabled = true; else if (document.getElementById('AcceptCC').checked) { document.getElementById('CCFeePercent').disabled = false; document.getElementById('CCFeePercent').focus(); }" <?php if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') { if ($myArray[1] == 'true') echo 'checked'; };?>>
<label for="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;% Convenience Charge?&nbsp;&nbsp;</label>
	<a name="CCFeePercentAnchor" class="plain"><input type="text" name="CCFeePercent" id="CCFeePercent" maxlength="5" size="3" <?php if (($myArray[0] == 'false' || $myArray[1] == 'false') || ($line['Locale'] == 'Test Drive' && $first_time_test_driver == 'true')) echo 'disabled'; if ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true') echo ' value="'.trim($myArray[2], "\x22").'"' ?> onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkCreditCardPolicyOnly();"></a><label>&nbsp;&nbsp;%</label><span title="header=[Help: Credit Cards] body=[Check the first check-box if you wish to accept credit cards.<p>Next consider whether you wish to also levy a so-called convenience charge on credit card payments. A conveninece charge (typically 1&ndash;3%) helps defray your cost of processing a credit card payment&nbsp;<br>(typically 3%).</p><p>If you wish to levy a convenience charge, check the second check-box and enter the percentage charge in the adjacent text field.</p>] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span><div class="error" id="CCFeePercentError"><br>Please check this value. Use only numbers and a decimal point (.) in this field. Examples: <i>2</i> or <i>2.75</i><br></div><?php if ($_SESSION['MsgCCFeePercent'] != null) { echo $_SESSION['MsgCCFeePercent']; $_SESSION['MsgCCFeePercent']=null; } ?></td>
	</tr>
	<tr>
	<td><label for="ServiceLevel">Service Level?</label></td>
	<td colspan="2"><input type="radio" name="ServiceLevel" id="ServiceLevel" value="0" <?php if ($line['ServiceLevel'] == 0 || ($line['Locale'] == 'Test Drive' && $first_time_test_driver == 'true')) echo 'checked' ?>><label for="ServiceLevel">	Self-Help &amp; Referrals&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
	  <input type="radio" name="ServiceLevel" id="ServiceLevel" value="1" <?php if ($line['ServiceLevel'] == 1 && ($line['Locale'] != 'Test Drive' || $first_time_test_driver != 'true')) echo 'checked' ?>><label for="ServiceLevel">
	Full Service (incl. preparation/filing of court forms)</label><span title="header=[Help: Service Level] body=[Check the &lsquo;Full Service&rsquo; button if you prepare and file the parties&rsquo; court&nbsp;<br>forms(attorneys and paralegals only). Check the &lsquo;Self-Help & Referrals&rsquo; button if you provide self-help resources or referrals to third-parties for those additional services.] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span></td>
	</tr>
	<tr>
	<td><label for="Suspend">Suspend Profile?</label></td>
	<!-- Since the Suspend check-box is disabled for a 'Test Drive' locale, I need to ensure it is uncheked while it's disabled for test drivers rather than have its checked/unchecked status read from the database as for non-test-drivers. Otherwise, if a test driver clicked the 'Save For Later' button with the check-box checked, that would result in the check box showing as both disabled and checked (i.e. Suspended) the next time a test driver loaded the profile. --><td colspan="2"><input type="checkbox" name="Suspend" id="Suspend" value="1" <?php if ($line['Locale'] == 'Test Drive') echo ' disabled'; else if ($line['Suspend'] == 1) echo 'checked'; ?>><span title="header=[Help: Suspend Profile] body=[Check this box to prevent your listing from appearing on the New Resolution web site. (For example, you might want to check the box<br>if you are on vacation and unable to respond to inquiries.) You may&nbsp;<br>log into your profile and uncheck the box at any time.] singleclickstop=[on] requireclick=[on]" style="position:relative; top: 4px;"><img src="/images/QuestionMarkIcon.jpg"></span></td>
	</tr>
<?php if ($line['Locale'] != 'Test Drive'): // Don't bother to show mediator's personal contact details if it's a test driver. Overwhelming/excess information. ?>
	<tr>
	<td colspan="5"><HR align="left" size="1px" noshade color="#FF9900" style="margin-top: 12px;"><h2 style="margin-left:20px; margin-top:6px; margin-bottom:10px;">MEDIATOR&rsquo;S PERSONAL CONTACT INFORMATION</h2><div class="greytext" style="margin-left: 20px; margin-top:-6px; margin-bottom:16px;">This information will not appear on the New Resolution web site. We will use it to contact you regarding the status of your account.</div></td>
	</tr>
	<tr>
	<td rowspan="6">&nbsp;</td>
	<td valign="top"><label for="StreetPersonal">Street Address</label></td>
	<td colspan="2"><a name="StreetPersonalAnchor" class="plain"><input type="text" name="StreetPersonal" id="StreetPersonal" maxlength="100" size="64" value="<?php if ($line['Locale'] != 'Test Drive') echo $line['StreetPersonal'];?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); hideAllPersonalErrors(); return checkStreetPersonalOnly();" <?php if ($line['Locale'] == 'Test Drive') echo 'disabled'; ?>></a><div class="error" id="StreetPersonalError"><br>Please enter a valid street address. Use only alphanumerics (A-Z, a-z, 0-9), dash (-), pound (#),<br>period (.), and space characters. Examples: <i>925 N. Washington Ave.</i> or </i><i>8708 Oak Street, #3</i><br></div><?php if ($_SESSION['MsgStreetPersonal'] != null) { echo $_SESSION['MsgStreetPersonal']; $_SESSION['MsgStreetPersonal']=null; } ?></td>
	</tr>
	<tr>
	<td><label for="CityPersonal">City</label></td>
	<td colspan="2"><a name="CityPersonalAnchor" class="plain"><input type="text" name="CityPersonal" id="CityPersonal" maxlength="40" size="40" value="<?php if ($line['Locale'] != 'Test Drive') echo $line['CityPersonal'];?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); hideAllPersonalErrors(); return checkCityPersonalOnly();" <?php if ($line['Locale'] == 'Test Drive') echo 'disabled'; ?>></a><div class="error" id="CityPersonalError"><br>Please enter a valid city. Use only letters (A-Z, a-z), dash (-), and space characters here.<br>Examples: <i>Springfield</i> or <i>South Bend</i><br></div><?php if ($_SESSION['MsgCityPersonal'] != null) { echo $_SESSION['MsgCityPersonal']; $_SESSION['MsgCityPersonal']=null; } ?></td>
	</tr>
	<tr>
	<td><label for="StatePersonal">State</label></td>
	<td colspan="2">
	<select name="StatePersonal" id="StatePersonal" size="1" <?php if ($line['Locale'] == 'Test Drive') echo 'disabled'; ?> onchange="hideAllErrors(); hideAllPersonalErrors(); return checkStatePersonalOnly();">
	<?php
	$statesArray = array(array('&lt;&nbsp; Select a State &nbsp;&gt;',null), array('Alabama','AL'),	array('Alaska','AK'), array('Arizona','AZ'), array('Arkansas','AR'),	array('California','CA'), array('Colorado','CO'), array('Connecticut','CT'), array('Delaware','DE'), array('District of Columbia (D.C.)','D.C.'), array('Florida','FL'), array('Georgia','GA'), array('Hawaii','HI'), array('Idaho','ID'), array('Illinois','IL'), array('Indiana','IN'), array('Iowa','IA'), array('Kansas','KS'), array('Kentucky','KY'), array('Louisiana','LA'), array('Maine','ME'), array('Maryland','MD'), array('Massachusetts','MA'), array('Michigan','MI'), array('Minnesota','MN'), array('Mississippi','MI'), array('Missouri','MO'), array('Montana','MT'), array('Nebraska','NE'), array('Nevada','NV'), array('New Hampshire','NH'), array('New Jersey','NJ'), array('New Mexico','NM'), array('New York','NY'), array('North Carolina','NC'), array('North Dakota','ND'), array('Ohio','OH'), array('Oklahoma','OK'), array('Oregon','OR'), array('Pennsylvania','PA'), array('Rhode Island','RI'), array('South Carolina','SC'), array('South Dakota','SD'), array('Tennessee','TN'), array('Texas','TX'), array('Utah','UT'), array('Vermont','VT'), array('Virginia','VA'), array('Washington','WA'), array('Washington, D.C.','D.C.'), array('West Virginia','WV'), array('Wisconsin','WI'), array('Wyoming','WY'));
	for ($i=0; $i<53; $i++)
	{
	$optiontag = '<option value="'.$statesArray[$i][1].'" ';
	if ($line['State'] == $statesArray[$i][1]) $optiontag = $optiontag.'selected';
	$optiontag = $optiontag.'>'.$statesArray[$i][0]."</option>\n";
	echo $optiontag;
	}
	if ($line['State'] == 'US' && $line['Locale'] != 'Test Drive') echo '<option value="US" selected>US - Special Case</option>'; // Only when logged in for the special case of the 'Your Locale Here' mediator, include one extra option in the drop-down menu i.e. selection of a state called 'US'.
	if ($line['Locale'] == 'Test Drive') echo '<option value="US" selected>US</option>'; // Show this option only when a "test driver" is logged in.
	?>
	</select><div class="error" id="StatePersonalError"><br>Please select a state from the drop-down menu<br></div>
	</td>
	</tr>
	<tr>
	<td><label for="">Zip</label></td>
	<td colspan="2"><a name="ZipPersonalAnchor" class="plain"><input type="text" name="ZipPersonal" id="ZipPersonal" maxlength="10" size="10" value="<?php if ($line['Locale'] != 'Test Drive') echo $line['ZipPersonal'];?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); hideAllPersonalErrors(); return checkZipPersonalOnly();" <?php if ($line['Locale'] == 'Test Drive') echo 'disabled'; ?>></a><div class="greytextsmall">Examples: 94040 or 94040-1738</div><div class="error" id="ZipPersonalError">Please enter a valid zip code. Use either a five-digit format or a five-plus-four format.<br></div><?php if ($_SESSION['MsgZipPersonal'] != null) { echo $_SESSION['ZipPersonal']; $_SESSION['ZipPersonal']=null; } ?></td>
	</tr>
	<tr>
	<td><label for="Tel1Personal">Telephone</label></td>
	<td colspan="2"><a name="TelephonePersonalAnchor" class="plain"><input type="text" name="Tel1Personal" id="Tel1Personal" maxlength="3" size="3" value="<?php if ($line['Locale'] != 'Test Drive') echo substr($line['TelephonePersonal'],0,3); ?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white';" <?php if ($line['Locale'] == 'Test Drive') echo 'disabled'; ?>></a>&nbsp;&nbsp;&nbsp;<input type="text" name="Tel2Personal" id="Tel2Personal" maxlength="3" size="3" value="<?php if ($line['Locale'] != 'Test Drive') echo substr($line['TelephonePersonal'],4,3); ?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white';" <?php if ($line['Locale'] == 'Test Drive') echo 'disabled'; ?>>&nbsp;&ndash;&nbsp;<input type="text" name="Tel3Personal" id="Tel3Personal" maxlength="4" size="4" value="<?php if ($line['Locale'] != 'Test Drive') echo substr($line['TelephonePersonal'],8,4); ?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); hideAllPersonalErrors(); return checkTelephonePersonalOnly();" <?php if ($line['Locale'] == 'Test Drive') echo 'disabled'; ?>>
<div class="error" id="TelephonePersonalError"><br>Please enter a valid telephone number. Use only numbers (0-9).<br></div><?php if ($_SESSION['MsgTelephonePersonal'] != null) { echo $_SESSION['MsgTelephonePersonal']; $_SESSION['MsgTelephonePersonal']=null; } ?></td>
	</tr>
	<tr>
	<td valign="top"><label for="EmailPersonal">Email</label></td>
	<td colspan="2"><a name="EmailPersonalAnchor" class="plain"><input type="text" name="EmailPersonal" id="EmailPersonal" maxlength="60" size="45" value="<?php if ($line['Locale'] != 'Test Drive') echo $line['EmailPersonal'];?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); hideAllPersonalErrors(); return checkEmailPersonalOnly();" <?php if ($line['Locale'] == 'Test Drive') echo 'disabled'; ?>></a><div class="greytextsmall">Example: jdoe_67@yahoo.com</div><div class="error" id="EmailPersonalError">The format of your email address is invalid. Please provide a valid address.<br></div><?php if ($_SESSION['MsgEmailPersonal'] != null) { echo $_SESSION['MsgEmailPersonal']; $_SESSION['MsgEmailPersonal']=null; } ?></td>
	</tr>
<?php endif; ?>
	<tr>
	<td colspan="5"><HR align="left" size="1px" noshade color="#FF9900"></td>
	</tr>
	<tr>
	<td colspan="5" align="center"><br>
	<input type="submit" name="SubmitProfile" value="Submit Now" class="buttonstyle" onclick="document.location = anchordestn; return checkForm();">
&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="submit" name="SaveForLater" value="Save for Later" class="buttonstyle">
&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="submit" name="Abort" value="Abort/Cancel" class="buttonstyle">
	</td>
	</tr>
	</table>
	</form>
	<br>
	<?php
	}
else
	{
	// The visitor's username and password were not found in the database.
	echo "<br><p class='basictext' style='margin-left: 50px'>Incorrect username or password. Please use your browser&rsquo;s Back button or ";
	$Username = null;
	$Password = null;
	// Include a 'Back' button for redisplaying the Authentication form.
	if (isset($_SERVER['HTTP_REFERER'])) // Antivirus software sometimes blocks transmission of HTTP_REFERER.
		{
		echo "<a href='$HTTP_REFERER'>click here</a> to try again.</p>";
		}
	else
		{
		echo "<a href='javascript:history.back()'>click here</a> to try again.</p>";
		}
	}

// Free resultset
mysql_free_result($result);

// Closing connection
mysql_close($db);

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
<!-- This second head is to help prevent IE from caching this page. Ref. http://www.htmlgoodies.com/beyond/reference/article.php/3472881 -->
<HEAD>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="Expires" CONTENT="Mon, 26 Jul 1997 05:00:00 GMT">
</HEAD>
</html>