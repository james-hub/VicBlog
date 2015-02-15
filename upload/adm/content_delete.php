<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');

// Connect to database
db_connect($config);

if (isset($_POST['list']))
{
    $count = 0;
    $sql = "DELETE FROM `".$config['db']['pre']."content` ";
    $sql2 = "DELETE FROM `".$config['db']['pre']."links` ";

	foreach ($_POST['list'] as $value)
	{
        if ($count == 0)
		{
			$sql.= "WHERE `content_id` = '".validate_input($value)."'";
			$sql2.= "WHERE `content_id` = '".validate_input($value)."'";
        } 
		else 
		{
			$sql.= " OR `content_id` = '".validate_input($value)."'";
			$sql2.= " OR `content_id` = '".validate_input($value)."'";
        }

	    $count++;
    }

    mysql_query($sql." LIMIT " . count($_POST['list']));
    mysql_query($sql2." LIMIT " . count($_POST['list']));

} 
elseif (isset($_GET['id']))
{
    $sql = "DELETE FROM `".$config['db']['pre']."content` WHERE `content_id` = '".validate_input($_GET["id"])."' LIMIT 1;";
    $sql2 = "DELETE FROM `".$config['db']['pre']."links` WHERE `content_id` = '".validate_input($_GET["id"])."' LIMIT 1;";

    mysql_query($sql);
    mysql_query($sql2);
}

header('Location: content_view.php');
exit;
?>