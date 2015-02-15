<?php 
/********************************** CHECK FOR BLANK ********************************/
/* pass the object and id of the next cell	*/ 


function check_Blank($myfld,$field_name)
{
	if(strlen(trim($myfld))==0)
		//return $field_name . " is not entered<br>";
		//return $field_name . " is required<br />" ;
		return "Enter " . $field_name . "" ;  
	return;
}
/********************************** CHECK FOR EMAIL ********************************/
/* pass the email address	*/

function check_Email($sEmailAddress,$field_name)
{
	if (ord(check_Length($sEmailAddress,"7","75"))==0)
	{	
		 if (!eregi("^[-_a-z0-9]+(\.[-_a-z0-9-]+)*@[-_a-z0-9]+(\.[a-z0-9]+)*(\.[a-z]{2,3})$",$sEmailAddress))
		{
			return "Enter Valid " .$field_name;
		}		
		return "";
	} else {
//		return $field_name . " must be minimum of 7 and maximum of 40 character<br>";
		return "Enter Valid " .$field_name;		 
		
	}
}

/********************************** CHECK FOR MIMINUM & MAXIMUM LENGHT ********************************/
/* required to pass object and the id of next cell where you want to print error 	
	minValue	:	number of minimum character
	maxValue	:	number of maximum character
*/ 

function check_Length($myfld, $minValue, $maxValue, $field_name="")
{	
	  if ( strlen($myfld) >= $minValue && strlen($myfld)<=$maxValue )
		 return "";
	  else 
	  	//return $field_name . " must be between " . $minValue ." and " . $maxValue ." character<br>";
			return $field_name . " must be minimum of " . $minValue ." and maximum of " . $maxValue ." characters<br>";
}


/********************************** COMBO CHECK ********************************/

function check_Combo($myfld, $field_name, $value)
{	
	  if ($myfld!=$value)
		 return "";
	  else 
	  	return "Select " .$field_name;
}
?>

