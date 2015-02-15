<?php
require_once('../includes/config.php');

function install_error($error)
{
	if(!isset($_GET['ignore_errors']))
	{
		exit($error.'<br><Br><a href="'.$_SERVER['PHP_SELF'].'?ignore_errors=1&install=1">Click here</a> to run the upgrade and ignore errors');
	}
}

$install_version = '1.1.2';

// Check to see if the script is already installed
if(isset($config['installed']))
{
	if($config['version'] == $install_version)
	{
		// Exit the script
		exit('VicBlog is already installed.');
	}
}

if(!isset($_GET['install']))
{
	echo 'Before you run an upgrade it is recomended that you backup your VicBlog database<br><Br>Are you sure you want to upgrade your VicBlog installation from '.$config['version'].' to '.$install_version.'?<br><br><a href="upgrade_1.0.php?install=1">Yes do it</a>';
}
else
{
	ignore_user_abort(1);

	echo '<pre>';
	
	// Try to connect to the databse
	echo "Connecting to database.... \t";
    $db_connection = @mysql_connect ($config['db']['host'], $config['db']['user'], $config['db']['pass']) OR install_error('ERROR ('.mysql_error().')');
    $db_select = @mysql_select_db ($config['db']['name']) OR install_error('ERROR ('.mysql_error().')');
	echo "success<br>";
	
	mysql_query("CREATE TABLE `".addslashes($config['db']['pre'])."drafts` (  `draft_id` mediumint(8) unsigned NOT NULL auto_increment,  `post_title` varchar(255) NOT NULL default '',  `cat_id` mediumint(8) unsigned NOT NULL default '0',  `post_body` longtext NOT NULL,  `updated_at` int(11) unsigned NOT NULL default '0',  KEY `id` (`draft_id`))");

	// Check that config file is writtable
	echo "Checking config file.. \t\t";
	if(@is_writable('../includes/config.php'))
	{
		echo "success<br>";
	}
	else
	{
		echo 'ERROR (config.php permisions not set correctly)';
		exit;
	}	
	
	// Start updating the config file with new variables
	echo "Writting config.php updates.. \t";
	$content = "<?php\n";
	$content.= "\$config['db']['host'] = '".addslashes(stripslashes($config['db']['host']))."';\n";
	$content.= "\$config['db']['name'] = '".addslashes(stripslashes($config['db']['name']))."';\n";
	$content.= "\$config['db']['user'] = '".addslashes(stripslashes($config['db']['user']))."';\n";
	$content.= "\$config['db']['pass'] = '".addslashes(stripslashes($config['db']['pass']))."';\n";
	$content.= "\$config['db']['pre'] = '".addslashes(stripslashes($config['db']['pre']))."';\n";
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
	$content.= "\$config['version'] = '1.1.2';\n";
	$content.= "\$config['lang'] = '".addslashes(stripslashes($config['lang']))."';\n";
	$content.= "\$config['installed'] = '1';\n";
	$content.= "?>";	
	
	// Open the includes/config.php for writting
	$handle = fopen('../includes/config.php', 'w');
	// Write the config file
	fwrite($handle, $content);
	// Close the file
	fclose($handle);
	echo "success<br>";
	
	echo "<br><Br><Br>Thank You! for upgrading VicBlog, Please <a href=\"../index.php\">click here</a> to access your site";

	echo '</pre>';
}
?>