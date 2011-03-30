<?php
// NOTE: Инициализация базовой части административного интерфейса.
include_once('adm_base.php');

try
{
	if (request::get('cll') == 'logout')
	{
		authenticate::logout();
		response::redirect('/xxx/');
	}
	else
	{
		$ui = user_interface::get_instance('login');
		$ui->admin();
	}
}
catch(Exception $e)
{
	dbg::write($e->getMessage(), LOG_PATH . 'ui_errors.log');
	response::header('404');
}
?>
