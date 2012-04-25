<?php
/**
*	Public site initialization
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @access	public
* @package	SBIN DIESEL	
*/
try
{
	$uri = (empty($_SERVER['REDIRECT_URL'])) ? '/' : $_SERVER['REDIRECT_URL'];
        $diStrc = data_interface::get_instance(SITE_DI);
        $page = $diStrc->get_page_by_uri($uri);
	define(PAGE_URI, $page['uri']);
	define(PAGE_NAME, $page['name']);
	define(SRCH_URI, str_replace($page['uri'], "", $uri));
	define(PAGE_ID, $page['id']);
	
	if (!empty($page['redirect']))
	{
		response::redirect($page['redirect']);
	}
	else
	{
                $uiStrc = user_interface::get_instance(SITE_UI);
                $uiStrc->set_args(request::get());
                $uiStrc->process_page($page);
	}
}
catch(Exception $e)
{
	dbg::write('Error: '.$e->getMessage());
	//response::header('404');
	//9* 28102010
	try
	{
		$out = user_interface::get_instance('action_page');
		$out->set_args(array(
			'action_msg' => $e->getMessage()
		));
		return $out->render();
	}
	catch(Exception $e)
	{
		dbg::write('Error: '.$e->getMessage());
		echo('<center>WWW ENGINE  IS NOT PRESENTED</center>');
	}
}
?>
