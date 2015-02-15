<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/lang/admin/lang_'.$config['lang'].'.php');
require_once('class.menu.php');

// Connect to database
db_connect($config);

if (isset($_POST['Submit']))
{
    $count = 0;
    $select_statement = '';

    foreach ($_POST['list'] as $value)
	{
        if ($count == 0)
		{
            $select_statement = "WHERE `post_id` = '".validate_input($value)."'";
        } 
		else 
		{
            $select_statement.= " OR `post_id` = '".validate_input($value)."'";
        }
	    $count++;
    }

    $query = "SELECT cat_count, cat.cat_id FROM `".$config['db']['pre']."cats` cat JOIN `".$config['db']['pre']."posts` pos ON cat.cat_id = pos.cat_id  WHERE `post_id` = '".validate_input($_POST["list"][0])."' LIMIT 1;";

    $query_result = mysql_query($query);

    while ($info = mysql_fetch_array($query_result))
	{
		// Update Category Count
		mysql_query("UPDATE `".$config['db']['pre']."cats` SET `cat_count` = '".validate_input(($info['cat_count']-$count))."' WHERE `cat_id` = '".validate_input($info['cat_id'])."' LIMIT 1;");
    }

    // Delete Posts
    mysql_query("DELETE FROM `".$config['db']['pre']."posts` ".$select_statement." LIMIT ".count($_POST['list']));
    // Delete Comments for Post Deleted
    mysql_query("DELETE FROM `".$config['db']['pre']."comments` ".$select_statement." LIMIT ".count($_POST['list']));

    header('Location: post_edit.php');
	exit;

} 
elseif (!isset($_POST['list']) && !isset($_GET['id']))
{
  header('Location: post_edit.php');
  exit;
}

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config,$lang);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $config['site_title'].' '.$lang['ADMIN'].' - '.$lang['DELETE'].' '.$lang['POSTS']; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="images/style.css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/JSCookMenu.js"></SCRIPT>
<LINK REL="stylesheet" HREF="menu/themes/Office/theme.css" TYPE="text/css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/themes/Office/theme.js"></SCRIPT>
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
<td class="heading"><?php echo $lang['DELETE'].' '.$lang['POSTS']; ?></td>
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
    <td align="center" bgcolor="#F6F6F6" style="padding:15px;"><div align="right">
        <form action="" method="post" name="f1" id="f1">
          <div align="center" class="style6">
            <table  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><span class="style6"><span class="style7"><?php echo $lang['DELETECONFIRM'].' '.$lang['POSTS']; ?>?</span><br>
                  </span><br>
                  <ul>
                    <?php 
$count = 0;
$sql = "SELECT post_id, post_title FROM ".$config['db']['pre']."posts ";

if (isset($_GET['id']))
{
	$sql.= "WHERE post_id='".validate_input($_GET['id'])."'";
	$count=1;
} 
else 
{
	foreach ($_POST['list'] as $value)
	{
		if ($count == 0)
		{
			$sql.= "WHERE post_id='".validate_input($value)."'";
		}
		else
		{
			$sql.= " OR post_id='".validate_input($value)."'";
		}
		$count++;
	}
	$sql.= " LIMIT " . count($_POST['list']);
}

$query_result = mysql_query($sql);
while ($info = @mysql_fetch_array($query_result))
{
	echo "<li>".substr($info['post_title'],0,30)."</li>";
	echo "<input type=\"hidden\" name=\"list[]\" id=\"list[]\" value=\"".$info['post_id']."\">";
}
?>
                  </ul>
                  <br>
                  <br>
                  <div align="center">
                    <input name="Submit" type="submit" style="border: 1px solid silver; background: #F5F5F5; color: #333;" id="Submit" value="<?php echo $lang['IMSURE']; ?>">
                </div></td>
              </tr>
            </table>
          </div>
        </form>
    </div></td>
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