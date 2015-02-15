<?php
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'; 	

	include("validation.php");

	$user_email	=	$_POST["user_email"];
	$user_password	=	$_POST["user_password"];
	$err = array();
	$err["user_email"]		=	check_Email($user_email,"Email");
	$err["user_password"]	=	check_Blank($user_password,"Password");

	if(strlen($err["user_email"])==0 && strlen($err["user_password"])==0) 
	{
		if($user_email=="admin@admin.com" && $user_password=="admin")
		{
			echo	'<error><result_flag><result>true</result></result_flag>';
			echo		'<error_field>';
				echo	"<login_failed>Susscefully Login</login_failed>";
			echo		'</error_field>	';		
			echo	'</error>';
		}
		else
		{
			echo	'<error><result_flag><result>false</result></result_flag>';
			echo		'<error_field>';
				echo	"<login_failed>Email: admin@admin.com == Password: admin</login_failed>";
			echo		'</error_field>	';		
			echo	'</error>';
		}
	}
	else
	{
		echo	'<error><result_flag><result>false</result></result_flag>';
		echo		'<error_field>';
		while(list($key, $value) = each($err)) {
			if(strlen($value)<>0) {
				echo			"<".$key.">".$value."</".$key.">";
			}
		}
		echo		'</error_field>	';		
		echo	'</error>';
	}



?>