<?php
/*
democlientpaths1.php (stored under /scripts) is very similar to democlientpaths2.php (stored under /ssi). Both files are essentially repositories of the file paths to image files, script files, ssi files, and mediator database table names. The two files differ as follows:
	democlientpaths1.php, which is called as a form action script by updateprofile.php, contains a few extra lines of code to return the code parser back to the page that called the script (i.e. back to updateprofile.php) either via $_SERVER['HTTP_REFERER'] or via Javascript's history.back() method (see below). democlientpaths2.php does not contain this extra code. It is simply included (via a require() statement) in createlocationincludes.php (which is itself included via a require() statement) as well as in createutilitymediatordata.php and in admin9.php.
*/
session_start();

// My path session variables are set here according to the value of $_POST['AccessLevel']
if ($_SESSION['ClientDemoSelected'] == 'demo')
{
$_SESSION['imagepathshort'] = '../demo/images/';
$_SESSION['dbmediatorstablename'] = 'mediators_table_demo';
$_SESSION['imagepathlong'] = '/home/paulme6/public_html/nrmedlic/demo/images/';
$_SESSION['scriptpathshort'] = '../demo/scripts/';
$_SESSION['pagepathshort'] = '../demo/';
$_SESSION['pagepathlong'] = '/home/paulme6/public_html/nrmedlic/demo/';
$_SESSION['ssipathlong'] = '/home/paulme6/public_html/nrmedlic/demo/ssi/';
}; 

if ($_SESSION['ClientDemoSelected'] == 'client') 
{
$_SESSION['imagepathshort'] = '../images/';
$_SESSION['dbmediatorstablename'] = 'mediators_table';
$_SESSION['imagepathlong'] = '/home/paulme6/public_html/nrmedlic/images/';
$_SESSION['scriptpathshort'] = '../scripts/';
$_SESSION['pagepathshort'] = '../';
$_SESSION['pagepathlong'] = '/home/paulme6/public_html/nrmedlic/';
$_SESSION['downloadablespathshort'] = '../downloadables/';
$_SESSION['ssipathlong'] = '/home/paulme6/public_html/nrmedlic/ssi/';
};
?>
