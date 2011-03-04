#!/usr/bin/env php5
<?php
// NOTE: Base initialization
include_once('base.php');

// NOTE: The prefix of UI methods
define('UI_CALL_PREFIX', ADM_PREFIX);

$int = data_interface::get_instance('interface');
$dis = $int->get_di_array();
//var_dump($dis);
foreach ($dis AS $name => $cfg)
{
	try
	{
		$di = $cfg['obj'];
		$di->make_dump();
		echo "{$name} ({$di->title}): Done\n";
	}
	catch(Exception $e)
	{
		echo "{$name} ({$di->title}): ERROR\n";
		echo $e->getMEssage() . "\n";
	}
}
?>
