<?php
require_once('includes/config.php');
require_once('includes/functions/func.global.php');

// Start session
session_start();

if($_SESSION['kbuser']['secviewed'] == '1')
{
	$filedata = base64_decode('R0lGODlhAQABAIAAAMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');
	$filesize = strlen($filedata);
	
	header('Content-type: image/gif');
	header('Content-Length: ' . $filesize);
	header('Connection: Close');
	
	echo $filedata;
	exit;
}

// Get 5 random characters
$captchastr = $_SESSION['kbuser']['seccode'];

// Create the image
$captcha = imagecreate(250,50);

// Set background color
$backcolor = imagecolorallocate($captcha, 255, 255, 255);

$linecolor = imagecolorallocate($captcha, 240, 240, 240);

// Set Text color
$txtcolor = imagecolorallocate($captcha, 86, 86, 86);

$gd_support = gd_info();

// Run through the 5 characters and add them to the image
for($i=1;$i<=5;$i++)
{
	$rotdirection = rand(1,2);
	
	if ($rotdirection == 1)
	{
		$angle = rand(0,20);
	}
	
	if ($rotdirection == 2)
	{
		$angle = rand(345,360);
	}
	
	if($gd_support['FreeType Support'])
	{
		imagettftext($captcha,rand(16,22),$angle,($i*30),30,$txtcolor,"includes/fonts/edmunds.ttf",substr($captchastr,($i-1),1));
	}
	else
	{
		imagestring($captcha, 5, ($i*30), 20, substr($captchastr,($i-1),1), $txtcolor);
	}
}

// Set the string to session
$_SESSION['kbuser']['seccode'] = $captchastr;

//Send the png header
header('Content-type: image/png');

//Output the image as a PNG
imagepng($captcha);

//Delete the image from memory
imagedestroy($captcha);

$_SESSION['kbuser']['secviewed'] = '1';
?>