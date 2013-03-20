<?php
/*
admin7.php allows an administrator (user) to update platform parameters such as the license fee and license term value-pairs being offered to new mediators and the default APR at which a mediator's license fee will increase unless an administrator manually intercedes. These values are stored in the DB's parameters_table. admin8.php is a slave script to process updates when the user clicks the 'Update Parameters' button.
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
<script>
function FocusFirst()
{
	document.getElementById('SetParams').elements[0].focus();
}

/* Begin JS form validation functions. (Note to self: I did PHP validation that user selected a TermNumber and TermUnit from the drop-down menus, and I've decided not to bother with a Javascript version of that type of validation.) */
function checkMarginNowOnly()
{
// Validate MarginNow percentage field.
var marginNowValue = document.getElementById("MarginNow").value;
illegalCharSet = /[^0-9\.]+/; // Reject everything that starts with anything other than a number and contains neither one or more non-digits and period.
if (illegalCharSet.test(marginNowValue))
	{
	document.getElementById("MarginNowError").style.display = "inline";
	return false;
	} 
else
	{
	return true;
	}
}

function checkDefAPROnly()
{
// Validate DefaultAPR percentage field.
var defAPRValue = document.getElementById("DefaultAPR").value;
illegalCharSet = /[^0-9\.]+/; // Reject everything that starts with anything other than a number and contains neither one or more non-digits and period.
if (illegalCharSet.test(defAPRValue))
	{
	document.getElementById("DefaultAPRError").style.display = "inline";
	return false;
	} 
else
	{
	return true;
	}
}

function checkLicFeeForTermOnly(val)
{
// Validate LicFeeForTerm input field.
illegalCharSet = /[^0-9\.]+/; // Reject everything that starts with anything other than a number and contains neither one or more non-digits and period.
if (illegalCharSet.test(val))
	{
	document.getElementById("LicFeeForTermError").style.display = "inline";
	return false;
	} 
else
	{
	return true;
	}
}

function checkForm() // To ease complexity, I eschew inclusion of the checkLicFeeForTermOnly() function inside checkForm() b/c it would need to be called multiple times for the dynamically generated license fee-license term rows.
{
hideAllErrors();
if (!checkDefAPROnly() || !checkMarginNowOnly())
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
document.getElementById("MarginNowError").style.display = "none";
document.getElementById("DefaultAPRError").style.display = "none";
document.getElementById("LicFeeForTermError").style.display = "none";
return true;
}

/*
 Append row to the HTML table when user clicks the 'Show More' button. Note that function appendRow() calls custom function createCell(). [ref. http://www.redips.net/javascript/adding-table-rows-and-columns/]  
*/

function appendRow()
{   
  var tbl = document.getElementById('ParamsTable'); // table reference   
  // append table row   
  var row = tbl.insertRow(tbl.rows.length); // insertRow() is a standard DOM method.
  row.style.height = '40px';
  // insert table cells to the new row using the createCell() custom function.  
  createCell(row.insertCell(0), '<label>License fee</label>');
  var cell1string = '<label>$</label><input name="LicFeeForTerm' + nth + '" id="LicFeeForTerm' + nth + '" size="5" maxlength="12" class="basictext">';
  createCell(row.insertCell(1), cell1string);
  var cell2string = '<label>for the term:</label>&nbsp;&nbsp;<select name="TermNumber' + nth + '" id="TermNumber' + nth + '" style="font-size:12px; font-weight:normal; font-family:Arial,Helvetica,sans-serif;"><option value="">&lt;&nbsp;Quantity&nbsp;&gt;</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option></select>&nbsp;&nbsp;&nbsp;<select name="TermUnit' + nth + '" id="TermUnit' + nth + '" style="font-size:12px; font-weight:normal; font-family:Arial,Helvetica,sans-serif;"><option value="">&lt;&nbsp;Units&nbsp;&gt;</option><option value="months">months</option><option value="quarter">quarter</option><option value="years">years</option></select>';  
  createCell(row.insertCell(2), cell2string);   
  nth = nth + 1; // Increment the n th row counter (which is a global variable) e.g. next time the appendRow() function is called, the 6th row will be added rather than the 5th row.
}

function createCell(cell, text)
{   
  var div = document.createElement('div'); // create DIV element   
  div.innerHTML = text;
  cell.appendChild(div);                   // append DIV to the table cell using standard DOM appendChild() method.  
}  

</script>
</head>

<!-- Note: on load, we assign a value for nth. This value will be one more than the number of rows in the the ParamsTable when the page is first loaded. Example: If FeeTerm in the parameters_table has, say, four fee-term value-pairs, then nth should be assigned the value of 5 because we want the first row that is appended if the user clicks the 'Show More' button to be identified with the suffix 5 in its name and id attributes (see function appendRow() above). Note that the DefaultAPR is presented in its own table, so its row doesn't interfere with the row count. Note also that ParamsTable is generated dynamically to always show the correct number of rows on loading for the number of fee-term value pairs in the paramaters_table. -->
<body onLoad="nth = document.getElementById('ParamsTable').rows.length + 1; FocusFirst();">
<div style="margin: 10px auto; padding: 0px; text-align: center;">
<form method="post" action="unwind.php" style="display: inline;">
<input type="submit" name="Logout" class="submitLinkSmall" value="Log Out">
</form>
</div>

<h1 style="text-align: center; font-size: 22px; padding: 0px; color: #425A8D;">New Resolution Mediation Launch Platform&trade; Administrator</h1>
<?php
require('../ssi/adminmenu.php'); // Include the navigation menu.
require('../ssi/obtainparameters.php'); // Include the obtainparameters.php file, which retrieves values $MarginNow, $DefaultAPR, and $FeeTermArray from the parameters_table table.

// Create short variable names
$Authentication = $_POST['Authentication'];

if  (empty($Authentication) && $_SESSION['Authenticated'] != 'true')
	{
	// Visitor needs to authenticate
	?>
	<div style="position: absolute; left: 240px; top: 100px; width: 250px; padding: 15px; border: 2px solid #444444; border-color: #425A8D;">
	<h3>Please authenticate yourself:</h3>
	<br>
	<form method="post" action="/scripts/admin7.php">
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
		<h1 style="margin-left: 0px;">Set platform parameters:</h1>
		<br>
		<form method="post" name="SetParams" id="SetParams" action="/scripts/admin8.php">
		
		<table width="530" cellpadding="0" cellspacing="0">
		<tr height="20" valign="bottom">
		<td width="220"><label>License fee margin</label></td>
		<td width="310" align="left">
		<input name="MarginNow" id="MarginNow" size="3" maxlength="7" style="font-size:12px; font-weight:normal; font-family:Arial,Helvetica,sans-serif;" value="<?=$MarginNow; ?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkMarginNowOnly();">&nbsp;<label>%</label>
		</td>
		</tr>
		<tr valign="top" height="40"><td colspan="2"><div class="greytextsmall">Enter margin guarantee such that a mediator&rsquo;s annualized fee increase won&rsquo;t exceed margin plus inflation.</div><div class="error" id="MarginNowError">Please enter a numeric value here. You may use only numbers [0-9] and the decimal point (.) character.<br></div><?php if ($_SESSION['MsgMarginNow'] != null) { echo $_SESSION['MsgMarginNow']; $_SESSION['MsgMarginNow']=null; } ?></td></tr>
		<tr height="20" valign="bottom">
		<td width="220"><label>License fee APR increase (default)</label></td>
		<td width="310" align="left">
		<input name="DefaultAPR" id="DefaultAPR" size="3" maxlength="7" style="font-size:12px; font-weight:normal; font-family:Arial,Helvetica,sans-serif;" value="<?=$DefaultAPR; ?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkDefAPROnly();">&nbsp;<label>%</label>
		</td>
		</tr>
		<tr valign="top" height="30"><td colspan="2"><div class="greytextsmall">Enter a default annual percentage rate (APR) by which license fees automatically increase upon renewal.</div><div class="error" id="DefaultAPRError">Please enter a numeric value here. You may use only numbers [0-9] and the decimal point (.) character.<br></div><?php if ($_SESSION['MsgDefaultAPR'] != null) { echo $_SESSION['MsgDefaultAPR']; $_SESSION['MsgDefaultAPR']=null; } ?></td></tr>
		</table>
		<br/>
		
		<table name="ParamsTable" id="ParamsTable" width="530" cellpadding="0" cellspacing="0">
		<?php
		$FeeIndexPointer = 0; // Keeps track of the index for successive fee elements in the $FeeTermArray fee-term value-pair array. (Note: it increments in steps of 2 to point to index 0, 2, 4, 6, etc.
		$TermIndexPointer = 1; // Keeps track of the index for successive term elements in the $FeeTermArray fee-term value-pair array. (Note: it increments in steps of 2 to point to index 1, 3, 5, 7, etc.
		for ($i=1; $i <= $NofFeeTermPairs; $i++) // When $i is 1, we generate the first row of the table; when $i is 2, we generate the second row, and so on until we've created one row for each fee-term value-pair in the $FeeTermArray (which is itself retrieved from the DB's parameters_table).
		{
		?>
			<tr height="40">
			<td width="75"><label>License fee</label></td>
			<td width="80" align="left">
			<label>$</label><input name="<?='LicFeeForTerm'.$i; ?>" id="<?='LicFeeForTerm'.$i; ?>" size="5" maxlength="12" style="font-size:12px; font-weight:normal; font-family:Arial,Helvetica,sans-serif;" value="<?=$FeeTermArray[$FeeIndexPointer]; ?>" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white'; hideAllErrors(); return checkLicFeeForTermOnly(this.value);">
			</td>
			<td width="280">
			<label>for the term:</label>&nbsp;
			<select name="<?='TermNumber'.$i;?>" id="<?='Term'.$i;?>" style="font-size:12px; font-weight:normal; font-family:Arial,Helvetica,sans-serif;">
			<option value="">&lt;&nbsp;Quantity&nbsp;&gt;</option>
		<?php
				for ($count=1; $count <= 36; $count++)
				{
		?>
				<option value="<?=$count;?>" <?php
											 switch($count)
											 {
											 	case 1:
													if ($FeeTermArray[$TermIndexPointer] == 1 || $FeeTermArray[$TermIndexPointer] == 3 || $FeeTermArray[$TermIndexPointer] == 12) echo 'SELECTED="yes"';
													break;
												case 2:
													if ($FeeTermArray[$TermIndexPointer] == 2 ||$FeeTermArray[$TermIndexPointer] == 24) echo 'SELECTED="yes"';
													break;
												case 3:
													if ($FeeTermArray[$TermIndexPointer] == 36) echo 'SELECTED="yes"';
													break;
												case 4:
													if ($FeeTermArray[$TermIndexPointer] == 4 || $FeeTermArray[$TermIndexPointer] == 48) echo 'SELECTED="yes"';
													break;
												case 5:
													if ($FeeTermArray[$TermIndexPointer] == 5 || $FeeTermArray[$TermIndexPointer] == 60) echo 'SELECTED="yes"';
													break;
												case 10:
													if ($FeeTermArray[$TermIndexPointer] == 10 || $FeeTermArray[$TermIndexPointer] == 120) echo 'SELECTED="yes"';
													break;
												default:
													if ($FeeTermArray[$TermIndexPointer] == $count && $FeeTermArray[$TermIndexPointer] != 12 && $FeeTermArray[$TermIndexPointer] != 24 && $FeeTermArray[$TermIndexPointer] != 36) echo 'SELECTED="yes"';
													break;
											 };
											 ?>><?=$count;?></option>
		<?php
				}
		?>
			</select>&nbsp;&nbsp;
			<select name="<?='TermUnit'.$i; ?>" id="<?='TermUnit'.$i; ?>" style="font-size:12px; font-weight:normal; font-family:Arial,Helvetica,sans-serif;">
			<option value="">&lt;&nbsp;Units&nbsp;&gt;</option>
			<option value="months" <?php if ($FeeTermArray[$TermIndexPointer] != 3 && $FeeTermArray[$TermIndexPointer] != 12 && $FeeTermArray[$TermIndexPointer] != 24 && $FeeTermArray[$TermIndexPointer] != 36 && $FeeTermArray[$TermIndexPointer] != 48 && $FeeTermArray[$TermIndexPointer] != 60) echo 'SELECTED="yes"'; ?>>months</option>
			<option value="quarter" <?php if ($FeeTermArray[$TermIndexPointer] == 3) echo 'SELECTED="yes"'; ?>>quarter</option>
			<option value="years" <?php if ($FeeTermArray[$TermIndexPointer] == 12 || $FeeTermArray[$TermIndexPointer] == 24 || $FeeTermArray[$TermIndexPointer] == 36 || $FeeTermArray[$TermIndexPointer] == 48 || $FeeTermArray[$TermIndexPointer] == 60 || $FeeTermArray[$TermIndexPointer] == 120) echo 'SELECTED="yes"'; ?>>years</option>
			</select>
			</td>
			</tr>
		<?php
			$FeeIndexPointer = $FeeIndexPointer + 2;  // Increment to obtain indices 2, 4, 6, etc. from $FeeTermArray for the 2nd, 3rd, 4th, etc. rows of the table.
			$TermIndexPointer = $TermIndexPointer + 2;  // Increment to obtain indices 3, 5, 7, etc. from $FeeTermArray for the 2nd, 3rd, 4th, etc. rows of the table.
			}
		?>
		</table>
		
		<table width="530" cellpadding="0" cellspacing="0">
		<tr height="30px" valign="top"><td><div class="error" id="LicFeeForTermError">Please enter a numeric license fee. You may use only numbers [0-9] and the decimal point (.) character.</div><?php if ($_SESSION['MsgLicFeeForTerm'] != null) { echo $_SESSION['MsgLicFeeForTerm']; $_SESSION['MsgLicFeeForTerm']=null; }; if ($_SESSION['MsgTermNumber'] != null) { echo $_SESSION['MsgTermNumber']; $_SESSION['MsgTermNumber']=null; }; if ($_SESSION['MsgTermUnit'] != null) { echo $_SESSION['MsgTermUnit']; $_SESSION['MsgTermUnit']=null; }; ?></td></tr>
		<tr height="60px" valign="middle"><td align="center"><input type="submit" name="UpdateParams" class="buttonstyle" value="Update Parameters" onClick="return checkForm();"></td></tr>
		</table>
		</form>
		<input type="button" value="Show More" class="buttonstylesimple" style="position: relative; left: 470px; bottom: -15px; width: 75px;" onClick="appendRow();">
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