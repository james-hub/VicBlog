<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/lang/admin/lang_'.$config['lang'].'.php');
require_once('class.menu.php');

db_connect($config);

if (isset($_POST) && isset($_POST['Submit']))
{
	foreach ($_POST['id'] as $value)
	{
       mysql_query("UPDATE `".$config['db']['pre']."content` SET `content_name` = '" . validate_input($_POST['name'][$value]) . "', `content_title` = '" . validate_input($_POST['title'][$value]) . "', `content_body` = '" . validate_input($_POST['body'][$value]) . "' WHERE `content_id` = '" . validate_input($value) . "' LIMIT 1 ;");
       mysql_query("UPDATE `".$config['db']['pre']."links` SET `link_title` = '" . validate_input($_POST['title'][$value]) . "' WHERE `content_id` = '" . validate_input($value) . "' LIMIT 1 ;");
	}

	header('Location: content_view.php');
	exit;
}

if (isset($_GET['id']))
{
	$_POST['list'][$_GET['id']] = $_GET['id'];
}

if (!isset($_POST['list']))
{
	header("Location: content_view.php");
	exit;
}

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config,$lang);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $config['site_title'].' '.$lang['ADMIN'].' - '.$lang['EDITCONTENT']; ?></title>
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
<script language="JavaScript" type="text/javascript" src="editor/wysiwyg_2.js"></script>
</head>
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
<td class="heading"><?php echo $lang['EDITCONTENT']; ?></span></td>
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
<?php
$count = 0;
$sql = "SELECT content_id, content_name, content_title, content_body FROM ".$config['db']['pre']."content ";

foreach ($_POST['list'] as $value){

    if ($count == 0){
        $sql.= "WHERE content_id='" . $value . "'";
    } else {
        $sql.= " OR content_id='" . $value . "'";
    }

    $count++;
}

$sql.= " LIMIT " . count($_POST['list']);

$query_result = mysql_query($sql);
while ($info = @mysql_fetch_array($query_result))
{
?>
<table width="800" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="390" height="25"><span class="style3"><?php echo $lang['CONTENT'].' '.$lang['ID']; ?></span></td>
                      <td width="20" height="25">&nbsp;</td>
                      <td width="390" height="25">&nbsp;</td>
                    </tr>
                    <tr>
                      <td><input name="id[<?php echo $info['content_id']; ?>]" type="Text" class="textbox" style="width:100%" value="<?php echo $info['content_id']; ?>" disabled>
        <input name="id[<?php echo $info['content_id']; ?>]" type="hidden" class="textbox" value="<?php echo $info['content_id']; ?>"></td>
                      <td>&nbsp;</td>
                      <td></td>
                    </tr>
                    <tr>
                      <td width="390" height="25"><span class="style3"><?php echo $lang['CONTENT'].' '.$lang['TITLE']; ?></span></td>
                      <td width="20" height="25">&nbsp;</td>
                      <td width="390" height="25"><span class="style3"><?php echo $lang['CONTENT'].' '.$lang['NAME']; ?></span></td>
                    </tr>
                    <tr>
                      <td><input name="title[<?php echo $info['content_id']; ?>]" type="text" style="width:100%;padding-left:3px;" maxlength="255" class="textbox" value="<?php echo stripslashes($info['content_title']); ?>"></td>
                      <td>&nbsp;</td>
                      <td><input name="name[<?php echo $info['content_id']; ?>]" type="text" style="width:100%;padding-left:3px;" maxlength="255" class="textbox" value="<?php echo stripslashes($info['content_name']); ?>"></td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
              <br>
              <textarea name="body[<?php echo $info['content_id']; ?>]" cols="70" rows="10" id="body[<?php echo $info['content_id']; ?>]" style="width:100%;height:300px;"><?php echo stripslashes($info['content_body']); ?></textarea>
              <script language="javascript1.2">
  generate_wysiwyg('body[<?php echo $info['content_id']; ?>]');
        </script>
          </td>
        </tr>
      </table>
<br><br>
<?php } ?>

<br>
<table width="800"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
      <input name="Submit" type="submit" style="border: 1px solid silver; background: #F5F5F5; color: #333;" value="<?php echo $lang['EDIT'].' '.$lang['CONTENT']; ?>">
&nbsp;
<input name="Reset" type="reset" style="border: 1px solid silver; background: #F5F5F5; color: #333;" value="<?php echo $lang['RESET']; ?>"></td>
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