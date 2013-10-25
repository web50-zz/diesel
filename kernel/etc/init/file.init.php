<?php
try
{
	// NOTE: Start session
	session_start();

	// NOTE: The prefix of DI methods
	define('DI_CALL_PREFIX', PUB_PREFIX);

	// NOTE: Get file
	$di = data_interface::get_instance('fm_files');
	$di->call('get', request::get());
}
catch(Exception $e)
{
	dbg::write("UID: " . UID . "\nREQUEST_URI: {$_SERVER['REQUEST_URI']}\n" . $e->getMessage() . "\n" . $e->getTraceAsString(), LOG_PATH . 'di_errors.log');
	response::send('Error while process request.', 'error');
}
?>
