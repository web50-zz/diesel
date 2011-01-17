<?php
/**
*	Конфигурационные параметры для ИД
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @version	2.0
* @access	public
* @package	CFsCMS2(PE)
* @since	12-09-2008
*/
class db_config
{
	public static $params = array(
		'localhost' => array(
			'type' => 'mysql',
			'host' => 'localhost',
			'charset' => CHARSET,
			'user' => 'web50ru',
			'pass' => 'web50ru',
			'dbs' => array(
				'db1' => 'web50ru',
				)
			),
		'session' => array(
			'type' => 'session',
		)
	);
}
?>
