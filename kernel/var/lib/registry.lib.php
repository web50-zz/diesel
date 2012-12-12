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
		// If empty registry key return null
		if (empty($rec)) return null;
		
		switch ($rec->type)
		{
			case 1:
				return (string)$rec->value;
			break;
			case 2:
				$data = json_decode($rec->value, true);
				switch (json_last_error())
				{
					case JSON_ERROR_NONE:
						return $data;
					break;
					case JSON_ERROR_DEPTH:
						dbg::write($rec->value . ' - Maximum stack depth exceeded');
					break;
					case JSON_ERROR_STATE_MISMATCH:
						dbg::write($rec->value . ' - Underflow or the modes mismatch');
					break;
					case JSON_ERROR_CTRL_CHAR:
						dbg::write($rec->value . ' - Unexpected control character found');
					break;
					case JSON_ERROR_SYNTAX:
						dbg::write($rec->value . ' - Syntax error, malformed JSON');
					break;
					case JSON_ERROR_UTF8:
						dbg::write($rec->value . ' - Malformed UTF-8 characters, possibly incorrectly encoded');
					break;
					default:
						dbg::write($rec->value . ' - Unknown error');
					break;
				}
				return false;
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
				//return (string)$rec->value; //9* пусть стринг по дефолту, да и не будет ругаться если нет ключа.
				// A.Litvinenko: Это не проверка на наличие ключа, это проверка на тип ключа.
				throw new Exception("Unknown registry record type for '{$name}'");
		}
	}
}
?>
