<?php
/* Based on Twitter Bootstrap with Portfolio Igniter theme courtesy https://wrapbootstrap.com/theme/portfolio-igniter-portfolio-theme-WB002R8U1 */

/* Note: this file was originally named sales.php */
// Start a session and then set a session variable for use by other pages (e.g. updtaeprofile.php) that need to determine whether they were accessed from this page (i.e. from mediationcareer.php). Knowledge of the referring page can then be used. For instance, updateprofile.php will include "test drive" instructions regarding the username and password if the user accessed updateprofile.php from the mediationcareer.php page. (Note: I originally tried using PHP's $_SERVER['HTTP_REFERER'] variable but that has a limitation b/c it's sometimes blocked by antivirus software. I also used Javascript's document.referrer, but that also has a limitation in some versions of IE. Hence my decision to use a session variable instead for tracking the referral source. Yet another approach, which I could use, is to submit a hidden form field in mediationcareer.php, which would then be available to updateprofile.php via the _POST array.)
session_start();

// The first_time_test_driver cookie is used to monitor whether the user is a first-time test driver. When updateprofile.php is called once the user clicks the 'Test Drive Now' button, the first_time_test_driver cookie is examined by that script. If the cookie exists, updateprofile.php should prepopulate the mediator profile form with blank values in the fields and unset the cookie (since the user is no longer a first-time test driver). If, on the other hand, the first_time_test_driver cookie has been unset (which happens inside processprofile.php), then updateprofile.php should prepopulate the mediator profile form with values from the database for username/password = test/drive. (Note: I chose to implement this control via a PHP cookie rather than a $_SESSION[] session variable here b/c I think the cookie persists longer than a mere session. Note also that PHP cookies must be set before any other output to the page happens!)
if (isset($_COOKIE['first_time_test_driver']) != 1 || !isset($_COOKIE['first_time_test_driver'])) setcookie('first_time_test_driver','true', time() + (60*60*24*30)); // If it wasn't already set, then set the cookie to 'true' and to expire in 30 days. Note the former (i.e. not equal to one) 'if clause' is necessary for IE to work, and the latter (i.e. not isset) 'if clause' is necesary for Firefox to work.

$_SESSION['ReferredFromSales'] = 'true';
$_SESSION['ClientDemoSelected'] = 'demo';
$_SESSION['SessValidUserFlag'] = 'false'; // Ensure that test-drivers will see Screen #2 (i.e. the Log In Screen) of updateprofile.php.

// The generic mediationcareer.php and invitation.php sales/prospectus pages as well as potentially other variant prospectus pages such as therapistmediator.php and attorneymediator.php may potentially include a feature that allows the visitor to check whether a particular locale license is already taken. If so, that would entail a call to action script sales_slave.php. Now, sales_slave.php then needs to return control back to the page that called it. Use of $_SERVER['SCRIPT_NAME'] by the slave script isn't dependable. So instead, I'm using $_SERVER['HTTP_HOST'] to determine the calling page's file name, which I'm storing in a session variable and passing it to sales_slave.php. The slave script can then use the session variable as the place to which it should pass back control. (The code for grabbing the file name is courtesy: http://www.webcheatsheet.com/php/get_current_page_url.php). Note that $_SERVER['SCRIPT_NAME'] doesn't convey any query string.
function curPageName()
	{
	return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
	}
$_SESSION['BackToPage'] = curPageName();

// Connect to mysql and select database in order to retrieve MarginNow from parameters_table for use in the sales pitch.
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
	or die('Could not connect: ' . mysql_error());

mysql_select_db('paulme6_newresolution') or die('Could not select database');

// Formulate query to retrieve MarginNow from parameters_table. 
$query = "select MarginNow from parameters_table";

$result = mysql_query($query) or die('The SELECT query to obtain MarginNow from parameters_table i.e. '.$query.' failed: ' . mysql_error());
$row = mysql_fetch_assoc($result);

$MarginNow = $row['MarginNow'];

/*
Calculate $value to $sigFigs significant figures. (Used in screen formatting of $MarginNow.)
Source: http://tamlyn.org/2008/12/formatting-bytes-with-significant-figures-in-php/comment-page-1/#comment-668
*/
function sigFig($value, $sigFigs) 
{
	if ($value == 0) return 0; // My addition of this line seems to help prevent a "Warning: division by zero"
	//Convert to scientific notation e.g. 12345 -> 1.2345x10^4 where $significand is 1.2345 and $exponent is 4
	$exponent = floor(log10(abs($value))+1);
	$significand = round(($value/pow(10, $exponent)) * pow(10, $sigFigs)) / pow(10, $sigFigs);
	return $significand * pow(10, $exponent);
}

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>The Platform&trade; from New Resolution LLC</title>
<meta NAME="description" CONTENT="Advance your mediation practice with the Platform&trade; from New Resolution LLC.">
<meta NAME="keywords" CONTENT="become a mediator,mediation career,mediator jobs,profit,professional mediator,New Resolution Platform,career in mediation">
<link href='TSScript/TSContainer.css' rel='stylesheet' type='text/css'>
<link  href='TSScript/TSGlossary/TSGlossary.css' rel='stylesheet' type='text/css'>
<style>
select#LocaleOfInterest { behavior: url(/scripts/IEoptionhack.htc); } /* Hack because IE 6 and IE 7 don't respect the 'disabled' attribute of an option element in a drop-down (i.e. select) menu. (This shortcoming seems to have been rectified in IE8 though.) Thanks to: http://apptaro.seesaa.net/article/21140090.html for this non-invasive workaround! */
option#LocaleOfInterest { behavior: url(/scripts/IEoptionhack.htc); } /* Hack because IE 6 and IE 7 don't respect the 'disabled' attribute of an option element in a drop-down (i.e. select) menu. (This shortcoming seems to have been rectified in IE8 though.) Thanks to: http://apptaro.seesaa.net/article/21140090.html for this non-invasive workaround! */
.gloss, .gloss a, .gloss a:hover { font-size: 13pt; color: black; }
ol.decimalgloss { list-style-type: decimal; }
ol.decimalgloss li { margin-bottom: 12px; margin-left: -6px; padding-left: 5px; color: black; font-size: 12pt; }
</style>
<script language="JavaScript" type='text/javascript' src="/scripts/windowpops.js"></script>
<!-- Start: The following javascripts pertain to Trio Solutions's glossary and image preview -->
<script language='JavaScript' type='text/javascript' src='TSScript/yahoo.js'></script>
<script language='JavaScript' type='text/javascript' src='TSScript/event.js'></script>
<script language='JavaScript' type='text/javascript' src='TSScript/dom.js'></script>
<script language='JavaScript' type='text/javascript' src='TSScript/dragdrop.js'></script>
<script language='JavaScript' type='text/javascript' src='TSScript/animation.js'></script>
<script language='JavaScript' type='text/javascript' src='TSScript/container.js'></script>
<script language='JavaScript' type='text/javascript' src='TSScript/TSPreviewImage/TSPreviewImage.js'></script>
<link rel='stylesheet' type='text/css' href='TSScript/TSGlossary/TSGlossary.css' />
<script language='JavaScript' type='text/javascript' src='TSScript/TSGlossary/TSGlossary.js'></script>
<!-- End: Trio Solutions's glossary and image preview -->
<link href="css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css">
<link href="css/render.css" rel="stylesheet" media="screen and (min-device-width: 800px)" type="text/css">
<!-- iPhone and iPod Touch -->
<link rel="stylesheet" media="only screen and (max-device-width: 480px)" href="css/mobilecss.css" type="text/css" />
<!-- iPhone4 and iPod Touch 4G -->
<link rel="stylesheet" media="only screen and (-webkit-min-device-pixel-ratio: 2)" type="text/css" href="css/mobilecss.css" />
<!-- iPad -->
<link rel="stylesheet" media="only screen and (max-device-width: 1024px)" href="css/mobilecss.css" type="text/css" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/jquery.parallax-1.1.js"></script>
<script type="text/javascript" src="js/bootstrap-scrollspy.js"></script>
<script type="text/javascript" src="js/bootstrap-collapse.js"></script>
<script type="text/javascript" src="js/jquery.localscroll-1.2.7-min.js"></script>
<script type="text/javascript" src="js/jquery.scrollTo-1.4.2-min.js"></script>

<script type="text/javascript">
<!--
/*
if (screen.width <= 699) {
document.location = "http://augustinas.eu/dev/4/mobile.html";
};
if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i))) {
   location.replace("http://augustinas.eu/dev/4/mobile.html");
};
*/
//-->
</script>

<link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>

<style> #page {opacity:0;}</style>

</head>


<body data-spy="scroll" data-offset="10" id="page">
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
require('ssi/obtainparameters.php'); // Include the obtainparameters.php file, which retrieves values $FeeTermArray (and $DefaultAPR) from the parameters_table table.
?>
<header>
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container-fluid"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a>
                <div class="nav-collapse">
                    <ul id="navi" class="nav">
                        <li><a href="#home">Home</a></li>
                        <li><a href="#second">About</a></li>
                        <li><a href="#third">Platform</a></li>
                        <li><a href="#fourth">SEO</a></li>
                        <li><a href="#fifth">FAQs</a></li>
                        <li><a href="#sixth">Sign Up</a></li>
                        <li><a href="#seventh">Contact</a></li>
                    </ul>
                </div>
          </div>
        </div>
    </div>
</header>

<div id="home">
    <div class="story">
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span3"> 
                </div>
                <div class="span9">
                    <h1>The Platform&trade; from New Resolution</h1>
					<h2>Advance your mediation practice today</h2>
                    <p>Are you an established mediator, passionate about the extraordinary power of the mediation process? Would you like to:</p>
					<ol>
					<li>Advance your professional practice through broader online visibility?.</li>
					<li>Gain an enhanced credibility edge?</li>
					<li>Exploit proprietary field-tested marketing collateral, custom designed for professional mediators?</li>
					<li>Participate in a collaborative network of professional piers?</li>
					</ol>
					
					<p>The technology behind the  Platform&trade; was built by 
  <script type='text/javascript'> var a = new Array('y','r','ul ','a','P','M','e','l','n,');document.write(a[4]+a[3]+a[2]+a[5]+a[6]+a[1]+a[7]+a[0]+a[8]);</script> 
a Silicon Valley engineer and  business consultant who built a highly successful mediation practice &mdash; despite competition from existing mediation providers and a cascade of new entrants to the market &mdash; thanks to the platform he created.</p>

                </div>
                <!--/span--> 
            </div>
            <!--/row--> 
        </div>
    </div>
    <!--.story--> 
</div>
<!--#intro--> 


<!-- floating objects in background, please documentation-->
<!--
<div class="bg"></div>
<div class="bg2"></div>
-->
<!-- /floating objects in background -->

<!--#second-->
<div id="second">    
        <div class="container-fluid">
        <div class="story">
			<div class="row-fluid">
                    <h1>Making mediation viable &amp; accessible</h1>
                    <p>New Resolution LLC makes the practice of professional mediation more <b>viable</b> and more <b>accessible</b> &mdash; more viable for people who want to make money while practicing divorce, family, and business mediation, and more accessible to clients who might otherwise suffer the costs and distress of litigation rather than experience the benefits and rewards of a mediated settlement.</p>
					<p>We invite trained and talented mediators to join the New Resolution network of independent mediators.</p>
            </div>
        </div><!--.story--> 
    </div>
</div>
<!--#second-->

<!--#third-->
<div id="third">
	<div class="container-fluid">
    <div class="story">
        <div class="row-fluid">
                <h1>Inside the Mediation Platform</h1>
                <p>Here&rsquo;s what you get when you build your practice on the Platform:&trade;</p>

				<ol>
				<li><h3>A full web presence on the New Resolution LLC web site</h3></li>
				<p>Visit <a href="/index.shtml" onClick="wintasticsecond('/index.shtml'); return false;">www.newresolutionmediation.com</a> and you&rsquo;ll find a professionally branded and high visibility web site, not a nameless and faceless directory comprising dozens of competing mediators in each geographic locale. On this site, you&rsquo;ll be able to manage all the specifics of your business &mdash; your contact information, your mediation fees, whether you accept credit cards, your bio, your picture, and so on &mdash; via your password-protected mediator profile. Want to increase your hourly rate? Easy. Log in, change the value in your profile, click &lsquo;Submit&rsquo;, and you&rsquo;re done. Any changes you make appear instantly on the New Resolution site. You can even take your name off the site for a period of time (say, while you&rsquo;re on vacation) by simply clicking the &lsquo;Suspend&rsquo; check-box. Power and simplicity.</p>
				<FORM>
				<INPUT TYPE="button" value="&nbsp;Test Drive Now&nbsp;" class="btn" onClick="wintasticsecond('/scripts/updateprofile.php?testdrive=1'); return false;">
				</FORM>

				<li><h3>Delivery to your email inbox of requests for consultations and mediation sessions</h3></li>
				<p>When a prospective client visits the New Resolution site and fills out a form to request a consultation or mediation session, you&rsquo;ll automatically receive in your email inbox details of any requests submitted to you. The client/prospect making the request will also receive an automatic acknowledgement that says you&rsquo;ll be in touch with them shortly.</p>
				<FORM>
				<INPUT TYPE="button" value="Test this Feature" class="btn" onClick="var emailtdformwindow; window.open('/emailtdform.php','','height=350,width=750,top=200,left=290,scrollbars=no,menubar=no,toolbar=no,location=no,status=yes'); return false;"></FORM>

				<li><h3>A professional email address: <script type='text/javascript'> var a = new Array('o','.','@new','name','your','resolution','mediation','c','m');document.write(a[4]+a[3]+a[2]+a[5]+a[6]+a[1]+a[7]+a[0]+a[8]);</script></h3></li>
				<p>You&rsquo;ll be able to receive messages sent to 
				  <script type='text/javascript'> var a = new Array('o','.','@new','name','your','resolution','mediation','c','m');document.write(a[4]+a[3]+a[2]+a[5]+a[6]+a[1]+a[7]+a[0]+a[8]);</script> via your preferred email application on your computer. You&rsquo;ll also be able to send clients and prospects messages from this account. Alternatively, if you prefer, we can simply forward  incoming messages to your existing email address e.g. yourname@yahoo.com.</p>

				<li><h3>Free technical support</h3></li>
				<p>Setting up an email account is really easy, and we provide simple instructions for the most popular email applications. Even so, if you get stuck, we&rsquo;re on hand with free technical support.</p>

				<li><h3>A professional online and offline brand</h3></li>
				<p>As a member of the New Resolution network of independent mediators, you can use our logo freely on your business cards, letter-head, and email signature. You may also want to use our professionally branded templates for business cards, letterhead, and email signatures. (Explore our Intellectual Property Library with this <a href="/iplibrary.php" onClick="wintasticsecond('/iplibrary.php'); return false;">guest pass</a>.)</p>

				<li><h3>Email marketing through the <em>Wires Crossed</em>&trade; e-zine format</h3></li>
				<p>The <em>Wires Crossed</em>&trade; e-zine format (<a href="/r_wirescrossed_preview.html" onClick="wintasticsecond('/r_wirescrossed_preview.html'); return false;">preview</a>) is available exclusively to Platform&trade; mediators. You may publish <em>Wires Crossed</em>&trade; using our library of ready-made syndicated content, or publish your own content. Either way, you&rsquo;ll have a professional, powerful, and fully customizable e-marketing format to help build your business.</p>

				<li><h3>Direct mail marketing</h3></li>
				<p>You&rsquo;ll have rights to the promotional copy in our over-size postcard direct mailer (<a href="#Link525553C0" id="Link525553C0" onMouseOver="javascript:createPreviewImage('TSPreviewImagePanelID525553', 'Direct Mail Postcard - Front', 'images/DirectMailPostcardFront.jpg', 'Link525553C0',600,391, true)">front</a> and <a href="#Link86922C0" id="Link86922C0" onMouseOver="javascript:createPreviewImage('TSPreviewImagePanelID86922', 'Direct Mail Postcard - Back', 'images/DirectMailPostcardBack.jpg', 'Link86922C0',600,391, true)">back</a>). You may print postcards with our copyrighted material and a picture of your choice, or you may purchase royalty-free rights to the picture shown. (Since New Resolution LLC does not own the copyright to that image, we cannot sell or transfer usage rights to you.)</p>

				<li><h3>The option (when available) to secure exclusive rights to a locale</h3></li>
				<p>Locales are geographic regions or territories. (<a href="#Link618865Context" name="Link618865Context" id="Link618865Context" onMouseOver="javascript:createGlossary('TSGlossaryPanelID618865', 'Locales: Definition', '<p class=\'gloss\'>New Resolution defines <em>locales</em> according to a standard established by the U.S. Office of Management and Budget using latest data from the U.S. Census Bureau. Each locale is (in the language of the Census Bureau) a <a target=\'_blank\' href=\'http://en.wikipedia.org/wiki/Core_Based_Statistical_Area\'><i>Core-Based Statistical Area</i></a> (CBSA) or, in the case of New York and Los Angeles, a division of a <a target=\'_blank\' href=\'http://en.wikipedia.org/wiki/Metropolitan_statistical_area#cite_ref-2\'><i>Metropolitan Area</i></a>. These locales cover 93% of the U.S. population. (Only the most rural and remote parts of the United States are excluded.) You can see a list of CBSAs by population <a target=\'_blank\' href=\'http://en.wikipedia.org/wiki/Table_of_United_States_Core_Based_Statistical_Areas\'>here</a>. And you can see the four <a target=\'_blank\' href=\'http://en.wikipedia.org/wiki/New_York-Newark-Bridgeport,_NY-NJ-CT-PA_CSA\'>New York</a> Metropolitan Area divisions and the two <a target=\'_blank\' href=\'http://en.wikipedia.org/wiki/Los_Angeles-Long_Beach-Riverside,_CA_CSA\'>Los Angeles</a> Metropolitan Area divisions by population.</p><p class=\'gloss\'>For most locales, New Resolution will issue a license to just one mediator. However, we may grant more than one license in locales with populations over 2.5 million people. In all cases, the maximum number of mediators per locale is limited. These are the rules we use to limit the number of licensees in each locale: </p><ol class=\'decimalgloss\'><li>We will grant only one license in any locale whose population is less than 2.5 million people.</li><li>In locales whose population exceeds 2.5 million people, we will not grant more than (i) one license per 1.25 million population or (ii) five licenses, whichever is the lesser.</li></ol>', 'Link618865Context');">More on locales</a>.) Every Platform&trade; mediator receives a Platform license for his/her chosen locale. In  a locale of population under 2.5 million people, we will issue a single  license only. But in locales with very large populations (over 2.5 million people), we may issue licenses to a limited number of mediators. The limit ensures you won&rsquo;t ever find yourself competing against a multitude of other New Resolution mediators in your locale. <!-- Even so, you may still wish to secure exclusive rights to be the only New Resolution mediator in your locale. When available, we&rsquo;ll work with you to agree mutually acceptable terms for an exclusive license.--></p>
				</ol>

    </div>

      
    </div>
    </div><!--.story--> 
    </div>
</div>
<!--#third-->

<!--#fourth-->
<div id="fourth">
    <div class="story">
        <div class="container-fluid">
			<div class="row-fluid">
				<div class="span12">
                <h1>Search engine visibility</h1>
				<p>Open your browser and go to Google or Bing/Yahoo. Now type in &lsquo;divorce mediation san francisco&rsquo;, &lsquo;divorce mediators arlington&rsquo;, &lsquo;custody mediation dallas&rsquo;, or &lsquo;family mediation san ramon&rsquo;. Although there&rsquo;s no predicting exactly what results a search engine will return for any given search, you&rsquo;ll probably find that New Resolution mediators appear at or near the top of the results list for all those searches. That&rsquo;s because our site is professionally optimized for our existing mediators who practice in these locations.</p>

				<p class="sales">When you become a member of the New Resolution network of independent mediators, we&rsquo;ll make sure the site is optimized for your locale too. Note: This <em>doesn&rsquo;t</em> mean we can guarantee that a search for, say, &lsquo;divorce mediators in [your city]&rsquo; will immediately appear at the top of the Google or Bing/Yahoo search results. (Search engines may take several weeks to update their indexes in light of changes to a web site, and their algorithms for ranking sites are subject to change.) But it <em>does</em> mean  your decision to build your practice on the Platform&trade; places you in the hands of a search-engine visibility expert with a proven record of top-ranking search results.</p>

				<FORM>
				<INPUT TYPE="button" value="&nbsp;Visit Demo Site&nbsp;" class="btn" onClick="alert('The Demo Site is a mirror image of the Public Site. Explore the Demo Site to see how your mediator\'s profile will blend into the rest of the site\'s content. In particular, don\'t miss the home page, mediators page, fees page, contact pages, and state-specific content under the Divorce tab.'); wintasticsecond('/demo/index.shtml'); return false;">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT TYPE="button" value="&nbsp;Visit Public Site&nbsp;" class="btn" onClick="alert('You are about to explore the New Resolution public web site, which features the services of New Resolution mediators. If you join the New Resolution network of independent mediators, your services will also appear on this site.'); wintasticsecond('/index.shtml'); return false;">
				</FORM>
				</div>
			</div>
        </div>
    </div><!--.story--> 
</div>
<!--#fourth-->

<!--#fifth-->
<div id="fifth">    
        <div class="container-fluid">
        <div class="story">
			<div class="row-fluid">
                    <h1>FAQs</h1>
					<h3>Q. I already have my own web site, and I&rsquo;m happy with it. Why would I want another?</h3>
					<p>A. As consumer behavior looks increasingly to online sources for information and referral, an additional online presence reinforces and legitimizes any existing presence. Recognizing this, successful marketers spread a wide net in order to attract the attention of as many clients as possible. The opportunity to extend your reach is a significant value.</p>

					<h3>Q. Is this just another mega-directory of mediators?</h3>
					<p>A. No. Visit <a target="secondwindow" title="New Resolution home page" href="/index.shtml">newresolutionmediation.com</a>. Now imagine you&rsquo;re looking for a mediator. Through a client&rsquo;s eyes, New Resolution is an integrated and focused mediation service provider site. In other words, it&rsquo;s your client&rsquo;s <em>destination</em>, not a mere sign post (i.e. directory) pointing clients in a hundred different directions. Also &mdash; and further distinguishing the Platform&trade; from mega-directories like mediate.com, divorcesupport.com, and martindale.com &mdash; members of the New Resolution network of independent mediators share in the benefits of a professional brand, an extended web presence, field-tested marketing resources, and enhanced online visibility and credibility.</p>

					<h3>Q. Is support available to help ensure my practice succeeds?</h3>
					<p>A. Platform members may be interested in the critically acclaimed <em>Mediating for Money: A Field Guide for Professional Mediators</em> and other mediation career <a target="secondwindow" title="mediation careers" href="http://www.mediationcareer.org">consulting</a> resources from  <a target="secondwindow" title="MediationCareer.og" href="http://www.mediationcareer.org">MediationCareer.org</a>. Available equally to Platform&trade; mediators and others alike, these resources are separate from the Platform&trade; and entirely optional.</p>

					<h3>Q. Is this a franchise?</h3>
					<p>A. No. Participation in the Platform&trade; is much simpler and much less expensive. The Platform&trade; is an opportunity to leverage a powerful suite of marketing infrastructure within a network of independent mediators, all of whom are motivated to promote the New Resolution brand while each retain 100% of their own profits. To this end, your relationship with New Resolution LLC is  a standard licensing agreement, which we&rsquo;ll ask you to acknowledge online when you join the Platform&trade;.</p>

					<h3>Q. Is this a &ldquo;get-rich-quick&rdquo; scheme or a &ldquo;business-in-a-box&rdquo;?</h3>
					<p>A. It&rsquo;s neither. Mediation is a new industry that&rsquo;s just beginning to see widespread adoption. In support of these emerging opportunities, the Platform&trade; provides serious marketing infrastructure as well as tremendous cost and time-to-market advantages, but it is not a &ldquo;business-in-a-box&rdquo; (there&rsquo;s no such thing!). The power and resources of the Platform will help you focus your talents on client service, but ongoing business development will remain your responsibility.</p>

					<h3>Q. How much does this cost?</h3>
					<p>A. You&rsquo;ll find our fee schedule below. Fees are flexible, affordable, guaranteed, and require no long-term commitments. We&rsquo;re confident you&rsquo;ll recognize the Platform&trade; as a remarkable value and opportunity to broaden your online visibility while gaining a critical online and offline marketing advantage over other competing mediators.</p>

					<h3>Q. What if I&rsquo;m not completely satisfied?</h3>
					<p>A. We offer a full money-back guarantee if you&rsquo;re not completely satisfied at any time within your first 30 days on the Platform&trade;.</p>

					<h3>Q. Why divorce, family, and business mediation?</h3>
					<p class="sales">The choice to practice divorce, family, and business mediation is, for many, a vocation or calling. Mediators derive enormous satisfaction from helping people reconcile complex and meaningful issues &mdash; for example, coparenting (child custody), alimony/spousal support, and decisions over who should remain in the family home. Divorce and family practices may also emphasize other issues &mdash; inheritance disputes, the unique challenges of same-sex relationships, or sibling disputes over the care of elderly parents. Meanwhile, businesses present an intricate mix of interpersonal issues, often comprising a high-stakes conflict is undermining productivity and even threatening the business&rsquo;s survival.</p>

					<p class="sales">Besides, those who have been in the trenches of the mediation industry know there is no money in barking dogs, broken fences, and landlord-tenant disputes. These clients are served by volunteer mediators in community mediation organizations. People pay for divorce and family/business mediation because they know it can save them thousands and sometimes tens of thousands of dollars in litigation costs. They&rsquo;ll also pay for family, marital, and business mediation because these disputes are usually unsuited to alternative forums like the courtroom or community mediation. They&rsquo;re too personal and too complex. There may also be too much money at stake.</p>

            </div>
        </div><!--.story--> 
    </div>
</div>
<!--#fifth-->

<!--#sixth-->
<div id="sixth">
	<div class="container-fluid">
    <div class="story">
        <div class="row-fluid">
                <h1>Sign Up</h1>
				<p>New Resolution LLC accepts payment via all major credit and debit cards. You may also pay by paper check. Upon payment and online acknowledgement of the terms of your agreement, you&rsquo;ll have immediate access to the Platform&trade; and live presence on the New Resolution site. To begin, select your state and locale.<a name="localecheck" style="text-decoration: none;">&nbsp;</a></p>

<!-- The code for this user availability check of/selection of a locale draws heavily from code in admin1.php. -->

<form method="post" name="StateDropForm" action="/scripts/sales_slave.php">
<table width="100%">
<tr>
<td>
<select name="StateOfInterest" size="1" style="font-size: 14px; width: 280px; color: #425A8D; background-color: #fafafa; border: 1px solid #425A8D;" onChange="this.form.submit();">
<?php
// Note: this code for generating a drop-down menu of states was first written for updateprofile.php.
$statesArray = array(array('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;&nbsp;Select Your State&nbsp;&gt;',null), array('Alabama','AL'),	array('Alaska','AK'), array('Arizona','AZ'), array('Arkansas','AR'),	array('California','CA'), array('Colorado','CO'), array('Connecticut','CT'), array('Delaware','DE'), array('District of Columbia (D.C.)','DC'), array('Florida','FL'), array('Georgia','GA'), array('Hawaii','HI'), array('Idaho','ID'), array('Illinois','IL'), array('Indiana','IN'), array('Iowa','IA'), array('Kansas','KS'), array('Kentucky','KY'), array('Louisiana','LA'), array('Maine','ME'), array('Maryland','MD'), array('Massachusetts','MA'), array('Michigan','MI'), array('Minnesota','MN'), array('Mississippi','MS'), array('Missouri','MO'), array('Montana','MT'), array('Nebraska','NE'), array('Nevada','NV'), array('New Hampshire','NH'), array('New Jersey','NJ'), array('New Mexico','NM'), array('New York','NY'), array('North Carolina','NC'), array('North Dakota','ND'), array('Ohio','OH'), array('Oklahoma','OK'), array('Oregon','OR'), array('Pennsylvania','PA'), array('Rhode Island','RI'), array('South Carolina','SC'), array('South Dakota','SD'), array('Tennessee','TN'), array('Texas','TX'), array('Utah','UT'), array('Vermont','VT'), array('Virginia','VA'), array('Washington','WA'), array('Washington, D.C.','DC'), array('West Virginia','WV'), array('Wisconsin','WI'), array('Wyoming','WY'));
for ($i=0; $i<53; $i++)
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
<noscript><input type="submit" value="Select" class="buttonstyle"></noscript>
</td>
</tr>
</table>
</form>

<form method="post" name="LocaleDropForm" action="/scripts/sales_slave.php">
<table width="100%">
<tr>
<td>
<select name="LocaleOfInterest" id="LocaleOfInterest" size="1" style="width: 280px; <?php if ($_SESSION['StateSelected'] != null) echo 'color: #425A8D; background-color: #fafafa; border: 1px solid #425A8D;'; else echo 'color: #CCCCCC;'; ?>" onChange="this.form.submit();">
<option value=null selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;&nbsp;Select Your Locale&nbsp;&gt;</option>
<?php
// Connect to mysql to obtain the locales that belong to the selected state of interest. We'll place these locales in an array, which we'll use to build a drop-down menu by which the user can select a particular locale.
$db = mysql_connect('localhost', 'paulme6_merlyn', 'fePhaCj64mkik')
or die('Could not connect: ' . mysql_error());
mysql_select_db('paulme6_newresolution') or die('Could not select database: ' . mysql_error());

// If $_SESSION['StateSelected'] is neither null nor '' (i.e. if the State drop-down menu isn't still in its neutral position), formulate query to obtain the locales that belong to the selected state of interest. We'll place these locales in an array, which we'll use to build a drop-down menu by which the user can select a particular locale.
if ($_SESSION['StateSelected'] != null && $_SESSION['StateSelected'] != '')
	{
	$query = "select Locale, LocaleShort, Exclusive, LocaleStates, MaxLicenses, NofLicenses, Full from locales_table WHERE LocaleStates LIKE '%".$_SESSION['StateSelected']."%'";
	$result = mysql_query($query) or die('Query (select all Locale values for selected state from locales_table) failed: ' . mysql_error());
	if (!$result) {	echo 'No $result was achievable.'; };

	// Place the result of the query in an array $line via the mysql_fetch_assoc command. The associative values $line['Locale'], $line['LocaleShort'], etc. on moving down through the lines of this array will hold the Locale, LocaleShort, etc. values. Then push each of these value into array $localesArray[] and $localesShortArray respectively, where the contents can then be sorted alphabetically before use in the option tag of the locales drop-down menu.
	$localesArray = array(); // Declare and initialize array
	$localesShortArray = array(); // Declare and initialize array
	while ($line = mysql_fetch_assoc($result))
		{
		array_push($localesArray, $line['Locale']);
		if ($line['Exclusive'] == 1 || $line['Full'] == 1) 
			{
			array_push($localesShortArray, $line['LocaleShort'].' [Full]'); // Append the word '[Full]' to the LocaleShort item for exclusive or full locales
			}
		else
			{
			array_push($localesShortArray, $line['LocaleShort']);
			}
		};
	sort($localesArray);
	sort($localesShortArray);

	$arrayLength = count($localesArray);
	for ($i=0; $i<$arrayLength; $i++)
		{
		$optiontag = '<option value="'.$localesArray[$i].'"';
		/* Note on option disablement: IE 6 and 7 don't implement the disabled attribute of the option element. I address that bug courtesy of http://apptaro.seesaa.net/article/21140090.html for a great non-invasive workaround! I use it with the necessary inclusion of "select, option { behavior: url(sample.htc); }" in my external stylesheet files nrcss.css and salescss.css. It's a necessary hack because IE doesn't respect the 'disabled' attribute of an option element in a drop-down (i.e. select) menu. Best of all, it's non-invasive, so you can just create standard HTML with the 'disabled' attribute. The standard code will work fine in Firefox, and this hack will make the disablement work in IE. Note that I disable option elements in drop-down menus such as the locale selector inside mediationcareer.php (and admin1.php) when a locale is full or exclusive and therefore not available to another mediator/licensee. */
		if (strpos($localesShortArray[$i], '[Full]') != false) $optiontag .= ' disabled'; // Disable option item if '[Full]' was appended to the array item in the short version of this locale.
		if (html_entity_decode($localesArray[$i], ENT_QUOTES, 'UTF-8') == $_SESSION['LocaleSelected']) $optiontag .= ' selected'; // Preset LocaleOfInterest drop-down menu to previously selected value. This is necessary for reload of mediationcareer.php on return from the form processor script sales_slave.php. Note use of html_entity_decode to handle use of special characters such as the ntilde in 'Canon City_CO' and the single quote in Coeur d'Alene_ID. See PHP manual http://us.php.net/manual/en/function.html-entity-decode.php, including Matt Robinson's contributed note for why I use ENT_QUOTES and UTF-8 parameters.
		$optiontag .= '>'.$localesShortArray[$i]."</option>\n";
		echo $optiontag;
		}
	}
// Closing connection
mysql_close($db);
?>
</select>
<!-- This submit button is no longer necessary when JS is enabled b/c submission takes place via the onchange method of the 'LocaleOfInterest' select element. --> 
<noscript><input type="submit" value="Select" class="buttonstyle"></noscript>
</td>
</tr>
</table>
</form>

<!-- This JS (from admin5.php) disables the Locale drop-down menu when the State drop-down menu is at its neutral position. -->
<script type="text/javascript">if (document.StateDropForm.StateOfInterest.selectedIndex == 0) { document.LocaleDropForm.LocaleOfInterest.disabled = true; } else { document.LocaleDropForm.LocaleOfInterest.disabled = false; };</script>

<p class="smalltext"><a href="/waitlist.php" onClick="poptasticDIY('/waitlist.php', 440, 470, 110, 110, 400, 400, 'auto'); return false;">[What if my locale is already taken?]</a></p>

<br/>
<br/>

<!-- Construct license fee table using Bootstrap -->
<div class="row-fluid">
<div class="span5">
<table class='table table-condensed'>

<tr>
<th width='100px'>License Term</td>
<th align="center">License Fee</td>
<th align="center">Avg. Cost per Month</td>
<th align="center">&nbsp;</td>
</tr>

<?php
/*
Dynamically generate the data rows of this license fee table, drawing license term and license fee value-pairs from the DB's parameters_table. (This code draws heavily from code used in admin7.php. Also note inclusion of obtainparameters.php above via a 'require' statement, which provides the $FeeTermArray fee-term value-pair array.)
*/
$FeeIndexPointer = 0; // Keeps track of the index for successive fee elements in the $FeeTermArray fee-term value-pair array. (Note: it increments in steps of 2 to point to index 0, 2, 4, 6, etc.
$TermIndexPointer = 1; // Keeps track of the index for successive term elements in the $FeeTermArray fee-term value-pair array. (Note: it increments in steps of 2 to point to index 1, 3, 5, 7, etc.
$BaseMonthlyFee = $FeeTermArray[0]/$FeeTermArray[1]; //This BaseMonthlyFee value will be used to calculate the savings that users achieve by choosing license terms longer than the base (i.e. shortest) term.
$BaseMonthlyFee = number_format($BaseMonthlyFee,2,'.',''); // Format the $BaseMonthlyFee to two decimal places (and no thousands separator).
for ($i=1; $i <= $NofFeeTermPairs; $i++) // When $i is 1, we generate the first row of the table; when $i is 2, we generate the second row, and so on until we've created one row for each fee-term value-pair in the $FeeTermArray (which is itself retrieved from the DB's parameters_table).
	{
?>
	<tr>
	<td style="font-weight: bold;">
	<?php
	// $FeeTermArray stores all license terms in months. For example, the 1-year term is stored as 12, and the one-quarter term is stored as 3. For human familiarity, I'm going to convert certain license terms from their month value into the more familiar unit of, say, the quarter or the year.
	 switch($FeeTermArray[$TermIndexPointer])
		{
	 	case 1:
			echo '1 month'; // Differs from default case because 'month' needs to be singular for a term of just one month.
			break;
	 	case 3:
			echo '1 quarter';
			break;
	 	case 12:
			echo '1 year';
			break;
	 	case 24:
			echo '2 years';
			break;
	 	case 36:
			echo '3 years';
			break;
	 	case 48:
			echo '4 years';
			break;
	 	case 60:
			echo '5 years';
			break;
	 	case 120:
			echo '10 years';
			break;
		default:
			echo $FeeTermArray[$TermIndexPointer].' months';
			break;
		};
	?>
	</td>
	<td align="center">$<?=$FeeTermArray[$FeeIndexPointer]; ?></td>
	<td align="center">$<?php $avgmthlycost = $FeeTermArray[$FeeIndexPointer]/$FeeTermArray[$TermIndexPointer]; $avgmthlycost = number_format($avgmthlycost,2,'.',''); echo $avgmthlycost; ?></td>
	<td align="center">
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post"> <!-- Swap www.sandbox.paypal for www.paypal for Sandbox vs live implementation.-->
	<!-- Identify your business so that you can collect the payments. -->
	<input type="hidden" name="business" value="RJHBWTTA9EWKC"> <!-- Use aberta_1247785190_biz@sbcglobal.net for Sandbox, or replace with my actual Merchant ID for live PayPal implementation for added security and anti-spam. -->
	<!-- Specify a Buy Now button. -->
	<input type="hidden" name="cmd" value="_xclick">
	<!-- Specify details about the item that buyers will purchase. -->
	<input type="hidden" name="item_name" value="New Resolution Mediation Platform <?=$FeeTermArray[$TermIndexPointer]; ?>-month license for <?=$_SESSION['LocaleSelected']; ?>"> <!-- Thus, the locale gets posted among the value of the PayPal button's item_name field. -->
	<input type="hidden" name="item_number" value="<?=$FeeTermArray[$TermIndexPointer]; ?>mLPL"> <!-- Thus, the license term gets posted among the value of the PayPal button's item_number field. -->
	<input type="hidden" name="amount" value="<?=$FeeTermArray[$FeeIndexPointer]; ?>"> <!-- Thus, the license fee gets posted as the value of the PayPal button's 'amount' field. -->
	<input type="hidden" name="no_note" value="1"> <!-- Deny buyers the opportunity to write a text note with their order -->
	<input type="hidden" name="currency_code" value="USD">
	<input type="hidden" name="custom" value="newlicensee"> <!-- When creating payment buttons on the mediationcareer.php page for new mediators, this hidden custom field takes the value of "newlicensee". When creating payment buttons inside each mediator's individual renewal web page, the associated payment button will set this 'custom' field to the value of that mediator's ID (as drawn from the mediators_table in the database). The value of the 'custom' field is used by ipn.php to distinguish between payments for newlicensee purchases and renewing mediators. The ipn.php script needs to price-check the amount actually received by the merchant (the gross amount posted as mc_gross) against the price list for the license purchased. Those prices will likely differ between newlicensee purchasers and renewing mediators. -->
	<!-- Display the payment button. -->
	<input type="hidden" name="charset" value="utf-8"> <!-- By setting this (see https://www.paypal.com/en_US/ebook/PP_WebsitePaymentsStandard_IntegrationGuide/formbasics.html#1052289), PayPal will now properly display special characters in locales such as "Canon City_CO" [the n is an ntilde character] and "Coeur d'Alene_ID" [which has a single quote]. -->
	<input type="image" name="submit" border="0"
	src="https://www.paypal.com/en_US/i/btn/btn_buynow_SM.gif" alt="PayPal - The safer, easier way to pay online" <?php $thelocale = $_SESSION['LocaleSelected']; $thelocale = explode('_', $thelocale); if ($_SESSION['LocaleSelected'] == null || $_SESSION['LocaleSelected'] == '') echo 'onClick="alert(\'Please use the drop-down menus above to select a locale before proceeding with your purchase.\'); return false;"'; else echo 'onClick="return confirm(\'Do you want to proceed with your purchase of a license for '.$thelocale[0].'?\n\nClick \\\'OK\\\' to proceed. Click \\\'Cancel\\\' to cancel or change your locale.\n\');"'; ?>>
	<img alt="paypal" border="0" width="1" height="1"	src="https://www.paypal.com/en_US/i/scr/pixel.gif" >
	<?php
	$_SESSION['custom'] = 'newlicensee'; // This session variable is used by userpass.php (the landing page after the mediator has signed the Licensing Agreement) in order to distinguish new mediators from existing mediators.

	//	For all but the first row of data (i.e. when $TermIndexPointer > 1), include a note below the 'Buy Now' PayPal button to show the $ savings for this license term. The savings are calculated by subtracting the license fee for this license term from ($BaseMonthlyFee * license term in months).
	if ($TermIndexPointer > 1)
		{
		$saving = ($BaseMonthlyFee * $FeeTermArray[$TermIndexPointer]) - $FeeTermArray[$FeeIndexPointer];
		$saving = number_format($saving); // This parameter choice for number_format causes formatting of commas as thousand separators only, and no decimal place formatting.
		echo '<span style="color: red;">Save $'.$saving.'!<span>';
		};
	?>
	</form>
	</td>
	</tr>
<?php
	$FeeIndexPointer = $FeeIndexPointer + 2;  // Increment to obtain indices 2, 4, 6, etc. from $FeeTermArray for the 2nd, 3rd, 4th, etc. rows of the table.
	$TermIndexPointer = $TermIndexPointer + 2;  // Increment to obtain indices 3, 5, 7, etc. from $FeeTermArray for the 2nd, 3rd, 4th, etc. rows of the table.
	};
?>	

</table>
</div>
</div>

    </div>

      
    </div>
    </div><!--.story--> 
    </div>
</div>
<!--#sixth-->

<!--#seventh-->
<div id="seventh">
	<div class="container-fluid">
	    <div class="story">
        	<div class="row-fluid">
            <div class="span7">
                <h1>Contact</h1>
				<p>For more information or to request a live online demo of the Platform, please contact:</p>
                <p>  <strong><script type='text/javascript'> var a = new Array('y','r','ul ','a','P','R. M','e','l','n');document.write(a[4]+a[3]+a[2]+a[5]+a[6]+a[1]+a[7]+a[0]+a[8]);</script></strong><br>
					 President and Founder<br>
					 New Resolution LLC<br>
					 844 California Street, San Francisco, CA 94108<br>
				</p>
				<p>
					415.378.7003 <strong>tel</strong><br>
                </p>
            </div> <!-- /span --> 
            </div>
        </div>
    </div>
</div>
<!--#seventh-->

<!-- footer --> 
<footer class="footer"> 
	<div class="row">
    <div class="span6 smalltext">&copy; <?php echo date("Y"); ?> New Resolution LLC. All rights reserved.</div>
    </div>
</footer>
<!-- /footer --> 

<!-- Le JS --> 
<script>
$(document).ready(function(){
	$('#navi').localScroll(800);
	RepositionNav();
	$(window).resize(function(){
		RepositionNav();
	});	
	// .parallax(xPosition, adjuster, inertia, outerHeight) options:
	// xPosition - Horizontal position of the element.
	// adjuster - y position to start from.
	// inertia - speed to move relative to vertical scroll. Example: 0.1 is one tenth the speed of scrolling, 2 is twice the speed of scrolling.
	// outerHeight (true/false) - Whether or not jQuery should use it's outerHeight option to determine when a section is in the viewport.
	$('#intro').parallax("50%", 0, 0.1, true);
	$('#second').parallax("50%", 0, 0.1, true);
	$('.bg').parallax("5%", 3000, 0.8, true);
	$('.bg2').parallax("30%", 3500, 0.8, true);
	$('#third').parallax("50%", 0, 0.1, true);
	$('#fourth').parallax("50%", 0, 0.1, true);
	$('#fifth').parallax("50%", 0, 0.1, true);
})
	$('#navbar').scrollspy();
</script>

<script>
$(".collapse").collapse()
</script>


<!-- page fade effect - this simple trick first waits for page to load(images, content) and after it's loaded fades it. You can remove it by deleting this section. -->
<!-- read documentation -->
<script>
$(document).ready(function() {
	$('#page').fadeTo(1500, 1);
});
</script>
<!-- /page fade effect -->
</body>
</html>