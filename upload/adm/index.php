<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/lang/admin/lang_'.$config['lang'].'.php');
require_once('class.menu.php');

// Connect to database
db_connect($config);

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config,$lang);

$validate_comments = mysql_num_rows(mysql_query("SELECT 1 FROM `".$config['db']['pre']."comments` WHERE comment_status='0'"));
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $config['site_title'].' '.$lang['ADMINCP']; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="css/main.css">
<link rel="stylesheet" type="text/css" href="images/style.css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/JSCookMenu.js"></SCRIPT>
<LINK REL="stylesheet" HREF="menu/themes/Office/theme.css" TYPE="text/css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/themes/Office/theme.js"></SCRIPT>
<script language="JavaScript"><!--
function checkBox(theBox){
  var aBox = theBox.form["list[]"];
  var selAll = false;
  var i;
  for(i=0;i<aBox.length;i++){
    if(aBox[i].checked==false) selAll=true;
  }
  if(theBox.name=="selall"){
    for(i=0;i<aBox.length;i++){
      aBox[i].checked = selAll;
    }
    selAll = !selAll;
  }
}
function init(){
  var theForm = document.f1;
  var aBox = theForm["list[]"];
  var selAll = false;
  var i;
  for(i=0;i<aBox.length;i++){
    if(aBox[i].checked==false) selAll=true;
    aBox[i].onclick = function(){checkBox(this)};
  }
}
//--></script>
</head>

<body onLoad="init()">
<!--Start top-->
<table width="850" cellpadding="0" cellspacing="0" border="0" align="center" style="margin-top: 20px;">
  <tr>
    <td height="42" valign="middle" style="width: 899px; background: #88A2F0; color: #F1F1F1; font-size: 24px; font-weight: bold; padding: 10px;"><?php echo $config['site_title'] ;?> Admin CP</td>
  </tr>
  <tr>
    <td><img src="images/dot.gif" width="1" height="1" alt=""></td>
  </tr>
</table>
<!--End top-->
<!--Start topmenu-->
<table width="850" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
<td bgcolor="#F0F0F0" height="25" style="padding-left:20px;" id="menu">
</td><SCRIPT language="JavaScript" type="text/javascript">
			var myMenu =
				
			// Start the menu
[
<?php echo $nav; ?>
];				

			// Output the menu
			cmDraw ('menu', myMenu, 'hbr', cmThemeOffice, 'ThemeOffice');
			</SCRIPT>
</tr>
<tr>
<td bgcolor="#333333"><img src="images/dot.gif" width="1" height="1" alt=""></td>
</tr>
</table>
<!--End topmenu-->
<br>
<div align="center">
<!--Show Messages-->
<?php

if (isset($_GET['error']))
{
     echo "<strong><h2>Error!</h2>\n";
     echo "Error Code: <font color='red'>".$_GET['error']."</font></strong><br><br>\n";
     echo "Please post on our <a href='http://vicblog/vichost.com/forums/' target='_new'>Forums</a> with the error code above.<br><br>\n";
}
elseif (isset($_GET['message'])) 
{
    echo '<strong>'.$_GET['message'].'</strong><br><br>';
}

?>
<!--End Messages-->
</div>
<!--Start heading page-->
<table width="850" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
<td class="heading"><?php echo $lang['LATEST_NEWS']; ?></td>
</tr>
<tr>
<td bgcolor="#333333"><img src="images/dot.gif" width="1" height="1" alt=""></td>
</tr>
</table>
<!--End heading page-->
<!-- Get News From VicBlogs -->
<table width="850" align="center">
<tr>
<td bgcolor="#F6F6F6">
<?php
echo "<iframe align='center' style='width: 100%; border: 1px solid silver; height: 300px;' src='http://www.vichost.com/vicblog/updates.php'></iframe>";
?> 
</td>
</tr>
</table>
<!-- News End -->
<br>
<!--Start form-->
<br>
<table width="850" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #CCCCCC;" align="center">
<tr>
  <td bgcolor="#F6F6F6" style="padding:15px;"><?php echo $validate_comments.' ' .$lang['COMVAL']; ?></td>
</tr>
</table>
<!--End form-->
<br>
<!--Start bottom-->
<table width="850" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
<td bgcolor="#333333"><img src="images/dot.gif" width="1" height="1" alt=""></td>
</tr>
<tr>
<td style="padding:15px;" align="center">
<span class="copyright">Copyright &copy; 2009-2010 <a href="http://vicblog.vichost.com" class="copyright" target="_blank">VicBlog Development</a> All Rights Reserved.</span></td>
</tr>
</table>
<!--End bottom-->
</body>
</html>