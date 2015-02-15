<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/lang/admin/lang_'.$config['lang'].'.php');
require_once('class.menu.php');

db_connect($config);

if(isset($_POST['category']))
{
    $unique = mysql_query("SELECT 1 FROM `".$config['db']['pre']."cats` WHERE cat_title = '".validate_input($_POST['category'])."' LIMIT 1;");
        
    if (!mysql_num_rows($unique))
	{
	    mysql_query("INSERT INTO `".$config['db']['pre']."cats` (`cat_title` , `cat_count`) VALUES ('".validate_input($_POST['category'])."', 0);");
    }

    header('Location: cat_view.php');
	exit;
}

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config,$lang);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $config['site_title'].' '.$lang['ADMIN'].' - '.$lang['ADDCATEGORIES']; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="css/main.css">
<link rel="stylesheet" type="text/css" href="images/style.css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/JSCookMenu.js"></SCRIPT>
<LINK REL="stylesheet" HREF="menu/themes/Office/theme.css" TYPE="text/css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/themes/Office/theme.js"></SCRIPT>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { 
  window.open(theURL,winName,features);
}
//-->
</script>
<script language="JavaScript" type="text/javascript" src="editor/wysiwyg.js"></script>
</head>

<body>
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
    <td valign="middle" class="heading"><?php echo $lang['ADDCATEGORIES']; ?></td>
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
    <td bgcolor="#F6F6F6" style="padding:15px;">
    
    <form name="form2" method="post" action="cat_add.php">
      <table width="800" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
          
          <table width="70%" cellpadding="0" cellspacing="2" border="0">
          <tr>
            <td width="35%">&nbsp;</td>
            <td width="65%">&nbsp;</td>
          </tr>
          <tr>
            <td><strong><?php echo $lang['CATEGORY'].' '.$lang['NAME']; ?> (<a href="#" onClick="MM_openBrWindow('help.php?id=1','help','width=500,height=200')">?</a>)</strong></td>
            <td>:
                <input name="category" type="Text" class="textbox" id="category" style="width:60%" value=""></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input name="Submit" type="submit" style="border: 1px solid silver; background: #F5F5F5; color: #333;" value="<?php echo $lang['ADD'].' '.$lang['CATEGORY']; ?>"></td>
          </tr>
          </table>
                    </td>
        </tr>
      </table>
    </form>
    
    </td>
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
    <td style="padding:15px;" align="center"><span class="copyright">Copyright &copy; 2009-2010 <a href="http://vicblog.vichost.com" class="copyright" target="_blank">VicBlog Development</a> All Rights Reserved.</span></td>
  </tr>
</table>
</body>
</html>