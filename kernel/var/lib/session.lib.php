<?php
/**
*	The library for operations with sessions
*
* @author	Anthon S. Litvinenko <crazyfluger@gmail.com>
* @version	1.0
* @access	public
* @package	CFsCMS2(PE)
*/
class session
{
	/**
	*	Set the session`s variables
	* @param	string|array	$name	The name or array of names of variables
	* @param	string|array	$value	The value or array of values of variables
	* @param	string		$scope	The scope to store data
	*/
	public static function set($name = NULL, $value = NULL, $scope = NULL)
	{
		// NOTE: If $name is array and $value not given
		if (is_array($name) &&  $value === NULL)
			foreach ($name as $var_name => $var_value)
				if ($scope === NULL)
					$_SESSION[$var_name] = $var_value;
				else
					$_SESSION[$scope][$var_name] = $var_value;
		// NOTE: If $name is array and $value is array
		else if (is_array($name) && is_array($value))
			foreach ($name as $n => $var_name)
				if ($scope === NULL)
					$_SESSION[$var_name] = $value[$n];
				else
					$_SESSION[$scope][$var_name] = $value[$n];
		// NOTE: If $name is array and $value is given
		else if (is_array($name))
			foreach ($name as $var_name)
				if ($scope === NULL)
					$_SESSION[$var_name] = $value;
				else
					$_SESSION[$scope][$var_name] = $value;
		// NOTE: If given $name and $value
		else
			if ($scope === NULL)
				$_SESSION[$name] = $value;
			else
				$_SESSION[$scope][$name] = $value;
	}
	
	/**
	*	Get the values stored in session
	* @param	string|array	$name		The name or array of names of variables
	* @param	string|array	$default	The default value or array of default values
	* @param	string		$scope		The scope to store data
	* @return	mixed		Value(s) stored in $_SESSION
	*/
	public static function get($name = NULL, $default = NULL, $scope = NULL)
	{
		// If name not given
		if ($name === NULL)
			if ($scope === NULL)
				return $_SESSION;
			else
				return $_SESSION[$scope];
		// If name given as array
		else if (is_array($name))
		{
			$ret = array();
			foreach ($name as $n => $var_name)
			{
				if ($scope === NULL && isset($_SESSION[$var_name]))
					$ret[$var_name] = $_SESSION[$var_name];
				else if ($scope !== NULL && isset($_SESSION[$scope][$var_name]))
					$ret[$var_name] = $_SESSION[$scope][$var_name];
				else if (is_array($default) && isset($default[$var_name]))
					$ret[$var_name] = $default[$var_name];
				else if (is_array($default) && isset($default[$n]))
					$ret[$var_name] = $default[$n];
				else if ($default)
					$ret[$var_name] = $default;
			}
			return $ret;
		}
		// If scope name not given and session variable is setted
		else if ($scope === NULL && isset($_SESSION[$name]))
			return $_SESSION[$name];
		// If scope name given and session variable is setted
		else if ($scope !== NULL && isset($_SESSION[$scope][$name]))
			return $_SESSION[$scope][$name];
		// Else return default value
		else
			return $default;
	}
	
	/**
	*	Remove data stored in $_SESSION
	* @param	string|array	$name		The name or array of names of variables
	* @param	string		$scope		The scope to store data
	*/
	public static function del($name, $scope = NULL)
	{
		// If name given as array
		if (is_array($name))
			foreach ($name as $var_name)
				if ($scope === NULL)
					unset($_SESSION[$var_name]);
				else
					unset($_SESSION[$scope][$var_name]);
				
		else
			if ($scope === NULL)
				unset($_SESSION[$name]);
			else
				unset($_SESSION[$scope][$name]);
	}
}
?>
