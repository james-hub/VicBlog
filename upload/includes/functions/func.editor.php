<?
function getFlickrPhotos($username,$per_page,$api_key,$page=1)
{
	$username = ltrim(rtrim($username));
	$username = urlencode($username);

	$url = "http://api.flickr.com/services/rest/?api_key=".$api_key."&method=flickr.people.findByUsername&username=".$username;

	if(function_exists('curl_init'))
	{
		$ch=curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data=curl_exec($ch);
		curl_close($ch);
	} 
	else
	{
		$data = file_get_contents($url);
	}
	
	$userid = readuserXML($data);
	
	if($userid)
	{
		$url = "http://api.flickr.com/services/rest/?api_key=".$api_key."&method=flickr.photos.search&user_id=".$userid."&page=".$page."&per_page=".$per_page;
		
		if(function_exists('curl_init'))
		{
			$ch=curl_init($url);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$data=curl_exec($ch);
			curl_close($ch);
		} 
		else
		{
			$data = file_get_contents($url);
		}
		
		$photos = readphotoXML($data);
		
		return $photos;
	}
	else
	{
		return -1;
	}
}

function getZooomrPhotos($userid,$per_page,$page=1)
{
	$userid = ltrim(rtrim($userid));
	$userid = urlencode($userid);
	$userid = str_replace('@Z01','',$userid);

	$url = "http://www.zooomr.com/services/feeds/public_photos/?id=".$userid."@Z01&format=rss_200";

	if(function_exists('curl_init'))
	{
		$ch=curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data=curl_exec($ch);
		curl_close($ch);
	} 
	else
	{
		$data = file_get_contents($url);
	}
	
	$photos = readZooomrphotoXML($data);
	
	return $photos;
}

function readuserXML($data)
{
	$parser = xml_parser_create();
	$tdb = array();
	xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
	xml_parse_into_struct($parser, $data, $values, $tags);

	if(isset($values[1]['attributes']['id']))
	{
		return $values[1]['attributes']['id'];
	}
	else
	{
		return 0;
	}
}

function readZooomrphotoXML($data)
{
	$parser = xml_parser_create();
	$tdb = array();
	xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
	xml_parse_into_struct($parser, $data, $values, $tags);

	$count = 0;
	$photos = array();
	$photos['photos'] = array();

	foreach ($values as $key=>$val)
	{
		if(($val['tag'] == 'item') AND ($val['type'] == 'close'))
		{
			$count++;
		}
		else
		{
			if( (isset($val['value'])) AND (isset($val['attributes'])) )
			{
				$photos['photos'][$count][$val['tag']]['value'] = $val['value'];
				$photos['photos'][$count][$val['tag']]['attributes'] = $val['attributes'];
			}
			elseif(isset($val['value']))
			{
				$photos['photos'][$count][$val['tag']] = $val['value'];
			}
			elseif(isset($val['attributes']))
			{
				$photos['photos'][$count][$val['tag']] = $val['attributes'];
			}
		}
	}
	
	return $photos;
}

function readphotoXML($data)
{
	$parser = xml_parser_create();
	$tdb = array();
	xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
	xml_parse_into_struct($parser, $data, $values, $tags);

	$photos = array();
	$photos['photos'] = array();
	$photos['info'] = array();
	$count = 0;

	foreach ($values as $key=>$val)
	{
		if(($val['tag'] == 'photos') AND ($val['type'] == 'open'))
		{
			$photos['info'] = $val['attributes'];
		}
		elseif($val['tag'] == 'photo')
		{
			$photos['photos'][$count] = $val['attributes'];
			$photos['photos'][$count]['thumb'] = 'http://farm'.$val['attributes']['farm'].'.static.flickr.com/'.$val['attributes']['server'].'/'.$val['attributes']['id'].'_'.$val['attributes']['secret'].'_s.jpg';
			$photos['photos'][$count]['full'] = 'http://farm'.$val['attributes']['farm'].'.static.flickr.com/'.$val['attributes']['server'].'/'.$val['attributes']['id'].'_'.$val['attributes']['secret'].'_m.jpg';
	
			$count++;		
		}
	}
	
	return $photos;
}
?>