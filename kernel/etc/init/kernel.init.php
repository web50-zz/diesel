<?php
/**
*	Main initialization
*
* @author	Anthon S. Litvinenko <crazyfluger@gmail.com>
* @version	2.0
* @access	public
* @package	SBIN Diesel	
*/

/**
*	Autoloading data and user interfaces, libraries, connectors and base classes
* @param		string	$class_name	The class name
*/
function resource_loader($class_name)
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
				$INST_R['class_instance'][$class_name] = $value['instance_name'];
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
			{
				$file_name = $file_name_instance;
				$INST_R['paths'][$class_name] = $value['ui_path'];
				$INST_R['class_instance'][$class_name] = $value['instance_name'];
			}
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
	else if (preg_match('/^lib_(\w+)/', $class_name, $matches))// 9* 21.04.2019  чтобы либы из инстансов грузить автоматом
	{
		foreach($INST_R['instances_path'] as $value)
		{
			if (file_exists($value['lib_path'] . $class_name . LIB_FEXT))
			{
				$file_name = $value['lib_path'] . $class_name . LIB_FEXT;
			}
		}
	}
	// NOTE: 9* 05072010 including  if exists
	if (file_exists($file_name))
	{
		include_once($file_name);
	}	
	else
	{
		// Not compatible with PHPExcel autoloader
		//throw new Exception("Can`t load '{$class_name}'");
	}
}

// Register kernel resource loader
spl_autoload_register('resource_loader');

//9* 22022011 possible configs
$conf_types =  array(
	'db',		// NOTE: Include data bases configurations
	'fs',		// NOTE: Include file storages configurations
	'theme',	// NOTE: 9* 05072010 Include current theme  configuration
	'instance',	// NOTE: 9* 05072010 Include current instance  configuration
	'site',		// NOTE: 9* 05072010 Include current instance  configuration
	'cache',	// NOTE: 9* 18102010 Include cache configurations
	'dump',		// NOTE: Anthon S Litvinenko [2010-03-03] Include dump configuration
	'uri',		// NOTE: Anthon S Litvinenko [2013-10-01] Include uri configuration
);

//9* choosing which one to load
foreach($conf_types as $key=>$value)
{
	$etc_file = CONF_ETC_PATH.$value.CONF_FEXT;
	$kernel_file = CONF_PATH . $value . CONF_FEXT;
	if(file_exists($etc_file))//9* /etc (if exists) has priority against kernel defaults 
	{
		include_once($etc_file);
	}
	elseif(file_exists($kernel_file))
	{
		include_once($kernel_file);
	}
}

//9* adjast instances configs
$INST_R = array(
	'instances_path' => array(),
	'paths' => array(),
	'paths_assoc' => array(),
	'class_instance'=>array()
);

foreach ($instances as $name)
{
	$data = array(
		'ui_path' =>  INSTANCES_PATH . $name . '/var/ui/',
		'di_path' =>  INSTANCES_PATH . $name . '/var/di/',
		'dump_path' =>  INSTANCES_PATH . $name . '/var/dump/',
		'lib_path' => INSTANCES_PATH . $name . '/var/lib/',
		'instance_name' => $name
		); 

	$INST_R['instances_path'][] = $data;
	$INST_R['paths_assoc'][$name] = $data ;
	
	$def_etc_file = INSTANCES_PATH .$name.'/etc/inst_'.$name.CONF_FEXT;
	$etc_file = CONF_ETC_PATH . 'inst_'.$name . CONF_FEXT;
	//9* init config files for instance
	if(file_exists($def_etc_file))//9* /etc (if exists) has priority against kernel defaults 
	{
		include_once($def_etc_file);
	}
	elseif(file_exists($etc_file))
	{
		include_once($etc_file);
	}

}
//9* end of instances configs

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

// NOTE: Include Switft Mail library
include_once(LIB_PATH . 'Swift/swift_required.php');

// NOTE: Обработка uri и передача управления в указанный файл
if (!empty($uri_configuration) && !$STOP_URI_INTERPRETER)
{
	// Получаем URI
	//define('URI', request::get('_uri', ''));
	//	$uri = (empty($_SERVER['REDIRECT_URL'])) ? '/' : $_SERVER['REDIRECT_URL']; //9* 2018-08-25 это не работает в  php как CGI вместо этого надо REQUEST_URI
	$uri = (empty($_SERVER['REQUEST_URI'])) ? '/' : $_SERVER['REQUEST_URI'];
	$parts = explode('?',$uri); //режем куски потому что это может содержать GET, его мы откинем. 
	define('URI', $parts[0]);
	// Перебираем конфигурацию
	foreach ($uri_configuration as $regexp => $handler)
	{
		// Проверяем на соответствие шаблону
		if (preg_match($regexp, URI, $match))
		{
			// Запоминаем результат preg_match
			$_REQUEST['_uri_match'] = $match;

			// Убираем первый для формирования префикса, ибо это общий match по URI
			array_shift($match);

			if (!empty($match))
			{
				// Склеиваем префикс и запоминаем его
				define('URI_PREFIX', '/' . join('/', $match) . '/');
			}
			else
			{
				// Иначе устанавливаем дефолтный "/"
				define('URI_PREFIX', '/');
			}

			// Если управляющий файл найден
			if (file_exists($handler))
			{
				// Вызываем его и прерываем цикл
				include_once($handler);
				break;
			}
			// Иначе генерируем Exception
			else
			{
				throw new Exception("{$handler} not exists.");
			}
		}
	}
}
?>
