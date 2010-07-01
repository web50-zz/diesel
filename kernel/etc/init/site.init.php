<?php
/**
*	Public site initialization
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @access	public
* @package	FlugerCMS
*/
try
{
	$uri = (empty($_SERVER['REDIRECT_URL'])) ? '/' : $_SERVER['REDIRECT_URL'];
	define(PAGE_URI, $uri);
        $diStrc = data_interface::get_instance('structure');
        $page = $diStrc->get_page_by_uri($uri);
	define(PAGE_ID, $page['id']);
	
	if (!empty($page['redirect']))
	{
		response::redirect($page['redirect']);
	}
	else
	{
                $uiStrc = user_interface::get_instance('structure');
                $uiStrc->set_args(request::get());
                $uiStrc->process_page($page);
	}
}
catch(Exception $e)
{
	dbg::write('Error: '.$e->getMessage());
	response::header('404');
}
?>
