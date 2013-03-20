<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<!-- For (non-techie) licensees, I have sought a method of providing licensees with a single file as their (customized) email signature. Ordinarily, the signature (if it contained an image) would comprise one html file and a second (jpg or gif) image file that is hreferenced via the img tag. I achieve a single file by using a base64-encoded image that is actually embedded into the HTML file (hence no need for a separate image file). I got the code (which I had to modify to make it work) from here: http://www.sweeting.org/mark/blog/2005/07/12/base64-encoded-images-embedded-in-html. -->
<!-- I'm allowing licensees to choose between two types of signature: one has a static NR logo, and the other has an animated GIF logo. -->
<!-- To access the base64-encoded image, open the source file of the output in your browser. -->
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Email Signature Creator (Non-Animated Logo)</title>
</head>

<body>

<div style="font-size: 11px; font-weight:bold; font-family:Arial, Helvetica, sans-serif;">
<table cellpadding="1" cellspacing="0">
<tr>
<td height="20" valign="top"><span style="text-align:left">John J. Doe, CFP &nbsp;&#8226;&nbsp;</span>Mediator&nbsp;&#8226;&nbsp;<span style="text-align:right">New Resolution LLC </span></td>
</tr>
<tr>
<td height="20" valign="top">10260 N. Maple Street</span>&nbsp;&#8226;&nbsp;<span style="text-align:right">Urbana-Champaign&nbsp;&#8226;&nbsp;IL 61820 </span></td>
</tr>
<tr>
<td height="20" valign="top"><span style="font-size: 11px; font-weight:bold; font-family:Arial, Helvetica, sans-serif; text-decoration: none; color: #000000;">jdoe@newresolutionmediation.com</span>&nbsp;&#8226;&nbsp;<span style="text-align:right;">&nbsp;309.712.4567</span></td>
</tr>
<tr>
<td height="20" valign="top"><span style="font-size: 11px; font-weight:bold; font-family:Arial, Helvetica, sans-serif">www.newresolutionmediation.com</span>&nbsp;&#8226;&nbsp; <span style="text-align:right">309.712.4568 fax</span></td>
</tr>
<tr>
<td height="20" valign="bottom">
<span style="font-size: 10px"><font size="1">Member of the New Resolution network of independent mediators</font></span>
</td>
</tr>
<tr>
<td>
<?php
// Edit the file-path line below to point to whatever image you want to encode. Note: if it's a GIF (JPG) image extension, make sure the MIME type is changed accordingly in the line that reads "src="data:image/gif;base64". Also change width and height accordingly (e.g. w=211, h=96 for animated gif).
$file = "/home/paulme6/public_html/nrmedlic/images/NewResolutionLogoBasic.jpg";
if($fp = fopen($file,"rb", 0))
{
   $picture = fread($fp,filesize($file));
   fclose($fp);
   // base64 encode the binary data, then break it
   // into chunks according to RFC 2045 semantics
   $base64 = base64_encode($picture);
   $tag = '<img border="0" ' .
          'src="data:image/jpg;base64,' . $base64 .
          '" width="244" height="66" alt="New Resolution Logo"/>';
   echo $tag;
}
?>
</td>
</tr>
</table>
</div>

</body>
</html>
