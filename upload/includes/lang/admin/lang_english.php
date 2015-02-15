<?php
$lang = array();
$help = array();

// A
$lang['ACTION'] = 'Action';
$lang['ACTIVE'] = 'Active';
$lang['ADD'] = 'Add';
$lang['ADDCATEGORIES'] = 'Add Categories';
$lang['ADDCONTENT'] = 'Add Content';
$lang['ADDPOST'] = 'Add Post';
$lang['ADDUSERS'] = 'Add Users';
$lang['ADMIN'] = 'Admin';
$lang['ADMINCP'] = 'Admin CP';
$lang['ADMINISTRATOR'] = 'Administrator';
$lang['AUTHOR'] = 'Author';
$lang['AVAILABLE'] = 'Available';
$lang['LATEST_NEWS'] = 'Latest News From VicBlog Development';
// B
$lang['BY'] = 'by';
// C
$lang['CATEGORY'] = 'Category';
$lang['CATEGORIES'] = 'Categories';
$lang['COMMENT'] = 'Comment';
$lang['COMMENTS'] = 'Comments';
$lang['CONFIRM'] = 'Confirm';
$lang['CONTENT'] = 'Content';
$lang['COMVAL'] = 'comments to validate';
// D
$lang['DATABASE'] = 'Database';
$lang['DATE'] = 'Date';
$lang['DELETE'] = 'Delete';
$lang['DELETECONFIRM'] = 'Are you sure you want to delete the following';
$lang['DISABLED'] = 'Disabled';
$lang['DRAFT_AUTOSAVED'] = 'Draft auto-saved at ';
$lang['DRAFT_SAVED'] = 'Draft saved at ';
$lang['DRAFT_EXISTS_CONFIRM'] = 'A draft of a previous post exists, click OK to load this draft or cancel to delete it.';
// E
$lang['EDIT'] = 'Edit';
$lang['EDITCATEGORIES'] = 'Edit Categories';
$lang['EDITCOMMENTS'] = 'Edit Comments'; 
$lang['EDITCONTENT'] = 'Edit Content';
$lang['EDITPOST'] = 'Edit Post';
$lang['EDITUSERS'] = 'Edit Users';
$lang['EMAIL'] = 'Email';
$lang['ERROINV'] = 'Error: Username contains invalid characters';
$lang['ERROLO'] = 'Error: Username Too Long';
$lang['ERROMISS'] = 'Error: Username Missing';
$lang['ERROPMIS'] = 'Error: Password Missing';
$lang['ERROPLO'] = 'Error: Password Too Long';
$lang['ERROUPMIS'] = 'Error: Username & Password do not match';
// F
// G
// H
$lang['HOST'] = 'Host';
// I
$lang['ID'] = 'ID';
$lang['IGNORE'] = 'Ignore';
$lang['IMSURE'] = 'Yes I\'m Sure';
// J
// K
// L
$lang['LOGOUT'] = 'Logout';
// M
$lang['MODERATOR'] = 'Moderator';
// N
$lang['NAME'] = 'Name';
$lang['NEEDHELP'] = 'Need more help?, &nbsp;<a href="http://vicblog.vichost.com" target="_new">Just Ask</a>';
$lang['NO'] = 'No';
$lang['NOHELP'] = 'No Help Text Available for this topic';
$lang['NOHELPMSG'] = 'No Help Text Available for this topic. Please contact <a href="http://vicblog.vichost.com" target="_new">VicBlog Development</a>';
// O
$lang['OF'] = 'of';
$lang['OPTIONS'] = 'Options';
// P
$lang['PASSCONFIRM'] = 'Passwords do not match';
$lang['PASSWORD'] = 'Password';
$lang['PLEASEPOST'] = 'Please enter a post';
$lang['PLEASETITLE'] = 'Please enter a title';
$lang['PREFIX'] = 'Prefix';
$lang['POST'] = 'Post';
$lang['POSTCOUNT'] = 'Post Count';
$lang['POSTED'] = 'Posted';
$lang['POSTS'] = 'Posts';
// Q
// R
$lang['RESET'] = 'Reset';
$lang['RESULTS'] = 'result(s)';
// S
$lang['SAVE'] = 'Save';
$lang['SELECITEM'] = 'Please Select an item';
$lang['SETTINGS'] = 'Settings';
$lang['SHOWING'] = 'Showing';
$lang['SUBMIT'] = 'Submit';
$lang['STATUS'] = 'Status';
// T
$lang['TEMPLATESET'] = 'Template Settings';
$lang['TITLE'] = 'Title';
$lang['TICKETS'] = 'Tickets';
$lang['TO'] = 'to';
$lang['TYPE'] = 'Type';
// U
$lang['USER'] = 'User';
$lang['USERALPHA'] = 'alpanumeric characters';
$lang['USERLENGTH'] = 'between 4 - 15 characters';
$lang['USERGROUP'] = 'Usergroup';
$lang['USERNAME'] = 'Username';
$lang['USERPASS'] = 'Username is Password';
$lang['USERS'] = 'Users';
$lang['USERTAKEN'] = 'Sorry, Username taken';
// V
$lang['VALCOMMENTS'] = 'Validate Comments';
$lang['VALIDATE'] = 'Validate';
$lang['VIEW'] = 'View';
$lang['VIEWTICK'] = 'View Tickets';
// W
$lang['WELCOME'] = 'Welcome';
$lang['WITHSELECTED'] = 'With Selected';
// X
// Y
// Z

// Admin Help Text

// database.php
$help[1]['subject'] = $lang['DATABASE']." ".$lang['HOST'];
$help[1]['message'] = '';
$help[2]['subject'] = $lang['DATABASE']." ".$lang['NAME'];
$help[2]['message'] = '';
$help[3]['subject'] = $lang['DATABASE']." ".$lang['USERNAME'];
$help[3]['message'] = '';
$help[4]['subject'] = $lang['DATABASE']." ".$lang['PASSWORD'];
$help[4]['message'] = '';
$help[5]['subject'] = $lang['DATABASE']." ".$lang['PREFIX'];
$help[5]['message'] = '';

// users_add.php
$help[6]['subject'] = $lang['USERNAME'];
$help[6]['message'] = '';
$help[7]['subject'] = $lang['PASSWORD'];
$help[7]['message'] = '';
$help[8]['subject'] = $lang['CONFIRM']." ".$lang['PASSWORD'];
$help[8]['message'] = '';
$help[9]['subject'] = $lang['USER']." ".$lang['TYPE'];
$help[9]['message'] = '';
$help[10]['subject'] = $lang['STATUS'];
$help[10]['message'] = '';
?>