<?php 
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/lang/admin/lang_'.$config['lang'].'.php');
require_once('class.menu.php');

// Connect to database
db_connect($config);

if(isset($_POST['tpl_name']))
{
	// Content that will be written to the config file
	$content = "<?php\n";
	$content.= "\$config['db']['host'] = '".addslashes($config['db']['host'])."';\n";
	$content.= "\$config['db']['name'] = '".addslashes($config['db']['name'])."';\n";
	$content.= "\$config['db']['user'] = '".addslashes($config['db']['user'])."';\n";
	$content.= "\$config['db']['pass'] = '".addslashes($config['db']['pass'])."';\n";
	$content.= "\$config['db']['pre'] = '".addslashes($config['db']['pre'])."';\n";
	$content.= "\n";
	$content.= "\$config['site_title'] = '".addslashes(stripslashes($config['site_title']))."';\n";
	$content.= "\$config['site_url'] = '".addslashes(stripslashes($config['site_url']))."';\n";
	$content.= "\$config['admin_email'] = '".addslashes(stripslashes($config['admin_email']))."';\n";
	$content.= "\$config['seo_urls'] = '".addslashes(stripslashes($config['seo_urls']))."';\n";
	$content.= "\n";
	$content.= "\$config['cookie_time'] = '".addslashes(stripslashes($config['cookie_time']))."';\n";
	$content.= "\$config['cookie_name'] = '".addslashes(stripslashes($config['cookie_name']))."';\n";
	$content.= "\n";
	$content.= "\$config['tpl_name'] = '".addslashes($_POST['tpl_name'])."';\n";
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
	
	header("Location: template_settings.php");
	exit;
}

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config,$lang);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $config['site_title'].' '.$lang['ADMIN'].' - '.$lang['TEMPLATESET']; ?></title>
<link rel="stylesheet" type="text/css" href="images/style.css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/JSCookMenu.js"></SCRIPT>
<LINK REL="stylesheet" HREF="menu/themes/Office/theme.css" TYPE="text/css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/themes/Office/theme.js"></SCRIPT>
<style type="text/css">
<!--
.style2 {	color: #FFFFFF;
	font-size: 12px;
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.style5 {font-size: 12px; font-family: Arial, Helvetica, sans-serif; }
-->
</style>
<?php
	echo '<SCRIPT LANGUAGE="JavaScript">';
	echo "\n";
	echo '  var img=new Array();';
	echo "\n";
	if ($handle = opendir('../templates/')) 
	{
	   while (false !== ($file = readdir($handle))) 
	   { 
		   if ($file != "." && $file != "..") 
		   { 
				echo 'img["' . $file . '"]="../templates/' . $file . '/images/sshot.PNG";';
				echo "\n";
		   } 
	   }
	   closedir($handle); 
	}
?>
	
	function swap(type){
	document.getElementById("imgMain").src=img[type];
	var sel=document.shoeFrm.shoeSel;
	for(i=0;i<sel.length;i++){if(sel.options[i].text==type){sel.selectedIndex=i;}}
	}
</script>
</head>
<body onLoad="init()">
<!--Start top-->
<table width="850" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top: 20px;">
  <tr>
    <td height="42" valign="middle" style="width: 899px; background: #88A2F0; color: #F1F1F1; font-size: 24px; font-weight: bold; padding: 10px;"><?php echo $config['site_title'] ;?> Admin CP</td>
  </tr>
  <tr>
    <td width="100%"><img src="images/dot.gif" width="1" height="1" alt=""></td>
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
    <td class="heading">Template Settings </td>
  </tr>
  <tr>
    <td bgcolor="#333333"><img src="images/dot.gif" width="1" height="1" alt=""></td>
  </tr>
</table>
<br>
<table width="850" border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #CCCCCC;">
<tr>
<td align="center" bgcolor="#F6F6F6" style="padding:15px;"><div align="right">
  <form action="template_settings.php" method="post" name="f1" id="f1">
          <div align="center"><br>
      <table width="100%"  border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td><br>
              <table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td><img src="../templates/<?php echo $config['tpl_name'];?>/images/sshot.PNG" name="imgMain" width="500" height="250" border="1" id="imgMain"></td>
                </tr>
                <tr>
                  <td height="40"><select name="tpl_name" id="tpl_name" style="width:347px;" onChange="swap(this.options[selectedIndex].text);">
                      <?php
if ($handle = opendir('../templates/')) 
{
   while (false !== ($file = readdir($handle))) {
    
       if ($file != "." && $file != "..") 
	   { 
	   		if($file == $config['tpl_name'])
			{
				echo "<option selected>" . $file . "</option>"; 
			}
			else
			{
				echo "<option>" . $file . "</option>"; 
			}
       } 
   }
   closedir($handle); 
}
?>
                    </select>
                      <input name="Submit2" type="submit" style="width:150px; background: #F5F5F5; color: #333; border: 1px solid silver;" value="Activate"></td>
                </tr>
              </table></td>
          </tr>
        </table>
        <br>
</div>
          <div align="left">      </div>
  </form>
</div></td>
</tr>
</table>
<br>
<br>
<!--End form-->
<table width="850" border="0" align="center" cellpadding="0" cellspacing="0">
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
