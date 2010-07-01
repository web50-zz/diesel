<?php
/**
*	Debuger
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @version	0.1
* @access	public
* @package	CFsCMS2(PE)
* @since	23-10-2008
*/
class dbg
{
	public function __constructor()
	{
	}
	
	/**
	*	Сохранить значение / сообщение
	*
	* @param	mixed	$value		Значение / сообщение
	* @param	string	$file		Путь к файлу, в который будет произведена запись
	*/
	public static function write($value, $file = NULL)
	{
		$log_file = ($file) ? $file : LOG_PATH . 'debug.log';
		$fh = fopen($log_file, 'a');
		fwrite($fh, date('Y-m-d H:i:s ============>' . "\n"));
		fwrite($fh, var_export($value, true) . "\n");
		fclose($fh);
	}
	
	/**
	*	Отобразить значение / сообщение
	*
	* @param	mixed	$value		Значение / сообщение
	* @param	string	$message	Заголовок
	* @param	string	$color		Цвет фона
	*/
	public static function show($value, $message = NULL, $color = '#99ccff')
	{
		echo '<div style="background-color: ' . $color . '">';
		echo '<fieldset>';
		if ($message) echo '<legend>' . $message . '</legend>';
		echo '<pre>';
		print_r($value);
		echo '</pre>';
		echo '</fieldset>';
		echo '</div>';
	}
	
	/**
	*	Отобразить значение переменной / объекта
	*
	* @param	mixed	$value		Значение / сообщение
	* @param	string	$message	Заголовок
	* @param	string	$color		Цвет фона
	*/
	public static function dump($value, $message = NULL, $color = '#ffffcc')
	{
		echo '<div style="background-color: ' . $color . '">';
		echo '<fieldset>';
		if ($message) echo '<legend>' . $message . '</legend>';
		echo '<pre>';
		var_dump($value);
		echo '</pre>';
		echo '</fieldset>';
		echo '</div>';
	}
	
	/**
	*	Отобразить значение переменной / массива
	*
	* @param	mixed	$value		Значение / сообщение
	* @param	string	$message	Заголовок
	* @param	string	$color		Цвет фона
	*/
	public static function html_dump($value, $message = NULL, $color = '#99ffcc')
	{
		echo '<div style="background-color: ' . $color . '">';
		echo '<fieldset>';
		if ($message) echo '<legend>' . $message . '</legend>';
		echo '<pre>';
		if (is_array($value))
		{
			self::html_dump_recursive($value);
		}
		else
		{
			echo htmlspecialchars($value);
		}
		echo '</pre>';
		echo '</fieldset>';
		echo '</div>';
	}
	
	public static function html_dump_recursive($value)
	{
		foreach ($value as $k => $v)
		{
			echo '<span style="background-color: #99ffcc">[' . $k . '] => </span>';
			echo '<span style=" border: 1px #aaa solid; background-color: #ffffcc">';
			if (is_array($v))
			{
				self::html_dump_recursive($v);
			}
			else
			{
				echo htmlspecialchars($v);
			}
			echo  '</span>' . "\n";
		}
	}
}
?>
