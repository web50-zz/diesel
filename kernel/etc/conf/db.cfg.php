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
