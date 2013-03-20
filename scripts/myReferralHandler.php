<?php

// Create short variable names
$Email = $_POST['email'];

// First send referral email to the address ($Email) provided by the user
$message = "Hello,\n\nA visitor to the New Resolution Mediation web site has recommended that you visit the site. We provide mediation services to help people reach settlements and avoid the cost and distress of an adversarial process.\n\n";
$message .= "Please visit our award-winning site at www.newresolutionmediation.com. There you'll find information about mediation and other valuable resources. You may also schedule a telephone or in-person consultation with one of our mediators if you think mediation may be of value to you.\n\n";
$message .= "Sincerely,\n\n";
$message .= "New Resolution Mediation\n\n";
$message .= "P.S. A note about your privacy: We won't contact you again without your permission. Nor will we ever sell, rent, or otherwise reuse your email address.";
$to = $Email;
$subject = 'Mediation Referral';
$headers = "From: request@newresolutionmediation.com"."\r\n" . 'X-Mailer: PHP/' . phpversion();
mail($to,$subject,$message,$headers);

//Now send the user back to the home page (index.html) after displaying a 'Success!' alert
echo "<html><head><title>Successful Submission</title></head><body><script type='text/javascript' language='javascript'>alert('Your submission was successful! Click \'OK\' to return to the home page.'); location.href='/index.shtml';</script></body></html>"; 

// Also send an email to notify the site owner that a referral message was sent 
$message_notify = "This is to notify you that a visitor to the New Resolution Mediation (.com) site provided the following email address to which a site referral message was sent: ".$Email."\n\n";
$message_notify .= "The message sent to that address is pasted below:\n\n";
$message_notify .= $message;
$to_notify = 'paul@newresolutionmediation.com';
$subject_notify = 'Notification of Mediation Referral (NRmed.com)';
mail($to_notify,$subject_notify,$message_notify,$headers);
exit;
?>