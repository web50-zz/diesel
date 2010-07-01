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
		$file_name = CLASS_PATH . $class_name . CLASS_FEXT;
	else if (preg_match('/^' . UI_CLASS_PREFIX . '(\w+)/', $class_name, $matches))
		$file_name = UI_PATH . $matches[1] . '/' . $matches[1] . UI_FEXT;
	else if (preg_match('/^' . DI_CLASS_PREFIX . '(\w+)/', $class_name, $matches))
		$file_name = DI_PATH . $matches[1] . DI_FEXT;
	else if (preg_match('/^' . CONNECTOR_CLASS_PREFIX . '(\w+)/', $class_name, $matches))
		$file_name = CONNECTOR_PATH . $matches[1] . CONNECTOR_FEXT;
	else if (file_exists(LIB_PATH . $class_name . LIB_FEXT))
		$file_name = LIB_PATH . $class_name . LIB_FEXT;
	
	if (file_exists($file_name))
		include_once($file_name);
}

// NOTE: Include data bases configurations
include_once(CONF_PATH . 'db' . CONF_FEXT);

// NOTE: Include file storages configurations
include_once(CONF_PATH . 'fs' . CONF_FEXT);

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
