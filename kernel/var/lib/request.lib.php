<?php
/**
*	Работа с данными которые пришли в запросе
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @version	1.0
* @access	public
* @package	CFsCMS2(PE)
* @since	2008-12-09
*/
class request
{
	public static function get($name = NULL, $default = NULL)
	{
		if (!$name)
			return $_REQUEST;
		else if (is_array($name))
		{
			$ret = array();
			foreach ($name as $n => $var_name)
			{
				if (isset($_REQUEST[$var_name]))
					$ret[$var_name] = $_REQUEST[$var_name];
				else if (is_array($default) && isset($default[$var_name]))
					$ret[$var_name] = $default[$var_name];
				else if (is_array($default) && isset($default[$n]))
					$ret[$var_name] = $default[$n];
				else if ($default)
					$ret[$var_name] = $default;
			}
			return $ret;
		}
		else if (isset($_REQUEST[$name]))
			return $_REQUEST[$name];
		else
			return $default;
	}
	
	public static function prepare()
	{
		if ($_SERVER['CONTENT_TYPE'] && preg_match('/charset=([^;]*)/', $_SERVER['CONTENT_TYPE'], $matches))
			$encoding = strtoupper($matches[1]);
		else
			$encoding = ENCODING;
		
		$urlencoded = strpos($_SERVER['CONTENT_TYPE'], 'urlencoded');
		
		foreach ($_REQUEST as $var => $val)
		{
			$value = $val;
			if ($urlencoded)
			{
				if (!is_array($value))
					$value = urldecode($value);
				else
					foreach ($value as $i => $v)
						$value[$i] = urldecode($v);
			}
			if ($encoding AND $encoding != ENCODING) $value = iconv($encoding, ENCODING, $value);
			$_REQUEST[$var] = $value;
		}
	}

	public static function json2int($value)
	{
		return intval(preg_replace("/[^0-9]+/", "", $value));
	}
}
?>
