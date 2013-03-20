<?php
/*
admin9.php allows an administrator (user) to add a new username-password pair to the userpass_table or userpass_table_demo database table. It also shows him/her how many username-password pairs remain available (i.e. not yet allocated to a client or demo mediator). The slave processing of this script is performed by admin10.php.
*/
// Start a session
session_start();

if (isset($_POST['ClientDemoSelection']))
	{
	$_SESSION['ClientDemoSelected'] = $_POST['ClientDemoSelection']; // Since contents of the post array aren't maintained for very long, store the fact that the user made a client-demo selection in a session variable.
	}
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

/* Begin JS form validation functions. */
function checkUPpairsOnly()
{
// Validate that username-password pair(s) were entered in a valid format.
var illegalCharSet, reqdCharSet, upPairsValue, upPairsArray;
var upPairsValue = document.getElementById("UPpairs").value;
upPairsValue = upPairsValue.replace(/\r/g, ""); // Replace \r with nothing to promote consistency of line-break handling by different o/s.
upPairsValue = upPairsValue.replace(/\x20/g, ""); // Replace all space characters (i.e. characters with hex code \x20) with nothing to tidy up username-password data prior to DB data entry.
while (upPairsValue.indexOf("\n\n") != -1) // While there's at least one double line break in the string ...
	{
	upPairsValue = upPairsValue.replace(/\n\n/g, "\n"); // Remove any duplicate line breaks to promote data handling.
	};
// Split upPairsValue string of form doncaster,rovers\nsilver,lining\nred,rum into array form st upPairsArray[0] = doncaster,rovers; upPairsArray[1] = silver,lining; and upPairsArray[2] = red,rum
var upPairsArray = upPairsValue.split(/\n/); 

illegalCharSet = /[^,\s\w]+/; // Exclude everything except a comma, a whitespace, and an alphanumeric (A-Z, a-z, 0-9, and underscore _).
reqdCharSet = /^\w+,{1}\w+$/;  // Names of form alphanumeric word, some whitespace perhaps, a comma, more whitespace perhaps, and one or more alphanumeric characters.

// Test whether each element in the upPairsArray[] passes the illegalCharSet and reqdCharSet tests
for (var i=0; i < upPairsArray.length; i++)
    {
	if (illegalCharSet.test(upPairsArray[i]) || !(reqdCharSet.test(upPairsArray[i])))
		{
		document.getElementById("UPpairsError").style.display = "inline";
		return false; // Flip testresult to false because at least one of the array elements fails.
		break; // Also now break out of the for loop.
		} 
	else
		{
		upPairsArray[i] = upPairsArray[i].replace(/,/g, ",\x20"); // To improve formatting of the JS alert, introduce a space after the comma that separates a username from a password in each element upPairsArray[i].
		};
	}

return true;
}

function checkForm() // Function checkForm() gets called when the user clicks the submit button. (Note that PHP validation also takes place (inside admin10.php) in case Administrator doesn't have javascript enabled.)
{
hideAllErrors();
if (checkUPpairsOnly())
	{
	return true;
	}
else
	{
	document.getElementById("UPpairsError").style.display = "inline";
	return false;
	}
} // End of checkForm()

/* This function hideAllErrors() is called by checkForm() and by onblur event within non-personal form fields. */
function hideAllErrors()
{
document.getElementById("UPpairsError").style.display = "none";
return;
}
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
$Username = $_POST['Username'];
$Password = $_POST['Password'];

if  (empty($Authentication) && $_SESSION['Authenticated'] != 'true')
	{
	// Visitor needs to authenticate
	?>
	<div style="position: absolute; left: 240px; top: 100px; width: 250px; padding: 15px; border: 2px solid #444444; border-color: #425A8D;">
	<h3>Please authenticate yourself:</h3>
	<br>
	<script type="text/javascript">window.onload = FocusFirst;</script>
	<form method="post" action="/scripts/admin9.php">
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
			<div style="position: absolute; left: 240px; top: 100px; width: 250px; text-align:center; padding: 15px; border: 2px solid #444444; border-color: #425A8D;">
			<h3>I want to access the following database:</h3>
			<form method="post" action="/scripts/admin9.php">
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
		else // A client vs demo selection had previously been made. Show the main screen.
			{
				switch ($_SESSION['ClientDemoSelected'])
				{
				case 'client' :
					$userpasstablename = 'userpass_table';
					break;
				case 'demo' :
					$userpasstablename = 'userpass_table_demo';
					break;
				default :
					echo 'Error: Unable to determine the name of an appropriate username-password table (client or demo) in admin9.php.<br>';
					exit;
				}
			?>
			<div style="margin: 20px auto; width: 530px; padding: 15px; border: 2px solid #444444; border-color: #425A8D;">
			<h1 style="margin-left: 0px;">Manage username-password repository for <?php if ($_SESSION['ClientDemoSelected'] == 'client') echo 'client'; else echo 'demo'; ?> mediators:</h1>
			<br>
			
			<?php
			// Establish a DB connection
			$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
			or die('Could not connect: ' . mysql_error());
			mysql_select_db('paulme6_newresolution') or die('Could not select database: ' . mysql_error());
			
			// Count the number of unallocated username-password pairs.
			$query = "SELECT count(*) FROM ".$userpasstablename." WHERE Available = 1"; // Count of only unallocated username-password pairs
			$result = mysql_query($query) or die('The SELECT query count of available username-password pairs failed i.e. '.$query.' failed: ' . mysql_error());
			$row = mysql_fetch_row($result); // $row array contains just one value i.e. the value of the count of all available UP pairs.
			$rowAvail = $row[0];

			// Count the total number of username-password pairs.
			$query = "SELECT count(*) FROM ".$userpasstablename; // Count of username-password pairs in the DB table
			$result = mysql_query($query) or die('The SELECT query count of total username-password pairs failed i.e. '.$query.' failed: ' . mysql_error());
			$row = mysql_fetch_row($result); // $row array contains just one value i.e. the value of the count of all username-password pairs.
			$rowAll = $row[0];
			
			// Obtain all username-password pairs from the userpass table.
			$query = "SELECT Username, Password FROM ".$userpasstablename." ORDER BY Username";  // Alphabetical order by Username
			$result = mysql_query($query) or die('The SELECT query of all username-password pairs failed i.e. '.$query.' failed: ' . mysql_error());

			// Construct string that will be echoed into a div below to display all username-password pairs already in the database.
			$retrievedUPs = '<table cellpadding="0" cellspacing="0"><tr style="height: 20px; vertical-align: top;">';
			$retrievedUPs .= '<td width="120"><span style="font-size: 12px; font-weight: bold;">Username</span></td>';
			$retrievedUPs .= '<td><span style="font-size: 12px; font-weight: bold;">Password</span></td>';
			$retrievedUPs .= '</tr>';
			while ($row = mysql_fetch_assoc($result))
				{
				$retrievedUPs .= '<tr style="height: 18px; vertical-align: top; font-family: Courier, Times, serif; font-size: 8px;"><td>'.$row['Username'].'</td>';
				$retrievedUPs .= '<td>'.$row['Password'].'</td></tr>';
				}
			$retrievedUPs .= '</table>';
			?>
			
			<table width="530">
			<tr>
			<td width="212" valign="top"><label>Total Username-Password Pairs</label></td>
			<td valign="top" align="left" class="basictext"><?php echo $rowAll; ?></td>
			</tr>
			<tr>
			<td width="212" valign="top"><label>Available Pairs</label></td>
			<td valign="top" align="left" class="basictext"><?php echo $rowAvail; ?></td>
			</tr>
			</table>
			<?php
			if ($rowAvail < 10) 
			{
			?>
			<table>
			<tr>
			<td width="530" class="errorphp">Warning: You have less than 10 username-password pairs available. Add more immediately!</td>
			</tr>
			</table>
			<?php
			}
			?>

			<form method="post" action="/scripts/admin10.php">
			<table width="530" style="margin-top: 40px;">
			<tr>
			<td width="212" valign="top">
			<label>Look Up Username for Password</label>
			<div class="greytextsmall">Enter a password</div>
			</td>
			<td>
			<input type="text" name="Password" id="Password" maxlength="20" size="20" onFocus="this.style.background='#FFFF99';" onBlur="this.style.background='white'">
			&nbsp;&nbsp;&nbsp;
			<input type="submit" name="LookUpUsername" class="buttonstyle" style="font-size: 12px; width: 125px;" value="Look Up Username">
			</td>
			</tr>
			</table>
			</form>
			<form method="post" action="/scripts/admin10.php">
			<table width="530" style="margin-top: 40px;">
			<tr>
			<td width="212" valign="top">
			<label>Look Up Password for Username</label>
			<div class="greytextsmall">Enter a username</div>
			</td>
			<td>
			<input type="text" name="Username" id="Username" maxlength="20" size="20" onFocus="this.style.background='#FFFF99';" onBlur="this.style.background='white';">
			&nbsp;&nbsp;&nbsp;
			<input type="submit" name="LookUpPassword" class="buttonstyle" style="font-size: 12px; width: 125px;" value="Look Up Password">
			</td>
			</tr>
			</table>
			</form>
			
			<form method="post" action="/scripts/admin10.php">
			<table width="530" style="margin-top: 40px;">
			<tr>
			<td width="220" valign="top"><label>Add Username-Password Pair(s)</label></td>
			<td>
			<textarea name="UPpairs" id="UPpairs" rows="10" cols="40" wrap="soft" style="overflow:auto; height: 180px; width: 310px;" onClick="this.focus(); this.select();" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors();">username, password (e.g. moonlight, sonata)</textarea><div class="greytextsmall">Format username-password pairs as <em>username, password</em><br>
			Alphanumeric characters and comma only. Use the  [Enter] key to place each pair on its own line when submitting multiple pairs.</div>
			<div class="error" id="UPpairsError">Enter valid username-password pair(s). Use a comma (,) to separate each username from its associated password. Place each username-password pair on its own line. Use only alphanumerics [A-Z, a-z, 0-9] in usernames and passwords.<br></div><!-- There is no need for a PHP validation message because admin10.php automatically rejects invalid user data or data that is already duplicated in the database table, simply refusing to insert it into the database. -->
			</td>
			</tr>
			<tr>
			<td width="220">&nbsp;</td>
			<td align="center">
			<input type="submit" style="margin-top: 20px;" name="SubmitNewUPpairs" class="buttonstyle" value="Submit" onClick="return checkForm();">
			</td>
			</tr>
			</table>
			<table width="530" style="margin-top: 40px;">
			<tr>
			<td width="265" valign="top"><label>View Usernames/Passwords in Database</label></td>
			<td>
			<div style="overflow: auto; height: 180px; border: 1px solid #425A8D; padding: 10px 10px 10px 10px;" class="basictext">
			<?php echo $retrievedUPs; ?>
			</div>
			</td>
			</tr>
			</table>
			</form>
			
			</div>

			<?php
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
exit;
?>	
</body>
</html>