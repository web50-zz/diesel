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

$INST_R = array();
$INST_R['paths'] = array();
// NOTE: 9* 05072010 Path to store Instance code
define ('INSTANCES_PATH', BASE_PATH . 'instances/' );
foreach($instances as $key=>$value)
{
	$tmp = array();
	$tmp['instance_ui_path'] =  INSTANCES_PATH . $value.'/var/ui/';
	$tmp['instance_di_path'] =  INSTANCES_PATH . $value.'/var/di/';
	array_push($INST_R,$tmp);
}
?>
