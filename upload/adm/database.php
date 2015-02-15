<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/lang/admin/lang_'.$config['lang'].'.php');
require_once('class.menu.php');

// Connect to database
db_connect($config);

if(isset($_POST['DBHost']))
{
	// Content that will be written to the config file
	$content = "<?php\n";
	$content.= "\$config['db']['host'] = '".addslashes($_POST['DBHost'])."';\n";
	$content.= "\$config['db']['name'] = '".addslashes($_POST['DBName'])."';\n";
	$content.= "\$config['db']['user'] = '".addslashes($_POST['DBUser'])."';\n";
	$content.= "\$config['db']['pass'] = '".addslashes($_POST['DBPass'])."';\n";
	$content.= "\$config['db']['pre'] = '".addslashes($_POST['DBPre'])."';\n";
	$content.= "\n";
	$content.= "\$config['site_title'] = '".addslashes(stripslashes($config['site_title']))."';\n";
	$content.= "\$config['site_url'] = '".addslashes(stripslashes($config['site_url']))."';\n";
	$content.= "\$config['admin_email'] = '".addslashes(stripslashes($config['admin_email']))."';\n";
	$content.= "\$config['seo_urls'] = '".addslashes(stripslashes($config['seo_urls']))."';\n";
	$content.= "\n";
	$content.= "\$config['cookie_time'] = '".addslashes(stripslashes($config['cookie_time']))."';\n";
	$content.= "\$config['cookie_name'] = '".addslashes(stripslashes($config['cookie_name']))."';\n";
	$content.= "\n";
	$content.= "\$config['tpl_name'] = '".addslashes(stripslashes($config['tpl_name']))."';\n";
	$content.= "\$config['version'] = '".addslashes(stripslashes($config['version']))."';\n";
	$content.= "\$config['lang'] = '".addslashes(stripslashes($config['lang']))."';\n";
	$content.= "\$config['installed'] = '1';\n";
	$content.= "?>";

	// Open the includes/config.php for writting
	$handle = fopen('../includes/config.php', 'w');
	// Write the config file
	fwrite($handle, $content);
	// Close the file
	fclose($handle);
	
	header('Location: database.php');
	exit;
}

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config,$lang);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $config['site_title'].' '.$lang['ADMIN'].' - '.$lang['DATABASE']; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="css/main.css">
<link rel="stylesheet" type="text/css" href="images/style.css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/JSCookMenu.js"></SCRIPT>
<LINK REL="stylesheet" HREF="menu/themes/Office/theme.css" TYPE="text/css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/themes/Office/theme.js"></SCRIPT>
<script language="JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
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
<!--Start heading page-->
<table width="850" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
<td class="heading"><?php echo $lang['DATABASE']; ?></td>
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
    <td align="center" valign="top" bgcolor="#F6F6F6" style="padding:15px;"><form name="form1" method="post" action="">
        <table width="70%" cellpadding="0" cellspacing="2" border="0">
          <tr>
            <td width="35%">&nbsp;</td>
            <td width="65%">&nbsp;</td>
          </tr>
          <tr>
            <td><strong><?php echo $lang['DATABASE'].' '.$lang['HOST']; ?> (<a href="#" onClick="MM_openBrWindow('help.php?id=1','help','width=500,height=200')">?</a>)</strong></td>
            <td>:
                <input name="DBHost" type="Text" class="textbox" id="DBHost" style="width:60%" value="<?php echo stripslashes($config['db']['host']); ?>"></td>
          </tr>
          <tr>
            <td><strong><?php echo $lang['DATABASE'].' '.$lang['NAME']; ?> (<a href="#" onClick="MM_openBrWindow('help.php?id=2','help','width=500,height=200')">?</a>)</strong></td>
            <td>:
                <input name="DBName" type="Text" class="textbox" id="DBName" style="width:60%" value="<?php echo stripslashes($config['db']['name']); ?>"></td>
          </tr>
          <tr>
            <td><strong><?php echo $lang['DATABASE'].' '.$lang['USERNAME']; ?> (<a href="#" onClick="MM_openBrWindow('help.php?id=3','help','width=500,height=200')">?</a>)</strong></td>
            <td>:
                <input name="DBUser" type="Text" class="textbox" id="DBUser" style="width:60%" value="<?php echo stripslashes($config['db']['user']); ?>"></td>
          </tr>
          <tr>
            <td><strong><?php echo $lang['DATABASE'].' '.$lang['PASSWORD']; ?> (<a href="#" onClick="MM_openBrWindow('help.php?id=4','help','width=500,height=200')">?</a>)</strong></td>
            <td>:
                <input name="DBPass" type="password" class="textbox" id="DBPass" style="width:60%" value="<?php echo stripslashes($config['db']['pass']); ?>"></td>
          </tr>
          <tr>
            <td><strong><?php echo $lang['DATABASE'].' '.$lang['PREFIX']; ?> (<a href="#" onClick="MM_openBrWindow('help.php?id=5','help','width=500,height=200')">?</a>)</strong></td>
            <td>:
                <input name="DBPre" type="text" class="textbox" id="DBPre" style="width:60%" value="<?php echo stripslashes($config['db']['pre']); ?>"></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td></td>
            <td height="30" style="padding-left:6px;"><input name="Submit" type="submit" style="border: 1px solid silver; background: #F5F5F5; color: #333;" value="<?php echo $lang['SUBMIT']; ?>">
&nbsp;
            <input name="Reset" type="reset" style="border: 1px solid silver; background: #F5F5F5; color: #333;" value="<?php echo $lang['RESET']; ?>">
            </td>
          </tr>
        </table>
    </form></td>
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