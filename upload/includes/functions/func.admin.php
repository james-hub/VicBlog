<?php
function create_post_url($config,$post_url,$post_id=0)
{
	if (function_exists('mb_strtolower'))
	{
		$post_url = mb_strtolower($post_url);
	}
	else
	{
		$post_url = strtolower($post_url);
	}
	$post_url = ltrim($post_url);
	$post_url = rtrim($post_url);
	$post_url = preg_replace('/\s\s+/', ' ', $post_url);
	$post_url = str_replace(' ','-',$post_url);
	$post_url = str_replace('_','-',$post_url);
	$post_url = htmlentities($post_url);
	$post_url = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde);/','$1',$post_url);
	$post_url = html_entity_decode($post_url);
	$post_url = preg_replace('/[^a-z0-9-]/', '', $post_url);
	$post_url = trim($post_url,'-');
	$post_url = substr($post_url,0,100);
	
	$check_url_exists = mysql_num_rows(mysql_query("SELECT 1 FROM ".$config['db']['pre']."posts WHERE post_url='".validate_input($post_url)."' AND post_id!='".validate_input($post_id)."' LIMIT 1"));
	
	if($check_url_exists)
	{
		$count = 1;
	
		do
		{
			$new_post_url = $post_url.'-'.$count;
		
			$check_url_exists = mysql_num_rows(mysql_query("SELECT 1 FROM ".$config['db']['pre']."posts WHERE post_url='".validate_input($new_post_url)."' AND post_id!='".validate_input($post_id)."' LIMIT 1"));
			
			$count++;
		}
		while ($check_url_exists);
		
		$post_url = $new_post_url;
	}

	return $post_url;
}

session_start();

if(!isset($_SESSION['kbuser']['id']))
{
	header('Location: '.$config['site_url'].'login.php?ref=adm/');
	exit;
}
else
{
	if($_SESSION['kbuser']['usergroup'] != '3')
	{
		header('Location: '.$config['site_url'].'login.php?ref=adm/');
		exit;
	}
}
?>