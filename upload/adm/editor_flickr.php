<?
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/functions/func.editor.php');
require_once('../includes/lang/admin/lang_'.$config['lang'].'.php');

db_connect($config);

$username = '';
$per_page = 35;

if(isset($_GET['username']))
{
	$username = $_GET['username'];
}
else
{
	if(isset($_COOKIE['flickrusr']))
	{
		$username = $_COOKIE['flickrusr'];
	}
}

if(isset($_GET['change']))
{
	$username = '';
	unset($_COOKIE['flickrusr']);
	setcookie("flickrusr","",time()-3600);
}

$username = ltrim(rtrim(urldecode($username)));

if(isset($_GET['username']))
{
	if(isset($_GET['remember']))
	{
		setcookie("flickrusr",$username,time()+15552000);
	}
}

if(!isset($_GET['page']))
{
	$_GET['page'] = 1;
}

if($username)
{
	$photos = getFlickrPhotos($username,$per_page,'13a14fe3974fa7b7a183ff4eb3382811',$_GET['page']);
	
	if($photos < 0)
	{
		header("Location: editor_flickr.php?change=1&error=1");
		exit;
	}

	$pages = pagenav($photos['info']['total'],$_GET['page'],$per_page,'editor_flickr.php?username='.$username.'&wysiwyg='.$_GET['wysiwyg'],1,17);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VicBlog - Add Flickr Photo</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
#pages {
	padding: 0px;
	margin:0px;
}
#pages ul {
	list-style-type: none;
	padding:0px;
	margin:0px;
}
#pages li {
	float: left;
	display: inline;
	margin: 0 5px 0 0;
	display: block;
	padding:0px;
}
#pages li a {
	color: #9aafe5;
	padding: 4px;
	border: 1px solid #9aafe5;
	text-decoration: none;
	float: left;
}
#pages li a:hover {
	color: #2e6ab1;
	border: 1px solid #2e6ab1;
}
#pages li.nolink {
	color: #CCC;
	border: 1px solid #F3F3F3;
	padding: 4px;
}
#pages li.current {
	color: #FFF;
	border: 1px solid #2e6ab1;
	padding: 4px;
	background: #2e6ab1;
}
-->
</style>
<script language="JavaScript" type="text/javascript">
/* ---------------------------------------------------------------------- *\
  Function    : insertFlickr()
  Description : Inserts image into the WYSIWYG.
\* ---------------------------------------------------------------------- */
function insertFlickr(src,alt) {
  var image = '<img src="'+src+'" alt="'+alt+'" border="0">';
  window.opener.insertHTML(image, '<?php echo $_GET['wysiwyg']; ?>');
  window.close();
}

</script>
</head>
<body>
<?php
if($username)
{
	?>
    <table width="555" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="25">Photos from <?php echo $username; ?> (<a href="editor_flickr.php?change=1&wysiwyg=<?php echo $_GET['wysiwyg']; ?>">change user</a>)</td>
        <td align="right">[photos <?php echo ((($_GET['page']-1)*35)+1); ?>-<?php echo ($_GET['page']*35); ?> of <?php echo $photos['info']['total']; ?>]</td>
      </tr>
    </table><br />
    <table border="0" cellspacing="0" cellpadding="0">
    <tr>
    <?php
	$counter = 0;
	foreach ($photos['photos'] as $key=>$val)
	{
		echo '<td width="80" height="80"><a href="javascript:insertFlickr(\''.$val['full'].'\',\''.str_replace("'","\'",$val['title']).'\');"><img src="'.$val['thumb'].'" alt="'.$val['title'].'" width="75" height="75" border="0"></a></td>';
		
		$counter++;
		
		if($counter == 7)
		{
			$counter = 0;
			echo "</tr><tr>";
		}
	}
	?>
    </tr>
    </table>
    <br />
	<div id="pages">
    <ul>
    <?php
    foreach ($pages as $key=>$val)
    {
		if($val['current'])
		{
			echo '<li class="current">'.$val['title'].'</li>';
		}
		else
		{
			echo '<li><a href="'.$val['link'].'">'.$val['title'].'</a></li>';
    	}
	}
	?>
    </ul>
    </div>
    <?
}
else
{
?>
<table width="555" border="0" cellpadding="0" cellspacing="0">
  <tr><td height="400" align="center" valign="middle">
<?php
if(isset($_GET['error']))
{
	echo '<span style="color:#FF0000;">Flickr username not found</span><br><br>';
}
?>
<form id="form1" name="form1" method="get" action="editor_flickr.php" style="padding:0px;margin:0px;">
  <table border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="100" align="left">Username:</td>
      <td align="left"><input type="text" name="username" id="username" /></td>
    </tr>
    <tr>
      <td align="left">Remember: </td>
      <td align="left"><input name="remember" type="checkbox" id="remember" value="1" checked="checked" /></td>
    </tr>
    <tr>
      <td align="left">&nbsp;</td>
      <td align="left"><input type="submit" name="button" id="button" value="Lookup" /></td>
    </tr>
  </table>
  <input type="hidden" name="wysiwyg" value="<?php echo $_GET['wysiwyg']; ?>" />
</form>
</td></tr></table>
<?
}
?>
</body>
</html>