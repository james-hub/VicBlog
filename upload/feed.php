<?php
require_once('includes/config.php');
require_once('includes/functions/func.global.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/lang/lang_'.$config['lang'].'.php');

db_connect($config);

$settings = get_settings('feed.php',$config);

header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>';

$comm = 0;
$sql_ammend = '';

if (isset($_GET['p']))
{
	$sql_ammend = "WHERE post_id = '".validate_input($_GET['p'])."' ";
}
elseif (isset($_GET['c']))
{
    $sql_ammend = "WHERE cat_id = '".validate_input($_GET['c'])."' ";
}
?>
<rss version="2.0" 
  xmlns:content="http://purl.org/rss/1.0/modules/content/"
  xmlns:wfw="http://wellformedweb.org/CommentAPI/"
  xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
  <title><?php echo stripslashes($config['site_title']); ?></title>
  <link><?php echo $config['site_url'].'feed.php'; ?></link>
  <description><?php echo stripslashes($config['site_title']); ?></description>
  <language>en</language>
<?php
$query_result = mysql_query("SELECT post_id,cat_id,post_title,post_body,post_comments,user_id,post_date,post_month,post_year,post_url FROM ".$config['db']['pre']."posts ".$sql_ammend." ORDER BY post_id DESC LIMIT ".$settings['posts_per_feed'].";");
while ($rss = mysql_fetch_array($query_result))
{
	$rss['post_body'] = strip_tags($rss['post_body'],'<tbody>, <tr>, <td>, <div>, <img>, <p>, <table>, <strong>, <ul>, <li>, <hr>, <br>, <span>, <a>, <u>, <b>');
	$rss['post_body'] = preg_replace('#\onclick="(.+?)"#', '', $rss['post_body']);

	if($config['seo_urls'])
	{
		$post_url = $config['site_url'].$rss['post_year'].'/'.$rss['post_month'].'/'.date('d',$rss['post_date']).'/'.$rss['post_url'].'/';
	}
	else
	{
		$post_url = $config['site_url'].'post.php?i='.$rss['post_id'];
	}
?>
  <item>
    <title><![CDATA[<?php echo $rss['post_title']; ?>]]></title>
    <link><?php echo $post_url; ?></link>
    <pubDate><?php echo date('r',$rss['post_date']);?></pubDate>
    <description><![CDATA[<?php echo $rss['post_body']; ?>]]></description>
    <guid><?php echo $post_url; ?></guid>
  </item>
<?php 
}
?>
  </channel>
</rss>