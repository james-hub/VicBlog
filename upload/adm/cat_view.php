<?php
require_once('../includes/config.php');
require_once('../includes/functions/func.global.php');
require_once('../includes/functions/func.admin.php');
require_once('../includes/lang/admin/lang_'.$config['lang'].'.php');
require_once('class.menu.php');

db_connect($config);

if(!isset($_GET['pageno']))
{
	$pageno = 1;
}
else
{
	$pageno = $_GET['pageno'];
}

$obj = new Menu();
$nav = $obj->get_js_menu(0,$config,$lang);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $config['site_title'].' '.$lang['ADMIN'].' - '.$lang['EDIT'].' '.$lang['CATEGORIES']; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="css/main.css">
<link rel="stylesheet" type="text/css" href="images/style.css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/JSCookMenu.js"></SCRIPT>
<LINK REL="stylesheet" HREF="menu/themes/Office/theme.css" TYPE="text/css">
<SCRIPT LANGUAGE="JavaScript" SRC="menu/themes/Office/theme.js"></SCRIPT>

<script language="JavaScript">
<!--
function checkBoxes(){
  var dml = document.f1;
  var len = dml.elements.length;
  var i=0;
  var a=0;
  for(i=0;i<len;i++) {
      if (dml.elements[i].name=='list[]'){
          if(dml.elements[i].checked==true){
            dml.submit();
            return true;
          }
      }
  }
  <?php echo "alert('".$lang['SELECITEM'].".'); \n"; ?>
}
function checkBox(theBox){
  var dml = document.f1;
  var len = dml.elements.length;
  var i=0;
  for( i=0 ; i<len ; i++) {
      if (dml.elements[i].name=='list[]') {
          dml.elements[i].checked=theBox.checked;
      }
  }
}
function init(){
  var theForm = document.f1;
  var aBox = theForm["list[]"];
  var selAll = false;
  var i;
  for(i=0;i<aBox.length;i++){
    if(aBox[i].checked==false) selAll=true;
    aBox[i].onclick = function(){checkBox(this)};
  }
  //theForm.selall.checked = selAll;
}

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
</head>

<body>
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
    <td valign="middle" class="heading"><?php echo $lang['EDITCATEGORIES']; ?></td>
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
    <td align="center" bgcolor="#F6F6F6" style="padding:15px;"><div align="right">
        <form action="cat_delete.php" method="post" name="f1" id="f1">
          <div align="right"> <br>
          </div>
          <table width="99%" border="0" align="center" cellpadding="2" cellspacing="0">
            <tr bgcolor="#88A2F0">
              <td width="50" height="30"><div align="center">
                  <input type="checkbox" name="selall" value="checkbox" onClick="checkBox(this)">
              </div></td>
              <td width="220" height="30"><span class="rowheader"><?php echo $lang['CATEGORY'].' '.$lang['NAME']; ?></span></td>
              <td width="220" height="30"><span class="rowheader"><?php echo $lang['POSTCOUNT']; ?></span></td>
            <td width="150" height="30"><span class="rowheader"><?php echo $lang['OPTIONS']; ?></span></td>
            </tr>
            <tr bgcolor="#000000">
              <td height="1" colspan="6" style="padding:0px;"></td>
            </tr>
<?php
$count = 0;
$counter = 0;

$cats = get_cats($config);

//Pagination Continued
$query = "SELECT 1 FROM ".$config['db']['pre']."cats";
$result = mysql_query($query);
$numrows = mysql_num_rows($result);
$lastpage = ceil($numrows/10);
if ($pageno < 1)
{
	$pageno = 1;
}
elseif($pageno > $lastpage)
{
	$pageno = $lastpage;
}
if ($pageno == 0){$pageno++;}
$limit = 'LIMIT '.(($pageno-1)*10) .',10';

$posts = array();
$author_where = '';

$query = "SELECT cat_id, cat_title, cat_count FROM ".$config['db']['pre']."cats ORDER BY cat_id ASC ".$limit;
$query_result = mysql_query($query);
while ($info = mysql_fetch_array($query_result))
{
	$counter++;
	if($count == 0)
	{
		$colour = '#F7F7F7';
		$count = 1;
	}
	else
	{
		$colour = '#EFEFEF';
		$count = 0;
	}
?>
            <tr bgcolor="<?php echo $colour; ?>">
              <td width="50" height="25" align="center"><input type="checkbox" name="list[]" id="list[]" value="<?php echo $info['cat_id']; ?>"></td>
              <td height="25"><span class="style5"><?php echo $info['cat_title']; ?></span></td>
              <td height="25"><span class="style5"><?php echo $info['cat_count']; ?></span></td>
              <td height="25"><table border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td><select name="amenu<?php echo $info['cat_id']; ?>" id="amenu<?php echo $info['cat_id']; ?>" onChange="MM_jumpMenu('parent',this,0)" style="width:145px">
                        <option value=""><?php echo $lang['OPTIONS']; ?></option>
                        <option value="cat_edit.php?id=<?php echo $info['cat_id']; ?>"><?php echo $lang['VIEW']."/".$lang['EDIT'].' '.$lang['CATEGORY']; ?></option>
                        <option value="cat_delete.php?id=<?php echo $info['cat_id']; ?>"><?php echo $lang['DELETE'].' '.$lang['CATEGORY']; ?></option>
                      </select>
                    </td>
                  </tr>
              </table></td>
            </tr>
<?php } ?>
            <tr bgcolor="#000000">
              <td height="1" colspan="6" style="padding:0px;"></td>
            </tr>
          </table>
          <div align="left"> <br>
              <table width="99%"  border="0" align="center" cellpadding="2" cellspacing="0">
                <tr>
                  <td width="200" valign="middle"><?php echo $lang['WITHSELECTED']; ?>:&nbsp;<a href="#" onclick="document.f1.action='cat_edit.php'; checkBoxes();"><img src="images/button_edit.gif" width="12" height="13" border="0"></a>
                  <a href="#" onclick="checkBoxes();"><img src="images/button_empty.gif" width="11" height="13" border="0"></a></td>
                  <td valign="middle"><?php
if($numrows==0)
{
	$st=0;
	$en=0;
}
elseif($lastpage==$pageno)
{
	$st=$numrows-$counter+1;
	$en=$numrows;
}
else
{
	$st=((($pageno-1)*10)+1);
	$en=$counter*$pageno;
}
?>
                      <div align="center"><?php echo $lang['SHOWING']; ?> <?php echo $st; ?>-<?php echo $en; ?> <?php echo $lang['OF']; ?> <?php echo $numrows; ?> <?php echo $lang['RESULTS']; ?></div></td>
                  <td width="200" valign="middle"><div align="right">
                      <?php
if ($pageno != 1 AND $numrows!=0) 
{
   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=1'>&lt;&lt;</a> ";
   $prevpage = $pageno-1;
   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$prevpage'>&lt;</a> ";
}
echo " ( Page $pageno of $lastpage ) ";

if ($pageno != $lastpage AND $numrows!=0) 
{
   $nextpage = $pageno+1;
   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$nextpage'>&gt;</a> ";   
   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$lastpage'>&gt;&gt;</a> ";
}
?>
                  </div></td>
                </tr>
              </table>
          </div>
        </form>
    </div></td>
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