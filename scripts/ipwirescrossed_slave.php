<?php
/*
ipwirescrossed_slave.php is the slave script to ipwirescrossed.php, used by the user to create the Wires Crossed HTML email template and content (via a link from the Milonic tramline menu, after user logged in via iplibrary.php). The slave combines the user's form input from ipwirescrossed.php with HTML to effect an HTML template (with placeholder content) as well as full versions of the Wires Crossed email newsletter/ezine with full content.
	It uses the PEAR extension HTML_Page2 as a basic HTML parser so that users can edit the HTML source code and then preview the parsed HTML live "on the fly" before downloading the HTML. The user effects a download by clicking a 'Download' button inside a pop-up window that displays the previewable HTML.
    The action script for this download button is wirescrossedfilecreatordownloader.php, which creates the HTML files (i.e. stores them on the server) for that particular user and allows him/her to easily download them with a single click, not having to worry about right-clicking links, etc.
 */
// Start a session
session_start();
if ($_SESSION['SessValidUserFlag'] != 'true')
	{
	echo "<br><p style='font-family: Arial, Helvetica, sans-serif; margin-left: 50px; margin-right: 50px; margin-top: 40px; font-size: 14px; font-weight: bold;'>Access to this page is restricted to New Resolution LLC clients and validated users.</p>";
	exit;
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Create HTML Wires Crossed Template and Content | Slave Script</title>
<link href="/nrcss.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/milonic_tramline/milonic_src.js"></script>
<noscript>This DHTML Menu requires JavaScript. Please adjust your browser settings to turn on JavaScript</noscript>
<script type="text/javascript" src="/milonic_tramline/mmenudom.js"></script> 
<script type="text/javascript" src="/milonic_tramline/menu_data.js"></script> 
<script type="text/javascript" language="javascript" src="/scripts/boxover.js"></script>
<script language="JavaScript" src="/scripts/windowpops.js" type='text/javascript'></script>
</head>
<body>
<?php
// Create short variable names
$Name = $_POST['name'];
$EntityName = $_POST['entityname'];
$StreetAddress = $_POST['streetaddress'];
$City = $_POST['city'];
$State = $_POST['state'];
$Zip = $_POST['zip'];
$Email = $_POST['email'];
$UseProfessionalEmail = $_POST['UseProfessionalEmail'];
$Tel1 = $_POST['tel1'];   // tel1,2,3 are area code, local code, and four-digit number
$Tel2 = $_POST['tel2'];
$Tel3 = $_POST['tel3'];
$Ext = $_POST['ext'];

// Prevent cross-site scripting
$Name = htmlspecialchars($Name, ENT_QUOTES);
$EntityName = htmlspecialchars($EntityName, ENT_QUOTES);
$StreetAddress = htmlspecialchars($StreetAddress, ENT_QUOTES);
$City = htmlspecialchars($City, ENT_QUOTES);
$Zip = htmlspecialchars($Zip, ENT_QUOTES);
$Email = htmlspecialchars($Email, ENT_QUOTES);
$Tel1 = htmlspecialchars($Tel1, ENT_QUOTES);
$Tel2 = htmlspecialchars($Tel2, ENT_QUOTES);
$Tel3 = htmlspecialchars($Tel3, ENT_QUOTES);
$Ext = htmlspecialchars($Ext, ENT_QUOTES);

// Examine the value of the UseProfessionalEmail check-box in ipemailsig.php. If it was checked (i.e. value is 1) then user is using his/her ProfessionalEmail and assign $ProfessionalEmail to $Email. Otherwise, let $Email retain its posted value.
if ($UseProfessionalEmail == 1) $Email = $_SESSION['ProfessionalEmail'];

// Compile HTML for (customized) Content1 (i.e. Edition #1):
$WCcontent1HTML = '<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<style type="text/css">body,.backgroundTable{background-color:#FFFFCC;}#contentTable{border:0px none #000000;margin-top:10px;}.headerTop{background-color:#000000;border-top:0px solid #000000;border-bottom:2px inset #000000;text-align:center;padding:0px;}.adminText{font-size:10px;color:#663300;line-height:200%;font-family:Verdana;text-decoration:none;}.headerBar{background-color:#000000;border-top:1px solid #FFFFFF;border-bottom:2px solid #333333;padding:0px;}.headerBarText{color:#333333;font-size:30px;font-family:Verdana;font-weight:normal;text-align:left;}.title{font-size:20px;font-weight:bold;color:#CC6600;font-family:arial;line-height:110%;}.subTitle{font-size:11px;font-weight:normal;color:#666666;font-style:italic;font-family:arial;}.defaultText{font-size:12px;color:#000000;line-height:150%;font-family:Verdana;width:400px;background-color:#FFFFFF;padding:20px;}.sideColumn{background-color:#EBE7CE;border-left:1px groove #CCCCCC;text-align:left;width:200px;padding:20px;margin:0px;}.sideColumnText{font-size:11px;font-weight:normal;color:#333333;font-family:arial;line-height:150%;}.sideColumnTitle{font-size:15px;font-weight:bold;color:#00707F;font-family:arial;line-height:150%;}.footerRow{background-color:#000000;border-top:2px solid #000000;padding:20px;}.footerText{font-size:10px;color:#996600;line-height:100%;font-family:verdana;}a,a:link,a:visited{color:#800000;text-decoration:underline;font-weight:normal;}.headerTop a{color:#663300;text-decoration:none;font-weight:normal;}.footerRow a{color:#800000;text-decoration:underline;font-weight:normal;}body,.backgroundTable{background-color:#FFC687;}td{font-size:12px;color:#000000;line-height:150%;font-family:trebuchet ms;}a{color:#EE9B3B;}.adminText,a.adminText:link,a.adminText:visited{font-size:10px;color:#FFFFFF;line-height:200%;font-family:verdana;text-decoration:underline;}</style></head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="background-color: #FFC687;">


<table width="100%" cellpadding="10" cellspacing="0" class="backgroundTable" style="background-color: #FFC687;">
<tr>
<td valign="top" align="center" style="font-size: 12px;color: #000000;line-height: 150%;font-family: trebuchet ms;">

<table id="contentTable" cellspacing="0" cellpadding="0" width="600" style="border: 0px none #000000;margin-top: 10px;"><tr><td style="font-size: 12px;color: #000000;line-height: 150%;font-family: trebuchet ms;">

<table width="600" cellpadding="0" cellspacing="0">
<tr>

<td class="headerTop" align="right" style="font-size: 12px;color: #000000;line-height: 150%;font-family: trebuchet ms;background-color: #000000;border-top: 0px solid #000000;border-bottom: 2px inset #000000;text-align: center;padding: 0px;"><div class="adminText" style="font-size: 10px;color: #FFFFFF;line-height: 200%;font-family: verdana;text-decoration: underline;"></div></td>
</tr>

<tr>
<td align="left" valign="middle" class="headerBar" style="font-size: 12px;color: #000000;line-height: 150%;font-family: trebuchet ms;background-color: #000000;border-top: 1px solid #FFFFFF;border-bottom: 2px solid #333333;padding: 0px;"><div class="headerBarText" style="color: #333333;font-size: 30px;font-family: Verdana;font-weight: normal;text-align: left;">
<div style="text-align: center;"><a href="http://www.newresolutionmediation.com" style="color: #800000;text-decoration: underline;font-weight: normal;"><img height="75" width="600" src="http://nrmedlic.paulmerlyn.com/images/wires_crossed_masthead.jpg" alt="Masthead for Wires Crossed from New Resolution" border="0" style="margin: 0; padding: 0;"></a></div>
</div></td>
</tr>

</table>

<table width="600" cellpadding="20" cellspacing="0" class="bodyTable">
<tr>

<td valign="top" class="defaultText" align="left" style="font-size: 12px;color: #000000;line-height: 150%;font-family: Verdana;width: 400px;background-color: #FFFFFF;padding: 20px;">
<span class="title" style="font-size: 20px;font-weight: bold;color: #CC6600;font-family: arial;line-height: 110%;">Getting Creative in Mediation</span><br>
<span class="subTitle" style="font-size: 11px;font-weight: normal;color: #666666;font-style: italic;font-family: arial;">How One Couple Solved a Nagging Problem</span> <font size="2">
<p>Mediation, unlike therapy or litigation, is a problem-solving process. As such, it\'s also a place to get creative. Think about the following problem.</p>
<p>Joe and Melinda were getting divorced. Melinda, a school librarian, had always been much more organized. Joe, on the other hand, was more casual. He marched to a different beat. It was a difference in style that had caused a lot of tension during the marriage.</p>
<p>Having worked out a joint custody schedule, they agreed that Melinda would be the primary contact for bills on behalf of the children, including school fees, insurance premiums, and contributions to a 529 college savings plan. She\'d keep track of these expenses, and Joe would reimburse her at the end of the month.</p>
<p>But Melinda was worried. She knew she\'d have to continually remind Joe about reimbursement, and that would surely undermine their co-parenting relationship.</p>
<p>Joe didn\'t like the idea of interest charges for late payments. He\'d resent paying Melinda punitive &quot;fines.&quot; Melinda, meanwhile, didn\'t want the role of the nagging ex-wife that she would inevitably become if Joe faced no consequences for being late.</p>

<p>Mediation allowed them to reframe their conflicting styles into a common problem: How could they ensure Melinda received timely reimbursement without Joe feeling resentment for the occasional month when he might be late? Here\'s what they agreed:</p>
<p>If Joe was late, he would pay into the children\'s college savings fund the larger of $50 or 10% of the overdue amount, up to a maximum of $250. Now, the only beneficiary of a late payment would be the children. And that was an outcome both Joe and Melinda felt good about!</p>
</td>

<td valign="top" class="sideColumn" align="left" style="margin: 0px;font-size: 12px;color: #000000;line-height: 150%;font-family: trebuchet ms;background-color: #EBE7CE;border-left: 1px groove #CCCCCC;text-align: left;width: 200px;padding: 20px;">
<div class="sideColumnText" style="font-size: 11px;font-weight: normal;color: #333333;font-family: arial;line-height: 150%;">
<span class="sideColumnTitle" style="font-size: 15px;font-weight: bold;color: #00707F;font-family: arial;line-height: 150%;">About New Resolution</span><br>
New Resolution LLC provides dispute resolution services through its network of independent mediators. Fair and impartial, we guide clients to settlement using a proven problem-solving process.
<p>Visit <a target="_blank" href="http://www.newresolutionmediation.com" style="color: #800000;text-decoration: underline;font-weight: normal; font-size: 11px;">newresolutionmediation.com</a> or call '.$Tel1.'.'.$Tel2.'.'.$Tel3;
if ($Ext != '' && !is_null($Ext)) $WCcontent1HTML .= ' Ext '.$Ext;
$WCcontent1HTML .= ' for consultations and appointments.</p>

<p>We help people with the following types of disputes:</p>
<img height="11" alt="Arrow" hspace="6" width="10" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Divorce/separation<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Child custody<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Co-parenting<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Domestic partnerships<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Post-marital agreements<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Inheritance/estates<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Small/family businesses<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Family conflict<br>

<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Interpersonal transactions<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Elder care<br><br>';

if ($EntityName == '' || is_null($EntityName)) $WCcontent1HTML .= '<strong>Mediation Office of '.$Name.'</strong><br>';
else $WCcontent1HTML .= '<strong>'.$EntityName.'</strong><br>';
$WCcontent1HTML .= $StreetAddress.'<br>'.$City.', '.$State.' '.$Zip.'</span><br>Tel '.$Tel1.'.'.$Tel2.'.'.$Tel3;
if ($Ext != '' && !is_null($Ext)) $WCcontent1HTML .= ' Ext '.$Ext;
$WCcontent1HTML .= '<br></strong><a href="mailto:'.$Email.'?subject=Mediation%20Inquiry%2FWires%20Crossed" style="color: #800000;text-decoration: underline;font-weight: normal; font-size: 11px;">'.$Email.'</a><br>
<p>In addition, we provide service in these satellite locations:</p>
<p>Your satellite location #1<br>
Your satellite location #2<br>
Your satellite location #3<br>
<img height="96" alt="Animated Logo" width="204" src="http://nrmedlic.paulmerlyn.com/images/anim_logo.gif"><span style="position: relative; bottom: 70px; left: 204px;">&reg;</span></div>
</td>

</tr>

<tr>
<td class="footerRow" valign="top" colspan="2" align="left" style="font-size: 12px;color: #000000;line-height: 150%;font-family: trebuchet ms;background-color: #000000;border-top: 2px solid #000000;padding: 20px;">
<div class="footerText" style="font-size: 10px;color: #996600;line-height: 100%;font-family: verdana;"><p><a href="*|UNSUB|*" style="color: #800000;text-decoration: underline;font-weight: normal; font-size: 10px;">Remove me</a> from this list. <a href="*|FORWARD|*" style="color: #800000;text-decoration: underline;font-weight: normal; font-size: 10px;">Forward</a> this issue of <em>Wires Crossed</em>&trade; to a friend.</p> <p><br> <em>Wires Crossed</em>&trade; is published by: <strong><span style="margin-left: 15px;">';

if ($EntityName == '' || is_null($EntityName)) $WCcontent1HTML .= 'The Mediation Office of '.$Name.'</span><br>';
else $WCcontent1HTML .= $EntityName.'</span><br>';
$WCcontent1HTML .= '<span style="margin-left: 196px;">'.$StreetAddress.', '.$City.', '.$State.' '.$Zip.'</span><br> <span style="margin-left: 196px;">Tel '.$Tel1.'.'.$Tel2.'.'.$Tel3;
if ($Ext != '' && !is_null($Ext)) $WCcontent1HTML .= ' Ext '.$Ext;
$WCcontent1HTML .= '</span><br></strong><span style="margin-left: 196px;"><a style="font-size: 10px;" href="mailto:'.$Email.'?subject=Mediation%20Inquiry%2FWires%20Crossed" style="color: #800000;text-decoration: underline;font-weight: normal;">'.$Email.'</a></span><br><br>'.$Name.' is a member of the New Resolution network of independent mediators.</p> <p>&copy; '.date("Y").' New Resolution LLC. All rights reserved.</p></div>

</td>
</tr>

</table>

</td>
</tr>
</table>

</td>
</tr>
</table>

</body>
</html>';


// Compile HTML for (customized) Content2 (i.e. Edition #2):
$WCcontent2HTML = '<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<style type="text/css">body,.backgroundTable{background-color:#FFFFCC;}#contentTable{border:0px none #000000;margin-top:10px;}.headerTop{background-color:#000000;border-top:0px solid #000000;border-bottom:2px inset #000000;text-align:center;padding:0px;}.adminText{font-size:10px;color:#663300;line-height:200%;font-family:Verdana;text-decoration:none;}.headerBar{background-color:#000000;border-top:1px solid #FFFFFF;border-bottom:2px solid #333333;padding:0px;}.headerBarText{color:#333333;font-size:30px;font-family:Verdana;font-weight:normal;text-align:left;}.title{font-size:20px;font-weight:bold;color:#CC6600;font-family:arial;line-height:110%;}.subTitle{font-size:11px;font-weight:normal;color:#666666;font-style:italic;font-family:arial;}.defaultText{font-size:12px;color:#000000;line-height:150%;font-family:Verdana;width:400px;background-color:#FFFFFF;padding:20px;}.sideColumn{background-color:#EBE7CE;border-left:1px groove #CCCCCC;text-align:left;width:200px;padding:20px;margin:0px;}.sideColumnText{font-size:11px;font-weight:normal;color:#333333;font-family:arial;line-height:150%;}.sideColumnTitle{font-size:15px;font-weight:bold;color:#00707F;font-family:arial;line-height:150%;}.footerRow{background-color:#000000;border-top:2px solid #000000;padding:20px;}.footerText{font-size:10px;color:#996600;line-height:100%;font-family:verdana;}a,a:link,a:visited{color:#800000;text-decoration:underline;font-weight:normal;}.headerTop a{color:#663300;text-decoration:none;font-weight:normal;}.footerRow a{color:#800000;text-decoration:underline;font-weight:normal;}body,.backgroundTable{background-color:#FFC687;}td{font-size:12px;color:#000000;line-height:150%;font-family:trebuchet ms;}a{color:#EE9B3B;}.adminText,a.adminText:link,a.adminText:visited{font-size:10px;color:#FFFFFF;line-height:200%;font-family:verdana;text-decoration:underline;}</style></head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="background-color: #FFC687;">


<table width="100%" cellpadding="10" cellspacing="0" class="backgroundTable" style="background-color: #FFC687;">
<tr>
<td valign="top" align="center" style="font-size: 12px;color: #000000;line-height: 150%;font-family: trebuchet ms;">

<table id="contentTable" cellspacing="0" cellpadding="0" width="600" style="border: 0px none #000000;margin-top: 10px;"><tr><td style="font-size: 12px;color: #000000;line-height: 150%;font-family: trebuchet ms;">

<table width="600" cellpadding="0" cellspacing="0">
<tr>

<td class="headerTop" align="right" style="font-size: 12px;color: #000000;line-height: 150%;font-family: trebuchet ms;background-color: #000000;border-top: 0px solid #000000;border-bottom: 2px inset #000000;text-align: center;padding: 0px;"><div class="adminText" style="font-size: 10px;color: #FFFFFF;line-height: 200%;font-family: verdana;text-decoration: underline;"></div></td>
</tr>

<tr>
<td align="left" valign="middle" class="headerBar" style="font-size: 12px;color: #000000;line-height: 150%;font-family: trebuchet ms;background-color: #000000;border-top: 1px solid #FFFFFF;border-bottom: 2px solid #333333;padding: 0px;"><div class="headerBarText" style="color: #333333;font-size: 30px;font-family: Verdana;font-weight: normal;text-align: left;">
<div style="text-align: center;"><a href="http://www.newresolutionmediation.com" style="color: #800000;text-decoration: underline;font-weight: normal;"><img height="75" width="600" src="http://nrmedlic.paulmerlyn.com/images/wires_crossed_masthead.jpg" alt="Masthead for Wires Crossed from New Resolution" border="0" style="margin: 0; padding: 0;"></a></div>
</div></td>
</tr>

</table>

<table width="600" cellpadding="20" cellspacing="0" class="bodyTable">
<tr>

<td valign="top" class="defaultText" align="left" style="font-size: 12px;color: #000000;line-height: 150%;font-family: Verdana;width: 400px;background-color: #FFFFFF;padding: 20px;">

<span class="title" style="font-size: 20px;font-weight: bold;color: #CC6600;font-family: arial;line-height: 110%;">In the Eye of the Storm</span><br>
<span class="subTitle" style="font-size: 11px;font-weight: normal;color: #666666;font-style: italic;font-family: arial;">Mediator Boot Camp: Part 1</span> <font size="2">
<p>Have you ever watched two people quarrel or even yell at one another? Perhaps you sometimes want to intervene, but don\'t always know the best way.</p>
<p>Mediators, of course, have to intervene. It\'s what our clients expect us to do. Read on for the first in a series of short articles that will show you <em>how</em> mediators intervene and <em>why</em> they so often succeed in resolving even the most bitter and long-standing disputes.</p>
<p>I think it will help you be a better mediator in your own life, whether you\'re stepping into a conflict around the water cooler, across the kitchen table, or between neighbors on your block.</p>
<p>You can begin to sharpen your conflict resolution skills by learning to recognize conflicts by type. Once you know the type of conflict, you\'ll also know its causes and will then be better able to intervene effectively i.e. apply the right conflict resolution tools for that type of conflict.</p>
<p>Look at the five types in the circle<sup>*</sup> below.</p>

<img height="320" alt="Causes of Conflict" width="320" src="http://nrmedlic.paulmerlyn.com/images/ConflictCircleWiresCrossed.jpg" /><br clear="all" />
<br />
<font style="font-weight: bold; font-size: 11px; color: #00707f; line-height: 2.5; font-family: Geneva, Verdana, sans-serif">RELATIONSHIP CONFLICTS</font><br />
Relationship conflicts are breaks in our ability to relate to one another. They are usually caused by miscommunication, strong emotions, preconceived opinions (stereotyping), or repetitive negative behavior. When English satirist <a target="_blank" href="http://www.rhymes.org.uk/a32-i-do-not-like-thee-doctor-fell.htm">Tom Brown</a> penned this famous poem in 1680, he was experiencing a relationship conflict:
<p style="font-size: 12px; margin-left: 50px; line-height: 1.5">I do not like thee, Doctor Fell,<br />
The reason why I cannot tell;<br />
But this I know, and know full well,<br />
I do not like thee, Doctor Fell.</p>
<font style="font-weight: bold; font-size: 11px; color: #00707f; line-height: 2.5; font-family: Geneva, Verdana, sans-serif">DATA CONFLICTS</font><br />

Data conflicts are quite different. These are caused by a lack of information (e.g. not knowing the market value of a small business), misinformation (e.g. belief in a false rumor about a job applicant), or different ways of looking at the same information (e.g. whether crime statistics indicate a safer or more dangerous neighborhood).<br />
<br />
<font style="font-weight: bold; font-size: 11px; color: #00707f; line-height: 2.5; font-family: Geneva, Verdana, sans-serif">VALUES CONFLICTS</font><br />
In values conflicts, different ideologies, worldviews, and lifestyles are at the heart of the conflict. Disputes over universal healthcare, withdrawal from Iraq, same-sex marriage, and increasing taxes on tobacco likely stem from a difference in values.<br />
<br />
<font style="font-weight: bold; font-size: 11px; color: #00707f; line-height: 2.5; font-family: Geneva, Verdana, sans-serif">INTEREST CONFLICTS</font><br />
Interest conflicts are typically about resources &mdash; for example, three cities in competition for an urban revitalization grant, or roommates who want to watch different TV shows.<br />
<br />
<font style="font-weight: bold; font-size: 11px; color: #00707f; line-height: 2.5; font-family: Geneva, Verdana, sans-serif">STRUCTURAL CONFLICTS</font><br />
Structural conflicts are mostly caused by actual or perceived power inequality. Disputes between corporations and labor unions are usually structural. A dispute between a manager and the CEO\'s admin-istrative assistant might also be structural.<br />
<br />
<br />
<span style="line-height:150%;font-family:arial;font-weight:bold;font-size:15px;color:#00707F;">Conflict in the Real World</span>
<p>Disputes in the real world don\'t always fit so neatly into a single type. That means we need may to work a little harder to figure out the causes of a conflict.</p>
<p>Consider this dispute between two parents: Mom favors a private boarding school. Dad wants his child to attend the local public school. They\'ve both compared scholastic achievement data from each school. Their argument is probably fueled by a values conflict, a data conflict, and perhaps a relationship conflict too.</p>
<p>Similarly, an argument between managers over allocation of pay raises to members of their staff might be both a structural and an interest conflict. And a dispute over spousal support may even comprise all five types of conflict: relationship, data, values, structural, and interest.</p>
<p>In the next edition of <em>Wires Crossed</em> we\'ll begin to look at <em>how</em> to intervene for the five types of conflict. In other words, I\'ll talk about what mediators actually say or do, and how you can apply those techniques to settle conflicts.</p>

<p>In the meantime, congratulations! You\'re about to graduate as a cadet from the Mediator Boot Camp. But first, test your knowledge by taking the Mediator Challenge below.</p>
<br clear="all" />

<span style="line-height:110%;font-family:arial;font-weight:bold;font-size:20px;color:#CC6600;">Mediator Challenge</span><br />
<span style="font-family:arial;font-weight:normal;font-size:11px;color:#666666;font-style:italic;">Flex your mediator muscles with this short exercise</span>
<p><img height="71" alt="Champion Icon" width="70" align="left" src="http://nrmedlic.paulmerlyn.com/images/champion_icon.jpg" />Tim and Diane keep getting their wires crossed. Can you match each conflict to the correct type? (Answers below)</p>
<div style="font-size: 8px">
<table width="320" border="0" cell-spacing="0" cell-padding="0">
    <tbody>
        <tr>
            <td valign="top">1.</td>

<td>Diane invited&nbsp;the guys from her investment<br/> 
club to a New Year\'s Eve party. Tim fears<br/>
the worst: a night of dividend forecasts and<br/>
bond yields!</td>
        </tr>
        <tr>
        </tr>
        <tr>

            <td valign="top">2.</td>
<td>It\'s hot, and Diane feels like lemonade. Tim, <br/>
however, wants to make his specialty lemon cheesecake.</td>
        </tr>
        <tr>
            <td valign="top">3.</td>
<td>Tim is a convert to the health benefits of<br/>
organic food. Diane remains skeptical.</td>

        </tr>
        <tr>
            <td valign="top">4.</td>
<td>Diane doesn\'t appreciate Tim\'s ideas on<br/>
what to do with an inheritance from her<br/>
grandmother.</td>
        </tr>
        <tr>

            <td valign="top">5.</td>
<td>Tim can never pass a homeless person with-<br/>
out reaching for some loose change. Diane<br/>
calls him &quot;an enabler.&quot;</td>
        </tr>
        <tr>
<td valign="top">6.</td>
<td>Tim is ready to refinance his student loans<br/>

at 5.25%. Diane thinks he can do better.</td>
        </tr>
        <tr>
            <td valign="top">7.</td>
<td>Diane wants their teenage son to follow his<br/>
heart and study music. That sounds foolish<br/>
to Tim, who sees prestige and money in<br/>
medicine or biotech.</td>

        </tr>
        <tr>
            <td valign="top">8.</td>
<td>All this arguing has really gotten to Tim. He\'s<br/>
constantly snapping at Diane, and she<br/>
doesn\'t like it!</td>
        </tr>
    </tbody>

</table>
</div>
<br />
<strong>Answers: </strong><span style="font-size: 11px; color: #c0c0c0">5=Values; 2=Interest; 6=Data; 7=Values; 4=Structural; 8=Relationship; 1=Relationship; 3=Data</span> </font><br />
<br />
<p style="font-size: 9px; line-height: 1.2">* Adapted from Christopher W. Moore. <em>The Mediation Process</em>.<br />
&nbsp;&nbsp;&nbsp;Jossey-Bass. 2003. &copy; Christopher W. Moore</p>

</td>

<td valign="top" class="sideColumn" align="left" style="margin: 0px;font-size: 12px;color: #000000;line-height: 150%;font-family: trebuchet ms;background-color: #EBE7CE;border-left: 1px groove #CCCCCC;text-align: left;width: 200px;padding: 20px;">
<div class="sideColumnText" style="font-size: 11px;font-weight: normal;color: #333333;font-family: arial;line-height: 150%;">
<span class="sideColumnTitle" style="font-size: 15px;font-weight: bold;color: #00707F;font-family: arial;line-height: 150%;">About New Resolution</span><br>
New Resolution LLC provides dispute resolution services through its network of independent mediators. Fair and impartial, we guide clients to settlement using a proven problem-solving process.
<p>Visit <a target="_blank" href="http://www.newresolutionmediation.com" style="color: #800000;text-decoration: underline;font-weight: normal; font-size: 11px;">newresolutionmediation.com</a> or call '.$Tel1.'.'.$Tel2.'.'.$Tel3;
if ($Ext != '' && !is_null($Ext)) $WCcontent2HTML .= ' Ext '.$Ext;
$WCcontent2HTML .= ' for consultations and appointments.</p>

<p>We help people with the following types of disputes:</p>
<img height="11" alt="Arrow" hspace="6" width="10" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Divorce/separation<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Child custody<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Co-parenting<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Domestic partnerships<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Post-marital agreements<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Inheritance/estates<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Small/family businesses<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Family conflict<br>

<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Interpersonal transactions<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Elder care<br><br>';

if ($EntityName == '' || is_null($EntityName)) $WCcontent2HTML .= '<strong>Mediation Office of '.$Name.'</strong><br>';
else $WCcontent2HTML .= '<strong>'.$EntityName.'</strong><br>';
$WCcontent2HTML .= $StreetAddress.'<br>'.$City.', '.$State.' '.$Zip.'</span><br>Tel '.$Tel1.'.'.$Tel2.'.'.$Tel3;
if ($Ext != '' && !is_null($Ext)) $WCcontent2HTML .= ' Ext '.$Ext;
$WCcontent2HTML .= '<br></strong><a href="mailto:'.$Email.'?subject=Mediation%20Inquiry%2FWires%20Crossed" style="color: #800000;text-decoration: underline;font-weight: normal; font-size: 11px;">'.$Email.'</a><br>
<p>In addition, we provide service in these satellite locations:</p>
<p>Your satellite location #1<br>
Your satellite location #2<br>
Your satellite location #3<br>
<img height="96" alt="Animated Logo" width="204" src="http://nrmedlic.paulmerlyn.com/images/anim_logo.gif"><span style="position: relative; bottom: 70px; left: 204px;">&reg;</span></div>
</td>

</tr>

<tr>
<td class="footerRow" valign="top" colspan="2" align="left" style="font-size: 12px;color: #000000;line-height: 150%;font-family: trebuchet ms;background-color: #000000;border-top: 2px solid #000000;padding: 20px;">
<div class="footerText" style="font-size: 10px;color: #996600;line-height: 100%;font-family: verdana;"><p><a href="*|UNSUB|*" style="color: #800000;text-decoration: underline;font-weight: normal; font-size: 10px;">Remove me</a> from this list. <a href="*|FORWARD|*" style="color: #800000;text-decoration: underline;font-weight: normal; font-size: 10px;">Forward</a> this issue of <em>Wires Crossed</em>&trade; to a friend.</p> <p><br> <em>Wires Crossed</em>&trade; is published by: <strong><span style="margin-left: 15px;">';

if ($EntityName == '' || is_null($EntityName)) $WCcontent2HTML .= 'The Mediation Office of '.$Name.'</span><br>';
else $WCcontent2HTML .= $EntityName.'</span><br>';
$WCcontent2HTML .= '<span style="margin-left: 196px;">'.$StreetAddress.', '.$City.', '.$State.' '.$Zip.'</span><br> <span style="margin-left: 196px;">Tel '.$Tel1.'.'.$Tel2.'.'.$Tel3;
if ($Ext != '' && !is_null($Ext)) $WCcontent2HTML .= ' Ext '.$Ext;
$WCcontent2HTML .= '</span><br></strong><span style="margin-left: 196px;"><a style="font-size: 10px;" href="mailto:'.$Email.'?subject=Mediation%20Inquiry%2FWires%20Crossed" style="color: #800000;text-decoration: underline;font-weight: normal;">'.$Email.'</a></span><br><br>'.$Name.' is a member of the New Resolution network of independent mediators.</p> <p>&copy; '.date("Y").' New Resolution LLC. All rights reserved.</p></div>

</td>
</tr>

</table>

</td>
</tr>
</table>

</td>
</tr>
</table>

</body>
</html>';


// Compile HTML for (customized) Content3 (i.e. Edition #3):
$WCcontent3HTML = '<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<style type="text/css">body,.backgroundTable{background-color:#FFFFCC;}#contentTable{border:0px none #000000;margin-top:10px;}.headerTop{background-color:#000000;border-top:0px solid #000000;border-bottom:2px inset #000000;text-align:center;padding:0px;}.adminText{font-size:10px;color:#663300;line-height:200%;font-family:Verdana;text-decoration:none;}.headerBar{background-color:#000000;border-top:1px solid #FFFFFF;border-bottom:2px solid #333333;padding:0px;}.headerBarText{color:#333333;font-size:30px;font-family:Verdana;font-weight:normal;text-align:left;}.title{font-size:20px;font-weight:bold;color:#CC6600;font-family:arial;line-height:110%;}.subTitle{font-size:11px;font-weight:normal;color:#666666;font-style:italic;font-family:arial;}.defaultText{font-size:12px;color:#000000;line-height:150%;font-family:Verdana;width:400px;background-color:#FFFFFF;padding:20px;}.sideColumn{background-color:#EBE7CE;border-left:1px groove #CCCCCC;text-align:left;width:200px;padding:20px;margin:0px;}.sideColumnText{font-size:11px;font-weight:normal;color:#333333;font-family:arial;line-height:150%;}.sideColumnTitle{font-size:15px;font-weight:bold;color:#00707F;font-family:arial;line-height:150%;}.footerRow{background-color:#000000;border-top:2px solid #000000;padding:20px;}.footerText{font-size:10px;color:#996600;line-height:100%;font-family:verdana;}a,a:link,a:visited{color:#800000;text-decoration:underline;font-weight:normal;}.headerTop a{color:#663300;text-decoration:none;font-weight:normal;}.footerRow a{color:#800000;text-decoration:underline;font-weight:normal;}body,.backgroundTable{background-color:#FFC687;}td{font-size:12px;color:#000000;line-height:150%;font-family:trebuchet ms;}a{color:#EE9B3B;}.adminText,a.adminText:link,a.adminText:visited{font-size:10px;color:#FFFFFF;line-height:200%;font-family:verdana;text-decoration:underline;}</style></head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="background-color: #FFC687;">


<table width="100%" cellpadding="10" cellspacing="0" class="backgroundTable" style="background-color: #FFC687;">
<tr>
<td valign="top" align="center" style="font-size: 12px;color: #000000;line-height: 150%;font-family: trebuchet ms;">

<table id="contentTable" cellspacing="0" cellpadding="0" width="600" style="border: 0px none #000000;margin-top: 10px;"><tr><td style="font-size: 12px;color: #000000;line-height: 150%;font-family: trebuchet ms;">

<table width="600" cellpadding="0" cellspacing="0">
<tr>

<td class="headerTop" align="right" style="font-size: 12px;color: #000000;line-height: 150%;font-family: trebuchet ms;background-color: #000000;border-top: 0px solid #000000;border-bottom: 2px inset #000000;text-align: center;padding: 0px;"><div class="adminText" style="font-size: 10px;color: #FFFFFF;line-height: 200%;font-family: verdana;text-decoration: underline;"></div></td>
</tr>

<tr>
<td align="left" valign="middle" class="headerBar" style="font-size: 12px;color: #000000;line-height: 150%;font-family: trebuchet ms;background-color: #000000;border-top: 1px solid #FFFFFF;border-bottom: 2px solid #333333;padding: 0px;"><div class="headerBarText" style="color: #333333;font-size: 30px;font-family: Verdana;font-weight: normal;text-align: left;">
<div style="text-align: center;"><a href="http://www.newresolutionmediation.com" style="color: #800000;text-decoration: underline;font-weight: normal;"><img height="75" width="600" src="http://nrmedlic.paulmerlyn.com/images/wires_crossed_masthead.jpg" alt="Masthead for Wires Crossed from New Resolution" border="0" style="margin: 0; padding: 0;"></a></div>
</div></td>
</tr>

</table>

<table width="600" cellpadding="20" cellspacing="0" class="bodyTable">
<tr>

<td valign="top" class="defaultText" align="left" style="font-size: 12px;color: #000000;line-height: 150%;font-family: Verdana;width: 400px;background-color: #FFFFFF;padding: 20px;">


<span class="title" style="font-size: 20px;font-weight: bold;color: #CC6600;font-family: arial;line-height: 110%;">In the Eye of the Storm</span><br>
<span class="subTitle" style="font-size: 11px;font-weight: normal;color: #666666;font-style: italic;font-family: arial;">Mediator Boot Camp: Part 2</span> <font size="2">
<p>Hello and welcome to another edition of <em>Wires Crossed!</em></p>
<p>Last time I introduced five types of conflict &mdash; relationship conflicts, data conflicts, values conflicts, interest conflicts, and structural (power) conflicts &mdash; and showed you how to recognize them.</p>
<p>The Mediator Boot Camp now continues with some techniques<span style="position: relative; bottom: 4px;">*</span> for resolving these conflicts. We begin with relationship conflicts, data conflicts, and interest conflicts. The next edition of <i>Wires Crossed</i> will conclude the series with advice on resolving values and structural conflicts.</p>

<p>First, a word on conflict resolution &mdash; what it is and what it isn&rsquo;t.</p>
<span style="font-family: Arial; font-size: medium; color: teal; font-weight: bold;">Conflict Resolution, Negotiation &amp; Reconciliation</span>
<p>The goal of conflict resolution is not to persuade the other person or to win an argument. That&rsquo;s the art of negotiation. Negotiation requires a very different skill set. If you&rsquo;re looking for tips on negotiation, this article won&rsquo;t help at all.</p>
<p>Nor is conflict resolution a process of reconciliation. In other words, the goal isn&rsquo;t to &ldquo;make up&rdquo; or &ldquo;make nice&rdquo;, though that is occasionally a by-product of conflict resolution.</p>

<p>Conflict resolution &mdash; the subject of this article &mdash; is about eliminating (or at least managing) the source of a conflict. If you like to think graphically, imagine two pieces of thread or rope. Conflict resolution is a process of disentanglement.</p>
<span style="font-family: Arial; font-size: medium; color: teal; font-weight: bold;">DIY Mediation</span>
<p><img width="128" height="145" border="0" src="http://nrmedlic.paulmerlyn.com/images/DIYmediation.jpg" alt="DIY Mediation" /><br clear="left" />
Of course, it&rsquo;s more difficult to play both mediator and disputant in the same dispute (that is, intervene in your own conflict). That&rsquo;s why people often turn to professional mediators. But as a graduate of the Mediator Boot Camp, you&rsquo;ll see that a little DIY mediation is well within your grasp.</p>
<font style="font-weight: bold; font-size: 11px; color: rgb(0, 112, 127); line-height: 2.5; font-family: Geneva,Verdana,sans-serif;"><hr />
RESOLVING RELATIONSHIP CONFLICTS</font><br />

<p>You&rsquo;ll recall that relationship conflicts are usually caused by miscommunication, strong emotions, preconceived opinions (stereotyping), or repetitive negative behavior. If Jill says, &ldquo;There&rsquo;s something about Mary that bugs me!&rdquo; or &ldquo;She&rsquo;s just so arrogant and opinionated!&rdquo; that&rsquo;s a relationship conflict.</p>
<p>Here are a couple of techniques that will help:</p>

<p><b>1. Take responsibility for your mistakes, promote expression of emotions, and validate feelings.</b></p>
<p><b>Example:</b> You&rsquo;re the office manager, and you didn&rsquo;t respond to Tom&rsquo;s email in which he offered to organize a fundraiser for the homeless. Now he&rsquo;s avoiding you and, you hear through the grapevine, talking negatively about you with coworkers. You step into Tom&rsquo;s office. Let&rsquo;s listen in to your conversation:</p>
<p><i>You: </i>I totally forgot to respond to that fundraiser suggestion, Tom. I&rsquo;m sorry about that. I guess you&rsquo;ve been pretty mad at me?</p>

<p><i>Tom: </i>Well, I did rather assume you didn&rsquo;t like my idea, and it never feels good to be ignored.</p>
<p><i>You: </i>Yeah, I can see why you felt that way. The fundraiser is a good idea, but I&rsquo;ll have to run it by corporate. There may be liability issues. Even if it&rsquo;s a no-go, I appreciate the idea.</p>
<p><i>Tom: </i>No problem. And thanks for getting back to me.</p>
<p>Nice work! In the space of about 30 seconds, you dowsed the flames of a relationship conflict that might have quickly turned into a three-alarm fire.</p>
<p><b>2. Define the problem, then divert negative energy from attacking each other to attacking that problem.</b></p>
<p><b>Example:</b> Things aren&rsquo;t going well between you and your roommate. It started when she borrowed your SUV (without your permission) to haul building supplies from Home Depot. Then she scratched a mirror while unloading the vehicle. After a few minutes of unproductive yelling at one another, you try a different approach:</p>

<p><i>You:</i> &ldquo;Look, this doesn&rsquo;t seem to be getting us anywhere. I don&rsquo;t know about you, but I&rsquo;m feeling this conversation is only making things worse. Let&rsquo;s refocus. How can we get my car fixed and avoid in the future what you call a &lsquo;misunderstanding&rsquo; over your using my car?&rdquo;</p>
<p>I&rsquo;ll bet dollars to doughnuts this conversation is about to take a turn for the better.</p>
<font style="font-weight: bold; font-size: 11px; color: rgb(0, 112, 127); line-height: 2.5; font-family: Geneva,Verdana,sans-serif;"><hr />
RESOLVING DATA CONFLICTS</font><br />

<p>Data conflicts are caused by a lack of information (e.g. not knowing the market value of a small business), misinformation (e.g. belief in a false rumor about a job applicant), or different ways of looking at the same information (e.g. whether crime statistics indicate a safer or more dangerous neighborhood).</p>
<p>Mediators help parties resolve data conflicts by providing a path out of the conflict &mdash; a means of disentanglement. We do this by focusing on what is important to the parties, helping them develop criteria to assess data, and facilitating use of third-party experts where appropriate.</p>
<p><b>Example:</b> Joe and Melinda are getting divorced. Joe is going to move back East, and Melinda has decided to buy out Joe&rsquo;s interest in the family home. Now all they need to do is agree on a purchase price&hellip;</p>
<p><i>Joe: </i>The house is worth at least $880,000. I&rsquo;ve looked at similar homes on Realtor.com.</p>
<p><i>Melinda: </i>You&rsquo;re just looking at asking prices. My friend, whose husband is a real-estate broker, said it&rsquo;s worth a lot less than that.</p>

<p><i>Joe: </i>Houses are selling at or above the ask price. If anything, my estimate is probably too low.</p>
<p>Here are the types of questions I&rsquo;d now be asking to help Joe and Melinda resolve this data conflict.</p>
<ul type="square">
    <li>&ldquo;I&rsquo;m wondering how much money is at stake here. Maybe we should consider your mortgage balance and then think in terms of the equity you have in the home.&rdquo; [Focusing the parties on what&rsquo;s important]</li>
    <li>&ldquo;Noting that Melinda doesn&rsquo;t feel your method of valuation is very reliable, Joe, can either of you think of any alternative methods that might be acceptable to both of you?&rdquo; [Brainstorming and developing criteria to assess data]</li>

    <li>&ldquo;Do you both think the money at stake here warrants the expense (say, $400) of an independent appraisal? If so, how might you select one mutually acceptable appraiser? Or would you rather each get your own appraisal and then split the difference?&rdquo; [Using third-party experts to break deadlocks]</li>
</ul>
<font style="font-weight: bold; font-size: 11px; color: rgb(0, 112, 127); line-height: 2.5; font-family: Geneva,Verdana,sans-serif;"><hr />
RESOLVING INTEREST CONFLICTS</font><br />
<p>Interest conflicts are typically about resources &mdash; for example, five cities in competition to host the Olympics, or two children who want to see different movies. Although at first glance these conflicts can seem intractable, they are often the easiest to resolve.</p>
<p><b>Example:</b> Adam and Zak are 9-year old twins. They can&rsquo;t agree on whether to see Batman or the new Pixar movie for their birthday outing. Zak especially feels that Adam always gets what he wants.</p>

<p>Enter Mom and Dad, who are maestros at conflict resolution:</p>
<p>Dad wants to encourage the boys to work out this conflict themselves. The first thing he does is announce that unless they agree, there&rsquo;ll be no birthday outing.</p>
<p><b>Tactic 1: Shift the focus from positions to interests.</b></p>
<p>Ten minutes later, and the boys are still arguing. Dad&rsquo;s first attempt to break the impasse didn&rsquo;t work on this occasion. Mom suggests a tie breaker instead: They&rsquo;ll look in the newspaper and see which movie gets a better review.</p>
<p><b>Tactic 2: Look for objective standards.</b></p>
<p>Neither Adam nor Zak are jazzed about that approach. And besides, as it happens, both movies get four-star reviews. Hmm? How about abandoning the movie theatre idea altogether and go go-karting instead. Then, when the movies come out on DVD, they can rent them from Netflix.</p>
<p><b>Tactic 3: Search for ways to expand resources and/or develop integrative solutions that address both parties&rsquo; needs.</b></p>

<p>It starts to rain, and Mom thinks go-karting will be too dangerous on a wet track. Dad asks the boys if one wants to choose a new color for painting their bedroom rather than pick which movie. (This works well because Zak is really happy to see either movie, whereas Adam desperately wants to see Batman.)</p>
<p><b>Tactic 4: Look for a compromise or trade-off.</b></p>
<p>When all else fails, a compromise or trade-off will usually resolve an interest conflict. But don&rsquo;t look for these too soon because you might miss out on a better (win-win) resolution.</p>
<p style="font-size: 9px; line-height: 1.2;">* Adapted from Christopher W. Moore. <em>The Mediation Process</em>.<br />
&nbsp;&nbsp;&nbsp;Jossey-Bass. 2003. &copy; Christopher W. Moore</p>

</td>

<td valign="top" class="sideColumn" align="left" style="margin: 0px;font-size: 12px;color: #000000;line-height: 150%;font-family: trebuchet ms;background-color: #EBE7CE;border-left: 1px groove #CCCCCC;text-align: left;width: 200px;padding: 20px;">
<div class="sideColumnText" style="font-size: 11px;font-weight: normal;color: #333333;font-family: arial;line-height: 150%;">
<span class="sideColumnTitle" style="font-size: 15px;font-weight: bold;color: #00707F;font-family: arial;line-height: 150%;">About New Resolution</span><br>
New Resolution LLC provides dispute resolution services through its network of independent mediators. Fair and impartial, we guide clients to settlement using a proven problem-solving process.
<p>Visit <a target="_blank" href="http://www.newresolutionmediation.com" style="color: #800000;text-decoration: underline;font-weight: normal; font-size: 11px;">newresolutionmediation.com</a> or call '.$Tel1.'.'.$Tel2.'.'.$Tel3;
if ($Ext != '' && !is_null($Ext)) $WCcontent3HTML .= ' Ext '.$Ext;
$WCcontent3HTML .= ' for consultations and appointments.</p>

<p>We help people with the following types of disputes:</p>
<img height="11" alt="Arrow" hspace="6" width="10" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Divorce/separation<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Child custody<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Co-parenting<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Domestic partnerships<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Post-marital agreements<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Inheritance/estates<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Small/family businesses<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Family conflict<br>

<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Interpersonal transactions<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Elder care<br><br>';

if ($EntityName == '' || is_null($EntityName)) $WCcontent3HTML .= '<strong>Mediation Office of '.$Name.'</strong><br>';
else $WCcontent3HTML .= '<strong>'.$EntityName.'</strong><br>';
$WCcontent3HTML .= $StreetAddress.'<br>'.$City.', '.$State.' '.$Zip.'</span><br>Tel '.$Tel1.'.'.$Tel2.'.'.$Tel3;
if ($Ext != '' && !is_null($Ext)) $WCcontent3HTML .= ' Ext '.$Ext;
$WCcontent3HTML .= '<br></strong><a href="mailto:'.$Email.'?subject=Mediation%20Inquiry%2FWires%20Crossed" style="color: #800000;text-decoration: underline;font-weight: normal; font-size: 11px;">'.$Email.'</a><br>
<p>In addition, we provide service in these satellite locations:</p>
<p>Your satellite location #1<br>
Your satellite location #2<br>
Your satellite location #3<br>
<img height="96" alt="Animated Logo" width="204" src="http://nrmedlic.paulmerlyn.com/images/anim_logo.gif"><span style="position: relative; bottom: 70px; left: 204px;">&reg;</span></div>
</td>

</tr>

<tr>
<td class="footerRow" valign="top" colspan="2" align="left" style="font-size: 12px;color: #000000;line-height: 150%;font-family: trebuchet ms;background-color: #000000;border-top: 2px solid #000000;padding: 20px;">
<div class="footerText" style="font-size: 10px;color: #996600;line-height: 100%;font-family: verdana;"><p><a href="*|UNSUB|*" style="color: #800000;text-decoration: underline;font-weight: normal; font-size: 10px;">Remove me</a> from this list. <a href="*|FORWARD|*" style="color: #800000;text-decoration: underline;font-weight: normal; font-size: 10px;">Forward</a> this issue of <em>Wires Crossed</em>&trade; to a friend.</p> <p><br> <em>Wires Crossed</em>&trade; is published by: <strong><span style="margin-left: 15px;">';

if ($EntityName == '' || is_null($EntityName)) $WCcontent3HTML .= 'The Mediation Office of '.$Name.'</span><br>';
else $WCcontent3HTML .= $EntityName.'</span><br>';
$WCcontent3HTML .= '<span style="margin-left: 196px;">'.$StreetAddress.', '.$City.', '.$State.' '.$Zip.'</span><br> <span style="margin-left: 196px;">Tel '.$Tel1.'.'.$Tel2.'.'.$Tel3;
if ($Ext != '' && !is_null($Ext)) $WCcontent3HTML .= ' Ext '.$Ext;
$WCcontent3HTML .= '</span><br></strong><span style="margin-left: 196px;"><a style="font-size: 10px;" href="mailto:'.$Email.'?subject=Mediation%20Inquiry%2FWires%20Crossed" style="color: #800000;text-decoration: underline;font-weight: normal;">'.$Email.'</a></span><br><br>'.$Name.' is a member of the New Resolution network of independent mediators.</p> <p>&copy; '.date("Y").' New Resolution LLC. All rights reserved.</p></div>

</td>
</tr>

</table>

</td>
</tr>
</table>

</td>
</tr>
</table>

</body>
</html>';


// Compile HTML for (customized) Wires Crossed template:
$WCtemplateHTML = '<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<style type="text/css">body,.backgroundTable{background-color:#FFFFCC;}#contentTable{border:0px none #000000;margin-top:10px;}.headerTop{background-color:#000000;border-top:0px solid #000000;border-bottom:2px inset #000000;text-align:center;padding:0px;}.adminText{font-size:10px;color:#663300;line-height:200%;font-family:Verdana;text-decoration:none;}.headerBar{background-color:#000000;border-top:1px solid #FFFFFF;border-bottom:2px solid #333333;padding:0px;}.headerBarText{color:#333333;font-size:30px;font-family:Verdana;font-weight:normal;text-align:left;}.title{font-size:20px;font-weight:bold;color:#CC6600;font-family:arial;line-height:110%;}.subTitle{font-size:11px;font-weight:normal;color:#666666;font-style:italic;font-family:arial;}.defaultText{font-size:12px;color:#000000;line-height:150%;font-family:Verdana;width:400px;background-color:#FFFFFF;padding:20px;}.sideColumn{background-color:#EBE7CE;border-left:1px groove #CCCCCC;text-align:left;width:200px;padding:20px;margin:0px;}.sideColumnText{font-size:11px;font-weight:normal;color:#333333;font-family:arial;line-height:150%;}.sideColumnTitle{font-size:15px;font-weight:bold;color:#00707F;font-family:arial;line-height:150%;}.footerRow{background-color:#000000;border-top:2px solid #000000;padding:20px;}.footerText{font-size:10px;color:#996600;line-height:100%;font-family:verdana;}a,a:link,a:visited{color:#800000;text-decoration:underline;font-weight:normal;}.headerTop a{color:#663300;text-decoration:none;font-weight:normal;}.footerRow a{color:#800000;text-decoration:underline;font-weight:normal;}body,.backgroundTable{background-color:#FFC687;}td{font-size:12px;color:#000000;line-height:150%;font-family:trebuchet ms;}a{color:#EE9B3B;}.adminText,a.adminText:link,a.adminText:visited{font-size:10px;color:#FFFFFF;line-height:200%;font-family:verdana;text-decoration:underline;}</style></head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="background-color: #FFC687;">


<table width="100%" cellpadding="10" cellspacing="0" class="backgroundTable" style="background-color: #FFC687;">
<tr>
<td valign="top" align="center" style="font-size: 12px;color: #000000;line-height: 150%;font-family: trebuchet ms;">

<table id="contentTable" cellspacing="0" cellpadding="0" width="600" style="border: 0px none #000000;margin-top: 10px;"><tr><td style="font-size: 12px;color: #000000;line-height: 150%;font-family: trebuchet ms;">

<table width="600" cellpadding="0" cellspacing="0">
<tr>

<td class="headerTop" align="right" style="font-size: 12px;color: #000000;line-height: 150%;font-family: trebuchet ms;background-color: #000000;border-top: 0px solid #000000;border-bottom: 2px inset #000000;text-align: center;padding: 0px;"><div class="adminText" style="font-size: 10px;color: #FFFFFF;line-height: 200%;font-family: verdana;text-decoration: underline;"></div></td>
</tr>

<tr>
<td align="left" valign="middle" class="headerBar" style="font-size: 12px;color: #000000;line-height: 150%;font-family: trebuchet ms;background-color: #000000;border-top: 1px solid #FFFFFF;border-bottom: 2px solid #333333;padding: 0px;"><div class="headerBarText" style="color: #333333;font-size: 30px;font-family: Verdana;font-weight: normal;text-align: left;">
<div style="text-align: center;"><a href="http://www.newresolutionmediation.com" style="color: #800000;text-decoration: underline;font-weight: normal;"><img height="75" width="600" src="http://nrmedlic.paulmerlyn.com/images/wires_crossed_masthead.jpg" alt="Masthead for Wires Crossed from New Resolution" border="0" style="margin: 0; padding: 0;"></a></div>
</div></td>
</tr>

</table>

<table width="600" cellpadding="20" cellspacing="0" class="bodyTable">
<tr>

<td valign="top" class="defaultText" align="left" style="font-size: 12px;color: #000000;line-height: 150%;font-family: Verdana;width: 400px;background-color: #FFFFFF;padding: 20px;">
<span class="title" style="font-size: 20px;font-weight: bold;color: #CC6600;font-family: arial;line-height: 110%;">Primary Heading</span><br>
<span class="subTitle" style="font-size: 11px;font-weight: normal;color: #666666;font-style: italic;font-family: arial;">Subheading Here</span><br>
<p>Placeholder copy. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Morbi commodo, ipsum sed pharetra gravida, orci magna rhoncus neque, id pulvinar odio lorem non turpis. Nullam sit amet enim. Suspendisse id velit vitae ligula volutpat condimentum. Aliquam erat volutpat. Sed quis velit. Nulla facilisi. Nulla libero. Vivamus pharetra posuere sapien. Nam consectetuer. Sed aliquam, nunc eget euismod ullamcorper, lectus nunc ullamcorper orci, fermentum bibendum enim nibh eget ipsum. Donec porttitor ligula eu dolor. Maecenas vitae nulla consequat libero cursus venenatis. Nam magna enim, accumsan eu, blandit sed, blandit a, eros.</p>

<span class="subTitle" style="font-size: 11px;font-weight: normal;color: #666666;font-style: italic;font-family: arial;">Subheading</span><br>
<p>Place additional content (copy and images) here.</p>
</td>

<td valign="top" class="sideColumn" align="left" style="margin: 0px;font-size: 12px;color: #000000;line-height: 150%;font-family: trebuchet ms;background-color: #EBE7CE;border-left: 1px groove #CCCCCC;text-align: left;width: 200px;padding: 20px;">
<div class="sideColumnText" style="font-size: 11px;font-weight: normal;color: #333333;font-family: arial;line-height: 150%;">
<span class="sideColumnTitle" style="font-size: 15px;font-weight: bold;color: #00707F;font-family: arial;line-height: 150%;">About New Resolution</span><br>
New Resolution LLC provides dispute resolution services through its network of independent mediators. Fair and impartial, we guide clients to settlement using a proven problem-solving process.
<p>Visit <a target="_blank" href="http://www.newresolutionmediation.com" style="color: #800000;text-decoration: underline;font-weight: normal; font-size: 11px;">newresolutionmediation.com</a> or call '.$Tel1.'.'.$Tel2.'.'.$Tel3;
if ($Ext != '' && !is_null($Ext)) $WCtemplateHTML .= ' Ext '.$Ext;
$WCtemplateHTML .= ' for consultations and appointments.</p>

<p>We help people with the following types of disputes:</p>
<img height="11" alt="Arrow" hspace="6" width="10" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Divorce/separation<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Child custody<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Co-parenting<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Domestic partnerships<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Post-marital agreements<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Inheritance/estates<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Small/family businesses<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Family conflict<br>

<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Interpersonal transactions<br>
<img height="11" alt="Arrow" hspace="6" width="10" align="middle" src="http://nrmedlic.paulmerlyn.com/images/arrow.gif">Elder care<br><br>';

if ($EntityName == '' || is_null($EntityName)) $WCtemplateHTML .= '<strong>Mediation Office of '.$Name.'</strong><br>';
else $WCtemplateHTML .= '<strong>'.$EntityName.'</strong><br>';
$WCtemplateHTML .= $StreetAddress.'<br>'.$City.', '.$State.' '.$Zip.'</span><br>Tel '.$Tel1.'.'.$Tel2.'.'.$Tel3;
if ($Ext != '' && !is_null($Ext)) $WCtemplateHTML .= ' Ext '.$Ext;
$WCtemplateHTML .= '<br></strong><a href="mailto:'.$Email.'?subject=Mediation%20Inquiry%2FWires%20Crossed" style="color: #800000;text-decoration: underline;font-weight: normal; font-size: 11px;">'.$Email.'</a><br>
<p>In addition, we provide service in these satellite locations:</p>
<p>Your satellite location #1<br>
Your satellite location #2<br>
Your satellite location #3<br>
<img height="96" alt="Animated Logo" width="204" src="http://nrmedlic.paulmerlyn.com/images/anim_logo.gif"><span style="position: relative; bottom: 70px; left: 204px;">&reg;</span></div>
</td>

</tr>

<tr>
<td class="footerRow" valign="top" colspan="2" align="left" style="font-size: 12px;color: #000000;line-height: 150%;font-family: trebuchet ms;background-color: #000000;border-top: 2px solid #000000;padding: 20px;">
<div class="footerText" style="font-size: 10px;color: #996600;line-height: 100%;font-family: verdana;"><p><a href="*|UNSUB|*" style="color: #800000;text-decoration: underline;font-weight: normal; font-size: 10px;">Remove me</a> from this list. <a href="*|FORWARD|*" style="color: #800000;text-decoration: underline;font-weight: normal; font-size: 10px;">Forward</a> this issue of <em>Wires Crossed</em>&trade; to a friend.</p> <p><br> <em>Wires Crossed</em>&trade; is published by: <strong><span style="margin-left: 15px;">';

if ($EntityName == '' || is_null($EntityName)) $WCtemplateHTML .= 'The Mediation Office of '.$Name.'</span><br>';
else $WCtemplateHTML .= $EntityName.'</span><br>';
$WCtemplateHTML .= '<span style="margin-left: 196px;">'.$StreetAddress.', '.$City.', '.$State.' '.$Zip.'</span><br> <span style="margin-left: 196px;">Tel '.$Tel1.'.'.$Tel2.'.'.$Tel3;
if ($Ext != '' && !is_null($Ext)) $WCtemplateHTML .= ' Ext '.$Ext;
$WCtemplateHTML .= '</span><br></strong><span style="margin-left: 196px;"><a style="font-size: 10px;" href="mailto:'.$Email.'?subject=Mediation%20Inquiry%2FWires%20Crossed" style="color: #800000;text-decoration: underline;font-weight: normal;">'.$Email.'</a></span><br><br>'.$Name.' is a member of the New Resolution network of independent mediators.</p> <p>&copy; '.date("Y").' New Resolution LLC. All rights reserved.</p></div>

</td>
</tr>

</table>

</td>
</tr>
</table>

</td>
</tr>
</table>

</body>
</html>';

?>

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
<h2 style="margin-left: 150px;">WIRES CROSSED&trade; E-ZINE INSTRUCTIONS </h2>
<div style="margin-left: 0px; margin-top: 0px; width: 850px; font-family: Arial, Helvetica, sans-serif; font-size: 10px; font-size: 12px;">
<p class="text">Your customized template and content files are ready! You can now preview and download them for export to any email marketing system. We recommend Mail Chimp.</p>

<p class="texthangindent">1.&nbsp;&nbsp;Click &lsquo;Generate Preview&rsquo; to see how the file will appear when read as an HTML email message.</p>

<p class="texthangindent">2.&nbsp;&nbsp;Click &lsquo;Download&rsquo; to save the HTML file on your computer for later export to an email marketing system (e.g. Mail Chimp) or an HTML editor application.</p>

<p class="texthangindent" style="margin-bottom: 30px;">3.&nbsp;&nbsp;Want to make changes? You can access the HTML source code directly in the panels below. (You&rsquo;ll need to know a little HTML to edit that.) Easier, download the code then make changes using a <a target="_blank" href="http://en.wikipedia.org/wiki/WYSIWYG" onclick="wintasticsecond('http://en.wikipedia.org/wiki/WYSIWYG'); return false;">WYSIWYG</a> HTML editor such as Dreamweaver, FrontPage, or the (free) <a target="_blank" href="http://www.openwebware.com/features.shtml" onClick="wintasticsecond('http://www.openwebware.com/features.shtml'); return false;">openWYSIWYG</a>.</p>

<div style="margin-left: 150px;">

<table>
<tr>
<td colspan="2" valign="top" height="30"><label for="Content1HTML">Wires Crossed&trade; Edition #1 (HTML Source Code) </label></td>
</tr>
<tr>
<td colspan="2" valign="top">
<form name="Content1HTMLTextArea">
<textarea name="Content1HTML" id="Content1HTML" rows="10" cols="80" wrap="soft" style="overflow:auto; height: 180px; width: 540px;" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white';" <?php if (isset($_SESSION['Guest'])) { echo 'disabled'; }; ?>><?php echo $WCcontent1HTML; ?></textarea><span title="header=[Help] body=[Click &lsquo;Generate Preview&rsquo; to see how the template will appear on the screen when people read your e-zine. Click &lsquo;Download&rsquo; to save the HTML file for later export to an email marketing system or HTML editor.] singleclickstop=[on] requireclick=[on]" style="position:relative; bottom: 156px;"><img src="/images/QuestionMarkIcon.jpg"></span>
</form>
</td>
</tr>
<tr>
<td align="left">
<input type="button" name="GenerateContent1" class="buttonstyle" style="margin-top: 8px; width: 150px; position: relative; left: 95px;" value="Generate Preview" onClick="poptasticDIY('/scripts/ipwirescrossedcontent1preview.php', 550, 850, 60, 60, 100, 100, 'yes'); return true;">
</td>
<td align="center">
<form action="/scripts/wirescrossedfilecreatordownloader.php" method="post">
<input type="hidden" value="/home/paulme6/public_html/nrmedlic/downloadables/myWCcontent1.html" name="file" id="file">
<input type="submit" name="download_content1" style="margin-top: 8px; width: 150px; position: relative; right: 60px;" value="Download" <?php if (isset($_SESSION['Guest'])) { echo 'disabled'; } else { echo 'class="buttonstyle"'; }; ?>>
</form>
</td>
</tr>
<tr>
<td colspan="2" height="60" align="center" valign="middle">
<HR align="left" size="1px" noshade color="#FF9900" style="width: 90%; color: #FF9900; align: left; height: 0px; margin-left: 10px;">
</td>
</tr>
<tr>
<td colspan="2" valign="top" height="30"><label for="Content2HTML">Wires Crossed&trade; Edition #2 (HTML Source Code) </label></td>
</tr>
<tr>
<td colspan="2" valign="top">
<form name="Content2HTMLTextArea">
<textarea name="Content2HTML" id="Content2HTML" rows="10" cols="80" wrap="soft" style="overflow:auto; height: 180px; width: 540px;" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white';" <?php if (isset($_SESSION['Guest'])) { echo 'disabled'; }; ?>><?php echo $WCcontent2HTML; ?></textarea><span title="header=[Help] body=[Click &lsquo;Generate Preview&rsquo; to see how the template will appear on the screen when people read your e-zine. Click &lsquo;Download&rsquo; to save the HTML file for later export to an email marketing system or HTML editor.] singleclickstop=[on] requireclick=[on]" style="position:relative; bottom: 156px;"><img src="/images/QuestionMarkIcon.jpg"></span>
</form>
</td>
</tr>
<tr>
<td align="left">
<input type="button" name="GenerateContent2" class="buttonstyle" style="margin-top: 8px; width: 150px; position: relative; left: 95px;" value="Generate Preview" onClick="poptasticDIY('/scripts/ipwirescrossedcontent2preview.php', 550, 850, 60, 60, 100, 100, 'yes'); return true;">
</td>
<td align="center">
<form action="/scripts/wirescrossedfilecreatordownloader.php" method="post">
<input type="hidden" value="/home/paulme6/public_html/nrmedlic/downloadables/myWCcontent2.html" name="file" id="file">
<input type="submit" name="download_content2" style="margin-top: 8px; width: 150px; position: relative; right: 60px;" value="Download" <?php if (isset($_SESSION['Guest'])) { echo 'disabled'; } else { echo 'class="buttonstyle"'; }; ?>>
</form>
</td>
</tr>
<tr>
<td colspan="2" height="60" align="center" valign="middle">
<HR align="left" size="1px" noshade color="#FF9900" style="width: 90%; color: #FF9900; align: left; height: 0px; margin-left: 10px;">
</td>
</tr>
<tr>
<td colspan="2" valign="top" height="30"><label for="Content3HTML">Wires Crossed&trade; Edition #3 (HTML Source Code) </label></td>
</tr>
<tr>
<td colspan="2" valign="top">
<form name="Content3HTMLTextArea">
<textarea name="Content3HTML" id="Content3HTML" rows="10" cols="80" wrap="soft" style="overflow:auto; height: 180px; width: 540px;" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white';" <?php if (isset($_SESSION['Guest'])) { echo 'disabled'; }; ?>><?php echo $WCcontent3HTML; ?></textarea><span title="header=[Help] body=[Click &lsquo;Generate Preview&rsquo; to see how the template will appear on the screen when people read your e-zine. Click &lsquo;Download&rsquo; to save the HTML file for later export to an email marketing system or HTML editor.] singleclickstop=[on] requireclick=[on]" style="position:relative; bottom: 156px;"><img src="/images/QuestionMarkIcon.jpg"></span>
</form>
</td>
</tr>
<tr>
<td align="left">
<input type="button" name="GenerateContent3" class="buttonstyle" style="margin-top: 8px; width: 150px; position: relative; left: 95px;" value="Generate Preview" onClick="poptasticDIY('/scripts/ipwirescrossedcontent3preview.php', 550, 850, 60, 60, 100, 100, 'yes'); return true;">
</td>
<td align="center">
<form action="/scripts/wirescrossedfilecreatordownloader.php" method="post">
<input type="hidden" value="/home/paulme6/public_html/nrmedlic/downloadables/myWCcontent3.html" name="file" id="file">
<input type="submit" name="download_content3" style="margin-top: 8px; width: 150px; position: relative; right: 60px;" value="Download" <?php if (isset($_SESSION['Guest'])) { echo 'disabled'; } else { echo 'class="buttonstyle"'; }; ?>>
</form>
</td>
</tr>
<tr>
<td colspan="2" height="60" align="center" valign="middle">
<HR align="left" size="1px" noshade color="#FF9900" style="width: 90%; color: #FF9900; align: left; height: 0px; margin-left: 10px;">
</td>
</tr>
<tr>
<td colspan="2" valign="top" height="30"><label for="TemplateHTML">Wires Crossed&trade; Template (HTML Source Code) </label></td>
</tr>
<tr>
<td colspan="2" valign="top">
<form name="TemplateHTMLTextArea">
<textarea name="TemplateHTML" id="TemplateHTML" rows="10" cols="80" wrap="soft" style="overflow:auto; height: 180px; width: 540px;" onFocus="this.style.background='#FFFF99'" onBlur="this.style.background='white';" <?php if (isset($_SESSION['Guest'])) { echo 'disabled'; }; ?>><?php echo $WCtemplateHTML; ?></textarea><span title="header=[Help] body=[Click &lsquo;Generate Preview&rsquo; to see how the template will appear on the screen when people read your e-zine. Click &lsquo;Download&rsquo; to save the HTML file for later export to an email marketing system or HTML editor.] singleclickstop=[on] requireclick=[on]" style="position:relative; bottom: 156px;"><img src="/images/QuestionMarkIcon.jpg"></span>
</form>
</td>
</tr>
<tr>
<td align="left">
  <input type="button" name="GeneratePreview" class="buttonstyle" style="margin-top: 8px; width: 150px; position: relative; left: 95px;" value="Generate Preview" onClick="poptasticDIY('/scripts/ipwirescrossedtemplatepreview.php', 550, 850, 60, 60, 100, 100, 'yes'); return true;"></td>
<td align="center">
<form action="/scripts/wirescrossedfilecreatordownloader.php" method="post">
<input type="hidden" value="/home/paulme6/public_html/nrmedlic/downloadables/myWCtemplate.html" name="file" id="file">
<input type="submit" name="download_template" style="margin-top: 8px; width: 150px; position: relative; right: 60px;" value="Download" <?php if (isset($_SESSION['Guest'])) { echo 'disabled'; } else { echo 'class="buttonstyle"'; }; ?>>
</form>
</td>
</tr>
</table> 

</div>
<br><br><br>
<div id="copyright" style="margin-left: 150px;">&copy; <?php echo date('Y'); ?> New Resolution LLC. All rights reserved.</div>
</div>

<?php
// Store HTML code for the template and content in session variables so the code can be passed to wirescrossedfilecreatordownloader.php, which stores this code on the server and as a precursor to supporting download of HTML template or HTML content files by the user.
$_SESSION['WCcontent1HTML'] = $WCcontent1HTML;
$_SESSION['WCcontent2HTML'] = $WCcontent2HTML;
$_SESSION['WCcontent3HTML'] = $WCcontent3HTML;
$_SESSION['WCtemplateHTML'] = $WCtemplateHTML;
//echo 'The $_SESSION[WCtemplateHTML] is: '.$_SESSION['WCtemplateHTML']; exit;
?>
</body>
</html>