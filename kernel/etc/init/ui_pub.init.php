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
	// Call user interface
	$ui = user_interface::get_instance(request::get('ui', UI_DEFAULT));

	if (($content = $ui->call(request::get('cll'), request::get())) === FALSE)
		response::header('404');
	else
		response::send($content, 'html');
}
catch(Exception $e)
{
	dbg::write($e->getMessage(), LOG_PATH . 'ui_errors.log');
	//response::header('404');
	//9* 28102010
	$out = user_interface::get_instance('action_page');
	$out->set_args(array(
		'action_msg' => $e->getMessage()
	));
	return $out->render();
}
?>
