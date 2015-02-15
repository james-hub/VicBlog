<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/lang/admin/lang_'.$config['lang'].'.php');
require_once('class.menu.php');

// Connect to database
db_connect($config);

$username_error = null;
$password_error = null;
$username = null;
$password = null;
$password2 = null;
$user_type = null;
$errors = 0;

if (isset($_POST['Submit'])){

   $username = $_POST['username'];
   $password = $_POST['password'];
   $password2 = $_POST['password2'];
   
   // Check Username syntax
   if (ereg('[^a-z0-9]',$username)){
       $errors++;
       $username_error = ' '.$lang['USERALPHA'];

   } elseif (strlen($username) < 4 || strlen($username) > 15){
       $errors++;
       $username_error = ' '.$lang['USERLENGTH'];

   } elseif ($username == "Admin" || $username == "Administrator" || $username == "Support" || $username == "Webmaster"){
      $errors++;
      $username_error = ' '.$lang['USERTAKEN'];
   }
   
   // Check Password syntax
   if (ereg('[^a-z0-9]',$password) || ereg('[^a-z0-9]',$password2)){
       $errors++;
       $password = null;
       $password2 = null;
       $password_error = ' '.$lang['USERALPHA'];

   }  elseif (strlen($password) < 4 || strlen($password) > 15){
       $errors++;
       $password = null;
       $password2 = null;
       $password_error = ' '.$lang['USERLENGTH'];

   } elseif ($password != $password2){
       $errors++;
       $password = null;
       $password2 = null;
       $password_error = ' '.$lang['PASSCONFIRM'];
   }

   if (!$errors){

       // Check Username is available
       $availcheck = mysql_num_rows(mysql_query("SELECT 1 FROM `".$config['db']['pre']."users` WHERE username='".validate_input($username)."' LIMIT 1;"));
   
       if (!$availcheck){
           
           $adduser = mysql_query("INSERT INTO `".$config['db']['pre']."users` ( `user_id` , `username` , `password` , `usergroup`, `status` ) VALUES ('', '".validate_input(strtolower($username))."', '".validate_input(md5($password))."', '".validate_input($_POST['user_type'])."', 1);");
            
           if ($adduser){
               header('Location: users_view.php');
               exit;
           }

       } else {
           $username_error = ' '.$lang['USERTAKEN'];
       }

   }

   $user_type = $_POST['user_type'];

}

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config,$lang);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $config['site_title'].' '.$lang['ADMIN'].' - '.$lang['ADD'].' '.$lang['USERS']; ?></title>
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
<td class="heading"><?php echo $lang['ADD'].' '.$lang['USERS']; ?></td>
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
        <form name="form1" method="post" action="">
        <table width="70%" cellpadding="0" cellspacing="2" border="0">
          <tr>
            <td width="35%">&nbsp;</td>
            <td width="65%">&nbsp;</td>
          </tr>
          <tr>
            <td><strong><?php echo $lang['USERNAME']; ?> (<a href="#" onClick="MM_openBrWindow('help.php?id=6','help','width=500,height=200')">?</a>)</strong></td>
            <td>:<input name="username" type="Text" class="textbox" id="username" style="width:60%" value="<?php echo $username; ?>"><span class="error"><?php echo $username_error; ?></span></td>
          </tr>
          <tr>
            <td><strong><?php echo $lang['PASSWORD']; ?> (<a href="#" onClick="MM_openBrWindow('help.php?id=7','help','width=500,height=200')">?</a>)</strong></td>
            <td>:<input name="password" type="password" class="textbox" id="password" style="width:60%" value="<?php echo $password; ?>"><span class="error"><?php echo $password_error; ?></span></td>
          </tr>
          <tr>
            <td><strong><?php echo $lang['CONFIRM'].' '.$lang['PASSWORD']; ?> (<a href="#" onClick="MM_openBrWindow('help.php?id=8','help','width=500,height=200')">?</a>)</strong></td>
            <td>:<input name="password2" type="password" class="textbox" id="password2" style="width:60%" value="<?php echo $password2; ?>"></td>
          </tr>
          <tr>
            <td><strong><?php echo $lang['USER'].' '.$lang['TYPE']; ?> (<a href="#" onClick="MM_openBrWindow('help.php?id=9','help','width=500,height=200')">?</a>)</strong></td>
            <td>:
            <select name="user_type">
            <?php
            $user_types[2] = $lang['USER'];
            $user_types[3] = $lang['ADMINISTRATOR'];
            $user_types[4] = $lang['MODERATOR'];

            for ($x=2;$x<5;$x++){
                $selected = "";
              
                if ($user_type == $x){ $selected = " selected"; }

                echo "<option value='".$x."'$selected>".$user_types[$x]."</option>";
            }
            ?>
            </select>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td></td>
            <td height="30" style="padding-left:6px;">
            <input name="Submit" type="submit" style="border: 1px solid silver; background: #F5F5F5; color: #333;" value="<?php echo $lang['SUBMIT']; ?>">
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