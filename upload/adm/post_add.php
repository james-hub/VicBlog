<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/lang/admin/lang_'.$config['lang'].'.php');
require_once('class.menu.php');

db_connect($config);

$errors = 0;
$error_title = '';
$error_body = '';

if(isset($_POST['body']))
{
	$_POST['subject'] = strip_tags($_POST['subject']);
	$_POST['subject'] = substr($_POST['subject'],0,100);

	if(trim($_POST['title']) == '')
	{
		$error_title = $lang['PLEASETITLE'];
	}
	
	if(trim($_POST['body']) == '')
	{
		$error_body = $lang['PLEASEPOST'];
	}

	$post_url = create_post_url($config,$_POST['subject']);

	mysql_query("INSERT INTO `".$config['db']['pre']."posts` ( `user_id` , `cat_id` , `post_title` , `post_url` , `post_body` , `post_date` , `post_dategmt` , `post_month` , `post_year` ) VALUES ('".validate_input($_SESSION['kbuser']['id'])."', '".validate_input($_POST['cat'])."', '".validate_input($_POST['subject'])."', '".validate_input($post_url)."', '".validate_input($_POST['body'])."', '".time()."', '".gmdate('U')."', '".gmdate('n')."', '".gmdate('Y')."');");
	
	if(mysql_insert_id())
	{
		mysql_query("UPDATE `".$config['db']['pre']."cats` SET `cat_count` = cat_count+1 WHERE `cat_id` = '".validate_input($_POST['cat'])."' LIMIT 1 ;");
		
		$archive_rows = mysql_num_rows(mysql_query("SELECT 1 FROM ".$config['db']['pre']."archive WHERE archive_month='".date('n')."' AND archive_year='".date('Y')."' LIMIT 1"));
		
		if(!$archive_rows)
		{
			mysql_query("INSERT INTO `".$config['db']['pre']."archive` VALUES ('".date('n')."', '".date('Y')."');");
		}
		
		setcookie ("DraftCookie", "", time() - 3600);
	}
	
	header('Location: post_view.php');
	exit;
}

mysql_query("DELETE FROM `".$config['db']['pre']."drafts` WHERE `updated_at` < '".(time()-21600)."'");

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config,$lang);

$draft_id = 0;
$draft_title = '';
$draft_cat = '';
$draft_body = '';

if(isset($_COOKIE['DraftCookie']))
{
	$draft_id = str_replace(array('{"post_id":"','"}'),'',stripslashes($_COOKIE['DraftCookie']));
}

if($draft_id)
{
	$draft_info = mysql_fetch_array(mysql_query("SELECT draft_id,cat_id,post_title,post_body FROM `" . $config['db']['pre'] . "drafts` WHERE `draft_id` = '" . validate_input($draft_id) . "' LIMIT 1"));

	if(isset($draft_info['draft_id']))
	{
		$draft_title = stripslashes($draft_info['post_title']);
		$draft_body = str_replace(chr(10), '',str_replace(chr(13), '',stripslashes($draft_info['post_body'])));
		$draft_cat = $draft_info['cat_id'];
	}
	else
	{
		setcookie ("DraftCookie", "", time() - 3600);
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?=$config['site_title'].' '.$lang['ADMIN'].' - '.$lang['ADD'].' '.$lang['POST'];?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="css/main.css">
<link rel="stylesheet" type="text/css" href="images/style.css">
<link REL="stylesheet" type="text/css" href="menu/themes/Office/theme.css">
<script language="JavaScript" src="menu/JSCookMenu.js"></script>
<script language="JavaScript" src="menu/themes/Office/theme.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript">
<!--
function checkBox(theBox)
{
	var aBox = theBox.form["list[]"];
	var selAll = false;
	var i;
	for(i=0;i<aBox.length;i++)
	{
		if(aBox[i].checked==false) selAll=true;
	}
	if(theBox.name=="selall")
	{
		for(i=0;i<aBox.length;i++)
		{
			aBox[i].checked = selAll;
		}
		selAll = !selAll;
	}
}
function init()
{
	var theForm = document.f1;
	var aBox = theForm["list[]"];
	var selAll = false;
	var i;
	for(i=0;i<aBox.length;i++)
	{
		if(aBox[i].checked==false) selAll=true;
		aBox[i].onclick = function(){checkBox(this)};
	}
}
//-->
</script>
<script language="JavaScript" type="text/javascript" src="editor/wysiwyg_2.js"></script>
<!-- Begin draft save functionality -->
<script type="text/javascript" src="js/mootools.js"></script>
<script type="text/javascript">
function draftLoad()
{
	if (confirm('<?php echo $lang['DRAFT_EXISTS_CONFIRM']; ?>'))
	{
		$('wysiwygbody').contentWindow.document.body.innerHTML = '<?php echo $draft_body; ?>';
		$('subject').setProperty('value', '<?php echo $draft_title; ?>');
		
        var catNum = $('cat').length;
      
		for (var i = 0; i < catNum; i++)
        {
			if ($('cat').options[i].value == '<?php echo $draft_cat; ?>')
			{
				$('cat').selectedIndex = i;
			}
        } 
	}
	else
	{
		var d = new Date();
		document.cookie = "DraftCookie=0;expires=" + d.toGMTString() + ";" + ";";
	}
}
var needToConfirm = true;

window.onbeforeunload = confirmExit;
function confirmExit()
{
	if (needToConfirm)
	{
		var showConfirm = true;
	
		if($('wysiwygbody').contentWindow.document.body.innerHTML == '<br>')
		{
			showConfirm = false;
		}
		if($('wysiwygbody').contentWindow.document.body.innerHTML == '')
		{
			showConfirm = false;
		}

		if(showConfirm)
		{
			return "You have attempted to leave without posting this entry.";
		}
	}
}
function draftSaver() 
{
    window.addEvent('domready', function() 
	{
		/* Initialize of Hash.Cookie */
		var HashCookie = new Hash.Cookie('DraftCookie', {duration: 30});
		
		/* Some default variable values */
		var draftID = 0;
		var draftStatus = false;
	  
		draftTitle    = '';
		draftCategory = '';
		draftBody     = ''; 
	  
		/**
		 * Updates draft id
		 */
		function updateDraftId(_draftId)
		{
			draftID = _draftId;
		}
	  
		/**
		 * If the draft is saved, the cookie is present, we get data from the cookie
		 * If there is no draft, return 0
		 */
		function getDraftId()
		{
			return draftID;
		}
	  
		/* removes the invalid draft info */
		function draftClean()
		{
			HashCookie.empty();
			updateDraftId(0);
		}
	  
		/**
		* Checks whether the draftTitle, draftCategory or draftBody changed during the last x
		* amount of seconds, if it has, updates the draft and creates the cookie if needed.
		*/
		updateDraftId(HashCookie.get('post_id'));
	  
		draftTitle    = $('subject').getValue();
		draftCategory = $('cat').getValue();
		draftBody = '';  
		
		postChecker();
		
		function postChecker()
		{ 
			var postChecker = (
				function() {
					var draftTitleCurrent    = $('subject').getValue();
			  		var draftCategoryCurrent = $('cat').getValue();
			  		var draftBodyCurrent     = $('wysiwygbody').contentWindow.document.body.innerHTML;  
	
					if(draftBodyCurrent == '')
					{
						return;
					}
					if(draftBodyCurrent == '<br>')
					{
						return;
					}
	
					// Create/update draft and cookie if the content changed. 
					if ((draftTitle != draftTitleCurrent) || (draftCategory != draftCategoryCurrent) || (draftBody != draftBodyCurrent))
					{
						// Update initial values with the current ones
						draftTitle    = draftTitleCurrent;
						draftCategory = draftCategoryCurrent;
						draftBody     = draftBodyCurrent;
			  
						new Ajax('post_add_ajax.php', {
							postBody: 'operation=insert&post_id=' + getDraftId() + '&post_title=' + draftTitle + '&post_category=' + draftCategory + '&post_body=' + draftBody , 
								onComplete: function(request){								
									var timeObj = new Date();
									var getTime = '';
												  
								  	getTime += timeObj.getHours();
									getTime += ':';
									
									if(timeObj.getMinutes() > 9)
									{
										getTime += timeObj.getMinutes();
									}
									else
									{
										getTime += '0'+timeObj.getMinutes();
									}
									getTime += ':';
									if(timeObj.getSeconds() > 9)
									{
										getTime += timeObj.getSeconds();
									}
									else
									{
										getTime += '0'+timeObj.getSeconds();
									}
																			   
									if (request != '0' && request != '-1')
									{
										// Updating draft id
										updateDraftId(request);
										
										if (draftStatus == false)
										{
									  		$('draft_saved_msg_container').innerHTML = '<?php echo $lang['DRAFT_AUTOSAVED']; ?> ' + getTime;
									  		draftStatus = true;
										}
										else
										{
									  		$('draft_saved_msg_container').innerHTML = '<?php echo $lang['DRAFT_AUTOSAVED']; ?> ' + getTime;
										}
									}
									else if (request != '0')
									{
										if (draftStatus == false)
										{
									  		$('draft_saved_msg_container').innerHTML = '<?php echo $lang['DRAFT_AUTOSAVED']; ?> ' + getTime;
									  		draftStatus = true;
										}
										else
										{
									  		$('draft_saved_msg_container').innerHTML = '<?php echo $lang['DRAFT_AUTOSAVED']; ?> ' + getTime;
										}
									}
	  
									// Updating the cookie
									HashCookie.set('post_id', getDraftId());  
									HashCookie.save();                                          
								}
							}
						).request();
					}
				}
			).periodical(5000);
		}
	});
}
</script>
<!-- end draft save functionality -->
<style type="text/css">
<!--
.style3 {
	font-weight: bold;
}
-->
</style>
</head>

<body <?php if(isset($draft_info['draft_id'])){ ?> onLoad="draftLoad();"<? } ?>>
<table width="850" cellpadding="0" cellspacing="0" border="0" align="center" style="margin-top: 20px;">
  <tr>
    <td height="42" valign="middle" style="width: 899px; background: #88A2F0; color: #F1F1F1; font-size: 24px; font-weight: bold; padding: 10px;"><?php echo $config['site_title'] ;?> Admin CP</td>
  </tr>
  <tr>
    <td><img src="images/dot.gif" width="1" height="1" alt=""></td>
  </tr>
</table>
<!--End top-->
<!--Start topmenu-->
<table width="850" cellpadding="0" cellspacing="0" border="0" align="center">
  <tr>
    <td bgcolor="#F0F0F0" height="25" style="padding-left:20px;" id="menu"></td>
    <SCRIPT language="JavaScript" type="text/javascript">
			var myMenu =
				
			// Start the menu
[
<?php echo $nav; ?>
];				

			// Output the menu
			cmDraw ('menu', myMenu, 'hbr', cmThemeOffice, 'ThemeOffice');
			</SCRIPT>
  </tr>
  <tr>
    <td bgcolor="#333333"><img src="images/dot.gif" width="1" height="1" alt=""></td>
  </tr>
</table>
<!--End topmenu-->
<br>
<!--Start heading page-->
<table width="850" cellpadding="0" cellspacing="0" border="0" align="center">
  <tr>
    <td valign="middle" class="heading"><?=$lang['ADD'].' '.$lang['POST'];?></td>
  </tr>
  <tr>
    <td bgcolor="#333333"><img src="images/dot.gif" width="1" height="1" alt=""></td>
  </tr>
</table>
<!--End heading page-->
<!--Start form-->
<br>
<table width="850" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #CCCCCC;" align="center">
  <tr>
    <td bgcolor="#F6F6F6" style="padding:15px;"><form name="form2" id="form2_id" onClick="needToConfirm = false" method="post" action="post_add.php">
      <table width="800" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="390" height="25"><span class="style3"><?=$lang['POST'].' '.$lang['TITLE'];?></span></td>
                      <td width="20" height="25">&nbsp;</td>
                      <td width="390" height="25"><span class="style3"><?=$lang['POST'].' '.$lang['CATEGORY'];?></span></td>
                    </tr>
                    <tr>
                      <td><input name="subject" type="text" id="subject" style="width:100%;padding-left:3px;" maxlength="100" class="textbox"></td>
                      <td>&nbsp;</td>
                      <td><select name="cat" id="cat" style="width:100%;padding-left:3px;" class="textbox">
<?
$query = "SELECT cat_id,cat_title FROM ".$config['db']['pre']."cats ORDER BY cat_title";
$query_result = mysql_query($query);
while ($info = mysql_fetch_array($query_result))
{
?>
                        <option value="<? echo $info['cat_id']; ?>"><? echo $info['cat_title'];?></option>
<?
}
?>
                      </select></td>
                    </tr>
                  </table>                  </td>
              </tr>
            </table>
              <br>
              <textarea name="body" cols="70" rows="10" id="body" style="width:100%;height:300px;"></textarea>
				<script language="javascript1.2">
					generate_wysiwyg('body');
				</script>          </td>
        </tr>
        <tr>
          <td id="draft_saved_msg_container" style="padding-top: 5px;padding-bottom: 5px;">&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><input name="Submit" id="submit_id" type="submit" onSubmit="needToConfirm = false" class="button" value="<?=$lang['ADD'].' '.$lang['POST'];?>"></td>
        </tr>
      </table>
    </form></td>
  </tr>
</table>
<!--End form-->
<br>
<!--Start bottom-->
<table width="850" cellpadding="0" cellspacing="0" border="0" align="center">
  <tr>
    <td bgcolor="#333333"><img src="images/dot.gif" width="1" height="1" alt=""></td>
  </tr>
  <tr>
    <td style="padding:15px;" align="center"><span class="copyright">Copyright &copy; 2009-2010 <a href="http://vicblog.vichost.com" class="copyright" target="_blank">VicBlog Development</a> All Rights Reserved.</span></td>
  </tr>
</table>
</body>
</html>