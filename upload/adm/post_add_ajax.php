<?php
require_once('../includes/config.php');
require_once('../includes/classes/class.json.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');

db_connect($config);

// Checks if the post variables were set
function postDataCheck()
{
	if (isset($_POST['post_title']) && isset($_POST['post_category']) && isset($_POST['post_body']))
	{
		return TRUE;
	}
	return FALSE;
}

if (!empty($_POST) && isset($_POST['operation']))
{
	switch ($_POST['operation'])
	{
		case 'check':
			$result = mysql_query('SELECT * FROM `' . $config['db']['pre'] . 'drafts` WHERE `post_id` = "' . validate_input($_POST['post_id']) . '" LIMIT 1');
			echo (mysql_num_rows($result) > 0)?1:0;
		break;
		case 'get_id':
			if (postDataCheck())
			{
				$result = mysql_query('SELECT `draft_id` FROM `' . $config['db']['pre'] . 'drafts` WHERE `post_title` = "' . validate_input($_POST['post_title']) . '" AND `cat_id` = "' . validate_input($_POST['post_category']) . '" AND `post_body` = "' . validate_input($_POST['post_body']) . '" LIMIT 1');
				$row = mysql_fetch_row($result);
          
				echo isset($row[0])?$row[0]:0;
			}
			else
			{
				echo 0;
			}    
		break;
		case 'load':
			$result = mysql_query('SELECT * FROM `' . $config['db']['pre'] . 'drafts` WHERE `draft_id` = "' . validate_input($_POST['post_id']) . '" LIMIT 1');
				  
			$row = mysql_fetch_row($result); 
			$json = new Services_JSON();
			echo (mysql_error() == NULL)?$json->encode($row):0;
		break;
		case 'insert':
			if (postDataCheck())
			{
        		if (isset($_POST['post_id']) && is_numeric($_POST['post_id']) && $_POST['post_id'] != 0)
        		{
          			$query = 'UPDATE `' . $config['db']['pre'] . 'drafts` SET `post_title` = "' . validate_input($_POST['post_title']) . '", `cat_id` = "' . validate_input($_POST['post_category']) . '", `post_body` = "' . validate_input($_POST['post_body']) . '", `updated_at` = "' . validate_input(time()) . '" WHERE `draft_id` = "' . validate_input($_POST['post_id']) . '" LIMIT 1';
					$result = mysql_query($query);
					$output = '-1';
				}
				else
				{
          			$query = 'INSERT INTO `' . $config['db']['pre'] . 'drafts` (`post_title`, `cat_id`, `post_body`, `updated_at`) VALUES ("' . validate_input($_POST['post_title']) . '", "' . validate_input($_POST['post_category']) . '", "' . validate_input($_POST['post_body']) . '", "' . validate_input(time()) . '")';
					$result = mysql_query($query);
					$output = mysql_insert_id();
        		}     
       			echo ($result)?$output:0;
      		}
      		else
      		{
				echo 0;
			}
		break;
		case 'delete':
			$result = mysql_query('DELETE FROM `' . $config['db']['pre'] . 'drafts` WHERE `draft_id` = "' . validate_input($_POST['post_id']) . '" LIMIT 1');
			echo 1;
		break;
		default:
		break;
	}
}
?>