<?php
class Utils
{
	static function getIndex($index, $val=null)
	{
		if (isset($_GET[$index]))
			return $_GET[$index];
		else return $val;	
	}	
	
	static function postIndex($index)
	{
		if (isset($_POST[$index]))
			return $_POST[$index];
		else return null;	
	}	
	
	static function isEmail($s)
	{
		
	}
	
	static function isWebsite($s)
	{
		
	}
	static function isStrongPassword($s)
	{
		
	}
}

?>