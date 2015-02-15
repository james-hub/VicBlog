<?php
require_once('includes/config.php');
require_once('includes/functions/func.global.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/lang/lang_'.$config['lang'].'.php');

// Connect to database
db_connect($config);

$error = array();

if(isset($_POST['username']))
{
	if(strlen($_POST['username']) == 0) 
	{
		$error[]['msg'] = $lang['ERROMISS'];
	}
	elseif(strlen($_POST['username']) > 40)
	{
		$error[]['msg'] = $lang['ERROLO'];
	}
	elseif(!preg_match("/^[[:alnum:]]+$/", $_POST['username']))
	{
		$error[]['msg'] = $lang['ERROINV'];
	}

	if(strlen($_POST['username']) == 0) 
	{
		$error[]['msg'] = $lang['ERROMISS'];
	}
	if(strlen($_POST['password']) == 0)
	{
		$error[]['msg'] = $lang['ERROPMIS'];
	}
	
	if(count($error) == 0)
	{
		$user_id = 0;
	
		$query = "SELECT user_id,usergroup FROM ".$config['db']['pre']."users WHERE username='" . validate_input($_POST['username']) . "' AND password='" . validate_input(md5($_POST['password'])) . "' AND status = '1' LIMIT 1";
		$query_result = mysql_query($query);
		while ($info = mysql_fetch_array($query_result))
		{
			$user_id = $info['user_id'];
			$usergroup = $info['usergroup'];
		}
		if($user_id)
		{
			session_start();
			$_SESSION['kbuser']['id'] = $user_id;
			$_SESSION['kbuser']['username'] = $_POST['username'];
			$_SESSION['kbuser']['usergroup'] = $usergroup;
			
			if($_POST['ref'])
			{
				$_POST['ref'] = str_replace('http://','',$_POST['ref']);
			
				if($_POST['ref'] == 'adm/')
				{
					header('Location: '.$config['site_url'].'adm/');
				}
				else
				{
					header('Location: '.$config['site_url'].urldecode($_POST['ref']));
				}
			}
			else
			{
				header('Location: '.$config['site_url']."index.php");
			}
			exit;
		}
		else
		{
			$error[]['msg'] = $lang['ERROUPMIS'];
		}
	}
}

$page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/login.html');
$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang));
$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
if(isset($_POST['username']))
{
	$page->SetParameter ('USERNAME_FIELD', $_POST['username']);
}
else
{
	$page->SetParameter ('USERNAME_FIELD', '');
}
if(isset($_GET['ref']))
{
	$page->SetParameter ('REF',$_GET['ref']);
}
else
{
	$page->SetParameter ('REF','');
}
$page->SetLoop ('ERRORS', $error);
$page->CreatePageEcho($lang,$config);
?>