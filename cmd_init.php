#!/usr/bin/env php5
<?php
// NOTE: Base initialization
include_once('base.php');

// NOTE: The prefix of UI methods
define('UI_CALL_PREFIX', ADM_PREFIX);

$int = data_interface::get_instance('util_db');

$path_di = DI_PATH ;
$path_dump = DUMP_PATH.$subfold;
$int->perform_init(3,$path_di,$path_dump);
echo " =====  done =====\n";
?>
