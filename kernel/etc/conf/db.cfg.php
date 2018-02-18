<?php
/**
*	Конфигурационные параметры для ИД
*
* @author	9* <9@u9.ru>
*/
class db_config
{
	public static $params = array(
		'localhost' => array(
			'type' => 'mysql',
			'host' => 'localhost',
			'persistent_connection'=>false,
			'charset' => CHARSET,
			'user' => 'diesel',
			'pass' => 'diesel',
			'debug'=> false,
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
