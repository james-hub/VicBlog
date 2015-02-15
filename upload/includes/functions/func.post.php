<?php
function check_comment($config,$settings,$post_id)
{
	$return = array();
	$return['error'] = '';
	$return['valid'] = 0;
	
	$post_details = mysql_fetch_array(mysql_query("SELECT user_id FROM ".$config['db']['pre']."posts WHERE post_id='".validate_input($post_id)."' LIMIT 1"));

	if(isset($_SESSION['kbuser']['id']))
	{
		if($_SESSION['kbuser']['id'] == $post_details['user_id'])
		{
			$return['valid'] = 1;
			
			return $return;
		}
	}
	
	

	return $return;
}
?>