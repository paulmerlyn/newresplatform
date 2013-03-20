<?php
// Reference: www.white-hat-web-design.co.uk/articles/php-captcha.php
session_start();
if(($_SESSION['security_code'] == $_POST['security_code']) && (!empty($_SESSION['security_code'])) )
{

// Create short variable names
$CareerName = $_POST['careername'];
$CareerEmail = $_POST['careeremail'];
$CareerTelephone = $_POST['careertelephone'];
$CareerRegion = $_POST['careerregion'];
$CareerYears = $_POST['careeryears'];
$CareerStatus = $_POST['careerstatus'];
$WebSite = $_POST['website'];
$WebAddress = $_POST['webaddress'];
$CareerSource = $_POST['careersource'];
$CareerComments = $_POST['careercomments'];

// Prevent cross-site scripting
$CareerName = htmlspecialchars($CareerName, ENT_QUOTES);
$CareerEmail = htmlspecialchars($CareerEmail, ENT_QUOTES);
$CareerTelephone = htmlspecialchars($CareerTelephone, ENT_QUOTES);
$CareerRegion = htmlspecialchars($CareerRegion, ENT_QUOTES);
$WebAddress = htmlspecialchars($WebAddress, ENT_QUOTES);
$CareerSource = htmlspecialchars($CareerSource, ENT_QUOTES);
$CareerComments = htmlspecialchars($CareerComments, ENT_QUOTES);

// Send email
$message = "A user has completed the 'Career Interest' form to register an interest in the New Resolution Mediation Launch Platform. The user likely accessed this form via the Launch Platform prospectus page or via the 'Careers' footer in the newresolutionmediation.com site.\n\n";
$message .= "Name: $CareerName\n";
$message .= "Email: $CareerEmail\n";
$message .= "Telephone: $CareerTelephone\n";
$message .= "Geographic region of interest: $CareerRegion\n";
$message .= "Years of mediation experience: $CareerYears\n";
$message .= "Status of mediation practice: $CareerStatus\n";
$message .= "Does inquirer have a web site? $WebSite";
if ($WebSite == 'yes') $message .= ", and the address is: $WebAddress";
$message .= "\n";
$message .= "How did user find out about New Resolution? $CareerSource\n";
$message .= "Additional comments: $CareerComments\n";
$to = 'paul@newresolutionmediation.com';
$subject = 'Registered Interest (via nrmedlic) in Joining New Resolution Network';
$headers = "From: $CareerEmail"."\r\n" . 'X-Mailer: PHP/' . phpversion();
mail($to,$subject,$message,$headers);

unset($_SESSION['security_code']);

//Now go back to the home page (index.html) after displaying a 'Success!' alert
echo "<html><head><title>Successful Submission</title></head><body><script type='text/javascript' language='javascript'>alert('Your submission was successful! We will be in touch with you shortly to schedule a consultation and online demo. Click \'OK\' to return to the previous page.'); 
var openerURL = window.opener.location.href;
window.opener.location = openerURL; window.close();
</script></body></html>";  
exit;
}
else
{
// Insert your code for showing an error message here
echo "<html><head><title>Security Code Mismatch: Help Fight Spam</title></head><body><script type='text/javascript' language='javascript'>alert('To help fight spam, please enter the security code on the previous page before resubmitting your message. Click \'OK\' to return to the previous page.'); 
window.location = 'http://www.newresolution.org/mediationcareersform.shtml';
</script></body></html>";  
exit;
}
?>