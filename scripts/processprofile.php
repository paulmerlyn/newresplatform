<?php
// Start a session
session_start();

ob_start(); // Used in conjunction with ob_flush() [see www.tutorialized.com/view/tutorial/PHP-redirect-page/27316], this allows me to postpone issuing output to the screen relating to $UploadFile until after the header has been sent.

// Create short variable names
$LoggedOut = $_POST['LoggedOut'];
$Abort = $_POST['Abort'];
$UploadFile = $_POST['UploadFile'];
$SubmitProfile = $_POST['SubmitProfile'];
$SaveForLater = $_POST['SaveForLater'];
$Suspend = $_POST['Suspend'];
$Name = $_POST['Name'];
$Credentials = $_POST['Credentials'];
$LocaleLabel = $_POST['LocaleLabel'];
$Locations = $_POST['Locations'];  // Pending manipulation before insertion to DB
$Email = $_POST['Email'];
$UseProfessionalEmail = $_POST['UseProfessionalEmail'];
$EntityName = $_POST['EntityName'];
$PrincipalStreet = $_POST['PrincipalStreet'];
$PrincipalAddressOther = $_POST['PrincipalAddressOther'];
$City = $_POST['City'];
$State = $_POST['State'];
$Zip = $_POST['Zip'];
$Tel1 = $_POST['Tel1'];   // Tel1,2,3 and Ext are pending manipulation before insertion to DB as Telephone
$Tel2 = $_POST['Tel2'];
$Tel3 = $_POST['Tel3'];
$Ext = $_POST['Ext'];
$Fax1 = $_POST['Fax1'];   // Fax1,2,3 are pending manipulation before insertion to DB as Fax
$Fax2 = $_POST['Fax2'];
$Fax3 = $_POST['Fax3'];
$Profile = $_POST['Profile'];
$DeletePhoto = $_POST['DeletePhoto'];
$ShowHrlyRate = $_POST['ShowHrlyRate'];
$HrlyRate = $_POST['HrlyRate'];
$AdminCharge = $_POST['AdminCharge'];
$ShowAdminCharge = $_POST['ShowAdminCharge'];
$AdminChgAmt = $_POST['AdminChgAmt'];
$ShowLocChg = $_POST['ShowLocChg'];
$LocChgFrom = $_POST['LocChgFrom'];
$LocChgTo = $_POST['LocChgTo'];
$OfferPkgs = $_POST['OfferPkgs'];
$NumSessPkg1 = $_POST['NumSessPkg1'];
$PricePkg1 = $_POST['PricePkg1'];
$NumSessPkg2 = $_POST['NumSessPkg2'];
$PricePkg2 = $_POST['PricePkg2'];
$NumSessPkg3 = $_POST['NumSessPkg3'];
$PricePkg3 = $_POST['PricePkg3'];
$SessLengthHrs = $_POST['SessLengthHrs'];
$SessLengthMins = $_POST['SessLengthMins'];
$SlidingScale = $_POST['SlidingScale'];
$Increment = $_POST['Increment'];
// Note the use of the is_null tests in the next four lines. This is for extra protection to ensure ConsultationPolicy in the DB (and hence the JS array in utilitymediatorcode.js for non-suspended mediators) is a well-formed value. If $OfferFreeTelConsltn, $OfferFreeInPersConsltn, $LocChgForInPersConsltn, and $OfferConsultFeeCredit aren't already set as posted values (as they should be), I set them to default values of 'false'. Why would they not be already set? I discovered a slight risk that they wouldn't naturally get set as posted values. The risk derives from their each being posted as a radio button pair (in updateprofile.php), and it's possible that neither of the two radio buttons in each pair will be set (i.e. set to 'CHECKED' in updateprofile.php). This may be caused by unpredictable behavior of the $first_time_test_driver cookie or for another reason. (Note: I conclude there's no risk of a null posting for the AdminCharge, SlidingScale, or ServiceLevel radio buttons in updateprofile.php, so no need to test them for null then default accordingly.)
$OfferFreeTelConsltn = $_POST['OfferFreeTelConsltn']; if (is_null($OfferFreeTelConsltn)) $OfferFreeTelConsltn = 'false';
$OfferFreeInPersConsltn = $_POST['OfferFreeInPersConsltn']; if (is_null($OfferFreeInPersConsltn)) $OfferFreeInPersConsltn = 'false';
$LocChgForInPersConsltn = $_POST['LocChgForInPersConsltn']; if (is_null($LocChgForInPersConsltn)) $LocChgForInPersConsltn = 'false';
$OfferConsultFeeCredit = $_POST['OfferConsultFeeCredit']; if (is_null($OfferConsultFeeCredit)) $OfferConsultFeeCredit = 'false'; 
$PercentConsltnFeeCredit = $_POST['PercentConsltnFeeCredit'];
$CancNumber = $_POST['CancNumber']; 
$CancUnits = $_POST['CancUnits'];
$LateCancFee = $_POST['LateCancFee'];
$TelephoneMediations = $_POST['TelephoneMediations'];
$VideoConf = $_POST['VideoConf'];
$AcceptCC = $_POST['AcceptCC'];
$LevyFee = $_POST['LevyFee'];
$CCFeePercent = $_POST['CCFeePercent'];
$ServiceLevel = $_POST['ServiceLevel'];

// Note: these "path" session variables are defined either by action script democlientpaths1.php or (in situations where the check-box screen in updateprofile.php is by-passed) within updateprofile.php itself.
$imagepathshort = $_SESSION['imagepathshort'];
$dbmediatorstablename = $_SESSION['dbmediatorstablename'];
$imagepathlong = $_SESSION['imagepathlong'];
$scriptpathshort = $_SESSION['scriptpathshort'];
$pagepathshort = $_SESSION['pagepathshort'];
$ssipathlong = $_SESSION['ssipathlong'];

if ($_SESSION['SessLocale'] != 'Test Drive_US') // The 'personal' contact fields are null for test drivers, so don't bother to look in the $_POST array for them (you'll trigger an error if you do).
	{
	$StreetPersonal = $_POST['StreetPersonal'];
	$CityPersonal = $_POST['CityPersonal'];
	$StatePersonal = $_POST['StatePersonal'];
	$ZipPersonal = $_POST['ZipPersonal'];
	$Tel1Personal = $_POST['Tel1Personal'];
	$Tel2Personal = $_POST['Tel2Personal'];
	$Tel3Personal = $_POST['Tel3Personal'];
	$EmailPersonal = $_POST['EmailPersonal'];
	};

/* Set cookies so that the mediator's (including test-drive mediators) PC will load a page whose drop-down menus are ready set to this mediator's locale, localelabel, and ID. Although I could set the cookies using Javascript as I do in coremediatorfunctions.js (via the cookiefunctions.js SetCookie() Javascript function), I instead take advantage of PHP's built-in setrawcookie() function. I can easily use PHP within processprofile.php because it's a PHP file and the operation is taking place server-side rather than client-side (e.g. onchange of a drop-down menu). Also, setting the cookie via PHP doesn't depend on the user's browser having Javascript enabled. */
// Note that PHP's setcookie function encodes spaces as plus (+) signs. This would mean a PHP-encoded cookie value for, say, 'Fort Worth' would be stored as a PHP cookie as 'Fort+Worth', which would not be matchable by, say, PresetLocalesDrop() (a function in file coremediatorfunctions.js) against items in the localelabels drop-down menu. The solution is to preemptively replace the spaces with their '%20' values and then store the PHP cookies using setrawcookie() rather than setcookie(). The setrawcookie() function doesn't urlencode the cookie value.
$LocaleForPHPCookie = $_SESSION['SessLocale']; 
// The next two lines of code are included to prevent a "Warning: Cookie values can not contain any of the following ',; \t\r\n\013\014' in /home/paulme6/public_html/nrmedlic/scripts/processprofile.php on line 106" error warning message that would otherwise get triggered for either of the two locales (Canon City_CO and Coeur d'Alene_ID) when setting the PHP cookie with special characters.
$LocaleForPHPCookie = str_replace('&ntilde;','n',$LocaleForPHPCookie); // Replace n-tilde with a plain n in $LocaleLabelForPHPCookie.
$LocaleForPHPCookie = str_replace("&#039;","'",$LocaleForPHPCookie); // Replace &#039; with a plain "'" (apostrophe) in $LocaleLabelForPHPCookie.
$LocaleForPHPCookie = str_replace(' ','%20',$LocaleForPHPCookie); // Replace space with %20 in $LocaleLabelForPHPCookie.
$LocaleLabelForPHPCookie = str_replace(' ','%20',$LocaleLabel); // Replace space with %20 in $LocaleLabelForPHPCookie.
if ($_SESSION['SessLocale'] == 'Test Drive_US') $LocaleLabelForPHPCookie .= '%20(Test)'; // Append ' (Test)' to the localelabel cookie value for test drivers because it will be appended to the localelabel before insertion into the DB.

// Delete any cookies that may have been previously set by processprofile.php by setting them to a -ve expiration time.
setcookie('selectedlocale', $LocaleForPHPCookie, time()-3600, '/', '.paulmerlyn.com');
setcookie('selectedlocalelabel', $LocaleLabel, time()-3600, '/', '.paulmerlyn.com');
setcookie('selectedmediator', $_SESSION['SessID'], time()-3600, '/', '.paulmerlyn.com');

if (isset($SubmitProfile))
	{
	$expire=time()+60*60*24*182;  // Set expiration variable at 180 days (expressed in seconds).
	setrawcookie('selectedlocale',$LocaleForPHPCookie,$expire,'/','.paulmerlyn.com');
	setrawcookie('selectedlocalelabel',$LocaleLabelForPHPCookie,$expire,'/','.paulmerlyn.com');
	setrawcookie('selectedmediator',$_SESSION['SessID'],$expire,'/','.paulmerlyn.com');
	
	// Also, if the submission was by a test driver, send myself an email using PHP's simple mail() function to notify me for marketing follow up:
	if ($_SESSION['SessLocale'] == 'Test Drive_US')
		{
		$to = "paul@newresolutionmediation.com";
		$subject = "Platform Test Driver Submission";
		$message = "Hello! This is an autogenerated message via processprofile.php to notify you that a test driver on the New Resolution Platform just submitted a test drive profile.\n\n";
		$message .= "Name: ".$Name."\n";
		$message .= "Credentials: ".$Credentials."\n";
		$message .= "LocaleLabel: ".$LocaleLabel."\n";
		$message .= "Email: ".$Email."\n";
		$message .= "Locations: ".$Locations."\n";
		$message .= "City: ".$City."\n";
		$message .= "State: ".$State."\n";
		$message .= "Zip: ".$Zip."\n";
		$message .= "Telephone: ".$Tel1.".".$Tel2.".".$Tel3."\n";
		$message .= "Profile: ".$Profile."\n";
		$from = "donotreply@newresolutionmediation.com";
		$headers = "From:" . $from;
		mail($to,$subject,$message,$headers);
		}
	};
	
// These additional headers, prior to the Location: $HTTP_REFERRER statement, are to inhibit browser caching, which might prevent the user from seeing the updated photo selection inside updateprofile.php. For more info, see http://www.thesitewizard.com/archive/phptutorial2.shtml
header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
header( "Cache-Control: no-store, no-cache, must-revalidate" );
header('Cache-Control: post-check=0, pre-check=0', FALSE); 
header( "Pragma: no-cache" );

/* In order to get inside the first if statement, the user must have clicked either the 'Save For Later' button or the  'Submit Now' or the 'Upload this Photo' button in updateprofile.php. Then, if the user is a test driver, we know the user should henceforth not be treated as a first-time test driver. So set the first_time_test_driver cookie to false. Set cookie to expire in 30 days. Also note that I do this right at the beginning because PHP can only set cookies BEFORE anything else has been output to the page. This cookie is initially set in sales.php. It's used to control whether the profile form fields in updateprofile.php should be prepopulated from the DB for a non-first-time test-driver, or should be blank for a first-time test driver.
*/
if (isset($SaveForLater) || isset($SubmitProfile) || isset($UploadFile))
	{
	if ($_SESSION['SessLocale'] == 'Test Drive_US') setcookie('first_time_test_driver','false',time() + (60*60*24*30));
	}

/*
If the user clicked the 'Abort' button or the 'Log out' hyperlink in the Profile Entry form inside updateprofile.php, then set the $_SESSION['SessValidUserFlag'] to 'false' and reload updateprofile.php. Now that once this flag is set to false, the page will show the Log-In form inside updateprofile.php. Also, exit processprofile.php so that none of the user's changes in the Profile Entry form will get loaded into the database.
*/
if (isset($Abort) || isset($LoggedOut))
{
	unset($Abort);
	unset($LoggedOut);
	$_SESSION['SessValidUserFlag'] = 'false';
	if (isset($_SERVER['HTTP_REFERER'])) // Antivirus software sometimes blocks transmission of HTTP_REFERER.
		{
		header("Location: updateprofile.php"); // Go back to previous page. (Alternative to echoing the Javascript statement: history.go(-1) or history.back()
		}
	else
		{
		echo "<script type='text/javascript' language='javascript'>history.back();</script>";
		ob_flush();
		};
	exit;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Mediator Profile Update Process Script</title>
<link href="/nrcss.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="javascript" src="/scripts/windowpops.js"></script>
</head>
<body>
<?php
/*
If the user clicked the 'Upload File' form submission button to include a mediator photo file, then move the file to the 'images' folder' and rename it from 'existingname.jpg' to 'mediatorXXX.jpg' where XXX is the mediator's ID.
*/
if (isset($UploadFile))
{
if ($_FILES['MediatorPic']['error'] > 0)
	{
	echo 'Problem: ';
	switch ($_FILES['MediatorPic']['error'])
		{
		case 1: echo '<p class=\'basictext\' style=\'margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px;\'>File exceeded upload_max_file_size</p>'; break;
		case 2: echo '<p class=\'basictext\' style=\'margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px;\'>Your photo file exceeded the maximum allowable file size of 2 MB (megabyte).<br><br>You will see no advantage from using a large file size because all images are optimized for display on the Web.<br><br>Please <a style=\'font-size: 14px;\' href=\'javascript: location.href = "updateprofile.php";\'>try again</a> using a smaller file size. (We recommend a file size of no more than 100 kB (kilobytes) for best results.)</p>'; break;
		case 3: echo '<p class=\'basictext\' style=\'margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px;\'>File only partially uploaded.</p>'; break;
		case 4: echo '<p class=\'basictext\' style=\'margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px;\'>No file uploaded.</p>'; break;
		}
	exit;
	}

// Does the file have the correct MIME type?
if ($_FILES['MediatorPic']['type'] != 'image/jpg' && $_FILES['MediatorPic']['type'] != 'image/jpeg' && $_FILES['MediatorPic']['type'] != 'image/pjpeg')
	{
	echo "<p class='basictext' style='margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px;'>";
	echo 'Your photo file is an '.$_FILES['MediatorPic']['type'].' file. This is a problem.<br><br>' ;
	echo 'Your photo file must be of type .jpg or .jpeg (or .JPG or .JPEG).<br><br>';
	echo 'Please <a style=\'font-size: 14px;\' href=\'updateprofile.php\' onclick=\'javascript: window.open("updateprofile.php",\'_self\'); return false;\'>try again</a> using a .jpg or .jpeg file.';
	echo "</p>";
	$_SESSION['SessValidUserFlag'] = 'true'; // Setting this flag saves user from having to log in again when visiting updateprofile.php.
	exit;
	}

// Next, as an additional security check, examine the uploaded file's extension.
$fileExt = strrchr($_FILES['MediatorPic']['name'], '.'); 
$allowableExtensions = array(".jpg", ".JPG",".jpeg",".JPEG");
if (!in_array($fileExt, $allowableExtensions))
	{
	echo 'Your file must be of type "jpg" or "jpeg". Please <a href=\'javascript: location.href = "updateprofile.php";\'>try again</a> using a file of this type.';
	exit;
	}     

// Rename and move (via the move_uploaded_file function) the file to where we want it.
$originalFileName = $_FILES['MediatorPic']['name'];
$newFileName = 'Mediator'.$_SESSION['SessID'].'.jpg';
$upfile = $imagepathshort.$newFileName; // A reference to the new file path and name

if (is_uploaded_file($_FILES['MediatorPic']['tmp_name'])) // Thus, is_uploaded_file() is called!!
	{
	if (!move_uploaded_file($_FILES['MediatorPic']['tmp_name'], $upfile)) // Thus, move_uploaded_file() is called!!
		{
		echo 'Problem: Could not move file to destination directory.';
		exit;
		}
	}
else
	{
	echo 'Problem: Possible file upload attack. Filename: ';
	echo $_FILES['MediatorPic']['name'];
	exit;
	}

// Use third-party createThumbnail() function to reduce size of the image. Ref. http://nodstrum.com/2006/12/09/image-manipulation-using-php/ for details
function createThumbnail($img, $imgPath, $suffix, $newWidth, $newHeight, $quality)
{
  // Open and create the original image from the path/file in the argument using PHP's imagecreatefromjpeg() function.
  $original = imagecreatefromjpeg("$imgPath/$img") or die("Error Opening original");
  list($width, $height, $type, $attr) = getimagesize("$imgPath/$img");

  // Resample the image.
  $tempImg = imagecreatetruecolor($newWidth, $newHeight) or die("Cant create temp image");
  imagecopyresized($tempImg, $original, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height) or die("Cant resize copy");

  // Create the new file name. It actually takes the same name as before because I omit a suffix when calling the createThumbnail() function.
  $newNameE = explode(".", $img);
  $newName = $newNameE[0].$suffix.'.'.$newNameE[1];

  // Save the image.
  imagejpeg($tempImg, "$imgPath/$newName", $quality) or die("Unable to save image.");

  // Clean up.
  imagedestroy($original);
  imagedestroy($tempImg);
  return true;
}

$imgPath = $imagepathlong; // no forward slash.
$img = $newFileName;
$reduce = createThumbnail($img, $imgPath, "", 100, 120, 80);


// Connect to mysql
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
or die('Could not connect: ' . mysql_error());
mysql_select_db('paulme6_newresolution') or die('Could not select database');


// Insert the new file name of the user's image file into the database.
$query = "update ".$dbmediatorstablename.
		" set ImageFileName = '".$newFileName."'    
  
		where ID = '".$_SESSION['SessID']."'";

$result = mysql_query($query) or die('Query (update ImageFileName) failed: ' . mysql_error());
if (!$result) {	echo 'No image file name was added to the database.'; };

$_SESSION['SessValidUserFlag'] = 'true';
?>
<script language="javascript" type="text/javascript">
<!--
history.back();
// -->
</script>
<noscript>
<?php
if (isset($_SERVER['HTTP_REFERER'])) // Antivirus software sometimes blocks transmission of HTTP_REFERER.
	{
	header("Location: updateprofile.php"); // Go back to previous page. (Alternative to echoing the Javascript statement: history.go(-1) or history.back() in cases where user has Javascript disabled.
	}
ob_flush();
?>
</noscript>
<?php
// By forcing the $SaveForLater variable to be set here, I ensure that any data that the user had typed into the updateprofile.php form (e.g. name, address, phone number, fees) will be inserted into the database when the user clicked the 'Select/Upload this Photo' button, just as it would if the user had clicked the 'Save for Later' button. That insertion into the DB takes place further down in this processprofile.php script.
$SaveForLater = true; 
}
		 
/*
Begin PHP form validation. Note: this should be largely irrelevant whenever the user has Javascript enabled. It's second line of defense in case JS is disabled. Second note: This PHP validation should only take place if the user clicked the 'Submit Now' button on updateprofile.php (i.e. if the $SubmitProfile variable is set). PHP validation should not take place if the user clicked the 'Save For Later' button.
*/

if (isset($SubmitProfile)) 
{

// Create a session variable for the PHP form validation flag, and initialize it to 'false' i.e. assume it's valid.
$_SESSION['phpinvalidflag'] = false;

// Create session variables to hold inline error messages, and initialize them to blank.
$_SESSION['MsgName'] = null;
$_SESSION['MsgCredentials'] = null;
$_SESSION['MsgLocaleLabel'] = null;
$_SESSION['MsgEmail'] = null;
$_SESSION['MsgLocations'] = null;
$_SESSION['MsgEntityName'] = null;
$_SESSION['MsgPrincipalStreet'] = null;
$_SESSION['MsgPrincipalAddressOther'] = null;
$_SESSION['MsgCity'] = null;
$_SESSION['MsgState'] = null;
$_SESSION['MsgZip'] = null;
$_SESSION['MsgTelephone'] = null;
$_SESSION['MsgFax'] = null;
$_SESSION['MsgProfile1'] = null;
$_SESSION['MsgProfile2'] = null;
$_SESSION['MsgHrlyRate'] = null;
$_SESSION['MsgAdminChgAmt'] = null;
$_SESSION['MsgLocationCharge'] = null;
$_SESSION['MsgPackages'] = null;
$_SESSION['MsgIncrement'] = null;
$_SESSION['MsgPercentConsltnFeeCredit'] = null;
$_SESSION['MsgCancPolicy'] = null;
$_SESSION['MsgCCFeePercent'] = null;
$_SESSION['MsgStreetPersonal'] = null;
$_SESSION['MsgCityPersonal'] = null;
$_SESSION['MsgStatePersonal'] = null;
$_SESSION['MsgMsgZipPersonal'] = null;
$_SESSION['MsgTelephonePersonal'] = null;
$_SESSION['MsgEmailPersonal'] = null;

// Seek to validate $Name
$illegalCharSet = '[0-9~!@#\$%\^&\*\(\)_\+`=\|\\:";<>\?]+'; // Exclude everything except A-Z, a-z, apostrophe ('), comma, period, hyphen, &, and space. (Note that the caret ^ inside square brackets applies to exclude only the first character in PHP's implementation of regular expressions, whereas it applies to any character in Javascript's implementation of regular expressions. Hence my different approach in defining the regular expression for illegalCharSet in PHP.)
$reqdCharSet = "^[A-Z][a-z'\.]+(-| )[A-Za-z,-\. ]+";  // Names of form J. Edgar Hoover, Lou-Ann Kay, Jr., D' Angelo, etc.
if (strlen($Name) < 5 || ereg($illegalCharSet, $Name) || !ereg($reqdCharSet, $Name))
	{
	$_SESSION['MsgName'] = '<span class="errorphp">Please enter your name here. Examples: <i>Jane Doe</i> or <i>John Doe, Jr.</i><br></span>';
	$_SESSION['phpinvalidflag'] = true; 
	};

// Seek to validate $Credentials
$illegalCharSet = '[0-9~!@#\$%\^&\*\(\)_\+`=\|\\:";<>\?]+'; // Exclude everything except A-Z, a-z, comma, period, hyphen, space.(Note that the caret ^ inside square brackets applies to exclude only the first character in PHP's implementation of regular expressions, whereas it applies to any character in Javascript's implementation of regular expressions. Hence my different approach in defining the regular expression for illegalCharSet in PHP.)
if (ereg($illegalCharSet, $Credentials))
	{
	$_SESSION['MsgCredentials'] = '<span class="errorphp">If included, credentials must be in the appropriate format. Examples: <i>M.F.T., Esq., Psy.D.,</i> or <i>M.A.</i><br></span>';
	$_SESSION['phpinvalidflag'] = true; 
	};

// Seek to validate $LocaleLabel
$illegalCharSet = '[0-9~!@#\$%\^&\*_\+`=\|\\:";<>\?]+'; // Exclude everything except A-Z, a-z, comma, period, hyphen, parentheses, &, single quote (e.g. Coeur d'Alene), and space. (Note that the caret ^ inside square brackets applies to exclude only the first character in PHP's implementation of regular expressions, whereas it applies to any character in Javascript's implementation of regular expressions. Hence my different approach in defining the regular expression for illegalCharSet in PHP.)
$reqdCharSet = "^[A-Z][a-z'\.]+[(-|\/| )]*[A-Za-z,-\.\/\(\) ]+";  // Names of form initial capital (e.g. San Jose-Gilroy) followed by potentially a period (e.g. N. Chicago) or lower case. May include dashes, slashes, or spaces. Also may have ( and ) for "(Test)" appendage. Allowance of apostrophe near beginning also supports name like "D'Angelo".
if (strlen($LocaleLabel) > 28 || ereg($illegalCharSet, $LocaleLabel) || !ereg($reqdCharSet, $LocaleLabel))
	{
	$_SESSION['MsgLocaleLabel'] = '<span class="errorphp"><br>Use only letters, dash (-), slash (/), period (.), parentheses, apostrophe (\'), and space character. Also, use initial capital<br>(upper-case) letters. Examples: <i>San Francisco</i> or <i>San Francisco Bay Area</i> or <i>San Francisco-Oakland-Berkeley</i><br></span>';
	$_SESSION['phpinvalidflag'] = true; 
	};

// Seek to validate $Email
$reqdCharSet = '^[A-Za-z0-9_\-\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$';  // Simple validation from Welling/Thomson book, p125.
if ($UseProfessionalEmail != 1) // Note: I only need to validate $Email if the mediator hasn't checked the UseProfessionalEmail checkbox to decline use of the preassigned ProfessionalEmail address. When the box is checked, $Email will be set to an empty string ''.
	{
	if (!ereg($reqdCharSet, $Email))
		{
		$_SESSION['MsgEmail'] = '<span class="errorphp">The format of your email address is invalid. Please provide a valid address. Example: <i>myname@gmail.com</i><br></span>';
		$_SESSION['phpinvalidflag'] = true; 
		};
	};

// Seek to validate $Locations
$illegalCharSet = '[~!@#\$%\^&\*_\+`=\|:";<>\?]+'; // Exclude everything except A-Z, a-z, period, hyphen, slash, apostrophe, space, comma, and parentheses. Note: I also need to exclude numbers from the illegal list b/c I use numbers in the demo database for the Locale = 'Your Locale Here', where I have locations = 'Main Location', 'Satellite Location 1', 'Satellite Location 2', etc.
$reqdCharSet = '[[:alpha:]]';  // An initial capital and then at least one alphabetic character.
if (ereg($illegalCharSet, $Locations) || !ereg($reqdCharSet, $Locations))
	{
	$_SESSION['MsgLocations'] = '<span class="errorphp">Enter valid location(s). Separate locations with a comma (,). Don\'t use [return] or [enter]. Use only letters,<br>dash (-), slash (/), period, apostrophe (\'), comma, and space. Example: <i>Queens, Midtown, Upper East Side</i><br></span>';
	$_SESSION['phpinvalidflag'] = true; 
	};

// Seek to validate $EntityName
$illegalCharSet = '[~#%\^\*_\+`\|:";<>]+'; // Exclude everything except A-Z, a-z, numbers, period, hyphen, apostrophe (single quote), $, &, ?, =, !, slash, space, comma, and parentheses.
if (ereg($illegalCharSet, $EntityName))
	{
	$_SESSION['MsgEntityName'] = "<span class='errorphp'><br>Please check your format. Use only alphanumerics (A-Z, a-z, 0-9), dash (-), slash (/), period (.),<br>apostrophe ('), &, and space characters. Example: <i>Law & Mediation Office of Jane Doe & Associates</i><br></span>";
	$_SESSION['phpinvalidflag'] = true; 
	};

// Seek to validate $PrincipalStreet
$illegalCharSet = '[~%\^\*_!@\+`\|\$:";<>\?]+'; // Exclude everything except A-Z, a-z, numbers, period, hyphen, apostrophe (single quote), #, &, =, !, slash, space, comma, and parentheses.
if (ereg($illegalCharSet, $PrincipalStreet))
	{
	$_SESSION['MsgPrincipalStreet'] = "<span class='errorphp'><br>Please enter a valid address. Use only alphanumerics (A-Z, a-z, 0-9), dash (-), pound (#), period (.),<br>apostrophe ('), comma (,) and space. Examples: <i>92 N. Lincoln Way</i> or </i><i>8708 Oak St., #3</i><br></span>";
	$_SESSION['phpinvalidflag'] = true; 
	};

// Seek to validate $PrincipalAddressOther
$illegalCharSet = '[~%\^\*_!@\+`\|\$:";<>\?]+'; // Exclude everything except A-Z, a-z, numbers, apostrophe, period, hyphen, #, &, =, !, slash, space, comma, and parentheses.
if (ereg($illegalCharSet, $PrincipalAddressOther))
	{
	$_SESSION['MsgPrincipalAddressOther'] = "<span class='errorphp'><br>Use only alphanumerics (A-Z, a-z, 0-9), dash (-), pound (#), period (.), apostrophe ('),<br>comma (,) and space. Example: <i>Suite #202</i><br></span>";
	$_SESSION['phpinvalidflag'] = true; 
	};

// Seek to validate $City
$illegalCharSet = '[0-9~%\^\*_@\+`\|\$:";<>\?#!=]+'; // Exclude everything except A-Z, a-z, period, hyphen, apostrophe, &, slash, space, comma, and parentheses.
// $reqdCharSet = '[[:alpha:]]{3,}';  // At least three alphabetic character.
$reqdCharSet = "^[A-Z][a-z'\.]+[(-|\/| )]*[A-Za-z,-\.\/\(\) ]+";  // Names of form initial capital (e.g. San Jose-Gilroy) followed by potentially a period (e.g. N. Chicago) or lower case. May include dashes, slashes, or spaces. Also may have ( and ) for "(Test)" appendage. Also supports names like D'Angelo.
if (ereg($illegalCharSet, $City) || !ereg($reqdCharSet, $City))
	{
	$_SESSION['MsgCity'] = "<span class='errorphp'><br>Please enter a valid city. Use only letters (A-Z, a-z), dash (-), apostrophe ('), and space characters here.<br>Use initial capital (upper-case) letters. Examples: <i>Springfield</i> or <i>South Bend</i><br></span>";
	$_SESSION['phpinvalidflag'] = true; 
	};

// Seek to validate $State (but don't bother for test drivers b/c the State drop-down menu is disabled for them and hence the value posted to $State will be null.
if ($_SESSION['SessLocale'] != 'Test Drive_US')
	{
	if (is_null($State) || $State == '') // Test for either null or '' (blank). The State field gets assigned a null value in updateprofile.php, but the value deposited into the POST array seems to equate to '' rather than null.
		{
		$_SESSION['MsgState'] = '<span class="errorphp"><br>Please select a state from the drop-down menu!<br></span>';
		$_SESSION['phpinvalidflag'] = true; 
		};
	};
	
// Seek to validate $Zip
$illegalCharSet = '[A-Za-z~%@\^\*_\+`\|\$:";<>\?\.#&+=!,\(\)]+'; // Exclude everything except numbers and dash.
$reqdCharSet = '[[:digit:]]{5,}';  // At least five numerics.
if (ereg($illegalCharSet, $Zip) || !ereg($reqdCharSet, $Zip))
	{
	$_SESSION['MsgZip'] = '<span class="errorphp">Please enter a valid zip code. Use either a five-digit format or a five-plus-four format.<br></span>';
	$_SESSION['phpinvalidflag'] = true; 
	};

// Seek to validate $Telephone
$illegalCharSet = '[A-Za-z~%\^\*_\+`\|\$:";<>\?\.#&+=!,\(\)-]+'; // Exclude everything except numbers.
$reqdCharSet = '[[:digit:]]{3,}';  // At least three numerics.
if (strlen($Tel1) != 0 && strlen($Tel2) != 0 && strlen($Tel3) != 0) // ... But only bother to validate the telephone number if it isn't blank.
{
	if (strlen($Tel1) < 3 || strlen($Tel2) < 3 || strlen($Tel3) < 4 || ereg($illegalCharSet, $Tel1) || ereg($illegalCharSet, $Tel2) || ereg($illegalCharSet, $Tel3) || ereg($illegalCharSet, $Ext) || !ereg($reqdCharSet, $Tel1) || !ereg($reqdCharSet, $Tel2) || !ereg($reqdCharSet, $Tel3))
	{
	$_SESSION['MsgTelephone'] = '<span class="errorphp"><br>Please enter a valid telephone number. Use only numbers (0-9). Leave the extension field blank if not applicable.<br></span>';
	$_SESSION['phpinvalidflag'] = true; 
	}
};

// Seek to validate $Fax
$illegalCharSet = '[A-Za-z~%\^\*_\+`\|\$:";<>\?\.#&+=!,\(\)]+'; // Exclude everything except numbers. Note: I also need to exclude numbers from the illegal list b/c I use numbers in the demo database for the Locale = 'Your Locale Here', where I have locations = 'Main Location', 'Satellite Location 1', 'Satellite Location 2', etc.
$reqdCharSet = '[[:digit:]]{3,}';  // At least three numerics.
if (strlen($Fax1) != 0 && strlen($Fax2) != 0 && strlen($Fax3) != 0) // ... But only bother to validate the fax number if it isn't blank.
{
	if (strlen($Fax1) < 3 || strlen($Fax2) < 3 || strlen($Fax3) < 4 || ereg($illegalCharSet, $Fax1) || ereg($illegalCharSet, $Fax2) || ereg($illegalCharSet, $Fax3) || !ereg($reqdCharSet, $Fax1) || !ereg($reqdCharSet, $Fax2) || !ereg($reqdCharSet, $Fax3))
	{
	$_SESSION['MsgFax'] = '<span class="errorphp"><br>Please enter a valid fax number (or leave this item blank). Use only numbers (0-9).<br></span>';
	$_SESSION['phpinvalidflag'] = true; 
	}
};

// Seek to validate $Profile (Part 1 of 2)
$illegalCharSet = '/[\r\n\t\f\v]/'; // Reject special characters return, newline, tab, form feed, vertical tab.
if (preg_match($illegalCharSet, $Profile) || strlen($Profile) < 300 || strlen($Profile) > 900)
	{
	$_SESSION['MsgProfile1'] = '<span class="errorphp">Please remove any newline characters (\'return\' or \'enter\'). To remove them, use the [delete] or [backspace] keys.<br>Also, please ensure your text is between 300 and 900 characters (approx. 50&ndash;125 words) in length.<br></span>';
	$_SESSION['phpinvalidflag'] = true; 
	};

// Seek to validate $Profile (Part 2 of 2) (but only bother with this part if it's not a test driver.
if ($_SESSION['SessLocale'] != 'Test Drive_US')
	{
	$illegalCharSet = '/( I | i | am )+/'; // Reject if find an " I " or " i " (lower-case people) or " am " in the text.
	$reqdCharSet = '/(he| she | He | She | they | They)/'; // I use "he" instead of " he " b/c Lorem ipsum has a "he" in it, but no " he ".
	if (preg_match($illegalCharSet, $Profile) || !preg_match($reqdCharSet, $Profile))
		{
		$_SESSION['MsgProfile2'] = '<span class="errorphp">Please describe yourself in the third person. Use words like &lsquo;he&rsquo; or &lsquo;she&rsquo; and your name (e.g. Jane Doe) rather than &lsquo;I&rsquo;.<br>Example of correct form: <i><u>Jane Doe</u> earned <u>her</u> degree from Dartmouth, where <u>she</u> ...</i><br> Incorrect: <i><u>I</u> earned <u>my</u> degree from Princeton, where <u>I</u> ...</i><br></span>';
		$_SESSION['phpinvalidflag'] = true; 
		}
	};

// Seek to validate $HrlyRate
$illegalCharSet = '/[^0-9]+/'; // Reject everything that contains one or more non-digits..
$reqdCharSet = '/[0-9]{2,}/';  // At least two numerics.
if ($ShowHrlyRate)
{
	if (preg_match($illegalCharSet, $HrlyRate) || !preg_match($reqdCharSet, $HrlyRate))
	{
		$_SESSION['MsgHrlyRate'] = '<span class="errorphp"><br>To publish your hourly rate, enter a $ amount. Use numbers (0-9) only. Alternatively, uncheck the check-box.<br></span>';
		$_SESSION['phpinvalidflag'] = true; 
	}
}

// Seek to validate $AdminChgAmt
$illegalCharSet = '/[^0-9]+/'; // Reject everything that contains one or more non-digits..
$reqdCharSet = '/[0-9]{2,}/';  // At least two numerics.
if ($ShowAdminCharge)
{
	if (preg_match($illegalCharSet, $AdminChgAmt) || !preg_match($reqdCharSet, $AdminChgAmt))
	{
		$_SESSION['MsgAdminChgAmt'] = '<span class="errorphp"><br>To publish your Case Admin Charge, enter a $ amount. Use numbers (0-9) only. Alternatively, uncheck the check-box.<br></span>';
		$_SESSION['phpinvalidflag'] = true; 
	}
}

// Seek to validate $LocChgFrom and $LocChgTo
$illegalCharSet = '/[^0-9]+/'; // Reject everything that contains one or more non-digits..
$reqdCharSet = '/[0-9]{2,}/';  // At least two numerics.
if ($ShowLocChg)
{
	if (preg_match($illegalCharSet, $LocChgFrom) || !preg_match($reqdCharSet, $LocChgFrom) || preg_match($illegalCharSet, $LocChgTo) || !preg_match($reqdCharSet, $LocChgTo))
	{
	$_SESSION['MsgLocationCharge'] = '<span class="errorphp">Please check these values. Use only numbers (0-9).<br></span>';
	$_SESSION['phpinvalidflag'] = true; 
	}
};

// Seek to validate NumSessPkg1, PricePkg1, NumSessPkg2, PricePkg2, NumSessPkg3, PricePkg3, SessLengthHrs, and SessLengthMins package fields
$illegalCharSet = '/[^0-9]+/'; // Reject everything that contains one or more non-digits.
if ($OfferPkgs)
{
	if (preg_match($illegalCharSet, $NumSessPkg1) || preg_match($illegalCharSet, $NumSessPkg2) || preg_match($illegalCharSet, $NumSessPkg3) || preg_match($illegalCharSet, $PricePkg1) || preg_match($illegalCharSet, $PricePkg2) || preg_match($illegalCharSet, $PricePkg3) || preg_match($illegalCharSet, $SessLengthHrs) || preg_match($illegalCharSet, $SessLengthMins) || (strlen($NumSessPkg1) > 0 && strlen($PricePkg1)==0)|| (strlen($NumSessPkg1)==0 && strlen($PricePkg1) > 0) || (strlen($NumSessPkg2) > 0 && strlen($PricePkg2)==0)  || (strlen($NumSessPkg2)==0 && strlen($PricePkg2) > 0) || (strlen($NumSessPkg3) > 0 && strlen($PricePkg3)==0)  || (strlen($NumSessPkg3)==0 && strlen($PricePkg3) > 0) || strlen($PricePkg1)==1 || strlen($PricePkg2)==1 || strlen($PricePkg3)==1 || strlen($SessLengthHrs)==0)
	{
	$_SESSION['MsgPackages'] = '<span class="errorphp"><br>Please check your values. Use only numbers (0-9). If you offer packages, you must provide a<br>duration (hours and minutes) for the length of each session. Example: <i>hours = 2; minutes = 30<br></span>';
	$_SESSION['phpinvalidflag'] = true; 
	}
};

// Seek to validate Increment (aka Interval)
$illegalCharSet = '/[^0-9]+/'; // Reject everything that contains one or more non-digits.
$reqdCharSet = '/[0-9]+/'; // Require at least one digits
if (preg_match($illegalCharSet, $Increment) || !preg_match($reqdCharSet, $Increment) || $Increment > 60 || $Increment < 1)
	{
	$_SESSION['MsgIncrement'] = '<span class="errorphp">Please check that your value is between 0 and 60 minutes. Use only numbers (0-9). Examples: <i>5, 6, 10, 15, 20,</i> or <i>30</i><br></span>';
	$_SESSION['phpinvalidflag'] = true; 
	};

// Seek to validate credit for consultation text field, $PercentConsltnFeeCredit
$illegalCharSet = '/[^0-9]+/'; // Reject everything that contains anything other than a digit.
$reqdCharSet = '/[0-9]+/'; // Require at least one digit if validating.
if ($OfferConsultFeeCredit == 'true')
{
	if (preg_match($illegalCharSet, $PercentConsltnFeeCredit) || !preg_match($reqdCharSet, $PercentConsltnFeeCredit))
	{
		$_SESSION['MsgPercentConsltnFeeCredit'] = '<span class="errorphp"><br>Please provide a percentage using values from 1 to 100. Uncheck the check-box for zero percent. Examples: <i>50</i> or <i>100.</i><br></span>';
		$_SESSION['phpinvalidflag'] = true; 
	}
}

// Seek to validate $CancNumber, $CancUnits, and $LateCancFee fields
$illegalCharSet = '/[^0-9]+/'; // Reject everything that contains one or more non-digits.
$reqdCharSet = '/[0-9]+/'; // Require at least one digit.
if (preg_match($illegalCharSet, $CancNumber) || !preg_match($reqdCharSet, $CancNumber) || preg_match($illegalCharSet, $LateCancFee) || !preg_match($reqdCharSet, $LateCancFee) || $CancUnits == "")
	{
	$_SESSION['MsgCancPolicy'] = '<span class="errorphp"><br>Make a selection from the drop-down menu and use only numbers in the two text fields to indicate the period (number of<br>hours, days, etc.) and fee for late cancellations.<br></span>';
	$_SESSION['phpinvalidflag'] = true; 
	};

// Seek to validate CCFeePercent text field if LevyFee check box is checked
$illegalCharSet = '/[^0-9\.]+/'; // Reject everything that contains one or more that is neither a period nor a digit.
$reqdCharSet = '/[0-9]+/'; // Require at least one digit.
if ($LevyFee)
{
	if (preg_match($illegalCharSet, $CCFeePercent) || !preg_match($reqdCharSet, $CCFeePercent) || $CCFeePercent < 0 || $CCFeePercent > 20 )
	{
		$_SESSION['MsgCCFeePercent'] = '<span class="errorphp"><br>Please check this value. Use only numbers and a decimal point (.) in this field. Examples: <i>2</i> or <i>2.75</i><br></span>';
		$_SESSION['phpinvalidflag'] = true; 
	}
}

// If it isn't a test driver (for whom the Personal Contact Information fields in updateprofile.php won't show), also validate the following personal fields.
if ($_SESSION['SessLocale'] != 'Test Drive_US')
	{
	
	// Seek to validate $StreetPersonal
	$illegalCharSet = '[~%\^\*_\+`\|\$:";<>\?]+'; // Exclude everything except A-Z, a-z, numbers, period, hyphen, #, &, =, !, slash, space, comma, and parentheses.
	if (ereg($illegalCharSet, $StreetPersonal))
		{
		$_SESSION['MsgStreetPersonal'] = '<span class="errorphp"><br>Please enter a valid street address. Use only alphanumerics (A-Z, a-z, 0-9), dash (-), pound (#),<br>period (.), and space characters. Examples: <i>925 N. Washington Ave.</i> or </i><i>8708 Oak Street, #3</i><br></span>';
		$_SESSION['phpinvalidflag'] = true; 
		};

	// Seek to validate $CityPersonal
	$illegalCharSet = '[0-9~%\^\*_\+`\|\$:";<>\?#!=]+'; // Exclude everything except A-Z, a-z, period, hyphen, &, slash, space, comma, apostrophe (e.g. Coeur d'Alene), and parentheses.
	// $reqdCharSet = '[[:alpha:]]{3,}';  // At least three alphabetic character.
	$reqdCharSet = '^[A-Z][a-z\.]+[(-|\/| )]*[A-Za-z,-\.\/\(\) ]+';  // Names of form initial capital (e.g. San Jose-Gilroy) followed by potentially a period (e.g. N. Chicago) or lower case. May include dashes, slashes, or spaces. Also may have ( and ) for "(Test)" appendage.
	if (ereg($illegalCharSet, $CityPersonal) || !ereg($reqdCharSet, $CityPersonal))
		{
		$_SESSION['MsgCityPersonal'] = '<span class="errorphp"><br>Please enter a valid city. Use only letters, period (.), dash (-), and space characters here.<br>Use initial capital (upper-case) letters. Examples: <i>Springfield</i> or <i>St. Cloud</i><br></span>';
		$_SESSION['phpinvalidflag'] = true; 
		};

	// Seek to validate $StatePersonal
	if (is_null($StatePersonal) || $StatePersonal == '') // Test for either null or '' (blank). The State field gets assigned a null value in updateprofile.php, but the value deposited into the POST array seems to equate to '' rather than null.
		{
		$_SESSION['MsgStatePersonal'] = '<span class="errorphp"><br>Please select a state from the drop-down menu.<br></span>';
		$_SESSION['phpinvalidflag'] = true; 
		};
	
	// Seek to validate $ZipPersonal
	$illegalCharSet = '[A-Za-z~%@\^\*_\+`\|\$:";<>\?\.#&+=!,\(\)]+'; // Exclude everything except numbers and dash.
	$reqdCharSet = '[[:digit:]]{5,}';  // At least five numerics.
	if (ereg($illegalCharSet, $ZipPersonal) || !ereg($reqdCharSet, $ZipPersonal))
		{
		$_SESSION['MsgZipPersonal'] = '<span class="errorphp">Please enter a valid zip code. Use either a five-digit format or a five-plus-four format.<br></span>';
		$_SESSION['phpinvalidflag'] = true; 
		};

	// Seek to validate $TelephonePersonal
	$illegalCharSet = '[A-Za-z~%\^\*_\+`\|\$:";<>\?\.#&+=!,\(\)-]+'; // Exclude everything except numbers.
	$reqdCharSet = '[[:digit:]]{3,}';  // At least three numerics.
	if (strlen($Tel1Personal) != 0 && strlen($Tel2Personal) != 0 && strlen($Tel3Personal) != 0) // ... But only bother to validate the telephone number if it isn't blank.
	{
		if (strlen($Tel1Personal) < 3 || strlen($Tel2Personal) < 3 || strlen($Tel3Personal) < 4 || ereg($illegalCharSet, $Tel1Personal) || ereg($illegalCharSet, $Tel2Personal) || ereg($illegalCharSet, $Tel3Personal) || !ereg($reqdCharSet, $Tel1Personal) || !ereg($reqdCharSet, $Tel2Personal) || !ereg($reqdCharSet, $Tel3Personal))
		{
		$_SESSION['MsgTelephonePersonal'] = '<span class="errorphp"><br>Please enter a valid telephone number. Use only numbers (0-9).<br></span>';
		$_SESSION['phpinvalidflag'] = true; 
		}
	};
	
	// Seek to validate $EmailPersonal
	$reqdCharSet = '^[A-Za-z0-9_\-\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$';  // Simple validation from Welling/Thomson book, p125.
	if (!ereg($reqdCharSet, $EmailPersonal))
		{
		$_SESSION['MsgEmailPersonal'] = '<span class="errorphp">The format of your email address is invalid. Please provide a valid address. Example: <i>myname@gmail.com</i><br></span>';
		$_SESSION['phpinvalidflag'] = true; 
		};

	} // End of validating Personal Contact Information fields (for non-test drivers only).


//Now go back to the previous page (updateprofile.php) and show any PHP inline validation error messages if the $_SESSION['phpinvalidflag'] has been set to true. ... otherwise, proceed to update the database with the user's form data.
//Note: Remember that output buffering (ob_start()) has been turned on, so only headers are sent until the code encounters either an ob_flush() or an ob_end_flush() statement, at which point the contents of the output buffer are output to the screen. Also note that ob_flush() differs from ob_end_flush in that the latter not only flushes but also turns off output buffering (i.e. cancels a prior ob_start() statement). Final note: the output buffer is always flushed at the end of the page (e.g. at the bottom of this processprofile.php script), even if there isn't an ob_flush() or ob_end_flush() statement.
if ($_SESSION['phpinvalidflag'])
	{
	?>
	<script type='text/javascript' language='javascript'><!-- history.back(); //--></script>
	<noscript>
	<?php
	if (isset($_SERVER['HTTP_REFERER']))
		header("Location: ".$_SERVER['HTTP_REFERER']); // Go back to previous page. (Similar to echoing the Javascript statement: history.go(-1) or history.back() except I think $_SERVER['HTTP_REFERER'] reloads the page, which causes freshly entered values in the updateprofile.php form to get overwritten by values retrieved from the DB. So the javascript 'history.back()' method is more suitable. However, if Javascript is enabled, php form validation is moot. And if Javascript is disabled, then the javascript 'history.back()' method won't work anyway.
	?>
	</noscript>
	<?php
	exit;	
	}

} // End of PHP form validation


/*
If the user clicked the 'Save For Later' form submission button (but not the 'Upload File' button, for which the processprofile.php's code does force $SaveForLater to be set) rather than the 'Submit Now' button in script updateprofile.php, then override whatever value the user gave to the Suspend item, forcing it to take the value of '1' i.e. true. This value will then be updated into the database
*/
if (isset($SaveForLater) && !isset($UploadFile)) { $Suspend = 1; };

/* Prevent cross-site scripting via htmlspecialchars on this user-entry form field */
$Name = htmlspecialchars($Name, ENT_QUOTES); // The ENT_QUOTES parameter is necessary to handle names with apostrophes e.g. Jean D'Tocqueville.

/* Prevent cross-site scripting via htmlspecialchars on this user-entry form field */
$Credentials = htmlspecialchars($Credentials, ENT_QUOTES);

/*
Manipulate user submission into $LocaleLabel if $Locale == 'Test Drive_US'. I basically want to append the words "(Test)" to the label when it's a test drive so it appears explicitly as a test drive in the locale label drop-down menus on the web site. I do this b/c I don't want test drivers typing in, say, a rude word as their localelabel and having that go on the live site without its being clearly tagged via a suffix as a test drive locale label. To see if the locale equals 'Test Drive_US', I need to look at the $_SESSION['SessLocale'] session variable created in updateprofile.php, because the locale is not passed into the $_POST array from that script. (The locale is actually defined when the Administrator sets up the mediator profile via admin1.php and admin2.php.) Nothing gets appended to $LocaleLabel for non-test-drive locales.
*/
if ($_SESSION['SessLocale'] == 'Test Drive_US') 
	{
	$LocaleLabel = str_replace(' (Test)', '', $LocaleLabel); // First strip the $LocaleLabel of the ' (Test)' string if one is to be found. (The appendage might have been previously applied.) If you don't do this, you'll find yourself adding one appendage onto an existing appendage, and so on.
	$LocaleLabel = $LocaleLabel.' (Test)'; // Apply the appendage.
	}
$LocaleLabel = htmlspecialchars($LocaleLabel, ENT_QUOTES); // Impedes cross-site scripting. (Note that $LocaleLabel may have a single-quote in it for a locale sa Coeur d'Alene.)

/* Prevent cross-site scripting via htmlspecialchars on this user-entry form field. Note that since $ProfessionalEmail doesn't get placed into the DB by this script, there is no need to prepare it using htmlspecialchars or addslashes, etc. */
$Email = htmlspecialchars($Email, ENT_QUOTES);

/*
Manipulate user submission into $Locations textarea so it comports with following format for storage in database: "Location1", "Location2, ... "Location n"
*/
// First use PHP's trim function to remove white space from beginning and end of $Locations ...
$Locations = trim($Locations); // ... then remove any remaining line breaks from $Locations before doing any further manipulation. Note the need for 'if' statements b/c strpos() returns a value of 'false' if the needle (\r or \n) is not in the haystack ($Locations).
if (strpos($Locations, "\r") !== false) $posr = strpos($Locations, "\r"); else $posr = strlen($Locations); // If the \r character is in the string then identify its position, else set $posr to a safe maximum such as the length of the string.
if (strpos($Locations, "\n") !== false) $posn = strpos($Locations, "\n"); else $posn = strlen($Locations); // Similarly for \n.
$pos = min($posr, $posn);  // Identify which character occurs first 
$Locations = substr($Locations,0,$pos); // Retain only that portion of $Locations from the beginning to the first occurence of either a \r or a \n.

// Now break $Locations up into an array of elements.
$myArray = explode(',',$Locations);
function trim_item(&$item) // This function, together with array_walk, trims white space and adds a " before and after each element in $myArray. Note use of & reference to $item.
	{
	$item = trim($item);
	$item = '"'.$item.'"';
	}
array_walk($myArray, 'trim_item');

// Remove any empty elements from $myArray which would exist if the user typed something like this in the Locations field in updateprofile.php: Elk Grove, Auburn,,,Sacramento
foreach($myArray as $key => $value)
	{ 
  	if ($value == '""') 
  		{ 
    	unset($myArray[$key]); 
		} 
	} 
$myArray = array_values($myArray);

$Locations = implode(',', $myArray); // Join array elements, separated by commas.
$Locations = htmlspecialchars($Locations, ENT_NOQUOTES); // Impedes cross-site scripting. Use ENT_NOQUOTES parameter to prevent substitution of the double-quote (") characters that I introduce before and after each location. 
$Locations = str_replace("'", '&#039;', $Locations);

/* Prevent cross-site scripting via htmlspecialchars on this user-entry form field */
$EntityName = htmlspecialchars($EntityName, ENT_COMPAT);

/* Prevent cross-site scripting via htmlspecialchars on this user-entry form field */
$PrincipalStreet = htmlspecialchars($PrincipalStreet, ENT_COMPAT);

/* Prevent cross-site scripting via htmlspecialchars on this user-entry form field */
$PrincipalAddressOther = htmlspecialchars($PrincipalAddressOther, ENT_COMPAT);

/* Prevent cross-site scripting via htmlspecialchars on this user-entry form field */
$City = htmlspecialchars($City, ENT_COMPAT);

/*
Manipulate user submission into $State drop-down menu. For virtually all cases, State = $State. However, when $_SESSION['SessLocale'] == 'Test Drive_US' (i.e. this is the test-drive-type profile) for, the drop-down menu in updateprofile.php is disabled (greyed out). Once disabled, this element can no longer pass a value into the POST array (i.e. into $_POST['State']), even though the greyed out drop-down menu would then show 'US' as the selected value. The data manipulation that must now happen here is to force the $State value to be 'US' rather than an empty (null) value when session variable $_SESSION['SessLocale'] == 'Test Drive_US'. In all other cases, there's no need to alter the $State value from its current value that's taken directly from $_POST['State'].
*/
if ($_SESSION['SessLocale'] == 'Test Drive_US') $State = 'US';

/* Prevent cross-site scripting via htmlspecialchars on this user-entry form field */
$Zip = htmlspecialchars($Zip, ENT_QUOTES);

/*
Manipulate user submission into $Tel1,2,3,Ext and $Fax1,2,3 so they comport with following format for storage in database: 415.378.7003 Ext. 12345 for telephones, and 415.366.3005 or "" for faxes.
*/
if ($Tel3 != '') $Telephone = $Tel1.'.'.$Tel2.'.'.$Tel3; // Minor if-test to avoid ugly prepopulation of Tel1 text field with .. in updateprofile.php profile form.
if (strlen($Ext) > 0) $Telephone = $Telephone.'  Ext. '.$Ext;  // Append extension if one exists
if ($Name == 'Your Name') $Telephone = '[your number here]'; // Special exception for the telephone number of the "Your Locale Here" mediator in the database.
$Telephone = htmlspecialchars($Telephone, ENT_QUOTES); // Impedes cross-site scripting.
if ((strlen($Fax1) == 3) && (strlen($Fax2) == 3) && (strlen($Fax3) == 4)) { $Fax = $Fax1.'.'.$Fax2.'.'.$Fax3; } else { $Fax = ''; };
$Fax = htmlspecialchars($Fax, ENT_QUOTES); // Impedes cross-site scripting.

/*
First use PHP's trim function to remove white space from beginning and end of $Profile ...
*/
$Profile = trim($Profile); // ... then remove any remaining line breaks from $Profile before inserting it into DB. Note the need for 'if' statements b/c strpos() returns a value of 'false' if the needle (\r or \n) is not in the haystack ($Profile).
if (strpos($Profile, "\r") !== false) $posr = strpos($Profile, "\r"); else $posr = strlen($Profile); // If the \r character is in the string then identify its position, else set $posr to a safe maximum such as the length of the string.
if (strpos($Profile, "\n") !== false) $posn = strpos($Profile, "\n"); else $posn = strlen($Profile); // Similarly for \n.
$pos = min($posr, $posn);  // Identify which character occurs first 
$Profile = substr($Profile,0,$pos); // Retain only that portion of $Profile from the beginning to the first occurence of either a \r or a \n.
$Profile = htmlspecialchars($Profile, ENT_QUOTES); // Use htmlspecialchars() to convert dangerous characters such as quotation marks and < or > (which can be used maliciously to embed, say, <script> tags) into harmless &quot;, &lt;, &gt;, etc. Note that ENT_COMPAT parameter converts double quotes only but leaves single-quotes alone, whereas ENT_QUOTES converts single and double. (Double quotes inside $Profile would break the mediatorProfile[] array when placed into utilitymediatordata.js, whereas single quotes are not a problem.) I could have alternatively used htmlentities(), but I read that htmlspecialchars() is a bit faster, and htmlentities() makes unnecessarily broad conversions for my purposes, which are served fully be htmlspecialchars().

/*
Manipulate user submission via $ShowHrlyRate and $HrlyRate into $HourlyRate to comport with following format for storage in database: true,180
*/
if ($ShowHrlyRate && is_numeric($HrlyRate)) $HourlyRate = $ShowHrlyRate.','.$HrlyRate;
elseif ($HrlyRate != null) $HourlyRate = 'false'.','.$HrlyRate;
else $HourlyRate = "false,''";
$HourlyRate = htmlspecialchars($HourlyRate, ENT_NOQUOTES); // Impedes cross-site scripting. Note: must use the 'NOQUOTES' parameter of the htmlspecialchars() function here to prevent the double-quote marks (") -- which are supposed to appear in the utilitymediatordata.js array for the mediatorHourlyRate[] array when the user doesn't choose to publish his/her hourly rate -- from getting converted into &quot; form.

/*
Manipulate user submission via $ShowAdminCharge and $AdminChgAmt into $AdminChargeDetails to comport with following format for storage in database: true,100
*/
if ($ShowAdminCharge && is_numeric($AdminChgAmt)) $AdminChargeDetails = $ShowAdminCharge.','.$AdminChgAmt;
elseif ($AdminChgAmt != null) $AdminChargeDetails = 'false'.','.$AdminChgAmt;
else $AdminChargeDetails = "false,''";
$AdminChargeDetails = htmlspecialchars($AdminChargeDetails, ENT_NOQUOTES); // Impedes cross-site scripting. Note: must use the 'NOQUOTES' parameter of the htmlspecialchars() function here to prevent the double-quote marks (") -- which are supposed to appear in the utilitymediatordata.js array for the mediatorAdminChargeDetails[] array when the user doesn't choose to publish his/her Admin Charge -- from getting converted into &quot; form.

/*
Manipulate user submission via $ShowLocChg, $LocChgFrom, and $LocChgTo into $LocationCharge to comport with following format for storage in database: true,90,130 or false,"",""
*/
if ($ShowLocChg && (is_numeric($LocChgFrom) || $LocChgFrom == '' || $LocChgFrom == null) && (is_numeric($LocChgTo) || $LocChgTo == '' || $LocChgTo == null)) 
{
	if ($LocChgFrom == '' && $LocChgTo == '') $LocationCharge = 'false,"",""'; // Blank both 'From' and 'To'
	elseif ($LocChgFrom == '') $LocationCharge = $ShowLocChg.','.$LocChgTo.','.$LocChgTo; // Blank 'From'
	elseif ($LocChgTo == '') $LocationCharge = $ShowLocChg.','.$LocChgFrom.','.$LocChgFrom; // Blank 'To'
	elseif ($LocChgFrom > $LocChgTo) $LocationCharge = $ShowLocChg.','.$LocChgTo.','.$LocChgFrom; // Backwards case
	else $LocationCharge = $ShowLocChg.','.$LocChgFrom.','.$LocChgTo; // Regular case
	$LocationCharge = htmlspecialchars($LocationCharge, ENT_QUOTES); // Impedes cross-site scripting.
}
else 
{
$LocationCharge = 'false,"",""';
};


/*
Manipulate user submission via $OfferPkgs, $NumSessPkg1, $PricePkg1, $NumSessPkg2, $PricePkg2, $NumSessPkg3, $PricePkg3, $SessLengthHrs, and $SessLengthMins into $Packages to comport with following format for storage in database: true,3,1200,4,1900,"","",2,30 [example is for a three-session package costing $1200, a four-session package costing $1900, and each session of length 2 hours and 30 mins].
*/
$Packages = 'false,"","","","","","","",""'; // Initialize to assume all is false and null.
if ($OfferPkgs && is_numeric($SessLengthHrs) && (is_numeric($SessLengthMins) || $SessLengthMins == null || $SessLengthMins == ''))  // Progressively redefine elements of $myArray according to user submissions if check-box is checked and session length hours is a valid number and the minutes is either a number or blank or null.
	{
	$myArray = explode(',', $Packages); // This array will hold elements of $Packages.
	$myArray[0] = $OfferPkgs; // This element must be 'true' b/c we are inside the 'if' statement.
	if (($NumSessPkg1 != null) && ($PricePkg1 != null)) { $myArray[1] = $NumSessPkg1; $myArray[2] = $PricePkg1; };
	if (($NumSessPkg2 != null) && ($PricePkg2 != null)) { $myArray[3] = $NumSessPkg2; $myArray[4] = $PricePkg2; };
	if (($NumSessPkg3 != null) && ($PricePkg3 != null)) { $myArray[5] = $NumSessPkg3; $myArray[6] = $PricePkg3; };
	if (($SessLengthHrs != null) && ($SessLengthHrs != '')) $myArray[7] = $SessLengthHrs; // Only override the initalized value of "" if the SessLengthHrs field was not blank or null.
	if ($SessLengthMins != null && $SessLengthMins != '' && $SessLengthMins != 0) $myArray[8] = $SessLengthMins; // Only override the initialized value of "" if the SessLengthMins field was not blank, null, or 0.
	$Packages = implode(',', $myArray);
	$Packages = htmlspecialchars($Packages, ENT_NOQUOTES); // Impedes cross-site scripting. Note: must use the 'NOQUOTES' parameter of the htmlspecialchars() function here to prevent the double-quote marks (") -- which are supposed to appear in the utilitymediatordata.js array for the mediatorPackages[] array when the user left particular package fields blank -- from getting converted into &quot; form. 
	}
	
/* Prevent cross-site scripting via htmlspecialchars on this user-entry form field. Set $Increment to a 'default' value of "10" if the user-supplied value is not numeric. */
if (is_numeric($Increment)) $Increment = htmlspecialchars($Increment, ENT_QUOTES);
else $Increment = "10";

/*
Manipulate user submission via $OfferFreeTelConsltn, $OfferFreeInPersConsltn, $LocChgForInPersConsltn, $OfferConsultFeeCredit and $PercentConsltnFeeCredit to comport with following format for storage in database as $ConsultationPolicy: true,false,true,true,75 or false,false,true,false,"". Note that $OfferConsultFeeCredit and $PercentConsltnFeeCredit both get set to 'false' if either the OfferConsultFeeCredit radio button is set to 'No' (i.e. $OfferConsultFeeCredit is false) or the user enters a non-numeric value for PercentConsltnFeeCredit.
*/
if ($OfferConsultFeeCredit == 'true' && is_numeric($PercentConsltnFeeCredit)) 
	{
	$ConsultationPolicy = $OfferFreeTelConsltn.','.$OfferFreeInPersConsltn.','.$LocChgForInPersConsltn.','.$OfferConsultFeeCredit.','.$PercentConsltnFeeCredit; 
	}
else
	{
	$ConsultationPolicy = $OfferFreeTelConsltn.','.$OfferFreeInPersConsltn.','.$LocChgForInPersConsltn.',false,""';
	};
$ConsultationPolicy = htmlspecialchars($ConsultationPolicy, ENT_NOQUOTES); // Impedes cross-site scripting. Note: 'NOQUOTES'

/*
Manipulate user submission via $CancNumber, $CancUnits, and $LateCancFee to comport with following format for storage in database as $CancellationPolicy: e.g. "24","hours","100" or "5","business days","200". Note that for a non-numeric $CancNumber or $LateCancFee, the $CancellationPolicy takes a default value of 3 business days and $100 fee. Note also that, first, client-side Javascript form validation and, second, server-side PHP form validation should ensure that this default clause is never invoked. I just put it there for extra safety in case form validation ever fails. Also note that PHP form validation doesn't happen when user clicks the 'Save For Later' button in updateprofile.php.
*/
// $CancellationPolicy = '"'.$CancNumber.'","'.$CancUnits.'","'.$LateCancFee.'"';
if (!is_numeric($CancNumber) || $CancUnits == '') 
	{
	$CancNumber = 3; // Set a default because the CancNumber-CancUnits value pair was not sensible.
	$CancUnits = "business days"; // Set a default because the CancNumber-CancUnits value pair was not sensible.
	};
if (!is_numeric($LateCancFee)) $LateCancFee = 100; // Set a default if an appropriate value were not set by the user.
$CancellationPolicy = '"'.$CancNumber.'","'.$CancUnits.'","'.$LateCancFee.'"';
$CancellationPolicy = htmlspecialchars($CancellationPolicy, ENT_NOQUOTES); // Impedes cross-site scripting. Note: 'NOQUOTES'

/*
Manipulate user submission via $AcceptCC, $LevyFee, and $CCFeePercent into $CardPolicy to comport with following format for storage in database: true,true,"180" or true,false,""
*/
if ($AcceptCC) 
	{
	if (substr($CCFeePercent,0,1)=='.')  $CCFeePercent = '0'.$CCFeePercent; // Prepend a zero if $CCFeePercent began with a decimal point.
	if (substr($CCFeePercent,-1)=='.')  $CCFeePercent = $CCFeePercent.'0'; // Append a zero if $CCFeePercent ended with a decimal point.
	if ($LevyFee && is_numeric($CCFeePercent) && $CCFeePercent > 0) $CardPolicy = 'true,true,"'.$CCFeePercent.'"'; else $CardPolicy = 'true,false,""';
	}
else $CardPolicy = 'false,false,""';
$CardPolicy = htmlspecialchars($CardPolicy, ENT_NOQUOTES); // Impedes cross-site scripting. Note: 'NOQUOTES'

/* Prevent cross-site scripting via htmlspecialchars on this user-entry form field */
/* Note use of ternary operator - see www.scottklarr.com/topic/76/ternary-php-short-hand-ifelse-statement/ */
$StreetPersonal = ($_SESSION['SessLocale'] == 'Test Drive_US') ? null : htmlspecialchars($StreetPersonal, ENT_COMPAT);

/* Prevent cross-site scripting via htmlspecialchars on this user-entry form field */
//$CityPersonal = htmlspecialchars($CityPersonal, ENT_COMPAT);
$CityPersonal = ($_SESSION['SessLocale'] == 'Test Drive_US') ? null : htmlspecialchars($CityPersonal, ENT_COMPAT);

/*
Manipulate user submission into $StatePersonal drop-down menu. For all cases except when $_SESSION['SessLocale'] == 'Test Drive_US', StatePersonal = $StatePersonal.
*/
if ($_SESSION['SessLocale'] == 'Test Drive_US') $StatePersonal = 'US';

/* Prevent cross-site scripting via htmlspecialchars on this user-entry form field */
//$ZipPersonal = htmlspecialchars($ZipPersonal, ENT_QUOTES);
$ZipPersonal = ($_SESSION['SessLocale'] == 'Test Drive_US') ? null : htmlspecialchars($ZipPersonal, ENT_COMPAT);

/*
Manipulate user submission into $Tel1Personal, Tel2Personal, Tel3Personal so they comport with following format for storage in database: 415.378.7003.
*/
if ($Tel3Personal != '') $TelephonePersonal = $Tel1Personal.'.'.$Tel2Personal.'.'.$Tel3Personal; // Minor if-test to avoid ugly prepopulation of Tel1 text field with .. in updateprofile.php profile form.
$TelephonePersonal = htmlspecialchars($TelephonePersonal, ENT_QUOTES); // Impedes cross-site scripting.

/* Prevent cross-site scripting via htmlspecialchars on this user-entry form field */
//$EmailPersonal = htmlspecialchars($EmailPersonal, ENT_QUOTES);
$EmailPersonal = ($_SESSION['SessLocale'] == 'Test Drive_US') ? null : htmlspecialchars($EmailPersonal, ENT_COMPAT);

if (!get_magic_quotes_gpc())
{
	$Name = addslashes($Name);
	$Credentials = addslashes($Credentials);
	$LocaleLabel = addslashes($LocaleLabel);
	$Email = addslashes($Email);
	$Location = addslashes($Location);
	$EntityName = addslashes($EntityName);
	$PrincipalStreet = addslashes($PrincipalStreet);
	$PrincipalAddressOther = addslashes($PrincipalAddressOther);
	$City = addslashes($City);
	$State = addslashes($State);
	$Zip = addslashes($Zip);
	$Telephone = addslashes($Telephone);
	$Fax = addslashes($Fax);
	$Profile = addslashes($Profile);
	$HourlyRate = addslashes($HourlyRate);
	$AdminChargeDetails = addslashes($AdminChargeDetails);
	$LocationCharge = addslashes($LocationCharge);
	$ConsultationPolicy = addslashes($ConsultationPolicy);
	$CancellationPolicy = addslashes($CancellationPolicy);
	$CCFeePercent = addslashes($CCFeePercent);
	$StreetPersonal = addslashes($StreetPersonal);
	$CityPersonal = addslashes($CityPersonal);
	$StatePersonal = addslashes($StatePersonal);
	$ZipPersonal= addslashes($ZipPersonal);
	$TelephonePersonal = addslashes($TelephonePersonal);
	$EmailPersonal = addslashes($EmailPersonal);
}	

// Connect to mysql
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
or die('Could not connect: ' . mysql_error());
mysql_select_db('paulme6_newresolution') or die('Could not select database');


// First insert the ImageFileName of 'BlankSubForBrokenImage.jpg' if the user had checked the 'DeletePhoto' check-box in the updateprofile.php form. (Note: If the user didn't check this box, then there's no need to insert anything into the DB for ImageFileName. Any changes that the user had wanted to make to the existing image could have been made when the user clicked the 'Select/Upload This Photo' button, which is processed separately above in processprofile.php.)
if ($DeletePhoto)
{
$newFileName = 'Mediator'.$_SESSION['SessID'].'.jpg';

$photofile = imagecreatefromjpeg('/home/paulme6/public_html/nrmedlic/images/BlankSubForBrokenImage.jpg'); // Create a new jpg image from the path/file in the argument and assign it to resource $photofile.

imagejpeg($photofile, $imagepathlong.$newFileName) or die("Cannot save the blank image"); // Create a jpg file from the $photofile image resource and save it to $newFileName.

imagedestroy($photofile); // Clean up

$query = "update ".$dbmediatorstablename.
		" set ImageFileName = '".$newFileName."'    
		where ID = '".$_SESSION['SessID']."'";

$result = mysql_query($query) or die('Query (update) failed: ' . mysql_error());
if (!$result) 
	{
	echo 'Unable to overwrite in the database the ImageFileName with a blank file = BlankSubForBrokenImage.jpg.';
	}
}; // end of the DeletePhoto operation.

// ... then insert the rest of the user's data into the database. Note that ProfessionalEmail doesn't get placed in the DB because I don't allow users to define that. I do it manually.
$query = "update ".$dbmediatorstablename.
		" set Suspend = '".$Suspend."', Name = '".$Name."', Credentials = '".$Credentials."', LocaleLabel = '".$LocaleLabel."', Email = '".$Email."', UseProfessionalEmail = '".$UseProfessionalEmail."', Locations = '".$Locations."', EntityName = '".$EntityName."', PrincipalStreet = '".$PrincipalStreet."', PrincipalAddressOther = '".$PrincipalAddressOther."', City = '".$City."', State = '".$State."', Zip = '".$Zip."', Telephone = '".$Telephone."', Fax = '".$Fax."', Profile = '".$Profile."', HourlyRate = '".$HourlyRate."', AdminCharge = '".$AdminCharge."', AdminChargeDetails = '".$AdminChargeDetails."', LocationCharge = '".$LocationCharge."', Packages = '".$Packages."', SlidingScale = '".$SlidingScale."', Increment = '".$Increment."', ConsultationPolicy = '".$ConsultationPolicy."', CancellationPolicy = '".$CancellationPolicy."', TelephoneMediations = '".$TelephoneMediations."', VideoConf = '".$VideoConf."', CardPolicy = '".$CardPolicy."', ServiceLevel = '".$ServiceLevel."', StreetPersonal = '".$StreetPersonal."', CityPersonal = '".$CityPersonal."', StatePersonal = '".$StatePersonal."', ZipPersonal = '".$ZipPersonal."', TelephonePersonal = '".$TelephonePersonal."', EmailPersonal = '".$EmailPersonal."'    
  
		where ID = '".$_SESSION['SessID']."'";

$result = mysql_query($query) or die('Query (update) failed: ' . mysql_error().' and the query string was: '.$query);
if (!$result) 
{
	echo 'No mediators were added to the database.';
}
else
{
	// The mediator's profile has been updated.
	
	// As a security measure to help guard against spurious deletions from mediators_table (or mediators_table_demo) on re-processing of processprofile.php without first going through updateprofile.php, unset the $_SESSION['SessID'), $_SESSION['SessUsername', and $_SESSION['SessPassword'] session variables. Also for security, force user to log in again when sent back to updateprofile.php (unless user got to processprofile.php via the $UploadFile button, in which case there is no reason to unset those session variables.
	if (!isset($UploadFile))
		{
		unset($_SESSION['SessValidUserFlag']);
		unset($_SESSION['SessID']);
		unset($_SESSION['SessUsername']);
		unset($_SESSION['SessPassword']);
		}
	else
		{
		unset($UploadFile);
		};
	if (isset($SaveForLater)) { unset($SaveForLater); echo "<p class='basictext' style='margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px;'>The changes you have made to your profile have been saved.</p><p class='basictext' style='margin-left: 50px; margin-right: 50px; font-size: 14px;'>Please note: The profile will not currently appear on the live web site.</p><p class='basictext' style='margin-left: 50px; margin-right: 50px; font-size: 14px;'>You may <a style='font-size: 14px;' href='updateprofile.php'>log in</a> any time to complete your profile and submit it to the live web site by clicking the 'Submit Now' button.</p>";};
	if (isset($SubmitProfile))
		{
		unset($SubmitProfile);
		if ($_SESSION['SessLocale'] == 'Test Drive_US') 
			{
			echo "<p class='basictext' style='margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px;'>Your profile has been submitted successfully.</p><p class='basictext' style='margin-left: 50px; margin-right: 50px; font-size: 14px;'>Please click <a target='_self' style='font-size: 14px;' href='/testdriverguide.shtml' onClick=\"poptastic4('/testdriverguide.shtml'); return false;\">here</a> to see your inclusion in the New Resolution web site. Or click <a style='font-size: 14px;' href='updateprofile.php'>here</a> to log in again and make further updates.</p>";
			}
		else
			{
			if ($Suspend == 0) echo "<p class='basictext' style='margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px;'>Your profile has been submitted successfully.</p><p class='basictext' style='margin-left: 50px; margin-right: 50px; font-size: 14px;'>Please visit the <a style='font-size: 14px;' href='".$pagepathshort."index.shtml'>New Resolution</a> web site to review the changes you have submitted.</p><p class='basictext' style='margin-left: 50px; margin-right: 50px; font-size: 14px;'>Or <a style='font-size: 14px;' href='updateprofile.php'>log in</a> to make further changes.</p>"; 
			if ($Suspend == 1) echo "<p class='basictext' style='margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px;'>Your changes have been recorded.</p><p class='basictext' style='margin-left: 50px; margin-right: 50px; font-size: 14px;'>You have suspended your profile from appearance on the site. You may unsuspend your profile at any time by <a style='font-size: 14px;' href='updateprofile.php'>logging in</a> and unchecking the &lsquo;Suspend&rsquo; box.</p><p class='basictext' style='margin-left: 50px; margin-right: 50px; font-size: 14px;'>In the meantime, please visit the <a style='font-size: 14px;' href='".$pagepathshort."index.shtml'>New Resolution</a> site to ensure your profile is suspended."; 
			}
		};
}

require('../ssi/createutilitymediatordata.php'); // Include the createutilitymediatordata.php file, which creates, for either the client level (stored in /scripts/) or the demo level (stored in /demo/scripts/), the javascript mediator data file, utilitymediatordata.js. I use an SSI include here for modularity because the same code block is used a few times in admin6.php.

require('../ssi/createlocalelocationincludes.php'); // Include the createlocalelocationincludes.php file, which creates, for either the client level (stored in /scripts/) or the demo level (stored in /demo/scripts/), the three .php files (i.e. localeslistable.php, localeslistphrase.php, and labellocations.php), which are currently used to incorporate locale- and location-specific data on the newresolution home page and elsewhere sa in various page title tags. Specifically, labellocations.html provides the table of locale labels and locations at the bottom of the page (and, also, indirectly it provides content for manipulation by marqueescript.js for use in the horizontally scrolling list of labels and parenthesized locations at the top of the screen); localeslistphrase.html provides locale labels content for the server-side include within the paragraph text after the words "... satellite locations in ..."; and localeslisttable.html provides the table of rows of format "locale label, state" (e.g. "Tacoma, WA") that appears on the right-hand edge of the home page. I use a PHP require statement for modularity b/c this same creation code is used elsewhere in expirationrelease.php and admin6.php.

require('../ssi/createcustompages.php'); // Include the createcustompages.php file, which creates, for either the client level (stored in /) or the demo level (stored in /demo/), the custom .shtml mediator files (e.g. file = "mediatorSanFrancisco-Oakland-Fremont.shtml") and the custom .shtml fees pages (e.g. file = "mediationcostSanFrancisco-Oakland-Fremont.shtml") for each locale for which a (non-suspended) mediator exists in mediators_table or mediators_table_demo respectively.


ob_end_flush();
?>
</body>
</html>