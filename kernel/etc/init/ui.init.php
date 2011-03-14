<?php
/**
*	The user interface initialization code
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @version	2.0
* @access	public
* @package	CFsCMS2(PE)
*/
try
{
	// NOTE: If defined authentication data interface and user not logged in
	if (defined('AUTH_DI') && !authenticate::is_logged())
	{
		// Then redicrect to login form
		response::redirect('login/');
	}
	
	// Call user interface
	$ui = user_interface::get_instance(request::get('ui', UI_DEFAULT));
	if (($content = $ui->call(request::get('cll', UI_CLL_DEFAULT), request::get())) === FALSE)
		response::header('404');
	else
		response::send($content, 'html');
}
catch(Exception $e)
{
	dbg::write($e->getMessage() . "\n" . $e->getTraceAsString(), LOG_PATH . 'ui_errors.log');
	response::header('404');
}
?>
