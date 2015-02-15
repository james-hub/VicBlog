<?php
require_once('../includes/config.php');

$install_version = '0.0.1-Beta';

if(isset($_GET['lang']))
{
	$_POST['lang'] = $_GET['lang'];
}

if(isset($_POST['lang']))
{
	require_once('lang/lang_'.$_POST['lang'].'.php');
}

// Check to see if the script is already installed
if(isset($config['installed']))
{
	if($config['version'] == $install_version)
	{
		// Exit the script
		exit('VicBlog is already installed.');
	}
	else
	{
		header('Location: upgrade_'.$config['version'].'.php');
		exit;
	}
}

if( ini_get('safe_mode') )
{
	$safemode = 1;
}
else
{
	$safemode = 0;
}

$error = '';

// Check that their config file is writtable
if(is_writable('../includes/config.php'))
{
	// Check that their thumbs folder is writtable
	if(is_writable('../images/'))
	{
		// Check that their thumbs folder is writtable
		if(is_writable('../cache/'))
		{
			if(!isset($_POST['lang']))
			{
				$step = 2;
			}
			else
			{
				if(!isset($_POST['DBHost']))
				{
					$step = 3;
				}
				else
				{
					// Test the connection
					if(@mysql_connect($_POST['DBHost'], $_POST['DBUser'], $_POST['DBPass']))
					{
						if(@mysql_select_db($_POST['DBName']))
						{
							if(isset($_POST['adminuser']))
							{
								if(trim($_POST['adminuser']) == '')
								{
									$step = 4;
								}
								else
								{
									$site_path = str_replace('\\','/',ereg_replace('install', '', dirname(__FILE__)));
									$site_url = "http://" . $_SERVER['HTTP_HOST'] . ereg_replace ("index.php", "", ereg_replace ("install/", "", $_SERVER['PHP_SELF']));

									// Content that will be written to the config file
									$content = "<?php\n";
									$content.= "\$config['db']['host'] = '".addslashes($_POST['DBHost'])."';\n";
									$content.= "\$config['db']['name'] = '".addslashes($_POST['DBName'])."';\n";
									$content.= "\$config['db']['user'] = '".addslashes($_POST['DBUser'])."';\n";
									$content.= "\$config['db']['pass'] = '".addslashes($_POST['DBPass'])."';\n";
									$content.= "\$config['db']['pre'] = '".addslashes($_POST['DBPre'])."';\n";
									$content.= "\n";
									$content.= "\$config['site_title'] = 'VicBlog';\n";
									$content.= "\$config['site_url'] = '".addslashes($site_url)."';\n";
									$content.= "\$config['admin_email'] = '".addslashes(stripslashes($config['admin_email']))."';\n";
									$content.= "\$config['seo_urls'] = '".addslashes(stripslashes($config['seo_urls']))."';\n";
									$content.= "\n";
									$content.= "\$config['cookie_time'] = '".addslashes(stripslashes($config['cookie_time']))."';\n";
									$content.= "\$config['cookie_name'] = '".addslashes(stripslashes($config['cookie_name']))."';\n";
									$content.= "\n";
									$content.= "\$config['tpl_name'] = '".$config['tpl_name']."';\n";
									$content.= "\$config['version'] = '".$config['version']."';\n";
									$content.= "\$config['lang'] = '".addslashes(stripslashes($config['lang']))."';\n";
									$content.= "\$config['installed'] = '1';\n";
									$content.= "?>";

									// Open the includes/config.php for writting
									$handle = fopen('../includes/config.php', 'w');
									// Write the config file
									fwrite($handle, $content);
									// Close the file
									fclose($handle);

									// Create admin menu
									mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."amenu` (  `menu_id` smallint(3) unsigned NOT NULL auto_increment,  `sort_id` smallint(3) unsigned NOT NULL default '0',  `parent_id` smallint(3) unsigned NOT NULL default '0',  `menu_title` varchar(40) NOT NULL default '',  `menu_icon` varchar(100) NOT NULL default '',  `menu_url` varchar(255) NOT NULL default '',  `menu_target` varchar(10) NOT NULL default '',  `menu_desc` varchar(100) NOT NULL default '',  PRIMARY KEY  (`menu_id`));");
									// Insert admin menu
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (92, 10, 89, 'DATABASE', '', 'database.php', '', '');");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (91, 10, 88, 'EDITUSERS', '', 'users_view.php', '', '');");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (90, 20, 87, 'EDITPOST', '', 'post_view.php', '', '');");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (89, 20, 0, 'SETTINGS', '', 'database.php', '', '');");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (88, 30, 0, 'USERS', '', 'users_view.php', '', '');");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (87, 10, 0, 'POSTS', '', 'post_add.php', '', '');");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (86, 10, 87, 'ADDPOST', '', 'post_add.php', '', '');");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (93, 5, 88, 'ADDUSERS', '', 'users_add.php', '', '');");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (94, 50, 0, 'COMMENTS', '', 'comment_validate.php', '', '');");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (95, 60, 0, 'CONTENT', '', 'content_add.php', '', '');");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (96, 10, 95, 'ADDCONTENT', '', 'content_add.php', '', '');");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (97, 20, 95, 'EDITCONTENT', '', 'content_view.php', '', '');");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (98, 10, 94, 'VALCOMMENTS', '', 'comment_validate.php', '', '');");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (103, 20, 94, 'EDITCOMMENTS', '', 'comment_edit.php', '', '');");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (99, 30, 87, 'CATEGORIES', '', 'cat_view.php', '', '');");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (100, 10, 99, 'ADDCATEGORIES', '', 'cat_add.php', '', '');");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (101, 20, 99, 'EDITCATEGORIES', '', 'cat_edit.php', '', '');");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (102, 30, 89, 'TEMPLATESET', '', 'template_settings.php', '', '');");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."amenu` VALUES (104, 60, 0, 'LOGOUT', '', 'logout.php', '', '');");
									mysql_query("INSERT INTO '".addslashes($_POST['DBPre'])."amenu' VALUES (105, 60, 0, 'INDEX' , '', 'index.php', '', '');");
									// Create post archive table
									mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."archive` (  `archive_month` smallint(2) unsigned NOT NULL default '0',  `archive_year` mediumint(4) unsigned NOT NULL default '0',  PRIMARY KEY  (`archive_month`,`archive_year`));");
									// Insert archive for test post
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."archive` VALUES (".date("n").", ".date("Y").");");
									// Create blocks table
									mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."blocks` (  `block_id` int(11) unsigned NOT NULL default '0',  `block_page` varchar(50) NOT NULL default '',  `block_type` mediumint(4) unsigned NOT NULL default '0',  `block_value` varchar(200) NOT NULL default '',  PRIMARY KEY  (`block_id`),  KEY `block_page` (`block_page`))");
									// Create category table
									mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."cats` (  `cat_id` mediumint(8) unsigned NOT NULL auto_increment,  `cat_title` varchar(200) NOT NULL default '',  `cat_count` int(11) unsigned NOT NULL default '0',  PRIMARY KEY  (`cat_id`));");
									// Insert uncategorized record
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."cats` VALUES (1, 'Uncategorized', 1);");
									// Create Comments Table
									mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."comments` (  `comment_id` int(11) unsigned NOT NULL auto_increment,  `post_id` int(11) unsigned NOT NULL default '0',  `user_id` int(11) unsigned NOT NULL default '0',  `comment_date` int(11) unsigned NOT NULL default '0',  `comment_dategmt` int(11) unsigned NOT NULL default '0',  `comment_author` varchar(40) NOT NULL default '',  `comment_email` varchar(100) NOT NULL default '',  `comment_website` varchar(180) NOT NULL default '',  `comment_body` mediumtext NOT NULL, `comment_status` bool NOT NULL default '0', `comment_ip` varchar(50) NOT NULL default '', PRIMARY KEY  (`comment_id`),  KEY `post_id` (`post_id`));");
									// Create Content table
									mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."content` (  `content_id` int(11) unsigned NOT NULL auto_increment,  `content_type` smallint(2) unsigned NOT NULL default '0',  `content_name` varchar(20) NOT NULL default '',  `content_title` varchar(200) NOT NULL default '',  `content_body` mediumtext NOT NULL,  PRIMARY KEY  (`content_id`));");
									// Insert sample pages
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."content` VALUES (1, 0, 'about', 'About', 'This is a default page generated by the Installation. You can delete or modify this page via the admin cp.');");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."content` VALUES (2, 0, 'contact', 'Contact', 'This is a default page generated by the Installation. You can delete or modify this page via the admin cp.');");
									// Create drafts table
									mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."drafts` (  `draft_id` mediumint(8) unsigned NOT NULL auto_increment,  `post_title` varchar(255) NOT NULL default '',  `cat_id` mediumint(8) unsigned NOT NULL default '0',  `post_body` longtext NOT NULL,  `updated_at` int(11) unsigned NOT NULL default '0',  KEY `id` (`draft_id`))");
									// Create links table
									mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."links` (  `link_id` int(11) unsigned NOT NULL auto_increment,  `link_type` smallint(2) unsigned NOT NULL default '0',  `link_order` mediumint(5) unsigned NOT NULL default '0',  `content_id` int(11) unsigned NOT NULL default '0',  `link_url` varchar(200) NOT NULL default '',  `link_title` varchar(50) NOT NULL default '',  PRIMARY KEY  (`link_id`));");
									// Insert Sample Links
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."links` VALUES (1, 1, 10, 1, '', 'About');");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."links` VALUES (2, 1, 20, 2, '', 'Contact');");
									// Create Posts table
									mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."posts` (  `post_id` int(11) unsigned NOT NULL auto_increment,  `user_id` int(11) unsigned NOT NULL default '0',  `cat_id` mediumint(8) unsigned NOT NULL default '0',  `post_title` varchar(255) NOT NULL default '',  `post_url` varchar(100) NOT NULL default '',  `post_body` longtext NOT NULL,  `post_comments` mediumint(8) unsigned NOT NULL default '0',  `post_date` int(11) unsigned NOT NULL default '0',  `post_dategmt` int(11) unsigned NOT NULL default '0',  `post_month` smallint(2) unsigned NOT NULL default '0',  `post_year` mediumint(4) unsigned NOT NULL default '0',  `post_allowcom` tinyint(1) unsigned NOT NULL default '1',  PRIMARY KEY  (`post_id`),  KEY `cat_id` (`cat_id`),  KEY `user_id` (`user_id`),  KEY `post_month` (`post_month`,`post_year`),  KEY `post_url` (`post_url`))");							
									// Insert Sample Post
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."posts` ( `user_id` , `cat_id` , `post_title` , `post_url` , `post_body` , `post_date` , `post_dategmt` , `post_month` , `post_year` ) VALUES ('1', '1', 'Test Post', 'test-post', 'This is a test post\r\n\r\nLogin to the admin to edit or delete it.', '".time()."', '".gmdate("U")."', '".gmdate("n")."', '".gmdate("Y")."');");													
									// Create Settings Table
									mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."settings` (  `setting_id` int(11) unsigned NOT NULL auto_increment,  `setting_file` varchar(100) NOT NULL default '',  `setting_title` varchar(200) NOT NULL default '',  `setting_name` varchar(100) NOT NULL default '',  `setting_type` varchar(30) NOT NULL default 'textfield',  `setting_options` mediumtext NOT NULL,  `setting_value` mediumtext NOT NULL,  `setting_display` tinyint(1) unsigned NOT NULL default '0',  PRIMARY KEY  (`setting_id`),  KEY `setting_name` (`setting_name`),  KEY `setting_file` (`setting_file`))");
									// Insert Settings
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."settings` VALUES (1, 'index.php', '', 'posts_per_page', 'textfield', '', '15', 0);");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."settings` VALUES (2, 'feed.php', '', 'posts_per_feed', 'textfield', '', '15', 0);");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."settings` VALUES (3, 'post.php', '', 'comment_cookie_time', 'textfield', '', '31536000', 0);");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."settings` VALUES (4, 'post.php', '', 'comment_blacklist', 'textarea', '', '', 0);");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."settings` VALUES (5, 'post.php', '', 'comment_modwords', 'textarea', '', '', 0);");
									// Create users table
									mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."users` (  `user_id` int(11) unsigned NOT NULL auto_increment,  `username` varchar(40) NOT NULL default '',  `password` varchar(40) NOT NULL default '',  `usergroup` tinyint(1) unsigned NOT NULL default '0', `status` bool NOT NULL default '1', PRIMARY KEY  (`user_id`));");
									// Insert Admin record
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."users` VALUES (1, '".addslashes($_POST['adminuser'])."', '".addslashes(md5($_POST['adminpass']))."', 3, 1);");
									// Create Usergroups table
									mysql_query("CREATE TABLE `".addslashes($_POST['DBPre'])."usergroups` ( `group_id` int(11) unsigned NOT NULL auto_increment, `group_name` varchar(30) NOT NULL default '',  `group_removable` enum('0','1') NOT NULL default '0',  PRIMARY KEY  (`group_id`));");
									// Insert Usergroup records
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."usergroups` (`group_id`, `group_name`, `group_removable`) VALUES (1, 'Unregistered', '0');");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."usergroups` (`group_id`, `group_name`, `group_removable`) VALUES (2, 'Registered', '0');");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."usergroups` (`group_id`, `group_name`, `group_removable`) VALUES (3, 'Administrator', '0');");
									mysql_query("INSERT INTO `".addslashes($_POST['DBPre'])."usergroups` (`group_id`, `group_name`, `group_removable`) VALUES (4, 'Moderator', '0');");
									
									$step = 5;
								}
							}
							else
							{
								$step = 4;
							}
						}
						else
						{
							$error_number = mysql_errno();
						
							if($error_number == '1044')
							{
								$error = $lang['ERROR1044'];
							}
							elseif($error_number == '1046')
							{
								$error = $lang['ERROR1046'];
							}
							elseif($error_number = '1049')
							{
								$error = $lang['ERROR1049'];
							}
							else
							{
								$error = mysql_error().' - '.$error_number;
							}
							$step = 3;
						}
					}
					else
					{
						$error_number = mysql_errno();
					
						if($error_number == '1045')
						{
							$error = $lang['ERROR1045'];
						}
						elseif($error_number == '2005')
						{
							$error = $lang['ERROR2005'];
						}
						else
						{
							$error = mysql_error().' - '.$error_number;
						}
						$step = 3;
					}
				}
			}
		}
		else
		{
			$step = 1;
			$error = 'Could not write to your cache folder.<br><br>Please check that you have set the chmod/permisions to 0777';
		}
	}
	else
	{
		$step = 1;
		$error = 'Could not write to your images folder.<br><br>Please check that you have set the chmod/permisions to 0777';
	}
}
else
{
	$step = 1;
	$error = 'Could not write to your includes/config.php file.<br><br>Please check that you have set the chmod/permisions to 0777';
}

if($step == 1)
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VicBlog Installation</title>
<link rel="stylesheet" type="text/css" href="install.css">
</head>

<body>
<table class="wrapper" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><span class="heading">VicBlog Installation</span></td>
        <td align="right" valign="bottom">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><br></td>
  </tr>
  <tr>
    <td>
	<br><br>
	<span class="error"><?php echo $error;?></span><br><br><br>
	<a href="index.php">Click here</a> once you have corrected this.<br><br><br><br><bR>
    </td>
  </tr>
  <tr>
    <td><div align="center"><span class="footer">Powered by VicBlog<br/>Copyright &copy; 2009-2010 <a href="http://vicblog.vichost.com">VicBlog Development</a></span></div></td>
  </tr>
</table>
</body>
</html>
<?php
}
elseif($step == 2)
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VicBlog Installation</title>
<link rel="stylesheet" type="text/css" href="install.css">
</head>

<body>
<table class="wrapper" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><span class="heading">VicBlog Installation</span></td>
        <td align="right" valign="bottom">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><br><br><br></td>
  </tr>
  <tr>
    <td>Welcome to the installation wizard for VicBlog v<?php echo $install_version; ?>.<br/>VicBlog is released under the terms of the GNU General Public License version 3.<br/>
	This license allows you to use, modify and redistribute VicBlog. Please read the license below and click on Continue to start the Installation.<Br>
     </td>
	 </tr>
	 <td align="center"><br/>
	 <form action="index.php?lang=english" method="POST">
	 <textarea class="gpl">
<?php echo file_get_contents("gpl.txt"); ?>
	</textarea><br/><br/>
	By clicking on the Continue button, you agree to the terms of the above License.<br/>
	<br/><input class="button" type="submit" name="submit" value="Continue">
	</form><br/>
    </td>
  </tr>
  <tr>
    <td><div align="center"><span class="footer">Powered by VicBlog<br/>Copyright &copy; 2009-2010 <a href="http://vicblog.vichost.com">VicBlog Development</a></span></div></td>
  </tr>
</table>
</body>
</html>
<?php
}
elseif($step == 3)
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VicBlog Installation</title>
<link rel="stylesheet" type="text/css" href="install.css">
</head>

<body>
<table class="wrapper" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><span class="heading">VicBlog Installation</span></td>
        <td align="right" valign="bottom">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><br><br><br></td>
  </tr>
  <tr>
    <td><form name="form1" method="post" action="index.php" style="padding:0px;margin:0px;">
	<table border="0" cellspacing="0" cellpadding="3">
	<tr><td><?php echo $lang['MYSQLFILL'];?>: <br>
      <br>
	<?php
	if($error != '')
	{
		echo '<span class="error">'.$error.'</span><br><Br>';
	}
	?>
	</td></tr></table>
      <br>      <table border="0" cellspacing="0" cellpadding="3">
        <tr>
          <td><span class="content"><?php echo $lang['MYSQLHOST'];?>: </span></td>
          <td><input style="width:150px;" name="DBHost" type="text" id="DBHost" value="<?php if(isset($_POST['DBHost'])){ echo $_POST['DBHost']; } ELSE { echo 'localhost'; } ?>"></td>
          <td><span class="content">&nbsp;<a href="javascript:alert('<?php echo $lang['HOSTHELP'];?>');">(?)</a> </span></td>
        </tr>
        <tr>
          <td><span class="content"><?php echo $lang['MYSQLUSER'];?>:</span></td>
          <td><input style="width:150px;" name="DBUser" type="text" id="DBUser" value="<?php if(isset($_POST['DBUser'])){ echo $_POST['DBUser']; } ?>"></td>
          <td><span class="content">&nbsp;<a href="javascript:alert('<?php echo $lang['USERHELP'];?>');">(?)</a> </span></td>
        </tr>
        <tr>
          <td><span class="content"><?php echo $lang['MYSQLPASS'];?>:</span></td>
          <td><input style="width:150px;" name="DBPass" type="password" id="DBPass" value="<?php if(isset($_POST['DBPass'])){ echo $_POST['DBPass']; } ?>"></td>
          <td><span class="content">&nbsp;<a href="javascript:alert('<?php echo $lang['PASSHELP'];?>');">(?)</a> </span></td>
        </tr>
        <tr>
          <td><span class="content"><?php echo $lang['MYSQLNAME'];?>: </span></td>
          <td><input style="width:150px;" name="DBName" type="text" id="DBName" value="<?php if(isset($_POST['DBName'])){ echo $_POST['DBName']; } ?>"></td>
          <td><span class="content">&nbsp;<a href="javascript:alert('<?php echo $lang['NAMEHELP'];?>');">(?)</a> </span></td>
        </tr>
        <tr>
          <td><span class="content"><?php echo $lang['MYSQLPRE'];?>: </span></td>
          <td><input style="width:150px;" name="DBPre" type="text" id="DBPre" value="<?php if(isset($_POST['DBPre'])){ echo $_POST['DBPre']; } else { echo 'vic_'; } ?>"></td>
          <td><span class="content">&nbsp;<a href="javascript:alert('<?php echo $lang['PREHELP'];?>');">(?)</a> </span></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input class="button" name="Submit" type="submit" value="Continue"></td>
          <td>&nbsp;</td>
        </tr>
      </table>
            <br><br><br>
			<input name="lang" type="hidden" value="<?php echo $_POST['lang'];?>">
			 </form>
    </td>
  </tr>
  <tr>
    <td><div align="center"><span class="footer">Powered by VicBlog<br/>Copyright &copy; 2009-2010 <a href="http://vicblog.vichost.com">VicBlog Development</a></span></div></td>
  </tr>
</table>
</body>
</html>
<?php
}
elseif($step == '4')
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VicBlog Installation</title>
<link rel="stylesheet" type="text/css" href="install.css">
</head>

<body>
<table class="wrapper" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><span class="heading">VicBlog Installation</span></td>
        <td align="right" valign="bottom">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><br></td>
  </tr>
  <tr>
    <td>
	<form name="form1" method="post" action="index.php" style="padding:0px;margin:0px;">
	      <?php echo $lang['ADMFILL'];?><br>
      <br>
      <br>      <table border="0" cellspacing="0" cellpadding="3">
        <tr>
          <td><span class="content"><?php echo $lang['ADMUSER'];?>: </span></td>
          <td><input style="width:150px;" name="adminuser" type="text" id="adminuser" value="<?php if(isset($_POST['adminuser'])){ echo $_POST['adminuser']; } ?>"></td>
          <td><span class="content">&nbsp;<a href="javascript:alert('<?php echo $lang['ADMUSERHELP'];?>');">(?)</a> </span></td>
        </tr>
        <tr>
          <td><span class="content"><?php echo $lang['ADMPASS'];?>: </span></td>
          <td><input style="width:150px;" name="adminpass" type="password" id="adminpass" value="<?php if(isset($_POST['adminpass'])){ echo $_POST['adminpass']; } ?>"></td>
          <td><span class="content">&nbsp;<a href="javascript:alert('<?php echo $lang['ADMPASSHELP'];?>');">(?)</a> </span></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input class="button" name="Submit" type="submit" value="Continue"></td>
          <td>&nbsp;</td>
        </tr>
      </table>
            <br>
            <br>
			<input name="DBHost" type="hidden" id="DBHost" value="<?php echo $_POST['DBHost'];?>">
			<input name="DBName" type="hidden" id="DBName" value="<?php echo $_POST['DBName'];?>">
			<input name="DBUser" type="hidden" id="DBUser" value="<?php echo $_POST['DBUser'];?>">
			<input name="DBPass" type="hidden" id="DBPass" value="<?php echo $_POST['DBPass'];?>">
			<input name="DBPre" type="hidden" id="DBPre" value="<?php echo $_POST['DBPre'];?>">
			<input name="lang" type="hidden" value="<?php echo $_POST['lang'];?>">
			</form>
    </td>
  </tr>
  <tr>
    <td><div align="center"><span class="footer">Powered by VicBlog<br/>Copyright &copy; 2009-2010 <a href="http://vicblog.vichost.com">VicBlog Development</a></span></div></td>
  </tr>
</table>
</body>
</html>
<?php
}
elseif($step == '5')
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VicBlog Installation</title>
<link rel="stylesheet" type="text/css" href="install.css">
</head>

<body>
<table class="wrapper" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><span class="heading">VicBlog Installation</span></td>
        <td align="right" valign="bottom">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><br></td>
  </tr>
  <tr>
    <td><?php echo $lang['THANKYOU'];?>:<br>
      <br>      
      - <a href="../index.php">Front End</a><br>
      <br>
      - <a href="../adm/">Admin</a><br>
      <br>
    </td>
  </tr>
  <tr>
    <td><div align="center"><span class="footer">Powered by VicBlog<br/>Copyright &copy; 2009-2010 <a href="http://vicblog.vichost.com">VicBlog Development</a></span></div><br><br><img src="http://www.kubelabs.com/images/install/pixel.gif" width="1" height="1"></td>
  </tr>
</table>
</body>
</html>
<?php
}
?>