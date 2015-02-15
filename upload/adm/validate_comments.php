<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/lang/admin/lang_'.$config['lang'].'.php');
require_once('class.menu.php');

// Connect to database
db_connect($config);

if(isset($_POST))
{
	if(count($_POST) > 1)
	{
		if(isset($_POST['Submit']))
		{
			foreach ($_POST['id'] as $value)
			{
				if ($_POST['action'][$value] == 'delete')
				{
					mysql_query("DELETE FROM `".$config['db']['pre']."comments` WHERE comment_id = '".validate_input($value)."' LIMIT 1;");
				}
				elseif ($_POST['action'][$value] == 'validate')
				{
					mysql_query("UPDATE `".$config['db']['pre']."comments` SET `comment_status` = 1 WHERE comment_id = '".validate_input($value)."' LIMIT 1;");
					mysql_query("UPDATE `".$config['db']['pre']."posts` SET `post_comments` = post_comments+1 WHERE `post_id` = '".validate_input($_POST['post'][$value])."' LIMIT 1;");
				}
			}
		}
	}
}

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config,$lang);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $config['site_title'].' '.$lang['ADMIN'].' - '.$lang['VALIDATE'].' '.$lang['COMMENTS']; ?></title>
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
<td class="heading"><?php echo $lang['VALIDATE'].' '.$lang['COMMENTS']; ?></td>
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
    <td align="center" valign="top" bgcolor="#F6F6F6" style="padding:15px;">

    <form name="validate_comments" method="post" action="">
    
    <table width="750" cellpadding="0" cellspacing="2" border="0">
      <tr>
        <td width="150">&nbsp;</td>
        <td width="600">&nbsp;</td>
      </tr>

    <?php

    $query = "SELECT comment_id, comment_author, comment_date, comment_body, cat_title, pos.post_id FROM `".$config['db']['pre']."comments` com JOIN `".$config['db']['pre']."posts` pos ON com.post_id = pos.post_id JOIN `".$config['db']['pre']."cats` cat ON pos.cat_id = cat.cat_id WHERE com.comment_status = 0 ORDER BY comment_id ASC LIMIT 20;";
    $query_result = mysql_query($query);
    while ($info = mysql_fetch_array($query_result))
	{
    ?>
      <tr>
        <td><strong><?php echo $lang['POSTED'].' '.$lang['BY']; ?>:</strong></td>
        <td><?php echo stripslashes($info['comment_author']); ?></td>
      </tr>
      <tr>
        <td><strong><?php echo $lang['DATE']; ?>:</strong></td>
        <td><?php echo stripslashes(date('d/m/Y',$info['comment_date']));?></td>
      </tr>
      <tr>
        <td><strong><?php echo $lang['CATEGORY']; ?>:</strong></td>
        <td><?php echo stripslashes($info['cat_title']); ?></td>
      </tr>
      <tr>
        <td valign="top"><strong><?php echo $lang['COMMENTS']; ?>:</strong></td>
        <td><div style="height:100px;width:60%;overflow:auto;border:1px solid #344167;padding:2px;"><?php echo stripslashes($info['comment_body']); ?></div></td>
      </tr>
      <tr>
        <td><strong><?php echo $lang['ACTION']; ?>:</strong></td>
        <td><input type="radio" name="action[<?php echo $info['comment_id']; ?>]" value="validate"><?php echo $lang['VALIDATE']; ?> <input type="radio" name="action[<?php echo $info['comment_id']; ?>]" value="delete"><?php echo $lang['DELETE']; ?> <input type="radio" name="action[<?php echo $info['comment_id']; ?>]" value="ignore" checked><?php echo $lang['IGNORE']; ?></td>
      </tr>
      <tr>
        <td colspan="2">
            <input name="id[<?php echo $info['comment_id']; ?>]" type="hidden" class="textbox" value="<?php echo $info['comment_id']; ?>">
            <input name="post[<?php echo $info['comment_id']; ?>]" type="hidden" class="textbox" value="<?php echo $info['post_id']; ?>">
            <hr color="#344167">
        </td>
      </tr>

    <?php } if (mysql_num_rows($query_result)==0){ ?>
        <tr><td colspan="2" align="center"><?php echo $lang['NO'].' '.$lang['COMMENTS'].' '.$lang['TO'].' '.$lang['VALIDATE']; ?>.</td></td>
    <?php } else { ?>
          <tr><td></td><td>
         <input name="Submit" type="submit" style="border: 1px solid silver; background: #F5F5F5; color: #333;" value="<?php echo $lang['SUBMIT']; ?>">
         <input name="Reset" type="reset" style="border: 1px solid silver; background: #F5F5F5; color: #333;" value="<?php echo $lang['RESET']; ?>">
         </tr>
    <?php } ?>

    </table>

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