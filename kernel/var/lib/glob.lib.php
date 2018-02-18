<?php
/**
*	Library Glob основная помойка 
*
* @author	Fedot B Pozdnyakov <9@u9.ru> 18.02.2018
* @package	SBIN Diesel
*/
class glob 
{
	private static $storage = array();
	
	public static function get($name)
	{
		if (!isset(self::$storage[$name]))
		{
			return false;
		}
		return self::$storage[$name];
	}

	public static function set($name,$value)
	{
		if($name != '')
		{
			self::$storage[$name] = $value;
		}
	}
}
?>
