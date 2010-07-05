<?php
/**
*	Main initialization
*
* @author	Anthon S. Litvinenko <crazyfluger@gmail.com>
* @version	2.0
* @access	public
* @package	CFsCMS2(PE)
*/

/**
*	Autoloading data and user interfaces, libraries, connectors and base classes
* @param		string	$class_name	The class name
*/
function __autoload($class_name)
{
	if (!$class_name) return FALSE;
	
	if (file_exists(CLASS_PATH . $class_name . CLASS_FEXT))
	{
		$file_name = CLASS_PATH . $class_name . CLASS_FEXT;
	}
	else if (preg_match('/^' . UI_CLASS_PREFIX . '(\w+)/', $class_name, $matches))
	{
		$file_name = UI_PATH . $matches[1] . '/' . $matches[1] . UI_FEXT;
		$file_name_instance = INSTANCE_UI_PATH . $matches[1] . '/' . $matches[1] . UI_FEXT;
	}
	else if (preg_match('/^' . DI_CLASS_PREFIX . '(\w+)/', $class_name, $matches))
	{
		$file_name = DI_PATH . $matches[1] . DI_FEXT;
		$file_name_instance = INSTANCE_DI_PATH . $matches[1] . DI_FEXT;
	}
	else if (preg_match('/^' . CONNECTOR_CLASS_PREFIX . '(\w+)/', $class_name, $matches))
	{
		$file_name = CONNECTOR_PATH . $matches[1] . CONNECTOR_FEXT;
	}
	else if (file_exists(LIB_PATH . $class_name . LIB_FEXT))
	{
		$file_name = LIB_PATH . $class_name . LIB_FEXT;
	}

// NOTE: 9* 05072010 including  from kernel if exists, else trying to find this one in instance 
	if (file_exists($file_name))
	{
		include_once($file_name);
	}	
	else if(file_exists($file_name_instance))
	{
		include_once($file_name_instance);
	}
}

// NOTE: Include data bases configurations
include_once(CONF_PATH . 'db' . CONF_FEXT);

// NOTE: Include file storages configurations
include_once(CONF_PATH . 'fs' . CONF_FEXT);

// NOTE:9* 05072010 Include current theme  configuration
include_once(CONF_PATH . 'theme' . CONF_FEXT);

// NOTE:9* 050702010 Include current instance  configuration
include_once(CONF_PATH . 'instance' . CONF_FEXT);

// NOTE:9* 05072010 Include current instance  configuration
include_once(CONF_PATH . 'site' . CONF_FEXT);

// NOTE: Include default localization file
include_once(LOCALES_PATH . 'default.php');

// NOTE: Include localization file
$lc_file = LOCALES_PATH . LANG . '.' . ENCODING . '.php';
if (file_exists($lc_file))
{
	include_once($lc_file);
	// NOTE: Apply localization data
	LC::apply();
}

if (!function_exists('json_encode'))
	if (!class_exists('Services_JSON'))
		throw new Exception(LC::get_err('json_not_supported'));
?>
