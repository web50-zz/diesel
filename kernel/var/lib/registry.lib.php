<?php
/**
*	Library Registry
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class registry
{
	private static $storage = array();
	
	public static function get($name)
	{
		if (!isset(self::$storage[$name]))
		{
			$di = data_interface::get_instance('registry');
			self::$storage[$name] = $di->get($name);
		}
		$rec = self::$storage[$name];
		
		switch ($rec->type)
		{
			case 1:
				return (string)$rec->value;
			break;
			case 2:
				return json_decode($rec->value);
			break;
			case 3:
				$cfg = $rec->value;
				if (!empty($cfg->di))
				{
					$di = data_interface::get_instance($cfg->di);
					return $di->call($cfg->ep, json_decode($cfg->params));
				}
				else if (!empty($cfg->ui))
				{
					$ui = user_interface::get_instance($cfg->ui);
					return $ui->call($cfg->ep, json_decode($cfg->params));
				}
				else
				{
					throw new Exception("Unknown call configuration for registry record '{$name}'");
				}
			break;
			default:
				throw new Exception("Unknown registry record type for '{$name}'");
		}
	}
}
?>
