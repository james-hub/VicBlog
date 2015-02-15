<?php
require_once('../includes/config.php');
require_once('../includes/lang/admin/lang_'.$config['lang'].'.php');

if(isset($help[$_GET['id']]['subject']))
{
	$subject = $help[$_GET['id']]['subject'];
}
else
{
	$subject = $lang['NOHELP'];
}

if(isset($help[$_GET['id']]['message']))
{
	$msg = $help[$_GET['id']]['message'];
}
else
{
	$msg = $lang['NOHELP'];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Help: <?php echo $subject; ?></title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.style1 {
	color: #FFFFFF;
	font-weight: bold;
	font-family: Tahoma, Verdana;
	font-size: 14px;
}
.style2 {
	font-size: 14px;
	font-family: Tahoma, Verdana;
}
-->
</style></head>

<body>
<table width="500" height="200" border="0" cellpadding="4" cellspacing="0">
  <tr>
    <td height="20" bgcolor="#384771"><span class="style1"><?php echo $subject; ?></span></td>
  </tr>
  <tr>
    <td valign="top"><span class="style2"><?php echo $msg; ?></span></td>
  </tr>
  <tr>
    <td height="20" valign="top" class="style2"><div align="right"><?=$lang['NEEDHELP'];?></div></td>
  </tr>
</table>
</body>
</html>