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
	global $INST_R;
	if (!$class_name) return FALSE;
	
	if (file_exists(CLASS_PATH . $class_name . CLASS_FEXT))
	{
		$file_name = CLASS_PATH . $class_name . CLASS_FEXT;
	}
	else if (preg_match('/^' . UI_CLASS_PREFIX . '(\w+)/', $class_name, $matches))
	{
		foreach ($INST_R['instances_path'] as $value)
		{
			$file_name_instance = $value['ui_path'] . $matches[1] . '/' . $matches[1] . UI_FEXT;

			if (file_exists($file_name_instance))
			{
				$file_name = $file_name_instance;
				//9* 25022011 required for UI tmpl resources path detection in UI CLASS proto
				$INST_R['paths'][$class_name] = $value['ui_path'];
			}
		}

		if (!$file_name)
		{
			$file_name = UI_PATH . $matches[1] . '/' . $matches[1] . UI_FEXT;
			//DEFAULT 9* 25022011 required for UI tmpl resources path detection in UI CLASS proto
			$INST_R['paths'][$class_name] = UI_PATH;
		}
	}
	else if (preg_match('/^' . DI_CLASS_PREFIX . '(\w+)/', $class_name, $matches))
	{
		foreach ($INST_R['instances_path'] as $value)
		{
			$file_name_instance = $value['di_path'] . $matches[1] . DI_FEXT;

			if (file_exists($file_name_instance))
				$file_name = $file_name_instance;
		}

		if (!$file_name)
			$file_name = DI_PATH . $matches[1] . DI_FEXT;
	}
	else if (preg_match('/^' . CONNECTOR_CLASS_PREFIX . '(\w+)/', $class_name, $matches))
	{
		$file_name = CONNECTOR_PATH . $matches[1] . CONNECTOR_FEXT;
	}
	else if (file_exists(LIB_PATH . $class_name . LIB_FEXT))
	{
		$file_name = LIB_PATH . $class_name . LIB_FEXT;
	}

	// NOTE: 9* 05072010 including  if exists
	if (file_exists($file_name))
	{
		include_once($file_name);
	}	
}

//9* 22022011 possible configs
$conf_types =  array(
		'db',// NOTE: Include data bases configurations
		'fs',// NOTE: Include file storages configurations
		'theme',// NOTE:9* 05072010 Include current theme  configuration
		'instance',// NOTE:9* 050702010 Include current instance  configuration
		'site',// NOTE:9* 05072010 Include current instance  configuration
		'cache'// NOTE:9* 18102010 Include cache configurations
		);
//9* choosing which one to load
foreach($conf_types as $key=>$value)
{
	$etc_file = CONF_ETC_PATH.$value.CONF_FEXT;
	if(file_exists($etc_file))//9* /etc (if exists) has priority against kernel defaults 
	{
		include_once($etc_file);
	}
	else
	{
		include_once(CONF_PATH . $value . CONF_FEXT);
	}
}


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
