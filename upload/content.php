<?php
require_once('includes/config.php');
require_once('includes/functions/func.global.php');
require_once('includes/classes/class.template_engine.php');
require_once('includes/lang/lang_'.$config['lang'].'.php');

if(isset($_POST['id']))
{
	$_GET['i'] = $_POST['id'];
}

if(!isset($_GET['i']))
{
	exit($lang['NOCONTENTID']);
}

// Connect to database
db_connect($config);

// Start session
session_start();

$cats = get_cats($config);

$archive = get_archive($config,$lang);

$links = get_links($config);

$content = mysql_fetch_array(mysql_query("SELECT content_id,content_type,content_title,content_body FROM ".$config['db']['pre']."content WHERE content_id='".validate_input($_GET['i'])."' LIMIT 1"));

$page = new HtmlTemplate ('templates/' . $config['tpl_name'] . '/content_html.html');
$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,$cats,''));
$page->SetParameter ('CONTENT_TITLE', stripslashes($content['content_title']));
$page->SetParameter ('CONTENT', stripslashes($content['content_body']));
$page->SetLoop ('CATS', $cats);
$page->SetLoop ('ARCHIVE', $archive);
$page->SetLoop ('LINKS', $links);
$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
$page->CreatePageEcho($lang,$config);
?>