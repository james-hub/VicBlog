<?php
function create_header($config,$lang,$cats=array(),$page_title='')
{
	$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/overall_header.html");
	$page->SetParameter('SITE_TITLE', $config['site_title']);
	$page->SetParameter('PAGE_TITLE', $page_title);
	$page->SetParameter('TPL_NAME', $config['tpl_name']);
	$page->SetParameter('SITE_URL', $config['site_url']);
	$page->SetParameter('RSS_FEED', $config['site_url'].'feed.php');
	$page->SetLoop('CATS', $cats);
	return $page->CreatePageReturn($lang,$config);
}

function create_footer($config,$lang)
{
	$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/overall_footer.html");
	$page->SetParameter('VERSION',$config['version']);
	return $page->CreatePageReturn($lang,$config);
}

function db_connect($config)
{
	$db_connection = @mysql_connect ($config['db']['host'], $config['db']['user'], $config['db']['pass']) OR error (mysql_error(), __LINE__, __FILE__, 0, '', '');
	$db_select = @mysql_select_db ($config['db']['name']) or error (mysql_error(), __LINE__, __FILE__, 0, '', '');

	return $db_connection;
}

function error($msg, $line='', $file='', $formatted=0,$lang=array(),$config=array())
{
	if($formatted == 0)
	{
		echo "Low Level Error: " . $msg;
	}
	else
	{
		$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/error.html");
		$page->SetParameter ('OVERALL_HEADER', create_header($lang,$config['tpl_name'],'Error'));
		$page->SetParameter ('MESSAGE', $msg);
		$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
		$page->CreatePageEcho($lang,$config);
	}
	exit;
}

function email($email_to,$email_subject,$email_body,$config,$bcc=array())
{
	$mail = new PHPMailer();

	$mail->CharSet="utf-8";
	
	if($config['email']['type'] == 'smtp')
	{
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->Username = $config['email']['smtp']['user'];
		$mail->Password = $config['email']['smtp']['pass'];
		$mail->Host = $config['email']['smtp']['host'];
	}
	elseif ($config['email']['type'] == 'sendmail')
	{
		$mail->IsSendmail();
	}
	else
	{
		$mail->IsMail();
	}
	
	$mail->FromName = $config['site_title'];
	$mail->From = $config['admin_email'];
	
	if(count($bcc) > 0)
	{
		$counter = 0;
		
		foreach ($bcc as $value) 
		{
			if($counter == 0)
			{
				$mail->AddAddress($value);
			}
			else
			{
				$mail->AddBCC($value);
			}
			$counter++;
		}
	}
	else
	{
		$mail->AddAddress($email_to);
	}
	
	$mail->Subject = $email_subject;
	$mail->Body = $email_body;
	
	$mail->IsHTML(false);
	
	$mail->Send();
}

function message($message,$config,$lang,$forward='',$back=true)
{
	if($forward == '')
	{
		if($back)
		{
			$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/message.html");
			$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,'Message'));
			$page->SetParameter ('MESSAGE', $message);
			$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
			$page->CreatePageEcho($lang,$config);
		}
		else
		{
			$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/message_noback.html");
			$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,'Message'));
			$page->SetParameter ('MESSAGE', $message);
			$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
			$page->CreatePageEcho($lang,$config);
		}
	}
	else
	{
		$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/message_forward.html");
		$page->SetParameter ('OVERALL_HEADER', create_header($config,$lang,'Message'));
		$page->SetParameter ('MESSAGE', $message);
		$page->SetParameter ('FORWARD', $forward);
		$page->SetParameter ('OVERALL_FOOTER', create_footer($config,$lang));
		$page->CreatePageEcho($lang,$config);
	}
	exit;
}

function checkinstall($config)
{
	if(!isset($config['installed']))
	{
		header("Location: install/");
		exit;
	}
}

function transfer($config,$url,$msg)
{
	if(!$config['transfer_filter'])
	{
		header("Location: ".$url);
		exit;
	}

	ob_start();
	echo "<html>\n";
	echo "<head>\n";
	echo "<title>Admin CP - Powered by VicBlog</title>\n";
	echo "<STYLE>\n";
	echo "<!--\n";
	echo "TABLE, TR, TD                { font-family:Verdana, Tahoma, Arial;font-size: 7.5pt; color:#000000}\n";
	echo "a:link, a:visited, a:active  { text-decoration:underline; color:#000000 }\n";
	echo "a:hover                      { color:#465584 }\n";
	echo "#alt1   { background-color: #EFEFEF  }\n";
	echo "body {\n";
	echo "	background-color: #FFFFFF;\n";
	echo "}\n";
	echo "-->\n";
	echo "</STYLE>\n";
	echo "<script language=\"JavaScript\" type=\"text/javascript\">\n";
	echo "function changeurl(){\n";
	echo "window.location='" . $url . "';\n";
	echo "}\n";
	echo "</script>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"></head>\n";
	echo "<body onload=\"window.setTimeout('changeurl();',2000);\">\n";
	echo "<table width='95%' height='85%'>\n";
	echo "<tr>\n";
	echo "<td valign='middle'>\n";
	echo "<table align='center' border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"#FF9900\">\n";
	echo "<tr>\n";
	echo "<td id='mainbg'>";
	echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"12\">\n";
	echo "<tr>\n";
	echo "<td width=\"100%\" align=\"center\" id=alt1>\n";
	echo $msg . "<br><br>\n";
	echo "Please wait while we transfer you...<br><br>\n";
	echo "(<a href='" . $url . "'>Or click here if you are too lazy to wait</a>)</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</body></html>\n";
	ob_end_flush();
}

function encode_ip($_SERVER,$_ENV)
{
	if( getenv('HTTP_X_FORWARDED_FOR') != '' )
	{
		$client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : $REMOTE_ADDR );
	
		$entries = explode(',', getenv('HTTP_X_FORWARDED_FOR'));
		reset($entries);
		while (list(, $entry) = each($entries)) 
		{
			$entry = trim($entry);
			if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list) )
			{
				$private_ip = array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/', '/^10\..*/', '/^224\..*/', '/^240\..*/');
				$found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
	
				if ($client_ip != $found_ip)
				{
					$client_ip = $found_ip;
					break;
				}
			}
		}
	}
	else
	{
		$client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : $REMOTE_ADDR );
	}
	
	return $client_ip;
}

function time_taken($time)
{
	if($time > 86400)
	{
		$days = floor($time/86400);
		$hours = floor(($time-($days*86400))/3600);
		
		if($days > 1)
		{
			$took = $days . ' days';
		}
		else
		{
			$took = $days . ' day';
		}
	}
	elseif($time > 3600)
	{
		$hours = floor(($time/60)/60);
		$mins = floor(($time-($hours*3600))/60);
		
		if($hours > 1)
		{
			$took = $hours.' hours';
		}
		else
		{
			$took = $hours.' hour';
		}
	}
	elseif($time > 60)
	{
		$mins = floor($time/60);
	
		$took = $mins . ' minutes';
	}
	else
	{
		$took = $time . ' seconds';
	}
	
	return $took;
}

function getrandnum($length)
{
	$randstr=''; 
	srand((double)microtime()*1000000); 
	$chars = array ( 'a','b','C','D','e','f','G','h','i','J','k','L','m','N','P','Q','r','s','t','U','V','W','X','y','z','1','2','3','4','5','6','7','8','9'); 
	for ($rand = 0; $rand <= $length; $rand++) 
	{ 
		$random = rand(0, count($chars) -1); 
		$randstr .= $chars[$random]; 
	}
	
	return $randstr;
}

function pagenav($total,$page,$perpage,$url,$posts=0,$window=5) 
{
	$page_arr = array();
	$arr_count = 0;

	if($posts) 
	{
		$symb='&';
	}
	else
	{
		$symb='?';
	}
	$total_pages = ceil($total/$perpage);
	$llimit = 1;
	$rlimit = $total_pages;
	$html = '';
	if ($page<1 || !$page) 
	{
		$page=1;
	}
	
	if(($page - floor($window/2)) <= 0)
	{
		$llimit = 1;
		if($window > $total_pages)
		{
			$rlimit = $total_pages;
		}
		else
		{
			$rlimit = $window;
		}
	}
	else
	{
		if(($page + floor($window/2)) > $total_pages) 
		{
			if ($total_pages - $window < 0)
			{
				$llimit = 1;
			}
			else
			{
				$llimit = $total_pages - $window + 1;
			}
			$rlimit = $total_pages;
		}
		else
		{
			$llimit = $page - floor($window/2);
			$rlimit = $page + floor($window/2);
		}
	}
	if ($page>1)
	{
		$page_arr[$arr_count]['title'] = 'Prev';
		$page_arr[$arr_count]['link'] = $url.$symb.'page='.($page-1);
		$page_arr[$arr_count]['current'] = 0;
		
		$arr_count++;
	}

	for ($x=$llimit;$x <= $rlimit;$x++) 
	{
		if ($x <> $page) 
		{
			$page_arr[$arr_count]['title'] = $x;
			$page_arr[$arr_count]['link'] = $url.$symb.'page='.($x);
			$page_arr[$arr_count]['current'] = 0;
		} 
		else 
		{
			$page_arr[$arr_count]['title'] = $x;
			$page_arr[$arr_count]['link'] = $url.$symb.'page='.($x);
			$page_arr[$arr_count]['current'] = 1;
		}
		
		$arr_count++;
	}
	
	if($page < $total_pages)
	{
		$page_arr[$arr_count]['title'] = 'Next';
		$page_arr[$arr_count]['link'] = $url.$symb.'page='.($page+1);
		$page_arr[$arr_count]['current'] = 0;
		
		$arr_count++;
	}
	
	return $page_arr;
}

function validate_input($input,$dbcon=true,$content='all',$maxchars=0)
{
	if(get_magic_quotes_gpc()) 
	{
		if(ini_get('magic_quotes_sybase')) 
		{
			$input = str_replace("''", "'", $input);
		} 
		else 
		{
			$input = stripslashes($input);
		}
	}
	
	if($content == 'alnum')
	{
		$input = ereg_replace("[^a-zA-Z0-9]", '', $input);
	}
	elseif($content == 'num')
	{
		$input = ereg_replace("[^0-9]", '', $input);
	}
	elseif($content == 'alpha')
	{
		$input = ereg_replace("[^a-zA-Z]", '', $input);
	}
	
	if($maxchars)
	{
		$input = substr($input,0,$maxchars);
	}

	if($dbcon)
	{
		$input = mysql_real_escape_string($input);
	}
	else
	{
		$input = mysql_escape_string($input);
	}
	
	return $input;
}
function makeInt ($x,$signed=false) 
{
	if(!is_numeric($x))
	{
   		$x = intval($x);
	}
	
	if(!$x)
	{
		$x=1;
	}
	
	if(!$signed)
	{
		if($x<1)
		{
			$x=1;
		}
	}
	
	return $x;
}

function get_cats($config)
{
	$cats = array();
	
	$query = "SELECT cat_id,cat_title,cat_count FROM ".$config['db']['pre']."cats ORDER BY cat_title ASC";
	$query_result = @mysql_query ($query) OR error(mysql_error(), __LINE__, __FILE__, 0, '', '');
	while ($info = @mysql_fetch_array($query_result))
	{
		$cats[$info['cat_id']]['cat_id'] = $info['cat_id'];
		$cats[$info['cat_id']]['cat_title'] = $info['cat_title'];
		$cats[$info['cat_id']]['cat_count'] = $info['cat_count'];
	}
	
	return $cats;
}

function get_archive($config,$lang)
{
	$archive = array();
	$count = 0;
	
	$month_names = array();
	$month_names[1] = 'JANUARY';
	$month_names[2] = 'FEBRUARY';
	$month_names[3] = 'MARCH';
	$month_names[4] = 'APRIL';
	$month_names[5] = 'MAY';
	$month_names[6] = 'JUNE';
	$month_names[7] = 'JULY';
	$month_names[8] = 'AUGUST';
	$month_names[9] = 'SEPTEMBER';
	$month_names[10] = 'OCTOBER';
	$month_names[11] = 'NOVEMBER';
	$month_names[12] = 'DECEMBER';
	
	$query = "SELECT archive_month,archive_year FROM ".$config['db']['pre']."archive ORDER BY archive_year,archive_month";
	$query_result = @mysql_query ($query) OR error(mysql_error(), __LINE__, __FILE__, 0, '', '');
	while ($info = @mysql_fetch_array($query_result))
	{
		$archive[$count]['month'] = $info['archive_month'];
		$archive[$count]['month_text'] = $lang[$month_names[$info['archive_month']]];
		$archive[$count]['year'] = $info['archive_year'];
		
		$count++;
	}
	
	return $archive;
}

function get_links($config)
{
	$links = array();
	$count = 0;
	
	$query = "SELECT link_id,link_type,content_id,link_url,link_title FROM ".$config['db']['pre']."links ORDER BY link_order ASC";
	$query_result = @mysql_query ($query) OR error(mysql_error(), __LINE__, __FILE__, 0, '', '');
	while ($info = @mysql_fetch_array($query_result))
	{
		$links[$count]['link_id'] = $info['link_id'];
		$links[$count]['link_type'] = $info['link_type'];
		$links[$count]['content_id'] = $info['content_id'];
		$links[$count]['link_url'] = $info['link_url'];
		$links[$count]['link_title'] = $info['link_title'];
		
		if($info['link_type'] == '1')
		{
			$links[$count]['link'] = 'content.php?i='.$info['content_id'];
		}
		elseif($info['link_id'] == '2')
		{
			$links[$count]['link'] = $info['content_url'];
		}
		
		$count++;
	}
	
	return $links;
}

function get_settings($page,$config)
{
	$settings = array();
	
	$query = "SELECT setting_id,setting_name,setting_value FROM ".$config['db']['pre']."settings WHERE setting_file='' OR setting_file='".validate_input($page)."'";
	$query_result = @mysql_query ($query) OR error(mysql_error(), __LINE__, __FILE__, 0, '', '');
	while ($info = @mysql_fetch_array($query_result))
	{
		$settings[$info['setting_name']] = $info['setting_value'];
	}
	
	return $settings;
}
?>