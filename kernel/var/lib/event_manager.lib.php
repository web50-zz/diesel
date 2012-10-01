<?php
/**
*	Library Event Manager
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class event_manager
{
	private static $registry = array();

	const REGISTRY_PATH = 'share/event_manager/data.php';
	
	public static function init()
	{
		include_once(BASE_PATH . self::REGISTRY_PATH);
	}

	public static function set_listeners($data)
	{
		self::$registry = $data;
	}

	/**
	*	Fire event
	*
	* @access	public
	* @param	object	$event_obj	The object who firing event
	* @param	string	$event_name	Event name
	* @param	array	$event_params	Event params passed into the arguments of callable listeners function
	* @param	array	$event_args	Event args passed as arguments callable interface
	*/
	public static function fire_event($event_obj, $event_name, $event_params = null, $event_args = null)
	{
		// If listeners array is empty, init it
		if (empty(self::$registry))
			self::init();

		// Detecting interface type
		if ($event_obj instanceof data_interface)
			$interface_type = 'di';
		else if ($event_obj instanceof user_interface)
			$interface_type = 'ui';
		else
			throw new Exception("Unknown interface type");

		// Get listeners for event
		$listeners = (array)self::$registry[$interface_type][$event_obj->interfaceName][$event_name];
		foreach ($listeners as $l)
		{
			if ($l['type'] == 'ui')
				$iObj = user_interface::get_instance($l['name']);
			else if ($l['type'] == 'di')
				$iObj = data_interface::get_instance($l['name']);

			if (method_exists($iObj, $l['handler']))
			{
				// If getted args, push args
				if ($event_args !== null)
					$iObj->push_args($event_args);
				/**
				*	Adding into the arguments of callable function
				* First the firing event object
				* Seconds events params
				* And the last users params
				*/
				$params = array_merge(array($event_obj), (array)$event_params, (array)$l['params']);
				// Call listener function
				if (call_user_func_array(array($iObj, $l['handler']), $params) === FALSE)
					throw new Exception("Can`t call {$l['name']}::{$l['handler']}");
				// If getted args, pop args
				if ($event_args !== null)
					$iObj->pop_args();
			}
			else
			{
				throw new Exception("The method `{$l['handler']}` of `{$l['name']}` not exist.");
			}
		}
	}
	
	// Регистрация сенсора (listener)
	public static function register_listeners()
	{
		$data = array();
		$int = data_interface::get_instance('interface');
		$interfaces = array(
			'ui' => $int->get_ui_name_array(),
			'di' => $int->get_di_name_array(),
		);
		foreach ($interfaces['ui'] AS $uiName => $props)
		{
			$uiObj = user_interface::get_instance($uiName);
			// If method listeners exists, then register listeners
			if (method_exists($uiObj, '_listeners'))
			{
				foreach ((array)$uiObj->_listeners() as $l)
				{
					if ($l['di'])
					{
						$data['di'][$l['di']][$l['event']][] = array(
							'type' => 'ui',
							'name' => $uiName,
							'handler' => $l['handler'],
							'params' => $l['params'],
						);
					}
					else if ($l['ui'])
					{
						$data['ui'][$l['ui']][$l['event']][] = array(
							'type' => 'ui',
							'name' => $uiName,
							'handler' => $l['handler'],
							'params' => $l['params'],
						);
					}
				}
			}
		}
		foreach ($interfaces['di'] AS $diName => $props)
		{
			$diObj = data_interface::get_instance($diName);
			// If method listeners exists, then register listeners
			if (method_exists($diObj, '_listeners'))
			{
				foreach ((array)$diObj->_listeners() as $l)
				{
					if ($l['di'])
					{
						$data['di'][$l['di']][$l['event']][] = array(
							'type' => 'di',
							'name' => $diName,
							'handler' => $l['handler'],
							'params' => $l['params'],
						);
					}
					else if ($l['ui'])
					{
						$data['ui'][$l['ui']][$l['event']][] = array(
							'type' => 'di',
							'name' => $diName,
							'handler' => $l['handler'],
							'params' => $l['params'],
						);
					}
				}
			}
		}
		$file = BASE_PATH . self::REGISTRY_PATH;
		$dir = dirname($file);
		// Create share folder if not exists
		if (!file_exists($dir))
			mkdir($dir, 0775, true);

		$fh = fopen($file, 'w');
		fwrite($fh, "<?php\n");
		fwrite($fh, "event_manager::set_listeners(" . var_export($data, 1) . ");\n");
		fwrite($fh, "?>");
		fclose($fh);
	}
}
