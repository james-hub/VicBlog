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
                
                if (strlen($_POST['password'][$value])>3){
                    $password = "`password` = '".validate_input(md5($_POST['password'][$value]))."', ";
                }

				mysql_query("UPDATE `".$config['db']['pre']."users` SET ".$password." `usergroup` = '".validate_input($_POST['user_type'][$value])."', `status` = '".validate_input($_POST['user_status'][$value])."' WHERE `user_id` = '".validate_input($value)."' LIMIT 1;");
			}

			header("Location: users_view.php");
			exit;

		}
	}
}


if(isset($_GET['id']))
{
	$_POST['list'][$_GET['id']] = $_GET['id'];
}

if (!isset($_POST['list'])){
   header("Location: users_view.php");
   exit;
}

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config,$lang);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title><?php echo $config['site_title'].' '.$lang['ADMIN'].' - '.$lang['EDIT'].' '.$lang['USERS']; ?></title>
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
<script language="JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
<script language="JavaScript" type="text/javascript" src="editor/wysiwyg.js"></script>
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
<td class="heading"><?php echo $lang['EDIT'].' '.$lang['USERS']; ?></span></td>
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

$user_types[2] = $lang['USER'];
$user_types[3] = $lang['ADMINISTRATOR'];
$user_types[4] = $lang['MODERATOR'];
$user_status[0] = $lang['DISABLED'];
$user_status[1] = $lang['ACTIVE'];

$sql = "SELECT user_id, username, usergroup, status FROM ".$config['db']['pre']."users ";

foreach ($_POST['list'] as $value){

	if ($count == 0){
		$sql.= "WHERE user_id='" . $value . "'";
	} else {
		$sql.= " OR user_id='" . $value . "'";
	}
	
	$count++;
}

$sql.= " LIMIT " . count($_POST['list']);

$query_result = mysql_query($sql);
while ($info = mysql_fetch_array($query_result)){
?>

<table width="70%" cellpadding="0" cellspacing="2" border="0">
          <tr>
            <td width="35%">&nbsp;</td>
            <td width="65%">&nbsp;</td>
          </tr>
          <tr>
            <td><strong><?php echo $lang['USERNAME']; ?> (<a href="#" onClick="MM_openBrWindow('help.php?id=6','help','width=500,height=200')">?</a>)</strong></td>
            <td>:<input name="username[<?php echo $info['user_id']; ?>]" type="Text" class="textbox" id="username" disabled style="width:60%" value="<?php echo $info['username']; ?>"></td>
          </tr>
          <tr>
            <td><strong><?php echo $lang['PASSWORD']; ?> (<a href="#" onClick="MM_openBrWindow('help.php?id=7','help','width=500,height=200')">?</a>)</strong></td>
            <td>:<input name="password[<?php echo $info['user_id']; ?>]" type="password" class="textbox" id="password" style="width:60%" value=""></td>
          </tr>
          <tr>
            <td><strong><?php echo $lang['USER'].' '.$lang['TYPE']; ?> (<a href="#" onClick="MM_openBrWindow('help.php?id=9','help','width=500,height=200')">?</a>)</strong></td>
            <td>:
            <select name="user_type[<?php echo $info['user_id']; ?>]">
            <?php
            for ($x=2;$x<5;$x++){
                $selected = "";
              
                if ($info['usergroup'] == $x){ $selected = " selected"; }

                echo "<option value='".$x."'$selected>".$user_types[$x]."</option>";
            }
            ?>
            </select>
            </td>
          </tr>
          <tr>
            <td><strong><?php echo $lang['USER'].' '.$lang['STATUS']; ?> (<a href="#" onClick="MM_openBrWindow('help.php?id=10','help','width=500,height=200')">?</a>)</strong></td>
            <td>:
            <select name="user_status[<?php echo $info['user_id']; ?>]">
            <?php
            for ($x=0;$x<2;$x++){
                $selected = "";
              
                if ($info['status'] == $x){ $selected = " selected"; }

                echo "<option value='".$x."'$selected>".$user_status[$x]."</option>";
            }
            ?>
            </select>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
        <input name="id[<?php echo $info['user_id']; ?>]" type="hidden" class="textbox" value="<?php echo $info['user_id']; ?>">
<br><br>

<?php 
} 
?>
<br>
<table width="800"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
      <input name="Submit" type="submit" style="border: 1px solid silver; background: #F5F5F5; color: #333;" value="<?php echo $lang['SUBMIT']; ?>">
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