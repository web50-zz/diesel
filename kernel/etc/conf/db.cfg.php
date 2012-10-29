<?php
/**
*	Конфигурационные параметры для ИД
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @version	2.0
* @access	public
* @package	SBIN DIESEL	
* @since	12-09-2008
*/
class db_config
{
	public static $params = array(
		'localhost' => array(
			'type' => 'mysql',
			'host' => 'localhost',
			'charset' => CHARSET,
			'user' => 'diesel',
			'pass' => 'diesel',
			'dbs' => array(
				'db1' => 'diesel',
				)
			),
		'session' => array(
			'type' => 'session',
		)
	);
}
?>
