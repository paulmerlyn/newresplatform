<?php
/*
admin5A.php is Part 2 of admin5.php. Together, these allow an administrator (user) to manage certain attributes of a mediator's account. admin5.php retrieves and displays mediator(s) that match selection criteria supplied by the user. The user then selects/confirms which mediator he/she wants to manage by clicking a check-box. On submit of this check-box form element, control passes to admin5A.php, which provides the management user-interface for tasks sa freezing the mediator's account or performing a renewal. The back-end slave processing of admin5.php and admin5A.php is performed by admin6.php.
*/
// Start a session
session_start();

if (isset($_POST['ClientDemoSelection']))
	{
	$_SESSION['ClientDemoSelected'] = $_POST['ClientDemoSelection']; // Since contents of the post array aren't maintained for very long, store the fact that the user made a client-demo selection in a session variable.
	}
unset($_SESSION['selectedID']); // Unset this to create a clean slate every time admin5.php is started.
?>
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
};

function ClearCheckBoxElements()
{
document.getElementById('Exclusive').disabled = false;
document.getElementById('FreezeAndSuspend').disabled = false;
document.getElementById('FreezeOnly').checked = false;
document.getElementById('SuspendOnly').checked = false;
document.getElementById('DeleteMediator').checked = false; // Move "dead soldier" mediators to a garbage table.
};
</script>
</head>

<body>
<div style="margin: 10px auto; padding: 0px; text-align: center;">
<form method="post" action="unwind.php" style="display: inline;">
<input type="submit" name="ChangeDB" class="submitLinkSmall" value="Change Database">
</form>
&nbsp;&nbsp;&nbsp;&nbsp;
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
	<script type="text/javascript">window.onload = FocusFirst;</script>
	<form method="post" action="admin5.php">
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
		if (!isset($_SESSION['ClientDemoSelected']))
			{
			// Show visitor the form to select either client or demo database access.
			?>
			<div style="position: absolute; left: 240px; top: 100px; width: 250px; text-align:center; padding: 15px; border: 1px solid #444444;">
			<h3>I want to access the following database:</h3>
			<form method="post" action="admin5.php">
			<input type="radio" name="ClientDemoSelection" id="ClientDemoSelection" value="client" checked>
			<label for="ClientDemoSelection">Client&nbsp;&nbsp;&nbsp;</label>
			<input type="radio" name="ClientDemoSelection" id="ClientDemoSelection" value="demo">
			<label for="ClientDemoSelection">Demo</label>
			<br><br>
			<input type="submit" name="ClientDemoButton" value="Continue" class="buttonstyle">
			</form>
			</div>
			<?php
			exit;
			}
		else // A client vs demo selection had previously been made. Set the DB and show the main screen.
			{
			switch ($_SESSION['ClientDemoSelected'])
				{
				case 'client' :
					$dbmediatorstablename = 'mediators_table';
					break;
				case 'demo' :
					$dbmediatorstablename = 'mediators_table_demo';
					break;
				default :
					echo 'Error: Unable to determine the name of an appropriate database table for data insertion.<br>';
					exit;
				}
			?>
			<div style="margin: 20px auto; width: 530px; padding: 15px; border: 2px solid #444444; border-color: #425A8D;">
			<h1 style="margin-left: 0px;">Manage a mediator in the <?php if ($_SESSION['ClientDemoSelected'] == 'client') echo 'client'; else echo 'demo'; ?> database:</h1>
			<br>
			
			<form method="post" name="IdentifyMediator" id="IdentifyMediator" action="admin6.php">
			
			<table width="530">
			<tr>
			<td width="120"><label> State</label></td>
			<td>
			<select name="State" id="State" size="1" style="font-size: 12px; font-weight:normal; font-family:Arial,Helvetica,'Century Gothic', sans-serif" onChange="this.form.submit();">
			<?php
			// Note: this code for generating a drop-down menu of states was first written for updateprofile.php.
			$statesArray = array(array('&lt;&nbsp;Select a State&nbsp;&gt;',null), array('Alabama','AL'),	array('Alaska','AK'), array('Arizona','AZ'), array('Arkansas','AR'),	array('California','CA'), array('Colorado','CO'), array('Connecticut','CT'), array('Delaware','DE'), array('District of Columbia (D.C.)','DC'), array('Florida','FL'), array('Georgia','GA'), array('Hawaii','HI'), array('Idaho','ID'), array('Illinois','IL'), array('Indiana','IN'), array('Iowa','IA'), array('Kansas','KS'), array('Kentucky','KY'), array('Louisiana','LA'), array('Maine','ME'), array('Maryland','MD'), array('Massachusetts','MA'), array('Michigan','MI'), array('Minnesota','MN'), array('Mississippi','MS'), array('Missouri','MO'), array('Montana','MT'), array('Nebraska','NE'), array('Nevada','NV'), array('New Hampshire','NH'), array('New Jersey','NJ'), array('New Mexico','NM'), array('New York','NY'), array('North Carolina','NC'), array('North Dakota','ND'), array('Ohio','OH'), array('Oklahoma','OK'), array('Oregon','OR'), array('Pennsylvania','PA'), array('Rhode Island','RI'), array('South Carolina','SC'), array('South Dakota','SD'), array('Tennessee','TN'), array('Texas','TX'), array('Utah','UT'), array('Vermont','VT'), array('Virginia','VA'), array('Washington','WA'), array('Washington, D.C.','DC'), array('West Virginia','WV'), array('Wisconsin','WI'), array('Wyoming','WY'), array('United States (Generic&ndash;US)','US'));
			for ($i=0; $i<54; $i++)
			{
				$optiontag = '<option value="'.$statesArray[$i][1].'" ';
				if (isset($_SESSION['StateSelected'])) // Preset to previously selected state if one was previously selected.
					{
					if ($_SESSION['StateSelected'] == $statesArray[$i][1]) { $optiontag = $optiontag.'selected'; }
					}
				$optiontag = $optiontag.'>'.$statesArray[$i][0]."</option>\n";
				echo $optiontag;
			}
			?>
			</select>
			<!-- This submit button is no longer necessary when JS is enabled b/c submission takes place via the onchange method of the 'StateOfInterest' select element. --> 
			<noscript><input type="submit" value="Select" name="StateSelected" class="buttonstyle"></noscript>
			</td>
			</tr>
			<tr>
			<td width="120"><label>Locale</label></td>
			<td>
			<select name="Locale" id="Locale" size="1" style="font-size: 12px; font-weight: normal; font-family: Arial, Helvetica, sans-serif;"> <!-- There are no Exclusive et al check boxes to clear when $_SESSION['ClientDemoSelected'] is 'demo'. -->
			<option value='' selected>&lt;&nbsp;Select a Locale&nbsp;&gt;</option>
			<?php
// Connect to mysql to obtain the locales that belong to the selected state of interest. We'll place these locales in an array, which we'll use to build a drop-down menu by which the user can select a particular locale.
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
or die('Could not connect: ' . mysql_error());
mysql_select_db('paulme6_newresolution') or die('Could not select database: ' . mysql_error());

// Formulate query to retrieve all short-name version of locales from the DB that have been established for the selected state.
$query = "select Locale, LocaleShort, LocaleStates from locales_table";
$result = mysql_query($query) or die('Query (select all Locale values from table) failed: ' . mysql_error());
if (!$result) {	echo 'No $result was achievable.'; };

// Place the result of the query in an array $line via the mysql_fetch_assoc command. The associative values $line['LocaleShort'], $line['Locale'], $line['LocaleStates'] on moving down through the lines of this array will hold the Locale, LocaleShort, and LocaleStates values. ...
// ... Then build up strings $localesString and $localesShortString that contain all the $line['Locale'] and $line['LocaleShort'] values for which $_SESSION['StateSelected'] matches with the state value in that line's LocaleStates column. So for a $_SESSION['StateSelected'] of 'WY', $localesString would contain 'Cheyenne_WY, Casper_WY, Gillette_WY, Rock Springs_WY, Riverton_WY, Laramie_WY' and $localesShortString would contain 'Cheyenne, Casper, Gillette, Rock Springs, Riverton, Laramie'.
mysql_data_seek($result, 0); // Reset the point to the beginning of the $result resultset.
$localesString = ''; // Note, technically, this is a string, not an array.
$localesShortString = ''; // Note, technically, this is a string, not an array.
$i = 0;
while ($line = mysql_fetch_assoc($result))
	{
	if ($_SESSION['StateSelected'] != null) if (strstr($line['LocaleStates'],$_SESSION['StateSelected'])) // if the selected state is found within the LocaleStates value ...
		{
		$localesString .= $line['Locale']; // ... add the associated Locale to the string.
		$localesString .= ','; 
		$localesShortString .= $line['LocaleShort']; // ... add the associated LocaleShort to the string.
		$localesShortString .= ','; 
		}
	$i = $i + 1;
	};
$localesString = substr_replace($localesString, '', -1); // Delete the final ',' from the end of the $localesString and replace it with nothing.
$localesShortString = substr_replace($localesShortString, '', -1); // Delete the final ',' from the end of the $localesShortString and replace it with nothing.
// Now we really do need an array, so convert these strings into arrays via explode.
$localesArray = explode(',',$localesString);
$localesShortArray = explode(',',$localesShortString);
sort($localesArray);
sort($localesShortArray);
$arrayLength = count($localesShortArray);
if (!empty($localesShortString)) for ($i=0; $i<$arrayLength; $i++) // Only bother to generate option elements if $localesShortString isn't empty (which it will be when the selected state is the initial default of 'Select a State').
	{
	$optiontag = '<option value="'.$localesArray[$i].'"';
	if ($localesArray[$i] == $_SESSION['LocaleSelected']) $optiontag .= ' selected'; // Preset locale to previously selected value.
	$optiontag .= '>'.$localesShortArray[$i]."</option>\n";
	echo $optiontag;
	}
// Closing connection
mysql_close($db);
			?>
			</select>
			<!-- This JS disables the Locale drop-down menu when the State drop-down menu is at its neutral position. -->
			<script type="text/javascript">if (document.IdentifyMediator.State.selectedIndex == 0) { document.IdentifyMediator.Locale.disabled = true; } else { document.IdentifyMediator.Locale.disabled = false; };</script>
			</td>
			</tr>
			<tr>
			<td width="120" valign="top"><label>Name</label></td>
			<td><input type="text" name="Name" value="" maxlength="30" size="20"><div class="greytextsmall">Enter all or any part of the name</div></td>
			</tr>
			<tr>
			<td valign="top"><label>Username</label></td>
			<td><input type="text" name="Username" value="" maxlength="20" size="20"><div class="greytextsmall">Enter all or the first few characters of the username</div></td>
			</tr>
			<tr>
			<td valign="top"><label>Password</label></td>
			<td><input type="text" name="Password" value="" maxlength="20" size="20"><div class="greytextsmall">Exact match required when specifying password</div></td>
			</tr>
			<tr>
			<td valign="top"><label>Locale Label</label></td>
			<td><input type="text" name="LocaleLabel" id="LocaleLabel" maxlength="30" size="30">
			<div class="greytextsmall">Enter all or any part of the locale label </div></td>
			</tr>
			<tr>
			<td valign="top"><label>ID</label></td>
			<td><input type="text" name="ID" id="ID" maxlength="4" size="3"><div class="greytextsmall">Exact match required when specifying ID</div></td>
			</tr>
			<tr>
			<td valign="top"><label>Email</label></td>
			<td><input type="text" name="Email" maxlength="50" size="30">
			<div class="greytextsmall">Enter all or any part of the email address</div></td>
			</tr>
			<tr>
			<td valign="top"><label>Zip (Professional)</label></td>
			<td><input type="text" name="Zip" maxlength="10" size="5"><div class="greytextsmall">Enter all or the first few characters of the zip code</div></td>
			</tr>
			<tr>
			<td valign="top"><label>Zip (Personal)</label></td>
			<td><input type="text" name="ZipPersonal" maxlength="10" size="5"><div class="greytextsmall">Enter all or the first few characters of the zip code</div></td>
			</tr>
			<tr>
			<td valign="top"><label>Show All</label></td>
			<td><input type="checkbox" name="ShowAllMeds" value="1" onClick="if (this.checked) this.form.submit();"><div class="greytextsmall">Check box to override search criteria and retrieve all mediators</div>
</td>
			</tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr>
			<td colspan="2" align="left"><input type="submit" name="FindMediator" class="buttonstyle" style="margin-left: 120px;" value="Find Mediator"></td>
			</tr>
			</table>
			</form>

			</div>

			<?php
			if (isset($_SESSION['FindMediatorReturn']))
			{
				unset($_SESSION['FindMediatorReturn']);
				$IDmatchesArray = $_SESSION['IDmatchesArray'];  // Load the session variable (whose value was determined in admin6.php) into $IDmatchesArray for continued use inside admin5.php.
				$NofMatches = count($IDmatchesArray);  // Note: $NofMatches will be 0 when $IDmatchesArray is null from admin6.php.
				switch ($NofMatches)
				{
				case 0:
					echo '<script type="text/javascript" language="javascript">alert("No mediators match your search criteria. Please try again.");</script>';
					break;
				default: // Display details of all potential mediator matches.
					?>
					<form method="post" action="admin5A.php">
					
					<script type="text/javascript" language="javascript">
					// Chrome browser detection courtesy http://davidwalsh.name/detecting-google-chrome-javascript
					var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
					if (is_chrome) document.write('<br>')
					</script>
					
					<div style="margin: 20px auto; width: 900px; padding: 10px; border: 2px solid #444444; border-color: #425A8D;">
					<h1 style="margin-left: 0px;">Select mediator from the <?php if ($_SESSION['ClientDemoSelected'] == 'client') echo 'client'; else echo 'demo'; ?> database:</h1>
					<table id="matchingmediatorstable" width="540" cellspacing="0" cellpadding="6" border="0" style="font-size: 10px; font-family: Arial, Helvetica, sans-serif; padding: 0px;">
					<thead>
					<tr>
					<th align="left">&nbsp;</th>
					<th align="left">Name</th>
					<th align="left">Locale</th>
					<th align="left">ID</th>
					<th align="left">Locale&nbsp;Label</th>
					<th align="left">Email</th>
					<th align="left">Personal Email</th>
					<th align="left">Telephone</th>
					<th align="left">Tel&nbsp;(Personal)</th>
					</tr>
					</thead>
					<tbody>
					<?php
					// Connect to DB to select details of mediators whose IDs are in the $IDmatchesArray.
					$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
					or die('Could not connect: ' . mysql_error());
					mysql_select_db('paulme6_newresolution') or die('Could not connect to the database: ' . mysql_error());
					$i = 0;
					$query = "SELECT Name, Locale, ID, LocaleLabel, Email, UseProfessionalEmail, ProfessionalEmail, EmailPersonal, Telephone, TelephonePersonal FROM ".$dbmediatorstablename." WHERE (ID=".$IDmatchesArray[$i].")";
					$i=1;
					while ($i < $NofMatches)
					{
						$query .= " OR (ID=".$IDmatchesArray[$i].")";
						$i++;
					}
					$result = mysql_query($query) or die('Query (select Name, Locale, ID, Email, etc. from client or demo mediators table for user-selected mediator search criteria) has failed: ' . mysql_error());
					while ($row = mysql_fetch_assoc($result))
					{
						$IDmatchesArray[$i] = $row['ID'];
						echo '<tr>';
						echo '<td><input type="checkbox" name="selectedID" value="'.$row['ID'].'" onclick="if (this.checked) this.form.submit();"></td>';
						echo '<td>'.$row['Name'].'</td>';
						echo '<td>'.$row['Locale'].'</td>';
						echo '<td>'.$row['ID'].'</td>';
						echo '<td>'.$row['LocaleLabel'].'</td>';
						if ($row['UseProfessionalEmail'] == 1) { echo '<td>'.$row['ProfessionalEmail'].'</td>'; } else { echo '<td>'.$row['Email'].'</td>'; } 
						echo '<td>'.$row['EmailPersonal'].'</td>';
						echo '<td>'.$row['Telephone'].'</td>';
						echo '<td>'.$row['TelephonePersonal'].'</td>';
						echo '</tr>';
					}
					?>
					</tbody>
					</table>
					</div>
					<script type="text/javascript" language="javascript">
					location.hash = "matchingmediatorstable";
					</script>
					</form>
					<?php
					// Closing connection
					mysql_close($db);
					break;
				}
			}
			
			
			if ($_SESSION['ClientDemoSelected'] == 'client') // By-pass if the user selected 'demo' rather than 'client'.
			{
				if (isset($_SESSION['IneligibleForExclusive'])) // This flag gets set in admin2.php if user is trying to check the 'Exclusive check-box for a locale that already has more than one licensee.
				{
				echo '<script type="text/javascript" language="javascript">document.getElementById(\'Exclusive\').checked = false; document.getElementById(\'Exclusive\').disabled = true;</script>';
				if ($_SESSION['IneligibleForExclusive'] == 1)
					{
					echo '<script type="text/javascript" language="javascript">alert("This locale already has at least one mediator.\nIt is not available for sale as an exclusive license."); document.getElementById(\'Exclusive\').checked = false; document.getElementById(\'Exclusive\').disabled = true;</script>';
					}
				else
					{
					echo '<script type="text/javascript" language="javascript">document.getElementById(\'Exclusive\').checked = true; document.getElementById(\'Exclusive\').disabled = false;</script>';
					};
				};
			};

			}
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