<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/lang/admin/lang_'.$config['lang'].'.php');
require_once('class.menu.php');

db_connect($config);

if(isset($_POST))
{
	if(count($_POST) > 1)
	{
		if(isset($_POST['Submit']))
		{
			foreach ($_POST['id'] as $value)
			{
				mysql_query("UPDATE `".$config['db']['pre']."cats` SET `cat_title` = '" . validate_input($_POST['title'][$value]) . "' WHERE `cat_id` = '" . validate_input($value) . "' LIMIT 1 ;");
			}
			 
			header('Location: cat_view.php');
			exit;
		}
	}
}

if(isset($_GET['id']))
{
	$_POST['list'][$_GET['id']] = $_GET['id'];
}

if (!isset($_POST['list']))
{
	header("Location: cat_view.php");
	exit;
}

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config,$lang);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><? echo $config['site_title'].' '.$lang['ADMIN'].' - '.$lang['EDITCATEGORIES']; ?></title>
<link rel="stylesheet" type="text/css" href="images/style.css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/JSCookMenu.js"></SCRIPT>
<LINK REL="stylesheet" HREF="menu/themes/Office/theme.css" TYPE="text/css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/themes/Office/theme.js"></SCRIPT>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
body,td,th {
	font-size: 12px;
}
.style3 {
	color: #344167;
	font-weight: bold;
}
-->
</style>
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
<script language="JavaScript" type="text/javascript" src="editor/wysiwyg.js"></script>
</head>
<!--Start top-->
<table width="850" cellpadding="0" cellspacing="0" border="0" align="center">
  <tr>
    <td width="100%" height="42" valign="top" background="images/bg_top.gif"><a href="index.php"><img src="images/logo.gif" width="93" height="17" hspace="24" vspace="11" border="0"></a></td>
  </tr>
  <tr>
    <td><img src="images/dot.gif" width="1" height="1" alt=""></td>
  </tr>
</table>
<!--End top-->
<!--Start topmenu-->
<table width="850" cellpadding="0" cellspacing="0" border="0" align="center">
  <tr>
    <td bgcolor="#F0F0F0" height="25" style="padding-left:20px;" id="menu"></td>
    <SCRIPT language="JavaScript" type="text/javascript">
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
<!--Start heading page-->
<table width="850" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
<td class="heading"><?php echo $lang['EDITCATEGORIES']; ?></span></td>
</tr>
<tr>
<td bgcolor="#333333"><img src="images/dot.gif" width="1" height="1" alt=""></td>
</tr>
</table>
<!--End heading page-->

<!--Start form-->
<br>
<table width="850" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #CCCCCC;" align="center">
<tr>
<td align="center" bgcolor="#F6F6F6" style="padding:15px;">
  <form action="" method="post" name="f1" id="f1">

<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    
    <table width="800"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="80" height="25"><?php echo $lang['CATEGORY'].' '.$lang['ID']; ?></td>
        <td width="20" height="25">&nbsp;</td>
        <td width="700" height="25"><?php echo $lang['CATEGORY'].' '.$lang['NAME']; ?></td>
      </tr>

<?php
$count = 0;
$sql = "SELECT cat_id, cat_title FROM ".$config['db']['pre']."cats ";

foreach ($_POST['list'] as $value)
{
    if ($count == 0)
	{
        $sql.= "WHERE cat_id='" . validate_input($value) . "'";
    }
	else
	{
        $sql.= " OR cat_id='" . validate_input($value) . "'";
    }

    $count++;
}

$sql.= " LIMIT " . count($_POST['list']);

$query_result = mysql_query($sql);
while ($info = @mysql_fetch_array($query_result))
{
?>
      <tr>
        <td><input name="rowid" type="text" disabled value="<?php echo $info['cat_id'];?>" size="5">
        <input name="id[<?php echo $info['cat_id']; ?>]" type="hidden" class="textbox" value="<?php echo $info['cat_id']; ?>"></td>
        <td>&nbsp;</td>
        <td><input name="title[<?php echo $info['cat_id']; ?>]" type="text" style="padding-left:3px;" size="40" maxlength="100" class="textbox" value="<?php echo stripslashes($info['cat_title']); ?>"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
<?php
}
?>
     </table>
    </td>
  </tr>
</table>

<br>
<table width="800"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
      <input name="Submit" type="submit" class="button" value="<?php echo $lang['SUBMIT']; ?>">
&nbsp;
<input name="Reset" type="reset" class="button" value="<?php echo $lang['RESET']; ?>"></td>
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
<table width="850" cellpadding="0" cellspacing="0" border="0" align="center">
  <tr>
    <td bgcolor="#333333"><img src="images/dot.gif" width="1" height="1" alt=""></td>
  </tr>
  <tr>
    <td style="padding:15px;" align="center"><span class="copyright">Copyright &copy; 2009-2010 <a href="http://vicblog.vichost.com" class="copyright" target="_blank">VicBlog Development</a> All Rights Reserved.</span></td>
  </tr>
</table>
<!--End bottom-->
</body>
</html>