<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/lang/admin/lang_'.$config['lang'].'.php');
require_once('class.menu.php');

db_connect($config);

if(isset($_POST['body']))
{
	mysql_query("INSERT INTO `".$config['db']['pre']."content` (`content_name` , `content_title` , `content_body`) VALUES ('".validate_input($_POST['name'])."', '".validate_input($_POST['title'])."', '".validate_input($_POST['body'])."');");

	$new_id = mysql_insert_id();
	$result = mysql_query("SELECT MAX(link_order) FROM `".$config['db']['pre']."links`");
	$row = mysql_fetch_row($result);
	$link_order = $row[0] + 10;

	if ($new_id && $link_order)
	{
		mysql_query("INSERT INTO `".$config['db']['pre']."links` (`link_type` , `link_order` , `content_id`, `link_title`) VALUES (1 , '".validate_input($link_order)."', '".validate_input($new_id)."', '".validate_input($_POST['title'])."');");
	}

	header('Location: content_view.php');
	exit;
}

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config,$lang);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $config['site_title'].' '.$lang['ADMIN'].' - '.$lang['ADD'].' '.$lang['CONTENT']; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="css/main.css">
<link rel="stylesheet" type="text/css" href="images/style.css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/JSCookMenu.js"></SCRIPT>
<LINK REL="stylesheet" HREF="menu/themes/Office/theme.css" TYPE="text/css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/themes/Office/theme.js"></SCRIPT>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript">
<!--
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
//-->
</script>
<script language="JavaScript" type="text/javascript" src="editor/wysiwyg_2.js"></script>
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
    <td valign="middle" class="heading"><?php echo $lang['ADD'].' '.$lang['CONTENT']; ?></td>
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
    <td bgcolor="#F6F6F6" style="padding:15px;"><form name="form2" method="post" action="content_add.php">
      <table width="800" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="390" height="25"><span class="style3"><?php echo $lang['CONTENT'].' '.$lang['TITLE']; ?></span></td>
                      <td width="20" height="25">&nbsp;</td>
                      <td width="390" height="25"><span class="style3"><?php echo $lang['CONTENT'].' '.$lang['NAME']; ?></span></td>
                    </tr>
                    <tr>
                      <td><input name="title" type="text" id="title" style="width:100%;padding-left:3px;" maxlength="255" class="textbox"></td>
                      <td>&nbsp;</td>
                      <td><input name="name" type="text" id="name" style="width:100%;padding-left:3px;" maxlength="255" class="textbox"></td>
                    </tr>
                  </table>
                  </td>
              </tr>
            </table>
              <br>
              <textarea name="body" cols="70" rows="10" id="body" style="width:100%;height:300px;"></textarea>
              <script language="javascript1.2">
  generate_wysiwyg('body');
        </script>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><input name="Submit" type="submit" style="border: 1px solid silver; background: #F5F5F5; color: #333;" value="<?php echo $lang['ADD'].' '.$lang['CONTENT']; ?>"></td>
        </tr>
      </table>
    </form></td>
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