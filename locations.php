<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html><!-- InstanceBegin template="/Templates/MasterTemplate1F.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="CONTENT-LANGUAGE" CONTENT="en-US">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Mediator Locations in the New Resolution Mediation Network: <?php require('ssi/seolocationslist.php'); ?>.</title>
<meta NAME="description" CONTENT="Mediators in the New Resolution network of independent professionals: <?php require('ssi/seolocationslist.php'); ?>.">
<meta NAME="keywords" CONTENT="locations, <?php require('ssi/seolocationslist.php'); ?>, San Francisco Bay Area, Palo Alto, Mountain View, San Mateo, San Jose, Pleasanton, Walnut Creek, Oakland, Fremont, Pleasant Hill, Santa Clara, Burlingame, Petaluma, San Bruno, Hayward, Campbell, San Ramon">
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
<script type="text/javascript" src="/scripts/cookiefunctions.js"></script>
<script type="text/javascript" src="/scripts/coremediatorfunctions.js"></script>
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
	<script type="text/javascript" src="scripts/utilitymediatordata.js"></script>
	<script type="text/javascript" src="/scripts/utilitymediatorcode.js"></script> <!-- I need to include utilitymediatorcode.js and utilitymediatordata.js because the SetCookie() function uses the 'uniqueLocale' array that is defined inside utilitymediatorcode.js -->
<?php
// Connect to mysql. I need to access the DB on this location page b/c I need to not echo (i.e. not show) the region of the image map that pertains to a Locale Label if there is no mediator in the database with that LocaleLabel who currently has a non-suspended record in the DB, and, if that first condition wasn't met, there is no mediator in the database who doesn't share the same Locale. If the first condition is fulfilled, then simply echo the area and set the selectedlocale and selectedlocalelabel cookies accordingly. If the first condition isn't met but the second condition is, then echo the area, set the selectedlocale cookie, and set the selectedlocalelabel cookie to the LocaleLabel of one such other mediator. If neither condition is met, then don't echo the area. Failure to turn off such a region would cause an error if a user subsequently clicked on that region. The error would follow from the code trying to run code inside cookie-setting javascript functions that depends on the contents of the uniqueLocale javascript array.
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
or die('Could not connect: ' . mysql_error());
mysql_select_db('paulme6_newresolution') or die('Could not select database');

// Formulate query to retrieve an array of all locales that associate with an ID that is not suspended. (The DB table will be mediators_table_demo for demo/locations.php and mediators_table for the /locations.php page.)
$query = "select LocaleLabel, Locale from mediators_table where Suspend != 1";
$result = mysql_query($query) or die('Query (select LocaleLabel and Locale from table where ID not suspended) failed: ' . mysql_error());
if (!$result) {	echo 'No $result was achievable. Check whether any LocalesLabels/Locales associate with IDs that are not currently suspended.'; };

// Place the result of the query in an array $line. The associative values $line['LocaleLabel'] and $line['Locale'] on moving down through the lines of this array will hold the LocaleLabels and Locales for which a mediator ID is not currently suspended e.g. $line['LocaleLabel'] holds 'North Miami Beach', 'Fort Worth', 'San Francisco', 'Dallas', 'Scottsdale', etc., and $line['Locale'] holds 'Miami-Fort Lauderdale-Pompano Beach_FL', 'Dallas-Fort Worth-Arlington_TX', 'San Francisco-Oakland-Fremont_CA', 'Dallas-Fort Worth-Arlington_TX', 'Phoenix-Mesa-Glendale_AZ', etc.

// Build up an array $collocale that contains all the $line['Locale'] values
$i = 0;
while ($line = mysql_fetch_assoc($result))
	{
	$collocale[$i] = $line['Locale'];
	$i = $i + 1;
	}

// Build up a parallel array $collocalelabel that contains all the $line['LocaleLabel'] values
mysql_data_seek($result, 0);
$i = 0;
while ($line = mysql_fetch_assoc($result))
	{
	$collocalelabel[$i] = $line['LocaleLabel'];
	$i = $i + 1;
	}
?>
	<h1>Locations</h1><br/>
	<img border="0" usemap="#usmap" style="margin-left:60px" src="images/USmap.jpg">
	<map name="usmap">
	<?php
	// Note that the state abbreviation (e.g. NM) appended via an underscore when setting the selectedlocalelabel is always a single state (not, say, NY_NJ_PA) and that state is the state in which the the place happens to be on the map. That's because the selectedlocalelabel cookie is always defined by combining a mediator's LocaleLabel with his/her mediatorState (chosen by the mediator via a drop-down menu in his/her mediator profile) via a conjoining underscore.
	if (in_array('Albuquerque',$collocalelabel)) echo "<area shape='rect' coords='40,150,125,175' href='s_mediators.shtml' onclick=\"SetCookie('selectedlocale','Albuquerque_NM','',''); SetCookie('selectedlocalelabel','Albuquerque_NM','',''); SetCookie('selectedstate','NM','','');\">";
	else
		{
		for ($i=0; $i<count($collocale); $i++)
			{
			if ($collocale[$i] == 'Albuquerque_NM')
				{
				echo "<area shape='rect' coords='40,150,125,175' href='s_mediators.shtml' onclick=\"SetCookie('selectedlocale','Albuquerque_NM','',''); SetCookie('selectedlocalelabel','".$collocalelabel[$i]."','',''); SetCookie('selectedstate','NM','','');\">";
				break;
				}
			}
		};

	if (in_array('Dallas',$collocalelabel)) echo "<area shape='rect' coords='216,170,255,189' href='s_mediators.shtml' onclick=\"SetCookie('selectedlocale','Dallas-Fort Worth-Arlington_TX','',''); SetCookie('selectedlocalelabel','Dallas_TX','',''); SetCookie('selectedstate','TX','','');\">";
	else
		{
		for ($i=0; $i<count($collocale); $i++)
			{
			if ($collocale[$i] == 'Dallas-Fort Worth-Arlington_TX')
				{
				echo "<area shape='rect' coords='216,170,255,189' href='s_mediators.shtml' onclick=\"SetCookie('selectedlocale','Dallas-Fort Worth-Arlington_TX','',''); SetCookie('selectedlocalelabel','".$collocalelabel[$i]."_TX','',''); SetCookie('selectedstate','TX','','');\">";
				break;
				}
			}
		};
	
	if (in_array('Fort Worth',$collocalelabel)) echo "<area shape='rect' coords='150,190,215,208' href='s_mediators.shtml' onclick=\"SetCookie('selectedlocale','Dallas-Fort Worth-Arlington_TX','',''); SetCookie('selectedlocalelabel','Fort Worth_TX','',''); SetCookie('selectedstate','TX','','');\">";
	else
		{
		for ($i=0; $i<count($collocale); $i++)
			{
			if ($collocale[$i] == 'Dallas-Fort Worth-Arlington_TX')
				{
				echo "<area shape='rect' coords='150,182,215,205' href='s_mediators.shtml' onclick=\"SetCookie('selectedlocale','Dallas-Fort Worth-Arlington_TX','',''); SetCookie('selectedlocalelabel','".$collocalelabel[$i]."_TX','',''); SetCookie('selectedstate','TX','','');\">";
				break;
				}
			}
		};
	
	if (in_array('Miami',$collocalelabel)) echo "<area shape='rect' coords='360,230,420,260' href='s_mediators.shtml' onclick=\"SetCookie('selectedlocale','Miami-Fort Lauderdale-Pompano Beach_FL','',''); SetCookie('selectedlocalelabel','Miami_FL','',''); SetCookie('selectedstate','FL','','');\">";
	else
		{
		for ($i=0; $i<count($collocale); $i++)
			{
			if ($collocale[$i] == 'Miami-Fort Lauderdale-Pompano Beach_FL')
				{
				echo "<area shape='rect' coords='360,230,420,260' href='s_mediators.shtml' onclick=\"SetCookie('selectedlocale','Miami-Fort Lauderdale-Pompano Beach_FL','',''); SetCookie('selectedlocalelabel','".$collocalelabel[$i]."_FL','',''); SetCookie('selectedstate','FL','','');\">";
				break;
				}
			}
		};

	if (in_array('Scottsdale',$collocalelabel)) echo "<area shape='rect' coords='126,135,175,165' href='s_mediators.shtml' onclick=\"SetCookie('selectedlocale','Phoenix-Mesa-Glendale_AZ','',''); SetCookie('selectedlocalelabel','Scottsdale_AZ','',''); SetCookie('selectedstate','AZ','','');\">";
	else
		{
		for ($i=0; $i<count($collocale); $i++)
			{
			if ($collocale[$i] == 'Phoenix-Mesa-Glendale_AZ')
				{
				echo "<area shape='rect' coords='126,135,175,165' href='s_mediators.shtml' onclick=\"SetCookie('selectedlocale','Phoenix-Mesa-Glendale_AZ','',''); SetCookie('selectedlocalelabel','".$collocalelabel[$i]."_AZ','',''); SetCookie('selectedstate','AZ','','');\">";
				break;
				}
			}
		};

	if (in_array('San Francisco/North Bay',$collocalelabel)) echo "<area shape='rect' coords='15,84,115,115' href='s_mediators.shtml' onclick=\"SetCookie('selectedlocale','San Francisco-Oakland-Fremont_CA','',''); SetCookie('selectedlocalelabel','San Francisco/North Bay_CA','',''); SetCookie('selectedstate','CA','','');\">";
	else
		{
		for ($i=0; $i<count($collocale); $i++)
			{
			if ($collocale[$i] == 'San Francisco-Oakland-Fremont_CA')
				{
				echo "<area shape='rect' coords='15,84,115,115' href='s_mediators.shtml' onclick=\"SetCookie('selectedlocale','San Francisco-Oakland-Fremont_CA','',''); SetCookie('selectedlocalelabel','".$collocalelabel[$i]."_CA','',''); SetCookie('selectedstate','CA','','');\">";
				break;
				}
			}
		};

	if (in_array('Seattle',$collocalelabel)) echo "<area shape='rect' coords='20,0,100,25' href='s_mediators.shtml' onclick=\"SetCookie('selectedlocale','Seattle-Tacoma-Bellevue_WA','',''); SetCookie('selectedlocalelabel','Seattle_WA','',''); SetCookie('selectedstate','WA','','');\">";
	else
		{
		for ($i=0; $i<count($collocale); $i++)
			{
			if ($collocale[$i] == 'Seattle-Tacoma-Bellevue_WA')
				{
				echo "<area shape='rect' coords='20,0,100,25' href='s_mediators.shtml' onclick=\"SetCookie('selectedlocale','Seattle-Tacoma-Bellevue_WA','',''); SetCookie('selectedlocalelabel','".$collocalelabel[$i]."_WA','',''); SetCookie('selectedstate','WA','','');\">";
				break;
				}
			}
		};

	if (in_array('Laguna-Irvine-Mission Viejo',$collocalelabel)) echo "<area shape='rect' coords='40,140,110,170' href='s_mediators.shtml' onclick=\"SetCookie('selectedlocale','Santa Ana-Anaheim-Irvine_CA','',''); SetCookie('selectedlocalelabel','Laguna-Irvine-Mission Viejo_CA','',''); SetCookie('selectedstate','CA','','');\">";
	else
		{
		for ($i=0; $i<count($collocale); $i++)
			{
			if ($collocale[$i] == 'Santa Ana-Anaheim-Irvine_CA')
				{
				echo "<area shape='rect' coords='40,140,110,170' href='s_mediators.shtml' onclick=\"SetCookie('selectedlocale','Santa Ana-Anaheim-Irvine_CA','',''); SetCookie('selectedlocalelabel','".$collocalelabel[$i]."_CA','',''); SetCookie('selectedstate','CA','','');\">";
				break;
				}
			}
		};

	if (in_array('Greater Tampa Bay',$collocalelabel)) echo "<area shape='rect' coords='340,210,470,245' href='s_mediators.shtml' onclick=\"SetCookie('selectedlocale','Tampa-St. Petersburg-Clearwater_FL','',''); SetCookie('selectedlocalelabel','Greater Tampa Bay_FL','',''); SetCookie('selectedstate','FL','','');\">";
	else
		{
		for ($i=0; $i<count($collocale); $i++)
			{
			if ($collocale[$i] == 'Tampa-St. Petersburg-Clearwater_FL')
				{
				echo "<area shape='rect' coords='340,210,470,245' href='s_mediators.shtml' onclick=\"SetCookie('selectedlocale','Tampa-St. Petersburg-Clearwater_FL','',''); SetCookie('selectedlocalelabel','".$collocalelabel[$i]."_FL','',''); SetCookie('selectedlocalelabel','Greater Tampa Bay_FL','',''); SetCookie('selectedstate','FL','','');\">";
				break;
				}
			}
		};

	if (in_array('Columbus',$collocalelabel)) echo "<area shape='rect' coords='256,100,333,125' href='s_mediators.shtml' onclick=\"SetCookie('selectedlocale','Columbus_OH','',''); SetCookie('selectedlocalelabel','Columbus_OH','',''); SetCookie('selectedstate','OH','','');\">";
	else
		{
		for ($i=0; $i<count($collocale); $i++)
			{
			if ($collocale[$i] == 'Columbus_OH')
				{
				echo "<area shape='rect' coords='256,100,333,125' href='s_mediators.shtml' onclick=\"SetCookie('selectedlocale','Columbus_OH','',''); SetCookie('selectedlocalelabel','".$collocalelabel[$i]."','',''); SetCookie('selectedstate','OH','','');\">";
				break;
				}
			}
		};
?>
	</map>
	<div style="position: absolute; left: 550px; top: 350px; width: 180px;" class="basictextbold">Click a location on the map to see mediators in that locale.</div>

<?php
// Closing connection
mysql_close($db);
?>
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
