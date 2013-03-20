<?php
/*
createcustompages.php generates custom versions of the generic s_mediators.shtml and s_fees.shtml for each locale with an active mediator. (It also, when appropriate, deletes from the server any superfluous custom mediator and custom fees pages -- a task also performed by admin6.php when an administrator trashes a mediator.) It is called by processprofile.php every time a user edits his/her mediator profile via updateprofile.php and its processprofile.php slave. (It also gets called by cron script expirationrelease.php and by admin6.php every time an administrator renews a mediator or edits the Suspend and Freeze settings in a mediator profile via admin5A.php.) Suppose we have mediators in locales 'Dallas-Fort Worth-Arlington_TX' and in 'San Francisco-Oakland-Fremont_CA'. In that case, this script would need to create four files called 'mediatorDallas-FortWorth-Arlington.shtml', 'mediatorSanFrancisco-Oakland-Fremont.shtml', 'mediationcostDallas-FortWorth-Arlington.shtml', and 'mediationcostSanFrancisco-Oakland-Fremont.shtml'. These two pairs of custom files would then be loaded via the drop-down menu in the generic s_mediators.shtml and s_fees.shtml respectively as well as by user-selections in drop-down menus on each of the custom .shtml pages.
	If two mediators share a common locale (e.g. the locale Toyville_TX), then mediatorToyville.shtml (or mediationcostToyville.shtml) will get created once then it will get created again (i.e. writing over the first instance of the file). Both versions of the file will be identical and will display details of both mediators via the LocaleBasedInfoDumper() function that gets called by GenerateLocalesDrop() (a function defined in coremediatorfunctions.js).
	Another task performed by createcustompages.php is to rewrite (i.e. update) the sitemap.xml so that it integrates all the applicable custom mediator .shtml pages. We only need to do this for the client case, of course, because the files under the /demo folder don't need to be referenced in a sitemap.xml.
	The functionality of createcustompages.php is quite similar to that of createutilitymediatordata.php, except the former creates a .shtml file whereas the latter creates a .js file.
	Also note that the $myString contents inside the "themediators" div (which appears within the string constructions for both the custom mediator pages and the custom fees page) is to promote search visibility using dynamicdrive.com's animated div script (as deployed in the Natl Mediation Training Registry's index page).
*/

require('democlientpaths2.php'); // Include the democlientpaths2.php file, which provides the page paths for either the client case (i.e. '../') or the demo case (i.e. '../demo/') as appropriate to which the custom mediators .shtml file and custom fees .shtml file should be stored on the server. Note that the ssi file democlientpaths2.php is similar to but different from the script file democlientpaths1.php.
$pagepathshort = $_SESSION['pagepathshort'];
$pagepathlong = $_SESSION['pagepathlong'];

$extraSiteMapLines = ''; // Initialize variable used to store extra lines to be inserted into the sitemap.xml.
$AlreadyUsedLocales = array(); // Initialize for later use inside the main loop below.

// Retrieve all rows from database in which the 'Suspend' column is 0 (i.e. false),  the mediator isn't under AdminFreeze, and the mediator isn't in the "just created" stage s.t. he/she hasn't yet completed his/her mediator profile. Then place these rows in resource $row.
$query = "select * from ".$dbmediatorstablename." where Suspend = 0 AND AdminFreeze != 1 AND Name != '' AND Name IS NOT NULL";
$result = mysql_query($query) or die('Query (select) failed: ' . mysql_error());

// Begin to construct strings that will be placed in a custom mediator .shtml file and in a custom fees .shtml file.
while ($row = mysql_fetch_assoc($result))
{

// Perform another DB sub-query inside the main query loop so we can obtain the names of all of the Locations used by any mediator associated with this particular Locale. We then cite these Locations in the .shtml page's description and keywords meta-tags.
$querysub = "select Name, Locations, Locale, Profile, PrincipalStreet, PrincipalAddressOther, City, State, Zip, SlidingScale, HourlyRate, Increment, ConsultationPolicy, CancellationPolicy, Packages FROM ".$dbmediatorstablename." where Locale = '".$row['Locale']."' AND AdminFreeze != 1 AND Name != '' AND Name IS NOT NULL";
$resultsub = mysql_query($querysub) or die('Sub-query (select) inside the main loop failed: ' . mysql_error());

$alllocationsstring = ''; // Initialize; will hold all locations associated with this Locale
$allnamesstring = ''; // Initialize; will hold all mediator names associated with this Locale

while ($line = mysql_fetch_assoc($resultsub))
	{
	$alllocationsstring .= ','.$line['Locations'];
	$allnamesstring .= ','.$line['Name'];
	}

// Clean up $alllocationsstring and $allnamesstring for use in the page's meta tags.
$alllocationsstring = substr($alllocationsstring, 1); // remove the initial comma at the beginning of the string
$allnamesstring = substr($allnamesstring, 1);
$alllocationsstring = str_replace('"', '', $alllocationsstring); // remove double quotes
$alllocationsstring = str_replace(',', ', ', $alllocationsstring); // insert a space in between locations
$allnamesstring = str_replace(',', ', ', $allnamesstring); // insert a space in between mediator names
// In order to remove duplicates from $alllocationsstring, convert it to an array then make use of PHP's array_unique() function.
$alllocationsarray = explode(', ', $alllocationsstring);
$alllocationsarray = array_unique($alllocationsarray);
$alllocationsstring = implode(', ', $alllocationsarray); // reconstitute the string

// Create the name that we want to give to the custom mediator file from $row['Locale'] using string functions. (Note: we performed a comparable set of manipulations to build the uniqueLocaleLabelAssocStub[] array using Javascript within utilitymediatorcode.js, but now we need to work with PHP, and it's sensible to use $row['Locale'] as our starting point.)
$cleanstub = $row['Locale'];
$cleanstub = str_replace(' ', '', $cleanstub); // Remove spaces
$cleanstub = str_replace('&ntilde;', 'n', $cleanstub); // Replace n-tilde with a plain n (in Canon City_CO only)
$cleanstub = str_replace('&#039;', '', $cleanstub); // Remove apostrophe special character (in Coeur d'Alene_ID only)
$cleanstubArray = explode('_', $cleanstub); $cleanstub = $cleanstubArray[0];  // Take just the part of the string up to (excluding) the underscore.

// First build up string $myString to be written into the custom mediator .shtml file.
$myString = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>";
$myString .= "<html>";
$myString .= "<head>";
$myString .= "<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'><meta http-equiv='CONTENT-LANGUAGE' CONTENT='en-US'>";
$myString .= "<title>".str_replace('_', ', ', $row['Locale'])." Mediators</title>"; // Replace the underscore with a comma when using the Locale within a page title e.g. "Coeur d'Alene, ID" rather than "Coeur d'Alene_ID"
$myString .= "<meta NAME='description' CONTENT='Mediator ".$allnamesstring." provide(s) divorce, family, and business mediation in ".$alllocationsstring."'>";
$myString .= "<meta NAME='keywords' CONTENT='mediators,".$allnamesstring.",".$alllocationsstring."'>";
$myString .= "<link href='/nrcss.css' rel='stylesheet' type='text/css'>";
$myString .= "<link rel='shortcut icon' href='http://newresolutionmediation.com/favicon.ico' type='image/x-icon'>";
$myString .= "<!-- See: http://www.dynamicdrive.com/dynamicindex17/animatedcollapse.htm for documentation on animated, collapsible divs. Animated Collapsible DIV v2.4- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com) Note: The following jquery script call caused the milonic menu to crash until I moved the script call above (i.e. before) the milonic script calls in the head section of this page. -->";
$myString .= "<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js'></script>";
$myString .= "<SCRIPT language='JavaScript' src='/milonic_src.js' type='text/javascript'></SCRIPT>";
$myString .= "<script language='JavaScript' type='text/javascript'>";
$myString .= 'if(ns4)_d.write("<scr"+"ipt language='."'JavaScript' src='/mmenuns4.js'><\/scr".'"+"ipt>");';
$myString .= 'else _d.write("<scr"+"ipt language='."'JavaScript' src='/mmenudom.js'><\/scr".'"+"ipt>");';
$myString .=  "</script>";
$myString .= "<SCRIPT language='JavaScript' src='/menu_data_1.js' type='text/javascript'></SCRIPT>";
$myString .= "<script type='text/javascript' src='/openmenusbyurl.js'></script>";
$myString .= "<script language='JavaScript' src='/scripts/emailaddresschecker.js' type='text/javascript'></script>";
$myString .= "<script language='JavaScript' src='/scripts/windowpops.js' type='text/javascript'></script>";
$myString .= "<script language='JavaScript' type='text/javascript' src='/scripts/coremediatorfunctions.js'></script>";
$myString .= "<script language='JavaScript' type='text/javascript' src='/scripts/dynamicformutility.js'></script>";
$myString .= "<script language='JavaScript' type='text/javascript' src='/scripts/cookiefunctions.js'></script>";
$myString .= "<script language='JavaScript' type='text/javascript' src='/scripts/animatedcollapse.js'></script>";
$myString .= "<script type='text/javascript'>";
$myString .= "animatedcollapse.addDiv('themediators', 'fade=0, hide=1'); ";
$myString .= "animatedcollapse.init(); ";
$myString .= "</script>";
$myString .= "</head>";
$myString .= "<body>";
$myString .= "<div id='main'>";
$myString .= "<div id='relwrapper'>";
$myString .= "<script type='text/javascript'> ";
$myString .= "var _gaq = _gaq || []; ";
$myString .= "_gaq.push(['_setAccount', 'UA-1100858-3']); ";
$myString .= "_gaq.push(['_trackPageview']); ";
$myString .= "(function() { ";
$myString .= "var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true; ";
$myString .= "ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js'; ";
$myString .= "(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga); ";
$myString .= "})(); ";
$myString .= "</script>";
$myString .= "<a name='backtotop'></a>";
$myString .= "<a href='index.shtml' onClick='wintasticself(\'index.shtml\'); return false;'><img src='/images/mediation_animlogo.gif' alt='New Resolution logo' width='204' height='96' class='toprightlogo_1' border='0'>";
$myString .= "<div id='toprightlogotm_1' style='color: #000000;'>&reg;</div><div id='LLCsuffix_1'>LLC</div></a>";
$myString .= "<table width='100%'  border='0' cellpadding='0' cellspacing='0'>";
$myString .= "<tr>";
$myString .= "<td width='750'><img src='/images/TangBlock.jpg' alt='tangerine block' width='750' height='100'></td>";
$myString .= "<td height='100%' rowspan='4'>&nbsp;</td>";
$myString .= "</tr>";
$myString .= "<tr>";
$myString .= "<td>";
$myString .= "<a href='r_onlinehelp.php'><img style='position: absolute; top: 100px; left: 672px' src='/images/WrenchIcon.jpg' alt='Mediation Self-Help' border='0'></a>";
$myString .= "<a href='locations.php'><img style='position: absolute; top: 100px; left: 40px' src='/images/LocationIcon.jpg' alt='Our Locations' border='0'></a>";
$myString .= "<a href='index.shtml'><img style='position: absolute; top: 100px; left: 0px' src='/images/HomeIcon.jpg' alt='Tour Site' border='0'></a>";
$myString .= "</td>";
$myString .= "</tr>";
$myString .= "<tr>";
$myString .= "<td height='86'><img src='/images/tangmenubg.jpg' alt='mediation' width='750' height='30' class='tangmenucover_1'></td>";
$myString .= "</tr>";
$myString .= "<tr>";
$myString .= "<td width='750'>";
$myString .= "<script type='text/javascript' src='scripts/utilitymediatordata.js'></script>";
$myString .= "<script type='text/javascript' src='/scripts/utilitymediatorcode.js'></script>";
$myString .= "<h1>Our Mediators<sup>&dagger;</sup></h1>";
$myString .= "<form name='generatelocalesform'>";
$myString .= "<table class='formmargins' cellspacing='0' cellpadding='0'>";
$myString .= "<tr>";
$myString .= "<td class='formcol1_2'>";
$myString .= "<script type='text/javascript'>";
$myString .= "mediatorspageflag = true;";
$myString .= "document.write(GenerateLocalesDrop());";
$myString .= "</script>";
$myString .= "</td>";
$myString .= "</tr>";
$myString .= "</table>";
$myString .= "</form>";
$myString .= "<HR align='left' size='1px' noshade color='#FF9900' class='formdivider4'>";
$myString .= "<div id='instructions' style='display:block'>";
$myString .= "<table class='invertbox2' width='470' cellpadding='10px' cellspacing='0'>";
$myString .= "<tr><td>Mediator information will appear here when you select a locale.</td></tr>";
$myString .= "</table>";
$myString .= "<HR align='left' size='1px' noshade color='#FF9900' class='formdivider4'>";
$myString .= "</div>";
$myString .= "<div id='holding_pen1' style='display: none'></div>";
$myString .= "<br/><span class='basictextboldsmaller' style='margin-left: 150px;'>&dagger;&nbsp;All mediators are independent members of the New Resolution network of independent professionals</span>";
$myString .= "<script type='text/javascript' language='javascript'>";
$myString .= "PresetLocalesDropSelection();  //Call this function to (i) get the value of the 'selectedlocale' cookie,  (ii) preset the selected option to match the value of this cookie, and (iii) show/hide either the 'instructions' div or the div for the selected locale accordingly.";
$myString .= "</script>";
$myString .= "</td>";
$myString .= "</tr>";
$myString .= "<tr>";
$myString .= "<td height='40' valign='bottom'>";
$myString .= "<div id='themediators'>";
mysql_data_seek($resultsub, 0); // Move the internal pointer back to the first row (i.e. row 0) in $resultsub.\n\r";
while ($line = mysql_fetch_assoc($resultsub))
{
$myString .= "<h2>Mediator ".$line['Name']."</h2>";
$myString .= "<div class='greytext' style='margin-left: 150px;'>Locale: ".str_replace('_', ', ', $line['Locale'])."</div>";
$theaddress = '';
if (!empty($line['PrincipalStreet'])) $theaddress = $line['PrincipalStreet'].', ';
if (!empty($line['PrincipalAddressOther'])) $theaddress .= $line['PrincipalAddressOther'].', ';
if (!empty($line['City'])) $theaddress .= $line['City'].', ';
if (!empty($line['State'])) $theaddress .= $line['State'].' ';
if (!empty($line['Zip'])) $theaddress .= $line['Zip'];
$myString .= "<div class='greytext' style='margin-left: 150px;'>Main office: ".$theaddress."</div>";
// Tidy up the list of locations for this particular mediator using variable $thelocns
$thelocns = str_replace('"', '', $line['Locations']);
$thelocns = str_replace(',', ', ', $thelocns);
$myString .= "<div class='greytext' style='margin-left: 150px;'>All locations: ".$thelocns."</div><br />";
$myString .= "<p class='text'>".$line['Profile']."</p>";
}
$myString .= "</div>";
$myString .= "<div id='copyright'>&copy; ".date("Y")." New Resolution LLC. All rights reserved. <a class='footer' href='sitemap.shtml'>Site Map</a>&nbsp;|&nbsp;<a class='footer' href='/scripts/updateprofile.php'>Log In</a>&nbsp;|&nbsp;<a class='footer' href='/mediationcareersform.shtml' onClick='window.open(\'/mediationcareersform.shtml\',\'\',\'height=615,width=750,top=30,left=290,scrollbars=yes,menubar=no,toolbar=no,location=no,status=yes\'); return false;'>Careers</a>&nbsp;|&nbsp;<a class='footer' href='javascript:animatedcollapse.toggle(".'"themediators"'.")'>Mediators</a></div>";
$myString .= "</td></tr>";
$myString .= "</table>";

$myString .= "<!-- Place this tag where you want the +1 button to render (Note: I have two +1 buttons on each page.) -->";
$myString .= "<span id='plusone'><g:plusone size='small' count='false' href='<?php ";
$myString .= "function curPageURL() ";
$myString .= "	{";
$myString .= '	$pageURL = "http";';
$myString .= '	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}';
$myString .= '	$pageURL .= "://";';
$myString .= '	if ($_SERVER["SERVER_PORT"] != "80")';
$myString .= '		{';
$myString .= '		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];';
$myString .= '		} ';
$myString .= '	else';
$myString .= '		{';
$myString .= '		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];';
$myString .= '		}';
$myString .= '	return $pageURL;';
$myString .= '	}';
$myString .= 'echo curPageURL();';
$myString .= ' ?>';
$myString .= "'></g:plusone></span>";
$myString .= "<!--  Place this tag after the last plusone tag -->";
$myString .= "<script type='text/javascript'>";
$myString .= " (function() {";
$myString .= "    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;";
$myString .= "    po.src = 'https://apis.google.com/js/plusone.js';";
$myString .= "    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);";
$myString .= "  })();";
$myString .= "</script>";

$myString .= "<!-- Start of StatCounter Code -->";
$myString .= "<script type='text/javascript'>";
$myString .= "var sc_project=5651836; ";
$myString .= "var sc_invisible=1; ";
$myString .= "var sc_partition=60; ";
$myString .= "var sc_click_stat=1; ";
$myString .= "var sc_security='97c14d16'; ";
$myString .= "</script>";
$myString .= "<script type='text/javascript' src='http://www.statcounter.com/counter/counter.js'></script>";
$myString .= "<noscript><div class='statcounter'><a title='joomla counter' href='http://www.statcounter.com/joomla/' target='_blank'><img class='statcounter' src='http://c.statcounter.com/5651836/0/97c14d16/1/' alt='joomla counter'></a></div></noscript>";
$myString .= "<!-- End of StatCounter Code -->";
$myString .= "</div>";
$myString .= "</div>";
$myString .= "</body>";
$myString .= "</html>";

// Opening file
$fp = fopen($pagepathshort."mediator".$cleanstub.".shtml","w");

// Attempt to apply an exclusive lock
$lk = flock($fp, LOCK_EX);
if (!$lk) echo "Error locking the custom mediator .shtml file!";

// Write to file
fwrite($fp, $myString, strlen($myString));

// Unlock the file (this would get done by the o/s automatically on exit of the script, but this is a good safe thing to do in case the script served by Apache doesn't end.
flock($fp, LOCK_UN);

// Closing file
fclose($fp);


// Next, build up a new value for string $myString to be written into the custom mediationcost .shtml file.
$myString = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>";
$myString .= "<html>";
$myString .= "<head>";
$myString .= "<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'><meta http-equiv='CONTENT-LANGUAGE' CONTENT='en-US'>";
$myString .= "<title>Cost of Mediation in ".str_replace('_', ', ', $row['Locale'])." | Mediator Fees</title>"; // Replace the underscore with a comma when using the Locale within a page title e.g. "Coeur d'Alene, ID" rather than "Coeur d'Alene_ID"
$myString .= "<meta NAME='description' CONTENT='Cost of divorce, family, and business mediation in ".$alllocationsstring." from members of the New Resolution Network of Independent Mediators'>";
$myString .= "<meta NAME='keywords' CONTENT='mediation cost,mediator fees,how much mediation costs in ".$alllocationsstring."'>";
$myString .= "<link href='/nrcss.css' rel='stylesheet' type='text/css'>";
$myString .= "<link rel='shortcut icon' href='http://newresolutionmediation.com/favicon.ico' type='image/x-icon'>";
$myString .= "<!-- See: http://www.dynamicdrive.com/dynamicindex17/animatedcollapse.htm for documentation on animated, collapsible divs. Animated Collapsible DIV v2.4- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com) Note: The following jquery script call caused the milonic menu to crash until I moved the script call above (i.e. before) the milonic script calls in the head section of this page. -->";
$myString .= "<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js'></script>";
$myString .= "<SCRIPT language='JavaScript' src='/milonic_src.js' type='text/javascript'></SCRIPT>";
$myString .= "<script language='JavaScript' type='text/javascript'>";
$myString .= 'if(ns4)_d.write("<scr"+"ipt language='."'JavaScript' src='/mmenuns4.js'><\/scr".'"+"ipt>");';
$myString .= 'else _d.write("<scr"+"ipt language='."'JavaScript' src='/mmenudom.js'><\/scr".'"+"ipt>");';
$myString .=  "</script>";
$myString .= "<SCRIPT language='JavaScript' src='/menu_data_1.js' type='text/javascript'></SCRIPT>";
$myString .= "<script type='text/javascript' src='/openmenusbyurl.js'></script>";
$myString .= "<script language='JavaScript' src='/scripts/emailaddresschecker.js' type='text/javascript'></script>";
$myString .= "<script language='JavaScript' src='/scripts/windowpops.js' type='text/javascript'></script>";
$myString .= "<script language='JavaScript' type='text/javascript' src='/scripts/coremediatorfunctions.js'></script>";
$myString .= "<script language='JavaScript' type='text/javascript' src='/scripts/dynamicformutility.js'></script>";
$myString .= "<script language='JavaScript' type='text/javascript' src='/scripts/cookiefunctions.js'></script>";
$myString .= "<script language='JavaScript' type='text/javascript' src='/scripts/animatedcollapse.js'></script>";
$myString .= "<script type='text/javascript'>";
$myString .= "animatedcollapse.addDiv('themediators', 'fade=0, hide=1'); ";
$myString .= "animatedcollapse.init(); ";
$myString .= "</script>";
$myString .= "</head>";
$myString .= "<body>";
$myString .= "<div id='main'>";
$myString .= "<div id='relwrapper'>";
$myString .= "<script type='text/javascript'> ";
$myString .= "var _gaq = _gaq || []; ";
$myString .= "_gaq.push(['_setAccount', 'UA-1100858-3']); ";
$myString .= "_gaq.push(['_trackPageview']); ";
$myString .= "(function() { ";
$myString .= "var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true; ";
$myString .= "ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js'; ";
$myString .= "(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga); ";
$myString .= "})(); ";
$myString .= "</script>";
$myString .= "<a name='backtotop'></a>";
$myString .= "<a href='index.shtml' onClick='wintasticself(\'index.shtml\'); return false;'><img src='/images/mediation_animlogo.gif' alt='New Resolution logo' width='204' height='96' class='toprightlogo_1' border='0'>";
$myString .= "<div id='toprightlogotm_1' style='color: #000000;'>&reg;</div><div id='LLCsuffix_1'>LLC</div></a>";
$myString .= "<table width='100%'  border='0' cellpadding='0' cellspacing='0'>";
$myString .= "<tr>";
$myString .= "<td width='750'><img src='/images/TangBlock.jpg' alt='tangerine block' width='750' height='100'></td>";
$myString .= "<td height='100%' rowspan='4'>&nbsp;</td>";
$myString .= "</tr>";
$myString .= "<tr>";
$myString .= "<td>";
$myString .= "<a href='r_onlinehelp.php'><img style='position: absolute; top: 100px; left: 672px' src='/images/WrenchIcon.jpg' alt='Mediation Self-Help' border='0'></a>";
$myString .= "<a href='locations.php'><img style='position: absolute; top: 100px; left: 40px' src='/images/LocationIcon.jpg' alt='Our Locations' border='0'></a>";
$myString .= "<a href='index.shtml'><img style='position: absolute; top: 100px; left: 0px' src='/images/HomeIcon.jpg' alt='Tour Site' border='0'></a>";
$myString .= "</td>";
$myString .= "</tr>";
$myString .= "<tr>";
$myString .= "<td height='86'><img src='/images/tangmenubg.jpg' alt='mediation' width='750' height='30' class='tangmenucover_1'></td>";
$myString .= "</tr>";
$myString .= "<tr>";
$myString .= "<td width='750'>";
$myString .= "<script type='text/javascript' src='scripts/utilitymediatordata.js'></script>";
$myString .= "<script type='text/javascript' src='/scripts/utilitymediatorcode.js'></script>";
$myString .= "<h1>Fees<sup>&dagger;</sup></h1>";
$myString .= "<form name='generatelocalesform'>";
$myString .= "<table class='formmargins' cellspacing='0' cellpadding='0'>";
$myString .= "<tr>";
$myString .= "<td class='formcol1_2'>";
$myString .= "<script type='text/javascript'>";
$myString .= "feespageflag = true;";
$myString .= "document.write(GenerateLocalesDrop() + \"<div id='meddropannex'></div>\");";
$myString .= "</script>";
$myString .= "</td>";
$myString .= "</tr>";
$myString .= "</table>";
$myString .= "</form>";
$myString .= "<HR align='left' size='1px' noshade color='#FF9900' class='formdivider4'>";
$myString .= "<div id='instructions' style='display:block'>";
$myString .= "<table class='invertbox2' width='470' cellpadding='10px' cellspacing='0'>";
$myString .= "<tr><td>Fee information will appear here when you select a locale/mediator.</td></tr>";
$myString .= "</table>";
$myString .= "<HR align='left' size='1px' noshade color='#FF9900' class='formdivider4'>";
$myString .= "</div>";
$myString .= "<div id='holding_pen1' style='display: none'></div>";
$myString .= "<script type='text/javascript' language='javascript'>";
$myString .= "PresetLocalesDropSelection();  //Call this function to (i) get the value of the 'selectedlocale' cookie,  (ii) preset the selected option to match the value of this cookie, and (iii) show/hide either the 'instructions' div or the div for the selected locale accordingly.";
$myString .= "";
$myString .= "</script>";
$myString .= "<p class='text'>&nbsp;</p>";
$myString .= "</td>";
$myString .= "</tr>";
$myString .= "<tr>";
$myString .= "<td height='40' valign='bottom'>";
$myString .= "<div id='themediators'>";
mysql_data_seek($resultsub, 0); // Move the internal pointer back to the first row (i.e. row 0) in $resultsub.\n\r";
while ($line = mysql_fetch_assoc($resultsub))
{
$myString .= "<h2>Fees for Mediator ".$line['Name']."</h2>";
$thelocale = str_replace('_', ', ', $line['Locale']);
$thelocale = str_replace('-', ' ', $thelocale);
$myString .= "<p class='text'>Cost of mediation in ".$thelocale."</p>";
$thelocns = str_replace('"', '', $line['Locations']);
$thelocns = str_replace(',', ', ', $thelocns);
$myString .= "<div class='greytext' style='margin-left: 150px;'>Mediator locations: ".$thelocns."</div><br />";
if ($line['HourlyRate'] != null) { $hrate = strstr($line['HourlyRate'], 'true,'); if ($hrate != false) $hrate = '$'.str_replace('true,','',$hrate); else $hrate = 'please contact mediator for details'; }
$myString .= "<p class='text'>Mediation hourly rate: ".$hrate."</p>";
$loc = explode(',',$line['Packages']);
$pkgrate = ''; // Initialize
if ($loc[0] == 'false') $pkgrate .= 'no packages';
else
	{
	if ($loc[1] != '""') $pkgrate .= '<br />'.$loc[1].'-session package for $'.$loc[2];
	if ($loc[3] != '""') $pkgrate .= '<br>'.$loc[3].'-session package for $'.$loc[4];
	if ($loc[5] != '""') $pkgrate .= '<br>'.$loc[5].'-session package for $'.$loc[6];
	$pkgrate .= '<br>(each session is '.$loc[7];
	if ((int)$loc[7] < 2) $pkgrate .= ' hour'; else $pkgrate .= ' hours';
	if ($loc[8] != '""') $pkgrate .= ' '.$loc[8].' mins)'; else $pkgrate .= ')';
	};
$myString .= "<p class='text'>Package rate discounts: ".$pkgrate."</p>";
if ($line['SlidingScale'] == 0) $sliding = 'no'; else $sliding = 'yes';
$myString .= "<p class='text'>Sliding scale: ".$sliding."</p>";
$myString .= "<p class='text'>Fee increment: ".$line['Increment']." minutes</p>";
$cardpol = str_replace('"','',$line['CardPolicy']); // Strip out the double quotes (") from the string.
$cardpol = explode(',',$cardpol); // Create an array
if ($cardpol[0] == 'false') $cardpolicy = 'not accepted'; 
else 
	{
	$cardpolicy = 'credit cards accepted';
	if ($cardpol[1] == 'true') $cardpolicy .= ' (convenience charge of '.$cardpol[2].'%)'; else $cardpolicy .= ' (no convenience charge)';
	};
$myString .= "<p class='text'>Credit card policy: ".$cardpolicy."</p>";
$cancpol = str_replace('"','',$line['CancellationPolicy']); // Strip out the double quotes (") from the string.
$cancpol = explode(',',$cancpol); // Create an array
if ($cancpol[0] != 'null') $cancpolicy = '$'.$cancpol[2].' fee for cancellation inside '.$cancpol[0].' '.$cancpol[1];
$myString .= "<p class='text'>Cancellation policy: ".$cancpolicy."</p>";
}
$myString .= "</div>";
$myString .= "<div id='copyright'>&copy; ".date("Y")." New Resolution LLC. All rights reserved. <a class='footer' href='sitemap.shtml'>Site Map</a>&nbsp;|&nbsp;<a class='footer' href='/scripts/updateprofile.php'>Log In</a>&nbsp;|&nbsp;<a class='footer' href='/mediationcareersform.shtml' onClick='window.open(\'/mediationcareersform.shtml\',\'\',\'height=615,width=750,top=30,left=290,scrollbars=yes,menubar=no,toolbar=no,location=no,status=yes\'); return false;'>Careers</a>&nbsp;|&nbsp;<a class='footer' href='javascript:animatedcollapse.toggle(".'"themediators"'.")'>Mediation Fees</a></div>";
$myString .= "</td></tr>";
$myString .= "</table>";

$myString .= "<!-- Place this tag where you want the +1 button to render (Note: I have two +1 buttons on each page.) -->";
$myString .= "<span id='plusone'><g:plusone size='small' count='false' href='<?php ";
$myString .= "function curPageURL() ";
$myString .= "	{";
$myString .= '	$pageURL = "http";';
$myString .= '	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}';
$myString .= '	$pageURL .= "://";';
$myString .= '	if ($_SERVER["SERVER_PORT"] != "80")';
$myString .= '		{';
$myString .= '		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];';
$myString .= '		} ';
$myString .= '	else';
$myString .= '		{';
$myString .= '		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];';
$myString .= '		}';
$myString .= '	return $pageURL;';
$myString .= '	}';
$myString .= 'echo curPageURL();';
$myString .= ' ?>';
$myString .= "'></g:plusone></span>";
$myString .= "<!--  Place this tag after the last plusone tag -->";
$myString .= "<script type='text/javascript'>";
$myString .= " (function() {";
$myString .= "    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;";
$myString .= "    po.src = 'https://apis.google.com/js/plusone.js';";
$myString .= "    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);";
$myString .= "  })();";
$myString .= "</script>";

$myString .= "<!-- Start of StatCounter Code -->";
$myString .= "<script type='text/javascript'>";
$myString .= "var sc_project=5651836; ";
$myString .= "var sc_invisible=1; ";
$myString .= "var sc_partition=60; ";
$myString .= "var sc_click_stat=1; ";
$myString .= "var sc_security='97c14d16'; ";
$myString .= "</script>";
$myString .= "<script type='text/javascript' src='http://www.statcounter.com/counter/counter.js'></script>";
$myString .= "<noscript><div class='statcounter'><a title='joomla counter' href='http://www.statcounter.com/joomla/' target='_blank'><img class='statcounter' src='http://c.statcounter.com/5651836/0/97c14d16/1/' alt='joomla counter'></a></div></noscript>";
$myString .= "<!-- End of StatCounter Code -->";
$myString .= "</div>";
$myString .= "</div>";
$myString .= "</body>";
$myString .= "</html>";

// Opening file
$fp = fopen($pagepathshort."mediationcost".$cleanstub.".shtml","w");

// Attempt to apply an exclusive lock
$lk = flock($fp, LOCK_EX);
if (!$lk) echo "Error locking the custom mediationcost (i.e. custom fees) .shtml file!";

// Write to file
fwrite($fp, $myString, strlen($myString));

// Unlock the file (this would get done by the o/s automatically on exit of the script, but this is a good safe thing to do in case the script served by Apache doesn't end.
flock($fp, LOCK_UN);

// Closing file
fclose($fp);


/* Also, for the 'client' case (not necessary for 'demo' case), as we loop through the main loop for each non-suspended mediator, build up a string containing references to the relevant custom mediator page and custom fee page for subsequent inclusion (see further below) in the sitemap.xml file. */
// But since we need to build the $extraSiteMapLines string for unique locales only, I'll use array $AlreadyUsedLocales to track whether extra sitemap lines for a mediator in the main loop would have already been created. I'll only further extend $extraSiteMapLines if we're encountering the mediator's Locale for the first time.
if ($_SESSION['ClientDemoSelected'] == 'client')
	if (!in_array($row['Locale'], $AlreadyUsedLocales))
		{
		array_push($AlreadyUsedLocales, $row['Locale']);
		// For custom mediator .shtml pages:
		$extraSiteMapLines .= "<url>\n";
		$extraSiteMapLines .= "<loc>http://www.newresolutionmediation.com/mediator".$cleanstub.".shtml</loc>\n";
		$extraSiteMapLines .= "<changefreq>monthly</changefreq>\n";
		$extraSiteMapLines .= "<priority>0.7</priority>\n";
		$extraSiteMapLines .= "</url>\n\n";
		// For custom mediationcost .shtml pages:
		$extraSiteMapLines .= "<url>\n";
		$extraSiteMapLines .= "<loc>http://www.newresolutionmediation.com/mediationcost".$cleanstub.".shtml</loc>\n";
		$extraSiteMapLines .= "<changefreq>monthly</changefreq>\n";
		$extraSiteMapLines .= "<priority>0.7</priority>\n";
		$extraSiteMapLines .= "</url>\n\n";
		}
}

// Free resultset
mysql_free_result($result);

/* Next, as a bit of nice housekeeping, it's appropriate to delete (i.e. unlink in technical PHP parlance) any custom versions of the generic s_mediators.shtml file (e.g. mediatorSanFrancisco-Oakland-Fremont.shtml) and any custom versions of the generic s_fees.shtml file (e.g. mediationcostSanFrancisco-Oakland-Fremont.shtml) from the server if (and only if) all the mediators associated with that (e.g. San Francisco-Oakland-Fremont_CA) locale have Suspend == 1. */
$query = "select Locale from ".$dbmediatorstablename." where Suspend = 1";
$result = mysql_query($query) or die('Query (select of Locale) failed: ' . mysql_error());

// Loop through each row of the resultset, which comprises mediators who have Suspend == 1
while ($row = mysql_fetch_assoc($result)) 
	{
	$suspendedLocale = $row['Locale'];
	$deleteCustomFile = true; // Initialize flag

	// Perform a nested loop thru the entire mediators_table (or mediators_table_demo) where the Locale matches $suspendedLocale i.e. a locale in the outer loop of Suspend'ed mediators. 
	$query1 = "select Locale, Suspend from ".$dbmediatorstablename." where Locale='".$row['Locale']."'"; 
	$result1 = mysql_query($query1) or die('Query (select of Locale) failed: ' . mysql_error());
	while ($line = mysql_fetch_assoc($result1))
		{
		if ($line['Suspend'] == 0)
			{
			$deleteCustomFile = false; // If we find a row in the result1 resultset whose mediator is not suspended, then don't delete the custom mediator file for this locale and might as well break out of the loop.
			break;
			}
		}
	if ($deleteCustomFile == true) // Delete the custom .shtml file if the $deleteCustomFile is still true upon completing the inner loop.
		{
		// Once again, just as we did above, create the name of the the custom mediator file from $row['Locale'] using string functions. This time, though, we are creating the file name for deletion rather than in order to write or rewrite to the file. (Note also: we performed a comparable set of manipulations to build the uniqueLocaleLabelAssocStub[] array using Javascript within utilitymediatorcode.js, but now we need to work with PHP, and it's sensible to use $row['Locale'] as our starting point.)
		$cleanstub = $suspendedLocale;
		$cleanstub = str_replace(' ', '', $cleanstub); // Remove spaces
		$cleanstub = str_replace('&ntilde;', 'n', $cleanstub); // Replace n-tilde with a plain n (in Canon City_CO only)
		$cleanstub = str_replace('&#039;', '', $cleanstub); // Remove apostrophe special character (in Coeur d'Alene_ID only)
		$cleanstubArray = explode('_', $cleanstub); $cleanstub = $cleanstubArray[0];  // Take just the part of the string up to (excluding) the underscore.

		@unlink($pagepathlong."mediator".$cleanstub.".shtml"); // Unlinking custom versions of the s_mediators.shtml files. (The @ symbol suppresses warning messages if the file that we're trying to delete (i.e. unlink) doesn't exist on the server.)
		@unlink($pagepathlong."mediationcost".$cleanstub.".shtml"); // Unlinking custom versions of the s_fees.shtml files. 
		}
	}

/* Finally, iff $_SESSION['ClientDemoSelected'] == 'client', we should rewrite (i.e. update) the sitemap.xml so that it integrates the static (i.e. non-changing) site map page references as well as references to all the (changable) custom mediator .shtml and mediationcost.shtml pages. (Note: there is no sitemap.xml for files in the /demo folder.) */
if ($_SESSION['ClientDemoSelected'] == 'client')
{
// First define the sitemap string, integrating the $extraSiteMapLines that I constructed above within the main loop.
$sitemapString .= "<?xml version='1.0' encoding='UTF-8'?>\n";
$sitemapString .= "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>\n";
$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/</loc>\n";
$sitemapString .= "<lastmod>2010-10-28</lastmod>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.9</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/index.shtml</loc>\n";
$sitemapString .= "<lastmod>2010-10-28</lastmod>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.9</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/m_mediation.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/m_mediationbenefits.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/s_fees.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/d_whendivmed.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/m_mat.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/m_preparation.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/r_books.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/m_questions.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/s_disputes.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/s_custom.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/s_mediators.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/locations.php</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/s_testimonials.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/r_mediationarticles.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/s_standards.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/d_divorce.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/d_divmedbenefits.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/d_msa.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/d_divandsep.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/d_children.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/d_support.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/d_property.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/d_tax.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/f_businessmediation.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/f_eldermediation.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/f_inheritance.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/r_maritalmediation.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/r_samesexmediation.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/l_litigation.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/l_vindication.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/l_costs.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/l_empowerment.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/l_litvmed.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/c_contact.shtml</loc>\n";
$sitemapString .= "<changefreq>yearly</changefreq>\n";
$sitemapString .= "<priority>0.1</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/consultreqform.shtml</loc>\n";
$sitemapString .= "<changefreq>yearly</changefreq>\n";
$sitemapString .= "<priority>0.1</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/c_inpersonconsultreqform.shtml</loc>\n";
$sitemapString .= "<changefreq>yearly</changefreq>\n";
$sitemapString .= "<priority>0.1</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/c_schedmediationform.shtml</loc>\n";
$sitemapString .= "<changefreq>yearly</changefreq>\n";
$sitemapString .= "<priority>0.1</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/otherpartyrequestform.shtml</loc>\n";
$sitemapString .= "<changefreq>yearly</changefreq>\n";
$sitemapString .= "<priority>0.1</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/locations.php</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/d_costcalculator.shtml</loc>\n";
$sitemapString .= "<changefreq>yearly</changefreq>\n";
$sitemapString .= "<priority>0.3</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/sitemap.shtml</loc>\n";
$sitemapString .= "<changefreq>monthly</changefreq>\n";
$sitemapString .= "<priority>0.2</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/mediationcareersform.shtml</loc>\n";
$sitemapString .= "<changefreq>yearly</changefreq>\n";
$sitemapString .= "<priority>0.7</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/mediationcareer.php</loc>\n";
$sitemapString .= "<changefreq>yearly</changefreq>\n";
$sitemapString .= "<priority>0.9</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/mediationarticles/Article_ReasonsForDivMed.pdf</loc>\n";
$sitemapString .= "<changefreq>yearly</changefreq>\n";
$sitemapString .= "<priority>0.3</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/mediationarticles/Article_Aspects_of_Divorce.pdf</loc>\n";
$sitemapString .= "<changefreq>yearly</changefreq>\n";
$sitemapString .= "<priority>0.3</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/mediationarticles/Article_Ringer_Difficult_Conversations.pdf</loc>\n";
$sitemapString .= "<changefreq>yearly</changefreq>\n";
$sitemapString .= "<priority>0.3</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/mediationarticles/Article_Nolo_WhyMediation.pdf</loc>\n";
$sitemapString .= "<changefreq>yearly</changefreq>\n";
$sitemapString .= "<priority>0.3</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/mediationarticles/Article_Nolo_MedFAQ.pdf</loc>\n";
$sitemapString .= "<changefreq>yearly</changefreq>\n";
$sitemapString .= "<priority>0.3</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/mediationarticles/Article_Nolo_LawyerMediation.pdf</loc>\n";
$sitemapString .= "<changefreq>yearly</changefreq>\n";
$sitemapString .= "<priority>0.3</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/mediationarticles/Article_Parade_101905.pdf</loc>\n";
$sitemapString .= "<changefreq>yearly</changefreq>\n";
$sitemapString .= "<priority>0.3</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/mediationarticles/Article_Nolo_DivorceMedFAQ.pdf</loc>\n";
$sitemapString .= "<changefreq>yearly</changefreq>\n";
$sitemapString .= "<priority>0.3</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/mediationarticles/Article_DivorceMedWhenAngry.pdf</loc>\n";
$sitemapString .= "<changefreq>yearly</changefreq>\n";
$sitemapString .= "<priority>0.3</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/mediationarticles/Article_Nolo_DivorceMedMyths.pdf</loc>\n";
$sitemapString .= "<changefreq>yearly</changefreq>\n";
$sitemapString .= "<priority>0.3</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/mediationarticles/Article_Nolo_DivorceLaywerMed.pdf</loc>\n";
$sitemapString .= "<changefreq>yearly</changefreq>\n";
$sitemapString .= "<priority>0.3</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/mediationarticles/Article_Nolo_SmallBusiness.pdf</loc>\n";
$sitemapString .= "<changefreq>yearly</changefreq>\n";
$sitemapString .= "<priority>0.3</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/mediationarticles/Article_CIOMag_0204.pdf</loc>\n";
$sitemapString .= "<changefreq>yearly</changefreq>\n";
$sitemapString .= "<priority>0.3</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= "<url>\n";
$sitemapString .= "<loc>http://www.newresolutionmediation.com/mediator-interview.shtml</loc>\n";
$sitemapString .= "<changefreq>yearly</changefreq>\n";
$sitemapString .= "<priority>0.3</priority>\n";
$sitemapString .= "</url>\n";

$sitemapString .= $extraSiteMapLines; // Insert the extra sitemap lines here for the current custom mediator .shtml pages.

$sitemapString .= "</urlset>\n";

// Opening file

$fp = fopen($pagepathshort."sitemap".".xml","w");

// Attempt to apply an exclusive lock
$lk = flock($fp, LOCK_EX);
if (!$lk) echo "Error locking the sitemap.xml file!";

// Write to file
fwrite($fp, $sitemapString, strlen($sitemapString));

// Unlock the file (this would get done by the o/s automatically on exit of the script, but this is a good safe thing to do in case the script served by Apache doesn't end.
flock($fp, LOCK_UN);

// Closing file
fclose($fp);
}
?>