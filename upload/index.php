<?php
require_once('includes/config.php');
require_once('includes/functions/func.global.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/lang/lang_'.$config['lang'].'.php');

// Check if the script is installed
checkinstall($config);

// Connect to database
db_connect($config);

$settings = get_settings('index.php',$config);

if(!isset($_GET['page']))
{
	$_GET['page'] = 1;
}
else
{
	$_GET['page'] = makeInt($_GET['page']);
}

$blog_posts = array();
$users = array();
$user_where = '';
$dm = array();

$cats = get_cats($config);

$archive = get_archive($config,$lang);

$links = get_links($config);

if(isset($_GET['m']))
{
	$query = "SELECT post_id,cat_id,post_title,post_body,post_comments,user_id,post_date,post_month,post_year,post_url FROM ".$config['db']['pre']."posts WHERE post_month='".validate_input($_GET['m'])."' AND post_year='".validate_input($_GET['y'])."' ORDER BY post_id DESC LIMIT ".validate_input(($_GET['page']-1)*$settings['posts_per_page']).",".$settings['posts_per_page'];
	$count = mysql_num_rows(mysql_query("SELECT 1 FROM ".$config['db']['pre']."posts WHERE post_month='".validate_input($_GET['m'])."' AND post_year='".validate_input($_GET['y'])."'"));
	$url_query = '&m='.$_GET['m'];
}
elseif(isset($_GET['cat']))
{
	$query = "SELECT post_id,cat_id,post_title,post_body,post_comments,user_id,post_date,post_month,post_year,post_url FROM ".$config['db']['pre']."posts WHERE cat_id='".validate_input($_GET['cat'])."' ORDER BY post_id DESC LIMIT ".validate_input(($_GET['page']-1)*$settings['posts_per_page']).",".$settings['posts_per_page'];
	$count = mysql_num_rows(mysql_query("SELECT 1 FROM ".$config['db']['pre']."posts WHERE cat_id='".validate_input($_GET['cat'])."'"));
	$url_query = '&cat='.$_GET['cat'];
}
elseif(isset($_GET['author']))
{
	$query = "SELECT post_id,cat_id,post_title,post_body,post_comments,user_id,post_date,post_month,post_year,post_url FROM ".$config['db']['pre']."posts WHERE user_id='".validate_input($_GET['author'])."' ORDER BY post_id DESC LIMIT ".validate_input(($_GET['page']-1)*$settings['posts_per_page']).",".$settings['posts_per_page'];
	$count = mysql_num_rows(mysql_query("SELECT 1 FROM ".$config['db']['pre']."posts WHERE user_id='".validate_input($_GET['author'])."'"));
	$url_query = '&author='.$_GET['author'];
}
elseif(isset($_GET['q']))
{
	$query = "SELECT post_id,cat_id,post_title,post_body,post_comments,user_id,post_date,post_month,post_year,post_url FROM ".$config['db']['pre']."posts WHERE post_title LIKE '%".validate_input($_GET['q'])."%' ORDER BY post_id DESC LIMIT ".validate_input(($_GET['page']-1)*$settings['posts_per_page']).",".$settings['posts_per_page'];
	$count = mysql_num_rows(mysql_query("SELECT 1 FROM ".$config['db']['pre']."posts WHERE post_title LIKE '%".validate_input($_GET['q'])."%'"));
	$url_query = '&q='.$_GET['q'];
}
else
{
	$query = "SELECT post_id,cat_id,post_title,post_body,post_comments,user_id,post_date,post_month,post_year,post_url FROM ".$config['db']['pre']."posts ORDER BY post_id DESC LIMIT ".validate_input(($_GET['page']-1)*$settings['posts_per_page']).",".$settings['posts_per_page'];
	$count = mysql_num_rows(mysql_query("SELECT 1 FROM ".$config['db']['pre']."posts"));
	$url_query = '';
}


if ($count>($_GET['page']*$settings['posts_per_page']))
{
	$pg_more = 'index.php?page='.($_GET['page']+1).$url_query;
}
else
{
	$pg_more = '';
}

if ($_GET['page']>1)
{
	$pg_less = 'index.php?page='.($_GET['page']-1).$url_query;
}
else
{
	$pg_less = '';
}

$query_result = @mysql_query ($query) OR error(mysql_error(), __LINE__, __FILE__, 0, '', '');
while ($info = @mysql_fetch_array($query_result))
{
	$post_day = date('d',$info['post_date']);

	$blog_posts[$info['post_id']]['post_id'] = $info['post_id'];
	$blog_posts[$info['post_id']]['post_title'] = stripslashes($info['post_title']);
	$blog_posts[$info['post_id']]['post_body'] = stripslashes($info['post_body']);
	$blog_posts[$info['post_id']]['post_comments'] = $info['post_comments'];
	$blog_posts[$info['post_id']]['post_month_short'] = date('M',$info['post_date']);
	$blog_posts[$info['post_id']]['post_day'] = $post_day;
	$blog_posts[$info['post_id']]['user_id'] = $info['user_id'];
	$blog_posts[$info['post_id']]['user_name'] = '';
	$blog_posts[$info['post_id']]['cat_id'] = $info['cat_id'];
	$blog_posts[$info['post_id']]['cat_name'] = $cats[$info['cat_id']]['cat_title'];
	
	if($config['seo_urls'])
	{
		$blog_posts[$info['post_id']]['post_url'] = $config['site_url'].$info['post_year'].'/'.$info['post_month'].'/'.$post_day.'/'.$info['post_url'].'/';
	}
	else
	{
		$blog_posts[$info['post_id']]['post_url'] = $config['site_url'].'post.php?i='.$info['post_id'];
	}
	
	$blog_posts[$info['post_id']]['cat_url'] = $config['site_url'].'?cat='.$info['cat_id'];
	$blog_posts[$info['post_id']]['author_url'] = $config['site_url'].'?author='.$info['user_id'];

	if(!isset($dm[$blog_posts[$info['post_id']]['post_month_short'].$blog_posts[$info['post_id']]['post_day']]))
	{
		$dm[$blog_posts[$info['post_id']]['post_month_short'].$blog_posts[$info['post_id']]['post_day']] = $info['post_id'];
		
		$blog_posts[$info['post_id']]['first_post'] = '1';
	}
	else
	{
		$blog_posts[$info['post_id']]['first_post'] = '0';
	}
	
	if($user_where == '')
	{
		$user_where = "WHERE user_id='".validate_input($info['user_id'])."'";
	}
	else
	{
		$user_where.= " OR user_id='".validate_input($info['user_id'])."'";
	}
}

if($user_where != '')
{
	$query = "SELECT user_id,username FROM ".$config['db']['pre']."users ".$user_where;
	$query_result = @mysql_query ($query) OR error(mysql_error(), __LINE__, __FILE__, 0, '', '');
	while ($info = @mysql_fetch_array($query_result))
	{
		$users[$info['user_id']] = $info['username'];
	}
}

foreach ($blog_posts as $key => $value)
{
	$blog_posts[$key]['user_name'] = $users[$value['user_id']];
}

$page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/index.html');
$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang));
$page->SetLoop ('POSTS', $blog_posts);
$page->SetLoop ('CATS', $cats);
$page->SetLoop ('ARCHIVE', $archive);
$page->SetLoop ('LINKS', $links);
$page->SetParameter ('PG_MORE', $pg_more);
$page->SetParameter ('PG_LESS', $pg_less);
$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
$page->CreatePageEcho($lang,$config);
?>