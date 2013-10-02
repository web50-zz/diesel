<?php
// NOTE: Start session
session_start();

// NOTE: Data Interface for user authorisation
define('AUTH_DI', 'user');

// NOTE: The prefix of UI methods
define('UI_CALL_PREFIX', ADM_PREFIX);

// NOTE: The prefix of DI methods
define('DI_CALL_PREFIX', ADM_PREFIX);

/* .htaccess Example
RewriteRule	^xxx/$				adm_gui.php?ui=administrate&cll=workspace	[L,QSA]
RewriteRule	^xxx/(.*)/$			adm_$1.php					[L,QSA]
RewriteRule	^xxx/ui/([^/]+)/(.*)\.[a-z]+$	adm_gui.php?ui=$1&cll=$2			[L,QSA]
RewriteRule	^xxx/di/([^/]+)/(.*)\.[a-z]+$	adm_data.php?di=$1&cll=$2			[L,QSA]
*/

// Получаем текущий префикс админки
$prefix = request::get('_uri_prefix');

// Склеиваем префикс для preg_match
$preg_prefix = str_replace('/', '\/', $prefix);

// Получаем текущий URI
$uri = request::get('_uri', $prefix);

if ($uri == $prefix)
{
	// NOTE: Вызов административной части
	call_ui('administrate', 'workspace');
}
else if (preg_match("/^{$preg_prefix}([^\/]*)\/$/", $uri, $match))
{
	if ($match[1] == 'login')
	{
		$ui = user_interface::get_instance('login');
		$ui->admin();
	}
	else if ($match[1] == 'logout')
	{
		authenticate::logout();
		response::redirect("/{$prefix}");
	}
	else 
	{
		response::header('404', 'Not Found.');
	}
}
else if (preg_match("/^{$preg_prefix}ui\/([^\/]*)\/(.*)(?:\..+)$/", $uri, $match))
{
	call_ui($match[1], $match[2]);
}
else if (preg_match("/^{$preg_prefix}di\/([^\/]*)\/(.*)(?:\..+)$/", $uri, $match))
{
	call_di($match[1], $match[2]);
}
else
{
	response::header('404', 'Not Found.');
}

/**
*	Вызов UI
*/
function call_ui($name, $call)
{
	try
	{
		// NOTE: If defined authentication data interface and user not logged in
		if (defined('AUTH_DI') && !authenticate::is_logged())
		{
			// Then redicrect to login form
			response::redirect('login/');
		}
		
		// Call user interface
		$ui = user_interface::get_instance($name);

		if (($content = $ui->call($call, request::get())) === FALSE)
			response::header('404', 'Not Found.');
		else
			response::send($content, 'html');
	}
	catch(Exception $e)
	{
		dbg::write($e->getMessage() . "\n" . $e->getTraceAsString(), LOG_PATH . 'ui_errors.log');
		response::header('404', 'Not Found.');
	}
}

/**
*	Вызов DI
*/
function call_di($name, $call)
{
	try
	{
		// NOTE: If defined authentication data interface and user not logged in
		if (defined('AUTH_DI') && !authenticate::is_logged())
		{
			// Then send error
			response::send('Session closed. Authorization needed.', 'error');
		}

		$di = data_interface::get_instance($name);

		// If return FALSE then access denied
		if (!$di->call($call, request::get()))
			response::send('Access denied.', 'error');
	}
	catch(Exception $e)
	{
		dbg::write("UID: " . UID . "\nREQUEST_URI: {$_SERVER['REQUEST_URI']}\n" . $e->getMessage() . "\n" . $e->getTraceAsString(), LOG_PATH . 'di_errors.log');
		response::send('Error while process request.', 'error');
	}
}
?>
