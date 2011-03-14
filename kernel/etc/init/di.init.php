<?php
/**
*	The data interface initialization code
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @version	2.0
* @access	public
* @package	SBIN Diesel	
*/
try
{
	// NOTE: If defined authentication data interface and user not logged in
	if (defined('AUTH_DI') && !authenticate::is_logged())
	{
		// Then send error
		response::send('Session closed. Authorization needed.', 'error');
	}

	$di = data_interface::get_instance(request::get('di'));
	// If return FALSE then access denied
	if (!$di->call(request::get('cll'), request::get()))
		response::send('Access denied.', 'error');
}
catch(Exception $e)
{
	dbg::write($e->getMessage() . "\n" . $e->getTraceAsString(), LOG_PATH . 'di_errors.log');
	response::send('Error while process request.', 'error');
}
?>
