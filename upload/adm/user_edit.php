<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once("../includes/functions/loggincheck.php");
require_once('class.menu.php');

// Connect to database
db_connect($config);

IF(isset($_POST['Submit']))
{
	foreach ($_POST['id'] as $value) 
	{
		mysql_query("UPDATE `".$config['db']['pre']."users` SET `email` = '".validate_input($_POST['email'][$value])."' WHERE `user_id` = '".validate_input($value)."' LIMIT 1;");
	}
	
	header("Location: search_users.php");
	exit;
}

if(isset($_GET['id']))
{
	$_POST['list'][] = $_GET['id'];
}

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>PHPDug Admin - Edit Users</title>
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
  //theBox.form.selall.checked = selAll;
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
  //theForm.selall.checked = selAll;
}
//--></script>
</head>
<body>
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
<table width="850" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td bgcolor="#F0F0F0" height="25" style="padding-left:20px;" id="menu"><SCRIPT language="JavaScript" type="text/javascript">
			var myMenu =
				
			// Start the menu
[
<?php echo $nav; ?>
];				

			// Output the menu
			cmDraw ('menu', myMenu, 'hbr', cmThemeOffice, 'ThemeOffice');
			</SCRIPT></td>
  </tr>
  <tr>
    <td bgcolor="#333333"><img src="images/dot.gif" width="1" height="1" alt=""></td>
  </tr>
</table>
<br>

<!--End topmenu-->
<!--Start heading page-->
<table width="850" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
<td class="heading"><img src="images/icons/icon_editrule.gif" width="21" height="22" alt="" align="absmiddle" hspace="5">Edit Users</td>
</tr>
<tr>
<td bgcolor="#333333"><img src="images/dot.gif" width="1" height="1" alt=""></td>
</tr>
</table>
<!--End heading page-->
<!--Start form-->
<br>
<table width="850" border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #CCCCCC;">
<tr>
<td align="center" bgcolor="#F6F6F6" style="padding:15px;">
  <form action="" method="post" name="f1" id="f1">
<?php
$count = 0;
$sql = "SELECT user_id,username,email FROM ".$config['db']['pre']."users ";

foreach ($_POST['list'] as $value) 
{
	IF($count == 0)
	{
		$sql.= "WHERE user_id='".validate_input($value)."'";
	}
	ELSE
	{
		$sql.= " OR user_id='".validate_input$value)."'";
	}
	
	$count++;
} 
$sql.= " LIMIT " . count($_POST['list']);


$query_result = mysql_query($sql);
while ($info = @mysql_fetch_array($query_result))
{
?>
<table width="70%" cellpadding="0" cellspacing="2" border="0">
  <tr>
    <td width="35%"><strong>User ID</strong></td>
    <td>:
        <input name="id[<? echo $info['user_id']; ?>]" type="Text" class="textbox" style="width:316px" value="<? echo $info['user_id']; ?>" disabled></td>
        <input name="id[<? echo $info['user_id']; ?>]" type="hidden" class="textbox" style="width:316px" value="<? echo $info['user_id']; ?>"></td>
  </tr>
  <tr>
    <td width="35%"><strong>Username</strong></td>
    <td>:
        <input name="username[<? echo $info['user_id']; ?>]" type="Text" class="textbox" style="width:316px" value="<? echo $info['username']; ?>"></td>
  </tr>
   <tr>
    <td width="35%"><strong>Email</strong></td>
    <td>:
        <input name="email[<? echo $info['user_id']; ?>]" type="Text" class="textbox" style="width:316px" value="<? echo $info['email']; ?>"></td>
  </tr>
</table>
<br><br>
<?php
}
?>
<br>
<br>
<table width="70%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="35%">&nbsp;</td>
    <td>&nbsp;
      <input name="Submit" type="submit" style="border: 1px solid silver; background: #F5F5F5; color: #333;" value="Submit">
&nbsp;
<input name="Reset" type="reset" style="border: 1px solid silver; background: #F5F5F5; color: #333;" value="Reset"></td>
  </tr>
</table>
<br>
  </form>
</td>
</tr>
</table>
<!--End form-->
<br><br>
<!--Start bottom-->
<table width="850" border="0" align="center" cellpadding="0" cellspacing="0">
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