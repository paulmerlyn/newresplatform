<?php
/*
admin11.php allows an administrator (user) to enter details of a prospect into the DB's prospects_table, including the designation of an appropriate category (e.g. "Experienced Attorney", "Newbie Mediator with Minimal Site", "Experienced Non-Attorney Mediator", "Therapist-Mediator", "Therapist (Non-Mediator)", "Financial Pro", "Professional Coach", etc.) for use in sending an email message targeted appropriately. The email message can be sent immediately or later according to whether the administrator clicks a check box. After authentication of the administrator and submission of the prospect details form, it hands over to form action script admin12.php, a slave script to insert the data into the DB and send a marketing email to the prospect if desired.
*/

// Start a session
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>New Resolution Mediation Launch Platform&trade; Administrator</title>
<link href="/nrcss.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="javascript" src="/scripts/emailaddresschecker.js"></script>
<script>
function FocusFirst()
{
	document.getElementById('CreateProspect').elements[0].focus();
}

/* Begin JS form validation functions. I'm doing minimal JS form validation (email address and FormMailCode only), but I do a more thorough check of several fields via PHP form validation inside slave script admin12.php. */
function checkProspectEmailOnly()
{
// Validate EntityEmail field.
var prospectEmailValue = document.getElementById("ProspectEmail").value;
var prospectEmailLength = prospectEmailValue.length;
if (prospectEmailValue != null && prospectEmailValue != '') // Since ProspectEmail may be unknown when the Administrator originally enters the prospect into the DB, only validate if it's non-blank.
	{
	if (prospectEmailValue > 60 || !(emailCheck(prospectEmailValue,'noalert'))) // emailCheck() is function in emailaddresscheker.js. This field is reqd i.e. blank causes a rejection as invalid.
		{
		document.getElementById("ProspectEmailError").style.display = "inline";
		anchordestn = "#ProspectEmailAnchor";
		return false;
		}
	else
		{
		return true;
		}
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

function checkFormMailCodeOnly()
{
// Validate State selection.
if (document.getElementById('FormMailCode').selectedIndex == 0)
	{
	document.getElementById("FormMailCodeError").style.display = "inline";
	return false;
	} 
else
	{
	return true;
	}
}

function checkForm() // checkForm() gets called when the user clicks the submit button.
{
hideAllErrors();
if (!checkProspectEmailOnly() || !checkFormMailCodeOnly() || !checkStateOnly()) return false;
else return true; // All element(s) passed their validity checks, so return a true.
} // End of checkForm()

/* This function hideAllErrors() is called by checkForm() and by onblur event. */
function hideAllErrors()
{
document.getElementById("ProspectEmailError").style.display = "none";
document.getElementById("StateError").style.display = "none";
document.getElementById("FormMailCodeError").style.display = "none";
return true;
}
</script>
</head>

<body onLoad="FocusFirst();">
<div style="margin: 10px auto; padding: 0px; text-align: center;">
<form method="post" action="unwind.php" style="display: inline;">
<input type="submit" name="Logout" class="submitLinkSmall" value="Log Out">
</form>
</div>

<h1 style="text-align: center; font-size: 22px; padding: 0px; color: #425A8D;">New Resolution Mediation Launch Platform&trade; Administrator</h1>
<?php
require('../ssi/adminmenu.php'); // Include the navigation menu.

// Create short variable names
$Authentication = $_POST['Authentication'];

if  (empty($Authentication) && $_SESSION['Authenticated'] != 'true')
	{
	// Visitor needs to authenticate
	?>
	<div style="position: absolute; left: 240px; top: 100px; width: 250px; padding: 15px; border: 2px solid #444444; border-color: #425A8D;">
	<h3>Please authenticate yourself:</h3>
	<br>
	<form method="post" action="/scripts/admin12.php">
	<table border="0" width="280">
	<tr>
	<td align="center"><input type="password" name="Authentication" maxlength="40" size="20"></td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
	<td align="center"><input type="submit" class="buttonstyle" value="Authenticate"></td>
	</tr>
	</table>
	</form>
	</div>
	<?php
	exit;
	}
else
	{
	// See if the password entered by the user in the POST array is correct. (Note that PHP comparisons are case-sensitive [unlike MySQL query matches] and sha1 returns a lower-case result.) If it is correct or if the $_SESSION['Authenticated'] session variable was set for a previously established authentication, proceed to show either the client vs demo selection form or proceed straight to the main screen.
	if ((sha1($Authentication) == 'dc6a59aab127063fd353585bf716c7f7c34d2aa0') || $_SESSION['Authenticated'] == 'true')
		{
		$_SESSION['Authenticated'] = 'true';
	?>
		<div style="margin: 20px auto; width: 530px; padding: 15px; border: 2px solid #444444; border-color: #425A8D;">
		<h1 style="margin-left: 0px;">Create a prospect:</h1>
		<br>
		<form method="post" name="CreateProspect" id="CreateProspect" action="/scripts/admin12.php">
		
		<table width="530">
		<tr height="30">
		<td width="120" align="left"><label for="Title">Title/Address</label></td>
		<td align="left" width="40"><input type="text" name="Title" id="Title" maxlength="10" size="3"  style="width: 30px;" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white';">
		&nbsp;&nbsp;&nbsp;
		<label for="FirstName">First Name</label>&nbsp;
		<input type="text" name="FirstName" id="FirstName" maxlength="20" size="8" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white';">
		&nbsp;&nbsp;&nbsp;
		<label>Last Name</label>&nbsp;<input type="text" name="LastName" id="LastName" maxlength="30" size="9" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white';">
		</td>
		</tr>
		<tr><!-- This row is specially created to hold Title, FirstName, and LastName errors. -->
		<td>&nbsp;</td>
		<td colspan="3" valign="top" align="left">
		<?php if ($_SESSION['MsgTitle'] != null) { echo $_SESSION['MsgTitle']; $_SESSION['MsgTitle']=null; } ?>
		<?php if ($_SESSION['MsgFirstName'] != null) { echo $_SESSION['MsgFirstName']; $_SESSION['MsgFirstName']=null; } ?>
		<?php if ($_SESSION['MsgLastName'] != null) { echo $_SESSION['MsgLastName']; $_SESSION['MsgLastName']=null; } ?>
		</td>
		</tr>
		<tr height="40">
		<td align="left" valign="top" style="position: relative; top: 4px;"><label>Use Formal Title</label></td>
		<td align="left" colspan="3"><input type="checkbox" name="UseFormalTitle" value="1">
		<div class="greytextsmall">Check to use formal title in solicitation. Example: <em>Dr. Saunders</em>, rather than <em>Bob</em></div></td>
		</tr>
		<tr height="50">
		<td align="left"><label for="Email">Prospect&rsquo;s Email</label></td>
		<td align="left" colspan="3"><a name="ProspectEmailAnchor" class="plain"><input type="text" name="ProspectEmail" id="ProspectEmail" maxlength="60" size="30" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkProspectEmailOnly();"></a><span class="redsup">&nbsp;&nbsp;<b>*</b> (required)</span>
		<div class="error" id="ProspectEmailError"><br />Please check your format. Use only alphanumerics (A-Z, a-z, 0-9), dash (-), period (.), @, and underscore (_) characters.<br></div><?php if ($_SESSION['MsgProspectEmail'] != null) { echo $_SESSION['MsgProspectEmail']; $_SESSION['MsgProspectEmail']=null; } ?>
		</td>
		</tr>
		<tr height="30">
		<td width="120" align="left"><label for="AreaLabel">Area Label</label></td>
		<td align="left" width="410"><input type="text" name="AreaLabel" id="AreaLabel" maxlength="40" size="30"  style="width: 300px;" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white';">
		<div class="greytextsmall">Example: <em>the DFW Metroplex</em> or <em>the Bay Area</em> or <em>Delaware</em></div><?php if ($_SESSION['MsgAreaLabel'] != null) { echo $_SESSION['MsgAreaLabel']; $_SESSION['MsgAreaLabel']=null; } ?>
		</td>
		</tr>
		<tr height="30">
		<td width="120" align="left"><label for="AreaLabel">State</label></td>
		<td align="left" width="410">
		<select name="State" id="State" size="1" onchange="hideAllErrors(); return checkStateOnly();">
			<?php
			$statesArray = array(array('&lt;&nbsp; Select a State &nbsp;&gt;',null), array('Alabama','AL'),	array('Alaska','AK'), array('Arizona','AZ'), array('Arkansas','AR'),	array('California','CA'), array('Colorado','CO'), array('Connecticut','CT'), array('Delaware','DE'), array('District of Columbia','DC'), array('Florida','FL'), array('Georgia','GA'), array('Hawaii','HI'), array('Idaho','ID'), array('Illinois','IL'), array('Indiana','IN'), array('Iowa','IA'), array('Kansas','KS'), array('Kentucky','KY'), array('Louisiana','LA'), array('Maine','ME'), array('Maryland','MD'), array('Massachusetts','MA'), array('Michigan','MI'), array('Minnesota','MN'), array('Mississippi','MS'), array('Missouri','MO'), array('Montana','MT'), array('Nebraska','NE'), array('Nevada','NV'), array('New Hampshire','NH'), array('New Jersey','NJ'), array('New Mexico','NM'), array('New York','NY'), array('North Carolina','NC'), array('North Dakota','ND'), array('Ohio','OH'), array('Oklahoma','OK'), array('Oregon','OR'), array('Pennsylvania','PA'), array('Rhode Island','RI'), array('South Carolina','SC'), array('South Dakota','SD'), array('Tennessee','TN'), array('Texas','TX'), array('Utah','UT'), array('Vermont','VT'), array('Virginia','VA'), array('Washington','WA'), array('Washington, D.C.','DC'), array('West Virginia','WV'), array('Wisconsin','WI'), array('Wyoming','WY'));
			for ($i=0; $i<53; $i++)
				{
				$optiontag = '<option value="'.$statesArray[$i][1].'" ';
				$optiontag = $optiontag.'>'.$statesArray[$i][0]."</option>\n";
				echo $optiontag;
				}
			?>
			</select><span class="redsup">&nbsp;&nbsp;<b>*</b></span>
			<div class="error" id="StateError">Please select a state from the drop-down menu.<br></div>
			<?php if ($_SESSION['MsgState'] != null) { echo $_SESSION['MsgState']; $_SESSION['MsgState']=null; } ?>
		</td>
		</tr>
		<tr height="30">
		<td width="120" align="left"><label for="FormMailCode">Form Mail Code</label></td>
		<td align="left" width="410">
		<select name="FormMailCode" id="FormMailCode" style="font-size:12px; font-weight:normal; font-family:Arial,Helvetica,sans-serif;"  onchange="hideAllErrors(); return checkFormMailCodeOnly();">
		<option value="">&lt;&nbsp;Select a Code&nbsp;&gt;</option>
		<option value="EarlyMedNo">1. Early Mediator/No Site</option>
		<option value="EarlyMedMin">2. Early Mediator/Minimal Site</option>
		<option value="EstdMedMin">3. Established Mediator/Minimal Site/Generic</option>
		<option value="EstdMed">4. Established Mediator/Visibility Pitch</option>
		<option value="ExpAttyMed">5. Experienced Attorney Mediator</option>
		<option value="AttyNonMed">6. Attorney Non-Mediator/Extend Scope</option>
		<option value="TherapMed">7. Therapist Mediator</option>
		<option value="TherapExt">8. Therapist Non-Mediator/Extend Scope</option>
		<option value="FinProMed" disabled="disabled">9. Financial Pro Mediator</option>
		<option value="FinProExt" disabled="disabled">10. Financial Pro Non-Mediator/Extend Scope</option>
		<option value="CoachMed" disabled="disabled">11. Coach Mediator</option>
		<option value="CoachExt" disabled="disabled">12. Coach Non-Mediator/Extend Scope</option>
		<option value="LawStud">13. Law Students</option>
		<option value="TherapStud">14. Therapist Students</option>
		</select>
		<span class="redsup"></a>&nbsp;&nbsp;<b>*</b></span>
		<div class="error" id="FormMailCodeError"><br>Please select a code from the drop-down menu.<br></div>
		</td>
		</tr>
		<tr height="40">
		<td align="left" valign="top" style="position: relative; top: 4px;"><label>Send Email Now?</label></td>
		<td align="left" colspan="3"><input type="checkbox" name="SendNow" value="1">
		<div class="greytextsmall">Check to send a marketing message to this prospect now.</div></td>
		</tr>
		<tr height="30">
		<td width="120" align="left"><label for="Source Notes">Source Notes</label></td>
		<td align="left" width="410"><input type="text" name="SourceNotes" id="SourceNotes" maxlength="70" size="100"  style="width: 300px;" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white';">
		<div class="greytextsmall">Record the source of this prospect here (e.g. LinkedIn, mediate.com, etc.)</div>
		</td>
		</tr>
		<tr height="60px" valign="middle">
		<td align="center" colspan="3"><input type="submit" name="AdminCreateProspect" class="buttonstyle" style="margin-left: 0px;" value="Create Prospect" onclick="return checkForm();"></td>
		</tr>
		</table>			
		</form>
		</div>

		<?php
		}
	else
		{
		// Authentication is denied.
		echo "<p class='basictext' style='position: absolute; left: 150px; margin-right: 50px; margin-top: 180px; font-size: 14px;'>Authentication is denied. Use your browser&rsquo;s Back button or ";
		// Include a 'Back' button for redisplaying the Authentication form.
		if (isset($_SERVER['HTTP_REFERER'])) // Antivirus software sometimes blocks HTTP_REFERER.
			{
			echo "<a style='font-size: 14px;' href='".$_SERVER['HTTP_REFERER']."'>click here</a> to try again.</p>";
			}
		else
			{
			echo "<a style='font-size: 14px;' href='javascript:history.back()'>click here</a> to try again.</p>";
			}
		}
	}
?>	
</body>
</html>