<?php
/*
For maximum ease of access/editing, I've placed the copy of the email marketing messages used by admin12.php and prospectinviter.php inside this SSI file. This include file simply defines email messages for each FormMailCode as selected by the Administrator when inserting a prospect into the prospects_table via admin11.php.
	For the purpose of admin11.php, iff the Administrator checks the "Send Now" box in admin11.php, the relevant message would be sent to the prospect according to the code selected by the Administrator in admin11.php. For example, the Administrator designates a prospect as an Experienced Attorney Mediator and checks the box, so an email message will be sent. Otherwise, the contents of this include file are irrelevant, and admin11.php merely adds details of a new prospect to the prospects_table in the DB.
	For the purpose of prospectinviter.php (which is predominantly called via a cron job), emailsolicitationcontent.php provides the email subject line and message content.
*/

// Start a session
session_start();
 
// Create the hashcode, which will be passed as a string in a URL when a prospect clicks the "unsubscribe me" link in his/her email solicitation message.
$hashcode = 'polo'; // This secret key gets used to hash the prospect's email address. If you change the value of this key, make sure you change it also in scripts/unsubscriber.php.
$hashcode = $hashcode.$_SESSION['ProspectEmail'];
$hashcode = sha1($hashcode);
$hashcode = substr($hashcode, 0, 12); // Truncate, allowing only first 12 characters.

// Pass $AreaCode from admin12.php via session variable for insertion into the email text below.

//FormMailCode = "EarlyMedNo (1. Early Mediator/No Site)

// Formulate body text for HTML version:
$subject1 = 'How to Establish Your Mediation Practice';
$message1HTML = "<html><body><table cellspacing='16'><tr><td style='font-family: Arial, Helvetica, sans-serif'>".$_SESSION['dearline']."</td></tr>";
$message1HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>I&rsquo;m writing to tell you that New Resolution LLC is now inviting trained mediators to join the New Resolution Mediation Launch Platform.&trade; The Platform will interest you if you&rsquo;d like to develop the potential of your mediation practice.</td></tr>";
$message1HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>In summary, for <u>less than the cost of a monthly utility bill</u>, the New Resolution Mediation Launch Platform&trade; grants you:</td></tr>";
$message1HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'><ol>";
$message1HTML .= "<li><strong>Immediate credibility</strong> as a professional mediator on a site you can call your own.</li>";
$message1HTML .= "<li><strong>Prominence and high visibility</strong> on the New Resolution web site (newresolutionmediation.com).</li>";
$message1HTML .= "<li>Membership in a <strong>professional network</strong> of independent mediators.</li>";
$message1HTML .= "<li>Access to a <strong>comprehensive library</strong> of proprietary, field-tested, marketing resources.</li>";
$message1HTML .= "<li>The <strong>freedom to focus</strong> your talents on mediation client service and business development.</li>";
$message1HTML .= "</ol></td></tr>";
$message1HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Please take a few minutes to learn more about the New Resolution Mediation Launch Platform&trade; on our prospectus page [click <a href='http://www.newresolutionmediation.com/launch'>here</a>] or point your browser to: www.newresolutionmediation.com/launch";
$message1HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>You may submit a request for a consultation and online demo via that page. Better yet, explore the Platform for yourself by clicking the &lsquo;test drive&rsquo; buttons on the page. And please let me know if I can answer any questions.</td></tr>";
$message1HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>We&rsquo;re currently focusing our outreach efforts in ".$_SESSION['AreaLabel']." and would be glad to welcome you to our network of independent mediators. Please sign up directly via the prospectus page.</td></tr>";
$message1HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Sincerely</td></tr>";
$message1HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Paul Merlyn<br />";
$message1HTML .= "Principal Mediator<br />";
$message1HTML .= "<strong>New Resolution LLC</strong><br />";
$message1HTML .= "844 California Street | San Francisco | CA 94108<br />";
$message1HTML .= "<a href='http://www.newresolutionmediation.com'>www.newresolutionmediation.com</a><br />";
$message1HTML .= "<a href='mailto:paul@newresolutionmediation.com'>paul@newresolutionmediation.com</a><br />";
$message1HTML .= "T. 415.378.7003<br />";
$message1HTML .= "F. 415.366.3005</td></tr>";
$message1HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>P.S. In most locations, we only accept <span style='color: red'>one mediator per location</span> (see the prospectus for details). Please act quickly to avoid missing out to another mediator in your area.</td></tr>";
$message1HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif; font-size: 10px;'>If you don&rsquo;t want to receive further communications regarding the New Resolution network of independent mediators, click <a href='http://www.newresolutionmediation.com/scripts/unsubscriber.php?code=".$hashcode."'>here</a>. Thank you, and please pardon the intrusion!</td></tr></body></html>";

// Formulate body text for plain text version
$message1Text = $_SESSION['dearline']."\n\nI'm writing to tell you that New Resolution LLC is now inviting trained mediators to join the New Resolution Mediation Launch Platform. The Platform will interest you if you'd like to develop the potential of your mediation practice.\n\n";
$message1Text .= "In summary, for less than the cost of a monthly utility bill, the Platform grants you:\n\n";
$message1Text .= "1. Immediate credibility as a professional mediator on a site you can call your own.\n\n";
$message1Text .= "2. Prominence on the high-visibility New Resolution web site (newresolutionmediation.com).\n\n";
$message1Text .= "3. Membership in a professionally branded network of independent mediators.\n\n";
$message1Text .= "4. Access to a comprehensive library of proprietary, field-tested, marketing resources.\n\n";
$message1Text .= "5. The freedom to focus your talents on mediation client service and business development.\n\n";
$message1Text .= "Please take a few minutes to learn more about the New Resolution Mediation Launch Platform by visiting our prospectus at: www.newresolutionmediation.com/launch\n\n";
$message1Text .= "You may submit a request for a consultation and online demo via that page. Better yet, explore the Platform for yourself by clicking the 'test drive' buttons on the page. And please let me know if I can answer any questions.\n\n";
$message1Text .= "We're currently focusing our outreach efforts in ".$_SESSION['AreaLabel']." and would be glad to welcome you to our network of independent mediators. Please sign up directly via the prospectus page.\n\n";
$message1Text .= "Sincerely\n
Paul Merlyn\n
Principal Mediator\n
New Resolution LLC\n
844 California Street | San Francisco | CA 94108\n
paul@newresolutionmediation.com
www.newresolutionmediation.com
T. 415.378.7003\n;
F. 415.366.3005\n\n";
$message1Text .= "P.S. In most locations, we only accept one mediator per location (see the prospectus for details). Please act quickly to avoid missing out to another mediator in your area.";

//FormMailCode = "EarlyMedMin (2. Early Mediator/Minimal Site)

// Formulate body text for HTML version:
$subject2 = 'How to Advance Your Mediation Practice';
$message2HTML = "<html><body><table cellspacing='16'><tr><td style='font-family: Arial, Helvetica, sans-serif'>".$_SESSION['dearline']."</td></tr>";
$message2HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>I&rsquo;m writing to tell you that New Resolution LLC is now inviting suitable candidates to join the New Resolution Mediation Platform.&trade; The Platform will interest you if you&rsquo;d like to develop the potential of your mediation practice.</td></tr>";
$message2HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>In summary, for <u>less than the cost of a monthly utility bill</u>, the New Resolution Mediation Platform&trade; grants you:</td></tr>";
$message2HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'><ol>";
$message2HTML .= "<li>A <strong>prominent presence</strong> on the newresolutionmediation.com web site &mdash; a site you can call your own.</li>";
$message2HTML .= "<li><strong>Broader online visibility</strong> as a mediator in search-engine results.</li>";
$message2HTML .= "<li>Membership in a <strong>professional network</strong> of independent mediators.</li>";
$message2HTML .= "<li>Access to a <strong>comprehensive library</strong> of proprietary, field-tested, marketing resources.</li>";
$message2HTML .= "<li>The <strong>freedom to focus</strong> your talents on mediation client service and business development.</li>";
$message2HTML .= "</ol></td></tr>";
$message2HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Please take a few minutes to learn more about the New Resolution Mediation Platform&trade; on our prospectus page [click <a href='http://www.newresolutionmediation.com/mediationplatform'>here</a>] or point your browser to: www.newresolutionmediation.com/mediationplatform";
$message2HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>You may submit a request for a consultation and online demo via that page. Better yet, explore the Platform for yourself by clicking the &lsquo;test drive&rsquo; buttons on the page. And please let me know if I can answer any questions.</td></tr>";
$message2HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>We&rsquo;re currently focusing our outreach efforts in ".$_SESSION['AreaLabel']." and would be glad to welcome you to our network of independent mediators. Please sign up directly via the prospectus page.</td></tr>";
$message2HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Sincerely</td></tr>";
$message2HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Paul Merlyn<br />";
$message2HTML .= "Principal Mediator<br />";
$message2HTML .= "<strong>New Resolution LLC</strong><br />";
$message2HTML .= "844 California Street | San Francisco | CA 94108<br />";
$message2HTML .= "<a href='http://www.newresolutionmediation.com'>www.newresolutionmediation.com</a><br />";
$message2HTML .= "<a href='mailto:paul@newresolutionmediation.com'>paul@newresolutionmediation.com</a><br />";
$message2HTML .= "T. 415.378.7003<br />";
$message2HTML .= "F. 415.366.3005</td></tr>";
$message2HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>P.S. In most locations, we only accept <span style='color: red'>one mediator per location</span> (see the prospectus for details). Please act quickly to avoid missing out to another mediator in your area.</td></tr>";
$message2HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif; font-size: 10px;'>If you don&rsquo;t want to receive further communications regarding the New Resolution network of independent mediators, click <a href='http://www.newresolutionmediation.com/scripts/unsubscriber.php?code=".$hashcode."'>here</a>. Thank you, and please pardon the intrusion!</td></tr></body></html>";

// Formulate body text for plain text version
$message2Text = $_SESSION['dearline']."\n\nI'm writing to tell you that New Resolution LLC is now inviting suitable candidates to join the New Resolution Mediation Platform. The Platform will interest you if you'd like to develop the potential of your mediation practice.\n\n";
$message2Text .= "In summary, for less than the cost of a monthly utility bill, the Platform grants you:\n\n";
$message2Text .= "1. Prominent presence on the New Resolution web site (newresolutionmediation.com) - a site you can call your own.\n\n";
$message2Text .= "2. Broader online visibility as a mediator in search-engine results.\n\n";
$message2Text .= "3. Membership in a professional network of independent mediators.\n\n";
$message2Text .= "4. Access to a comprehensive library of proprietary, field-tested, marketing resources.\n\n";
$message2Text .= "5. The freedom to focus your talents on mediation client service and business development.\n\n";
$message2Text .= "Please take a few minutes to learn more about the New Resolution Mediation Platform by visiting our prospectus page at: www.newresolutionmediation.com/mediationplatform\n\n";
$message2Text .= "You may submit a request for a consultation and online demo via that page. Better yet, explore the Platform for yourself by clicking the 'test drive' buttons on the page. And please let me know if I can answer any questions.\n\n";
$message2Text .= "We're currently focusing our outreach efforts in ".$_SESSION['AreaLabel']." and would be glad to welcome you to our network of independent mediators. Please sign up directly via the prospectus page.\n\n";
$message2Text .= "Sincerely\n
Paul Merlyn\n
Principal Mediator\n
New Resolution LLC\n
844 California Street | San Francisco | CA 94108\n
paul@newresolutionmediation.com
www.newresolutionmediation.com
T. 415.378.7003\n;
F. 415.366.3005\n\n";
$message2Text .= "P.S. In most locations, we only accept one mediator per location (see the prospectus for details). Please act quickly to avoid missing out to another mediator in your area.";

//FormMailCode = "EstdMedMin (3. Established Mediator/Minimal Site/Generic)

// Formulate body text for HTML version:
$subject3 = 'How to Advance Your Mediation Practice';
$message3HTML = "<html><body><table cellspacing='16'><tr><td style='font-family: Arial, Helvetica, sans-serif'>".$_SESSION['dearline']."</td></tr>";
$message3HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>I&rsquo;m writing to tell you that New Resolution LLC is now inviting suitable candidates to join the New Resolution Platform.&trade; The Platform will interest you if you&rsquo;d like to advance your existing mediation practice.</td></tr>";
$message3HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>In summary, for <u>less than the cost of a monthly utility bill</u>, the New Resolution Platform&trade; grants you:</td></tr>";
$message3HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'><ol>";
$message3HTML .= "<li>Broader <strong>localized online visibility</strong> within search-engine results</li>";
$message3HTML .= "<li>Presence as a mediator on the New Resolution web site (newresolutionmediation.com) &mdash; <strong>a site you can call your own</strong> or that complements your existing site.</li>";
$message3HTML .= "<li>Membership in a professionally branded <strong>network of independent mediators</strong>.</li>";
$message3HTML .= "<li>Access to a <strong>comprehensive library</strong> of proprietary, field-tested, marketing resources.</li>";
$message3HTML .= "<li>The <strong>freedom to focus</strong> your talents on mediation client service and business development.</li>";
$message3HTML .= "</ol></td></tr>";
$message3HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Please take a few minutes to learn more about the New Resolution Platform&trade; on our prospectus page [click <a href='http://www.newresolutionmediation.com/mediationplatform'>here</a>] or point your browser to: www.newresolutionmediation.com/mediationplatform";
$message3HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>You may submit a request for a consultation and online demo via that page. Better yet, explore the Platform for yourself by clicking the &lsquo;test drive&rsquo; buttons on the page. And please let me know if I can answer any questions.</td></tr>";
$message3HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>We&rsquo;re currently focusing our outreach efforts in ".$_SESSION['AreaLabel']." and would be glad to welcome you to our network of independent mediators. Please sign up directly via the prospectus page.</td></tr>";
$message3HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Sincerely</td></tr>";
$message3HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Paul Merlyn<br />";
$message3HTML .= "Principal Mediator<br />";
$message3HTML .= "<strong>New Resolution LLC</strong><br />";
$message3HTML .= "844 California Street | San Francisco | CA 94108<br />";
$message3HTML .= "<a href='http://www.newresolutionmediation.com'>www.newresolutionmediation.com</a><br />";
$message3HTML .= "<a href='mailto:paul@newresolutionmediation.com'>paul@newresolutionmediation.com</a><br />";
$message3HTML .= "T. 415.378.7003<br />";
$message3HTML .= "F. 415.366.3005</td></tr>";
$message3HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>P.S. In most locations, we only accept <span style='color: red'>one mediator per location</span> (see the prospectus for details). Please act quickly to avoid missing out to another mediator in your area.</td></tr>";
$message3HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif; font-size: 10px;'>If you don&rsquo;t want to receive further communications regarding the New Resolution network of independent mediators, click <a href='http://www.newresolutionmediation.com/scripts/unsubscriber.php?code=".$hashcode."'>here</a>. Thank you, and please pardon the intrusion!</td></tr></body></html>";

// Formulate body text for plain text version
$message3Text = $_SESSION['dearline']."\n\nI'm writing to tell you that New Resolution LLC is now inviting suitable candidates to join the New Resolution Platform. The Platform will interest you if you'd like to develop the potential of your mediation practice.\n\n";
$message3Text .= "In summary, for less than the cost of a monthly utility bill, the Platform grants you:\n\n";
$message3Text .= "1. Broader localized online visibility within search-engine results.\n\n";
$message3Text .= "2. Prominent presence as a mediator on the New Resolution web site (newresolutionmediation.com) - a site you can call your own or that complements your existing site.\n\n";
$message3Text .= "3. Membership in a professionally branded network of independent mediators.\n\n";
$message3Text .= "4. Access to a comprehensive library of proprietary, field-tested, marketing resources.\n\n";
$message3Text .= "5. The freedom to focus your talents on mediation client service and business development.\n\n";
$message3Text .= "Please take a few minutes to learn more about the New Resolution Platform by visiting our prospectus page: www.newresolutionmediation.com/mediationplatform\n\n";
$message3Text .= "You may submit a request for a consultation and online demo via that page. Better yet, explore the Platform for yourself by clicking the 'test drive' buttons on the page. And please let me know if I can answer any questions.\n\n";
$message3Text .= "We're currently focusing our outreach efforts in ".$_SESSION['AreaLabel']." and would be glad to welcome you to our network of independent mediators. Please sign up directly via the prospectus page.\n\n";
$message3Text .= "Sincerely\n
Paul Merlyn\n
Principal Mediator\n
New Resolution LLC\n
844 California Street | San Francisco | CA 94108\n
paul@newresolutionmediation.com
www.newresolutionmediation.com
T. 415.378.7003\n;
F. 415.366.3005\n\n";
$message3Text .= "P.S. In most locations, we only accept one mediator per location (see the prospectus for details). Please act quickly to avoid missing out to another mediator in your area.";

//FormMailCode = "EstdMed (4. Established Mediator/Visibility Pitch)

// Formulate body text for HTML version:
$subject4 = 'Fortify Your Mediation Practice';
$message4HTML = "<html><body><table cellspacing='16'><tr><td style='font-family: Arial, Helvetica, sans-serif'>".$_SESSION['dearline']."</td></tr>";
$message4HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>New Resolution LLC is now inviting advanced practitioners to join the New Resolution Mediation Platform. The Platform will interest you as an established mediator if you&rsquo;d like to fortify your practice of divorce, custody, elder, and family/small business mediation.</td></tr>";
$message4HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>In summary, for <u>less than the cost of a monthly utility bill</u>, the Platform grants members:</td></tr>";
$message4HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'><ol>";
$message4HTML .= "<li><strong>Broader online visibility</strong> as a mediator, complementary to your existing site.</li>";
$message4HTML .= "<li>A <strong>complementary professional identity</strong> to your existing web presence.</li>";
$message4HTML .= "<li>Membership in a <strong>professional network</strong> of independent mediators.</li>";
$message4HTML .= "<li>Access to a <strong>comprehensive library</strong> of proprietary, field-tested, mediation marketing resources.</li>";
$message4HTML .= "<li>The <strong>freedom to focus</strong> your talents on mediation client service and business development.</li>";
$message4HTML .= "</ol></td></tr>";
$message4HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Please take a few minutes to learn more about the Platform on our prospectus page [click <a href='http://www.newresolutionmediation.com/platform'>here</a>] or point your browser to: www.newresolutionmediation.com/platform";
$message4HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>You may submit a request for a consultation and online demo via that page. Better yet, explore the Platform for yourself by clicking the &lsquo;test drive&rsquo; buttons on the page. And please let me know if I can answer any questions.</td></tr>";
$message4HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>We&rsquo;re currently focusing our outreach efforts in ".$_SESSION['AreaLabel']." and would be glad to welcome you to our network of independent mediators. Please sign up directly via the prospectus page.</td></tr>";
$message4HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Sincerely</td></tr>";
$message4HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Paul Merlyn<br />";
$message4HTML .= "Principal Mediator<br />";
$message4HTML .= "<strong>New Resolution LLC</strong><br />";
$message4HTML .= "844 California Street | San Francisco | CA 94108<br />";
$message4HTML .= "<a href='http://www.newresolutionmediation.com'>www.newresolutionmediation.com</a><br />";
$message4HTML .= "<a href='mailto:paul@newresolutionmediation.com'>paul@newresolutionmediation.com</a><br />";
$message4HTML .= "T. 415.378.7003<br />";
$message4HTML .= "F. 415.366.3005</td></tr>";
$message4HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>P.S. In most locations, we only accept <span style='color: red'>one mediator per location</span> (see the prospectus for details). Please act quickly to avoid missing out.</td></tr>";
$message4HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif; font-size: 10px;'>If you don&rsquo;t want to receive further communications regarding the New Resolution network of independent mediators, click <a href='http://www.newresolutionmediation.com/scripts/unsubscriber.php?code=".$hashcode."'>here</a>. Thank you, and please pardon the intrusion!</td></tr></body></html>";

// Formulate body text for plain text version
$message4Text = $_SESSION['dearline']."\n\nNew Resolution LLC is now inviting advanced practitioners to join the New Resolution Mediation Platform. The Platform will interest you as an established mediator if you'd like to fortify your practice of divorce, custody, elder, and family/small business mediation.\n\n";
$message4Text .= "In summary, for less than the cost of a monthly utility bill, the Platform grants you:\n\n";
$message4Text .= "1. Broader online visibility as a mediator, complementary to your existing site.\n\n";
$message4Text .= "2. A complementary professional identity to your existing web presence.\n\n";
$message4Text .= "3. Membership in a professional network of independent mediators.\n\n";
$message4Text .= "4. Access to a comprehensive library of proprietary, field-tested, medation marketing resources.\n\n";
$message4Text .= "5. The freedom to focus your talents on mediation client service and business development.\n\n";
$message4Text .= "Please take a few minutes to learn more about the Platform by visiting our prospectus page: www.newresolutionmediation.com/platform\n\n";
$message4Text .= "You may submit a request for a consultation and online demo via that page. Better yet, explore the Platform for yourself by clicking the 'test drive' buttons on the page. And please let me know if I can answer any questions.\n\n";
$message4Text .= "We're currently focusing our outreach efforts in ".$_SESSION['AreaLabel']." and would be glad to welcome you to our network of independent mediators. Please sign up directly via the prospectus page.\n\n";
$message4Text .= "Sincerely\n
Paul Merlyn\n
Principal Mediator\n
New Resolution LLC\n
844 California Street | San Francisco | CA 94108\n
paul@newresolutionmediation.com
www.newresolutionmediation.com
T. 415.378.7003\n;
F. 415.366.3005\n\n";
$message4Text .= "P.S. In most locations, we only accept one mediator per location (see the prospectus for details). Please act quickly to avoid missing out.";

//FormMailCode = "ExpAttyMed (5. Experienced Attorney Mediator)

// Formulate body text for HTML version:
$subject5 = 'Attorneys: Expand Your Mediation Practice';
$message5HTML = "<html><body><table cellspacing='16'><tr><td style='font-family: Arial, Helvetica, sans-serif'>".$_SESSION['dearline']."</td></tr>";
$message5HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>New Resolution LLC is now inviting suitable candidates to join the New Resolution Mediation Platform&trade;. The Platform will interest you as a family law attorney and a mediator if you&rsquo;d like to further extend the reach of your law practice into divorce, custody, or family/small business mediation.</td></tr>";
$message5HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>In summary, for <u>less than the cost of a monthly utility bill</u>, the Platform grants you:</td></tr>";
$message5HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'><ol>";
$message5HTML .= "<li><strong>Broader online visibility</strong> as a mediator, complementary to your existing site.</li>";
$message5HTML .= "<li>A <strong>complementary professional identity</strong>, bringing clarity and focus to your role as a mediator.</li>";
$message5HTML .= "<li>Membership in a professionally branded <strong>network of independent mediators</strong>.</li>";
$message5HTML .= "<li>Access to a <strong>comprehensive library</strong> of proprietary, field-tested, mediation marketing resources.</li>";
$message5HTML .= "<li>The <strong>freedom to focus</strong> your talents on client service and business development.</li>";
$message5HTML .= "</ol></td></tr>";
$message5HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Please take a few minutes to learn more about the Platform on our prospectus page [click <a href='http://www.newresolutionmediation.com/attorney'>here</a>] or point your browser to: www.newresolutionmediation.com/attorney";
$message5HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>You may submit a request for a consultation and online demo via that page. Better yet, explore the Platform for yourself by clicking the &lsquo;test drive&rsquo; buttons on the page. And please let me know if I can answer any questions.</td></tr>";
$message5HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>We&rsquo;re currently focusing our outreach efforts in ".$_SESSION['AreaLabel']." and would be glad to welcome you or your law firm to our network of independent mediators. Please sign up directly via the prospectus page.</td></tr>";
$message5HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Sincerely</td></tr>";
$message5HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Paul Merlyn<br />";
$message5HTML .= "Principal Mediator<br />";
$message5HTML .= "<strong>New Resolution LLC</strong><br />";
$message5HTML .= "844 California Street | San Francisco | CA 94108<br />";
$message5HTML .= "<a href='http://www.newresolutionmediation.com'>www.newresolutionmediation.com</a><br />";
$message5HTML .= "<a href='mailto:paul@newresolutionmediation.com'>paul@newresolutionmediation.com</a><br />";
$message5HTML .= "T. 415.378.7003<br />";
$message5HTML .= "F. 415.366.3005</td></tr>";
$message5HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif; font-size: 10px;'>If you don&rsquo;t want to receive further communications regarding the New Resolution network of independent mediators, click <a href='http://www.newresolutionmediation.com/scripts/unsubscriber.php?code=".$hashcode."'>here</a>. Thank you, and please pardon the intrusion!</td></tr></body></html>";

// Formulate body text for plain text version
$message5Text = $_SESSION['dearline']."\n\nNew Resolution LLC is now inviting suitable candidates to join the New Resolution Mediation Platform. The Platform will interest you as a family law attorney and a mediator if you'd like to further extend the reach of your law practice into divorce, custody, or family/small business mediation.\n\n";
$message5Text .= "In summary, for less than the cost of a monthly utility bill, the Platform grants you:\n\n";
$message5Text .= "1. Broader online visibility as a mediator, complementary to your existing site.\n\n";
$message5Text .= "2. A complementary professional identity, bringing clarity and focus to your role as a mediator.\n\n";
$message5Text .= "3. Membership in a professionally branded network of independent mediators.\n\n";
$message5Text .= "4. Access to a comprehensive library of proprietary, field-tested, mediation marketing resources.\n\n";
$message5Text .= "5. The freedom to focus your talents on client service and business development.\n\n";
$message5Text .= "Please take a few minutes to learn more about the Platform by visiting our prospectus page: www.newresolutionmediation.com/attorney\n\n";
$message5Text .= "You may submit a request for a consultation and online demo via that page. Better yet, explore the Platform for yourself by clicking the 'test drive' buttons on the page. And please let me know if I can answer any questions.\n\n";
$message5Text .= "We're currently focusing our outreach efforts in ".$_SESSION['AreaLabel']." and would be glad to welcome you or your law firm to our network of independent mediators. Please sign up directly via the prospectus page.\n\n";
$message5Text .= "Sincerely\n
Paul Merlyn\n
Principal Mediator\n
New Resolution LLC\n
844 California Street | San Francisco | CA 94108\n
paul@newresolutionmediation.com
www.newresolutionmediation.com
T. 415.378.7003\n;
F. 415.366.3005";

//FormMailCode = "AttyNonMed (6. Attorney Non-Mediator)

// Formulate body text for HTML version:
$subject6 = 'Expand Your Family Law Practice into Mediation';
$message6HTML = "<html><body><table cellspacing='16'><tr><td style='font-family: Arial, Helvetica, sans-serif'>".$_SESSION['dearline']."</td></tr>";
$message6HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>New Resolution LLC has recently launched the New Resolution Mediation Platform,&trade; and we are now inviting suitable candidates to join. The Platform will interest you as a family law attorney if you&rsquo;d like to extend your legal services into the expanding realm of mediation.</td></tr>";
$message6HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>In summary, for <u>less than the cost of a monthly utility bill</u>, the Platform grants you:</td></tr>";
$message6HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'><ol>";
$message6HTML .= "<li><strong>Online visibility</strong> as a mediator on the newresolutionmediation.com site.</li>";
$message6HTML .= "<li>A <strong>complementary professional identity</strong>, bringing clarity and focus to your expanded role as a mediator.</li>";
$message6HTML .= "<li>Membership in a professionally branded <strong>network of independent mediators</strong>.</li>";
$message6HTML .= "<li>Access to a <strong>comprehensive library</strong> of proprietary, field-tested, mediation marketing resources.</li>";
$message6HTML .= "<li>The <strong>freedom to focus</strong> your talents on client service and business development.</li>";
$message6HTML .= "</ol></td></tr>";
$message6HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Please take a few minutes to learn more about the Platform on our prospectus page [click <a href='http://www.newresolutionmediation.com/attorney'>here</a>] or point your browser to: www.newresolutionmediation.com/attorney";
$message6HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>You may submit a request for a consultation and online demo via that page. Better yet, explore the Platform for yourself by clicking the &lsquo;test drive&rsquo; buttons on the page. And please let me know if I can answer any questions.</td></tr>";
$message6HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>We&rsquo;re currently focusing our outreach efforts in ".$_SESSION['AreaLabel']." and would be glad to welcome you or your law firm to our network of independent mediators. Please sign up directly via the prospectus page.</td></tr>";
$message6HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Sincerely</td></tr>";
$message6HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Paul Merlyn<br />";
$message6HTML .= "Principal Mediator<br />";
$message6HTML .= "<strong>New Resolution LLC</strong><br />";
$message6HTML .= "844 California Street | San Francisco | CA 94108<br />";
$message6HTML .= "<a href='http://www.newresolutionmediation.com'>www.newresolutionmediation.com</a><br />";
$message6HTML .= "<a href='mailto:paul@newresolutionmediation.com'>paul@newresolutionmediation.com</a><br />";
$message6HTML .= "T. 415.378.7003<br />";
$message6HTML .= "F. 415.366.3005</td></tr>";
$message6HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>P.S. In most locations, we only accept <span style='color: red'>one mediator per location</span> (see the prospectus for details). Please act quickly to avoid missing out.</td></tr>";
$message6HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif; font-size: 10px;'>If you don&rsquo;t want to receive further communications regarding the New Resolution network of independent mediators, click <a href='http://www.newresolutionmediation.com/scripts/unsubscriber.php?code=".$hashcode."'>here</a>. Thank you, and please pardon the intrusion!</td></tr></body></html>";

// Formulate body text for plain text version
$message6Text = $_SESSION['dearline']."\n\nNew Resolution LLC has recently launched the New Resolution Mediation Platform, and we are now inviting suitable candidates to join. The Platform will interest you as a family law attorney if you&rsquo;d like to extend your legal services into the expanding realm of mediation.\n\n";
$message6Text .= "In summary, for less than the cost of a monthly utility bill, the Platform grants you:\n\n";
$message6Text .= "1. Online visibility as a mediator on the newresolutionmediation.com site.\n\n";
$message6Text .= "2. A complementary professional identity, bringing clarity and focus to your expanded role as a mediator.\n\n";
$message6Text .= "3. Membership in a professionally branded network of independent mediators.\n\n";
$message6Text .= "4. Access to a comprehensive library of proprietary, field-tested, mediation marketing resources.\n\n";
$message6Text .= "5. The freedom to focus your talents on client service and business development.\n\n";
$message6Text .= "Please take a few minutes to learn more about the Platform by visiting our prospectus page: www.newresolutionmediation.com/attorney\n\n";
$message6Text .= "You may submit a request for a consultation and online demo via that page. Better yet, explore the Platform for yourself by clicking the 'test drive' buttons on the page. And please let me know if I can answer any questions.\n\n";
$message6Text .= "We're currently focusing our outreach efforts in ".$_SESSION['AreaLabel']." and would be glad to welcome you or your law firm to our network of independent mediators. Please sign up directly via the prospectus page.\n\n";
$message6Text .= "Sincerely\n
Paul Merlyn\n
Principal Mediator\n
New Resolution LLC\n
844 California Street | San Francisco | CA 94108\n
paul@newresolutionmediation.com
www.newresolutionmediation.com
T. 415.378.7003\n;
F. 415.366.3005\n\n";
$message6Text .= "P.S. In most locations, we only accept one mediator per location (see the prospectus for details). Please act quickly to avoid missing out.";

//FormMailCode = "TherapMed (7. Therapist Mediator)

// Formulate body text for HTML version:
$subject7 = 'Therapists: Expand Your Mediation Practice';
$message7HTML = "<html><body><table cellspacing='16'><tr><td style='font-family: Arial, Helvetica, sans-serif'>".$_SESSION['dearline']."</td></tr>";
$message7HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>New Resolution LLC is now inviting suitable candidates to join the New Resolution Mediation Platform&trade;. The Platform will interest you as a psychotherapist and as a mediator if you&rsquo;d like to further extend your reach into the expanding market for divorce, custody, and family/small business mediation.</td></tr>";
$message7HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>In summary, for <u>less than the cost of a monthly utility bill</u>, the Platform grants you:</td></tr>";
$message7HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'><ol>";
$message7HTML .= "<li><strong>Online visibility</strong> as a mediator on the award-winning newresolutionmediation.com site.</li>";
$message7HTML .= "<li>A <strong>complementary professional identity</strong>, bringing clarity and focus to your role as a mediator.</li>";
$message7HTML .= "<li>Membership in a <strong>professional network</strong> of independent mediators.</li>";
$message7HTML .= "<li>Access to a <strong>comprehensive library</strong> of proprietary, field-tested, mediation marketing resources.</li>";
$message7HTML .= "<li>The <strong>freedom to focus</strong> your talents on client service and business development.</li>";
$message7HTML .= "</ol></td></tr>";
$message7HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Please take a few minutes to learn more about the Platform on our prospectus page [click <a href='http://www.newresolutionmediation.com/therapist'>here</a>] or point your browser to: www.newresolutionmediation.com/therapist";
$message7HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>You may submit a request for a consultation and online demo via that page. Better yet, explore the Platform for yourself by clicking the &lsquo;test drive&rsquo; buttons on the page. And please let me know if I can answer any questions. I think you&rsquo;ll immediately understand how the New Resolution Mediation Platform&trade; can easily and effectively accelerate your business.</td></tr>";
$message7HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>We&rsquo;re currently focusing our outreach efforts in ".$_SESSION['AreaLabel']." and would be glad to welcome you or your law firm to our network of independent mediators. Please sign up directly via the prospectus page.</td></tr>";
$message7HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Sincerely</td></tr>";
$message7HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Paul Merlyn<br />";
$message7HTML .= "Principal Mediator<br />";
$message7HTML .= "<strong>New Resolution LLC</strong><br />";
$message7HTML .= "844 California Street | San Francisco | CA 94108<br />";
$message7HTML .= "<a href='http://www.newresolutionmediation.com'>www.newresolutionmediation.com</a><br />";
$message7HTML .= "<a href='mailto:paul@newresolutionmediation.com'>paul@newresolutionmediation.com</a><br />";
$message7HTML .= "T. 415.378.7003<br />";
$message7HTML .= "F. 415.366.3005</td></tr>";
$message7HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif; font-size: 10px;'>If you don&rsquo;t want to receive further communications regarding the New Resolution network of independent mediators, click <a href='http://www.newresolutionmediation.com/scripts/unsubscriber.php?code=".$hashcode."'>here</a>. Thank you, and please pardon the intrusion!</td></tr></body></html>";

// Formulate body text for plain text version
$message7Text = $_SESSION['dearline']."\n\nNew Resolution LLC is now inviting suitable candidates to join the New Resolution Mediation Platform. The Platform will interest you as a psychotherapist and a mediator if you'd like to further extend your reach into the expanding market for divorce, custody, and family/small business mediation.\n\n";
$message7Text .= "In summary, for less than the cost of a monthly utility bill, the Platform grants you:\n\n";
$message7Text .= "1. Online visibility as a mediator on the award-winning newresolutionmediation.com site.\n\n";
$message7Text .= "2. A complementary professional identity, bringing clarity and focus to your role as a mediator.\n\n";
$message7Text .= "3. Membership in a professionally branded network of independent mediators.\n\n";
$message7Text .= "4. Access to a comprehensive library of proprietary, field-tested, mediation marketing resources.\n\n";
$message7Text .= "5. The freedom to focus your talents on client service and business development.\n\n";
$message7Text .= "Please take a few minutes to learn more about the Platform by visiting our prospectus page: www.newresolutionmediation.com/therapist\n\n";
$message7Text .= "You may submit a request for a consultation and online demo via that page. Better yet, explore the Platform for yourself by clicking the 'test drive' buttons on the page. And please let me know if I can answer any questions. I think you'll immediately understand how the New Resolution Mediation Platform can easily and effectively accelerate your business.\n\n";
$message7Text .= "We're currently focusing our outreach efforts in ".$_SESSION['AreaLabel']." and would be glad to welcome you to our network of independent mediators. Please sign up directly via the prospectus page.\n\n";
$message7Text .= "Sincerely\n
Paul Merlyn\n
Principal Mediator\n
New Resolution LLC\n
844 California Street | San Francisco | CA 94108\n
paul@newresolutionmediation.com
www.newresolutionmediation.com
T. 415.378.7003\n;
F. 415.366.3005";

//FormMailCode = "TherapExt (8. Therapist Non-Mediator/Extend Scope)

// Formulate body text for HTML version:
$subject8 = 'Therapists: Apply Your Skills as a Divorce/Family Mediator';
$message8HTML = "<html><body><table cellspacing='16'><tr><td style='font-family: Arial, Helvetica, sans-serif'>".$_SESSION['dearline']."</td></tr>";
$message8HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>New Resolution LLC has recently launched the New Resolution Mediation Platform,&trade; and we are now inviting suitable candidates to join. The Platform will interest you as a counselor or psychotherapist if you&rsquo;d like to apply your skills in the expanding market for divorce and family mediation services.</td></tr>";
$message8HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>In summary, for <u>less than the cost of a monthly utility bill</u>, the Platform grants you:</td></tr>";
$message8HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'><ol>";
$message8HTML .= "<li><strong>Online visibility</strong> as a mediator on the award-winning newresolutionmediation.com site.</li>";
$message8HTML .= "<li>A <strong>complementary professional identity</strong>, bringing clarity and focus to your role as a mediator.</li>";
$message8HTML .= "<li>Membership in a <strong>professional network</strong> of independent mediators.</li>";
$message8HTML .= "<li>Access to a <strong>comprehensive library</strong> of proprietary, field-tested, mediation marketing resources.</li>";
$message8HTML .= "<li>The <strong>freedom to focus</strong> your talents on clients and business development.</li>";
$message8HTML .= "</ol></td></tr>";
$message8HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Please take a few minutes to learn more about the Platform on our prospectus page [click <a href='http://www.newresolutionmediation.com/therapist'>here</a>] or point your browser to: www.newresolutionmediation.com/therapist";
$message8HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>You may submit a request for a consultation and online demo via that page. Better yet, explore the Platform for yourself by clicking the &lsquo;test drive&rsquo; buttons on the page. And please let me know if I can answer any questions. I think you&rsquo;ll immediately understand how the New Resolution Mediation Platform&trade; can easily and effectively accelerate your business.</td></tr>";
$message8HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>We&rsquo;re currently focusing our outreach efforts in ".$_SESSION['AreaLabel']." and would be glad to welcome you to our network of independent mediators. Please sign up directly via the prospectus page.</td></tr>";
$message8HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Sincerely</td></tr>";
$message8HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Paul Merlyn<br />";
$message8HTML .= "Principal Mediator<br />";
$message8HTML .= "<strong>New Resolution LLC</strong><br />";
$message8HTML .= "844 California Street | San Francisco | CA 94108<br />";
$message8HTML .= "<a href='http://www.newresolutionmediation.com'>www.newresolutionmediation.com</a><br />";
$message8HTML .= "<a href='mailto:paul@newresolutionmediation.com'>paul@newresolutionmediation.com</a><br />";
$message8HTML .= "T. 415.378.7003<br />";
$message8HTML .= "F. 415.366.3005</td></tr>";
$message8HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif; font-size: 10px;'>If you don&rsquo;t want to receive further communications regarding the New Resolution network of independent mediators, click <a href='http://www.newresolutionmediation.com/scripts/unsubscriber.php?code=".$hashcode."'>here</a>. Thank you, and please pardon the intrusion!</td></tr></body></html>";

// Formulate body text for plain text version
$message8Text = $_SESSION['dearline']."\n\nNew Resolution LLC has recently launched the New Resolution Mediation Platform, and we are now inviting suitable candidates to join. The Platform will interest you as a psychotherapist if you'd like to apply your skills in the expanding market for divorce and family mediation services.\n\n";
$message8Text .= "In summary, for less than the cost of a monthly utility bill, the Platform grants you:\n\n";
$message8Text .= "1. Online visibility as a mediator on the award-winning newresolutionmediation.com site.\n\n";
$message8Text .= "2. A complementary professional identity, bringing clarity and focus to your role as a mediator.\n\n";
$message8Text .= "3. Membership in a professional network of independent mediators.\n\n";
$message8Text .= "4. Access to a comprehensive library of proprietary, field-tested, mediation marketing resources.\n\n";
$message8Text .= "5. The freedom to focus your talents on clients and business development.\n\n";
$message8Text .= "Please take a few minutes to learn more about the Platform by visiting our prospectus page: www.newresolutionmediation.com/therapist\n\n";
$message8Text .= "You may submit a request for a consultation and online demo via that page. Better yet, explore the Platform for yourself by clicking the 'test drive' buttons on the page. And please let me know if I can answer any questions. I think you'll immediately understand how the New Resolution Mediation Platform&trade; can easily and effectively accelerate your business.\n\n";
$message8Text .= "We're currently focusing our outreach efforts in ".$_SESSION['AreaLabel']." and would be glad to welcome you to our network of independent mediators. Please sign up directly via the prospectus page.\n\n";
$message8Text .= "Sincerely\n
Paul Merlyn\n
Principal Mediator\n
New Resolution LLC\n
844 California Street | San Francisco | CA 94108\n
paul@newresolutionmediation.com
www.newresolutionmediation.com
T. 415.378.7003\n;
F. 415.366.3005";

//FormMailCode = "LawStud (13. Law Students)

// Formulate body text for HTML version:
$subject13 = 'Begin Your Mediation Practice Today';
$message13HTML = "<html><body><table cellspacing='16'><tr><td style='font-family: Arial, Helvetica, sans-serif'>".$_SESSION['dearline']."</td></tr>";
$message13HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>If you&rsquo;re a law student trained in Alternative Dispute Resolution, the Mediation Platform&trade; from New Resolution LLC will interest you. The Platform provides an immediate, flexible, and cost-effective solution for mediators who want to develop an independent ADR practice. As you probably know, thousands of non-attorney and attorney mediators currently derive fulfillment as well as supplemental income (and in some cases, full-time income) from mediation, enabling their clients to resolve disputes outside of the traditional contested litigation system. Would you like to join them?</td></tr>";
$message13HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>In summary, for <u>less than the cost of a monthly utility bill</u>, the Platform grants you:</td></tr>";
$message13HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'><ol>";
$message13HTML .= "<li><strong>Online visibility</strong> as a mediator on the newresolutionmediation.com site.</li>";
$message13HTML .= "<li>A <strong>complementary professional identity</strong>, bringing clarity and focus to your role as a mediator.</li>";
$message13HTML .= "<li>Membership in a <strong>professionally branded network</strong> of independent mediators.</li>";
$message13HTML .= "<li>Access to a <strong>comprehensive library</strong> of proprietary, field-tested, mediation marketing resources.</li>";
$message13HTML .= "<li>The <strong>freedom to focus</strong> your talents on client service and business development.</li>";
$message13HTML .= "</ol></td></tr>";
$message13HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Please take a few minutes to learn more about the Platform on our prospectus page [click <a href='http://www.newresolutionmediation.com/attorney'>here</a>] or point your browser to: www.newresolutionmediation.com/attorney";
$message13HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>You may submit a request for a consultation and online demo via that page. Better yet, explore the Platform for yourself by clicking the &lsquo;test drive&rsquo; buttons on the page. And please let me know if I can answer any questions.</td></tr>";
$message13HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>We&rsquo;re currently focusing our outreach efforts in ".$_SESSION['AreaLabel']." and would be glad to welcome you to our network of independent mediators. Please sign up directly via the prospectus page.</td></tr>";
$message13HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Sincerely</td></tr>";
$message13HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Paul Merlyn<br />";
$message13HTML .= "President & CEO<br />";
$message13HTML .= "<strong>New Resolution LLC</strong><br />";
$message13HTML .= "844 California Street | San Francisco | CA 94108<br />";
$message13HTML .= "<a href='http://www.newresolutionmediation.com'>www.newresolutionmediation.com</a><br />";
$message13HTML .= "<a href='mailto:paul@newresolutionmediation.com'>paul@newresolutionmediation.com</a><br />";
$message13HTML .= "T. 415.378.7003<br />";
$message13HTML .= "F. 415.366.3005</td></tr>";
$message13HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>P.S. In most locations, we only accept <span style='color: red'>one mediator per location</span> (see the prospectus for details). Please act quickly to avoid missing out to another mediator in your area.</td></tr>";
$message13HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif; font-size: 10px;'>If you don&rsquo;t want to receive further communications regarding the New Resolution network of independent mediators, click <a href='http://www.newresolutionmediation.com/scripts/unsubscriber.php?code=".$hashcode."'>here</a>. Thank you, and please pardon the intrusion!</td></tr></body></html>";

// Formulate body text for plain text version
$message13Text = $_SESSION['dearline']."\n\nIf you're a law student trained in Alternative Dispute Resolution, the Mediation Platform from New Resolution LLC will interest you. The Platform provides an immediate, flexible, and cost-effective solution for mediators who want to develop an independent ADR practice. Thousands of non-attorney and attorney mediators currently derive fulfillment as well as supplemental income (and, in some cases, full-time income) from mediation, enabling their clients to resolve disputes outside of the traditional contested litigation system.\n\n";
$message13Text .= "In summary, for less than the cost of a monthly utility bill, the Platform grants you:\n\n";
$message13Text .= "1. Online visibility as a mediator on the newresolutionmediation.com site.\n\n";
$message13Text .= "2. A complementary professional identity, bringing clarity and focus to your role as a mediator.\n\n";
$message13Text .= "3. Membership in a professionally branded network of independent mediators.\n\n";
$message13Text .= "4. Access to a comprehensive library of proprietary, field-tested, mediation marketing resources.\n\n";
$message13Text .= "5. The freedom to focus your talents on client service and business development.\n\n";
$message13Text .= "Please take a few minutes to learn more about the Platform by visiting our prospectus page: www.newresolutionmediation.com/attorney\n\n";
$message13Text .= "You may submit a request for a consultation and online demo via that page. Better yet, explore the Platform for yourself by clicking the 'test drive' buttons on the page. And please let me know if I can answer any questions.\n\n";
$message13Text .= "We're currently focusing our outreach efforts in ".$_SESSION['AreaLabel']." and would be glad to welcome you to our network of independent mediators. Please sign up directly via the prospectus page.\n\n";
$message13Text .= "Sincerely\n
Paul Merlyn\n
Principal Mediator\n
New Resolution LLC\n
844 California Street | San Francisco | CA 94108\n
paul@newresolutionmediation.com
www.newresolutionmediation.com
T. 415.378.7003\n;
F. 415.366.3005\n\n";
$message13Text .= "P.S. In most locations, we only accept one mediator per location (see the prospectus for details). Please act quickly to avoid missing out to another mediator in your area.";

//FormMailCode = "TherapStud (14. Therapist Students)

// Formulate body text for HTML version:
$subject14 = 'Begin Your Mediation Practice Today';
$message14HTML = "<html><body><table cellspacing='16'><tr><td style='font-family: Arial, Helvetica, sans-serif'>".$_SESSION['dearline']."</td></tr>";
$message14HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>If you&rsquo;re a therapy student, intern, or resident with training in Alternative Dispute Resolution, the Mediation Platform&trade; from New Resolution LLC will interest you. The Platform provides an easy, flexible, and cost-effective solution for mediators to quickly develop an independent mediation practice. As you probably know, thousands of mediators from diverse backgrounds currently derive fulfillment as well as supplemental income (and in some cases, full-time income) from mediation, enabling their clients to resolve disputes outside of the traditional contested litigation system.</td></tr>";
$message14HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>In summary, for <u>less than the cost of a monthly utility bill</u>, the Platform grants you:</td></tr>";
$message14HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'><ol>";
$message14HTML .= "<li><strong>Online visibility</strong> as a mediator on the newresolutionmediation.com site.</li>";
$message14HTML .= "<li>A <strong>complementary professional identity</strong>, bringing clarity and focus to your role as a mediator.</li>";
$message14HTML .= "<li>Membership in a professionally branded <strong>network of independent mediators</strong>.</li>";
$message14HTML .= "<li>Access to a <strong>comprehensive library</strong> of proprietary, field-tested, mediation marketing resources.</li>";
$message14HTML .= "<li>The <strong>freedom to focus</strong> your talents on client service and business development.</li>";
$message14HTML .= "</ol></td></tr>";
$message14HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Please take a few minutes to learn more about the Platform on our prospectus page [click <a href='http://www.newresolutionmediation.com/therapist'>here</a>] or point your browser to: www.newresolutionmediation.com/therapist";
$message14HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>You may submit a request for a consultation and online demo via that page. Better yet, explore the Platform for yourself by clicking the &lsquo;test drive&rsquo; buttons on the page. And please let me know if I can answer any questions.</td></tr>";
$message14HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>We&rsquo;re currently focusing our outreach efforts in ".$_SESSION['AreaLabel']." and would be glad to welcome you to our network of independent mediators. Please sign up directly via the prospectus page.</td></tr>";
$message14HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Sincerely</td></tr>";
$message14HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>Paul Merlyn<br />";
$message14HTML .= "President & CEO<br />";
$message14HTML .= "<strong>New Resolution LLC</strong><br />";
$message14HTML .= "844 California Street | San Francisco | CA 94108<br />";
$message14HTML .= "<a href='http://www.newresolutionmediation.com'>www.newresolutionmediation.com</a><br />";
$message14HTML .= "<a href='mailto:paul@newresolutionmediation.com'>paul@newresolutionmediation.com</a><br />";
$message14HTML .= "T. 415.378.7003<br />";
$message14HTML .= "F. 415.366.3005</td></tr>";
$message14HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif'>P.S. In most locations, we only accept <span style='color: red'>one mediator per location</span> (see the prospectus for details). Please act quickly to avoid missing out to another mediator in your area.</td></tr>";
$message14HTML .= "<tr><td style='font-family: Arial, Helvetica, sans-serif; font-size: 10px;'>If you don&rsquo;t want to receive further communications regarding the New Resolution network of independent mediators, click <a href='http://www.newresolutionmediation.com/scripts/unsubscriber.php?code=".$hashcode."'>here</a>. Thank you, and please pardon the intrusion!</td></tr></body></html>";

// Formulate body text for plain text version
$message14Text = $_SESSION['dearline']."\n\nIf you're a therapy student, intern, or resident with training in Alternative Dispute Resolution, the Mediation Platform from New Resolution LLC will interest you. The Platform provides an easy, flexible, and cost-effective solution for mediators to quickly develop an independent mediation practice. As you probably know, thousands of mediators from diverse backgrounds currently derive fulfillment as well as supplemental income (and in some cases, full-time income) from mediation, enabling their clients to resolve disputes outside of the traditional contested litigation system.\n\n";
$message14Text .= "In summary, for less than the cost of a monthly utility bill, the Mediation Platform grants you:\n\n";
$message14Text .= "1. Online visibility as a mediator on the newresolutionmediation.com site.\n\n";
$message14Text .= "2. A complementary professional identity, bringing clarity and focus to your role as a mediator.\n\n";
$message14Text .= "3. Membership in a professionally branded network of independent mediators.\n\n";
$message14Text .= "4. Access to a comprehensive library of proprietary, field-tested, mediation marketing resources.\n\n";
$message14Text .= "5. The freedom to focus your talents on client service and business development.\n\n";
$message14Text .= "Please take a few minutes to learn more about the Platform by visiting our prospectus page: www.newresolutionmediation.com/therapist\n\n";
$message14Text .= "You may submit a request for a consultation and online demo via that page. Better yet, explore the Platform for yourself by clicking the 'test drive' buttons on the page. And please let me know if I can answer any questions.\n\n";
$message14Text .= "We're currently focusing our outreach efforts in ".$_SESSION['AreaLabel']." and would be glad to welcome you to our network of independent mediators. Please sign up directly via the prospectus page.\n\n";
$message14Text .= "Sincerely\n
Paul Merlyn\n
Principal Mediator\n
New Resolution LLC\n
844 California Street | San Francisco | CA 94108\n
paul@newresolutionmediation.com
www.newresolutionmediation.com
T. 415.378.7003\n;
F. 415.366.3005\n\n";
$message14Text .= "P.S. In most locations, we only accept one mediator per location (see the prospectus for details). Please act quickly to avoid missing out to another mediator in your area.";
?>