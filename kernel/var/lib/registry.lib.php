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
	
	public static function get($name,$default = 0)
	{
		if (!isset(self::$storage[$name]))
		{
			$di = data_interface::get_instance('registry');
			self::$storage[$name] = $di->get($name);
		}
		$rec = self::$storage[$name];
		// If empty registry key return null
		if(empty($rec) && $default != 0)
		{
			return $default;
		}
		if (empty($rec))
		{
			return null;
		}
		
		switch ($rec->type)
		{
			case 1:
				return (string)$rec->value;
			break;
			case 2:
				$data = json_decode($rec->value, true);
				return $data;
				/* на некоторых хостингах вот это все не работает и вызывает ошибки
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
				*/
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
