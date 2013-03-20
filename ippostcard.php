<?php
/* 
ippostcard.php is one of the files in the Intellectual Property Library. Components of the library are: iplogo.php, ipbusinesscard.php, ipletterhead.php, ipemailsig.php, ipwirescrossed.php, and ippostcard.php. These files are accessible via the Milonic tramline menu after a user has successfully logged in (i.e. gotten his/her $_SESSION['ValidUserFlag'] set to 'true') via the log-in screen in either updateprofile.php or iplibrary.php.
*/
ob_start();
session_start();
if ($_SESSION['SessValidUserFlag'] != 'true')
	{
	echo "<br><p style='font-family: Arial, Helvetica, sans-serif; margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px; font-weight: bold;'>Access to this page is restricted to New Resolution LLC clients and validated users.</p>";
	exit;
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!-- The ipbusinesscard.php file is opened when a user clicks on the 'Business Card' menu item in Milonic tramline menu item (see /milonic_tramline/menu_data.js) inside iplibrary.php. -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>IP Library | Postcard</title>
<link href="/nrcss.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/milonic_tramline/milonic_src.js"></script>
<noscript>This DHTML Menu requires JavaScript. Please adjust your browser settings to turn on JavaScript</noscript>
<script type="text/javascript" src="/milonic_tramline/mmenudom.js"></script> 
<script type="text/javascript" src="/milonic_tramline/menu_data.js"></script> 
<script language="JavaScript">
<!--
 if (document.images) 
  {
 	 pic1 = new Image(508,334); pic1.src = "/images/JaneDoePostcard_screen_front_large.jpg";
 	 pic2 = new Image(508,334); pic1.src = "/images/JaneDoePostcard_screen_back_large.jpg";
  }

function turnOnf(imageName) // turn-on function for front-of-postcard (f) image
{
if (document.images) 
	{
      document["image2f"].src = "/images/JaneDoePostcard_screen_front_large.jpg"; 
      document["image2b"].style.display = "none"; // Make the back-of-the-card thumbnail disappear b/c it blocks enlarged front image.
	}
}

function turnOnb(imageName) // turn-on function for back-of-postcard (b) image
{
if (document.images) 
	{
      document["image2b"].src = "/images/JaneDoePostcard_screen_back_large.jpg"; 
	}
}

function turnOfff(imageName) // turn-off function for front-of-postcard (f) image
{
if (document.images)
	{
	  document["image2f"].src = "/images/JaneDoePostcard_screen_front.jpg"; 
      document["image2b"].style.display = "block"; // Make the back-of-the-card thumbnail reappear when no longer showing enlarged front image.
	}
}

function turnOffb(imageName) // turn-off function for back-of-card (b) image
{
if (document.images)
	{
	  document["image2b"].src = "/images/JaneDoePostcard_screen_back.jpg"; 
	}
}
  // -->
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
<div style="position: absolute; top: 5px; left: 480px; padding: 0px;">
<form name="LogOutForm" method="post" action="/iplibrary.php">
<input type="hidden" name="LoggedOut" id="LoggedOut" value="true">
<input type="submit" class="submitLinkSmall" value="Log Out">
<!-- I've disguised the submit button as a link -->
</form> 
</div>
<h1 style="margin-left:370px; position:relative; top: 10px; font-size: 22px; color: #425A8D;">Intellectual Property Library</h1>
<HR align="left" size="2px" noshade color="#FF9900" style="margin-top: 22px; size: 2px; height: 0px; position: relative; right: 0px;">
<img src="/images/VerticalLine.bmp" style="position: absolute; top: 0px; left: 122px;">
<h2 style="margin-left: 150px;">POSTCARD DIRECT MAILER</h2>
<p class="text">Select a format and download the postcard:</p>
<div style="margin-left: 150px; margin-top: 0px; width: 800px; font-family: Arial, Helvetica, sans-serif; font-size: 10px; font-size: 12px;">
<table>
<tr>
<td width="340" height="80" valign="top">
<div style="margin-left: 20px;">
<span style="font-size: 12px;">Adobe PDF (.pdf) format:<br></span>
<div style="float: left;">
<form action="/scripts/filedownloader.php" method="get">
<input type="hidden" value="/home/paulme6/public_html/nrmedlic/downloadables/JaneDoePostcard_front.pdf" name="file" id="file">
<input type="submit" name="download" style="margin-top: 8px; width: 120px;" value="Download Front" <?php if (isset($_SESSION['Guest'])) { echo 'disabled'; } else { echo 'class="buttonstyle"'; }; ?>>
</form>
</div>
<div style="float:left; margin-left:20px;">
<form action="/scripts/filedownloader.php" method="get">
<input type="hidden" value="/home/paulme6/public_html/nrmedlic/downloadables/JaneDoePostcard_back.pdf" name="file" id="file">
<input type="submit" name="download" style="margin-top: 8px; width: 120px;" value="Download Back" <?php if (isset($_SESSION['Guest'])) { echo 'disabled'; } else { echo 'class="buttonstyle"'; }; ?>>
</form>
</div>
</div>
</td>
<td rowspan="6" align="right" valign="top" width="460">
<div style="position: absolute; width: 420px;">
<a name="cardfront"></a>
<img name="image2f" src="/images/JaneDoePostcard_screen_front.jpg" alt="New Resolution Mediation postcard (front)" style="position: absolute; top: -50px; right: 70px;">
<span style="position: absolute; top: -60px; right: 0px;">
<a href="#cardfront" onmouseout="turnOfff('image2f')" onmouseover="turnOnf('image2f')"><img src="/images/MagnifyingGlass.jpg" alt="" name="image1f" vspace="10" class="magnfier"></a>
</span>
<br>
<a name="cardback"></a>
<img name="image2b" src="/images/JaneDoePostcard_screen_back.jpg" alt="New Resolution Mediation postcard (back)" style="position: absolute; top: 120px; right: 70px;">
<span style="position: absolute; top: 110px; right: 0px;">
<a href="#cardfront" onmouseout="turnOffb('image2b')" onmouseover="turnOnb('image2b')"><img src="/images/MagnifyingGlass.jpg" alt="" name="image1b" vspace="10" class="magnfier"></a>
</span>
</div>
<div style="margin-left: 0px; margin-top: 295px; font-size: 12px;">Use a publishing application (e.g. InDesign or Publisher) to personalize the generic postcard (back) file with your name, credentials, and contact information. (Our support desk will prepare your print-shop-ready file for you for a $75 fee.)<br>
      <br>
      Inclusion of verbiage &ldquo;Member of the New Resolution network of independent mediators&rdquo; is required. Omission is a breach of your Licensing Agreement and will result in license termination.<br><br> 
You may use any image on the front of the card. The tug-of-war illustration is available from <?php if (isset($_SESSION['Guest'])) { echo '[CLIENTS ONLY]'; } else { echo 'Images.com, Inc'; }; ?>. (New Resolution LLC cannot transfer rights to this content. You may purchase royalty-free rights for use in your own marketing materials <?php if (isset($_SESSION['Guest'])) { echo '<a href="#" onclick="javascript: alert(\'This resource is reserved for client-access only.\');">'; } else { echo '<a href="http://www.images.com/search/?query=113792&order=relevance&RM=CHECKED&RF=CHECKED&color=CHECKED&grey=CHECKED&illustration=CHECKED&photography=CHECKED&filter=CHECKED&square=CHECKED&horizontal=CHECKED&panoramic=CHECKED&vertical=CHECKED&vcd=CHECKED" alt="Tug-of-War from images.com" target="_blank">'; }; ?>here</a>.)<br>
  <br>
Fonts on back of card: Haettenschweiler, Avenir LT 65 Medium, Palatino Linotype, Britannic Bold, Kalinga, Microsoft Sans Serif<br><br>
Line color on front of card (tangerine): 255/153/0 (RGB) and 0/0.4/1/0 (CMYK)<br><br>
Logotype: Century Gothic<br><br>
Size: oversized (8.75" x 5.75") for 8.5" x 5.5" with bleed.
</div>
</td>
</tr>
<tr>
<td height="80" valign="top">
<div style="margin-left: 20px;">
<span style="font-size: 12px;">Adobe Photoshop (.psd) format:<br></span>
<div style="float: left;">
<form action="/scripts/filedownloader.php" method="get">
<input type="hidden" value="/home/paulme6/public_html/nrmedlic/downloadables/JaneDoePostcard_front.psd" name="file" id="file">
<input type="submit" name="download" style="margin-top: 8px; width: 120px;" value="Download Front" <?php if (isset($_SESSION['Guest'])) { echo 'disabled'; } else { echo 'class="buttonstyle"'; }; ?>>
</form>
</div>
<div style="float:left; margin-left:20px;">
<form action="/scripts/filedownloader.php" method="get">
<input type="hidden" value="/home/paulme6/public_html/nrmedlic/downloadables/JaneDoePostcard_back.psd" name="file" id="file">
<input type="submit" name="download" style="margin-top: 8px; width: 120px;" value="Download Back" <?php if (isset($_SESSION['Guest'])) { echo 'disabled'; } else { echo 'class="buttonstyle"'; }; ?>>
</form>
</div>
</div>
</td>
</tr>
<tr>
<td height="80" valign="top">
<div style="margin-left: 20px;">
<span style="font-size: 12px;">JPEG (.jpg) format:<br></span>
<div style="float: left;">
<form action="/scripts/filedownloader.php" method="get">
<input type="hidden" value="/home/paulme6/public_html/nrmedlic/downloadables/JaneDoePostcard_front.jpg" name="file" id="file">
<input type="submit" name="download" style="margin-top: 8px; width: 120px;" value="Download Front" <?php if (isset($_SESSION['Guest'])) { echo 'disabled'; } else { echo 'class="buttonstyle"'; }; ?>>
</form>
</div>
<div style="float:left; margin-left:20px;">
<form action="/scripts/filedownloader.php" method="get">
<input type="hidden" value="/home/paulme6/public_html/nrmedlic/downloadables/JaneDoePostcard_back.jpg" name="file" id="file">
<input type="submit" name="download" style="margin-top: 8px; width: 120px;" value="Download Back" <?php if (isset($_SESSION['Guest'])) { echo 'disabled'; } else { echo 'class="buttonstyle"'; }; ?>>
</form>
</div>
<br><br>
</div></td>
</tr>
<tr>
<td height="80" valign="top">
<div style="margin-left: 20px;">
<span style="font-size: 12px;">Postscript (.ps) format:<br></span>
<div style="float: left;">
<form action="/scripts/filedownloader.php" method="get">
<input type="hidden" value="/home/paulme6/public_html/nrmedlic/downloadables/JaneDoePostcard_front.ps" name="file" id="file">
<input type="submit" name="download" style="margin-top: 8px; width: 120px;" value="Download Front" <?php if (isset($_SESSION['Guest'])) { echo 'disabled'; } else { echo 'class="buttonstyle"'; }; ?>>
</form>
</div>
<div style="float:left; margin-left:20px;">
<form action="/scripts/filedownloader.php" method="get">
<input type="hidden" value="/home/paulme6/public_html/nrmedlic/downloadables/JaneDoePostcard_back.ps" name="file" id="file">
<input type="submit" name="download" style="margin-top: 8px; width: 120px;" value="Download Back" <?php if (isset($_SESSION['Guest'])) { echo 'disabled'; } else { echo 'class="buttonstyle"'; }; ?>>
</form>
</div>
<br><br>
</div></td>
</tr>
<tr>
<td height="80" valign="top">
<div style="margin-left: 20px;">
<span style="font-size: 12px;">Publisher (.pub) format:<br></span>
<div style="float: left;">
<form action="/scripts/filedownloader.php" method="get">
<input type="hidden" value="/home/paulme6/public_html/nrmedlic/downloadables/JaneDoePostcard_front.pub" name="file" id="file">
<input type="submit" name="download" style="margin-top: 8px; width: 120px;" value="Download Front" <?php if (isset($_SESSION['Guest'])) { echo 'disabled'; } else { echo 'class="buttonstyle"'; }; ?>>
</form>
</div>
<div style="float:left; margin-left:20px;">
<form action="/scripts/filedownloader.php" method="get">
<input type="hidden" value="/home/paulme6/public_html/nrmedlic/downloadables/JaneDoePostcard_back.pub" name="file" id="file">
<input type="submit" name="download" style="margin-top: 8px; width: 120px;" value="Download Back" <?php if (isset($_SESSION['Guest'])) { echo 'disabled'; } else { echo 'class="buttonstyle"'; }; ?>>
</form>
</div>
<br><br>
</div>

</td>
</tr>
<tr>
<td valign="top" height="200">
<div style="margin-left: 20px;">
<span style="font-size: 12px;">TIFF (.tif) format:<br></span>
<div style="float: left;">
<form action="/scripts/filedownloader.php" method="get">
<input type="hidden" value="/home/paulme6/public_html/nrmedlic/downloadables/JaneDoePostcard_front.tif" name="file" id="file">
<input type="submit" name="download" style="margin-top: 8px; width: 120px;" value="Download Front" <?php if (isset($_SESSION['Guest'])) { echo 'disabled'; } else { echo 'class="buttonstyle"'; }; ?>>
</form>
</div>
<div style="float:left; margin-left:20px;">
<form action="/scripts/filedownloader.php" method="get">
<input type="hidden" value="/home/paulme6/public_html/nrmedlic/downloadables/JaneDoePostcard_back.tif" name="file" id="file">
<input type="submit" name="download" style="margin-top: 8px; width: 120px;" value="Download Back" <?php if (isset($_SESSION['Guest'])) { echo 'disabled'; } else { echo 'class="buttonstyle"'; }; ?>>
</form>
</div>
<br><br>
</div>
</td>
</tr>
</table>
<br>
<div id="copyright">&copy; <?php echo date('Y'); ?> New Resolution LLC. All rights reserved.</div>
</div>
<?php
ob_flush();
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
</html>
