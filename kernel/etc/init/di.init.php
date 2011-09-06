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
	/* 9* старый вариант не рубил отдачу файлов по сслке /files/?id=6 например в случае  если юзер не залогинен
	 и вот потому  дополнительно условие если аутх мое публик то на pub_ не будем руибить доступ ибо это публик по дефолту


	if (defined('AUTH_DI') && !authenticate::is_logged())
	*/
	if (defined('AUTH_DI') && !authenticate::is_logged()&&AUTH_MODE != 'public')//9* new 30052011 см выше коменты
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
	dbg::write("REQUEST_URI: {$_SERVER['REQUEST_URI']}\n" . $e->getMessage() . "\n" . $e->getTraceAsString(), LOG_PATH . 'di_errors.log');
	response::send('Error while process request.', 'error');
}
?>
