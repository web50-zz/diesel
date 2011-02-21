<?php
/**
*	Конфигурационные параметры для работы с файлами
*
* @author	9*
* @version	0.1
* @access	public
* @package	CFsCMS2(PE)
* @since	05-07-2010
*/
$instance = 'market';
// NOTE: Путь к хранилищу файлов
// NOTE: 9* 05072010 Path to store Instance code
define ('INSTANCES_PATH', BASE_PATH . 'instances/' );
define ('CURRENT_INSTANCE_PATH', INSTANCES_PATH . $instance.'/');
define ('INSTANCE_UI_PATH', INSTANCES_PATH . $instance.'/var/ui/');
define ('INSTANCE_DI_PATH', INSTANCES_PATH . $instance.'/var/di/');
define ('RELATIVE_INSTANCE_UI_PATH','instances/'. $instance.'/var/ui/');
?>
