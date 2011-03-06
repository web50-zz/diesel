<?php
/**
*	Конфигурационные параметры для работы с инстансами 
*
* @author	9*
* @version	2.0
* @access	public
* @package      SBIN DIESEL	
* @since	05-07-2010
*/
$instances = array(
);

// NOTE: 9* 05072010 Path to store Instance code
define ('INSTANCES_PATH', BASE_PATH . 'instances/' );

$INST_R = array(
	'instances_path' => array(),
	'paths' => array()
);

foreach ($instances as $name)
{
	$INST_R['instances_path'][] = array(
		'ui_path' =>  INSTANCES_PATH . $name . '/var/ui/',
		'di_path' =>  INSTANCES_PATH . $name . '/var/di/',
		'dump_path' =>  INSTANCES_PATH . $name . '/var/dump/',
		'instance_name' => $name 
	);
}
?>
