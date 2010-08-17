<?php
// NOTE: Инициализация базовой части CMS
define('AUTH_MODE', 'public');
define('AUTH_DI', 'user');
include_once('base.php');
$args = request::get(array('user', 'secret'));
try
{
	if (!empty($args))
		authenticate::login();
}
catch(Exception $e)
{
	dbg::write($e->getMessage(), LOG_PATH . 'access.log');
	$data['errors'] = $e->getMessage();
}

if (authenticate::is_logged() && request::get('logout') == 'yes')
	authenticate::logout();
?>
