<?php
require_once('includes/config.php');
require_once('includes/functions/func.global.php');
require_once('includes/functions/func.post.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/lang/lang_'.$config['lang'].'.php');

if(isset($_POST['id']))
{
	$_GET['i'] = $_POST['id'];
}

// Connect to database
db_connect($config);

$settings = get_settings('post.php',$config);

if(!isset($_GET['i']))
{
	if(isset($_GET['name']))
	{
		$post_lookup = mysql_fetch_array(mysql_query("SELECT post_id FROM ".$config['db']['pre']."posts WHERE post_url='".validate_input($_GET['name'])."' LIMIT 1"));
	
		if(isset($post_lookup['post_id']))
		{
			$_GET['i'] = $post_lookup['post_id'];
		}
	}
	else
	{
		exit($lang['NOPOSTID']);
	}
}

// Start session
session_start();

$comment_errors = 0;
$name_error = '';
$email_error = '';
$comment_error = '';
$name_field = '';
$email_field = '';
$website_field = '';
$comment_field = '';
$comment_pending = 0;
$user_ip = encode_ip($_SERVER,$_ENV);

if(isset($_POST['comment']))
{
	$_POST['comment'] = strip_tags($_POST['comment']);
	$_POST['comment'] = substr($_POST['comment'],0,1000);
	$_POST['name'] = substr(strip_tags($_POST['name']),0,40);
	$_POST['email'] = substr(strip_tags($_POST['email']),0,100);
	$_POST['website'] = substr(strip_tags($_POST['website']),0,180);
	
	$name_field = $_POST['name'];
	$email_field = $_POST['email'];
	$website_field = $_POST['website'];
	$comment_field = $_POST['comment'];
	
	if(trim($_POST['comment']) == '')
	{
		$comment_errors++;
		$comment_error=$lang['ENTERCOMMENT'];
	}
	
	if(trim($_POST['name']) == '')
	{
		$comment_errors++;
		$name_error=$lang['ENTERNAME'];
	}
	
	if(trim($_POST['email']) == '')
	{
		$comment_errors++;
		$email_error=$lang['ENTEREMAIL'];
	}
	elseif(!eregi("^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$", $_POST['email'])) 
	{
		$comment_errors++;
		$email_error=$lang['INVEMAIL'];
	}
	
	$check_comm = check_comment($config,$settings,$_GET['i']);
	
	if($comment_errors == 0)
	{
		setcookie('comment_name', urlencode($_POST['name']), time()+$settings['comment_cookie_time']);
		setcookie('comment_email', urlencode($_POST['email']), time()+$settings['comment_cookie_time']);
		setcookie('comment_website', urlencode($_POST['website']), time()+$settings['comment_cookie_time']);
	
		mysql_query("INSERT INTO `".$config['db']['pre']."comments` (`post_id` ,`user_id` ,`comment_date` ,`comment_dategmt` ,`comment_author` ,`comment_email` ,`comment_website` ,`comment_body` , `comment_status` , `comment_ip`) VALUES ( '".validate_input($_GET['i'])."', '0', '".time()."', '".gmdate("U")."', '".validate_input($_POST['name'])."', '".validate_input($_POST['email'])."', '".validate_input($_POST['website'])."', '".validate_input($_POST['comment'])."', 0, '".validate_input($user_ip)."');");
		$comment_id = mysql_insert_id();
		
		if($check_comm['valid'])
		{
			mysql_query("UPDATE `".$config['db']['pre']."posts` SET `post_comments` = post_comments+1 WHERE `post_id` = '".validate_input($_GET['i'])."' LIMIT 1;");
		}

		$post_info = mysql_fetch_array(mysql_query("SELECT post_id,post_title,post_body,post_date,post_comments,user_id,cat_id,post_year,post_month,post_url,post_allowcom FROM ".$config['db']['pre']."posts WHERE post_id='".validate_input($_GET['i'])."' LIMIT 1"));
		
		if($config['seo_urls'])
		{
			header("Location: ".$config['site_url'].$post_info['post_year'].'/'.$post_info['post_month'].'/'.date('d',$post_info['post_date']).'/'.$post_info['post_url'].'/#c'.$comment_id);
		}
		else
		{
			header("Location: ".$config['site_url'].'post.php?i='.$post_info['post_id'].'#c'.$comment_id);
		}
		exit;
	}
}

$cats = get_cats($config);

$archive = get_archive($config,$lang);

$links = get_links($config);

$post_info = mysql_fetch_array(mysql_query("SELECT post_id,post_title,post_body,post_date,post_comments,user_id,cat_id,post_year,post_month,post_url,post_allowcom FROM ".$config['db']['pre']."posts WHERE post_id='".validate_input($_GET['i'])."' LIMIT 1"));

if(!isset($post_info['post_id']))
{
	exit($lang['POSTNOTEXIST']);
}

$user_info = mysql_fetch_row(mysql_query("SELECT username FROM ".$config['db']['pre']."users WHERE user_id='".$post_info['user_id']."' LIMIT 1"));

$comments = array();

$query = "SELECT comment_id,user_id,comment_date,comment_body,comment_author,comment_website FROM ".$config['db']['pre']."comments WHERE post_id='".validate_input($_GET['i'])."' AND (comment_status=1 OR comment_ip='".validate_input($user_ip)."') ORDER BY comment_id ASC";
$query_result = @mysql_query ($query) OR error(mysql_error(), __LINE__, __FILE__, 0, '', '');
while ($info = @mysql_fetch_array($query_result))
{
	$comments[$info['comment_id']]['comment_id'] = $info['comment_id'];
	$comments[$info['comment_id']]['user_id'] = $info['user_id'];
	$comments[$info['comment_id']]['comment_date'] = date("F jS, Y \a\t g:i a",$info['comment_date']);
	$comments[$info['comment_id']]['comment_author'] = $info['comment_author'];
	$comments[$info['comment_id']]['comment_website'] = $info['comment_website'];
	$comments[$info['comment_id']]['comment_body'] = stripslashes($info['comment_body']);
}

$_SESSION['kbuser']['seccode'] = strtoupper(getrandnum(4));
$_SESSION['kbuser']['secviewed'] = '0';

if($name_field == '')
{
	if(isset($_COOKIE['comment_name']))
	{
		$name_field = substr(strip_tags(urldecode($_COOKIE['comment_name'])),0,40);
	}
	if(isset($_COOKIE['comment_email']))
	{
		$email_field = substr(strip_tags(urldecode($_COOKIE['comment_email'])),0,100);
	}
	if(isset($_COOKIE['comment_website']))
	{
		$website_field = substr(strip_tags(urldecode($_COOKIE['comment_website'])),0,180);
	}
}

$post_day = date('d',$post_info['post_date']);

$page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/post.html');
$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$cats,stripslashes($post_info['post_title'])));
$page->SetParameter ('COMMENT_PENDING', $comment_pending);
$page->SetParameter ('POST_ID', $post_info['post_id']);
$page->SetParameter ('POST_TITLE', stripslashes($post_info['post_title']));
$page->SetParameter ('POST_BODY', stripslashes($post_info['post_body']));
$page->SetParameter ('POST_MONTH_SHORT', date('M',$post_info['post_date']));
$page->SetParameter ('POST_DAY', $post_day);
$page->SetParameter ('POST_USER', stripslashes($user_info[0]));
if($config['seo_urls'])
{
	$page->SetParameter ('POST_URL', $config['site_url'].$post_info['post_year'].'/'.$post_info['post_month'].'/'.$post_day.'/'.$post_info['post_url'].'/');
}
else
{
	$page->SetParameter ('POST_URL', $config['site_url'].'post.php?i='.$post_info['post_id']);
}
$page->SetParameter ('POST_COMMENTS', $post_info['post_comments']);
$page->SetParameter ('CAT_NAME', $cats[$post_info['cat_id']]['cat_title']);
$page->SetParameter ('CAT_ID', $post_info['cat_id']);
$page->SetParameter ('ERROR_NAME', $name_error);
$page->SetParameter ('ERROR_EMAIL', $email_error);
$page->SetParameter ('ERROR_COMMENT', $comment_error);
$page->SetParameter ('NAME_FIELD', $name_field);
$page->SetParameter ('EMAIL_FIELD', $email_field);
$page->SetParameter ('WEBSITE_FIELD', $website_field);
$page->SetParameter ('COMMENT_FIELD', $comment_field);
$page->SetParameter ('TIME', time());
$page->SetParameter ('ALLOW_COMMENTS', $post_info['post_allowcom']);
$page->SetLoop ('CATS', $cats);
$page->SetLoop ('ARCHIVE', $archive);
$page->SetLoop ('COMMENTS', $comments);
$page->SetLoop ('LINKS', $links);
$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
$page->CreatePageEcho($lang,$config);
?>